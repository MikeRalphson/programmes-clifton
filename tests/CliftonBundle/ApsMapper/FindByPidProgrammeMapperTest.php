<?php

namespace Tests\BBC\CliftonBundle\ApsMapper;

use BBC\ProgrammesPagesService\Domain\Enumeration\MediaTypeEnum;
use BBC\ProgrammesPagesService\Domain\Entity\Brand;
use BBC\ProgrammesPagesService\Domain\Entity\Clip;
use BBC\ProgrammesPagesService\Domain\Entity\Episode;
use BBC\ProgrammesPagesService\Domain\Entity\Format;
use BBC\ProgrammesPagesService\Domain\Entity\Genre;
use BBC\ProgrammesPagesService\Domain\Entity\Series;
use BBC\ProgrammesPagesService\Domain\Entity\Image;
use BBC\ProgrammesPagesService\Domain\Entity\MasterBrand;
use BBC\ProgrammesPagesService\Domain\Entity\Network;
use BBC\ProgrammesPagesService\Domain\ValueObject\Mid;
use BBC\ProgrammesPagesService\Domain\ValueObject\Nid;
use BBC\ProgrammesPagesService\Domain\ValueObject\Pid;
use BBC\ProgrammesPagesService\Domain\ValueObject\PartialDate;
use BBC\ProgrammesPagesService\Domain\ValueObject\Synopses;
use BBC\CliftonBundle\ApsMapper\FindByPidProgrammeMapper;
use DateTime;
use DateTimeImmutable;
use PHPUnit_Framework_TestCase;

class FindByPidProgrammeMapperTest extends PHPUnit_Framework_TestCase
{
    public function testMappingBrand()
    {
        $brand = new Brand(
            1,
            new Pid('b006q2x0'),
            'Doctor Who',
            'Search Title',
            new Synopses('Short Synopsis', 'Medium Synopsis', 'Long Synopsis'),
            new Image(new Pid('p01m5mss'), 'Title', 'ShortSynopsis', 'ShortSynopsis', 'standard', 'jpg'),
            0,
            1,
            false,
            false,
            0,
            0,
            11,
            0,
            0,
            false,
            null,
            101,
            null,
            [],
            [],
            1001
        );

        $expectedOutput = (object) [
            'type' => 'brand',
            'pid' => 'b006q2x0',
            'expected_child_count' => 1001,
            'position' => 101,
            'image' => (object) ['pid' => 'p01m5mss'],
            'media_type' => null,
            'title' => 'Doctor Who',
            'short_synopsis' => 'Short Synopsis',
            'medium_synopsis' => 'Medium Synopsis',
            'long_synopsis' => 'Long Synopsis',
            'first_broadcast_date' => null,
            'display_title' => (object) [
                'title' => 'Doctor Who',
                'subtitle' => '',
            ],
            'links' => [],
            'supporting_content_items' => [],
            'categories' => [],
        ];

        $mapper = new FindByPidProgrammeMapper();
        $this->assertEquals($expectedOutput, $mapper->getApsObject($brand));
    }

    public function testMappingSeries()
    {
        $series = new Series(
            1,
            new Pid('b06hgxtt'),
            'Series 9 - Omnibus',
            'Search Title',
            new Synopses('Short Synopsis', 'Medium Synopsis', 'Long Synopsis'),
            new Image(new Pid('p01m5mss'), 'Title', 'ShortSynopsis', 'ShortSynopsis', 'standard', 'jpg'),
            0,
            1,
            false,
            false,
            0,
            0,
            11,
            0,
            0,
            false,
            null,
            101,
            null,
            [],
            [],
            1001
        );

        $expectedOutput = (object) [
            'type' => 'series',
            'pid' => 'b06hgxtt',
            'expected_child_count' => 1001,
            'position' => 101,
            'image' => (object) ['pid' => 'p01m5mss'],
            'media_type' => null,
            'title' => 'Series 9 - Omnibus',
            'short_synopsis' => 'Short Synopsis',
            'medium_synopsis' => 'Medium Synopsis',
            'long_synopsis' => 'Long Synopsis',
            'first_broadcast_date' => null,
            'display_title' => (object) [
                'title' => 'Series 9 - Omnibus',
                'subtitle' => '',
            ],
            'links' => [],
            'supporting_content_items' => [],
            'categories' => [],
        ];

        $mapper = new FindByPidProgrammeMapper();
        $this->assertEquals($expectedOutput, $mapper->getApsObject($series));
    }

