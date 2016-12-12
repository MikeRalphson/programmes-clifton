UPDATE mysql.user SET Password=PASSWORD('developer') WHERE User='root';
DELETE FROM mysql.user WHERE User='';
DELETE FROM mysql.user WHERE User='root' AND Host NOT IN ('localhost', '127.0.0.1', '::1');
DROP DATABASE IF EXISTS test;
DELETE FROM mysql.db WHERE Db='test' OR Db='test\\_%';
CREATE database xhprof;
GRANT ALL PRIVILEGES ON xhprof.* TO 'xhprof'@'localhost' IDENTIFIED BY 'developer';
CREATE TABLE xhprof.details (
	`id`                     CHAR(17)  NOT NULL,
	`url`                    VARCHAR(255)       DEFAULT NULL,
	`c_url`                  VARCHAR(255)       DEFAULT NULL,
	`timestamp`              TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	`server name`            VARCHAR(64)        DEFAULT NULL,
	`perfdata`               MEDIUMBLOB,
	`type`                   TINYINT(4)         DEFAULT NULL,
	`cookie`                 BLOB,
	`post`                   BLOB,
	`get`                    BLOB,
	`pmu`                    INT(11) UNSIGNED   DEFAULT NULL,
	`wt`                     INT(11) UNSIGNED   DEFAULT NULL,
	`cpu`                    INT(11) UNSIGNED   DEFAULT NULL,
	`ct`                     INT(11) UNSIGNED   DEFAULT NULL,
	`server_id`              CHAR(3)   NOT NULL DEFAULT 't11',
	`aggregateCalls_include` VARCHAR(255)       DEFAULT NULL,
	PRIMARY KEY (`id`),
	KEY `url` (`url`),
	KEY `c_url` (`c_url`),
	KEY `cpu` (`cpu`),
	KEY `ct` (`ct`),
	KEY `wt` (`wt`),
	KEY `pmu` (`pmu`),
	KEY `timestamp` (`timestamp`)
)
	ENGINE = MyISAM
	DEFAULT CHARSET = utf8;
FLUSH PRIVILEGES;
