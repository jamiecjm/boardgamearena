<?php
/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * viticulture implementation : © Leo Bartoloni bartololeo74@gmail.com
 * 
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * material.inc.php
 *
 * viticulture game material description
 *
 * Here, you can describe the material of your game with PHP variables.
 *   
 * This file is loaded in your game logic class constructor, ie these variables
 * are available everywhere in your game logic code.
 *
 */


$this->decks = array(
  DECK_GREEN => array("discard"=>DISCARD_GREEN, "cardType"=>"greenCard"),
  DECK_YELLOW => array("discard"=>DISCARD_YELLOW, "cardType"=>"yellowCard"),
  DECK_BLUE => array("discard"=>DISCARD_BLUE, "cardType"=>"blueCard"),
  DECK_PURPLE => array("discard"=>DISCARD_PURPLE, "cardType"=>"purpleCard"),
  DECK_AUTOMA => array("discard"=>DISCARD_AUTOMA, "cardType"=>"automaCard")
);

$this->fields = array(
  1=>array("key"=>1,"maxValue"=>5, "dbField"=>"field1", "location"=>"vine1", "price" => 5),
  2=>array("key"=>2,"maxValue"=>6, "dbField"=>"field2", "location"=>"vine2", "price" => 6),
  3=>array("key"=>3,"maxValue"=>7, "dbField"=>"field3", "location"=>"vine3", "price" => 7)
);

$this->grapes = array(
  "grapeRed" => array("color" => "red", "colorAbbr"=>"r"),
  "grapeWhite" => array("color" => "white", "colorAbbr"=>"w")
);

$this->grapePrice = array(1=>1, 2=>1, 3=>1, 4=>2, 5=>2, 6=>2, 7=>3, 8=>3, 9=>3);

$this->seasons = array(
  0 => '',
  1 => clienttranslate('Spring'),
  2 => clienttranslate('Summer'),
  3 => clienttranslate('Fall'),
  4 => clienttranslate('Winter')
);

$this->soloParameters = array(
  1 => array( // Very Easy
    'description'=>clienttranslate('Very Easy'),
    'turns'=>8,
    'targetScore'=>20,
    'startScoring'=>0,
    'occupyAtLeastTwoLocations'=>0,
    'aggressive'=>array(-1,0,1,4,8,13,20,20)
  ),
  2 => array( // Easy
    'description'=>clienttranslate('Easy'),
    'turns'=>7,
    'targetScore'=>20,
    'startScoring'=>3,
    'occupyAtLeastTwoLocations'=>0,
    'aggressive'=>array(-1,0,1,4,8,13,20)
  ),
  3 => array( // Normal
    'description'=>clienttranslate('Normal'),
    'turns'=>7,
    'targetScore'=>20,
    'startScoring'=>0,
    'occupyAtLeastTwoLocations'=>0,
    'aggressive'=>array(-1,0,1,4,8,13,20)
  ),
  4 => array( // Hard
    'description'=>clienttranslate('Hard'),
    'turns'=>7,
    'targetScore'=>20,
    'startScoring'=>0,
    'occupyAtLeastTwoLocations'=>1,
    'aggressive'=>array(-1,0,1,4,8,13,20)
  ),
  5 => array( // Very Hard
    'description'=>clienttranslate('Very Hard'),
    'turns'=>7,
    'targetScore'=>23,
    'startScoring'=>0,
    'occupyAtLeastTwoLocations'=>1,
    'aggressive'=>array(-1,0,1,4,8,13,23)
  )
);

// possible wines by medium_cellar+large_cellar
// 0 : initial cellar
// 1 : medium cellar
// 2 : large cellar
$this->wines = array(
  0 => array(
    'wineRed'=>array('type'=>'wineRed', 'min'=>1, 'max'=>3),
    'wineWhite'=>array('type'=>'wineWhite', 'min'=>1, 'max'=>3),
    'wineBlush'=>array('type'=>'wineBlush', 'min'=>0, 'max'=>0),
    'wineSparkling'=>array('type'=>'wineSparkling', 'min'=>0, 'max'=>0)
  ),
  1 => array(
    'wineRed'=>array('type'=>'wineRed', 'min'=>1, 'max'=>6),
    'wineWhite'=>array('type'=>'wineWhite', 'min'=>1, 'max'=>6),
    'wineBlush'=>array('type'=>'wineBlush', 'min'=>4, 'max'=>6),
    'wineSparkling'=>array('type'=>'wineSparkling', 'min'=>0, 'max'=>0)
  ),
  2 => array(
    'wineRed'=>array('type'=>'wineRed', 'min'=>1, 'max'=>9),
    'wineWhite'=>array('type'=>'wineWhite', 'min'=>1, 'max'=>9),
    'wineBlush'=>array('type'=>'wineBlush', 'min'=>4, 'max'=>9),
    'wineSparkling'=>array('type'=>'wineSparkling', 'min'=>7, 'max'=>9)
  )
);

////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////
// player components
$this->playerTokens = array(
  1=>array(    "key"=>1,    "name"=>clienttranslate("Grande Worker"),    "type"=>"worker_g",    "type_arg"=>1,    "location"=>"player",    "set"=>0,    "isBuilding"=>false,    "price"=>4,    "automa"=>"player"  ),
  2=>array(    "key"=>2,    "name"=>clienttranslate("Worker"),    "type"=>"worker_1",    "type_arg"=>2,    "location"=>"player",    "set"=>0,    "isBuilding"=>false,    "price"=>4,    "automa"=>"player"  ),
  3=>array(    "key"=>3,    "name"=>clienttranslate("Worker"),    "type"=>"worker_2",    "type_arg"=>3,    "location"=>"player",    "set"=>0,    "isBuilding"=>false,    "price"=>4,    "automa"=>"player"  ),
  4=>array(    "key"=>4,    "name"=>clienttranslate("Worker"),    "type"=>"worker_3",    "type_arg"=>4,    "location"=>"playerOff",    "set"=>0,    "isBuilding"=>false,    "price"=>4,    "automa"=>"player"  ),
  5=>array(    "key"=>5,    "name"=>clienttranslate("Worker"),    "type"=>"worker_4",    "type_arg"=>5,    "location"=>"playerOff",    "set"=>0,    "isBuilding"=>false,    "price"=>4,    "automa"=>"player"  ),
  6=>array(    "key"=>6,    "name"=>clienttranslate("Worker"),    "type"=>"worker_5",    "type_arg"=>6,    "location"=>"playerOff",    "set"=>0,    "isBuilding"=>false,    "price"=>4,    "automa"=>"player"  ),
  7=>array(    "key"=>7,    "name"=>clienttranslate("Trellis"),    "type"=>"trellis",    "type_arg"=>7,    "location"=>"playerOff",    "set"=>0,    "isBuilding"=>true,    "price"=>2,    "automa"=>"NO"  ),
  8=>array(    "key"=>8,    "name"=>clienttranslate("Windmill"),    "type"=>"windmill",    "type_arg"=>8,    "location"=>"playerOff",    "set"=>0,    "isBuilding"=>true,    "price"=>5,    "automa"=>"NO"  ),
  9=>array(    "key"=>9,    "name"=>clienttranslate("Irrigation"),    "type"=>"irrigation",    "type_arg"=>9,    "location"=>"playerOff",    "set"=>0,    "isBuilding"=>true,    "price"=>3,    "automa"=>"NO"  ),
  10=>array(    "key"=>10,    "name"=>clienttranslate("Yoke"),    "type"=>"yoke",    "type_arg"=>10,    "location"=>"playerOff",    "set"=>0,    "isBuilding"=>true,    "price"=>2,    "automa"=>"NO"  ),
  11=>array(    "key"=>11,    "name"=>clienttranslate("Tasting Room"),    "type"=>"tastingRoom",    "type_arg"=>11,    "location"=>"playerOff",    "set"=>0,    "isBuilding"=>true,    "price"=>6,    "automa"=>"NO"  ),
  12=>array(    "key"=>12,    "name"=>clienttranslate("Medium Cellar"),    "type"=>"mediumCellar",    "type_arg"=>12,    "location"=>"playerOff",    "set"=>0,    "isBuilding"=>true,    "price"=>4,    "automa"=>"NO"  ),
  13=>array(    "key"=>13,    "name"=>clienttranslate("Large Cellar"),    "type"=>"largeCellar",    "type_arg"=>13,    "location"=>"playerOff",    "set"=>0,    "isBuilding"=>true,    "price"=>6,    "automa"=>"NO"  ),
  14=>array(    "key"=>14,    "name"=>clienttranslate("Cottage"),    "type"=>"cottage",    "type_arg"=>14,    "location"=>"playerOff",    "set"=>0,    "isBuilding"=>true,    "price"=>4,    "automa"=>"NO"  ),
  15=>array(    "key"=>15,    "name"=>clienttranslate("Rooster"),    "type"=>"rooster",    "type_arg"=>15,    "location"=>"player",    "set"=>0,    "isBuilding"=>false,    "price"=>0,    "automa"=>"NO"  )
);




