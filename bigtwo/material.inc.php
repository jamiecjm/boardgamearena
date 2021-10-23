<?php

/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * BigTwo implementation : © <Your name here> <Your email address here>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * material.inc.php
 *
 * BigTwo game material description
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

$this->suits = array(
  1 => array(
    'name' => clienttranslate('diamonds'),
    'nametr' => self::_('diamonds')
  ),
  2 => array(
    'name' => clienttranslate('clubs'),
    'nametr' => self::_('clubs')
  ),
  3 => array(
    'name' => clienttranslate('hearts'),
    'nametr' => self::_('hearts')
  ),
  4 => array(
    'name' => clienttranslate('spades'),
    'nametr' => self::_('spades')
  ),
);

$this->ranks_label = array(
  3 => '3',
  4 => '4',
  5 => '5',
  6 => '6',
  7 => '7',
  8 => '8',
  9 => '9',
  10 => '10',
  11 => clienttranslate('J'),
  12 => clienttranslate('Q'),
  13 => clienttranslate('K'),
  14 => clienttranslate('A'),
  15 => '2'
);
