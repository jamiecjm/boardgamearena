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
    "imagePosition" => 1,
    "name" => clienttranslate("Tentacled"),
    "description" => clienttranslate("May feed 1 additional time."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "migrate" => 5,
  ),
  1002 => array(
    "imagePosition" => 1,
    "name" => clienttranslate("Tentacled"),
    "description" => clienttranslate("May feed 1 additional time."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "migrate" => 6,
  ),
  1003 => array(
    "imagePosition" => 1,
    "name" => clienttranslate("Tentacled"),
    "description" => clienttranslate("May feed 1 additional time."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "migrate" => 7,
  ),
  1004 => array(
    "imagePosition" => 1,
    "name" => clienttranslate("Tentacled"),
    "description" => clienttranslate("May feed 1 additional time."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "migrate" => 7,
  ),
  1005 => array(
    "imagePosition" => 1,
    "name" => clienttranslate("Tentacled"),
    "description" => clienttranslate("May feed 1 additional time."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "migrate" => 8,
  ),
  1006 => array(
    "imagePosition" => 1,
    "name" => clienttranslate("Tentacled"),
    "description" => clienttranslate("May feed 1 additional time."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "migrate" => 8,
  ),
  1007 => array(
    "imagePosition" => 1,
    "name" => clienttranslate("Tentacled"),
    "description" => clienttranslate("May feed 1 additional time."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "migrate" => 9,
  ),
  1008 => array(
    "imagePosition" => 1,
    "name" => clienttranslate("Tentacled"),
    "description" => clienttranslate("May feed 1 additional time."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "migrate" => 9,
  ),
  1009 => array(
    "imagePosition" => 1,
    "name" => clienttranslate("Tentacled"),
    "description" => clienttranslate("May feed 1 additional time."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "migrate" => 10,
  ),
  1010 => array(
    "imagePosition" => 1,
    "name" => clienttranslate("Tentacled"),
    "description" => clienttranslate("May feed 1 additional time."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "migrate" => 11,
  ),
  1011 => array(
    "imagePosition" => 2,
    "name" => clienttranslate("Shark Cleaner"),
    "description" => clienttranslate("After a species with attack 3 or more attacks, gains 2 if this is the closest Shark Cleaner on either side."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 2,
    "migrate" => 5
  ),
  1012 => array(
    "imagePosition" => 2,
    "name" => clienttranslate("Shark Cleaner"),
    "description" => clienttranslate("After a species with attack 3 or more attacks, gains 2 if this is the closest Shark Cleaner on either side."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 2,
    "migrate" => 6
  ),
  1013 => array(
    "imagePosition" => 2,
    "name" => clienttranslate("Shark Cleaner"),
    "description" => clienttranslate("After a species with attack 3 or more attacks, gains 2 if this is the closest Shark Cleaner on either side."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 2,
    "migrate" => 7
  ),
  1014 => array(
    "imagePosition" => 2,
    "name" => clienttranslate("Shark Cleaner"),
    "description" => clienttranslate("After a species with attack 3 or more attacks, gains 2 if this is the closest Shark Cleaner on either side."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 2,
    "migrate" => 7
  ),
  1015 => array(
    "imagePosition" => 2,
    "name" => clienttranslate("Shark Cleaner"),
    "description" => clienttranslate("After a species with attack 3 or more attacks, gains 2 if this is the closest Shark Cleaner on either side."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 2,
    "migrate" => 8
  ),
  1016 => array(
    "imagePosition" => 2,
    "name" => clienttranslate("Shark Cleaner"),
    "description" => clienttranslate("After a species with attack 3 or more attacks, gains 2 if this is the closest Shark Cleaner on either side."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 2,
    "migrate" => 8
  ),
  1017 => array(
    "imagePosition" => 2,
    "name" => clienttranslate("Shark Cleaner"),
    "description" => clienttranslate("After a species with attack 3 or more attacks, gains 2 if this is the closest Shark Cleaner on either side."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 2,
    "migrate" => 9
  ),
  1018 => array(
    "imagePosition" => 2,
    "name" => clienttranslate("Shark Cleaner"),
    "description" => clienttranslate("After a species with attack 3 or more attacks, gains 2 if this is the closest Shark Cleaner on either side."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 2,
    "migrate" => 9
  ),
  1019 => array(
    "imagePosition" => 2,
    "name" => clienttranslate("Shark Cleaner"),
    "description" => clienttranslate("After a species with attack 3 or more attacks, gains 2 if this is the closest Shark Cleaner on either side."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 2,
    "migrate" => 10
  ),
  1020 => array(
    "imagePosition" => 2,
    "name" => clienttranslate("Shark Cleaner"),
    "description" => clienttranslate("After a species with attack 3 or more attacks, gains 2 if this is the closest Shark Cleaner on either side."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 2,
    "migrate" => 11
  ),
  1021 => array(
    "imagePosition" => 3,
    "name" => clienttranslate("Bottom Feeder"),
    "description" => clienttranslate("Gains 2 after the species to the left is attacked."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 2,
    "migrate" => 5
  ),
  1022 => array(
    "imagePosition" => 3,
    "name" => clienttranslate("Bottom Feeder"),
    "description" => clienttranslate("Gains 2 after the species to the left is attacked."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 2,
    "migrate" => 6
  ),
  1023 => array(
    "imagePosition" => 3,
    "name" => clienttranslate("Bottom Feeder"),
    "description" => clienttranslate("Gains 2 after the species to the left is attacked."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 2,
    "migrate" => 7
  ),
  1024 => array(
    "imagePosition" => 3,
    "name" => clienttranslate("Bottom Feeder"),
    "description" => clienttranslate("Gains 2 after the species to the left is attacked."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 2,
    "migrate" => 7
  ),
  1025 => array(
    "imagePosition" => 3,
    "name" => clienttranslate("Bottom Feeder"),
    "description" => clienttranslate("Gains 2 after the species to the left is attacked."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 2,
    "migrate" => 8
  ),
  1026 => array(
    "imagePosition" => 3,
    "name" => clienttranslate("Bottom Feeder"),
    "description" => clienttranslate("Gains 2 after the species to the left is attacked."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 2,
    "migrate" => 8
  ),
  1027 => array(
    "imagePosition" => 3,
    "name" => clienttranslate("Bottom Feeder"),
    "description" => clienttranslate("Gains 2 after the species to the left is attacked."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 2,
    "migrate" => 9
  ),
  1028 => array(
    "imagePosition" => 3,
    "name" => clienttranslate("Bottom Feeder"),
    "description" => clienttranslate("Gains 2 after the species to the left is attacked."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 2,
    "migrate" => 9
  ),
  1029 => array(
    "imagePosition" => 3,
    "name" => clienttranslate("Bottom Feeder"),
    "description" => clienttranslate("Gains 2 after the species to the left is attacked."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 2,
    "migrate" => 10
  ),
  1030 => array(
    "imagePosition" => 3,
    "name" => clienttranslate("Bottom Feeder"),
    "description" => clienttranslate("Gains 2 after the species to the left is attacked."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 2,
    "migrate" => 11
  ),
  1031 => array(
    "imagePosition" => 4,
    "name" => clienttranslate("Symbiotic"),
    "description" => clienttranslate("Gains 1 after the species to the left forages or attacks."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 1,
    "migrate" => 5
  ),
  1032 => array(
    "imagePosition" => 4,
    "name" => clienttranslate("Symbiotic"),
    "description" => clienttranslate("Gains 1 after the species to the left forages or attacks."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 1,
    "migrate" => 6
  ),
  1033 => array(
    "imagePosition" => 4,
    "name" => clienttranslate("Symbiotic"),
    "description" => clienttranslate("Gains 1 after the species to the left forages or attacks."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 1,
    "migrate" => 7
  ),
  1034 => array(
    "imagePosition" => 4,
    "name" => clienttranslate("Symbiotic"),
    "description" => clienttranslate("Gains 1 after the species to the left forages or attacks."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 1,
    "migrate" => 7
  ),
  1035 => array(
    "imagePosition" => 4,
    "name" => clienttranslate("Symbiotic"),
    "description" => clienttranslate("Gains 1 after the species to the left forages or attacks."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 1,
    "migrate" => 8
  ),
  1036 => array(
    "imagePosition" => 4,
    "name" => clienttranslate("Symbiotic"),
    "description" => clienttranslate("Gains 1 after the species to the left forages or attacks."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 1,
    "migrate" => 8
  ),
  1037 => array(
    "imagePosition" => 4,
    "name" => clienttranslate("Symbiotic"),
    "description" => clienttranslate("Gains 1 after the species to the left forages or attacks."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 1,
    "migrate" => 9
  ),
  1038 => array(
    "imagePosition" => 4,
    "name" => clienttranslate("Symbiotic"),
    "description" => clienttranslate("Gains 1 after the species to the left forages or attacks."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 1,
    "migrate" => 9
  ),
  1039 => array(
    "imagePosition" => 4,
    "name" => clienttranslate("Symbiotic"),
    "description" => clienttranslate("Gains 1 after the species to the left forages or attacks."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 1,
    "migrate" => 10
  ),
  1040 => array(
    "imagePosition" => 4,
    "name" => clienttranslate("Symbiotic"),
    "description" => clienttranslate("Gains 1 after the species to the left forages or attacks."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 1,
    "migrate" => 11
  ),
  1041 => array(
    "imagePosition" => 5,
    "name" => clienttranslate("Parasitic"),
    "description" => clienttranslate("Before your feeding phase, leeches 2 from the species to the left."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "migrate" => 5,
    "trigger" => "Before feeding phase"
  ),
  1042 => array(
    "imagePosition" => 5,
    "name" => clienttranslate("Parasitic"),
    "description" => clienttranslate("Before your feeding phase, leeches 2 from the species to the left."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "migrate" => 6
  ),
  1043 => array(
    "imagePosition" => 5,
    "name" => clienttranslate("Parasitic"),
    "description" => clienttranslate("Before your feeding phase, leeches 2 from the species to the left."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "migrate" => 7
  ),
  1044 => array(
    "imagePosition" => 5,
    "name" => clienttranslate("Parasitic"),
    "description" => clienttranslate("Before your feeding phase, leeches 2 from the species to the left."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "migrate" => 7
  ),
  1045 => array(
    "imagePosition" => 5,
    "name" => clienttranslate("Parasitic"),
    "description" => clienttranslate("Before your feeding phase, leeches 2 from the species to the left."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "migrate" => 8
  ),
  1046 => array(
    "imagePosition" => 5,
    "name" => clienttranslate("Parasitic"),
    "description" => clienttranslate("Before your feeding phase, leeches 2 from the species to the left."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "migrate" => 8
  ),
  1047 => array(
    "imagePosition" => 5,
    "name" => clienttranslate("Parasitic"),
    "description" => clienttranslate("Before your feeding phase, leeches 2 from the species to the left."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "migrate" => 9
  ),
  1048 => array(
    "imagePosition" => 5,
    "name" => clienttranslate("Parasitic"),
    "description" => clienttranslate("Before your feeding phase, leeches 2 from the species to the left."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "migrate" => 9
  ),
  1049 => array(
    "imagePosition" => 5,
    "name" => clienttranslate("Parasitic"),
    "description" => clienttranslate("Before your feeding phase, leeches 2 from the species to the left."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "migrate" => 10
  ),
  1050 => array(
    "imagePosition" => 5,
    "name" => clienttranslate("Parasitic"),
    "description" => clienttranslate("Before your feeding phase, leeches 2 from the species to the left."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "migrate" => 11
  ),
  1051 => array(
    "imagePosition" => 6,
    "name" => clienttranslate("Filter Feeder"),
    "description" => clienttranslate("Never over populates."),
    "forage" => 5,
    "attack" => 0,
    "defense" => 0,
    "gains" => 0,
    "migrate" => 5
  ),
  1052 => array(
    "imagePosition" => 6,
    "name" => clienttranslate("Filter Feeder"),
    "description" => clienttranslate("Never over populates."),
    "forage" => 5,
    "attack" => 0,
    "defense" => 0,
    "gains" => 0,
    "migrate" => 6
  ),
  1053 => array(
    "imagePosition" => 6,
    "name" => clienttranslate("Filter Feeder"),
    "description" => clienttranslate("Never over populates."),
    "forage" => 5,
    "attack" => 0,
    "defense" => 0,
    "gains" => 0,
    "migrate" => 7
  ),
  1054 => array(
    "imagePosition" => 6,
    "name" => clienttranslate("Filter Feeder"),
    "description" => clienttranslate("Never over populates."),
    "forage" => 5,
    "attack" => 0,
    "defense" => 0,
    "gains" => 0,
    "migrate" => 7
  ),
  1055 => array(
    "imagePosition" => 6,
    "name" => clienttranslate("Filter Feeder"),
    "description" => clienttranslate("Never over populates."),
    "forage" => 5,
    "attack" => 0,
    "defense" => 0,
    "gains" => 0,
    "migrate" => 8
  ),
  1056 => array(
    "imagePosition" => 6,
    "name" => clienttranslate("Filter Feeder"),
    "description" => clienttranslate("Never over populates."),
    "forage" => 5,
    "attack" => 0,
    "defense" => 0,
    "gains" => 0,
    "migrate" => 8
  ),
  1057 => array(
    "imagePosition" => 6,
    "name" => clienttranslate("Filter Feeder"),
    "description" => clienttranslate("Never over populates."),
    "forage" => 5,
    "attack" => 0,
    "defense" => 0,
    "gains" => 0,
    "migrate" => 9
  ),
  1058 => array(
    "imagePosition" => 6,
    "name" => clienttranslate("Filter Feeder"),
    "description" => clienttranslate("Never over populates."),
    "forage" => 5,
    "attack" => 0,
    "defense" => 0,
    "gains" => 0,
    "migrate" => 9
  ),
  1059 => array(
    "imagePosition" => 6,
    "name" => clienttranslate("Filter Feeder"),
    "description" => clienttranslate("Never over populates."),
    "forage" => 5,
    "attack" => 0,
    "defense" => 0,
    "gains" => 0,
    "migrate" => 10
  ),
  1060 => array(
    "imagePosition" => 6,
    "name" => clienttranslate("Filter Feeder"),
    "description" => clienttranslate("Never over populates."),
    "forage" => 5,
    "attack" => 0,
    "defense" => 0,
    "gains" => 0,
    "migrate" => 11
  ),
  1061 => array(
    "imagePosition" => 7,
    "name" => clienttranslate("Apex Predator"),
    "description" => null,
    "forage" => 0,
    "attack" => 3,
    "defense" => 2,
    "gains" => 0,
    "migrate" => 5
  ),
  1062 => array(
    "imagePosition" => 7,
    "name" => clienttranslate("Apex Predator"),
    "description" => null,
    "forage" => 0,
    "attack" => 3,
    "defense" => 2,
    "gains" => 0,
    "migrate" => 6
  ),
  1063 => array(
    "imagePosition" => 7,
    "name" => clienttranslate("Apex Predator"),
    "description" => null,
    "forage" => 0,
    "attack" => 3,
    "defense" => 2,
    "gains" => 0,
    "migrate" => 7
  ),
  1064 => array(
    "imagePosition" => 7,
    "name" => clienttranslate("Apex Predator"),
    "description" => null,
    "forage" => 0,
    "attack" => 3,
    "defense" => 2,
    "gains" => 0,
    "migrate" => 7
  ),
  1065 => array(
    "imagePosition" => 7,
    "name" => clienttranslate("Apex Predator"),
    "description" => null,
    "forage" => 0,
    "attack" => 3,
    "defense" => 2,
    "gains" => 0,
    "migrate" => 8
  ),
  1066 => array(
    "imagePosition" => 7,
    "name" => clienttranslate("Apex Predator"),
    "description" => null,
    "forage" => 0,
    "attack" => 3,
    "defense" => 2,
    "gains" => 0,
    "migrate" => 8
  ),
  1067 => array(
    "imagePosition" => 7,
    "name" => clienttranslate("Apex Predator"),
    "description" => null,
    "forage" => 0,
    "attack" => 3,
    "defense" => 2,
    "gains" => 0,
    "migrate" => 9
  ),
  1068 => array(
    "imagePosition" => 7,
    "name" => clienttranslate("Apex Predator"),
    "description" => null,
    "forage" => 0,
    "attack" => 3,
    "defense" => 2,
    "gains" => 0,
    "migrate" => 9
  ),
  1069 => array(
    "imagePosition" => 7,
    "name" => clienttranslate("Apex Predator"),
    "description" => null,
    "forage" => 0,
    "attack" => 3,
    "defense" => 2,
    "gains" => 0,
    "migrate" => 10
  ),
  1070 => array(
    "imagePosition" => 7,
    "name" => clienttranslate("Apex Predator"),
    "description" => null,
    "forage" => 0,
    "attack" => 3,
    "defense" => 2,
    "gains" => 0,
    "migrate" => 11
  ),
  1071 => array(
    "imagePosition" => 8,
    "name" => clienttranslate("Speed"),
    "description" => clienttranslate("May have 1 extra trait."),
    "forage" => 2,
    "attack" => 2,
    "defense" => 2,
    "gains" => 0,
    "migrate" => 5
  ),
  1072 => array(
    "imagePosition" => 8,
    "name" => clienttranslate("Speed"),
    "description" => clienttranslate("May have 1 extra trait."),
    "forage" => 2,
    "attack" => 2,
    "defense" => 2,
    "gains" => 0,
    "migrate" => 6
  ),
  1073 => array(
    "imagePosition" => 8,
    "name" => clienttranslate("Speed"),
    "description" => clienttranslate("May have 1 extra trait."),
    "forage" => 2,
    "attack" => 2,
    "defense" => 2,
    "gains" => 0,
    "migrate" => 7
  ),
  1074 => array(
    "imagePosition" => 8,
    "name" => clienttranslate("Speed"),
    "description" => clienttranslate("May have 1 extra trait."),
    "forage" => 2,
    "attack" => 2,
    "defense" => 2,
    "gains" => 0,
    "migrate" => 7
  ),
  1075 => array(
    "imagePosition" => 8,
    "name" => clienttranslate("Speed"),
    "description" => clienttranslate("May have 1 extra trait."),
    "forage" => 2,
    "attack" => 2,
    "defense" => 2,
    "gains" => 0,
    "migrate" => 8
  ),
  1076 => array(
    "imagePosition" => 8,
    "name" => clienttranslate("Speed"),
    "description" => clienttranslate("May have 1 extra trait."),
    "forage" => 2,
    "attack" => 2,
    "defense" => 2,
    "gains" => 0,
    "migrate" => 8
  ),
  1077 => array(
    "imagePosition" => 8,
    "name" => clienttranslate("Speed"),
    "description" => clienttranslate("May have 1 extra trait."),
    "forage" => 2,
    "attack" => 2,
    "defense" => 2,
    "gains" => 0,
    "migrate" => 9
  ),
  1078 => array(
    "imagePosition" => 8,
    "name" => clienttranslate("Speed"),
    "description" => clienttranslate("May have 1 extra trait."),
    "forage" => 2,
    "attack" => 2,
    "defense" => 2,
    "gains" => 0,
    "migrate" => 9
  ),
  1079 => array(
    "imagePosition" => 8,
    "name" => clienttranslate("Speed"),
    "description" => clienttranslate("May have 1 extra trait."),
    "forage" => 2,
    "attack" => 2,
    "defense" => 2,
    "gains" => 0,
    "migrate" => 10
  ),
  1080 => array(
    "imagePosition" => 8,
    "name" => clienttranslate("Speed"),
    "description" => clienttranslate("May have 1 extra trait."),
    "forage" => 2,
    "attack" => 2,
    "defense" => 2,
    "gains" => 0,
    "migrate" => 11
  ),
  1081 => array(
    "imagePosition" => 9,
    "name" => clienttranslate("Inking"),
    "description" => null,
    "forage" => 1,
    "attack" => 1,
    "defense" => 4,
    "gains" => 0,
    "migrate" => 5
  ),
  1082 => array(
    "imagePosition" => 9,
    "name" => clienttranslate("Inking"),
    "description" => null,
    "forage" => 1,
    "attack" => 1,
    "defense" => 4,
    "gains" => 0,
    "migrate" => 6
  ),
  1083 => array(
    "imagePosition" => 9,
    "name" => clienttranslate("Inking"),
    "description" => null,
    "forage" => 1,
    "attack" => 1,
    "defense" => 4,
    "gains" => 0,
    "migrate" => 7
  ),
  1084 => array(
    "imagePosition" => 9,
    "name" => clienttranslate("Inking"),
    "description" => null,
    "forage" => 1,
    "attack" => 1,
    "defense" => 4,
    "gains" => 0,
    "migrate" => 7
  ),
  1085 => array(
    "imagePosition" => 9,
    "name" => clienttranslate("Inking"),
    "description" => null,
    "forage" => 1,
    "attack" => 1,
    "defense" => 4,
    "gains" => 0,
    "migrate" => 8
  ),
  1086 => array(
    "imagePosition" => 9,
    "name" => clienttranslate("Inking"),
    "description" => null,
    "forage" => 1,
    "attack" => 1,
    "defense" => 4,
    "gains" => 0,
    "migrate" => 8
  ),
  1087 => array(
    "imagePosition" => 9,
    "name" => clienttranslate("Inking"),
    "description" => null,
    "forage" => 1,
    "attack" => 1,
    "defense" => 4,
    "gains" => 0,
    "migrate" => 9
  ),
  1088 => array(
    "imagePosition" => 9,
    "name" => clienttranslate("Inking"),
    "description" => null,
    "forage" => 1,
    "attack" => 1,
    "defense" => 4,
    "gains" => 0,
    "migrate" => 9
  ),
  1089 => array(
    "imagePosition" => 9,
    "name" => clienttranslate("Inking"),
    "description" => null,
    "forage" => 1,
    "attack" => 1,
    "defense" => 4,
    "gains" => 0,
    "migrate" => 10
  ),
  1090 => array(
    "imagePosition" => 9,
    "name" => clienttranslate("Inking"),
    "description" => null,
    "forage" => 1,
    "attack" => 1,
    "defense" => 4,
    "gains" => 0,
    "migrate" => 11
  ),
  1091 => array(
    "imagePosition" => 10,
    "name" => clienttranslate("Schooling"),
    "description" => clienttranslate("Aging +1\nCan't be attacked when this species has 5 or more population"),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "migrate" => 5
  ),
  1092 => array(
    "imagePosition" => 10,
    "name" => clienttranslate("Schooling"),
    "description" => clienttranslate("Aging +1\nCan't be attacked when this species has 5 or more population"),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "migrate" => 6
  ),
  1093 => array(
    "imagePosition" => 10,
    "name" => clienttranslate("Schooling"),
    "description" => clienttranslate("Aging +1\nCan't be attacked when this species has 5 or more population"),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "migrate" => 7
  ),
  1094 => array(
    "imagePosition" => 10,
    "name" => clienttranslate("Schooling"),
    "description" => clienttranslate("Aging +1\nCan't be attacked when this species has 5 or more population"),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "migrate" => 7
  ),
  1095 => array(
    "imagePosition" => 10,
    "name" => clienttranslate("Schooling"),
    "description" => clienttranslate("Aging +1\nCan't be attacked when this species has 5 or more population"),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "migrate" => 8
  ),
  1096 => array(
    "imagePosition" => 10,
    "name" => clienttranslate("Schooling"),
    "description" => clienttranslate("Aging +1\nCan't be attacked when this species has 5 or more population"),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "migrate" => 8
  ),
  1097 => array(
    "imagePosition" => 10,
    "name" => clienttranslate("Schooling"),
    "description" => clienttranslate("Aging +1\nCan't be attacked when this species has 5 or more population"),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "migrate" => 9
  ),
  1098 => array(
    "imagePosition" => 10,
    "name" => clienttranslate("Schooling"),
    "description" => clienttranslate("Aging +1\nCan't be attacked when this species has 5 or more population"),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "migrate" => 9
  ),
  1099 => array(
    "imagePosition" => 10,
    "name" => clienttranslate("Schooling"),
    "description" => clienttranslate("Aging +1\nCan't be attacked when this species has 5 or more population"),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "migrate" => 10
  ),
  1100 => array(
    "imagePosition" => 10,
    "name" => clienttranslate("Schooling"),
    "description" => clienttranslate("Aging +1\nCan't be attacked when this species has 5 or more population"),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "migrate" => 11
  ),
  1101 => array(
    "imagePosition" => 11,
    "name" => clienttranslate("Transparent"),
    "description" => clienttranslate("Can't be attacked or leeched if there is population in the reef"),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "migrate" => 5
  ),
  1102 => array(
    "imagePosition" => 11,
    "name" => clienttranslate("Transparent"),
    "description" => clienttranslate("Can't be attacked or leeched if there is population in the reef"),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "migrate" => 6
  ),
  1103 => array(
    "imagePosition" => 11,
    "name" => clienttranslate("Transparent"),
    "description" => clienttranslate("Can't be attacked or leeched if there is population in the reef"),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "migrate" => 7
  ),
  1104 => array(
    "imagePosition" => 11,
    "name" => clienttranslate("Transparent"),
    "description" => clienttranslate("Can't be attacked or leeched if there is population in the reef"),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "migrate" => 7
  ),
  1105 => array(
    "imagePosition" => 11,
    "name" => clienttranslate("Transparent"),
    "description" => clienttranslate("Can't be attacked or leeched if there is population in the reef"),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "migrate" => 8
  ),
  1106 => array(
    "imagePosition" => 11,
    "name" => clienttranslate("Transparent"),
    "description" => clienttranslate("Can't be attacked or leeched if there is population in the reef"),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "migrate" => 8
  ),
  1107 => array(
    "imagePosition" => 11,
    "name" => clienttranslate("Transparent"),
    "description" => clienttranslate("Can't be attacked or leeched if there is population in the reef"),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "migrate" => 9
  ),
  1108 => array(
    "imagePosition" => 11,
    "name" => clienttranslate("Transparent"),
    "description" => clienttranslate("Can't be attacked or leeched if there is population in the reef"),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "migrate" => 9
  ),
  1109 => array(
    "imagePosition" => 11,
    "name" => clienttranslate("Transparent"),
    "description" => clienttranslate("Can't be attacked or leeched if there is population in the reef"),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "migrate" => 10
  ),
  1110 => array(
    "imagePosition" => 11,
    "name" => clienttranslate("Transparent"),
    "description" => clienttranslate("Can't be attacked or leeched if there is population in the reef"),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "migrate" => 11
  ),
  1111 => array(
    "imagePosition" => 12,
    "name" => clienttranslate("Whale Cleaner"),
    "description" => clienttranslate("After a species with forage 3 or more forages, gains 3 if this is the closest Whale Cleaner on either side."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 3,
    "migrate" => 5
  ),
  1112 => array(
    "imagePosition" => 12,
    "name" => clienttranslate("Whale Cleaner"),
    "description" => clienttranslate("After a species with forage 3 or more forages, gains 3 if this is the closest Whale Cleaner on either side."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 3,
    "migrate" => 6
  ),
  1113 => array(
    "imagePosition" => 12,
    "name" => clienttranslate("Whale Cleaner"),
    "description" => clienttranslate("After a species with forage 3 or more forages, gains 3 if this is the closest Whale Cleaner on either side."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 3,
    "migrate" => 7
  ),
  1114 => array(
    "imagePosition" => 12,
    "name" => clienttranslate("Whale Cleaner"),
    "description" => clienttranslate("After a species with forage 3 or more forages, gains 3 if this is the closest Whale Cleaner on either side."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 3,
    "migrate" => 7
  ),
  1115 => array(
    "imagePosition" => 12,
    "name" => clienttranslate("Whale Cleaner"),
    "description" => clienttranslate("After a species with forage 3 or more forages, gains 3 if this is the closest Whale Cleaner on either side."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 3,
    "migrate" => 8
  ),
  1116 => array(
    "imagePosition" => 12,
    "name" => clienttranslate("Whale Cleaner"),
    "description" => clienttranslate("After a species with forage 3 or more forages, gains 3 if this is the closest Whale Cleaner on either side."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 3,
    "migrate" => 8
  ),
  1117 => array(
    "imagePosition" => 12,
    "name" => clienttranslate("Whale Cleaner"),
    "description" => clienttranslate("After a species with forage 3 or more forages, gains 3 if this is the closest Whale Cleaner on either side."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 3,
    "migrate" => 9
  ),
  1118 => array(
    "imagePosition" => 12,
    "name" => clienttranslate("Whale Cleaner"),
    "description" => clienttranslate("After a species with forage 3 or more forages, gains 3 if this is the closest Whale Cleaner on either side."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 3,
    "migrate" => 9
  ),
  1119 => array(
    "imagePosition" => 12,
    "name" => clienttranslate("Whale Cleaner"),
    "description" => clienttranslate("After a species with forage 3 or more forages, gains 3 if this is the closest Whale Cleaner on either side."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 3,
    "migrate" => 10
  ),
  1120 => array(
    "imagePosition" => 12,
    "name" => clienttranslate("Whale Cleaner"),
    "description" => clienttranslate("After a species with forage 3 or more forages, gains 3 if this is the closest Whale Cleaner on either side."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 3,
    "migrate" => 11
  ),
);

$this->scenarioCards = array(
  2001 => array(
    "title" => clienttranslate("Abundance"),
    "description" => clienttranslate("Traits with gains get [Gain +1]"),
    "type" => null
  ),
  2002 => array(
    "title" => clienttranslate("Asteroid Impact"),
    "description" => clienttranslate("Species without a Deep trait lose 3 population to the Reef."),
    "type" => clienttranslate("Event — Aggressive — Complex")
  ),
  2003 => array(
    "title" => clienttranslate("Biodiverse Reef"),
    "description" => clienttranslate("Species get forage 2."),
    "type" => null
  ),
  2004 => array(
    "title" => clienttranslate("Contagious Outbreak"),
    "description" => clienttranslate("After a species overpopulates, adjacent species lose 3 population to the Reef."),
    "type" => clienttranslate("Aggressive — Complex")
  ),
  2005 => array(
    "title" => clienttranslate("Contagious Proximity"),
    "description" => clienttranslate("Species store 3 less population on their species boards."),
    "type" => clienttranslate("Complex")
  ),
  2006 => array(
    "title" => clienttranslate("Degenerative Virus"),
    "description" => clienttranslate("During aging, choose a species to age an additional time."),
    "type" => null
  ),
  2007 => array(
    "title" => clienttranslate("Detritus"),
    "description" => clienttranslate("Players place 2 population from their score pile into the Reef after a migrate action is taken."),
    "type" => null
  ),
  2008 => array(
    "title" => clienttranslate("Epizootic"),
    "description" => clienttranslate("Species go extinct when they overpopulate. Place all population in the Reef."),
    "type" => clienttranslate("Complex 2")
  ),
  2009 => array(
    "title" => clienttranslate("Fertile"),
    "description" => clienttranslate("New species gain 2."),
    "type" => null
  ),
  2010 => array(
    "title" => clienttranslate("Food Surge"),
    "description" => clienttranslate("Species double their population. Take from any Ocean zone. "),
    "type" => clienttranslate("Event — Complex 2")
  ),
  2011 => array(
    "title" => clienttranslate("Genetic Diversity"),
    "description" => clienttranslate("The maximum number of traits per species is increased by one."),
    "type" => null
  ),
  2012 => array(
    "title" => clienttranslate("Inhospitable Environment"),
    "description" => clienttranslate("Aging +1"),
    "type" => clienttranslate("Complex")
  ),
  2013 => array(
    "title" => clienttranslate("Inhospitable Environment"),
    "description" => clienttranslate("Aging +1"),
    "type" => clienttranslate("Complex")
  ),
  2014 => array(
    "title" => clienttranslate("Inhospitable Environment"),
    "description" => clienttranslate("Aging +2"),
    "type" => clienttranslate("Complex 2")
  ),
  2015 => array(
    "title" => clienttranslate("Large Predators"),
    "description" => clienttranslate("Species get [Attack 2]."),
    "type" => clienttranslate("Aggressive")
  ),
  2016 => array(
    "title" => clienttranslate("Long Haul Migration"),
    "description" => clienttranslate("Species may forage from any Ocean zone."),
    "type" => null
  ),
  2017 => array(
    "title" => clienttranslate("Paradigm Shift"),
    "description" => clienttranslate("Species with the most population lose all population to the Reef."),
    "type" => clienttranslate("Event — Aggressive — Complex")
  ),
  2018 => array(
    "title" => clienttranslate("Pathogenic Immunity"),
    "description" => clienttranslate("Species never overpopulate."),
    "type" => null
  ),
  2019 => array(
    "title" => clienttranslate("Population Explosion"),
    "description" => clienttranslate("Every [Gain] triggers."),
    "type" => clienttranslate("Event")
  ),
  2020 => array(
    "title" => clienttranslate("Prosperity"),
    "description" => clienttranslate("Species may store 9 extra population on their species boards."),
    "type" => null
  ),
  2021 => array(
    "title" => clienttranslate("Protective Growth"),
    "description" => clienttranslate("Species gain 2 after they evolve a defensive trait."),
    "type" => null
  ),
  2022 => array(
    "title" => clienttranslate("Protective Shells"),
    "description" => clienttranslate("Species get [Defense 2]."),
    "type" => null
  ),
  2023 => array(
    "title" => clienttranslate("Shallow Gene Pool"),
    "description" => clienttranslate("The maximum number of traits per species is reduced by one."),
    "type" => clienttranslate("Complex")
  ),
  2024 => array(
    "title" => clienttranslate("Solar Radiation"),
    "description" => clienttranslate("After each aging phase, species without a Deep trait lose 1 population to the Reef."),
    "type" => clienttranslate("Complex 2")
  ),
  2025 => array(
    "title" => clienttranslate("Thermal Currents"),
    "description" => clienttranslate("On your turn, you may evolve traits directly from the Gene Pool."),
    "type" => clienttranslate("Complex")
  ),
  2026 => array(
    "title" => clienttranslate("Aggressive Environment"),
    "description" => clienttranslate("Species with 0 population may be attacked. They immediately go extinct before any traits trigger."),
    "type" => clienttranslate("Aggressive 3")
  ),
  2027 => array(
    "title" => clienttranslate("Coral Bleaching"),
    "description" => clienttranslate("After your aging phase, place 2 population from the Reef onto any Ocean zone."),
    "type" => null
  ),
  2028 => array(
    "title" => clienttranslate("Evolutionary Arms Race"),
    "description" => clienttranslate("Species may not attack a target whose [Attack] or [Forage] is greater than their own [Attack]."),
    "type" => null
  ),
  2029 => array(
    "title" => clienttranslate("Horizontal Gene Transfer"),
    "description" => clienttranslate("Before your feeding phase, you may swap one of your traits with a trait on an adjacent species."),
    "type" => clienttranslate("Aggressive 2 — Complex 2")
  ),
  2030 => array(
    "title" => clienttranslate("Hostile Conditions"),
    "description" => clienttranslate("Aging occurs for the active player and the player to their right."),
    "type" => clienttranslate("Aggressive 2 — Complex 2")
  ),
  2031 => array(
    "title" => clienttranslate("Hostile Environment"),
    "description" => clienttranslate("Aging +3"),
    "type" => clienttranslate("Complex 4")
  ),
  2032 => array(
    "title" => clienttranslate("Lazy Creator"),
    "description" => clienttranslate("Before the game, decide as a group what this card says."),
    "type" => clienttranslate("Event (maybe) — Complex 3")
  ),
  2033 => array(
    "title" => clienttranslate("Mass Migration"),
    "description" => clienttranslate("Slide the leftmost species of each player to the opponent on their left."),
    "type" => clienttranslate("Event — Aggressive")
  ),
  2034 => array(
    "title" => clienttranslate("Parallel Universe"),
    "description" => clienttranslate("On your turn, numbers on traits can be treated as 1 higher or 1 lower."),
    "type" => clienttranslate("Complex 4")
  ),
  2035 => array(
    "title" => clienttranslate("Prescient Mutations"),
    "description" => clienttranslate("During the next player’s turn, you may exchange 1 Surface card in your hand for 1 card in the discard
    pile."),
    "type" => clienttranslate("Complex 2")
  ),
  2036 => array(
    "title" => clienttranslate("Radiation Blast"),
    "description" => clienttranslate("Players shuffle the traits on their species and play them randomly on their species one at a time."),
    "type" => clienttranslate("Event")
  ),
  2037 => array(
    "title" => clienttranslate("Schadenfreude"),
    "description" => clienttranslate("When a species goes extinct, each other player’s species gain 1."),
    "type" => clienttranslate("Complex")
  ),
  2038 => array(
    "title" => clienttranslate("Shallow Reef"),
    "description" => clienttranslate("Species lose 2 population to the Reef after foraging."),
    "type" => null
  ),
  2039 => array(
    "title" => clienttranslate("Snowball Earth"),
    "description" => clienttranslate("The [Attack] and [Forage] of every trait is reduced by 1."),
    "type" => clienttranslate("Complex")
  ),
  2040 => array(
    "title" => clienttranslate("Uber Hostile Environment"),
    "description" => clienttranslate("Aging happens to every species in play."),
    "type" => clienttranslate("Aggressive 3 — Complex 3")
  )
);

$this->deepCards = array(
  3001 => array(
    "artist" => "Damien Mammoliti",
    "title" => clienttranslate("Abyss Dweller"),
    "description" => clienttranslate("May not attack or be attacked by species with a Surface trait. Evolves other Deep traits for free."),
    "forage" => 0,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "resolution" => 1
  ),
  3002 => array(
    "artist" => "Tomasz Jedruszek",
    "title" => clienttranslate("Acute Smell"),
    "description" => clienttranslate("Gains 1 for each Shark Cleaner that triggers."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 1,
    "resolution" => 2
  ),
  3003 => array(
    "artist" => "Guillaume Ducos",
    "title" => clienttranslate("Advanced Eyes"),
    "description" => clienttranslate("Ignores 1 defensive trait when attacking a species with a Deep trait."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "resolution" => 1
  ),
  3004 => array(
    "artist" => "Guillaume Ducos",
    "title" => clienttranslate("Ambush"),
    "description" => clienttranslate("After each species forages, take 2 population from it."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "resolution" => 3
  ),
  3005 => array(
    "artist" => "Catherine Hamilton",
    "title" => clienttranslate("Apex Predator"),
    "description" => null,
    "forage" => 0,
    "attack" => 4,
    "defense" => 3,
    "gains" => 0,
    "resolution" => 1
  ),
  3006 => array(
    "artist" => "Tomasz Jedruszek",
    "title" => clienttranslate("Atavism"),
    "description" => clienttranslate("May have 2 extra traits.\nBefore your feeding phase, may swap traits with Surface cards in your hand."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "resolution" => 1
  ),
  3007 => array(
    "artist" => "Guillaume Ducos",
    "title" => clienttranslate("Barbels"),
    "description" => clienttranslate("Gains 1 for each Bottom Feeder that triggers."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 1,
    "resolution" => 1
  ),
  3008 => array(
    "artist" => "Tomasz Jedruszek",
    "title" => clienttranslate("Behemoth"),
    "description" => clienttranslate("Ignores \"can't be attacked\" effects if this species has a greeter [attack] than the target."),
    "forage" => 0,
    "attack" => 3,
    "defense" => 0,
    "gains" => 0,
    "resolution" => 2
  ),
  3009 => array(
    "artist" => "Katherine Souza",
    "title" => clienttranslate("Bioluminescence"),
    "description" => clienttranslate("Gains 1 after a Deep trait is played on a species."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 1,
    "resolution" => 2
  ),
  3010 => array(
    "artist" => "Guillaume Ducos",
    "title" => clienttranslate("Biotic Cycle Reversal"),
    "description" => clienttranslate("Gains 3 after an attack reduces this species to 0 population."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 3,
    "resolution" => 2
  ),
  3011 => array(
    "artist" => "Katherine Souza",
    "title" => clienttranslate("Blubber"),
    "description" => clienttranslate("May store 9 extra population on the species board."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 1,
    "gains" => 0,
    "resolution" => 1
  ),
  3012 => array(
    "artist" => "Catherine Hamilton",
    "title" => clienttranslate("Bottom Feeder"),
    "description" => clienttranslate("Gains 2 after an adjacent species loses population due to an attack."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 2,
    "resolution" => 1
  ),
  3013 => array(
    "artist" => "Katherine Souza",
    "title" => clienttranslate("Bright Coloration"),
    "description" => clienttranslate("Gains 1 after a new species is created."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 1,
    "resolution" => 1
  ),
  3014 => array(
    "artist" => "Guillaume Ducos",
    "title" => clienttranslate("Bubble Net"),
    "description" => clienttranslate("Ignores its own [attack 0] and [attack]. Uses [forage] when evaluating this species' [attack]. Ignores Schooling when attacking."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "resolution" => 1
  ),
  3015 => array(
    "artist" => "Tomasz Jedruszek",
    "title" => clienttranslate("Burrower"),
    "description" => clienttranslate("After an attack an adjacent species is resolved, if the attacker is a valid target, this species may attack it once."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "resolution" => 1
  ),
  3016 => array(
    "artist" => "Guillaume Ducos",
    "title" => clienttranslate("Camouflage"),
    "description" => clienttranslate("[Defense +2] for each other species you have."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "resolution" => 1
  ),
  3017 => array(
    "artist" => "Katherine Souza",
    "title" => clienttranslate("Cavitation Bullet"),
    "description" => clienttranslate("Never takes more than 2 population from an attack.\nIgnores all defensive traits when attacking."),
    "forage" => 1,
    "attack" => 2,
    "defense" => 0,
    "gains" => 0,
    "resolution" => 1
  ),
  3018 => array(
    "artist" => "Guillaume Ducos",
    "title" => clienttranslate("Chitin-Plated Maw"),
    "description" => clienttranslate("Before your feeding phase, leeches 4 from the species with the greatest [attack]. Pick one if there is a tie."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "resolution" => 4
  ),
  3019 => array(
    "artist" => "Tomasz Jedruszek",
    "title" => clienttranslate("Colossus"),
    "description" => clienttranslate("Can't be attacked by a species whose [attack] is less than this species' [forage]\nMay take less population than its [forage]."),
    "forage" => 11,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "resolution" => 4
  ),
  3020 => array(
    "artist" => "Guillaume Ducos",
    "title" => clienttranslate("Communication"),
    "description" => clienttranslate("May have 1 extra trait.\nAllgains are doubled."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 5,
    "gains" => 'x2',
    "resolution" => 3
  ),
  3021 => array(
    "artist" => "Damien Mammoliti",
    "title" => clienttranslate("Coprophagia"),
    "description" => clienttranslate("Before your feeding phase, takes 2 population from any player's score pile onto its species board."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "resolution" => 1
  ),
  3022 => array(
    "artist" => "Guillaume Ducos",
    "title" => clienttranslate("Crushing Jaw"),
    "description" => clienttranslate("Ignores all [defense] when attacking."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "resolution" => 1
  ),
  3023 => array(
    "artist" => "Damien Mammoliti",
    "title" => clienttranslate("Cthulhu Leech"),
    "description" => clienttranslate("Play on any plater's species. It counts as a trait and can't be removed or copied. You score 2 of its population before it Ages."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "resolution" => 2
  ),
  3024 => array(
    "artist" => "Tomasz Jedruszek",
    "title" => clienttranslate("Deep Diver"),
    "description" => clienttranslate("[forage +2] for each Deep trait on this species. May forage from any Ocean zone."),
    "forage" => 0,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "resolution" => 3
  ),
  3025 => array(
    "artist" => "Álvaro Nebot",
    "title" => clienttranslate("Deep-Sea Kraken"),
    "description" => clienttranslate("May attack 1 additional time.\nPopulation from each attack after the first goes directly to your score pile."),
    "forage" => 0,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "resolution" => 3
  ),
  3026 => array(
    "artist" => "Katherine Souza",
    "title" => clienttranslate("Defensive Spines"),
    "description" => clienttranslate("Species lose 4 population to the Reef after attacking or leeching from this species."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "resolution" => 1
  ),
  3027 => array(
    "artist" => "Alavaro Nebot",
    "title" => clienttranslate("Dense Population"),
    "description" => clienttranslate("May have 1 extra trait.\nMay store 9 extra population on the species board."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "resolution" => 2
  ),
  3028 => array(
    "artist" => "Damien Mammoliti",
    "title" => clienttranslate("Draconic"),
    "description" => clienttranslate("Overpopulation is scored instead of lost."),
    "forage" => 0,
    "attack" => 7,
    "defense" => 3,
    "gains" => 0,
    "resolution" => 4
  ),
  3029 => array(
    "artist" => "Guillaume Ducos",
    "title" => clienttranslate("Electroreception"),
    "description" => clienttranslate("Ignores 1 defensive trait when attacking."),
    "forage" => 2,
    "attack" => 2,
    "defense" => 0,
    "gains" => 0,
    "resolution" => 2
  ),
  3030 => array(
    "artist" => "Guillaume Ducos",
    "title" => clienttranslate("Endurance"),
    "description" => clienttranslate("Aging +2\nTakes 2 extra population when it gains."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => '+2',
    "resolution" => 3
  ),
  3031 => array(
    "artist" => "Guillaume Ducos",
    "title" => clienttranslate("Extremophile"),
    "description" => clienttranslate("May have 1 extra trait.\nCan't be leeched.\n Never overpopulates.\nNever goes extinct."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "resolution" => 1
  ),
  3032 => array(
    "artist" => "Tomasz Jedruszek",
    "title" => clienttranslate("Fast Metabolism"),
    "description" => clienttranslate("May Age on any player's Aging phase."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "resolution" => 1
  ),
  3033 => array(
    "artist" => "Catherine Hamilton",
    "title" => clienttranslate("Filter Feeder"),
    "description" => clienttranslate("Never overpopulates.\nMay store 5 extra population on the species board."),
    "forage" => 6,
    "attack" => 0,
    "defense" => 0,
    "gains" => 0,
    "resolution" => 2
  ),
  3034 => array(
    "artist" => "Tomasz Jedruszek",
    "title" => clienttranslate("Filter Kraken"),
    "description" => clienttranslate("May forage 2 additional times."),
    "forage" => 1,
    "attack" => 0,
    "defense" => 0,
    "gains" => 0,
    "resolution" => 1
  ),
  3035 => array(
    "artist" => "Isaac Fryxelius",
    "title" => clienttranslate("Flight"),
    "description" => clienttranslate("Can't be attacked unless the attacker has Speed.\nIgnores Schooling and Inking when attacking a species without a Deep trait."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "resolution" => 1
  ),
  3036 => array(
    "artist" => "Jon Hodgson",
    "title" => clienttranslate("Gargantuan"),
    "description" => clienttranslate("Can't attack a species unless that species has at least 7 population.\nIgnores \"can't be attacked\" effects."),
    "forage" => 0,
    "attack" => 9,
    "defense" => 0,
    "gains" => 0,
    "resolution" => 4
  ),
  3037 => array(
    "artist" => "Celeste Hansen",
    "title" => clienttranslate("Gene Transfer"),
    "description" => clienttranslate("May have 2 extra traits. Before your feeding phase, may swap Surface traits for traits on one of your adjacent species."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "resolution" => 1
  ),
  3038 => array(
    "artist" => "Álvaro Nebot",
    "title" => clienttranslate("Gentle Giant"),
    "description" => clienttranslate("Can't be attacked.\nMay store 5 extra population on the species board."),
    "forage" => 3,
    "attack" => 0,
    "defense" => 0,
    "gains" => 0,
    "resolution" => 2
  ),
  3039 => array(
    "artist" => "Guillaume Ducos",
    "title" => clienttranslate("Giant Cephalopod"),
    "description" => clienttranslate("May feed 3 additional times."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "resolution" => 3
  ),
  3040 => array(
    "artist" => "Isaac Fryxelius",
    "title" => clienttranslate("Gigantic Brain"),
    "description" => clienttranslate("May have any number of traits.\nAges population equal to the number of traits it has. OTher effects may never change this amount."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "resolution" => 4
  ),
  3041 => array(
    "artist" => "Tomasz Jedruszek",
    "title" => clienttranslate("Gigantic Scavenger"),
    "description" => clienttranslate("After a species with [attack 5] or more attacks, takes 2 population from that species."),
    "forage" => 0,
    "attack" => 6,
    "defense" => 0,
    "gains" => 0,
    "resolution" => 4,
    "no_forage" => true
  ),
  3042 => array(
    "artist" => "Yoann Boissonnet",
    "title" => clienttranslate("Goethite Exoskeleton"),
    "description" => clienttranslate("May have 1 extra trait.\nCan't be attacked by a species with less than [attack 9]"),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "resolution" => 1
  ),
  3043 => array(
    "artist" => "Guillaume Ducos",
    "title" => clienttranslate("Grazer"),
    "description" => clienttranslate("Before your feeding phase, takes 2 population from the Reef."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "resolution" => 1
  ),
  3044 => array(
    "artist" => "Yoann Boissonnet",
    "title" => clienttranslate("Hard Shell"),
    "description" => clienttranslate("Before your feeding phase, takes 2 population from the Reef."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 6,
    "gains" => 0,
    "resolution" => 1
  ),
  3045 => array(
    "artist" => "Tomasz Jedruszek",
    "title" => clienttranslate("Hydra"),
    "description" => clienttranslate("When attacking, may also take 1 population from any number of species without a defensive trait."),
    "forage" => 0,
    "attack" => 4,
    "defense" => 0,
    "gains" => 0,
    "resolution" => 4,
    "no_forage" => true
  ),
  3046 => array(
    "artist" => "Álvaro Nebot",
    "title" => clienttranslate("Impostor Cleaner"),
    "description" => clienttranslate("After Shark Cleaners gain, takes 1 population from each of them."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "resolution" => 2
  ),
  3047 => array(
    "artist" => "Álvaro Nebot",
    "title" => clienttranslate("Impostor Cleaner"),
    "description" => clienttranslate("After Whale Cleaners gain, takes 1 population from each of them."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "resolution" => 1
  ),
  3048 => array(
    "artist" => "Catherine Hamilton",
    "title" => clienttranslate("Inking"),
    "description" => clienttranslate("Species lose 3 population to the Reef after attacking this species."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 4,
    "gains" => 0,
    "resolution" => 1
  ),
  3049 => array(
    "artist" => "Guillaume Ducos",
    "title" => clienttranslate("Kleptoparasitic"),
    "description" => clienttranslate("Aging +2\nAfter an adjacent species attacks, takes 1 population from that species."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "resolution" => 2
  ),
  3050 => array(
    "artist" => "Isaac Fryxelius",
    "title" => clienttranslate("Leviathan"),
    "description" => clienttranslate("Aging +1\nCan't be attacked."),
    "forage" => 0,
    "attack" => 5,
    "defense" => 0,
    "gains" => 0,
    "resolution" => 4,
    "no_forage" => true
  ),
  3051 => array(
    "artist" => "Tomasz Jedruszek",
    "title" => clienttranslate("Living Ecosystem"),
    "description" => clienttranslate("Before your feeding phase, migrate population equal to this species' [forage] from any Ocean zone to the Reef."),
    "forage" => 2,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "resolution" => 1
  ),
  3052 => array(
    "artist" => "Guillaume Ducos",
    "title" => clienttranslate("Luminous Bacteria"),
    "description" => clienttranslate("May have 1 extra trait.\nMay forage from any Ocean zone."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "resolution" => 1
  ),
  3053 => array(
    "artist" => "Guillaume Ducos",
    "title" => clienttranslate("Massive Fins"),
    "description" => clienttranslate("After this species forages, every Parasitic leeches 1 from it.\nMay forage from any Ocean zone."),
    "forage" => 8,
    "attack" => 0,
    "defense" => 0,
    "gains" => 0,
    "resolution" => 3,
    "no_attach" => true
  ),
  3054 => array(
    "artist" => "Tomasz Jedruszek",
    "title" => clienttranslate("Megamouth"),
    "description" => null,
    "forage" => 5,
    "attack" => 5,
    "defense" => 0,
    "gains" => 0,
    "resolution" => 1
  ),
  3055 => array(
    "artist" => "Catherine Hamilton",
    "title" => clienttranslate("Migratory Filter Feeder"),
    "description" => clienttranslate("May store 9 extra population on the species board.\nMay forage from any Ocean zone."),
    "forage" => 6,
    "attack" => 0,
    "defense" => 0,
    "gains" => 0,
    "resolution" => 5,
    "no_attack" => true
  ),
  3056 => array(
    "artist" => "Emily Hancock",
    "title" => clienttranslate("Mucus Cocoon"),
    "description" => clienttranslate("Can't be leeched.\nBefore your feeding phase, species with a leeching trait lose 1 population to the Reef."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "resolution" => 1
  ),
  3057 => array(
    "artist" => "Damien Mammoliti",
    "title" => clienttranslate("Mutualism"),
    "description" => clienttranslate("Gains 1 for each Symbiotic that triggers."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 1,
    "resolution" => 1
  ),
  3058 => array(
    "artist" => "Katherine Souza",
    "title" => clienttranslate("Neurotoxin"),
    "description" => clienttranslate("Species lose 2 population to the Reef after they attack or are attacked by this species."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "resolution" => 1
  ),
  3059 => array(
    "artist" => "Guillaume Ducos",
    "title" => clienttranslate("Pack Hunting"),
    "description" => clienttranslate("May have 1 extra trait.\nDoubles each [attack] on this species' traits."),
    "forage" => 1,
    "attack" => "x2",
    "defense" => 0,
    "gains" => 0,
    "resolution" => 1
  ),
  3060 => array(
    "artist" => "Guillaume Ducos",
    "title" => clienttranslate("Parasite Cleaner"),
    "description" => clienttranslate("Before your feeding phase takes 1 ppopulation from every species with a leeching trait."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "resolution" => 1
  ),
  3061 => array(
    "artist" => "Catherin Hamilton",
    "title" => clienttranslate("Parasitic"),
    "description" => clienttranslate("Before your feeding phase, leeches 2 from an adjacent species."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "resolution" => 1
  ),
  3062 => array(
    "artist" => "Damien Mammoliti",
    "title" => clienttranslate("Pathogen Cultivation"),
    "description" => clienttranslate("Can't be leeched.\nIgnores \"can't be attacked\" effects on species with a leeching trait."),
    "forage" => 0,
    "attack" => 5,
    "defense" => 0,
    "gains" => 0,
    "resolution" => 2,
    "no_forage" => true,
  ),
  3063 => array(
    "artist" => "Tomasz Jedruszek",
    "title" => clienttranslate("Poisonous Inking"),
    "description" => clienttranslate("This species and its adjacent species have [defense 4]"),
    "forage" => 1,
    "attack" => 1,
    "defense" => 4,
    "gains" => 0,
    "resolution" => 2
  ),
  3064 => array(
    "artist" => "Yoann Boissonnet",
    "title" => clienttranslate("Poisonous Spines"),
    "description" => clienttranslate("After being attacked, the attacker and each other species that gained from the attack lose 3 population to the Reef."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "resolution" => 1
  ),
  3065 => array(
    "artist" => "Catherine Hamilton",
    "title" => clienttranslate("Rapid Mutation"),
    "description" => clienttranslate("After being attacked, may swap a Deep trait on this species (including this trait) for a trait in the Gene Pool."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "resolution" => 1
  ),
  3066 => array(
    "artist" => "Yoann Boissonnet",
    "title" => clienttranslate("Regeneration"),
    "description" => clienttranslate("May have 1 extra trait.\nGains 1 after losing population due to an attack."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 1,
    "resolution" => 1
  ),
  3067 => array(
    "artist" => "Guillaume Ducos",
    "title" => clienttranslate("Rows of Teeth"),
    "description" => clienttranslate("When attacking, Bottom Feeders and Shark Cleaners that gain take an additional population."),
    "forage" => 1,
    "attack" => 3,
    "defense" => 0,
    "gains" => 0,
    "resolution" => 1
  ),
  3068 => array(
    "artist" => "Damien Mammoliti",
    "title" => clienttranslate("Scavenger"),
    "description" => clienttranslate("Gains 1 after a species loses 2 or more population due to an attack."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "resolution" => 1
  ),
  3069 => array(
    "artist" => "Catherine Hamilton",
    "title" => clienttranslate("Schooling"),
    "description" => clienttranslate("Aging +3\nCan't be attacked if this species has 5 or more population."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "resolution" => 2
  ),
  3070 => array(
    "artist" => "Tomasz Jedruszek",
    "title" => clienttranslate("Sea Rex"),
    "description" => clienttranslate("Aging +2"),
    "forage" => 0,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "resolution" => 2,
    "no_forage" => true
  ),
  3071 => array(
    "artist" => "Yoann Boissonnet",
    "title" => clienttranslate("Sedentary Colony"),
    "description" => clienttranslate("Can't be attacked. Takes 1 population from any Ocean zone when an adjacent species gains."),
    "forage" => 0,
    "attack" => 0,
    "defense" => 0,
    "gains" => 0,
    "resolution" => 2,
    "no_forage" => true,
    "no_attack" => true
  ),
  3072 => array(
    "artist" => "Catherine Hamilton",
    "title" => clienttranslate("Shark Cleaner"),
    "description" => clienttranslate("After a species with [attack 3] or more attacks, gains 3 if this is the closest Shark Cleaner on either side."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 3,
    "resolution" => 1
  ),
  3073 => array(
    "artist" => "Tomasz Jedruszek",
    "title" => clienttranslate("Slow Metabolism"),
    "description" => clienttranslate("May have 2 extra traits.\nMay age 0."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "resolution" => 1
  ),
  3074 => array(
    "artist" => "Catherine Hamilton",
    "title" => clienttranslate("Speed"),
    "description" => clienttranslate("May have 1 extra trait."),
    "forage" => 3,
    "attack" => 3,
    "defense" => 3,
    "gains" => 0,
    "resolution" => 2
  ),
  3075 => array(
    "artist" => "Tomas Jedruszek",
    "title" => clienttranslate("Swarming"),
    "description" => clienttranslate("Never overpopulates.\n[attack +1] for each population."),
    "forage" => 1,
    "attack" => 0,
    "defense" => 0,
    "gains" => 0,
    "resolution" => 1
  ),
  3076 => array(
    "artist" => "Catherine Hamilton",
    "title" => clienttranslate("Symbiotic"),
    "description" => clienttranslate("Gains 1 after an adjacent species forages or attacks."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 1,
    "resolution" => 1
  ),
  3077 => array(
    "artist" => "Yoann Boissonnet",
    "title" => clienttranslate("Tail Whip"),
    "description" => clienttranslate("Before your feeding phase, takes 1 population from each species with Schooling."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "resolution" => 1
  ),
  3078 => array(
    "artist" => "Tomasz Jedruszek",
    "title" => clienttranslate("Telomere Repair"),
    "description" => clienttranslate("Gains 2 before Aging.\nDoes not score during Aging."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 2,
    "resolution" => 1
  ),
  3079 => array(
    "artist" => "Tomasz Jedruszek",
    "title" => clienttranslate("Tentacle Leech"),
    "description" => clienttranslate("Before your feeding phase, leeches 2 from a species with a Deep trait."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "resolution" => 1
  ),
  3080 => array(
    "artist" => "Catherine Hamilton",
    "title" => clienttranslate("Tentacled"),
    "description" => clienttranslate("May feed 2 additional times."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "resolution" => 2
  ),
  3081 => array(
    "artist" => "Guillaume Ducos",
    "title" => clienttranslate("The Kraken"),
    "description" => clienttranslate("Ignores \"can't be attacked\" effects on species with at least [forage 5] or [attack 5].\nMay attack 2 additional times."),
    "forage" => 0,
    "attack" => 13,
    "defense" => 0,
    "gains" => 0,
    "resolution" => 5
  ),
  3082 => array(
    "artist" => "Isaac Fryxelius",
    "title" => clienttranslate("Tiny"),
    "description" => clienttranslate("Can't be attacked by a species with [attack 2] or greater.\nDiscard this trait if its [forage] or [attack] is greater than 2."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "resolution" => 1
  ),
  3083 => array(
    "artist" => "Catherine Hamilton",
    "title" => clienttranslate("Transparent"),
    "description" => clienttranslate("Can't be attacked or leeched if there is population in the Reef.\nIgnores \"can't be attacked\" effects on Deep traits."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "resolution" => 1
  ),
  3084 => array(
    "artist" => "Damien Mammoliti",
    "title" => clienttranslate("Tusks"),
    "description" => null,
    "forage" => 2,
    "attack" => 2,
    "defense" => 5,
    "gains" => 0,
    "resolution" => 1
  ),
  3085 => array(
    "artist" => "Isaac Fryxelius",
    "title" => clienttranslate("Vestigial Limb"),
    "description" => clienttranslate("Play on another player's species.\nIt counts as a trait and can't be removed.\nPlay another trait after playing this one."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "resolution" => 1
  ),
  3086 => array(
    "artist" => "Damien Mammoliti",
    "title" => clienttranslate("Voraious Feeder"),
    "description" => clienttranslate("May feed in addition to your normal feeding turn if you have played another trait on it this turn."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "resolution" => 1
  ),
  3087 => array(
    "artist" => "Álvaro Nebot",
    "title" => clienttranslate("Warm Blooded"),
    "description" => clienttranslate("May have 1 extra trait.\nMay Age an additional 1-3 population."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 0,
    "resolution" => 2
  ),
  3088 => array(
    "artist" => "Catherine Hamilton",
    "title" => clienttranslate("Whale Cleaner"),
    "description" => clienttranslate("After a species with [forage 5] or more forages, gains 4 if this is the closest Whale Cleaner on either side."),
    "forage" => 1,
    "attack" => 1,
    "defense" => 0,
    "gains" => 4,
    "resolution" => 1
  ),
  3089 => array(
    "artist" => "Yoann Boissonnet",
    "title" => clienttranslate("Zooplankton"),
    "description" => clienttranslate("Can't be attacked.\nGains 3 after population is migrated to the Reef.\nAdjacent species may forage from this species board."),
    "forage" => 0,
    "attack" => 0,
    "defense" => 0,
    "gains" => 3,
    "resolution" => 1
  )
);