////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////
// board locations
$this->boardLocations = array(
  101=>array(    "key"=>101,    "players"=>1,    "season"=>2,    "action"=>"playYellowCard_1",    "bonus"=>"",    "bl"=>"102",    "max"=>1,    "stat"=>"vit_action_play_yellow_card",   "int"=>1,   "sha"=>100,   "set"=>0,  "des"=>clienttranslate('Play a summer visitor card ${token_yellowCard}')),
  102=>array(    "key"=>102,    "players"=>3,    "season"=>2,    "action"=>"playYellowCard_1",    "bonus"=>"playYellowCard_1",    "bl"=>"102",    "max"=>1,    "stat"=>"vit_action_play_yellow_card",   "int"=>1,   "sha"=>100,   "set"=>0,  "des"=>clienttranslate('Play two summer visitor cards ${token_yellowCard}')),
  103=>array(    "key"=>103,    "players"=>5,    "season"=>2,    "action"=>"playYellowCard_1",    "bonus"=>"",    "bl"=>"102",    "max"=>1,    "stat"=>"vit_action_play_yellow_card",   "int"=>1,   "sha"=>100,   "set"=>0,  "des"=>clienttranslate('Play a summer visitor card ${token_yellowCard}')),
  111=>array(    "key"=>111,    "players"=>1,    "season"=>2,    "action"=>"drawGreenCard_1",    "bonus"=>"",    "bl"=>"112",    "max"=>1,    "stat"=>"vit_action_draw_green_card",   "int"=>0,   "sha"=>110,   "set"=>0,  "des"=>clienttranslate('Draw a vine card ${token_greenCardPlus}')),
  112=>array(    "key"=>112,    "players"=>3,    "season"=>2,    "action"=>"drawGreenCard_1",    "bonus"=>"drawGreenCard_1",    "bl"=>"112",    "max"=>1,    "stat"=>"vit_action_draw_green_card",   "int"=>0,   "sha"=>110,   "set"=>0,  "des"=>clienttranslate('Draw two vine cards ${token_greenCardPlus}')),
  113=>array(    "key"=>113,    "players"=>5,    "season"=>2,    "action"=>"drawGreenCard_1",    "bonus"=>"",    "bl"=>"112",    "max"=>1,    "stat"=>"vit_action_draw_green_card",   "int"=>0,   "sha"=>110,   "set"=>0,  "des"=>clienttranslate('Draw a vine card ${token_greenCardPlus}')),
  121=>array(    "key"=>121,    "players"=>1,    "season"=>2,    "action"=>"getLira_2",    "bonus"=>"",    "bl"=>"122",    "max"=>1,    "stat"=>"vit_action_give_tour",   "int"=>0,   "sha"=>120,   "set"=>0,  "des"=>clienttranslate('Give tour to gain ${token_lira2}')),
  122=>array(    "key"=>122,    "players"=>3,    "season"=>2,    "action"=>"getLira_2",    "bonus"=>"getLira_1",    "bl"=>"122",    "max"=>1,    "stat"=>"vit_action_give_tour",   "int"=>0,   "sha"=>120,   "set"=>0,  "des"=>clienttranslate('Give tour to gain ${token_lira3}')),
  123=>array(    "key"=>123,    "players"=>5,    "season"=>2,    "action"=>"getLira_2",    "bonus"=>"",    "bl"=>"122",    "max"=>1,    "stat"=>"vit_action_give_tour",   "int"=>0,   "sha"=>120,   "set"=>0,  "des"=>clienttranslate('Give tour to gain ${token_lira2}')),
  131=>array(    "key"=>131,    "players"=>1,    "season"=>2,    "action"=>"buildStructure_1",    "bonus"=>"",    "bl"=>"132",    "max"=>1,    "stat"=>"vit_action_build_structure",   "int"=>1,   "sha"=>130,   "set"=>0,  "des"=>clienttranslate('Build one structure')),
  132=>array(    "key"=>132,    "players"=>3,    "season"=>2,    "action"=>"buildStructure_1",    "bonus"=>"getDiscountLira1",    "bl"=>"132",    "max"=>1,    "stat"=>"vit_action_build_structure",   "int"=>1,   "sha"=>130,   "set"=>0,  "des"=>clienttranslate('Gain ${token_lira1} and build one structure')),
  133=>array(    "key"=>133,    "players"=>5,    "season"=>2,    "action"=>"buildStructure_1",    "bonus"=>"",    "bl"=>"132",    "max"=>1,    "stat"=>"vit_action_build_structure",   "int"=>1,   "sha"=>130,   "set"=>0,  "des"=>clienttranslate('Build one structure')),
  141=>array(    "key"=>141,    "players"=>1,    "season"=>2,    "action"=>"sellGrapes_1|buySellVine_1",    "bonus"=>"",    "bl"=>"142",    "max"=>1,    "stat"=>"vit_action_sell_grape_or_buy_sell_field",   "int"=>1,   "sha"=>140,   "set"=>0,  "des"=>clienttranslate('Sell at least one grape or buy/sell one field ')),
  142=>array(    "key"=>142,    "players"=>3,    "season"=>2,    "action"=>"sellGrapes_1|buySellVine_1",    "bonus"=>"getVp_1",    "bl"=>"142",    "max"=>1,    "stat"=>"vit_action_sell_grape_or_buy_sell_field",   "int"=>1,   "sha"=>140,   "set"=>0,  "des"=>clienttranslate('Sell at least one grape or buy/sell one field and get a victory point  ${token_vp1}')),
  143=>array(    "key"=>143,    "players"=>5,    "season"=>2,    "action"=>"sellGrapes_1|buySellVine_1",    "bonus"=>"",    "bl"=>"142",    "max"=>1,    "stat"=>"vit_action_sell_grape_or_buy_sell_field",   "int"=>1,   "sha"=>140,   "set"=>0,  "des"=>clienttranslate('Sell at least one grape or buy/sell one field ')),
  151=>array(    "key"=>151,    "players"=>1,    "season"=>2,    "action"=>"plant_1",    "bonus"=>"",    "bl"=>"152",    "max"=>1,    "stat"=>"vit_action_plant",   "int"=>1,   "sha"=>150,   "set"=>0,  "des"=>clienttranslate('Plant a vine card ${token_greenCard}')),
  152=>array(    "key"=>152,    "players"=>3,    "season"=>2,    "action"=>"plant_1",    "bonus"=>"plant_1",    "bl"=>"152",    "max"=>1,    "stat"=>"vit_action_plant",   "int"=>1,   "sha"=>150,   "set"=>0,  "des"=>clienttranslate('Plant two vine cards ${token_greenCard}')),
  153=>array(    "key"=>153,    "players"=>5,    "season"=>2,    "action"=>"plant_1",    "bonus"=>"",    "bl"=>"152",    "max"=>1,    "stat"=>"vit_action_plant",   "int"=>1,   "sha"=>150,   "set"=>0,  "des"=>clienttranslate('Plant a vine card ${token_greenCard}')),
  301=>array(    "key"=>301,    "players"=>1,    "season"=>4,    "action"=>"drawPurpleCard_1",    "bonus"=>"",    "bl"=>"302",    "max"=>1,    "stat"=>"vit_action_draw_purple_card",   "int"=>0,   "sha"=>300,   "set"=>0,  "des"=>clienttranslate('Draw a wine order card ${token_purpleCardPlus}')),
  302=>array(    "key"=>302,    "players"=>3,    "season"=>4,    "action"=>"drawPurpleCard_1",    "bonus"=>"drawPurpleCard_1",    "bl"=>"302",    "max"=>1,    "stat"=>"vit_action_draw_purple_card",   "int"=>0,   "sha"=>300,   "set"=>0,  "des"=>clienttranslate('Draw two wine orders ${token_purpleCardPlus}')),
  303=>array(    "key"=>303,    "players"=>5,    "season"=>4,    "action"=>"drawPurpleCard_1",    "bonus"=>"",    "bl"=>"302",    "max"=>1,    "stat"=>"vit_action_draw_purple_card",   "int"=>0,   "sha"=>300,   "set"=>0,  "des"=>clienttranslate('Draw a wine order card ${token_purpleCardPlus}')),
  311=>array(    "key"=>311,    "players"=>1,    "season"=>4,    "action"=>"harvestField_1",    "bonus"=>"",    "bl"=>"312",    "max"=>1,    "stat"=>"vit_action_harvest",   "int"=>1,   "sha"=>310,   "set"=>0,  "des"=>clienttranslate('Harvest one field')),
  312=>array(    "key"=>312,    "players"=>3,    "season"=>4,    "action"=>"harvestField_1",    "bonus"=>"harvestField_1",    "bl"=>"312",    "max"=>1,    "stat"=>"vit_action_harvest",   "int"=>1,   "sha"=>310,   "set"=>0,  "des"=>clienttranslate('Harvest two fields')),
  313=>array(    "key"=>313,    "players"=>5,    "season"=>4,    "action"=>"harvestField_1",    "bonus"=>"",    "bl"=>"312",    "max"=>1,    "stat"=>"vit_action_harvest",   "int"=>1,   "sha"=>310,   "set"=>0,  "des"=>clienttranslate('Harvest one field')),
  321=>array(    "key"=>321,    "players"=>1,    "season"=>4,    "action"=>"trainWorker_1",    "bonus"=>"",    "bl"=>"322",    "max"=>1,    "stat"=>"vit_action_train_worker",   "int"=>0,   "sha"=>320,   "set"=>0,  "des"=>clienttranslate('Train a worker ${token_worker} for ${token_lira4}')),
  322=>array(    "key"=>322,    "players"=>3,    "season"=>4,    "action"=>"trainWorker_1",    "bonus"=>"getDiscountLira1",    "bl"=>"322",    "max"=>1,    "stat"=>"vit_action_train_worker",   "int"=>0,   "sha"=>320,   "set"=>0,  "des"=>clienttranslate('Train a worker ${token_worker} for ${token_lira3}')),
  323=>array(    "key"=>323,    "players"=>5,    "season"=>4,    "action"=>"trainWorker_1",    "bonus"=>"",    "bl"=>"322",    "max"=>1,    "stat"=>"vit_action_train_worker",   "int"=>0,   "sha"=>320,   "set"=>0,  "des"=>clienttranslate('Train a worker ${token_worker} for ${token_lira4}')),
  331=>array(    "key"=>331,    "players"=>1,    "season"=>4,    "action"=>"fillOrder_1",    "bonus"=>"",    "bl"=>"332",    "max"=>1,    "stat"=>"vit_action_fill_order",   "int"=>1,   "sha"=>330,   "set"=>0,  "des"=>clienttranslate('Fill a wine order card ${token_purpleCard}')),
  332=>array(    "key"=>332,    "players"=>3,    "season"=>4,    "action"=>"fillOrder_1",    "bonus"=>"getVp_1",    "bl"=>"332",    "max"=>1,    "stat"=>"vit_action_fill_order",   "int"=>1,   "sha"=>330,   "set"=>0,  "des"=>clienttranslate('Fill a wine order  ${token_purpleCard} and get a victory point ${token_vp1}')),
  333=>array(    "key"=>333,    "players"=>5,    "season"=>4,    "action"=>"fillOrder_1",    "bonus"=>"",    "bl"=>"332",    "max"=>1,    "stat"=>"vit_action_fill_order",   "int"=>1,   "sha"=>330,   "set"=>0,  "des"=>clienttranslate('Fill a wine order ${token_purpleCard}')),
  341=>array(    "key"=>341,    "players"=>1,    "season"=>4,    "action"=>"makeWine_2",    "bonus"=>"",    "bl"=>"342",    "max"=>1,    "stat"=>"vit_action_make_wine",   "int"=>1,   "sha"=>340,   "set"=>0,  "des"=>clienttranslate('Make up to two wines')),
  342=>array(    "key"=>342,    "players"=>3,    "season"=>4,    "action"=>"makeWine_2",    "bonus"=>"makeWine_1",    "bl"=>"342",    "max"=>1,    "stat"=>"vit_action_make_wine",   "int"=>1,   "sha"=>340,   "set"=>0,  "des"=>clienttranslate('Make up to three wines')),
  343=>array(    "key"=>343,    "players"=>5,    "season"=>4,    "action"=>"makeWine_2",    "bonus"=>"",    "bl"=>"342",    "max"=>1,    "stat"=>"vit_action_make_wine",   "int"=>1,   "sha"=>340,   "set"=>0,  "des"=>clienttranslate('Make up to two wines')),
  351=>array(    "key"=>351,    "players"=>1,    "season"=>4,    "action"=>"playBlueCard_1",    "bonus"=>"",    "bl"=>"352",    "max"=>1,    "stat"=>"vit_action_play_blue_card",   "int"=>1,   "sha"=>350,   "set"=>0,  "des"=>clienttranslate('Play a winter visitor card ${token_blueCard}')),
  352=>array(    "key"=>352,    "players"=>3,    "season"=>4,    "action"=>"playBlueCard_1",    "bonus"=>"playBlueCard_1",    "bl"=>"352",    "max"=>1,    "stat"=>"vit_action_play_blue_card",   "int"=>1,   "sha"=>350,   "set"=>0,  "des"=>clienttranslate('Play two winter visitor cards ${token_blueCard}')),
  353=>array(    "key"=>353,    "players"=>5,    "season"=>4,    "action"=>"playBlueCard_1",    "bonus"=>"",    "bl"=>"352",    "max"=>1,    "stat"=>"vit_action_play_blue_card",   "int"=>1,   "sha"=>350,   "set"=>0,  "des"=>clienttranslate('Play a winter visitor card ${token_blueCard}')),
  801=>array(    "key"=>801,    "players"=>1,    "season"=>9,    "action"=>"getLira_1",    "bonus"=>"",    "bl"=>"",    "max"=>99,    "stat"=>"vit_action_get_lira",   "int"=>0,   "sha"=>801,   "set"=>0,  "des"=>clienttranslate('Gain ${token_lira1}')),
  901=>array(    "key"=>901,    "players"=>1,    "season"=>9,    "action"=>"uproot_1|harvestField_1",    "bonus"=>"",    "bl"=>"",    "max"=>1,    "stat"=>"vit_action_yoke",   "int"=>1,   "sha"=>901,   "set"=>0,  "des"=>clienttranslate('Uproot one vine or harvest one field'))  
);




