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
 * states.inc.php
 *
 * viticulture game states description
 *
 */

$machinestates = array(

    // The initial state. Please do not modify.
    1 => array(
        "name" => "gameSetup",
        "description" => "",
        "type" => "manager",
        "action" => "stGameSetup",
        "transitions" => array( "" => 2 )
    ),
    
    // Note: ID=2 => your first state

    //select mamas and papas for choice or go to mama effect
    STATE_START_GAME => array(
        "name" => "startGame",
        "description" => '',
        "type" => "game",
        "action" => "stStartGame",
        "updateGameProgression" => false,   
        "transitions" => array( "choose" => STATE_MAMA_PAPA_CHOOSE, "mamaEffect" => STATE_MAMA_EFFECT )
    ),

    //mamas & papas choose card (optional MULTI) -> chooseMamaPapa
    STATE_MAMA_PAPA_CHOOSE => array(
        "name" => "mamaPapaChoose",
        "description" => clienttranslate('Players must choose mama and papa'),
        "descriptionmyturn" => clienttranslate('${you} must choose mama and papa'),
        "type" => "multipleactiveplayer",
        "args" => "argMamaPapaChoose",
        "possibleactions" => array( "chooseMamaPapa" ),
        "transitions" => array( "next" => STATE_MAMA_EFFECT, "zombiePass" => STATE_MAMA_EFFECT )
    ),

    //mama effect
    STATE_MAMA_EFFECT => array(
        "name" => "mamaEffect",
        "description" => '',
        "type" => "game",
        "action" => "stMamaEffect",
        "updateGameProgression" => false,   
        "transitions" => array( "next" => STATE_PAPA_OPTION_CHOOSE)
    ),

    //papa choose option -> mamaPapaChoices option
    STATE_PAPA_OPTION_CHOOSE => array(
        "name" => "papaOptionChoose",
        "description" => clienttranslate('${actplayer} must choose papa option'),
        "descriptionmyturn" => clienttranslate('${you} must choose papa option'),
        "type" => "activeplayer",
        "possibleactions" => array( "choosePapaOption"),
        "args" => "argPapaOptionChoose",
        "transitions" => array( "next" => STATE_PAPA_OPTION_CHOOSE_NEXT, "zombiePass" => STATE_PAPA_OPTION_CHOOSE_NEXT )
    ),

    //papa effect then nextplayer or go to game
    STATE_PAPA_OPTION_CHOOSE_NEXT => array(
        "name" => "papaOptionChooseNext",
        "description" => '',
        "type" => "game",
        "action" => "stPapaOptionChooseNext",
        "updateGameProgression" => false,   
        "transitions" => array( "next" => STATE_PAPA_OPTION_CHOOSE, "end" => STATE_START_TURN)
    ),

    //newturn
    STATE_START_TURN => array(
        "name" => "startTurn",
        "description" => '',
        "type" => "game",
        "action" => "stStartTurn",
        "updateGameProgression" => true,   
        "transitions" => array( "next" => STATE_SPRING_CHOOSE_WAKEUP)
    ),

    //spring (new player order & effect) -> chooseOrder
    STATE_SPRING_CHOOSE_WAKEUP => array(
        "name" => "springChooseWakeup",
        "description" => clienttranslate('${actplayer} must choose a wake-up row'),
        "descriptionmyturn" => clienttranslate('${you} must choose a wake-up row'),
        "type" => "activeplayer",
        "args" => "argSpringChooseWakeup",
        "possibleactions" => array( "chooseWakeup"),
        "transitions" => array( "next" => STATE_SPRING_CHOOSE_WAKEUP_NEXT, "zombiePass" => STATE_SPRING_CHOOSE_WAKEUP_NEXT )
    ),

    //spring next player or go to game
    STATE_SPRING_CHOOSE_WAKEUP_NEXT => array(
        "name" => "springChooseWakeupNext",
        "description" => '',
        "type" => "game",
        "action" => "stSpringChooseWakeupNext",
        "updateGameProgression" => true,   
        "transitions" => array( "next" => STATE_SPRING_CHOOSE_WAKEUP, "end" => STATE_START_SEASON_WORKERS)
    ),

    //start season workers
    STATE_START_SEASON_WORKERS => array(
        "name" => "startSeasonWorkers",
        "description" => '',
        "type" => "game",
        "action" => "stStartSeasonWorkers",
        "updateGameProgression" => true,   
        "transitions" => array( "next" => STATE_SEASON_WORKERS_NEXT)
    ),

    //summer/winter -> choose_action, pass
    STATE_SEASON_WORKERS => array(
        "name" => "seasonWorkers",
        "description" => clienttranslate('${actplayer} must place a worker or pass'),
        "descriptionmyturn" => clienttranslate('${you} must place a worker or pass'),
        "type" => "activeplayer",
        "args" => "argSeasonWorkers",
        "possibleactions" => array( "placeWorker", "pass"),
        "transitions" => array( "next" => STATE_SEASON_WORKERS_NEXT, "zombiePass" => STATE_SEASON_WORKERS_NEXT )
    ),

    //summer/winter next player or go to fall season
    STATE_SEASON_WORKERS_NEXT => array(
        "name" => "seasonWorkersNext",
        "description" => '',
        "type" => "game",
        "action" => "stSeasonWorkersNext",
        "updateGameProgression" => true,   
        "transitions" => array( "next" => STATE_SEASON_WORKERS, "plant" => STATE_PLANT, 
            "makeWine" => STATE_MAKE_WINE, "playYellowCard" => STATE_PLAY_YELLOW_CARD,
            "fillOrder" => STATE_FILL_ORDER, "playBlueCard" => STATE_PLAY_BLUE_CARD, 
            "playCardSecondOption" => STATE_PLAY_CARD_SECOND_OPTION,
            "chooseVisitorCardDraw"=>STATE_CHOOSE_VISITOR_CARD_DRAW,
            "chooseCards"=>STATE_CHOOSE_CARDS,
            "chooseOptions"=>STATE_CHOOSE_OPTIONS,
            "executeLocation"=>STATE_EXECUTE_LOCATION,
            'takeActionPrev'=>STATE_TAKE_ACTION_PREV,
            'discardVines'=>STATE_DISCARD_VINES,
            "allBuild" => STATE_ALL_BUILD, "allChoose" => STATE_ALL_CHOOSE, "allPlant" => STATE_ALL_PLANT, 'allGiveCard' => STATE_ALL_GIVE_CARD,
            "chooseFallCard" => STATE_FALL_CHOOSE_CARD, "end" => STATE_END_TURN)
    ),

    //fall -> choose_draw_card
    STATE_FALL_CHOOSE_CARD => array(
        "name" => "fallChooseCard",
        "description" => clienttranslate('${actplayer} must draw a summer or winter visitor card'),
        "descriptionmyturn" => clienttranslate('${you} must draw a summer or winter visitor card'),
        //choose two decks (cottage)
        "descriptionChoose1" => clienttranslate('${actplayer} must draw a summer or winter visitor card'),
        "descriptionChoose1myturn" => clienttranslate('${you} must draw a summer or winter visitor card'),
        //choose two decks (cottage)
        "descriptionChoose2" => clienttranslate('${actplayer} must draw two visitor cards (cottage)'),
        "descriptionChoose2myturn" => clienttranslate('${you} must draw two visitor cards (cottage)'),
        "type" => "activeplayer",
        "args" => "argFallChooseCard",
        "possibleactions" => array( "chooseFallCard"),
        "transitions" => array( "next" => STATE_FALL_CHOOSE_CARD_NEXT, "zombiePass" => STATE_FALL_CHOOSE_CARD_NEXT )
    ),

    //fall next player or go to workers season (winter)
    STATE_FALL_CHOOSE_CARD_NEXT => array(
        "name" => "fallChooseCardNext",
        "description" => '',
        "type" => "game",
        "action" => "stFallChooseCardNext",
        "updateGameProgression" => false,   
        "transitions" => array( "next" => STATE_FALL_CHOOSE_CARD, "end" => STATE_START_SEASON_WORKERS)
    ),
    
    //plant -> plant, refuse
    STATE_PLANT => array(
        "name" => "plant",
        "description" => clienttranslate('${actplayer} can plant a field'),
        "descriptionmyturn" => clienttranslate('${you} can plant a field'),
        "type" => "activeplayer",
        "args" => "argPlant",
        "possibleactions" => array( "plant", "refuse"),
        "transitions" => array( "next" => STATE_SEASON_WORKERS_NEXT, "zombiePass" => STATE_SEASON_WORKERS_NEXT )
    ),
    
    //makeWine -> makeWine, refuse
    STATE_MAKE_WINE => array(
        "name" => "makeWine",
        "description" => clienttranslate('${actplayer} can make a wine'),
        "descriptionmyturn" => clienttranslate('${you} can make a wine'),
        "type" => "activeplayer",
        "args" => "argMakeWine",
        "possibleactions" => array( "makeWine", "refuse"),
        "transitions" => array( "next" => STATE_SEASON_WORKERS_NEXT, "zombiePass" => STATE_SEASON_WORKERS_NEXT )
    ),

    //playYellowCard -> playYellowCard, refuse
    STATE_PLAY_YELLOW_CARD => array(
        "name" => "playYellowCard",
        "description" => clienttranslate('${actplayer} can play a summer visitor card'),
        "descriptionmyturn" => clienttranslate('${you} can play a summer visitor card'),
        "type" => "activeplayer",
        "args" => "argPlayYellowCard",
        "possibleactions" => array( "playYellowCard", "refuse"),
        "transitions" => array( "next" => STATE_SEASON_WORKERS_NEXT, "zombiePass" => STATE_SEASON_WORKERS_NEXT )
    ),

    //playBlueCard -> playBlueCard, refuse
    STATE_PLAY_BLUE_CARD => array(
        "name" => "playBlueCard",
        "description" => clienttranslate('${actplayer} can play a winter visitor card'),
        "descriptionmyturn" => clienttranslate('${you} can play a winter visitor card'),
        "type" => "activeplayer",
        "args" => "argPlayBlueCard",
        "possibleactions" => array( "playBlueCard", "refuse"),
        "transitions" => array( "next" => STATE_SEASON_WORKERS_NEXT, "zombiePass" => STATE_SEASON_WORKERS_NEXT )
    ),

    //playBlueCard -> playBlueCard, refuse
    STATE_FILL_ORDER => array(
        "name" => "fillOrder",
        "description" => clienttranslate('${actplayer} can fill a wine order card'),
        "descriptionmyturn" => clienttranslate('${you} can fill a wine order card'),
        "type" => "activeplayer",
        "args" => "argFillOrder",
        "possibleactions" => array( "fillOrder", "refuse"),
        "transitions" => array( "next" => STATE_SEASON_WORKERS_NEXT, "zombiePass" => STATE_SEASON_WORKERS_NEXT )
    ),

    //playCardSecondOption -> playCardSecondOption, refuse
    STATE_PLAY_CARD_SECOND_OPTION => array(
        "name" => "playCardSecondOption",
        "description" => clienttranslate('${actplayer} can play an additional option of visitor card'),
        "descriptionmyturn" => clienttranslate('${you} can play an additional option of visitor card'),
        "type" => "activeplayer",
        "args" => "argPlayCardSecondOption",
        "possibleactions" => array( "playCardSecondOption", "refuse"),
        "transitions" => array( "next" => STATE_SEASON_WORKERS_NEXT, "zombiePass" => STATE_SEASON_WORKERS_NEXT )
    ),

    //chooseVisitorCardDraw -> chooseVisitorCardDraw
    STATE_CHOOSE_VISITOR_CARD_DRAW => array(
        "name" => "chooseVisitorCardDraw",
        "description" => clienttranslate('${actplayer} can draw a visitor card'),
        "descriptionmyturn" => clienttranslate('${you} can draw a visitor card'),
        "type" => "activeplayer",
        "args" => "argChooseVisitorCardDraw",
        "possibleactions" => array( "chooseVisitorCardDraw"),
        "transitions" => array( "next" => STATE_SEASON_WORKERS_NEXT, "zombiePass" => STATE_SEASON_WORKERS_NEXT )
    ),

    //chooseCards -> chooseCards
    STATE_CHOOSE_CARDS => array(
        "name" => "chooseCards",
        "description" => clienttranslate('${actplayer} can choose ${maxCards} cards'),
        "descriptionmyturn" => clienttranslate('${you} can choose ${maxCards} cards'),
        "type" => "activeplayer",
        "args" => "argChooseCards",
        "possibleactions" => array( "chooseCards"),
        "transitions" => array( "next" => STATE_SEASON_WORKERS_NEXT, "zombiePass" => STATE_SEASON_WORKERS_NEXT )
    ),

    //discardVines-> discardVines
    STATE_DISCARD_VINES => array(
        "name" => "discardVines",
        "description" => clienttranslate('${actplayer} must discard ${minCards} ${token_card}'),
        "descriptionmyturn" => clienttranslate('${you} must discard ${minCards} ${token_card}'),
        "type" => "activeplayer",
        "args" => "argDiscardVines",
        "possibleactions" => array( "discardVines"),
        "transitions" => array( "next" => STATE_SEASON_WORKERS_NEXT, "zombiePass" => STATE_SEASON_WORKERS_NEXT )
    ),
    
    //chooseOptions -> chooseOptions
    STATE_CHOOSE_OPTIONS => array(
        "name" => "chooseOptions",
        "description" => clienttranslate('${actplayer} can choose an option'),
        "descriptionmyturn" => clienttranslate('${you} can choose an option'),
        "type" => "activeplayer",
        "args" => "argChooseOptions",
        "possibleactions" => array( "chooseOptions"),
        "transitions" => array( "next" => STATE_SEASON_WORKERS_NEXT, "zombiePass" => STATE_SEASON_WORKERS_NEXT )
    ),

    //executeLocation -> executeLocation
    STATE_EXECUTE_LOCATION => array(
        "name" => "executeLocation",
        "description" => clienttranslate('${actplayer} can execute location effects'),
        "descriptionmyturn" => clienttranslate('${you} can execute location effects'),
        "type" => "activeplayer",
        "args" => "argExecuteLocation",
        "possibleactions" => array( "executeLocation","refuse"),
        "transitions" => array( "next" => STATE_SEASON_WORKERS_NEXT, "refuse" => STATE_SEASON_WORKERS_NEXT, "zombiePass" => STATE_SEASON_WORKERS_NEXT )
    ),

    //takeActionPrev -> takeActionPrev
    STATE_TAKE_ACTION_PREV => array(
        "name" => "takeActionPrev",
        "description" => clienttranslate('${actplayer} can take any action in a previous season'),
        "descriptionmyturn" => clienttranslate('${you} can take any action in a previous season'),
        "type" => "activeplayer",
        "args" => "argTakeActionPrev",
        "possibleactions" => array( "takeActionPrev", "cancelAction", "refuse"),
        "transitions" => array( "next" => STATE_SEASON_WORKERS_NEXT, "same" => STATE_SEASON_WORKERS, "playBlueCard" => STATE_PLAY_BLUE_CARD,  "zombiePass" => STATE_SEASON_WORKERS_NEXT )
    ),

    //all players can build a structure (optional MULTI) -> allBuild
    STATE_ALL_BUILD => array(
        "name" => "allBuild",
        "description" => clienttranslate('Players can build a structure'),
        "descriptionmyturn" => clienttranslate('${you} can build a structure'),
        "type" => "multipleactiveplayer",
        "args" => "argAllBuild",
        "possibleactions" => array( "allBuild" , "refuse"),
        "transitions" => array( "next" => STATE_ALL_ACTION_END, "zombiePass" => STATE_ALL_ACTION_END )
    ),

    //all players can plant (optional MULTI) -> allPlant
    STATE_ALL_PLANT => array(
        "name" => "allPlant",
        "description" => clienttranslate('Players can plant a vine card'),
        "descriptionmyturn" => clienttranslate('${you} can plant a vine card'),
        "type" => "multipleactiveplayer",
        "args" => "argAllPlant",
        "possibleactions" => array( "allPlant" , "refuse"),
        "transitions" => array( "next" => STATE_ALL_ACTION_END, "zombiePass" => STATE_ALL_ACTION_END )
    ),

    //all players can give a card (optional MULTI) -> allGiveCard
    STATE_ALL_GIVE_CARD => array(
        "name" => "allGiveCard",
        "description" => clienttranslate('Players must give ${token_card} to ${playerNameGive}'),
        "descriptionmyturn" => clienttranslate('${you} must give ${token_card} to ${playerNameGive}'),
        //623 Importer
        "description623" => clienttranslate('All opponents can give up to 3 ${token_card1}/${token_card2} (total) to ${playerNameGive}'),
        "description623myturn" => clienttranslate('${you} and opponents can give up to 3 ${token_card1}/${token_card2} (total) to ${playerNameGive}'),
        //835 Governor
        "description835" => clienttranslate('Players must give ${token_card} to ${playerNameGive}'),
        "description835myturn" => clienttranslate('${you} must give ${token_card} to ${playerNameGive}'),
        "type" => "multipleactiveplayer",
        "args" => "argAllGiveCard",
        "possibleactions" => array( "allGiveCard" , "refuse"),
        "transitions" => array( "next" => STATE_ALL_ACTION_END, "zombiePass" => STATE_ALL_ACTION_END )
    ),

    //all players can choose an option/confirm (optional MULTI) -> allChoose
    STATE_ALL_CHOOSE => array(
        "name" => "allChoose",
        "description" => clienttranslate('Players can choose'),
        "descriptionmyturn" => clienttranslate('${you} can choose'),
        //621 Banker
        "description621" => clienttranslate('Players can choose: lose ${token_vp1} to gain ${token_lira3}'),
        "description621myturn" => clienttranslate('${you} can choose: lose ${token_vp1} to gain ${token_lira3}'),
        //631 Swindler
        "description631" => clienttranslate('Players can choose: give ${token_lira2} to ${other_player_name}, or ${other_player_name} gains ${token_vp1}'),
        "description631myturn" => clienttranslate('${you} can choose: give ${token_lira2} to ${other_player_name}, or ${other_player_name} gains ${token_vp1}'),
        //825 Motivaror
        "description825" => clienttranslate('Players can choose: retrieve grande worker ${token_workerGrande} and ${other_player_name} gains ${token_vp1}'),
        "description825myturn" => clienttranslate('${you} can choose: retrieve grande worker ${token_workerGrande} and ${other_player_name} gains ${token_vp1}'),
        //838 Guest Speaker
        "description838" => clienttranslate('Players can choose: pay ${token_lira1} to train a worker and ${other_player_name} gains ${token_vp1}'),
        "description838myturn" => clienttranslate('${you} can choose: pay ${token_lira1} to train a worker and ${other_player_name} gains ${token_vp1}'),
        //
        "type" => "multipleactiveplayer",
        "args" => "argAllChoose",
        "possibleactions" => array( "allChoose" , "refuse"),
        "transitions" => array( "next" => STATE_ALL_ACTION_END, "zombiePass" => STATE_ALL_ACTION_END )
    ),

    //end action all players
    // (ageing grapes and wine, get residual payments, remove workers, remove rooker, rotate first player >= 20 score -> end game)
    STATE_ALL_ACTION_END => array(
        "name" => "allActionEnd",
        "description" => '',
        "type" => "game",
        "action" => "stAllActionEnd",
        "updateGameProgression" => true,   
        "transitions" => array( "next" => STATE_SEASON_WORKERS_NEXT)
    ),

    //endturn
    // (ageing grapes and wine, get residual payments, remove workers, remove rooker, rotate first player >= 20 score -> end game)
    STATE_END_TURN => array(
        "name" => "endTurn",
        "description" => '',
        "type" => "game",
        "action" => "stEndTurn",
        "updateGameProgression" => true,   
        "transitions" => array( "next" => STATE_START_TURN, "discard" => STATE_DISCARD_CARDS, "end" => 99)
    ),
    
    //discard cards over 7
    STATE_DISCARD_CARDS => array(
        "name" => "discardCards",
        "description" => clienttranslate('Players must discard down to 7 cards'),
        "descriptionmyturn" => clienttranslate('${you} must discard down to 7 cards'),
        "type" => "multipleactiveplayer",
        "args" => "argDiscardCards",
        "possibleactions" => array( "discardCards" ),
        "transitions" => array( "next" => STATE_START_TURN, "zombiePass" => STATE_START_TURN )
    ),
    
    // Final state.
    // Please do not modify.
    99 => array(
        "name" => "gameEnd",
        "description" => clienttranslate("End of game"),
        "type" => "manager",
        "action" => "stGameEnd",
        "args" => "argGameEnd"
    )

);



