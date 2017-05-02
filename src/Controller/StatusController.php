<?php

namespace BBC\CliftonBundle\Controller;

use BBC\ProgrammesPagesService\Domain\ValueObject\Pid;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use DateTime;
use Doctrine\DBAL\ConnectionException as ConnectionExceptionDBAL;
use Doctrine\DBAL\Exception\ConnectionException;
use Doctrine\DBAL\Exception\DriverException;
use Symfony\Component\Debug\Exception\ContextErrorException;
use Doctrine\DBAL\DBALException;

/**
 * Class StatusController
 *
 * @package BBC\CliftonBundle\Controller
 */
class StatusController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     */
    public function statusAction(Request $request)
    {
        // If the load balancer is pinging us then give them a plain OK
        $hasDBIssues = $this->testForNonConnectionDatabaseIssues();
        if ($request->headers->get('User-Agent') == 'ELB-HealthChecker/1.0') {
            if ($hasDBIssues) {
                return new Response('ERROR', Response::HTTP_INTERNAL_SERVER_ERROR, ['content-type' => 'text/plain']);
            }
            return new Response('OK', Response::HTTP_OK, ['content-type' => 'text/plain']);
        }

        // Other people get a better info screen
        $dbalConnection = $this->get('doctrine.dbal.default_connection');

        return $this->render('@Clifton/Status/status.html.twig', [
            'now' => new DateTime(),
            'dbConnectivity' => $dbalConnection->isConnected() || $dbalConnection->connect(),
            'dbErrorCheck' => $hasDBIssues,
        ]);
    }

    /**
     * This insanity is copy/pasted from Faucet. It detects whether an Exception indicates that the
     * database is down. No, there is no single exception for that.
     * When the database is down we return a 200 status. Other DB exceptions return a 500 status.
     * See programmes ticket https://jira.dev.bbc.co.uk/browse/PROGRAMMES-5534
     */
    private function testForNonConnectionDatabaseIssues()
    {
        try {
            $pid = new Pid('b006m86d'); //Eastenders
            $programme = $this->get('pps.programmes_service')->findByPidFull($pid);
        } catch (ConnectionExceptionDBAL | ConnectionException $e) {
            return false;
        } catch (\PDOException $e) {
            if ($e->getCode() === 0 && stristr($e->getMessage(), 'There is no active transaction')) {
                // I am aware of how horrible this is. PDOExcetion is very generic. The only
                // way I can see to be specific to the case of "DB server went down"
                // is to do a string compare on the error message.
                return false;
            } elseif ($e->getCode() == 2002) {
                // Connection timeout
                return false;
            }
            return true;
        } catch (DriverException $e) {
            if ($e->getErrorCode() == 1213 || $e->getErrorCode() == 1205) {
                // This is thrown on a MySQL deadlock error 1213 or 1205 lock wait timeout. We catch it
                // and exit with a zero exit status allowing the processor
                // to restart
                return false;
            } elseif ($e->getErrorCode() == 2006) {
                // General error: 2006 MySQL server has gone away
                return false;
            }
            return true;
        } catch (DBALException | ContextErrorException $e) {
            $msg = $e->getMessage();
            if ($e->getCode() === 0 &&
                (stristr($msg, 'server has gone away') || stristr($msg, 'There is no active transaction.'))
            ) {
                // This is what happens when the SQL server goes away while the process is active
                return false;
            }
            return true;
        } catch (\Exception $e) {
            return true;
        }
        return false;
    }
}