////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////
// mamas
$this->mamas = array(
  1=>array(    "key"=>1,    "name"=>clienttranslate("Alaena"),    "green"=>1,    "yellow"=>1,    "purple"=>1,    "blue"=>0,    "lira"=>0),  
  2=>array(    "key"=>2,    "name"=>clienttranslate("Alyssa"),    "green"=>1,    "yellow"=>1,    "purple"=>0,    "blue"=>1,    "lira"=>0),  
  3=>array(    "key"=>3,    "name"=>clienttranslate("Deann"),    "green"=>1,    "yellow"=>0,    "purple"=>1,    "blue"=>1,    "lira"=>0),  
  4=>array(    "key"=>4,    "name"=>clienttranslate("Margot"),    "green"=>0,    "yellow"=>1,    "purple"=>1,    "blue"=>1,    "lira"=>0),  
  5=>array(    "key"=>5,    "name"=>clienttranslate("Margaret"),    "green"=>2,    "yellow"=>1,    "purple"=>0,    "blue"=>0,    "lira"=>0),  
  6=>array(    "key"=>6,    "name"=>clienttranslate("Nici"),    "green"=>2,    "yellow"=>0,    "purple"=>1,    "blue"=>0,    "lira"=>0),  
  7=>array(    "key"=>7,    "name"=>clienttranslate("Teruyo"),    "green"=>2,    "yellow"=>0,    "purple"=>0,    "blue"=>1,    "lira"=>0),  
  8=>array(    "key"=>8,    "name"=>clienttranslate("Emily"),    "green"=>1,    "yellow"=>2,    "purple"=>0,    "blue"=>0,    "lira"=>0),  
  9=>array(    "key"=>9,    "name"=>clienttranslate("Rebecca"),    "green"=>0,    "yellow"=>2,    "purple"=>1,    "blue"=>0,    "lira"=>0),  
  10=>array(    "key"=>10,    "name"=>clienttranslate("Danyel"),    "green"=>0,    "yellow"=>2,    "purple"=>0,    "blue"=>1,    "lira"=>0),  
  11=>array(    "key"=>11,    "name"=>clienttranslate("Laura"),    "green"=>1,    "yellow"=>0,    "purple"=>2,    "blue"=>0,    "lira"=>0),  
  12=>array(    "key"=>12,    "name"=>clienttranslate("Jess"),    "green"=>0,    "yellow"=>1,    "purple"=>2,    "blue"=>0,    "lira"=>0),  
  13=>array(    "key"=>13,    "name"=>clienttranslate("Casey"),    "green"=>0,    "yellow"=>0,    "purple"=>2,    "blue"=>1,    "lira"=>0),  
  14=>array(    "key"=>14,    "name"=>clienttranslate("Christine"),    "green"=>1,    "yellow"=>0,    "purple"=>0,    "blue"=>2,    "lira"=>0),  
  15=>array(    "key"=>15,    "name"=>clienttranslate("Naja"),    "green"=>0,    "yellow"=>1,    "purple"=>0,    "blue"=>2,    "lira"=>0),  
  16=>array(    "key"=>16,    "name"=>clienttranslate("Falon"),    "green"=>0,    "yellow"=>0,    "purple"=>1,    "blue"=>2,    "lira"=>0),  
  17=>array(    "key"=>17,    "name"=>clienttranslate("Nicole"),    "green"=>1,    "yellow"=>0,    "purple"=>0,    "blue"=>1,    "lira"=>2),  
  18=>array(    "key"=>18,    "name"=>clienttranslate("Ariel"),    "green"=>0,    "yellow"=>1,    "purple"=>1,    "blue"=>0,    "lira"=>2)  
);




////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////
// papas
$this->papas = array(
  101=>array(    "key"=>101,    "name"=>clienttranslate("Andrew"),    "lira"=>4,    "choice_bonus"=>"trellis",    "choice_lira"=>2),  
  102=>array(    "key"=>102,    "name"=>clienttranslate("Christian"),    "lira"=>3,    "choice_bonus"=>"irrigation",    "choice_lira"=>3),  
  103=>array(    "key"=>103,    "name"=>clienttranslate("Jay"),    "lira"=>5,    "choice_bonus"=>"yoke",    "choice_lira"=>2),  
  104=>array(    "key"=>104,    "name"=>clienttranslate("Josh"),    "lira"=>3,    "choice_bonus"=>"mediumCellar",    "choice_lira"=>4),  
  105=>array(    "key"=>105,    "name"=>clienttranslate("Kozi"),    "lira"=>2,    "choice_bonus"=>"cottage",    "choice_lira"=>4),  
  106=>array(    "key"=>106,    "name"=>clienttranslate("Matthew"),    "lira"=>1,    "choice_bonus"=>"windmill",    "choice_lira"=>5),  
  107=>array(    "key"=>107,    "name"=>clienttranslate("Matt"),    "lira"=>0,    "choice_bonus"=>"tastingRoom",    "choice_lira"=>6),  
  108=>array(    "key"=>108,    "name"=>clienttranslate("Paul"),    "lira"=>5,    "choice_bonus"=>"trellis",    "choice_lira"=>1),  
  109=>array(    "key"=>109,    "name"=>clienttranslate("Stephan"),    "lira"=>4,    "choice_bonus"=>"irrigation",    "choice_lira"=>2),  
  110=>array(    "key"=>110,    "name"=>clienttranslate("Steven"),    "lira"=>6,    "choice_bonus"=>"yoke",    "choice_lira"=>1),  
  111=>array(    "key"=>111,    "name"=>clienttranslate("Joel"),    "lira"=>4,    "choice_bonus"=>"mediumCellar",    "choice_lira"=>3),  
  112=>array(    "key"=>112,    "name"=>clienttranslate("Raymond"),    "lira"=>3,    "choice_bonus"=>"cottage",    "choice_lira"=>3),  
  113=>array(    "key"=>113,    "name"=>clienttranslate("Jerry"),    "lira"=>2,    "choice_bonus"=>"windmill",    "choice_lira"=>4),  
  114=>array(    "key"=>114,    "name"=>clienttranslate("Trevor"),    "lira"=>1,    "choice_bonus"=>"tastingRoom",    "choice_lira"=>5),  
  115=>array(    "key"=>115,    "name"=>clienttranslate("Rafael"),    "lira"=>2,    "choice_bonus"=>"worker",    "choice_lira"=>4),  
  116=>array(    "key"=>116,    "name"=>clienttranslate("Gary"),    "lira"=>3,    "choice_bonus"=>"worker",    "choice_lira"=>3),  
  117=>array(    "key"=>117,    "name"=>clienttranslate("Morten"),    "lira"=>4,    "choice_bonus"=>"vp1",    "choice_lira"=>3),  
  118=>array(    "key"=>118,    "name"=>clienttranslate("Alan"),    "lira"=>5,    "choice_bonus"=>"vp1",    "choice_lira"=>2),    
);