    public function testMappingEpisode()
    {
        $streamableFrom = new DateTimeImmutable();
        $streamableUntil = new DateTimeImmutable();

        $episode = new Episode(
            1,
            new Pid('b06tl32t'),
            'The Husbands of River Song',
            'Search Title',
            new Synopses('Short Synopsis', 'Medium Synopsis', 'Long Synopsis'),
            new Image(new Pid('p01m5mss'), 'Title', 'ShortSynopsis', 'ShortSynopsis', 'standard', 'jpg'),
            1,
            2,
            true,
            true,
            MediaTypeEnum::VIDEO,
            11,
            12,
            13,
            null,
            101,
            null,
            [],
            [],
            new PartialDate(2015, 02, 00),
            1001,
            $streamableFrom,
            $streamableUntil
        );

        $expectedOutput = (object) [
            'type' => 'episode',
            'pid' => 'b06tl32t',
            'expected_child_count' => null,
            'position' => 101,
            'image' => (object) ['pid' => 'p01m5mss'],
            'media_type' => 'audio_video',
            'title' => 'The Husbands of River Song',
            'short_synopsis' => 'Short Synopsis',
            'medium_synopsis' => 'Medium Synopsis',
            'long_synopsis' => 'Long Synopsis',
            'first_broadcast_date' => '2015-02-01T12:00:00Z',
            'display_title' => (object) [
                'title' => 'The Husbands of River Song',
                'subtitle' => '',
            ],
            'links' => [],
            'supporting_content_items' => [],
            'categories' => [],
            'versions' => [],
        ];

        $mapper = new FindByPidProgrammeMapper();
        $this->assertEquals($expectedOutput, $mapper->getApsObject($episode));
    }

    public function testMappingClip()
    {
        $streamableFrom = new DateTimeImmutable();
        $streamableUntil = new DateTimeImmutable();

        $clip = new Clip(
            1,
            new Pid('b06tl32t'),
            'The Husbands of River Song',
            'Search Title',
            new Synopses('Short Synopsis', 'Medium Synopsis', 'Long Synopsis'),
            new Image(new Pid('p01m5mss'), 'Title', 'ShortSynopsis', 'ShortSynopsis', 'standard', 'jpg'),
            1,
            2,
            true,
            true,
            MediaTypeEnum::VIDEO,
            null,
            101,
            null,
            [],
            [],
            new PartialDate(2015, 02, 00),
            1001,
            $streamableFrom,
            $streamableUntil
        );

        $expectedOutput = (object) [
            'type' => 'clip',
            'pid' => 'b06tl32t',
            'expected_child_count' => null,
            'position' => 101,
            'image' => (object) ['pid' => 'p01m5mss'],
            'media_type' => 'audio_video',
            'title' => 'The Husbands of River Song',
            'short_synopsis' => 'Short Synopsis',
            'medium_synopsis' => 'Medium Synopsis',
            'long_synopsis' => 'Long Synopsis',
            'first_broadcast_date' => '2015-02-01T12:00:00Z',
            'display_title' => (object) [
                'title' => 'The Husbands of River Song',
                'subtitle' => '',
            ],
            'links' => [],
            'supporting_content_items' => [],
            'categories' => [],
            'versions' => [],
        ];

        $mapper = new FindByPidProgrammeMapper();
        $this->assertEquals($expectedOutput, $mapper->getApsObject($clip));
    }

