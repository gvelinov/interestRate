CREATE TABLE IF NOT EXISTS `users` (
    `id` INT(10) unsigned NOT NULL AUTO_INCREMENT,
    `name` CHAR (20) NOT NULL,

    PRIMARY KEY (`id`)
);

INSERT IGNORE INTO `users` (`id`, `name`) VALUES (1, 'Client 1');
INSERT IGNORE INTO `users` (`id`, `name`) VALUES (2, 'Client 2');

CREATE TABLE IF NOT EXISTS `accounts` (
    `id` INT(10) unsigned NOT NULL AUTO_INCREMENT,
    `userID` INT(10) unsigned NOT NULL,
    `currency` CHAR(12),
    `balance` DECIMAL(13, 4) NOT NULL,

    INDEX (`userID`),
    PRIMARY KEY (`id`)
);

INSERT IGNORE INTO `accounts` (`id`, `userID`, `currency`, `balance`) VALUES (1, 1, 'BGN', 0);
INSERT IGNORE INTO `accounts` (`id`, `userID`, `currency`, `balance`) VALUES (2, 1, 'BGN', 100);
INSERT IGNORE INTO `accounts` (`id`, `userID`, `currency`, `balance`) VALUES (3, 2, 'BGN', 0);
INSERT IGNORE INTO `accounts` (`id`, `userID`, `currency`, `balance`) VALUES (4, 2, 'BGN', 50);

CREATE TABLE IF NOT EXISTS `transactions` (
    `id` INT(10) unsigned NOT NULL AUTO_INCREMENT,
    `accountID` INT(10) unsigned NOT NULL,
    `date` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `amount` DECIMAL(13, 4) NOT NULL,
    `type` ENUM ('deposit', 'withdrawal'),

    INDEX (`accountID`, `date`),
    PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `configs` (
    `id` INT(10) unsigned NOT NULL AUTO_INCREMENT,
    `name` CHAR(20) UNIQUE NOT NULL,
    `value` CHAR(20) NOT NULL,

    INDEX (`name`),
    PRIMARY KEY (`id`)
);

INSERT IGNORE INTO `configs` (`id`, `name`, `value`) VALUES (1, 'transaction_lock', '0');