////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////
// green cards
$this->greenCards = array(
  201=>array(    "key"=>201,    "name"=>clienttranslate("Sangiovese"),    "red"=>1,    "white"=>0,    "trellis"=>0,    "irrigation"=>0,    "qty"=>4,    "set"=>0),  
  202=>array(    "key"=>202,    "name"=>clienttranslate("Malvasia"),    "red"=>0,    "white"=>1,    "trellis"=>0,    "irrigation"=>0,    "qty"=>4,    "set"=>0),  
  203=>array(    "key"=>203,    "name"=>clienttranslate("Pinot"),    "red"=>1,    "white"=>1,    "trellis"=>1,    "irrigation"=>0,    "qty"=>6,    "set"=>0),  
  204=>array(    "key"=>204,    "name"=>clienttranslate("Syrah"),    "red"=>2,    "white"=>0,    "trellis"=>1,    "irrigation"=>0,    "qty"=>5,    "set"=>0),  
  205=>array(    "key"=>205,    "name"=>clienttranslate("Trebbiano"),    "red"=>0,    "white"=>2,    "trellis"=>1,    "irrigation"=>0,    "qty"=>5,    "set"=>0),  
  206=>array(    "key"=>206,    "name"=>clienttranslate("Merlot"),    "red"=>3,    "white"=>0,    "trellis"=>0,    "irrigation"=>1,    "qty"=>5,    "set"=>0),  
  207=>array(    "key"=>207,    "name"=>clienttranslate("Sauvignon Blanc"),    "red"=>0,    "white"=>3,    "trellis"=>0,    "irrigation"=>1,    "qty"=>5,    "set"=>0),  
  208=>array(    "key"=>208,    "name"=>clienttranslate("Cabernet Sauvignon"),    "red"=>4,    "white"=>0,    "trellis"=>1,    "irrigation"=>1,    "qty"=>4,    "set"=>0),  
  209=>array(    "key"=>209,    "name"=>clienttranslate("Chardonnay"),    "red"=>0,    "white"=>4,    "trellis"=>1,    "irrigation"=>1,    "qty"=>4,    "set"=>0)
);




////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////
// purple cards
$this->purpleCards = array(
  401=>array(    "key"=>401,    "red1"=>5,     "red2"=>0,    "red3"=>0,    "white1"=>0,    "white2"=>0,    "white3"=>0,    "blush1"=>0,    "blush2"=>0,    "sparkling"=>0,    "vp"=>2,    "resid"=>1,    "set"=>0),  
  402=>array(    "key"=>402,    "red1"=>2,     "red2"=>0,    "red3"=>0,    "white1"=>2,    "white2"=>0,    "white3"=>0,    "blush1"=>0,    "blush2"=>0,    "sparkling"=>0,    "vp"=>2,    "resid"=>1,    "set"=>0),  
  403=>array(    "key"=>403,    "red1"=>3,     "red2"=>0,    "red3"=>0,    "white1"=>1,    "white2"=>0,    "white3"=>0,    "blush1"=>0,    "blush2"=>0,    "sparkling"=>0,    "vp"=>2,    "resid"=>1,    "set"=>0),  
  404=>array(    "key"=>404,    "red1"=>1,     "red2"=>0,    "red3"=>0,    "white1"=>3,    "white2"=>0,    "white3"=>0,    "blush1"=>0,    "blush2"=>0,    "sparkling"=>0,    "vp"=>2,    "resid"=>1,    "set"=>0),  
  405=>array(    "key"=>405,    "red1"=>0,     "red2"=>0,    "red3"=>0,    "white1"=>5,    "white2"=>0,    "white3"=>0,    "blush1"=>0,    "blush2"=>0,    "sparkling"=>0,    "vp"=>2,    "resid"=>1,    "set"=>0),  
  406=>array(    "key"=>406,    "red1"=>0,     "red2"=>0,    "red3"=>0,    "white1"=>0,    "white2"=>0,    "white3"=>0,    "blush1"=>4,    "blush2"=>0,    "sparkling"=>0,    "vp"=>2,    "resid"=>1,    "set"=>0),  
  407=>array(    "key"=>407,    "red1"=>6,     "red2"=>0,    "red3"=>0,    "white1"=>0,    "white2"=>0,    "white3"=>0,    "blush1"=>0,    "blush2"=>0,    "sparkling"=>0,    "vp"=>3,    "resid"=>1,    "set"=>0),  
  408=>array(    "key"=>408,    "red1"=>2,     "red2"=>0,    "red3"=>0,    "white1"=>4,    "white2"=>0,    "white3"=>0,    "blush1"=>0,    "blush2"=>0,    "sparkling"=>0,    "vp"=>3,    "resid"=>1,    "set"=>0),  
  409=>array(    "key"=>409,    "red1"=>4,     "red2"=>0,    "red3"=>0,    "white1"=>2,    "white2"=>0,    "white3"=>0,    "blush1"=>0,    "blush2"=>0,    "sparkling"=>0,    "vp"=>3,    "resid"=>1,    "set"=>0),  
  410=>array(    "key"=>410,    "red1"=>3,     "red2"=>0,    "red3"=>0,    "white1"=>3,    "white2"=>0,    "white3"=>0,    "blush1"=>0,    "blush2"=>0,    "sparkling"=>0,    "vp"=>3,    "resid"=>1,    "set"=>0),  
  411=>array(    "key"=>411,    "red1"=>4,     "red2"=>3,    "red3"=>0,    "white1"=>0,    "white2"=>0,    "white3"=>0,    "blush1"=>0,    "blush2"=>0,    "sparkling"=>0,    "vp"=>3,    "resid"=>1,    "set"=>0),  
  412=>array(    "key"=>412,    "red1"=>0,     "red2"=>0,    "red3"=>0,    "white1"=>6,    "white2"=>0,    "white3"=>0,    "blush1"=>0,    "blush2"=>0,    "sparkling"=>0,    "vp"=>3,    "resid"=>1,    "set"=>0),  
  413=>array(    "key"=>413,    "red1"=>0,     "red2"=>0,    "red3"=>0,    "white1"=>4,    "white2"=>3,    "white3"=>0,    "blush1"=>0,    "blush2"=>0,    "sparkling"=>0,    "vp"=>3,    "resid"=>1,    "set"=>0),  
  414=>array(    "key"=>414,    "red1"=>0,     "red2"=>0,    "red3"=>0,    "white1"=>0,    "white2"=>0,    "white3"=>0,    "blush1"=>6,    "blush2"=>0,    "sparkling"=>0,    "vp"=>3,    "resid"=>1,    "set"=>0),  
  415=>array(    "key"=>415,    "red1"=>8,     "red2"=>0,    "red3"=>0,    "white1"=>0,    "white2"=>0,    "white3"=>0,    "blush1"=>0,    "blush2"=>0,    "sparkling"=>0,    "vp"=>4,    "resid"=>1,    "set"=>0),  
  416=>array(    "key"=>416,    "red1"=>5,     "red2"=>0,    "red3"=>0,    "white1"=>3,    "white2"=>0,    "white3"=>0,    "blush1"=>0,    "blush2"=>0,    "sparkling"=>0,    "vp"=>4,    "resid"=>1,    "set"=>0),  
  417=>array(    "key"=>417,    "red1"=>3,     "red2"=>0,    "red3"=>0,    "white1"=>5,    "white2"=>0,    "white3"=>0,    "blush1"=>0,    "blush2"=>0,    "sparkling"=>0,    "vp"=>4,    "resid"=>1,    "set"=>0),  
  418=>array(    "key"=>418,    "red1"=>4,     "red2"=>0,    "red3"=>0,    "white1"=>0,    "white2"=>0,    "white3"=>0,    "blush1"=>4,    "blush2"=>0,    "sparkling"=>0,    "vp"=>4,    "resid"=>1,    "set"=>0),  
  419=>array(    "key"=>419,    "red1"=>4,     "red2"=>3,    "red3"=>2,    "white1"=>0,    "white2"=>0,    "white3"=>0,    "blush1"=>0,    "blush2"=>0,    "sparkling"=>0,    "vp"=>4,    "resid"=>1,    "set"=>0),  
  420=>array(    "key"=>420,    "red1"=>0,     "red2"=>0,    "red3"=>0,    "white1"=>8,    "white2"=>0,    "white3"=>0,    "blush1"=>0,    "blush2"=>0,    "sparkling"=>0,    "vp"=>4,    "resid"=>1,    "set"=>0),  
  421=>array(    "key"=>421,    "red1"=>0,     "red2"=>0,    "red3"=>0,    "white1"=>4,    "white2"=>0,    "white3"=>0,    "blush1"=>4,    "blush2"=>0,    "sparkling"=>0,    "vp"=>4,    "resid"=>1,    "set"=>0),  
  422=>array(    "key"=>422,    "red1"=>0,     "red2"=>0,    "red3"=>0,    "white1"=>4,    "white2"=>3,    "white3"=>2,    "blush1"=>0,    "blush2"=>0,    "sparkling"=>0,    "vp"=>4,    "resid"=>1,    "set"=>0),  
  423=>array(    "key"=>423,    "red1"=>0,     "red2"=>0,    "red3"=>0,    "white1"=>0,    "white2"=>0,    "white3"=>0,    "blush1"=>8,    "blush2"=>0,    "sparkling"=>0,    "vp"=>4,    "resid"=>1,    "set"=>0),  
  424=>array(    "key"=>424,    "red1"=>0,     "red2"=>0,    "red3"=>0,    "white1"=>0,    "white2"=>0,    "white3"=>0,    "blush1"=>0,    "blush2"=>0,    "sparkling"=>7,    "vp"=>4,    "resid"=>1,    "set"=>0),  
  425=>array(    "key"=>425,    "red1"=>2,     "red2"=>0,    "red3"=>0,    "white1"=>2,    "white2"=>0,    "white3"=>0,    "blush1"=>5,    "blush2"=>0,    "sparkling"=>0,    "vp"=>5,    "resid"=>2,    "set"=>0),  
  426=>array(    "key"=>426,    "red1"=>7,     "red2"=>6,    "red3"=>0,    "white1"=>0,    "white2"=>0,    "white3"=>0,    "blush1"=>0,    "blush2"=>0,    "sparkling"=>0,    "vp"=>5,    "resid"=>2,    "set"=>0),  
  427=>array(    "key"=>427,    "red1"=>6,     "red2"=>0,    "red3"=>0,    "white1"=>6,    "white2"=>0,    "white3"=>0,    "blush1"=>0,    "blush2"=>0,    "sparkling"=>0,    "vp"=>5,    "resid"=>2,    "set"=>0),  
  428=>array(    "key"=>428,    "red1"=>3,     "red2"=>0,    "red3"=>0,    "white1"=>0,    "white2"=>0,    "white3"=>0,    "blush1"=>7,    "blush2"=>0,    "sparkling"=>0,    "vp"=>5,    "resid"=>2,    "set"=>0),  
  429=>array(    "key"=>429,    "red1"=>0,     "red2"=>0,    "red3"=>0,    "white1"=>3,    "white2"=>0,    "white3"=>0,    "blush1"=>7,    "blush2"=>0,    "sparkling"=>0,    "vp"=>5,    "resid"=>2,    "set"=>0),  
  430=>array(    "key"=>430,    "red1"=>0,     "red2"=>0,    "red3"=>0,    "white1"=>7,    "white2"=>6,    "white3"=>0,    "blush1"=>0,    "blush2"=>0,    "sparkling"=>0,    "vp"=>5,    "resid"=>2,    "set"=>0),  
  431=>array(    "key"=>431,    "red1"=>0,     "red2"=>0,    "red3"=>0,    "white1"=>0,    "white2"=>0,    "white3"=>0,    "blush1"=>0,    "blush2"=>0,    "sparkling"=>9,    "vp"=>5,    "resid"=>2,    "set"=>0),  
  432=>array(    "key"=>432,    "red1"=>0,     "red2"=>0,    "red3"=>0,    "white1"=>0,    "white2"=>0,    "white3"=>0,    "blush1"=>6,    "blush2"=>5,    "sparkling"=>0,    "vp"=>6,    "resid"=>2,    "set"=>0),  
  433=>array(    "key"=>433,    "red1"=>0,     "red2"=>0,    "red3"=>0,    "white1"=>4,    "white2"=>0,    "white3"=>0,    "blush1"=>0,    "blush2"=>0,    "sparkling"=>7,    "vp"=>6,    "resid"=>2,    "set"=>0),  
  434=>array(    "key"=>434,    "red1"=>0,     "red2"=>0,    "red3"=>0,    "white1"=>3,    "white2"=>0,    "white3"=>0,    "blush1"=>0,    "blush2"=>0,    "sparkling"=>7,    "vp"=>6,    "resid"=>2,    "set"=>0),  
  435=>array(    "key"=>435,    "red1"=>0,     "red2"=>0,    "red3"=>0,    "white1"=>3,    "white2"=>0,    "white3"=>0,    "blush1"=>0,    "blush2"=>0,    "sparkling"=>8,    "vp"=>6,    "resid"=>2,    "set"=>0),  
  436=>array(    "key"=>436,    "red1"=>0,     "red2"=>0,    "red3"=>0,    "white1"=>2,    "white2"=>0,    "white3"=>0,    "blush1"=>0,    "blush2"=>0,    "sparkling"=>8,    "vp"=>6,    "resid"=>2,    "set"=>0)
);




