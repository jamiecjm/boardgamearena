<?php

/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * oceans implementation : © <Your name here> <Your email address here>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * material.inc.php
 *
 * oceans game material description
 *
 * Here, you can describe the material of your game with PHP variables.
 *
 * This file is loaded in your game logic class constructor, ie these variables
 * are available everywhere in your game logic code.
 *
 */


/*

Example:

$this->card_types = array(
    1 => array( "card_name" => ...,
                ...
              )
);

*/

$this->surfaceCards = array(
  1001 => array(
    "type" => 1,
    "name" => clienttranslate("Tentacled"),
    "description" => clienttranslate("May feed 1 additional time."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0
  ),
  1002 => array(
    "type" => 2,
    "name" => clienttranslate("Shark Cleaner"),
    "description" => clienttranslate("After a species with attack 3 or more attacks, gains 2 if this is the closest Shark Cleaner on either side."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 2
  ),
  1003 => array(
    "type" => 3,
    "name" => clienttranslate("Bottom Feeder"),
    "description" => clienttranslate("Gains 2 after the species to the left is attacked."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 2
  ),
  1004 => array(
    "type" => 4,
    "name" => clienttranslate("Symbiotic"),
    "description" => clienttranslate("Gains 1 after the species to the left forages or attacks."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 1
  ),
  1005 => array(
    "type" => 5,
    "name" => clienttranslate("Parasitic"),
    "description" => clienttranslate("Before your feeding phase, leeches 2 from the species to the left."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0
  ),
  1006 => array(
    "type" => 6,
    "name" => clienttranslate("Filter Feeder"),
    "description" => clienttranslate("Never over populates."),
    "forage" => 5,
    "attack" => 0,
    "defense" => 0,
    "gains" => 0
  ),
  1007 => array(
    "type" => 7,
    "name" => clienttranslate("Apex Predator"),
    "description" => null,
    "forage" => 0,
    "attack" => 3,
    "defense" => 2,
    "gains" => 0
  ),
  1008 => array(
    "type" => 8,
    "name" => clienttranslate("Speed"),
    "description" => clienttranslate("May have 1 extra trait."),
    "forage" => 2,
    "attack" => 2,
    "defense" => 2,
    "gains" => 0
  ),
  1009 => array(
    "type" => 9,
    "name" => clienttranslate("Inking"),
    "description" => null,
    "forage" => 1,
    "attack" => 1,
    "defense" => 4,
    "gains" => 0
  ),
  1010 => array(
    "type" => 10,
    "name" => clienttranslate("Schooling"),
    "description" => clienttranslate("Aging +1\nCan't be attacked when this species has 5 or more population"),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0
  ),
  1011 => array(
    "type" => 11,
    "name" => clienttranslate("Transparent"),
    "description" => clienttranslate("Can't be attacked or leeched if there is population in the reef"),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0
  ),
  1012 => array(
    "type" => 12,
    "name" => clienttranslate("Whale Cleaner"),
    "description" => clienttranslate("After a species with forage 3 or more forages, gains 3 if this is the closest Whale Cleaner on either side."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 3
  ),
);