    public function testMappingOwnership()
    {
        $series = $this->createMock(Series::CLASS);
        $series->method('getMasterBrand')->willReturn(new MasterBrand(
            new Mid('bbc_one'),
            'BBC One',
            new Image(new Pid('p01tqv8z'), 'Title', 'ShortSynopsis', 'ShortSynopsis', 'standard', 'jpg'),
            new Network(
                new Nid('bbc_one'),
                'BBC One',
                new Image(new Pid('p01tqv8z'), 'Title', 'ShortSynopsis', 'ShortSynopsis', 'standard', 'jpg'),
                'bbc_one',
                'tv'
            )
        ));

        $expectedOwnership = (object) [
            'service' => (object) [
                'type' => 'tv',
                'id' => 'bbc_one',
                'key' => 'bbc_one',
                'title' => 'BBC One',
            ],
        ];

        $mapper = new FindByPidProgrammeMapper();
        $apsObject = $mapper->getApsObject($series);

        $this->assertObjectHasAttribute('ownership', $apsObject);
        $this->assertEquals($expectedOwnership, $apsObject->ownership);
    }

    public function testMappingOwnershipForSubMasterBrand()
    {
        $series = $this->createMock(Series::CLASS);
        $series->method('getMasterBrand')->willReturn(new MasterBrand(
            new Mid('bbc_one_scotland'),
            'BBC One Scotland',
            new Image(new Pid('p01tqv8z'), 'Title', 'ShortSynopsis', 'ShortSynopsis', 'standard', 'jpg'),
            new Network(
                new Nid('bbc_one'),
                'BBC One',
                new Image(new Pid('p01tqv8z'), 'Title', 'ShortSynopsis', 'ShortSynopsis', 'standard', 'jpg'),
                'bbc_one',
                'tv'
            )
        ));

        $expectedOwnership = (object) [
            'service' => (object) [
                'type' => 'tv',
                'id' => 'bbc_one',
                'key' => 'bbc_one',
                'title' => 'BBC One',
                'outlet' => (object) [
                    'key' => null,
                    'title' => 'BBC One Scotland',
                    'id' => 'bbc_one_scotland',
                ],
            ],
        ];

        $mapper = new FindByPidProgrammeMapper();
        $apsObject = $mapper->getApsObject($series);

        $this->assertObjectHasAttribute('ownership', $apsObject);
        $this->assertEquals($expectedOwnership, $apsObject->ownership);
    }

