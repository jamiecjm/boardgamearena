
-- ------
-- BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
-- viticulture implementation : © Leo Bartoloni bartololeo74@gmail.com
-- 
-- This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
-- See http://en.boardgamearena.com/#!doc/Studio for more information.
-- -----

-- dbmodel.sql

-- This is the file where you are describing the database schema of your game
-- Basically, you just have to export from PhpMyAdmin your table structure and copy/paste
-- this export here.
-- Note that the database itself and the standard tables ("global", "stats", "gamelog" and "player") are
-- already created and must not be created here

-- Note: The database schema is created from this file when the game starts. If you modify this file,
--       you have to restart a game to see your changes in database.

ALTER TABLE `player` ADD `playorder` INT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `player` ADD `wakeup_chart` INT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `player` ADD `wakeup_order` INT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `player` ADD `lira` INT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `player` ADD `pass` INT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `player` ADD `residual_payment` INT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `player` ADD `field1` INT UNSIGNED NOT NULL DEFAULT '1';
ALTER TABLE `player` ADD `field2` INT UNSIGNED NOT NULL DEFAULT '1';
ALTER TABLE `player` ADD `field3` INT UNSIGNED NOT NULL DEFAULT '1';
ALTER TABLE `player` ADD `trellis` INT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `player` ADD `irrigation` INT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `player` ADD `yoke` INT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `player` ADD `tastingRoom` INT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `player` ADD `tastingRoomUsed` INT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `player` ADD `cottage` INT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `player` ADD `windmill` INT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `player` ADD `windmillUsed` INT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `player` ADD `mediumCellar` INT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `player` ADD `largeCellar` INT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `player` ADD `card_played` INT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `player` ADD `bonuses` INT UNSIGNED NOT NULL DEFAULT '0';

-- cards
CREATE TABLE IF NOT EXISTS `card` (
  `card_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `card_type` varchar(16) NOT NULL,
  `card_type_arg` int(11) NOT NULL,
  `card_location` varchar(32) NOT NULL,
  `card_location_arg` int(11) NOT NULL,
  PRIMARY KEY (`card_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- player actions
CREATE TABLE IF NOT EXISTS `player_action` (
  `player_action_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `player_id` varchar(16)  NOT NULL,
  `action` varchar(32) NOT NULL,
  `play_order` int unsigned NOT NULL,
  `args` varchar(64) NULL,
  `card_id` int unsigned NULL,
  `card_key` int unsigned NULL,
  `status` int unsigned NULL,
  PRIMARY KEY (`player_action_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- player undo
--CREATE TABLE IF NOT EXISTS `undo_action` (
--  `player_action_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
--  `player_id` varchar(16)  NOT NULL,
--  `action` varchar(32) NOT NULL,
--  `args` varchar(1000) NULL,
--  `status` int unsigned NULL,
--  PRIMARY KEY (`player_action_id`)
--) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
-- notes:
-- placedWorker
-- args: id|boardLocation
-- yellowCard
-- args: cardId
-- blueCard
-- args: cardId
-- makeWine
-- args: wine|grape1|grape2