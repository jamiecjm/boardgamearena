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
 * stats.inc.php
 *
 * viticulture game statistics description
 *
 */

/*
    In this file, you are describing game statistics, that will be displayed at the end of the
    game.

    !! After modifying this file, you must use "Reload  statistics configuration" in BGA Studio backoffice
    ("Control Panel" / "Manage Game" / "Your Game")

    There are 2 types of statistics:
    _ table statistics, that are not associated to a specific player (ie: 1 value for each game).
    _ player statistics, that are associated to each players (ie: 1 value for each player in the game).

    Statistics types can be "int" for integer, "float" for floating point values, and "bool" for boolean

    Once you defined your statistics there, you can start using "initStat", "setStat" and "incStat" method
    in your game logic, using statistics names defined below.

    !! It is not a good idea to modify this file when a game is running !!

    If your game is already public on BGA, please read the following before any change:
    http://en.doc.boardgamearena.com/Post-release_phase#Changes_that_breaks_the_games_in_progress

    Notes:
    * Statistic index is the reference used in setStat/incStat/initStat PHP method
    * Statistic index must contains alphanumerical characters and no space. Example: 'turn_played'
    * Statistics IDs must be >=10
    * Two table statistics can't share the same ID, two player statistics can't share the same ID
    * A table statistic can have the same ID than a player statistics
    * Statistics ID is the reference used by BGA website. If you change the ID, you lost all historical statistic data. Do NOT re-use an ID of a deleted statistic
    * Statistic name is the English description of the statistic as shown to players

*/

