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
 * gameoptions.inc.php
 *
 * viticulture game options description
 * 
 * In this file, you can define your game options (= game variants).
 *   
 * Note: If your game has no variant, you don't have to modify this file.
 *
 * Note²: All options defined in this file should have a corresponding "game state labels"
 *        with the same ID (see "initGameStateLabels" in viticulture.game.php)
 *
 * !! It is not a good idea to modify this file when a game is running !!
 *
 */

$game_options = array(

    /* Example of game variant:
    
    
    // note: game variant ID should start at 100 (ie: 100, 101, 102, ...). The maximum is 199.
    100 => array(
                'name' => totranslate('my game option'),    
                'values' => array(

                            // A simple value for this option:
                            1 => array( 'name' => totranslate('option 1') )

                            // A simple value for this option.
                            // If this value is chosen, the value of "tmdisplay" is displayed in the game lobby
                            2 => array( 'name' => totranslate('option 2'), 'tmdisplay' => totranslate('option 2') ),

                            // Another value, with other options:
                            //  beta=true => this option is in beta version right now.
                            //  nobeginner=true  =>  this option is not recommended for beginners
                            3 => array( 'name' => totranslate('option 3'),  'beta' => true, 'nobeginner' => true ),) )
                        )
            )

    */

    101 => array(
        'name' => totranslate('Friendly Variant'),    
        'values' => array(
                    1 => array( 'name' => totranslate('off')),
                    2 => array( 'name' => totranslate('on')),
                )
        ),
        
    102 => array(
        'name' => totranslate('Mama & Papa Choice'),    
        'values' => array(
                    1 => array( 'name' => totranslate('off')),
                    2 => array( 'name' => totranslate('on')),
                )
        ),

    103 => array(
        'name' => totranslate('Solo Mode Difficulty'),
        'values' => array(
                1 => array( 'name' => totranslate('Very Easy')),
                2 => array( 'name' => totranslate('Very Easy-Aggressive')),
                3 => array( 'name' => totranslate('Easy')),
                4 => array( 'name' => totranslate('Easy-Aggressive')),
                5 => array( 'name' => totranslate('Normal')),
                6 => array( 'name' => totranslate('Normal-Aggressive')),
                7 => array( 'name' => totranslate('Hard')),
                8 => array( 'name' => totranslate('Hard-Aggressive')),
                9 => array( 'name' => totranslate('Very Hard')),
                10 => array( 'name' => totranslate('Very Hard-Aggressive'))
        ),
        'displaycondition' => array( 
            // Note: do not display this option unless these conditions are met
            array(
                'type' => 'maxplayers',
                'value' => 1
            ),
            array(
                'type' => 'minplayers',
                'value' => 1
            )
        ),
        'default'=>5
    ),

);

$game_preferences = array(
	100 => array(
			'name' => totranslate('Show values for vp and lira icons'),
			'needReload' => false, // after user changes this preference game interface would auto-reload
			'values' => array(
					1 => array( 'name' => totranslate( 'Disabled' ), 'cssPref' => 'vit_tokens_without_value' ),
					2 => array( 'name' => totranslate( 'Enabled' ), 'cssPref' => 'vit_tokens_with_value' )
			)
    ),
    101 => array(
            'name' => totranslate('Enables Winter Pass Action'),
            'needReload' => true, // after user changes this preference game interface would auto-reload
            'values' => array(
                    1 => array( 'name' => totranslate( 'Enabled' ), 'cssPref' => 'vit_winter_pass_enabled' ),
                    2 => array( 'name' => totranslate( 'Disabled' ), 'cssPref' => 'vit_winter_pass_disabled' )
            )
    )
);


