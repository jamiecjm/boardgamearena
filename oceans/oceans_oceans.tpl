{OVERALL_GAME_HEADER}

<!--
--------
-- BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
-- oceans implementation : © <Your name here> <Your email address here>
--
-- This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
-- See http://en.boardgamearena.com/#!doc/Studio for more information.
-------

    oceans_oceans.tpl

    This is the HTML template of your game.

    Everything you are writing in this file will be displayed in the HTML page of your game user interface,
    in the "main game zone" of the screen.

    You can use in this template:
    _ variables, with the format {MY_VARIABLE_ELEMENT}.
    _ HTML block, with the BEGIN/END format

    See your "view" PHP file to check how to set variables and control blocks

    Please REMOVE this comment before publishing your game on BGA
-->

<div id="playertables">

    <!-- BEGIN player -->
    <div class="playertable whiteblock playertable_{DIR}">
        <div class="playertablename" style="color:#{PLAYER_COLOR}">
            {PLAYER_NAME}
        </div>
        <div class="playertablecard" id="playertablecard_{PLAYER_ID}">
        </div>
    </div>
    <!-- END player -->

</div>

<div id="oceans_board">
    <div id="main">
        <div id="reef">
            <div id="reef_population_count" class="counter population_count whiteblock"></div>
        </div>
        <!-- <div id="species_boards"></div> -->
        <div id="ocean">
            <div id="ocean_zone1">
                <div id="ocean_zone1_population_count" class="counter population_count whiteblock"></div>
                <div id="scenario_card_1"></div>
            </div>
            <div id="ocean_zone2">
                <div id="ocean_zone2_population_count" class="counter population_count whiteblock"></div>
                <div id="scenario_card_2"></div>
            </div>
            <div id="ocean_zone3">
                <div id="ocean_zone3_population_count" class="counter population_count whiteblock"></div>
            </div>
        </div>
    </div>
    <div id="deck">
        <div id="surface_deck">
            <div id="surface_card_back" class="cards surface_cards">
                <div id="surface_deck_count" class="counter deck_count whiteblock"></div>
            </div>
            <div id="surface_card"></div>
        </div>
        <div id="deep_deck">
            <div id="deep_card_back" class="cards deep_cards"></div>
            <div id="deep_card_1" class="cards deep_cards"></div>
            <div id="deep_card_2" class="cards deep_cards"></div>
        </div>
    </div>
</div>



<script type="text/javascript">

// Javascript HTML templates

/*
// Example:
var jstpl_some_game_item='<div class="my_game_item" id="my_game_item_${MY_ITEM_ID}"></div>';

*/

var jstpl_discardSurface = '<div class="cards surface_cards" style="background-position:-${x}px 0px"></div>';
var jstpl_oceanZone1Scenario = '<div class="scenario_cards" style="background-position:-${x}px -${y}px"></div>';
var jstpl_oceanZone2Scenario = '<div class="scenario_cards" style="background-position:-${x}px -${y}px"></div>';

</script>

{OVERALL_GAME_FOOTER}