$stats_type = array(

    // Statistics global to table
    "table" => array(

        "vit_turns_number" => array("id"=> 10,
                    "name" => totranslate("Number of turns"),
                    "type" => "int" ),

/*
        Examples:


        "table_teststat1" => array(   "id"=> 10,
                                "name" => totranslate("table test stat 1"),
                                "type" => "int" ),

        "table_teststat2" => array(   "id"=> 11,
                                "name" => totranslate("table test stat 2"),
                                "type" => "float" )
*/
    ),

    // Statistics existing for each player
    "player" => array(

        "vit_mama" => array("id"=> 10,
            "name" => totranslate("Mama"),
            "type" => "int" ),

        "vit_papa" => array("id"=> 11,
            "name" => totranslate("Papa"),
            "type" => "int" ),

        "vit_papa_option" => array("id"=> 12,
            "name" => totranslate("Papa option (1:structure/bonus, 2:lira)"),
            "type" => "int" ),

        "vit_wakeup_chart_1" => array("id"=> 13,
            "name" => totranslate("Wakeup chart 1"),
            "type" => "int" ),

        "vit_wakeup_chart_2" => array("id"=> 14,
            "name" => totranslate("Wakeup chart 2"),
            "type" => "int" ),

        "vit_wakeup_chart_3" => array("id"=> 15,
            "name" => totranslate("Wakeup chart 3"),
            "type" => "int" ),

        "vit_wakeup_chart_4" => array("id"=> 16,
            "name" => totranslate("Wakeup chart 4"),
            "type" => "int" ),

        "vit_wakeup_chart_5" => array("id"=> 17,
            "name" => totranslate("Wakeup chart 5"),
            "type" => "int" ),

        "vit_wakeup_chart_6" => array("id"=> 18,
            "name" => totranslate("Wakeup chart 6"),
            "type" => "int" ),

        "vit_wakeup_chart_7" => array("id"=> 19,
            "name" => totranslate("Wakeup chart 7"),
            "type" => "int" ),

        "vit_residual_payment" => array("id"=> 20,
            "name" => totranslate("Residual payment"),
            "type" => "int" ),

        "vit_scoring_fill_order" => array("id"=> 21,
            "name" => totranslate("Scoring from fill order"),
            "type" => "int" ),

        "vit_scoring_wakeup" => array("id"=> 22,
            "name" => totranslate("Scoring from wakeup"),
            "type" => "int" ),

        "vit_scoring_action_bonus" => array("id"=> 23,
            "name" => totranslate("Scoring from actions with bonus"),
            "type" => "int" ),

        "vit_scoring_tasting_room" => array("id"=> 24,
            "name" => totranslate("Scoring from tasting room"),
            "type" => "int" ),

        "vit_scoring_windmill" => array("id"=> 25,
            "name" => totranslate("Scoring from windmill"),
            "type" => "int" ),

        "vit_scoring_yellow_card" => array("id"=> 26,
            "name" => totranslate("Scoring from yellow cards"),
            "type" => "int" ),

        "vit_scoring_blue_card" => array("id"=> 27,
            "name" => totranslate("Scoring from blue cards"),
            "type" => "int" ),

        "vit_trellis" => array("id"=> 28,
            "name" => totranslate("Trellis built"),
            "type" => "int" ),
    
        "vit_irrigation" => array("id"=> 29,
            "name" => totranslate("Irrigation built"),
            "type" => "int" ),
        
        "vit_yoke" => array("id"=> 30,
            "name" => totranslate("Yoke built"),
            "type" => "int" ),
    
        "vit_tasting_room" => array("id"=> 31,
            "name" => totranslate("Tasting Room built"),
            "type" => "int" ),
        
        "vit_cottage" => array("id"=> 32,
            "name" => totranslate("Cottage built"),
            "type" => "int" ),
        
        "vit_windmill" => array("id"=> 33,
            "name" => totranslate("Windmill built"),
            "type" => "int" ),
        
        "vit_medium_cellar" => array("id"=> 34,
            "name" => totranslate("Medium Cellar built"),
            "type" => "int" ),
                
        "vit_large_cellar" => array("id"=> 35,
            "name" => totranslate("Large Cellar built"),
            "type" => "int" ),
        
        "vit_action_give_tour" => array("id"=> 36,
            "name" => totranslate("Action give tour"),
            "type" => "int" ),

        "vit_action_draw_green_card" => array("id"=> 37,
            "name" => totranslate("Action draw vine card"),
            "type" => "int" ),
        
        "vit_action_play_yellow_card" => array("id"=> 38,
            "name" => totranslate("Action play summer visitor card"),
            "type" => "int" ),
                
        "vit_action_sell_grape_or_buy_sell_field" => array("id"=> 39,
            "name" => totranslate("Action sell grape or buy/sell field"),
            "type" => "int" ),
        
        "vit_action_build_structure" => array("id"=> 40,
            "name" => totranslate("Action build structure"),
            "type" => "int" ),
        
        "vit_action_plant" => array("id"=> 41,
            "name" => totranslate("Action plant"),
            "type" => "int" ),
                    
        "vit_action_draw_purple_card" => array("id"=> 42,
            "name" => totranslate("Action draw wine order card"),
            "type" => "int" ),

        "vit_action_play_blue_card" => array("id"=> 43,
            "name" => totranslate("Action play winter visitor card"),
            "type" => "int" ),

        "vit_action_harvest" => array("id"=> 44,
            "name" => totranslate("Action harvest field"),
            "type" => "int" ),
                    
        "vit_action_make_wine" => array("id"=> 45,
            "name" => totranslate("Action make wine"),
            "type" => "int" ),
                    
        "vit_action_train_worker" => array("id"=> 46,
            "name" => totranslate("Action train worker"),
            "type" => "int" ),

        "vit_action_fill_order" => array("id"=> 47,
            "name" => totranslate("Action fill order"),
            "type" => "int" ),

        "vit_action_get_lira" => array("id"=> 48,
            "name" => totranslate("Action get 1 lira"),
            "type" => "int" ),
            
        "vit_actions_with_bonus" => array("id"=> 49,
            "name" => totranslate("Actions with bonus"),
            "type" => "int" ),

        "vit_action_yoke" => array("id"=> 50,
            "name" => totranslate("Action yoke"),
            "type" => "int" ),

        "vit_solo_win" => array("id"=> 51,
            "name" => totranslate("Win in solo mode"),
            "type" => "int" )
    )

);