    public function testMappingParents()
    {
        $brand = new Brand(
            1,
            new Pid('b006q2x0'),
            'Doctor Who',
            'Search Title',
            new Synopses('Short Synopsis', 'Medium Synopsis', 'Long Synopsis'),
            new Image(new Pid('p01m5mss'), 'Title', 'ShortSynopsis', 'ShortSynopsis', 'standard', 'jpg'),
            0,
            1,
            false,
            false,
            0,
            0,
            11,
            0,
            0,
            false,
            null,
            101,
            new MasterBrand(
                new Mid('bbc_two'),
                'BBC Two',
                new Image(new Pid('p01tqv8z'), 'Title', 'ShortSynopsis', 'ShortSynopsis', 'standard', 'jpg'),
                new Network(
                    new Nid('bbc_two'),
                    'BBC Two',
                    new Image(new Pid('p01tqv8z'), 'Title', 'ShortSynopsis', 'ShortSynopsis', 'standard', 'jpg'),
                    'bbc_two',
                    'tv'
                )
            ),
            [],
            [],
            1001
        );

        $series = new Series(
            1,
            new Pid('b06hgxtt'),
            'Series 9 - Omnibus',
            'Search Title',
            new Synopses('Short Synopsis', 'Medium Synopsis', 'Long Synopsis'),
            new Image(new Pid('p01m5mss'), 'Title', 'ShortSynopsis', 'ShortSynopsis', 'standard', 'jpg'),
            0,
            1,
            false,
            false,
            0,
            0,
            11,
            0,
            0,
            false,
            $brand,
            101,
            new MasterBrand(
                new Mid('bbc_one'),
                'BBC One',
                new Image(new Pid('p01tqv8z'), 'Title', 'ShortSynopsis', 'ShortSynopsis', 'standard', 'jpg'),
                new Network(
                    new Nid('bbc_one'),
                    'BBC One',
                    new Image(new Pid('p01tqv8z'), 'Title', 'ShortSynopsis', 'ShortSynopsis', 'standard', 'jpg'),
                    'bbc_one',
                    'tv'
                )
            ),
            [],
            [],
            1001
        );

        $episode = $this->createMock(Episode::CLASS);
        $episode->method('getPid')->willReturn(new Pid('b06tl32t'));
        $episode->method('getParent')->willReturn($series);

        $expectedBrand = (object) [
            'programme' => (object) [
                'type' => 'brand',
                'pid' => 'b006q2x0',
                'expected_child_count' => 1001,
                'position' => 101,
                'image' => (object) ['pid' => 'p01m5mss'],
                'title' => 'Doctor Who',
                'short_synopsis' => 'Short Synopsis',
                'first_broadcast_date' => null,
                'ownership' => (object) [
                    'service' => (object) [
                        'type' => 'tv',
                        'id' => 'bbc_two',
                        'key' => 'bbc_two',
                        'title' => 'BBC Two',
                    ],
                ],
            ],
        ];

        $expectedSeries = (object) [
            'programme' => (object) [
                'type' => 'series',
                'pid' => 'b06hgxtt',
                'expected_child_count' => 1001,
                'position' => 101,
                'image' => (object) ['pid' => 'p01m5mss'],
                'title' => 'Series 9 - Omnibus',
                'short_synopsis' => 'Short Synopsis',
                'first_broadcast_date' => null,
                'ownership' => (object) [
                    'service' => (object) [
                        'type' => 'tv',
                        'id' => 'bbc_one',
                        'key' => 'bbc_one',
                        'title' => 'BBC One',
                    ],
                ],
                'parent' => $expectedBrand,
            ],
        ];

        $mapper = new FindByPidProgrammeMapper();
        $apsObject = $mapper->getApsObject($episode);

        $this->assertObjectHasAttribute('parent', $apsObject);
        $this->assertEquals($expectedSeries, $apsObject->parent);
    }

    public function testMappingCategories()
    {
        $series = $this->createMock(Series::CLASS);
        $series->method('getGenres')->willReturn([
            new Genre('g1id', 'Genre One', 'genre1', new Genre('g3id', 'Genre Three', 'genre3')),
            new Genre('g2id', 'Genre Two', 'genre2', null),
        ]);
        $series->method('getFormats')->willReturn([
            new Format('f1id', 'Format One', 'format1'),
        ]);

        $expectedCategories = [
            (object) [
                'type' => 'format',
                'id' => 'f1id',
                'key' => 'format1',
                'title' => 'Format One',
                'narrower' => [],
                'has_topic_page' => false,
                'sameAs' => null,
            ],
            (object) [
                'type' => 'genre',
                'id' => 'g1id',
                'key' => 'genre1',
                'title' => 'Genre One',
                'narrower' => [],
                'broader' => (object) [
                    'category' => (object) [
                        'type' => 'genre',
                        'id' => 'g3id',
                        'key' => 'genre3',
                        'title' => 'Genre Three',
                        'broader' => (object) [],
                        'has_topic_page' => false,
                        'sameAs' => null,
                    ],
                ],
                'has_topic_page' => false,
                'sameAs' => null,
            ],
            (object) [
                'type' => 'genre',
                'id' => 'g2id',
                'key' => 'genre2',
                'title' => 'Genre Two',
                'narrower' => [],
                'broader' => (object) [],
                'has_topic_page' => false,
                'sameAs' => null,
            ],
        ];

        $mapper = new FindByPidProgrammeMapper();
        $apsObject = $mapper->getApsObject($series);

        $this->assertObjectHasAttribute('categories', $apsObject);
        $this->assertEquals($expectedCategories, $apsObject->categories);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidDomainObject()
    {
        $image = new Image(new Pid('p01m5mss'), 'Title', 'ShortSynopsis', 'ShortSynopsis', 'standard', 'jpg');

        $mapper = new FindByPidProgrammeMapper();
        $mapper->getApsObject($image);
    }
}