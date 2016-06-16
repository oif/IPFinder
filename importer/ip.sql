SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

create table ip_0 (
    id INT NOT NULL AUTO_INCREMENT,
    startAt INT UNSIGNED NOT NULL,
    endAt  INT UNSIGNED NOT NULL,
    geo varchar(255),
    PRIMARY KEY (id)
);
ALTER TABLE  `ip_0` ADD INDEX (  `endAt` ) ;

create table ip_1 (
    id INT NOT NULL AUTO_INCREMENT,
    startAt INT UNSIGNED NOT NULL,
    endAt INT UNSIGNED NOT NULL,
    geo varchar(255),
    PRIMARY KEY (id)
);
ALTER TABLE  `ip_1` ADD INDEX (  `endAt` ) ;

create table ip_2 (
    id INT NOT NULL AUTO_INCREMENT,
    startAt INT UNSIGNED NOT NULL,
    endAt INT UNSIGNED NOT NULL,
    geo varchar(255),
    PRIMARY KEY (id)
);
ALTER TABLE  `ip_2` ADD INDEX (  `endAt` ) ;

create table ip_3 (
    id INT NOT NULL AUTO_INCREMENT,
    startAt INT UNSIGNED NOT NULL,
    endAt INT UNSIGNED NOT NULL,
    geo varchar(255),
    PRIMARY KEY (id)
);
ALTER TABLE  `ip_3` ADD INDEX (  `endAt` ) ;

create table ip_4 (
    id INT NOT NULL AUTO_INCREMENT,
    startAt INT UNSIGNED NOT NULL,
    endAt INT UNSIGNED NOT NULL,
    geo varchar(255),
    PRIMARY KEY (id)
);
ALTER TABLE  `ip_4` ADD INDEX (  `endAt` ) ;

create table ip_5 (
    id INT NOT NULL AUTO_INCREMENT,
    startAt INT UNSIGNED NOT NULL,
    endAt INT UNSIGNED NOT NULL,
    geo varchar(255),
    PRIMARY KEY (id)
);
ALTER TABLE  `ip_5` ADD INDEX (  `endAt` ) ;