////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////
// yellow cards
$this->yellowCards = array(
  601=>array(    "key"=>601,    "name"=>clienttranslate("Surveyor"),    "description"=>clienttranslate('Gain ${token_lira2} for each empty field you own OR gain ${token_vp1} for each planted field you own.'),   "solo"=>1,    "set"=>0),
  602=>array(    "key"=>602,    "name"=>clienttranslate("Broker"),    "description"=>clienttranslate('Pay ${token_lira9} to gain ${token_vp3} OR lose ${token_vp2} to gain ${token_lira6} '),   "solo"=>1,    "set"=>0),
  603=>array(    "key"=>603,    "name"=>clienttranslate("Wine Critic"),    "description"=>clienttranslate('Draw 2 ${token_blueCardPlus} OR discard 1 ${token_wineAny} of value 7 or more to gain ${token_vp4}'),   "solo"=>1,    "set"=>0),
  604=>array(    "key"=>604,    "name"=>clienttranslate("Blacksmith"),    "description"=>clienttranslate('Build a structure at a ${token_lira2} discount. If it is a ${token_lira5} or ${token_lira6} structure, also gain ${token_vp1}.'),   "solo"=>1,    "set"=>0),
  605=>array(    "key"=>605,    "name"=>clienttranslate("Contractor"),    "description"=>clienttranslate('Choose 2: Gain ${token_vp1}, build 1 structure, or plant 1 ${token_greenCard}.'),   "solo"=>1,    "set"=>0),
  606=>array(    "key"=>606,    "name"=>clienttranslate("Tour Guide"),    "description"=>clienttranslate('Gain ${token_lira4} OR harvest 1 field.'),   "solo"=>1,    "set"=>0),
  607=>array(    "key"=>607,    "name"=>clienttranslate("Novice Guide"),    "description"=>clienttranslate('Gain ${token_lira3} OR make up to 2 ${token_wineAny}'),   "solo"=>1,    "set"=>0),
  608=>array(    "key"=>608,    "name"=>clienttranslate("Uncertified Broker "),    "description"=>clienttranslate('Lose ${token_vp3}  to gain ${token_lira9} OR pay ${token_lira6} to gain ${token_vp2}.'),   "solo"=>1,    "set"=>0),
  609=>array(    "key"=>609,    "name"=>clienttranslate("Planter"),    "description"=>clienttranslate('Plant up to 2 ${token_greenCard} and gain ${token_lira1} OR uproot and discard 1 ${token_greenCard} to gain ${token_vp2}.'),   "solo"=>1,    "set"=>0),
  610=>array(    "key"=>610,    "name"=>clienttranslate("Buyer"),    "description"=>clienttranslate('Pay ${token_lira2} to place a ${token_grapeAny1} on your crush pad OR discard 1 ${token_grapeAny} to gain ${token_lira2} and ${token_vp1}.'),   "solo"=>1,    "set"=>0),
  611=>array(    "key"=>611,    "name"=>clienttranslate("Landscaper"),    "description"=>clienttranslate('Draw 1 ${token_greenCardPlus} and plant up to 1 ${token_greenCard} OR switch 2 ${token_greenCard} on your fields.'),   "solo"=>1,    "set"=>0),
  612=>array(    "key"=>612,    "name"=>clienttranslate("Architect"),    "description"=>clienttranslate('Build a structure at a ${token_lira3} discount OR gain ${token_vp1} for each ${token_lira4} structure you have built.'),   "solo"=>1,    "set"=>0),
  613=>array(    "key"=>613,    "name"=>clienttranslate("Uncertified Architect"),    "description"=>clienttranslate('Lose ${token_vp1} to build a ${token_lira2} or ${token_lira3} structure OR lose ${token_vp2} to build any structure.'),   "solo"=>1,    "set"=>0),
  614=>array(    "key"=>614,    "name"=>clienttranslate("Patron"),    "description"=>clienttranslate('Gain ${token_lira4} OR draw 1 ${token_purpleCardPlus} card and 1 ${token_blueCard}.'),   "solo"=>1,    "set"=>0),
  615=>array(    "key"=>615,    "name"=>clienttranslate("Auctioneer"),    "description"=>clienttranslate('Discard 2 ${token_anyCard} to gain ${token_lira4} OR discard 4 ${token_anyCard} to gain ${token_vp3}.'),   "solo"=>1,    "set"=>0),
  616=>array(    "key"=>616,    "name"=>clienttranslate("Entertainer"),    "description"=>clienttranslate('Pay ${token_lira4} to draw 3 ${token_blueCardPlus} OR discard 1 ${token_wineAny} and 3 visitor cards to gain ${token_vp3}.'),   "solo"=>1,    "set"=>0),
  617=>array(    "key"=>617,    "name"=>clienttranslate("Vendor"),    "description"=>clienttranslate('Draw 1 ${token_greenCardPlus} , 1 ${token_purpleCardPlus}, and 1 ${token_blueCardPlus}. Each opponent may draw 1 ${token_yellowCardPlus}.'),   "solo"=>1,    "set"=>0),
  618=>array(    "key"=>618,    "name"=>clienttranslate("Handyman"),    "description"=>clienttranslate('All players may build 1 structure at a ${token_lira2} discount. You gain ${token_vp1} for each opponent who does this.'),   "solo"=>1,    "set"=>0),
  619=>array(    "key"=>619,    "name"=>clienttranslate("Horticulturist"),    "description"=>clienttranslate('Plant 1 ${token_greenCard} even if you don\'t have the required structure(s) OR uproot and discard 2 ${token_greenCard} to gain ${token_vp3}.'),   "solo"=>1,    "set"=>0),
  620=>array(    "key"=>620,    "name"=>clienttranslate("Peddler"),    "description"=>clienttranslate('Discard 2 ${token_anyCard} to draw 1 of each type of card.'),   "solo"=>1,    "set"=>0),
  621=>array(    "key"=>621,    "name"=>clienttranslate("Banker"),    "description"=>clienttranslate('Gain ${token_lira5}. Each opponent may lose ${token_vp1} to gain ${token_lira3}.'),   "solo"=>1,    "set"=>0),
  622=>array(    "key"=>622,    "name"=>clienttranslate("Overseer"),    "description"=>clienttranslate('Build 1 structure at its regular cost and plant 1 ${token_greenCard}. If it is a 4-value ${token_greenCard}, gain ${token_vp1}.'),   "solo"=>1,    "set"=>0),
  623=>array(    "key"=>623,    "name"=>clienttranslate("Importer"),    "description"=>clienttranslate('Draw 3 ${token_blueCardPlus} cards unless all opponents combine to give you 3 visitor cards (total).'),   "solo"=>1,    "set"=>0),
  624=>array(    "key"=>624,    "name"=>clienttranslate("Sharecropper"),    "description"=>clienttranslate('Plant 1 ${token_greenCard} even if you don\'t have the required structure(s) OR uproot and discard 1 ${token_greenCard} to gain ${token_vp2}.'),   "solo"=>1,    "set"=>0),
  625=>array(    "key"=>625,    "name"=>clienttranslate("Grower"),    "description"=>clienttranslate('Plant 1 ${token_greenCard}. Then, if you have planted a total of at least 6 ${token_greenCard}, gain ${token_vp2}.'),   "solo"=>1,    "set"=>0),
  626=>array(    "key"=>626,    "name"=>clienttranslate("Negotiator"),    "description"=>clienttranslate('Discard 1 ${token_grapeAny} to gain ${token_residualPayment1} OR discard 1 ${token_wineAny} to gain ${token_residualPayment2} .'),   "solo"=>1,    "set"=>0),
  627=>array(    "key"=>627,    "name"=>clienttranslate("Cultivator"),    "description"=>clienttranslate('Plant 1 ${token_greenCard}. You may plant it on a field even if the total value of that field exceeds the max vine value.'),   "solo"=>1,    "set"=>0),
  628=>array(    "key"=>628,    "name"=>clienttranslate("Homesteader"),    "description"=>clienttranslate('Build 1 structure at a ${token_lira3} discount OR plant up to 2 ${token_greenCard}. You may lose ${token_vp1} to do both.'),   "solo"=>1,    "set"=>0),
  629=>array(    "key"=>629,    "name"=>clienttranslate("Planner"),    "description"=>clienttranslate('Place a worker on an action in a future season. Take that action at the beginning of that season.'),   "solo"=>1,    "set"=>0),
  630=>array(    "key"=>630,    "name"=>clienttranslate("Agriculturist"),    "description"=>clienttranslate('Plant 1 ${token_greenCard}. Then, if you have at least 3 different types of ${token_greenCard} planted on that field, gain ${token_vp2}.'),   "solo"=>1,    "set"=>0),
  631=>array(    "key"=>631,    "name"=>clienttranslate("Swindler"),    "description"=>clienttranslate('Each opponent may give you ${token_lira2}. For each opponent who does not, gain ${token_vp1}.'),   "solo"=>0,    "set"=>0),
  632=>array(    "key"=>632,    "name"=>clienttranslate("Producer"),    "description"=>clienttranslate('Pay ${token_lira2} to retrieve up to 2 ${token_worker} from other actions. They may be used again this year.'),   "solo"=>1,    "set"=>0),
  633=>array(    "key"=>633,    "name"=>clienttranslate("Organizer"),    "description"=>clienttranslate('Move your ${token_rooster} piece to an empty row on the wake-up chart, take the bonus, then pass to the next season.'),   "solo"=>1,    "set"=>0),
  634=>array(    "key"=>634,    "name"=>clienttranslate("Sponsor"),    "description"=>clienttranslate('Draw 2 ${token_greenCardPlus} OR gain ${token_lira3}. You may lose ${token_vp1} to do both.'),   "solo"=>1,    "set"=>0),
  635=>array(    "key"=>635,    "name"=>clienttranslate("Artisan"),    "description"=>clienttranslate('Choose 1: Gain ${token_lira3}, build a structure at a ${token_lira1} discount, or plant up to 2 ${token_greenCard}.'),   "solo"=>1,    "set"=>0),
  636=>array(    "key"=>636,    "name"=>clienttranslate("Stonemason"),    "description"=>clienttranslate('Pay ${token_lira8} to build any 2 structures (ignore their regular costs)'),   "solo"=>1,    "set"=>0),
  637=>array(    "key"=>637,    "name"=>clienttranslate("Volunteer Crew"),    "description"=>clienttranslate('All players may plant 1 ${token_greenCard}. Gain ${token_lira2} for each opponent who does this.'),   "solo"=>1,    "set"=>0),
  638=>array(    "key"=>638,    "name"=>clienttranslate("Wedding Party"),    "description"=>clienttranslate('Pay up to 3 opponents ${token_lira2} each. Gain ${token_vp1} for each of those opponents.'),   "solo"=>0,    "set"=>0) 
);




