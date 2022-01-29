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
    "description" => clienttranslate("g"),
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

$this->scenarioCards = array(
  2001 => array(
    "title" => clienttranslate("Abundance"),
    "description" => clienttranslate("Traits with gains get [Gain +1]")
  ),
  2002 => array(
    "title" => clienttranslate("Asteroid Impact"),
    "description" => clienttranslate("Species without a Deep trait lose 3 population to the Reef."),
    "event" => true,
    "aggressive" => 1,
    "complex" => 1
  ),
  2003 => array(
    "title" => clienttranslate("Biodiverse Reef"),
    "description" => clienttranslate("Species get forage 2.")
  ),
  2004 => array(
    "title" => clienttranslate("Contagious Outbreak"),
    "description" => clienttranslate("After a species overpopulates, adjacent species lose 3 population to the Reef."),
    "aggressive" => 1,
    "complex" => 1
  ),
  2005 => array(
    "title" => clienttranslate("Contagious Proximity"),
    "description" => clienttranslate("Species store 3 less population on their species boards."),
    "complex" => 1
  ),
  2006 => array(
    "title" => clienttranslate("Degenerative Virus"),
    "description" => clienttranslate("During aging, choose a species to age an additional time.")
  ),
  2007 => array(
    "title" => clienttranslate("Detritus"),
    "description" => clienttranslate("Players place 2 population from their score pile into the Reef after a migrate action is taken.")
  ),
  2008 => array(
    "title" => clienttranslate("Epizootic"),
    "description" => clienttranslate("Species go extinct when they overpopulate. Place all population in the Reef."),
    "complex" => 2
  ),
  2009 => array(
    "title" => clienttranslate("Fertile"),
    "description" => clienttranslate("New species gain 2.")
  ),
  2010 => array(
    "title" => clienttranslate("Food Surge"),
    "description" => clienttranslate("Species double their population. Take from any Ocean zone. "),
    "event" => true,
    "complex" => 2
  ),
  2011 => array(
    "title" => clienttranslate("Genetic Diversity"),
    "description" => clienttranslate("The maximum number of traits per species is increased by one.")
  ),
  2012 => array(
    "title" => clienttranslate("Inhospitable Environment"),
    "description" => clienttranslate("Aging +1"),
    "complex" => 1
  ),
  2013 => array(
    "title" => clienttranslate("Inhospitable Environment"),
    "description" => clienttranslate("Aging +1"),
    "complex" => 1
  ),
  2014 => array(
    "title" => clienttranslate("Inhospitable Environment"),
    "description" => clienttranslate("Aging +2"),
    "complex" => 2
  ),
  2015 => array(
    "title" => clienttranslate("Large Predators"),
    "description" => clienttranslate("Species get [Attack 2]."),
    "aggressive" => 1
  ),
  2016 => array(
    "title" => clienttranslate("Long Haul Migration"),
    "description" => clienttranslate("Species may forage from any Ocean zone.")
  ),
  2017 => array(
    "title" => clienttranslate("Paradigm Shift"),
    "description" => clienttranslate("Species with the most population lose all population to the Reef."),
    "event" => true,
    "aggressive" => 1,
    "complex" => 1
  ),
  2018 => array(
    "title" => clienttranslate("Pathogenic Immunity"),
    "description" => clienttranslate("Species never overpopulate.")
  ),
  2019 => array(
    "title" => clienttranslate("Population Explosion"),
    "description" => clienttranslate("Every [Gain] triggers."),
    "event" => true
  ),
  2020 => array(
    "title" => clienttranslate("Prosperity"),
    "description" => clienttranslate("Species may store 9 extra population on their species boards.")
  ),
  2021 => array(
    "title" => clienttranslate("Protective Growth"),
    "description" => clienttranslate("Species gain 2 after they evolve a defensive trait.")
  ),
  2022 => array(
    "title" => clienttranslate("Protective Shells"),
    "description" => clienttranslate("Species get [Defense 2].")
  ),
  2023 => array(
    "title" => clienttranslate("Shallow Gene Pool"),
    "description" => clienttranslate("The maximum number of traits per species is reduced by one."),
    "complex" => 1
  ),
  2024 => array(
    "title" => clienttranslate("Solar Radiation"),
    "description" => clienttranslate("After each aging phase, species without a Deep trait lose 1 population to the Reef."),
    "complex" => 2
  ),
  2025 => array(
    "title" => clienttranslate("Thermal Currents"),
    "description" => clienttranslate("On your turn, you may evolve traits directly from the Gene Pool."),
    "complex" => 1
  ),
  2026 => array(
    "title" => clienttranslate("Aggressive Environment"),
    "description" => clienttranslate("Species with 0 population may be attacked. They immediately go extinct before any traits trigger."),
    "aggressive" => 3
  ),
  2027 => array(
    "title" => clienttranslate("Coral Bleaching"),
    "description" => clienttranslate("After your aging phase, place 2 population from the Reef onto any Ocean zone.")
  ),
  2028 => array(
    "title" => clienttranslate("Evolutionary Arms Race"),
    "description" => clienttranslate("Species may not attack a target whose [Attack] or [Forage] is greater than their own [Attack].")
  ),
  2029 => array(
    "title" => clienttranslate("Horizontal Gene Transfer"),
    "description" => clienttranslate("Before your feeding phase, you may swap one of your traits with a trait on an adjacent species."),
    "aggressive" => 2,
    "complex" => 2
  ),
  2030 => array(
    "title" => clienttranslate("Hostile Conditions"),
    "description" => clienttranslate("Aging occurs for the active player and the player to their right."),
    "aggressive" => 2,
    "complex" => 2
  ),
  2031 => array(
    "title" => clienttranslate("Hostile Environment"),
    "description" => clienttranslate("Aging +3")
  ),
  2032 => array(
    "title" => clienttranslate("Lazy Creator"),
    "description" => clienttranslate("Before the game, decide as a group what this card says."),
    "event" => true,
    "complex" => 3
  ),
  2033 => array(
    "title" => clienttranslate("Mass Migration"),
    "description" => clienttranslate("Slide the leftmost species of each player to the opponent on their left."),
    "event" => true,
    "aggressive" => 1
  ),
  2034 => array(
    "title" => clienttranslate("Parallel Universe"),
    "description" => clienttranslate("On your turn, numbers on traits can be treated as 1 higher or 1 lower."),
    "complex" => 4
  ),
  2035 => array(
    "title" => clienttranslate("Prescient Mutations"),
    "description" => clienttranslate("During the next player’s turn, you may exchange 1 Surface card in your hand for 1 card in the discard
    pile."),
    "complex" => 2
  ),
  2036 => array(
    "title" => clienttranslate("Radiation Blast"),
    "description" => clienttranslate("Players shuffle the traits on their species and play them randomly on their species one at a time."),
    "event" => true
  ),
  2037 => array(
    "title" => clienttranslate("Schadenfreude"),
    "description" => clienttranslate("When a species goes extinct, each other player’s species gain 1."),
    "complex" => 1
  ),
  2038 => array(
    "title" => clienttranslate("Shallow Reef"),
    "description" => clienttranslate("Species lose 2 population to the Reef after foraging.")
  ),
  2039 => array(
    "title" => clienttranslate("Snowball Earth"),
    "description" => clienttranslate("The [Attack] and [Forage] of every trait is reduced by 1."),
    "complex" => 1
  ),
  2040 => array(
    "title" => clienttranslate("Uber Hostile Environment"),
    "description" => clienttranslate("Aging happens to every species in play."),
    "aggressive" => 3,
    "complex" => 3
  )
);