////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////
// blue cards
$this->blueCards = array(
  801=>array(    "key"=>801,    "name"=>clienttranslate("Merchant"),    "description"=>clienttranslate('Pay ${token_lira3} to place a ${token_grapeRed1} and a ${token_grapeWhite1}  on your crush pad OR fill 1 ${token_purpleCard} and gain ${token_vp1} extra.'),   "solo"=>1,    "set"=>0),
  802=>array(    "key"=>802,    "name"=>clienttranslate("Crusher"),    "description"=>clienttranslate('Gain ${token_lira3} and draw 1 ${token_yellowCard} OR draw 1 ${token_purpleCard} and make up to 2 ${token_wineAny}.'),   "solo"=>1,    "set"=>0),
  803=>array(    "key"=>803,    "name"=>clienttranslate("Judge"),    "description"=>clienttranslate('Draw 2 ${token_yellowCardPlus} OR discard 1 ${token_wineAny} of value 4 or more to gain ${token_vp3}.'),   "solo"=>1,    "set"=>0),
  804=>array(    "key"=>804,    "name"=>clienttranslate("Oenologist"),    "description"=>clienttranslate('Age all ${token_wineAny} in your cellar twice OR pay ${token_lira3} to upgrade your cellar to the next level.'),   "solo"=>1,    "set"=>0),
  805=>array(    "key"=>805,    "name"=>clienttranslate("Marketer"),    "description"=>clienttranslate('Draw 2 ${token_yellowCardPlus} and gain ${token_lira1} OR fill 1 ${token_purpleCard} and gain ${token_vp1} extra.'),   "solo"=>1,    "set"=>0),
  806=>array(    "key"=>806,    "name"=>clienttranslate("Crush Expert"),    "description"=>clienttranslate('Gain ${token_lira3} and draw 1 ${token_purpleCard} OR make up to 3 ${token_wineAny}.'),   "solo"=>1,    "set"=>0),
  807=>array(    "key"=>807,    "name"=>clienttranslate("Uncertified Teacher"),    "description"=>clienttranslate('Lose ${token_vp1} to train a ${token_worker} OR gain ${token_vp1} for each opponent who has a total of 6 ${token_worker}.'),   "solo"=>1,    "set"=>0),
  808=>array(    "key"=>808,    "name"=>clienttranslate("Teacher"),    "description"=>clienttranslate('Make up to 2 ${token_wineAny} OR pay ${token_lira2} to train 1 worker.'),   "solo"=>1,    "set"=>0),
  809=>array(    "key"=>809,    "name"=>clienttranslate("Benefactor"),    "description"=>clienttranslate('Draw 1 ${token_greenCard} and 1 ${token_yellowCard} card OR discard 2 visitor cards to gain ${token_vp2}.'),   "solo"=>1,    "set"=>0),
  810=>array(    "key"=>810,    "name"=>clienttranslate("Assessor"),    "description"=>clienttranslate('Gain ${token_lira1} for each card in your hand OR discard your hand (min of 1 card) to gain ${token_vp2}.'),   "solo"=>1,    "set"=>0),
  811=>array(    "key"=>811,    "name"=>clienttranslate("Queen"),    "description"=>clienttranslate('The player on your right  ${playerNameRight} must choose 1: lose ${token_vp1}, give you 2 ${token_anyCard}, or pay you ${token_lira3}.'),   "solo"=>0,    "set"=>0),
  812=>array(    "key"=>812,    "name"=>clienttranslate("Harvester"),    "description"=>clienttranslate('Harvest up to 2 fields and choose 1: Gain ${token_lira2} or gain ${token_vp1}.'),   "solo"=>1,    "set"=>0),
  813=>array(    "key"=>813,    "name"=>clienttranslate("Professor"),    "description"=>clienttranslate('Pay ${token_lira2} to train 1 ${token_worker} OR gain ${token_vp2} if you have a total of 6 ${token_worker}.'),   "solo"=>1,    "set"=>0),
  814=>array(    "key"=>814,    "name"=>clienttranslate("Master Vintner"),    "description"=>clienttranslate('Upgrade your cellar to the next level at a ${token_lira2} discount OR age 1 ${token_wineAny} and fill 1 ${token_purpleCard}.'),   "solo"=>1,    "set"=>0),
  815=>array(    "key"=>815,    "name"=>clienttranslate("Uncertified Oenologist"),    "description"=>clienttranslate('Age all ${token_wineAny} in your cellar twice OR lose ${token_vp1} to upgrade your cellar to the next level.'),   "solo"=>1,    "set"=>0),
  816=>array(    "key"=>816,    "name"=>clienttranslate("Promoter"),    "description"=>clienttranslate('Discard a ${token_grapeAny} or ${token_wineAny} to gain ${token_vp1} and ${token_residualPayment1}.'),   "solo"=>1,    "set"=>0),
  817=>array(    "key"=>817,    "name"=>clienttranslate("Mentor"),    "description"=>clienttranslate('All players may make up to 2 ${token_wineAny}. Draw 1 ${token_greenCardPlus} or ${token_yellowCardPlus} card for each opponent who does this.'),   "solo"=>1,    "set"=>0),
  818=>array(    "key"=>818,    "name"=>clienttranslate("Harvest Expert"),    "description"=>clienttranslate('Harvest 1 field and either draw 1 ${token_greenCardPlus} or pay ${token_lira1} to build a Yoke.'),   "solo"=>1,    "set"=>0),
  819=>array(    "key"=>819,    "name"=>clienttranslate("Innkeeper"),    "description"=>clienttranslate('As you play this card, put the top card of 2 different discard piles in your hand.'),   "solo"=>1,    "set"=>0),
  820=>array(    "key"=>820,    "name"=>clienttranslate("Jack-of-all-trades"),    "description"=>clienttranslate('Choose 2: Harvest 1 field, make up to 2 ${token_wineAny}, or fill 1 ${token_purpleCard}.'),   "solo"=>1,    "set"=>0),
  821=>array(    "key"=>821,    "name"=>clienttranslate("Politician"),    "description"=>clienttranslate('If you have less than 0${token_vp}, gain ${token_lira6}. Otherwise, draw 1 ${token_greenCardPlus}, 1 ${token_yellowCardPlus}, and 1 ${token_purpleCardPlus}.'),   "solo"=>1,    "set"=>0),
  822=>array(    "key"=>822,    "name"=>clienttranslate("Supervisor"),    "description"=>clienttranslate('Make up to 2 ${token_wineAny}. Gain${token_vp1} for each sparkling wine token you make.'),   "solo"=>1,    "set"=>0),
  823=>array(    "key"=>823,    "name"=>clienttranslate("Scholar"),    "description"=>clienttranslate('Draw 2 ${token_purpleCard} OR pay ${token_lira3} to train 1 ${token_worker}. You may lose ${token_vp1} to do both.'),   "solo"=>1,    "set"=>0),
  824=>array(    "key"=>824,    "name"=>clienttranslate("Reaper"),    "description"=>clienttranslate('Harvest up to 3 fields. If you harvest 3 fields, gain ${token_vp2}.'),   "solo"=>1,    "set"=>0),
  825=>array(    "key"=>825,    "name"=>clienttranslate("Motivator"),    "description"=>clienttranslate('Each player may retrieve their grande worker. Gain ${token_vp1} for each opponent who does this.'),   "solo"=>1,    "set"=>0),
  826=>array(    "key"=>826,    "name"=>clienttranslate("Bottler"),    "description"=>clienttranslate('Make up to 3 ${token_wineAny}. Gain ${token_vp1} for each type of wine you make.'),   "solo"=>1,    "set"=>0),
  827=>array(    "key"=>827,    "name"=>clienttranslate("Craftsman"),    "description"=>clienttranslate('Choose 2: Draw 1 ${token_purpleCard}, upgrade your cellar at the regular cost, or gain ${token_vp1}.'),   "solo"=>1,    "set"=>0),
  828=>array(    "key"=>828,    "name"=>clienttranslate("Exporter"),    "description"=>clienttranslate('Choose 1: Make up to 2 ${token_wineAny}, fill 1 ${token_purpleCard}, or discard 1 ${token_grapeAny} to gain ${token_vp2}.'),   "solo"=>1,    "set"=>0),
  829=>array(    "key"=>829,    "name"=>clienttranslate("Laborer"),    "description"=>clienttranslate('Harvest up to 2 fields OR make up to 3 ${token_wineAny}. You may lose ${token_vp1} to do both.'),   "solo"=>1,    "set"=>0),
  830=>array(    "key"=>830,    "name"=>clienttranslate("Designer"),    "description"=>clienttranslate('Build 1 structure at its regular cost. Then, if you have at least 6 structures, gain ${token_vp2}.'),   "solo"=>1,    "set"=>0),
  831=>array(    "key"=>831,    "name"=>clienttranslate("Governess"),    "description"=>clienttranslate('Pay ${token_lira3} to train 1 ${token_worker} that you may use this year OR discard 1 ${token_wineAny} to gain ${token_vp2}.'),   "solo"=>1,    "set"=>0),
  832=>array(    "key"=>832,    "name"=>clienttranslate("Manager"),    "description"=>clienttranslate('Take any action (no bonus) from a previous season without placing a worker.'),   "solo"=>1,    "set"=>0),
  833=>array(    "key"=>833,    "name"=>clienttranslate("Zymologist"),    "description"=>clienttranslate('Make up to 2 ${token_wineAny} of value 4 or greater, even if you haven\'t upgraded your cellar.'),   "solo"=>1,    "set"=>0),
  834=>array(    "key"=>834,    "name"=>clienttranslate("Noble"),    "description"=>clienttranslate('Pay ${token_lira1} to gain ${token_residualPayment1} OR lose ${token_residualPayment2} to gain ${token_vp2}.'),   "solo"=>1,    "set"=>0),
  835=>array(    "key"=>835,    "name"=>clienttranslate("Governor"),    "description"=>clienttranslate('Choose up to 3 opponents to each give you 1 ${token_yellowCard}. Gain ${token_vp1} for each of them who cannot.'),   "solo"=>0,    "set"=>0),
  836=>array(    "key"=>836,    "name"=>clienttranslate("Taster"),    "description"=>clienttranslate('Discard 1 ${token_wineAny} to gain ${token_lira4}. If it is the most valuable wine token in any player\'s cellar (no ties), gain ${token_vp2}.'),   "solo"=>1,    "set"=>0),
  837=>array(    "key"=>837,    "name"=>clienttranslate("Caravan"),    "description"=>clienttranslate('Turn the top card of each deck face up. Draw 2 of those cards and discard the others.'),   "solo"=>1,    "set"=>0),
  838=>array(    "key"=>838,    "name"=>clienttranslate("Guest Speaker"),    "description"=>clienttranslate('All players may pay ${token_lira1} to train ${token_worker}. Gain ${token_vp1} for each opponent who does this.'),   "solo"=>1,    "set"=>0)  
);


////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////
// automa cards
$this->automaCards = array(
  301=>array(    "key"=>301,    "name"=>"Automa 1",    "sea1"=>1,    "set1"=>0,    "act1"=>"drawGreenCard_1",    "des1"=>clienttranslate('Draw a vine card ${token_greenCardPlus}'),   "sea2"=>1,    "set2"=>0,    "act2"=>"getLira_2",    "des2"=>clienttranslate('Give tour to gain ${token_lira2}'),   "sea3"=>2,    "set3"=>1,    "act3"=>"buySellVine_1",    "des3"=>clienttranslate('Buy or sell a vine'),   "sea4"=>3,    "set4"=>1,    "act4"=>"buildStructure_1|getLira_2",    "des4"=>clienttranslate('Build one structure or give a tour to get ${token_lira2}') ),
  302=>array(    "key"=>302,    "name"=>"Automa 2",    "sea1"=>1,    "set1"=>0,    "act1"=>"drawGreenCard_1",    "des1"=>clienttranslate('Draw a vine card ${token_greenCardPlus}'),   "sea2"=>1,    "set2"=>0,    "act2"=>"buildStructure_1",    "des2"=>clienttranslate('Build one structure'),   "sea3"=>2,    "set3"=>0,    "act3"=>"plant_1",    "des3"=>clienttranslate('Plant a vine card ${token_greenCard}'),   "sea4"=>3,    "set4"=>0,    "act4"=>"makeWine_2",    "des4"=>clienttranslate('Make up to two wines') ),
  303=>array(    "key"=>303,    "name"=>"Automa 3",    "sea1"=>1,    "set1"=>0,    "act1"=>"drawGreenCard_1",    "des1"=>clienttranslate('Draw a vine card ${token_greenCardPlus}'),   "sea2"=>1,    "set2"=>1,    "act2"=>"placeStar_1|moveSta_1",    "des2"=>clienttranslate('Place or move ${token_star}'),   "sea3"=>2,    "set3"=>0,    "act3"=>"playYellowCard_1",    "des3"=>clienttranslate('Play a summer visitor card ${token_yellowCard}'),   "sea4"=>4,    "set4"=>0,    "act4"=>"playBlueCard_1",    "des4"=>clienttranslate('Play a winter visitor card ${token_blueCard}') ),
  304=>array(    "key"=>304,    "name"=>"Automa 4",    "sea1"=>1,    "set1"=>0,    "act1"=>"getLira_2",    "des1"=>clienttranslate('Give tour to gain ${token_lira2}'),   "sea2"=>1,    "set2"=>0,    "act2"=>"buildStructure_1",    "des2"=>clienttranslate('Build one structure'),   "sea3"=>2,    "set3"=>0,    "act3"=>"sellGrapes_1|buySellVine_1,tradeOneForOne_1",    "des3"=>clienttranslate('Sell at least one grape or buy/sell one field. Trade one for one'),   "sea4"=>4,    "set4"=>0,    "act4"=>"trainWorker_1",    "des4"=>clienttranslate('Train a worker ${token_worker} for ${token_lira4}') ),
  305=>array(    "key"=>305,    "name"=>"Automa 5",    "sea1"=>1,    "set1"=>0,    "act1"=>"getLira_2",    "des1"=>clienttranslate('Give tour to gain ${token_lira2}'),   "sea2"=>1,    "set2"=>1,    "act2"=>"placeStar_1|moveSta_1",    "des2"=>clienttranslate('Place or move ${token_star}'),   "sea3"=>3,    "set3"=>0,    "act3"=>"harvestField_1",    "des3"=>clienttranslate('Harvest one field'),   "sea4"=>4,    "set4"=>1,    "act4"=>"sellWine_1",    "des4"=>clienttranslate('Sell one wine token') ),
  306=>array(    "key"=>306,    "name"=>"Automa 6",    "sea1"=>1,    "set1"=>0,    "act1"=>"buildStructure_1",    "des1"=>clienttranslate('Build one structure'),   "sea2"=>1,    "set2"=>1,    "act2"=>"placeStar_1|moveSta_1",    "des2"=>clienttranslate('Place or move ${token_star}'),   "sea3"=>3,    "set3"=>0,    "act3"=>"drawPurpleCard_1",    "des3"=>clienttranslate('Draw a wine order card ${token_purpleCardPlus}'),   "sea4"=>4,    "set4"=>0,    "act4"=>"fillOrder_1",    "des4"=>clienttranslate('Fill a wine order ${token_purpleCard}') ),
  307=>array(    "key"=>307,    "name"=>"Automa 7",    "sea1"=>1,    "set1"=>0,    "act1"=>"drawGreenCard_1",    "des1"=>clienttranslate('Draw a vine card ${token_greenCardPlus}'),   "sea2"=>2,    "set2"=>0,    "act2"=>"playYellowCard_1",    "des2"=>clienttranslate('Play a summer visitor card ${token_yellowCard}'),   "sea3"=>2,    "set3"=>1,    "act3"=>"buySellVine_1",    "des3"=>clienttranslate('Buy or sell a vine'),   "sea4"=>3,    "set4"=>1,    "act4"=>"buildStructure_1|getLira_2",    "des4"=>clienttranslate('Build one structure or give a tour to get ${token_lira2}') ),
  308=>array(    "key"=>308,    "name"=>"Automa 8",    "sea1"=>1,    "set1"=>0,    "act1"=>"getLira_2",    "des1"=>clienttranslate('Give tour to gain ${token_lira2}'),   "sea2"=>2,    "set2"=>0,    "act2"=>"plant_1",    "des2"=>clienttranslate('Plant a vine card ${token_greenCard}'),   "sea3"=>2,    "set3"=>1,    "act3"=>"buySellVine_1",    "des3"=>clienttranslate('Buy or sell a vine'),   "sea4"=>3,    "set4"=>0,    "act4"=>"makeWine_2",    "des4"=>clienttranslate('Make up to two wines') ),
  309=>array(    "key"=>309,    "name"=>"Automa 9",    "sea1"=>1,    "set1"=>0,    "act1"=>"buildStructure_1",    "des1"=>clienttranslate('Build one structure'),   "sea2"=>2,    "set2"=>0,    "act2"=>"sellGrapes_1|buySellVine_1,tradeOneForOne_1",    "des2"=>clienttranslate('Sell at least one grape or buy/sell one field. Trade one for one'),   "sea3"=>2,    "set3"=>0,    "act3"=>"playYellowCard_1",    "des3"=>clienttranslate('Play a summer visitor card ${token_yellowCard}'),   "sea4"=>4,    "set4"=>0,    "act4"=>"playBlueCard_1",    "des4"=>clienttranslate('Play a winter visitor card ${token_blueCard}') ),
  310=>array(    "key"=>310,    "name"=>"Automa 10",    "sea1"=>1,    "set1"=>1,    "act1"=>"placeStar_1|moveSta_1",    "des1"=>clienttranslate('Place or move ${token_star}'),   "sea2"=>2,    "set2"=>0,    "act2"=>"plant_1",    "des2"=>clienttranslate('Plant a vine card ${token_greenCard}'),   "sea3"=>2,    "set3"=>0,    "act3"=>"sellGrapes_1|buySellVine_1,tradeOneForOne_1",    "des3"=>clienttranslate('Sell at least one grape or buy/sell one field. Trade one for one'),   "sea4"=>4,    "set4"=>0,    "act4"=>"trainWorker_1",    "des4"=>clienttranslate('Train a worker ${token_worker} for ${token_lira4}') ),
  311=>array(    "key"=>311,    "name"=>"Automa 11",    "sea1"=>2,    "set1"=>0,    "act1"=>"playYellowCard_1",    "des1"=>clienttranslate('Play a summer visitor card ${token_yellowCard}'),   "sea2"=>2,    "set2"=>0,    "act2"=>"sellGrapes_1|buySellVine_1,tradeOneForOne_1",    "des2"=>clienttranslate('Sell at least one grape or buy/sell one field. Trade one for one'),   "sea3"=>3,    "set3"=>0,    "act3"=>"harvestField_1",    "des3"=>clienttranslate('Harvest one field'),   "sea4"=>4,    "set4"=>1,    "act4"=>"sellWine_1",    "des4"=>clienttranslate('Sell one wine token') ),
  312=>array(    "key"=>312,    "name"=>"Automa 12",    "sea1"=>2,    "set1"=>1,    "act1"=>"buySellVine_1",    "des1"=>clienttranslate('Buy or sell a vine'),   "sea2"=>2,    "set2"=>0,    "act2"=>"plant_1",    "des2"=>clienttranslate('Plant a vine card ${token_greenCard}'),   "sea3"=>3,    "set3"=>0,    "act3"=>"drawPurpleCard_1",    "des3"=>clienttranslate('Draw a wine order card ${token_purpleCardPlus}'),   "sea4"=>4,    "set4"=>0,    "act4"=>"fillOrder_1",    "des4"=>clienttranslate('Fill a wine order ${token_purpleCard}') ),
  313=>array(    "key"=>313,    "name"=>"Automa 13",    "sea1"=>1,    "set1"=>0,    "act1"=>"drawGreenCard_1",    "des1"=>clienttranslate('Draw a vine card ${token_greenCardPlus}'),   "sea2"=>2,    "set2"=>0,    "act2"=>"playYellowCard_1",    "des2"=>clienttranslate('Play a summer visitor card ${token_yellowCard}'),   "sea3"=>3,    "set3"=>0,    "act3"=>"drawPurpleCard_1",    "des3"=>clienttranslate('Draw a wine order card ${token_purpleCardPlus}'),   "sea4"=>3,    "set4"=>0,    "act4"=>"harvestField_1",    "des4"=>clienttranslate('Harvest one field') ),
  314=>array(    "key"=>314,    "name"=>"Automa 14",    "sea1"=>1,    "set1"=>0,    "act1"=>"getLira_2",    "des1"=>clienttranslate('Give tour to gain ${token_lira2}'),   "sea2"=>2,    "set2"=>0,    "act2"=>"sellGrapes_1|buySellVine_1,tradeOneForOne_1",    "des2"=>clienttranslate('Sell at least one grape or buy/sell one field. Trade one for one'),   "sea3"=>3,    "set3"=>0,    "act3"=>"drawPurpleCard_1",    "des3"=>clienttranslate('Draw a wine order card ${token_purpleCardPlus}'),   "sea4"=>3,    "set4"=>0,    "act4"=>"makeWine_2",    "des4"=>clienttranslate('Make up to two wines') ),
  315=>array(    "key"=>315,    "name"=>"Automa 15",    "sea1"=>1,    "set1"=>0,    "act1"=>"buildStructure_1",    "des1"=>clienttranslate('Build one structure'),   "sea2"=>3,    "set2"=>0,    "act2"=>"drawPurpleCard_1",    "des2"=>clienttranslate('Draw a wine order card ${token_purpleCardPlus}'),   "sea3"=>3,    "set3"=>1,    "act3"=>"buildStructure_1|getLira_2",    "des3"=>clienttranslate('Build one structure or give a tour to get ${token_lira2}'),   "sea4"=>4,    "set4"=>0,    "act4"=>"playBlueCard_1",    "des4"=>clienttranslate('Play a winter visitor card ${token_blueCard}') ),
  316=>array(    "key"=>316,    "name"=>"Automa 16",    "sea1"=>1,    "set1"=>1,    "act1"=>"placeStar_1|moveSta_1",    "des1"=>clienttranslate('Place or move ${token_star}'),   "sea2"=>3,    "set2"=>0,    "act2"=>"harvestField_1",    "des2"=>clienttranslate('Harvest one field'),   "sea3"=>3,    "set3"=>0,    "act3"=>"makeWine_2",    "des3"=>clienttranslate('Make up to two wines'),   "sea4"=>4,    "set4"=>1,    "act4"=>"sellWine_1",    "des4"=>clienttranslate('Sell one wine token') ),
  317=>array(    "key"=>317,    "name"=>"Automa 17",    "sea1"=>2,    "set1"=>1,    "act1"=>"buySellVine_1",    "des1"=>clienttranslate('Buy or sell a vine'),   "sea2"=>3,    "set2"=>0,    "act2"=>"harvestField_1",    "des2"=>clienttranslate('Harvest one field'),   "sea3"=>3,    "set3"=>1,    "act3"=>"buildStructure_1|getLira_2",    "des3"=>clienttranslate('Build one structure or give a tour to get ${token_lira2}'),   "sea4"=>4,    "set4"=>0,    "act4"=>"trainWorker_1",    "des4"=>clienttranslate('Train a worker ${token_worker} for ${token_lira4}') ),
  318=>array(    "key"=>318,    "name"=>"Automa 18",    "sea1"=>2,    "set1"=>0,    "act1"=>"plant_1",    "des1"=>clienttranslate('Plant a vine card ${token_greenCard}'),   "sea2"=>3,    "set2"=>0,    "act2"=>"makeWine_2",    "des2"=>clienttranslate('Make up to two wines'),   "sea3"=>3,    "set3"=>1,    "act3"=>"buildStructure_1|getLira_2",    "des3"=>clienttranslate('Build one structure or give a tour to get ${token_lira2}'),   "sea4"=>4,    "set4"=>0,    "act4"=>"fillOrder_1",    "des4"=>clienttranslate('Fill a wine order ${token_purpleCard}') ),
  319=>array(    "key"=>319,    "name"=>"Automa 19",    "sea1"=>1,    "set1"=>0,    "act1"=>"drawGreenCard_1",    "des1"=>clienttranslate('Draw a vine card ${token_greenCardPlus}'),   "sea2"=>2,    "set2"=>0,    "act2"=>"playYellowCard_1",    "des2"=>clienttranslate('Play a summer visitor card ${token_yellowCard}'),   "sea3"=>4,    "set3"=>0,    "act3"=>"playBlueCard_1",    "des3"=>clienttranslate('Play a winter visitor card ${token_blueCard}'),   "sea4"=>4,    "set4"=>0,    "act4"=>"trainWorker_1",    "des4"=>clienttranslate('Train a worker ${token_worker} for ${token_lira4}') ),
  320=>array(    "key"=>320,    "name"=>"Automa 20",    "sea1"=>1,    "set1"=>0,    "act1"=>"getLira_2",    "des1"=>clienttranslate('Give tour to gain ${token_lira2}'),   "sea2"=>2,    "set2"=>0,    "act2"=>"sellGrapes_1|buySellVine_1,tradeOneForOne_1",    "des2"=>clienttranslate('Sell at least one grape or buy/sell one field. Trade one for one'),   "sea3"=>4,    "set3"=>0,    "act3"=>"playBlueCard_1",    "des3"=>clienttranslate('Play a winter visitor card ${token_blueCard}'),   "sea4"=>4,    "set4"=>1,    "act4"=>"sellWine_1",    "des4"=>clienttranslate('Sell one wine token') ),
  321=>array(    "key"=>321,    "name"=>"Automa 21",    "sea1"=>1,    "set1"=>0,    "act1"=>"buildStructure_1",    "des1"=>clienttranslate('Build one structure'),   "sea2"=>3,    "set2"=>1,    "act2"=>"buildStructure_1|getLira_2",    "des2"=>clienttranslate('Build one structure or give a tour to get ${token_lira2}'),   "sea3"=>4,    "set3"=>0,    "act3"=>"playBlueCard_1",    "des3"=>clienttranslate('Play a winter visitor card ${token_blueCard}'),   "sea4"=>4,    "set4"=>0,    "act4"=>"fillOrder_1",    "des4"=>clienttranslate('Fill a wine order ${token_purpleCard}') ),
  322=>array(    "key"=>322,    "name"=>"Automa 22",    "sea1"=>1,    "set1"=>0,    "act1"=>"placeStar_1|moveSta_1",    "des1"=>clienttranslate('Place or move ${token_star}'),   "sea2"=>3,    "set2"=>0,    "act2"=>"harvestField_1",    "des2"=>clienttranslate('Harvest one field'),   "sea3"=>4,    "set3"=>0,    "act3"=>"trainWorker_1",    "des3"=>clienttranslate('Train a worker ${token_worker} for ${token_lira4}'),   "sea4"=>4,    "set4"=>0,    "act4"=>"sellWine_1",    "des4"=>clienttranslate('Sell one wine token') ),
  323=>array(    "key"=>323,    "name"=>"Automa 23",    "sea1"=>2,    "set1"=>1,    "act1"=>"buySellVine_1",    "des1"=>clienttranslate('Buy or sell a vine'),   "sea2"=>3,    "set2"=>0,    "act2"=>"drawPurpleCard_1",    "des2"=>clienttranslate('Draw a wine order card ${token_purpleCardPlus}'),   "sea3"=>4,    "set3"=>0,    "act3"=>"trainWorker_1",    "des3"=>clienttranslate('Train a worker ${token_worker} for ${token_lira4}'),   "sea4"=>4,    "set4"=>0,    "act4"=>"fillOrder_1",    "des4"=>clienttranslate('Fill a wine order ${token_purpleCard}') ),
  324=>array(    "key"=>324,    "name"=>"Automa 24",    "sea1"=>2,    "set1"=>0,    "act1"=>"plant_1",    "des1"=>clienttranslate('Plant a vine card ${token_greenCard}'),   "sea2"=>3,    "set2"=>0,    "act2"=>"makeWine_2",    "des2"=>clienttranslate('Make up to two wines'),   "sea3"=>4,    "set3"=>1,    "act3"=>"sellWine_1",    "des3"=>clienttranslate('Sell one wine token'),   "sea4"=>4,    "set4"=>0,    "act4"=>"fillOrder_1",    "des4"=>clienttranslate('Fill a wine order ${token_purpleCard}') )  
);

