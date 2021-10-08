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
  * viticulture.game.php
  *
  * This is the main file for your game logic.
  *
  * In this PHP file, you are going to defines the rules of the game.
  *
  */


require_once( APP_GAMEMODULE_PATH.'module/table/table.game.php' );

// Sets & expansions
define('SET_BASE', 0); //Viticulture essential edition base game


// State constants
// Note: ID=2 => your first state
define("STATE_START_GAME", 2);
define("STATE_MAMA_PAPA_CHOOSE", 10);
define("STATE_MAMA_EFFECT", 11);
define("STATE_PAPA_OPTION_CHOOSE", 12);
define("STATE_PAPA_OPTION_CHOOSE_NEXT", 13);
define("STATE_START_TURN", 20);
define("STATE_SPRING_CHOOSE_WAKEUP", 30);
define("STATE_SPRING_CHOOSE_WAKEUP_NEXT", 31);
define("STATE_START_SEASON_WORKERS", 40);
define("STATE_SEASON_WORKERS", 41);
define("STATE_SEASON_WORKERS_NEXT", 42);
define("STATE_FALL_CHOOSE_CARD", 50);
define("STATE_FALL_CHOOSE_CARD_NEXT", 51);
define("STATE_PLANT", 70);
define("STATE_MAKE_WINE", 71);
define("STATE_PLAY_YELLOW_CARD", 72);
define("STATE_PLAY_BLUE_CARD", 73);
define("STATE_FILL_ORDER", 74);
define("STATE_CHOOSE_VISITOR_CARD_DRAW", 75);
define("STATE_CHOOSE_CARDS", 76);
define("STATE_CHOOSE_OPTIONS", 77);
define("STATE_EXECUTE_LOCATION", 78);
define("STATE_PLAY_CARD_SECOND_OPTION", 79);
define("STATE_TAKE_ACTION_PREV", 80);
define("STATE_ALL_BUILD", 81);
define("STATE_ALL_CHOOSE", 82);
define("STATE_ALL_PLANT", 83);
define("STATE_ALL_GIVE_CARD", 84);
define("STATE_DISCARD_VINES", 85);
define("STATE_ALL_ACTION_END", 89);
define("STATE_END_TURN", 90);
define("STATE_DISCARD_CARDS", 91);
define("STATE_END_GAME", 99);

define("DECK_GREEN", 'deckGreen');
define("DECK_YELLOW", 'deckYellow');
define("DECK_PURPLE", 'deckPurple');
define("DECK_BLUE", 'deckBlue');
define("DECK_AUTOMA", 'deckAutoma');
define("DISCARD_GREEN", 'discardGreen');
define("DISCARD_YELLOW", 'discardYellow');
define("DISCARD_PURPLE", 'discardPurple');
define("DISCARD_BLUE", 'discardBlue');
define("DISCARD_AUTOMA", 'discardAutoma');

define("HAND", 'hand');

define("SPRING", 1);
define("SUMMER", 2);
define("FALL", 3);
define("WINTER", 4);

define("END_GAME_SCORING", 20);

define("STATUS_NEW", 0);
define("STATUS_IN_PROGRESS", 1);

define("SOLO_PLAYER_ID", -1);


class viticulture extends Table
{
	function __construct( )
	{
        // Your global variables labels:
        //  Here, you can assign labels to global variables you are using for this game.
        //  You can use any number of global variables with IDs between 10 and 99.
        //  If your game has options (variants), you also have to associate here a label to
        //  the corresponding ID in gameoptions.inc.php.
        // Note: afterwards, you can get/set the global variables with getGameStateValue/setGameStateInitialValue/setGameStateValue
        parent::__construct();


        self::initGameStateLabels( array(
            //globals
            "turn" => 10,
            "season" => 11,
            "progression" => 12,
            "set" => 13,
            "active_player" => 14,
            "fall_two_cards" => 15,
            "force_next_player_id" => 16,
            "game_end" => 17,
            "solo" => 18,
            "soloDifficulty" => 19,
            "soloAggressive" => 20,
            //options
            "friendly_variant" => 101,
            "mama_papa_choice" => 102,
            "solo_mode_difficulty" => 103,
            )
        );

        $this->cards = self::getNew('module.common.deck');
        $this->cards->init('card');
	}

    protected function getGameName( )
    {
		// Used for translations and stuff. Please do not modify.
        return "viticulture";
    }

    /*
        setupNewGame:

        This method is called only once, when a new game is launched.
        In this method, you must setup the game according to the game rules, so that
        the game is ready to be played.
    */
    protected function setupNewGame( $players, $options = array() )
    {
        // Set the colors of the players with HTML color code
        // The default below is red/green/blue/orange/brown
        // The number of colors defined here must correspond to the maximum number of players allowed for the gams
        $gameinfos = self::getGameinfos();
        $default_colors = $gameinfos['player_colors'];

        $order = 0;
        $playersCount = 0;

        // Create players
        // Note: if you added some extra field on "player" table in the database (dbmodel.sql), you can initialize it there.
        $sql = "INSERT INTO player (player_id, player_color, player_canal, player_name, player_avatar, playorder) VALUES ";
        $values = array();
        foreach( $players as $playerId => $player )
        {
            $order += 1;
            $playersCount += 1;
            $color = array_shift( $default_colors );
            $values[] = "('".$playerId."','$color','".$player['player_canal']."','".addslashes( $player['player_name'] )."','".addslashes( $player['player_avatar'] )."',".$order.")";
            $last_player_id = $playerId;
        }
        $sql .= implode( $values, ',' );
        self::DbQuery( $sql );
        self::reattributeColorsBasedOnPreferences( $players, $gameinfos['player_colors'] );
        self::reloadPlayersBasicInfos();

        /************ Start the game initialization *****/
        self::setGameStateInitialValue( 'turn', 0 );
        self::setGameStateInitialValue( 'season', 0 );
        self::setGameStateInitialValue( 'set', 0 );
        self::setGameStateInitialValue( 'active_player', 0 );
        self::setGameStateInitialValue( 'fall_two_cards', 0 );
        
        if ($playersCount==1){
            $soloModeDifficultyOption = self::getGameStateValue('solo_mode_difficulty');
            switch ($soloModeDifficultyOption) {
                case 1: //Very Easy
                    self::setGameStateInitialValue( 'soloDifficulty', 1 );
                    self::setGameStateInitialValue( 'soloAggressive', 0 );
                    break;
                case 2: //Very Easy - Aggressive
                    self::setGameStateInitialValue( 'soloDifficulty', 1 );
                    self::setGameStateInitialValue( 'soloAggressive', 1 );
                    break;
                case 3: //Easy
                    self::setGameStateInitialValue( 'soloDifficulty', 2 );
                    self::setGameStateInitialValue( 'soloAggressive', 0 );
                    break;
                case 4: //Easy - Aggressive
                    self::setGameStateInitialValue( 'soloDifficulty', 2 );
                    self::setGameStateInitialValue( 'soloAggressive', 1 );
                    break;
                case 5: //Normal
                    self::setGameStateInitialValue( 'soloDifficulty', 3 );
                    self::setGameStateInitialValue( 'soloAggressive', 0 );
                    break;
                case 6: //Normal - Aggressive
                    self::setGameStateInitialValue( 'soloDifficulty', 3 );
                    self::setGameStateInitialValue( 'soloAggressive', 1 );
                    break;
                case 7: //Hard
                    self::setGameStateInitialValue( 'soloDifficulty', 4 );
                    self::setGameStateInitialValue( 'soloAggressive', 0 );
                    break;
                case 8: //Hard - Aggressive
                    self::setGameStateInitialValue( 'soloDifficulty', 4 );
                    self::setGameStateInitialValue( 'soloAggressive', 1 );
                    break;
                case 9: //Very Hard
                    self::setGameStateInitialValue( 'soloDifficulty', 5 );
                    self::setGameStateInitialValue( 'soloAggressive', 0 );
                    break;
                case 10: //Very Hard - Aggressive
                    self::setGameStateInitialValue( 'soloDifficulty', 5 );
                    self::setGameStateInitialValue( 'soloAggressive', 1 );
                    break;
            }
            self::setGameStateInitialValue( 'solo', 1 );
            $solo = 1;
        } else {
            self::setGameStateInitialValue( 'solo', 0 );
            self::setGameStateInitialValue( 'soloDifficulty', 3 );
            self::setGameStateInitialValue( 'soloAggressive', 0 );
            $solo = 0;
        }

        self::setGameStateInitialValue( 'force_next_player_id', 0 );
        self::setGameStateInitialValue( 'game_end', 0 );

        // Create player tokens
        $tokens = array();
        foreach ($this->playerTokens as $key=>$value){
            if ($this->isComponentPlayableBySet($value['set'])){
                $tokens[] = array('type' => $value["type"], 'type_arg' => $value['type_arg'], 'location' => $value['location'], 'nbr' => 1);
            }
        }
        $cardsByLocation = $this->group_by('location',$tokens);
        foreach( $players as $playerId => $player )
        {
            foreach( $cardsByLocation as $cardsByLocationKey => $cardsByLocationValue )
            {
                $this->cards->createCards( $cardsByLocationValue, $cardsByLocationKey, $playerId);
            }
        }

        // Solo player tokens
        if ($solo>0){
            foreach ($this->playerTokens as $key=>$value){
                if ($this->isComponentPlayableBySet($value['set']) && $value['automa'] && $value['automa'] !='NO'){
                    $cards = array();
                    $cards[] = array('type' => $value["type"], 'type_arg' => $value['type_arg'], 'location' => $value['automa'], 'nbr' => 1);
                    $this->cards->createCards( $cards, $value['automa'] , SOLO_PLAYER_ID);
                }
            }

            $playersData = $this->getPlayersData();
            $player = reset($playersData);
            $default_colors = $gameinfos['player_colors'];
            $color = array_shift( $default_colors );
            while ($color==$player['player_color']){
                $color = array_shift( $default_colors );
            }
            $cards = array();
            $cards[] = array('type' =>'color', 'type_arg' => 0, 'nbr' => 1);
            $this->cards->createCards( $cards, $color, SOLO_PLAYER_ID);

            //bonus (wakeup slots)
            for ($wakeup=1; $wakeup <= 7; $wakeup++) { 
                $cards = array();
                $cards[] = array('type' =>'wakeup_bonus', 'type_arg' => $wakeup, 'nbr' => 1);
                $this->cards->createCards( $cards, 'board', 0);
            }
        }

        // Loop over green cards
        $cards = array();
        foreach ($this->greenCards as $key=>$value){
            if ($this->isComponentPlayableBySet($value['set'])){
                $cards[] = array('type' =>'greenCard', 'type_arg' => $key, 'nbr' => $value['qty']);
            }
        }
        $this->cards->createCards( $cards, DECK_GREEN);
        //shuffle after create because of qty > 1
        $this->cards->shuffle( DECK_GREEN);

        // Loop over yellow cards
        $cards = array();
        foreach ($this->yellowCards as $key=>$value){
            if ($this->isComponentPlayableBySet($value['set'])){
                // all cards if not solo
                // in solo only cards with solo=1
                if ($solo==0 || $value['solo']==1){
                    $cards[] = array('type' =>'yellowCard', 'type_arg' => $key, 'nbr' => 1);
                }
            }
        }
        shuffle($cards);
        $this->cards->createCards( $cards, DECK_YELLOW);
        
        // Loop over purple cards
        $cards = array();
        foreach ($this->purpleCards as $key=>$value){
            if ($this->isComponentPlayableBySet($value['set'])){
                $cards[] = array('type' =>'purpleCard', 'type_arg' => $key, 'nbr' => 1);
            }
        }
        shuffle($cards);
        $this->cards->createCards( $cards, DECK_PURPLE);

        // Loop over blue cards
        $cards = array();
        foreach ($this->blueCards as $key=>$value){
            if ($this->isComponentPlayableBySet($value['set'])){
                // all cards if not solo
                // in solo only cards with solo=1
                if ($solo==0 || $value['solo']==1){
                    $cards[] = array('type' =>'blueCard', 'type_arg' => $key, 'nbr' => 1);
                }
            }
        }
        shuffle($cards);
        $this->cards->createCards( $cards, DECK_BLUE);

        // Loop over automa cards
        if ($solo>0){
            $cards = array();
            foreach ($this->automaCards as $key=>$value){
                $cards[] = array('type' =>'automaCard', 'type_arg' => $key, 'nbr' => 1);
            }
            shuffle($cards);
            $this->cards->createCards( $cards, DECK_AUTOMA);

        }

        //Temporary worker (Grey)
        $workerTemporary = array(
            array('type' =>'worker_t', 'type_arg' => 0, 'nbr' => 1)
        );
        $this->cards->createCards( $workerTemporary, 'board',0);

        // Init game statistics
        $this->initStats();

        // Activate first player (which is in general a good idea :) )
        if ($solo == 0){
            $this->activeNextPlayer(); 
        } else {
            $this->gamestate->changeActivePlayer( $last_player_id );
        }

        /************ End of the game initialization *****/
    }

    public function initStats() {
        // INIT GAME STATISTIC
        $all_stats = $this->getStatTypes();
        $player_stats = $all_stats ['player'];
        // all my stats starts with vit_
        foreach ( $player_stats as $key => $value ) {
            if ($this->startsWith($key, 'vit_')) {
                $this->initStat('player', $key, 0);
            }
        }
        $table_stats = $all_stats ['table'];
        // all my stats starts with vit_
        foreach ( $table_stats as $key => $value ) {
            if ($this->startsWith($key, 'vit_')) {
                $this->initStat('table', $key, 0);
            }
        }

    }

    /*
        getAllDatas:

        Gather all informations about current game situation (visible by the current player).

        The method is called each time the game interface is displayed to a player, ie:
        _ when the game starts
        _ when a player refreshes the game page (F5)
    */
    protected function getAllDatas($playerId = '', $dataRedux = false)
    {
        $state = $this->gamestate->state();
        $players = $this->getPlayersFullData();

        $result = array();

        $current_player_id = $playerId;
        if ($current_player_id == ''){
            $current_player_id = $this->getCurrentPlayerId();    // !! We must only return informations visible by this player !!
            //spectator?
            if ($this->arrayFindByProperty($players,'id',$current_player_id)==null){
                $current_player_id = $this->getActivePlayerId();    // !! We must only return informations visible by this player !!            
            }
        }

        $result['playersNumber'] = self::getPlayersNumber();

        if ($result['playersNumber']==1){
            $result['soloMode'] = 1; //1 is solo with no automa, 2 with automa?
            //not setted
            if ($this->getSoloDifficulty()==0){
                self::setGameStateInitialValue( 'soloDifficulty', 3 );
                self::setGameStateInitialValue( 'soloAggressive', 0 );
            }
        } else {
            $result['soloMode'] = 0;
        }

        // Get information about players
        $result['players'] = $players;

        $result['set'] = self::getGameStateValue('set');
        $result['turn'] = self::getGameStateValue('turn');
        $result['season'] = self::getGameStateValue('season');
        $result['tokens'] = $this->readTokens();
        $result['pceg'] = $this->checkIfPlayersCauseEndGame($players, false);
        $result['gameEnd'] = self::getGameStateValue('game_end');

        $result['hand'] = $this->readPlayerHand($players[$current_player_id]);

        $result["cdc"] = $this->readCountDeckCards();
        $result["tdd"] = $this->readTopDiscardDeck();
        
        //read deck
        //DEBUG: only for debug
        //$sql = "SELECT * FROM card";
        //$result['_deck'] = self::getObjectListFromDB( $sql); //DEBUG

        if (!$dataRedux){
            $result["greenCards"] = $this->readCardsBySet($this->greenCards);
            $result["yellowCards"] = $this->readCardsBySet($this->yellowCards);
            $result["purpleCards"] = $this->readCardsBySet($this->purpleCards);
            $result["blueCards"] = $this->readCardsBySet($this->blueCards);
            $result["locations"] = $this->readLocationsBySetAndPlayers(true);
            $result["playerTokens"] = $this->readPlayerTokensBySet();
            $result["fields"] = $this->fields;
            $result["mamas"] = $this->readMamas();
            $result["papas"] = $this->readPapas();
            $result["history"] = $this->readHistory();

            //solo 
            $soloMode = $this->checkIfSoloMode();
            $result["soloMode"] = $soloMode;
            if ($soloMode>0){
                $result["soloDifficulty"] = $this->getSoloDifficulty();
                $result["soloAggressive"] = $this->getSoloAggressive();
                $result["soloParameters"] = $this->soloParameters[$this->getSoloDifficulty()];
                $result['automaPlayerData'] = $this->getAutomaPlayerData();
                $result["automaCards"] = $this->automaCards;
                $result["acs"] = $this->getAutomaCardsSeason();
            } else {
                $result["automaCards"]=Array();
                $result["acs"] =Array();
            }
        }

        return $result;
    }

    function getAutomaCardsSeason(){
        $result = array();

        $soloMode = $this->checkIfSoloMode();

        if ($soloMode > 0){
            $turn = self::getGameStateValue('turn');
            $season = self::getGameStateValue('season');
            $automaPlayerId = SOLO_PLAYER_ID;
            $locationLike = 'history\\_'.$turn.'\\_'.$season.'\\_%';
            $sql = "SELECT card_type_arg, card_location FROM card WHERE card_location like '$locationLike' and card_location_arg = $automaPlayerId order by card_id";
            $cards = self::getObjectListFromDB( $sql);
            foreach ($cards as $cardsKey => $cardsValue) {
                $result[]=$cardsValue['card_type_arg'];
            }
        }

        return $result;
    }

    function getPlayerFullData($playerId = 0){
        $playerData = $this->getPlayersFullData($playerId);
        $playerData = reset($playerData);
        return $playerData;
    }

    function getPlayersFullData($playerId = 0){
        $playersFullData = $this->getPlayersData($playerId);

        foreach ($playersFullData as $key => $value) {
            //logic
            $playersFullData[$key]['greenCard']=0;
            $playersFullData[$key]['yellowCard']=0;
            $playersFullData[$key]['purpleCard']=0;
            $playersFullData[$key]['blueCard']=0;
            $playersFullData[$key]['grapes']=array();
            $playersFullData[$key]['wines']=array();
            $playersFullData[$key]['vine1']=array();
            $playersFullData[$key]['vine2']=array();
            $playersFullData[$key]['vine3']=array();
            $playersFullData[$key]['vine1Tot']=0;
            $playersFullData[$key]['vine2Tot']=0;
            $playersFullData[$key]['vine3Tot']=0;
            $handLocation = HAND;
            $sql = "SELECT card_type, count(*) qty FROM card WHERE card_location_arg=$key and card_location='$handLocation' group by card_type";
            $cards = self::getObjectListFromDB( $sql);
            foreach ($cards as $cardsKey => $cardsValue) {
                $playersFullData[$key][$cardsValue['card_type']]=$cardsValue['qty'];
            }
            $playersFullData[$key]['handSize']=$playersFullData[$key]['greenCard']+$playersFullData[$key]['yellowCard']+$playersFullData[$key]['purpleCard']+$playersFullData[$key]['blueCard'];
            $playersFullData[$key]['mama']=0;
            $playersFullData[$key]['papa']=0;
        }

        //read grapes and wines and assign values to player data
        $sql = "SELECT card_id id, card_location_arg playerId, card_type, card_type_arg FROM card WHERE (card_type like 'grape%' or card_type like 'wine%') and (card_location_arg = $playerId or $playerId = 0) and card_location!='card_flags'";
        $grapesWines = self::getObjectListFromDB( $sql);
        foreach ($grapesWines as $grapesWinesKey => $grapesWinesValue) {
            if ($this->startsWith($grapesWinesValue['card_type'],'grape')){
                $playersFullData[$grapesWinesValue['playerId']]['grapes'][]=array('i'=>$grapesWinesValue['id'],'t'=>$grapesWinesValue['card_type'], "v"=>$grapesWinesValue['card_type_arg']);
            }
            if ($this->startsWith($grapesWinesValue['card_type'],'wine')){
                $playersFullData[$grapesWinesValue['playerId']]['wines'][]=array('i'=>$grapesWinesValue['id'],'t'=>$grapesWinesValue['card_type'], "v"=>$grapesWinesValue['card_type_arg']);
            }
        }

        //read green cards in vines
        $sql = "SELECT card_id id, card_location_arg playerId, card_type, card_type_arg, card_location FROM card WHERE card_location like 'vine%' and (card_location_arg = $playerId or $playerId = 0)";
        $greenCardsVine = self::getObjectListFromDB( $sql);
        foreach ($greenCardsVine as $greenCardsVineKey => $greenCardsVineValue) {
            $card = $this->greenCards[$greenCardsVineValue['card_type_arg']];
            $playersFullData[$greenCardsVineValue['playerId']][$greenCardsVineValue['card_location']][]=array("i"=>$greenCardsVineValue['id'],"k"=>$greenCardsVineValue['card_type_arg'], "r"=>$card['red'], "w"=>$card['white']);
            $playersFullData[$greenCardsVineValue['playerId']][$greenCardsVineValue['card_location'].'Tot']+=$card['red']+$card['white'];
        }

        //read mama and papa and assign values to player data mama and papa
        $sql = "SELECT card_location_arg playerId, card_type, card_type_arg FROM card WHERE card_location in ('mama','papa') and (card_location_arg = $playerId or $playerId = 0)";
        $mamasPapas = self::getObjectListFromDB( $sql);
        foreach ($mamasPapas as $mamasPapasKey => $mamasPapasValue) {
            $playersFullData[$mamasPapasValue['playerId']][$mamasPapasValue['card_type']]=$mamasPapasValue['card_type_arg'];
        }

        return $playersFullData;
    }

    function getPlayersFullDataWithSolo($playerId = 0){
        $players = $this->getPlayersFullData($playerId);

        if ($this->checkIfSoloMode()>0 && ($playerId ==0 || $playerId = SOLO_PLAYER_ID)){
            $players[SOLO_PLAYER_ID] = $this->getAutomaPlayerData();
        }

        return $players;
    }

    function getAutomaPlayerData(){
        $player = Array();
        $player['id']=SOLO_PLAYER_ID;
        $player['player_name']='Automa';
        $player['name']='Automa';
        $player['score']=$this->getAutomaScore();
        $player['player_color']=$this->getAutomaColor();
        $player['playorder']=2;
        $player['wakeup_chart']=0;
        $player['wakeup_order']=0;
        $player['lira']=0;
        $player['pass']=1;
        $player['residual_payment']=0;
        $player['field1']=1;
        $player['field2']=1;
        $player['field3']=1;
        $player['trellis']=0;
        $player['irrigation']=0;
        $player['yoke']=0;
        $player['tastingRoom']=0;
        $player['windmill']=0;
        $player['mediumCellar']=0;
        $player['largeCellar']=0;
        $player['cottage']=0;
        $player['tastingRoomUsed']=0;
        $player['windmillUsed']=0;
        $player['card_played']=0;
        $player['bonuses']=0;
        $player['greenCard']=0;
        $player['yellowCard']=0;
        $player['purpleCard']=0;
        $player['blueCard']=0;
        $player['grapes']=array();
        $player['wines']=array();
        $player['vine1']=array();
        $player['vine2']=array();
        $player['vine3']=array();
        $player['vine1Tot']=0;
        $player['vine2Tot']=0;
        $player['handSize']=0;
        $player['mama']=0;
        $player['papa']=0;
        return $player;
    }

    function getAutomaColor(){
        $colorCard = $this->readCardsByPlayerIdAndCardType(SOLO_PLAYER_ID,'color');
        return $colorCard[0]['location'];
    }

    function getAutomaScore(){
       $soloDifficulty = $this->getSoloDifficulty();
       $soloAggressive = $this->getSoloAggressive();
       $soloParameters = $this->soloParameters[$soloDifficulty];
       if ($soloAggressive){
           $turn = self::getGameStateValue('turn');
           if ($turn<1){
               $turn=1;
           }
           return $soloParameters['aggressive'][$turn-1];
       } else {
           return $soloParameters['targetScore'];
       }
    }

    function calculateProgression($playersData){

        $progression = 0;

        if ($this->checkIfSoloMode()==0){

            //read max score of players
            $maxScore =0;
            foreach ($playersData as $playerId => $playerData) {
                if ($playerData['score']>$maxScore){
                    $maxScore = $playerData['score'];
                }
            }
            if ($maxScore >= END_GAME_SCORING){
                $maxScore = END_GAME_SCORING;
            }

            $turn = self::getGameStateValue('turn');
            $season = self::getGameStateValue('season');
            $seasons = $turn*4+$season;

            //by year and seasons (0-30)
            if ($seasons<=40){
                $progression+=$seasons;
            } else{
                $progression+=40;;
            }
            //by score (0-50)
            if ($maxScore>0){
                $progression += (int) (50*$maxScore/END_GAME_SCORING);
            }
            //last turn add season (0-8)
            if ($maxScore >= END_GAME_SCORING){
                $progression += (int) (10*$season/5);
            }

        } else {
            $turn = self::getGameStateValue('turn');
            $soloParameters =  $this->soloParameters[$this->getSoloDifficulty()];
            $progression = (int) $turn/($soloParameters['turns']+1)*100;
        }


        if ($progression > 100){
            $progression = 100;
        }

        $this->setGameStateValue('progression', ( int ) $progression);
    }

    /*
        getGameProgression:

        Compute and return the current game progression.
        The number returned must be an integer beween 0 (=the game just started) and
        100 (= the game is finished or almost finished).

        This method is called each time we are in a game state with the "updateGameProgression" property set to true
        (see states.inc.php)
    */
    function getGameProgression()
    {
        $prog = $this->getGameStateValue('progression');
        return $prog;
    }


//////////////////////////////////////////////////////////////////////////////
//////////// Utility functions
////////////

    /*
        In this space, you can put any utility methods useful for your game logic
    */

    /**
     * friendly variant, check if player it's blocking the location, if so and there are other locations available, moves worker in non-bonus location
     */
    function manageFriendlyBlocking($playerId, $nextActionId, $action, $locationKey){
        //only in friendly variant
        if ($this->isFriendlyVariant()==false){
            return;
        }

        //no friendly in solo mode
        if ($this->checkIfSoloMode()>0){
            return;
        }
        //only if I have locationKey and it's valid one
        if ($locationKey==null||$locationKey==0){
            return;
        }
        if (!array_key_exists($locationKey, $this->boardLocations)){
            return;
        }
        $locationInfo = $this->boardLocations[$locationKey];
        if ($locationInfo['bonus']==null||$locationInfo['bonus']==''){
            return;
        }

        $tokens = $this->readTokens();

        //ok we are in friendly variant with a bonus location
        //I must check if other non-bonus locations are available
        $playersNumber = self::getPlayersNumber();
        $freeLocation = null;
        foreach ($this->boardLocations as $locationsKey => $locationsValue) {
            //other locations
            if ($locationsKey!=$locationKey && $locationsValue['bl']==$locationKey && $locationsValue['players'] <= $playersNumber){
                //check if not occupied
                $occupied=false;
                foreach ($tokens as $tokensPl => $tokensPlValues) {
                    foreach ($tokensPlValues as $tokensKey => $tokensValue) {
                        if ($locationsKey==901){
                            //yoke
                            if ($tokensValue['l']=='board_'.$locationsKey && $tokensPl==$playerId){
                                $occupied = true;
                            }
                        } else if ($tokensValue['l']=='board_'.$locationsKey){
                            $occupied = true;
                        }
                    }
                }
                if (!$occupied){
                    $freeLocation = $locationsKey;
                    break;
                }
            }
        }

        //if new location
        //if so... I move worker to that location, sending a notification
        if ($freeLocation != null){
            $oldBoardLocation = 'board_'.$locationKey;
            //check if worker in location (if you refuse on 2nd make wine of three... it moves on first refuse, and on the second it must do nothing)
            $sql = "
                SELECT count(*)
                FROM card
                WHERE card_type like 'worker_%' and card_location like '$oldBoardLocation%' and card_location_arg=$playerId
            ";
            $found = self::getUniqueValueFromDB( $sql );

            if ($found==0){
                return false;
            }

            //move worker
            $newBoardLocation = 'board_'.$freeLocation;
            $this->DbQuery("UPDATE card SET card_location='$newBoardLocation' where card_type like 'worker_%' and card_location like '$oldBoardLocation%' and card_location_arg=$playerId");
           
            // Notify all players
            self::notifyAllPlayers( "manageFriendlyBlocking", clienttranslate( 'Moved worker of ${player_name} to non bonus location because they didn\'t use all actions of bonus location in friendly variant' ), array(
                'player_id' => $playerId,
                'player_name' => $this->getPlayerName($playerId)
            ) );
        }

    }

    /*
        getPlayersData:
    */
    function getPlayersData($playerId = 0) {

        $sql = "
            SELECT
                player_id id, player_name, player_score score, player_color, playorder, wakeup_chart, wakeup_order,
                lira, pass, residual_payment,
                field1, field2, field3,
                trellis, irrigation, yoke, tastingRoom, windmill, mediumCellar, largeCellar, cottage,
                tastingRoomUsed, windmillUsed,
                card_played, bonuses
            FROM player p
            WHERE player_id = $playerId or $playerId = 0
            ORDER BY p.playorder
        ";
        $players = self::getCollectionFromDB( $sql );

        return $players;
    }

    /*
    getPlayersDataWithSolo:
    */
    function getPlayersDataWithSolo($playerId = 0) {

        $players = $this->getPlayersData($playerId);
        if ($this->checkIfSoloMode()>0 && ($playerId == 0 || $playerId == SOLO_PLAYER_ID)){
            $players[SOLO_PLAYER_ID] = $this->getAutomaPlayerData();
        }

        return $players;
    }

    /*
        getPlayerData:
    */
    function getPlayerData($playerId) {

        $sql = "
            SELECT
                player_id id, player_name, player_score score, player_color, playorder, wakeup_chart, wakeup_order,
                lira, pass, residual_payment,
                field1, field2, field3,
                trellis, irrigation, yoke, tastingRoom, windmill, mediumCellar, largeCellar, cottage,
                tastingRoomUsed, windmillUsed,
                card_played, bonuses
            FROM player p
            WHERE player_id = $playerId
        ";
        $playerData = self::getObjectFromDb( $sql );

        return $playerData;
    }

    /*
        getPlayerName:
    */
    function getPlayerName($playerId) {

        if ($playerId == SOLO_PLAYER_ID){
            return 'Automa';
        }

        $sql = "
            SELECT player_name
            FROM player p
            WHERE player_id = $playerId
        ";
        $playerData = self::getObjectFromDb( $sql );

        return $playerData['player_name'];
    }

    function isComponentPlayableBySet($set){
        if ($set == SET_BASE){
            return true;
        }
        return false;
    }

    function getSeasonText($season){
        return $this->seasons[$season];
    }

    function getCardVpPriceBothActions($cardKey){
        switch ($cardKey) {
            case 628: //Homesteader
            case 634: //Sponsor
            case 823: //Scholar
            case 829: //Laborer
                return 1;
        }
        return 0;
    }

    function getCardBuildDiscount($cardKey){
        switch ($cardKey) {
            case 618: //Handyman
                return 2;
        }
        return 0;
    }

    function enrichPrivateHandGreenCards(&$privateData, $playerId, $possibleCards){
        foreach ($possibleCards as $possibleCardsKey => $possibleCardsValue) {
            foreach ($privateData[$playerId]['hand'] as $cardKey => $cardValue) {
                if ($privateData[$playerId]['hand'][$cardKey]['i']==$possibleCardsValue){
                    $privateData[$playerId]['hand'][$cardKey]['c']=1;
                }
            }
        }
    }

    function enrichPrivateHandYellowCards(&$privateData, $playerId, $possibleCards){
        foreach ($possibleCards as $possibleCardsKey => $possibleCardsValue) {
            foreach ($privateData[$playerId]['hand'] as $cardKey => $cardValue) {
                if ($privateData[$playerId]['hand'][$cardKey]['i']==$possibleCardsValue['i']){
                    $privateData[$playerId]['hand'][$cardKey]['c']=$possibleCardsValue['c'];
                }
            }
        }
    }

    function enrichPrivateHandBlueCards(&$privateData, $playerId, $possibleCards){
        foreach ($possibleCards as $possibleCardsKey => $possibleCardsValue) {
            foreach ($privateData[$playerId]['hand'] as $cardKey => $cardValue) {
                if ($privateData[$playerId]['hand'][$cardKey]['i']==$possibleCardsValue['i']){
                    $privateData[$playerId]['hand'][$cardKey]['c']=$possibleCardsValue['c'];
                }
            }
        }
    }
    
    function enrichPrivateHandPurpleCards(&$privateData, $playerId, $possibleCards){
        foreach ($possibleCards as $possibleCardsKey => $possibleCardsValue) {
            foreach ($privateData[$playerId]['hand'] as $cardKey => $cardValue) {
                if ($privateData[$playerId]['hand'][$cardKey]['i']==$possibleCardsValue){
                    $privateData[$playerId]['hand'][$cardKey]['c']=1;
                }
            }
        }
    }


    /**
     * executes occupied location
     */
    function executePlayerOccupiedLocation($playersDataKey, $locationKey){

        $location = $this->boardLocations[$locationKey];

        //interactive location
        if ($location['int']==1){
            //player action to execute interaction
            $this->insertPlayerAction($playersDataKey, 'executeLocation', 0, $locationKey);
            
        } else {

            //execute non interactive action
            $playersData = $this->getPlayersFullData();
            $tokens = $this->readTokens();
            $privateHandCards = $this->readPlayersPrivateHand($playersData);

            //first check if it's possible
            //(bug #41458: "I'M MID SEASON WHERE THE PICK SUMMER OR WINTER (YELLOW OR BLUE) CARD IS REQUIRED.")
            //player played planner on train worker with bonus (cost 4-1=3£) but they have only 2£

            $checkSeason = self::getGameStateValue('season');
            $active = $this->checkLocationPlayable($playersDataKey, $playersData, $tokens, $privateHandCards, 
                                                $location, true);

            if ($active==true){

                $this->executeLocationActionInternal( $playersDataKey, $locationKey, '', 0, 0,
                0, array(), '', 0, array(), 0, 0, array(),
                array(), array(), array(), array(), '',
                0, 0, 0, 
                $playersData, $tokens, $privateHandCards, true);

            } else {

                // Notify all players
                self::notifyAllPlayers( "action", clienttranslate( '${player_name} worker placed in previous season cannot do the selected action: ${action}' ), array(
                    'player_id' => $playersDataKey,
                    'player_name' => $this->getPlayerName($playersDataKey),
                    'action' => $this->getLocationActionDescription($location),
                    'i18n' => array('action' )
                ) );

            }
            

        }

    }

    /**
     * checks if a location action is playable
     * returns
     * false: not playable
     * true: playable
     */
    function checkLocationPlayable($playerId, $playersFullData, $tokens, $privateHandCards, $location, $lastFree){

        $playerFullData = $playersFullData[$playerId];

        $result = false;

        $actions = $location['action'];
        if ($location['bonus']!=''){
            $actions = $actions.'+'.$location['bonus'];
        }

        switch ($actions) {
            case 'playYellowCard_1':
                $result = $this->checkActionCardPlayability($playerId, 'yellowCard', $playerFullData, $privateHandCards, $tokens, $lastFree, false, $playersFullData,0);
                break;
            case 'playYellowCard_1+playYellowCard_1':
                $result = $this->checkActionCardPlayability($playerId, 'yellowCard', $playerFullData, $privateHandCards, $tokens, $lastFree, true, $playersFullData,0);
                break;
            case 'buildStructure_1':
                $result = $this->checkActionBuildPlayability($playerId, $playerFullData, $tokens, 0);
                break;
            case 'buildStructure_1+getDiscountLira1':
                $result = $this->checkActionBuildPlayability($playerId, $playerFullData, $tokens, 1);
                break;
            case 'sellGrapes_1|buySellVine_1':
            case 'sellGrapes_1|buySellVine_1+getVp_1':
                //one or more grapes or one or more wines
                if (count($playerFullData['grapes'])>0){
                    $result = 1;
                } else if ($playerFullData['field1']==0||$playerFullData['field2']==0||$playerFullData['field3']==0){
                    //one or more vine field sold
                    $result = 1;
                } else if (count($playerFullData['vine1'])==0||count($playerFullData['vine2'])==0||count($playerFullData['vine3'])==0){
                    // one empty vine
                    $result = 1;
                }
                break;
            case 'plant_1':
                $result = $this->checkActionCardPlayability($playerId, 'greenCard', $playerFullData, $privateHandCards, $tokens, $lastFree, false, $playersFullData,0);
                break;
            case 'plant_1+plant_1':
                $result = $this->checkActionCardPlayability($playerId, 'greenCard', $playerFullData, $privateHandCards, $tokens, $lastFree, true, $playersFullData,0);
                break;
            case 'harvestField_1':
                $result = $this->checkActionHarvest($playerId, $playerFullData, $tokens, $lastFree, false);
                break;
            case 'harvestField_1+harvestField_1':
                $result = $this->checkActionHarvest($playerId, $playerFullData, $tokens, $lastFree, true);
                break;
            case 'trainWorker_1':
                $result = $this->checkActionTrainWorker($playerId, $playerFullData, $tokens, 0);
                break;
            case 'trainWorker_1+getDiscountLira1':
                $result = $this->checkActionTrainWorker($playerId, $playerFullData, $tokens, 1);
                break;
            case 'fillOrder_1':
            case 'fillOrder_1+getVp_1':
                $result = $this->checkActionCardPlayability($playerId, 'purpleCard', $playerFullData, $privateHandCards, $tokens, $lastFree, false, $playersFullData,0);
                break;
            case 'makeWine_2':
                $result = $this->checkActionMakeWine($playerId, $playerFullData, $privateHandCards, $tokens, $lastFree, false, 1);
                break;
            case 'makeWine_2+makeWine_1':
                $result = $this->checkActionMakeWine($playerId, $playerFullData, $privateHandCards, $tokens, $lastFree, true, 1);
                break;
            case 'playBlueCard_1':
                $result = $this->checkActionCardPlayability($playerId, 'blueCard', $playerFullData, $privateHandCards, $tokens, $lastFree, false, $playersFullData,0);
                break;
            case 'playBlueCard_1+playBlueCard_1':
                $result = $this->checkActionCardPlayability($playerId, 'blueCard', $playerFullData, $privateHandCards, $tokens, $lastFree, true, $playersFullData,0);
                break;


            case 'drawGreenCard_1':
            case 'drawGreenCard_1+drawGreenCard_1':
            case 'getLira_2':
            case 'getLira_2+getLira_1':
            case 'drawPurpleCard_1':
            case 'drawPurpleCard_1+drawPurpleCard_1':
            case 'getLira_1':
                //no problem
                $result = true;
                break;

            case 'uproot_1|harvestField_1':
                if ($playerFullData['yoke']==1){
                    //can uproot a vine
                    $result = $this->checkActionUproot($playerId, $playerFullData);
                    if (!$result){
                        //can harvest a field
                        $result = $this->checkActionHarvest($playerId, $playerFullData, $tokens, $lastFree, false);

                    }
                }
                break;

            default:
                throw new BgaUserException( self::_("Action not valid!").$actions );
                break;
        }

        return $result;
    }

    function activateNextAction($playerId, $nextAction){
        $players = $this->getPlayersFullData();
        $playerFullData = $players[$playerId];
        $actionMakeable = true;

        if ($nextAction['action']=='makeWine'){
            $minimumWineValue = 1;
            $checkStructures = true;
            //833: //Zymologist
            //Make up to 2 ${token_wineAny} of value 4 or greater, even if you haven't upgraded your cellar.
            //makeWine_2_value4withouthmediumcellar
            if ($nextAction['card_key']==833){
                $minimumWineValue = 4;
                $checkStructures = false;
            }

            $possibleWines=$this->readPossibleWineMakeable($playerId, $playerFullData, $checkStructures, $minimumWineValue );
            if (count($possibleWines) == 0){
                $actionMakeable = false;
                $this->manageFriendlyBlocking($playerId,$nextAction['id'], $nextAction['action'], $nextAction['args']);
            }
        }

        if ($nextAction['action']=='playYellowCard'){
            $playersData = $this->getPlayersFullData();
            $tokens = $this->readTokens();
            $playersPrivateHand = $this->readPlayersPrivateHand($playersData);
            $handCard = $playersPrivateHand[$playerId]['hand'];
            if ($this->checkActionCardPlayability($playerId, 'yellowCard', $playersData[$playerId], $playersPrivateHand, $tokens, true, false, $playersData, 0)<=0){
                $actionMakeable = false;
                $this->manageFriendlyBlocking($playerId, $nextAction['id'], $nextAction['action'], $nextAction['args']);
            }
        }

        if ($nextAction['action']=='playBlueCard'){
            $playersData = $this->getPlayersFullData();
            $tokens = $this->readTokens();
            $playersPrivateHand = $this->readPlayersPrivateHand($playersData);
            $handCard = $playersPrivateHand[$playerId]['hand'];
            if ($this->checkActionCardPlayability($playerId, 'blueCard', $playersData[$playerId], $playersPrivateHand, $tokens, true, false, $playersData, 0)<=0){
                $actionMakeable = false;
                $this->manageFriendlyBlocking($playerId, $nextAction['id'], $nextAction['action'], $nextAction['args']);
            }
        }

        switch ($nextAction['action']) {
            case 'allBuild':
                $discount = $this->getCardBuildDiscount($nextAction['card_key']);
                $tokens = $this->readTokens();
                $playersActive = array();
                foreach ($players as $playersKey => $playersValue) {
                    if ($playersKey!=$playerId){
                        if ($this->checkActionBuildPlayability($playersKey, $playersValue, $tokens, $discount)>0){
                            $playersActive[] = $playersKey;
                        }
                    }
                }
                if (count($playersActive)>0){
                    $this->gamestate->setPlayersMultiactive( $playersActive, 'next', true );
                    $this->gamestate->nextState( $nextAction['action'] );
                } else {
                    $this->removePlayerAction($nextAction['id']);
                    return false;
                }
                break;

            case 'allPlant':
                $tokens = $this->readTokens();
                $playersActive = array();
                $privateHandCards = $this->readPlayersPrivateHand($players);
                foreach ($players as $playersKey => $playersValue) {
                    if ($playersKey!=$playerId){
                        $possibleGreenCards = $this->readPossibleGreenCards($playersKey, $playersValue, $tokens, $privateHandCards, true, true);
                        if (count($possibleGreenCards)>0){
                            $playersActive[] = $playersKey;
                        }
                    }
                }
                if (count($playersActive)>0){
                    $this->gamestate->setPlayersMultiactive( $playersActive, 'next', true );
                    $this->gamestate->nextState( $nextAction['action'] );
                } else {
                    $this->removePlayerAction($nextAction['id']);
                    return false;
                }
                break;

            case 'allGiveCard':
                $playersId = explode('_',$nextAction['args']);
                if (count($playersId)>0){
                    $this->gamestate->setPlayersMultiactive( $playersId, 'next', true );
                    $this->gamestate->nextState( $nextAction['action'] );
                } else {
                    $this->removePlayerAction($nextAction['id']);
                    return false;
                }
                break;

            case 'allChoose':
                $tokens = $this->readTokens();
                $playersActive = array();
                foreach ($players as $playersKey => $playersValue) {
                    if ($playersKey!=$playerId){
                        //621: Banker
                        if ($nextAction['card_key']==621){
                            $score = (int) $playersValue['score'];
                            if ($score>-5){
                                $playersActive[] = $playersKey;
                            }
                        }

                        //631: Swingler
                        if ($nextAction['card_key']==631){
                            $lira = (int) $playersValue['lira'];
                            if ($lira>=2){
                                $playersActive[] = $playersKey;
                            } else {
                                $this->dbIncScore($nextAction['player_id'], 1, 'vit_scoring_yellow_card');
                                // Notify all players
                                self::notifyAllPlayers( "refuse", clienttranslate( '${player_name} gets ${token_get}' ), array(
                                    'player_id' => $nextAction['player_id'],
                                    'player_name' => $this->getPlayerName($nextAction['player_id']),
                                    'token_get' => 'vp1'
                                ) );
                            }
                        }

                        //825: Motivator
                        if ($nextAction['card_key']==825 && $playersValue['pass']==0){
                            $worker = $this->readCardsByPlayerIdAndCardType($playersKey, 'worker_g');
                            if ($worker[0]['location']!='player'){
                                $playersActive[] = $playersKey;
                            }
                        }

                        //838: Guest Speaker
                        if ($nextAction['card_key']==838){
                            $availableWorkers = $this->readAvailableNewWorkers($playersKey);
                            $lira = (int) $playersValue['lira'];
                            if (count($availableWorkers)>0 && $lira>0){
                                $playersActive[] = $playersKey;
                            }
                        }

                    }
                }
                if (count($playersActive)>0){
                    $this->gamestate->setPlayersMultiactive( $playersActive, 'next', true );
                    $this->gamestate->nextState( $nextAction['action'] );
                } else {
                    $this->removePlayerAction($nextAction['id']);
                    return false;
                }
                break;

            default:
                if ($actionMakeable){
                    if (self::getActivePlayerId()!=$playerId){
                        $this->gamestate->changeActivePlayer( $playerId );
                        self::giveExtraTime( $playerId );
                    }

                    $this->gamestate->nextState( $nextAction['action'] );

                    return true;
                } else {
                    $this->removePlayerAction($nextAction['id']);
                    return false;
                }

                break;
        }
        return true;
    }

    function getLocationActionDescription($location){
        return $location['des'];
    }

    function getLocationSeasons(){
        //TO BE CHANGED WITH TUSCANY
        return array(SUMMER, WINTER);
    }

    function discardCardOnDeckTop($cardId, $cardKey){
        $cardType=$this->getCardType($cardKey);
        if ($cardType=='greenCard'){
            $discardDeck=DISCARD_GREEN;
        } else if ($cardType=='yellowCard'){
            $discardDeck=DISCARD_YELLOW;
        } else if ($cardType=='blueCard'){
            $discardDeck=DISCARD_BLUE;
        } else if ($cardType=='purpleCard'){
            $discardDeck=DISCARD_PURPLE;
        } else if ($cardType=='automaCard'){
            $discardDeck=DISCARD_AUTOMA;
        }
        //discard on top
        $this->cards->insertCardOnExtremePosition($cardId, $discardDeck, true);
    }

    function getCardType($cardKey){
        if (array_key_exists($cardKey, $this->yellowCards)){
            return 'yellowCard';
        }
        if (array_key_exists($cardKey, $this->blueCards)){
            return 'blueCard';
        }
        if (array_key_exists($cardKey, $this->greenCards)){
            return 'greenCard';
        }
        if (array_key_exists($cardKey, $this->purpleCards)){
            return 'purpleCard';
        }
        if (array_key_exists($cardKey, $this->automaCards)){
            return 'automaCard';
        }
    }

    function readCountDeckCards(){
        $result = array();
        $sql = "
            SELECT
                card_location location,
                count(*) num
            FROM card c
            WHERE card_location in ('deckGreen','deckYellow','deckPurple','deckBlue',
                                    'discardGreen','discardYellow','discardPurple','discardBlue')
            GROUP by card_location
            ORDER BY 1
        ";
        $counts = self::getObjectListFromDB( $sql );
        foreach ($counts as $countsKey => $countsValue) {
            $result[$countsValue['location']]=$countsValue['num'];
        }
        foreach ($this->decks as $decksKey => $decksValue) {
            if(!array_key_exists($decksKey, $result)){
                $result[$decksKey]=0;
            }
            if(!array_key_exists($decksValue['discard'], $result)){
                $result[$decksValue['discard']]=0;
            }
        }

        return $result;
    }

    function readTopDiscardDeck(){
        $result = array();
        foreach ($this->decks as $deck => $deckValue) {
            $card = $this->cards->getCardOnTop($deckValue['discard']);
            if ($card){
                $result[$deck] = array();
                $result[$deck]['i']=$card['id'];
                $result[$deck]['k']=$card['type_arg'];
            }
        }
        return $result;
    }

    /**
     * returns active wakeup order (slots not yet chosen)
     */
    function readActiveWakeupOrder(){
        $result = array();
        $playersData = $this->getPlayersData();
        $soloMode = $this->checkIfSoloMode();
        
        //in solo mode, only wakeup with bonus marker
        if ($soloMode>0){
            $bonuses = $this->readCardsByPlayerIdAndCardType(0,'wakeup_bonus');
        }
        
        for ($i=1; $i <= 7; $i++) {
            $found = false;
            foreach ($playersData as $playerId => $playerData) {
                if ($playerData['wakeup_chart']==$i){
                    $found = true;
                    break;
                }
            }
            
            if ($soloMode>0){
                $foundBonus = false;
                foreach ($bonuses as $bonusesKey => $bonusesValue) {
                    if ($bonusesValue['type_arg']==$i){
                        $foundBonus=true;
                        break;
                    }
                }
                if (!$foundBonus){
                    //simulating player occupation of wakeup to disable it
                    $found = true;
                }
            }

            if (!$found){
                $result[] = $i;
            }
        }


        return $result;

    }

    /**
     * check if action in progress with card
     */
    function checkPlayerActionCardInProgress($cardKey){
        $statusInProgress = STATUS_IN_PROGRESS;
        $sql = "
            SELECT player_action_id id, player_id, action, args, card_id, card_key, status
            FROM player_action
            WHERE card_key = $cardKey
            AND status = $statusInProgress
            ORDER BY play_order, player_action_id
            LIMIT 1
        ";
        $action = self::getObjectFromDb( $sql );
        if ($action==null){
            return null;
        }

        return array('id' => $action['id'], 'player_id' => $action['player_id'], 'action' => $action['action'], 'args' => $action['args'],
            'card_id' => $action['card_id'],'card_key' => $action['card_key'],'status' => $action['status']);
    }

    /**
     * reads player action
     */
    function readPlayerAction($playerId, $status){
        $sql = "
            SELECT player_action_id id, player_id, play_order, action, args, card_id, card_key, status
            FROM player_action
            WHERE (player_id = $playerId or $playerId = 0)
            AND   status = $status
            ORDER BY play_order, player_action_id
            LIMIT 1
        ";
        $action = self::getObjectFromDb( $sql );
        if ($action==null){
            return null;
        }

        return array('id' => $action['id'], 'player_id'=> $action['player_id'],'action' => $action['action'],'play_order' => $action['play_order'], 'args' => $action['args'],
            'card_id' => $action['card_id'],'card_key' => $action['card_key'],'status' => $action['status']);
    }

    /**
     * reads player action in progress
     */
    function readPlayerActionInProgress(){
        $status = STATUS_IN_PROGRESS;
        $sql = "
            SELECT player_action_id id, player_id, action, args, card_id, card_key, status
            FROM player_action
            WHERE status = $status
            ORDER BY play_order, player_action_id
            LIMIT 1
        ";
        $action = self::getObjectFromDb( $sql );
        if ($action==null){
            return null;
        }

        return array('id' => $action['id'], 'player_id' => $action['player_id'], 'action' => $action['action'], 'args' => $action['args'],
            'card_id' => $action['card_id'],'card_key' => $action['card_key'],'status' => $action['status']);
    }

    /**
     * reads player action by playerId, action and status
     */
    function readPlayerActionByAction($playerId, $action, $status){
        $sql = "
            SELECT player_action_id id, player_id, action, args, card_id, card_key, status
            FROM player_action
            WHERE (player_id = $playerId or $playerId = 0)
            AND   status = $status
            ORDER BY play_order, player_action_id
            LIMIT 1
        ";
        $action = self::getObjectFromDb( $sql );
        if ($action==null){
            return null;
        }

        return array('id' => $action['id'], 'player_id' => $action['player_id'], 'action' => $action['action'], 'args' => $action['args'],
            'card_id' => $action['card_id'],'card_key' => $action['card_key'],'status' => $action['status']);
    }

    /**
    * update player action status
    */
    function changeStatusPlayerAction($id, $status){
        $sql = "
            UPDATE player_action
            SET status = $status
            WHERE player_action_id = $id
        ";

        return self::DbQuery($sql);
    }

    /**
    * checks if exist player actions in status new for this card
    */
    function checkPlayerActionStatusNewWithCard($cardId){
        $status = STATUS_NEW;
        $sql = "
            SELECT count(*)
            FROM player_action
            WHERE card_id = $cardId
            AND   status = $status
        ";
        $actions = self::getUniqueValueFromDB( $sql );
        if ($actions==0){
            return false;
        }
        return true;
    }

    /**
    * removes player action
    */
    function removePlayerAction($id, $discardPlayedCard=true){

        //if card played and no more actions belong to this card, then discard it
        $sql = "
            SELECT card_id, card_key
            FROM player_action
            WHERE player_action_id = $id ";
        $action = self::getObjectFromDb( $sql );
        if ($action!=null && $action['card_id']>0 && $discardPlayedCard){
            if ($this->checkPlayerActionStatusNewWithCard($action['card_id'])==false){
                $this->discardCardOnDeckTop($action['card_id'], $action['card_key']);
            }
        }

        $sql = "
            DELETE FROM player_action
            WHERE player_action_id = $id
        ";

        return self::DbQuery($sql);
    }

    function addCardPlayedToHistory($playerId, $cardId, $cardKey){
        $cards = array();
        $cardType = $this->getCardType($cardKey);
        $cards[] = array('type' => $cardType, 'type_arg' => $cardKey, 'nbr' => 1);

        $turn = self::getGameStateValue('turn');
        $season = self::getGameStateValue('season');
        $moveNumber = $this->readCurrentMoveNumber();

        $this->cards->createCards( $cards, 'history_'.$turn.'_'.$season.'_'.$moveNumber, $playerId );
        self::notifyAllPlayers( "addCardPlayedToHistory", '', array(
            'card' => $playerId.'_'.$cardKey.'_'.$turn.'_'.$season.'_'.$moveNumber
        ) );
    }

    function readCurrentMoveNumber(){
        $nextMoveNumber = self::getUniqueValueFromDB("SELECT global_value value FROM global WHERE global_id = 3"); 
        if ($nextMoveNumber==null){
            return 0;
        }
        return $nextMoveNumber-1;
    }

    function insertPlayerAction($playerId, $action, $playOrder, $args, $cardId=null, $cardKey=null, $status=STATUS_NEW){
        // Create next player actions
        $cardIdNullValue = $cardId;
        if ($cardIdNullValue==null || $cardIdNullValue==''){
            $cardIdNullValue = 'null';
        }
        $cardKeyNullValue = $cardKey;
        if ($cardKeyNullValue==null || $cardKeyNullValue==''){
            $cardKeyNullValue = 'null';
        }

        $sql = "INSERT INTO player_action (player_id, action, play_order, args, card_id, card_key, status) VALUES ($playerId, '$action', $playOrder, '$args', $cardIdNullValue, $cardKeyNullValue, $status)";

        self::DbQuery( $sql );
    }

    /**
     * checks and remove player action in progress
     */
    function checkAndRemovePlayerActionInProgress($playerId, $action, $discardPlayedCard){
        $playerAction = $this->checkPlayerActionInProgress($playerId, $action, $discardPlayedCard);

        $this->removePlayerAction( $playerAction['id'], $discardPlayedCard);
    }

    /**
     * checks and remove player action in progress
     */
    function checkPlayerActionInProgress($playerId, $action){
        $playerAction = $this->readPlayerAction($playerId, STATUS_IN_PROGRESS);

        if ($playerAction==null || $playerAction['action']!=$action){
            throw new BgaUserException( self::_("Action not valid!") );
            return;
        }

        return $playerAction;
    }

    function readCardsBySet($cards){
        $result = array();
        foreach ($cards as $key => $value) {
            if ($this->isComponentPlayableBySet($value['set'])){
                $result[$key] = $value;
            }
        }
        return $result;
    }

    function isFriendlyVariant(){

        if (self::getGameStateValue('friendly_variant')==2){
            return true;
        } else {
            return false;
        }
    }

    function isMamaPapaChoice(){

        if (self::getGameStateValue('mama_papa_choice')==2){
            return true;
        } else {
            return false;
        }
    }

    function drawFromDeck($playerId, $deck, $nbr, $notify, $destination=HAND){
        $result = Array();
        for ($i=0; $i < $nbr; $i++) {
            $card = $this->cards->pickCardForLocation($deck, $destination, $playerId);
            if ($card == null){
                $discardDeck = $this->decks[$deck]['discard'];
                $cardType = $this->decks[$deck]['cardType'];
                $this->cards->moveAllCardsInLocation($discardDeck, $deck);
                $this->cards->shuffle($deck);
                self::notifyAllPlayers( "drawFromDeck", clienttranslate( 'Reshuffled discards in ${token_card} deck' ), array(
                    'token_card' => $cardType
                ) );
                $card = $this->cards->pickCardForLocation($deck, $destination, $playerId);
            }
            if ($card != null){
                $result[]=$card;
            }
        }

        //notifications
        if ($notify){
            $notificationText = '';
            switch ($deck) {
                case DECK_GREEN:
                    if (count($result)==0){
                        $notificationText = clienttranslate( '${player_name} wants to draw a vine card ${token_greenCard} but there are no more cards available' );
                    } else {
                        $notificationText = clienttranslate( '${player_name} draws ${number} vine card(s) ${token_greenCard}' );
                    }
                    break;

                case DECK_YELLOW:
                    if (count($result)==0){
                        $notificationText = clienttranslate( '${player_name} wants to draw a summer visitor card ${token_yellowCard} but there are no more cards available' );
                    } else {
                        $notificationText = clienttranslate( '${player_name} draws ${number} summer visitor card(s) ${token_yellowCard}' );
                    }
                    break;

                case DECK_BLUE:
                    if (count($result)==0){
                        $notificationText = clienttranslate( '${player_name} wants to draw a winter visitor card ${token_blueCard} but there are no more cards available' );
                    } else {
                        $notificationText = clienttranslate( '${player_name} draws ${number} winter visitor card(s) ${token_blueCard}' );
                    }
                    break;

                case DECK_PURPLE:
                    if (count($result)==0){
                        $notificationText = clienttranslate( '${player_name} wants to draw a wine order card ${token_purpleCard} but there are no more cards available' );
                    } else {
                        $notificationText = clienttranslate( '${player_name} draws ${number} wine order card(s) ${token_purpleCard}' );
                    }
                    break;
                    
                case DECK_AUTOMA:
                    if (count($result)==0){
                        $notificationText = clienttranslate( '${player_name} wants to draw an automa card but there are no more cards available' );
                    } else {
                        $notificationText = clienttranslate( '${player_name} draws ${number} automa card/s' );
                    }
                    break;

            }

            // Notify all players
            self::notifyAllPlayers( "drawFromDeck", $notificationText, array(
                'player_id' => $playerId,
                'player_name' => $this->getPlayerName($playerId),
                'number' => count($result),
                'token_yellowCard' => 'yellowCard',
                'token_blueCard' => 'blueCard',
                'token_purpleCard' => 'purpleCard',
                'token_greenCard' => 'greenCard'
            ) );
        }

        return $result;
    }

    function applyPapaEffect($playerId, $papaName, $effect){
        $tokens = explode('_', $effect);
        if (count($tokens)==1){
            $tokens[]=1;
        } else {
            $tokens[1]=(int) $tokens[1];
        }

        switch ($tokens[0]) {
            case 'vp1':
                $this->dbIncScore($playerId, $tokens[1],'');
                $notificationText = clienttranslate( '${player_name} chooses papa ${papaName} option and gets a vp ${token_vp1}' );
                // Notify all players
                self::notifyAllPlayers( "applyPapaEffect", $notificationText, array(
                    'player_id' => $playerId,
                    'player_name' => $this->getPlayerName($playerId),
                    'token_vp1' => 'vp1',
                    'papaName' => $papaName,
                    'i18n' => array('papaName' )
                ) );
                break;

            case 'lira':
                $this->dbIncLira($playerId, $tokens[1]);
                $notificationText = clienttranslate( '${player_name} chooses papa ${papaName} option and gets ${lira}${token_lira}' );
                // Notify all players
                self::notifyAllPlayers( "applyPapaEffect", $notificationText, array(
                    'player_id' => $playerId,
                    'player_name' => $this->getPlayerName($playerId),
                    'lira' => $tokens[1],
                    'token_lira' => 'lira',
                    'papaName' => $papaName,
                    'i18n' => array('papaName' )
                ) );
                break;

            case 'worker':
                $this->addWorker($playerId ,'player', 0, false);
                // Notify all players
                $notificationText = clienttranslate( '${player_name} chooses papa ${papaName} option and gets a worker ${token_worker}' );
                self::notifyAllPlayers( "applyPapaEffect", $notificationText, array(
                    'player_id' => $playerId,
                    'player_name' => $this->getPlayerName($playerId),
                    'token_worker' => 'worker',
                    'papaName' => $papaName,
                    'i18n' => array('papaName' )
                ) );
                break;

            case 'greenCard':
                $this->drawFromDeck($playerId, DECK_GREEN, $tokens[1], true);
                break;

            case 'yellowCard':
                $this->drawFromDeck($playerId, DECK_YELLOW, $tokens[1], true);
                break;

            case 'purpleCard':
                $this->drawFromDeck($playerId, DECK_PURPLE, $tokens[1], true);
                break;

            case 'blueCard':
                $this->drawFromDeck($playerId, DECK_BLUE, $tokens[1], true);
                break;

            case 'trellis':
            case 'irrigation':
            case 'yoke':
            case 'cottage':
            case 'windmill':
            case 'tastingRoom':
            case 'mediumCellar':
            case 'largeCellar':
                $this->placeBuilding($playerId,  $tokens[0], 0, true);
                break;

            default:
                throw new BgaUserException( self::_("Effect not valid!").$tokens[0] );

        }
    }

    function addWorker($playerId, $location, $price, $notify){
        $playerData = $this->getPlayerData($playerId);

        $availableNewWorkers = $this->readAvailableNewWorkers($playerId);
        if (count( $availableNewWorkers)==0){
            throw new BgaUserException( self::_("No more workers!") );
        }

        if ($price>0){
            if ($playerData['lira']<$price){
                throw new BgaUserException( self::_('You don\'t have enough lira (needed:${price})!') );
            }
            $this->dbIncLira($playerId,-$price);
        }

        $this->cards->moveCard( $availableNewWorkers[0]['id'], $location, $playerId );

        if ($notify){
            if ($price==0){
                // Notify all players
                self::notifyAllPlayers( "addWorker", clienttranslate( '${player_name} trains a new worker' ), array(
                    'player_id' => $playerId,
                    'player_name' => $this->getPlayerName($playerId),
                    'price' => $price
                ) );
            } else {
                // Notify all players
                self::notifyAllPlayers( "addWorker", clienttranslate( '${player_name} trains a new worker for ${token_lira}' ), array(
                    'player_id' => $playerId,
                    'player_name' => $this->getPlayerName($playerId),
                    'price' => $price,
                    'token_lira' => 'lira'.$price
                ) );
            }

        }
    }

    function readAvailableWorkers($playerId){
        //read available workers (not placed)
        $sql = "SELECT card_id id, card_type type FROM card WHERE card_location = 'player' and card_location_arg = $playerId and card_type like 'worker%' order by 1";
        return self::getObjectListFromDB( $sql);
    }

    function readAvailableNewWorkers($playerId){
        //read available new workers (train action)
        $sql = "SELECT card_id id, card_type type FROM card WHERE card_location = 'playerOff' and card_location_arg = $playerId and card_type like 'worker%' order by 1";
        return self::getObjectListFromDB( $sql);
    }

    function readWorkers($playerId){
        //read available new workers (train action)
        $sql = "SELECT card_id id, card_type type FROM card WHERE card_location != 'playerOff' and card_location_arg = $playerId and card_type like 'worker%' order by 1";
        return self::getObjectListFromDB( $sql);
    }

    function getTemporaryWorker($playerId){
        $temporaryWorker = $this->readCardsByPlayerIdAndCardType(0,'worker_t');

        $this->cards->moveCard( $temporaryWorker[0]['id'], 'player', $playerId );

    }

    function placeBuilding($playerId, $building, $price, $notify){
        if ($building==null || $building==''){
            throw new BgaUserException( self::_("Building not defined!") );
        }
        if ($this->checkBuildableBuilding($playerId, $building, $price)==0){
            throw new BgaUserException( self::_("Building not buildable!") );
        }
        $buildings = $this->readCardsByPlayerIdAndCardType($playerId, $building);
        $this->cards->moveCard( $buildings[0]['id'], 'player', $playerId );

        if ($price>0){
            $this->dbIncLira($playerId, -$price);
        }

        $this->DbQuery("UPDATE player SET $building=1 WHERE player_id='$playerId'");

        if ($notify){
            $playerToken = $this->arrayFindByProperty($this->playerTokens,'type',$building);
            if ($price>0){
                // Notify all players
                self::notifyAllPlayers( "placeBuilding", clienttranslate( '${player_name} pays ${token_price} and builds ${structure}${token_structure}' ), array(
                    'player_id' => $playerId,
                    'player_name' => $this->getPlayerName($playerId),
                    'structure' => $playerToken['name'],
                    'token_structure' => $building,
                    'token_price' => 'lira'.$price,
                    'i18n' => array( 'structure' )
                ) );
            } else {
                // Notify all players
                self::notifyAllPlayers( "placeBuilding", clienttranslate( '${player_name} builds ${structure}${token_structure}' ), array(
                    'player_id' => $playerId,
                    'player_name' => $this->getPlayerName($playerId),
                    'structure' => $playerToken['name'],
                    'token_structure' => $building,
                    'i18n' => array( 'structure' )
                ) );
            }

        }
    }

    function checkBuildableBuilding($playerId, $building, $price){
        $playerData = $this->getPlayerData($playerId);

        //largeCellar can be upgraded only if mediumCellar built
        if ($building=='largeCellar' && $playerData['mediumCellar']==0){
            return false;
        }
        if ($playerData['lira'] >= $price && $playerData[$building]==0){
            return true;
        }

        return false;
    }

    function readLocationsBySetAndPlayers($includeBonusLocation){
        $result = array();
        $playersNumber = self::getPlayersNumber();
        $soloMode = $this->checkIfSoloMode();

        foreach ($this->boardLocations as $key => $value) {
            if (!$includeBonusLocation && $value['bonus']){
                //no bonus
                continue;
            }
            if ($this->isComponentPlayableBySet($value['set'])){
                if ($value['players']<=$playersNumber){
                    $result[]=$value;
                } else {
                    //in solo mode, bonus locations can be used with wakeup bonuses
                    if ($soloMode && $value['bonus']){
                        $result[]=$value;
                    }
                }
            }
        }
        return $result;
    }

    function readActiveLocations($playerId, $playersFullData, $tokens, $privateHandCards, $season=null, $checkWorkers=true, $includeBonus=true, $includeAllSeasons=true){
        $result = array();
        $locations = $this->readLocationsBySetAndPlayers(true);
        $soloMode = $this->checkIfSoloMode();

        $checkSeason = $season;
        if ($checkSeason==null){
            $checkSeason = self::getGameStateValue('season');
        }

        foreach ($locations as $locationKey => $locationValue) {
            $active = $this->checkLocationAction($playerId, $playersFullData, $tokens, $privateHandCards, $locationValue, $checkSeason, $locations, $checkWorkers, $includeBonus, $includeAllSeasons, $soloMode);
            if ($active > 0){
                $result[] = array('t'=>$locationValue['key'],'a'=>$active);
            }
        }

        return $result;
    }

    /**
     * checks if a location action is playable
     * returns
     * 0: not playable
     * 1: playable
     * 2: playable only with worker grande
     */
    function checkLocationAction($playerId, $playersFullData, $tokens, $privateHandCards, $location, $season, $locations, $checkWorkers, $includeBonus, $includeAllSeasons, $soloMode){
        $playerFullData = $playersFullData[$playerId];

        //season?
        if ($location['season']!=9 && $location['season']!=$season){
            return 0;
        }

        if ($includeAllSeasons==false && $location['season']==9){
            //no include all seasons location action (yoke and 1 lira)
            return 0;
        }

        //in solo mode, bonus locations can be used only if player has bonuses (bonus wakeup)
        if ($soloMode>0 && $location['bonus']){
            if ($playerFullData['bonuses']==0){
                return 0;
            }
        }

        //workers available?
        $workers = 0;
        $workersGrande = 0;
        foreach ($tokens[$playerId] as $tokensKey => $tokensValue) {
            if ($tokensValue['l'] =='player' && $this->startsWith($tokensValue['t'],'worker')){
                $workers++;
                if ($tokensValue['t']=='worker_g'){
                    $workersGrande++;
                }
            }
        }
        
        if ($checkWorkers){
            //no workers no action
            if ($workers == 0){
                return 0;
            }
        }

        //location occupied?
        $occupied=false;
        $total=0;
        foreach ($tokens as $tokensPl => $tokensPlValues) {
            foreach ($tokensPlValues as $tokensKey => $tokensValue) {
                if ($location['key']==901){
                    //yoke
                    if ($tokensValue['l']=='board_'.$location['key'] && $tokensPl==$playerId){
                        $total++;
                    }
                } else if ($tokensValue['l']=='board_'.$location['key']){
                    $total++;
                }
            }
        }
        if ($total>=$location['max']){
            $occupied = true;
        }

        //not active if occupied and no worker grande
        //or occupied and bonus bonus
        //or occupied and is a playerboard action (yoke)
        if ($checkWorkers){
            if ($occupied && ($location['bonus']!='' || $workersGrande==0 || $location['key']==901)){
                return 0;
            }
        }
 

        //if not occupied check if it's the last one (friendly variant)
        $lastFree = true;
        if ($occupied==false){
            foreach ($locations as $locationsKey => $locationsValue){
                // same action, different location
                if ($locationsValue['action']==$location['action'] && $locationsValue['key'] != $location['key']){
                    $otherOccupied=false;
                    foreach ($tokens as $tokensPl => $tokensPlValues) {
                        foreach ($tokensPlValues as $tokensKey => $tokensValue) {
                            if ($tokensValue['l']=='board_'.$locationsValue['key']){
                                $otherOccupied=true;
                            }
                        }
                    }
                    if ($otherOccupied==false){
                        $lastFree = false;
                    }
                }
            }
        }

        $result = 0;

        $actions = $location['action'];
        if ($location['bonus']!=''){
            if ($includeBonus==false){
                //no location with bonus
                return 0;
            }

            $actions = $actions.'+'.$location['bonus'];
        }

        $result = $this->checkLocationPlayable($playerId, $playersFullData, $tokens, $privateHandCards, $location, $lastFree);

        if ($result==true){
            $result = 1;
        }
        if ($result==false){
            $result = 0;
        }

        //if occupied, it's playable only with worker grande (return 2)
        //or
        //if only worker grande left... then must use it
        if (($result==1 && $occupied)
        || ($result==1 && $workers==1 && $workersGrande>0)){
            $result = 2;
        }

        return $result;
    }

    function executeLocationActionInternal($playerId, $locationKey, $structure, $cardId, $cardKey,
                                   $field, $harvestFieldsId, $wine, $wineValue, $grapesId,
                                   $buyField, $sellField, $sellGrapesId,
                                   $uprootVinesId, $orderWinesId, $cardsSelectedId, $workersSelectedId, $otherSelection,
                                   $visitorCardId, $visitorCardKey, $visitorCardOption,
                                   $playersData, $tokens, $playersPrivateHand, $sendNotifications){

        //find location
        if(array_key_exists($locationKey, $this->boardLocations)){
            $location = $this->boardLocations[$locationKey];
        } else {
            //if not found, could it be shared location
            foreach ($this->boardLocations as $boardLocationsKey => $boardLocationsValue) {
                if ($boardLocationsValue['sha']==$locationKey && $boardLocationsValue['bonus']==''){
                    $location = $boardLocationsValue;
                }
            }
        }
      
        //location not found?
        if ($location==null){
            throw new BgaUserException( self::_("Location not found ")+$locationKey );
        }

        $playerData = $playersData[$playerId];

        $actions = $location['action'];
        if ($location['bonus']!=''){
            $actions = $actions.'+'.$location['bonus'];
        }

        $liraNotification = 0;

        switch ($actions) {
            case 'fillOrder_1':
            case 'fillOrder_1+getVp_1':
                $vpBonus = 0;
                if ($location['bonus']=='getVp_1'){
                    $vpBonus=1;
                }
                $this->fillOrderInternal($playerId, $cardId, $cardKey, $orderWinesId, $playersData, $tokens, $playersPrivateHand, $vpBonus);
                break;
            case 'uproot_1|harvestField_1':
                if (count($uprootVinesId)==1){
                    $this->uprootVineInternal($playerId, $uprootVinesId[0], $playerData, false);
                } else if (count($harvestFieldsId) == 1){
                    $this->harvestFieldInternal($playerId, $harvestFieldsId, $playersData, $tokens, $playersPrivateHand, 1, $locationKey);
                } else {
                    throw new BgaUserException( self::_("Cannot uproot or harvest field!") );
                }
                break;

            case 'sellGrapes_1|buySellVine_1':
            case 'sellGrapes_1|buySellVine_1+getVp_1':
                if ($buyField){
                    $this->buyField($playerId, $playerData, $buyField);
                } else if ($sellField){
                    $this->sellField($playerId, $playerData, $sellField);
                } else if (count($sellGrapesId)>0){
                    $this->sellGrapes($playerId, $playerData, $sellGrapesId);
                } else {
                    throw new BgaUserException( self::_("Cannot sell grapes or buy/sell field!") );
                }
                if ($location['bonus']=='getVp_1'){
                    $this->dbIncScore($playerId, 1, 'vit_scoring_action_bonus');
                    // Notify all players
                    self::notifyAllPlayers( "tastingRoomVp", clienttranslate( '${player_name} gets a vp ${token_vp} from location bonus' ), array(
                        'player_id' => $playerId,
                        'player_name' => $this->getPlayerName($playerId),
                        'token_vp' => 'vp1'
                    ) );
                }
                break;

            case 'makeWine_2':
                $this->makeWineInternal($playerId, $wine, $wineValue, $grapesId, $playersData, $tokens, $playersPrivateHand, true);
                $this->insertPlayerAction($playerId, 'makeWine', 0, $locationKey);
                break;

            case 'makeWine_2+makeWine_1':
                $this->makeWineInternal($playerId, $wine, $wineValue, $grapesId, $playersData, $tokens, $playersPrivateHand, true);
                $this->insertPlayerAction($playerId, 'makeWine', 0, $locationKey);
                $this->insertPlayerAction($playerId, 'makeWine', 0, $locationKey);
                break;

            case 'playYellowCard_1':
                $this->playYellowCardInternal($playerId, $locationKey, $structure, $cardId, $cardKey,
                    $field, $harvestFieldsId, $wine, $wineValue, $grapesId,
                    $buyField, $sellField, $sellGrapesId,
                    $uprootVinesId, $orderWinesId, $cardsSelectedId, $workersSelectedId, $otherSelection,
                    $visitorCardId, $visitorCardKey, $visitorCardOption,
                    $playersData, $tokens, $playersPrivateHand, true, $sendNotifications);

                break;

            case 'playYellowCard_1+playYellowCard_1':
                $this->playYellowCardInternal($playerId, $locationKey, $structure, $cardId, $cardKey,
                    $field, $harvestFieldsId, $wine, $wineValue, $grapesId,
                    $buyField, $sellField, $sellGrapesId,
                    $uprootVinesId, $orderWinesId, $cardsSelectedId, $workersSelectedId, $otherSelection,
                    $visitorCardId, $visitorCardKey, $visitorCardOption,
                    $playersData, $tokens, $playersPrivateHand, true, $sendNotifications);

                    $this->insertPlayerAction($playerId, 'playYellowCard', 100, $locationKey);

                break;

            case 'playBlueCard_1':
                $this->playBlueCardInternal($playerId, $locationKey, $structure, $cardId, $cardKey,
                    $field, $harvestFieldsId, $wine, $wineValue, $grapesId,
                    $buyField, $sellField, $sellGrapesId,
                    $uprootVinesId, $orderWinesId, $cardsSelectedId, $workersSelectedId, $otherSelection,
                    $visitorCardId, $visitorCardKey, $visitorCardOption,
                    $playersData, $tokens, $playersPrivateHand, true, $sendNotifications);

                break;

            case 'playBlueCard_1+playBlueCard_1':
                $this->playBlueCardInternal($playerId, $locationKey, $structure, $cardId, $cardKey,
                    $field, $harvestFieldsId, $wine, $wineValue, $grapesId,
                    $buyField, $sellField, $sellGrapesId,
                    $uprootVinesId, $orderWinesId, $cardsSelectedId, $workersSelectedId, $otherSelection,
                    $visitorCardId, $visitorCardKey, $visitorCardOption,
                    $playersData, $tokens, $playersPrivateHand, true, $sendNotifications);

                    $this->insertPlayerAction($playerId, 'playBlueCard', 100, $locationKey);

                break;

            case 'harvestField_1':
                //harvestFieldInternal method sends notification
                $this->harvestFieldInternal($playerId, $harvestFieldsId, $playersData, $tokens, $playersPrivateHand, 1, $locationKey);
                break;

            case 'harvestField_1+harvestField_1':
                $this->harvestFieldInternal($playerId, $harvestFieldsId, $playersData, $tokens, $playersPrivateHand, 2, $locationKey);
                break;

            case 'plant_1':
                //plant method sends notification
                $this->plantInternal($playerId, $cardId, $cardKey, $field, $playersData, $tokens, $playersPrivateHand, true, true);
                break;

            case 'plant_1+plant_1':
                //plant method sends notification
                $this->plantInternal($playerId, $cardId, $cardKey, $field, $playersData, $tokens, $playersPrivateHand, true, true);
                $playersData = $this->getPlayersFullData();
                $tokens = $this->readTokens();
                $playersPrivateHand = $this->readPlayersPrivateHand($playersData);
                $handCard = $playersPrivateHand[$playerId]['hand'];
                if ($this->checkActionCardPlayability($playerId, 'greenCard', $playersData[$playerId], $playersPrivateHand, $tokens, true, false, $playersData, $cardId)>0){
                    $this->insertPlayerAction($playerId, 'plant', 0, $locationKey);
                } else {
                    $this->manageFriendlyBlocking($playerId, null, 'plant_1', $locationKey);
                }
                break;

            case 'buildStructure_1':
                $this->buildStructureInternal($playerId, $structure, 0);
                break;

            case 'buildStructure_1+getDiscountLira1':
                $this->buildStructureInternal($playerId, $structure, 1);
                break;

            case 'trainWorker_1':
                //addWorker method sends notification
                $this->addWorker($playerId,'board_'.$location['sha'].'_new', 4, true);
                break;

            case 'trainWorker_1+getDiscountLira1':
                //addWorker method sends notification
                $this->addWorker($playerId,'board_'.$location['sha'].'_new', 3, true);
                break;

            case 'drawGreenCard_1':
                //drawFromDeck method sends notification
                $this->drawFromDeck($playerId, DECK_GREEN, 1, true);
                break;

            case 'drawGreenCard_1+drawGreenCard_1':
                //drawFromDeck method sends notification
                $this->drawFromDeck($playerId, DECK_GREEN, 2, true);
                break;

            case 'getLira_2':
                $this->dbIncLira($playerId, 2);
                $liraNotification = 2;
                //tasting room 1vp if you have wines (only once per turn);
                if ($playerData['tastingRoom']==1 && $playerData['tastingRoomUsed']==0 && count($playerData['wines'])>0){
                    $this->dbIncScore($playerId, 1, 'vit_scoring_tasting_room');
                    $this->DbQuery("UPDATE player SET tastingRoomUsed=1 WHERE player_id='$playerId'");
                    // Notify all players
                    self::notifyAllPlayers( "tastingRoomVp", clienttranslate( '${player_name} gets a vp ${token_vp} from tasting room ${token_tastingRoom} effect' ), array(
                        'player_id' => $playerId,
                        'player_name' => $this->getPlayerName($playerId),
                        'token_vp' => 'vp1',
                        'token_tastingRoom' => 'tastingRoom'
                    ) );
                }
                break;

            case 'getLira_2+getLira_1':
                $this->dbIncLira($playerId, 3);
                $liraNotification = 3;
                //tasting room 1vp if you have wines (only once per turn);
                if ($playerData['tastingRoom']==1 && $playerData['tastingRoomUsed']==0 && count($playerData['wines'])>0){
                    $this->dbIncScore($playerId, 1, 'vit_scoring_tasting_room');
                    $this->DbQuery("UPDATE player SET tastingRoomUsed=1 WHERE player_id='$playerId'");
                    // Notify all players
                    self::notifyAllPlayers( "tastingRoomVp", clienttranslate( '${player_name} gets a vp ${token_vp} from tasting room ${token_tastingRoom} effect' ), array(
                        'player_id' => $playerId,
                        'player_name' => $this->getPlayerName($playerId),
                        'token_vp' => 'vp1',
                        'token_tastingRoom' => 'tastingRoom'
                    ) );
                }
                break;

            case 'drawPurpleCard_1':
                //drawFromDeck method sends notification
                $this->drawFromDeck($playerId, DECK_PURPLE, 1, true);
                break;

            case 'drawPurpleCard_1+drawPurpleCard_1':
                //drawFromDeck method sends notification
                $this->drawFromDeck($playerId, DECK_PURPLE, 2, true);
                break;

            case 'getLira_1':
                $this->dbIncLira($playerId, 1);
                $liraNotification = 1;
                break;

            default:
                throw new BgaUserException( self::_("Action not valid!").$actions );
                break;
        }

        $statistic = $location['stat'];
        if ($statistic){
            self::incStat( 1, $statistic, $playerId );
        }
        if ($location['bonus']!=''){
            self::incStat( 1, 'vit_actions_with_bonus', $playerId );
        }

        if ($sendNotifications){
            if ($liraNotification>0){
                // Notify all players
                self::notifyAllPlayers( "action", clienttranslate( '${player_name} gets ${token_get}' ), array(
                    'player_id' => $playerId,
                    'player_name' => $this->getPlayerName($playerId),
                    'lira' => $liraNotification,
                    'token_get' => 'lira'.$liraNotification
                ) );
            }
        }

    }

    function discardCardsInternal($playerId, $cardsId, $toDiscard){
        $playersData = $this->getPlayersFullData();
        $tokens = $this->readTokens();
        $playersPrivateHand = $this->readPlayersPrivateHand($playersData);
        $handCard = $playersPrivateHand[$playerId]['hand'];
        if ($toDiscard!=count($cardsId)){
            throw new BgaUserException( self::_("Wrong number of cards!") );
        }

        //check all cards are present in player hand
        $cardsIdJoin = implode(',',$cardsId);
        $sql = "SELECT card_id id, card_type type, card_type_arg type_arg FROM card WHERE card_location='hand' AND card_location_arg = $playerId and card_id in (${cardsIdJoin}) and card_id not in (select pa.card_id from player_action pa where pa.card_id is not null)";
        $cards = self::getObjectListFromDB( $sql);

        if (count($cards) != count($cardsId)){
            throw new BgaUserException( self::_("Cards not valid!") );
        }

        //discard cards
        foreach ($cards as $cardsKey => $cardsValue) {
            //discard on top
            $this->discardCardOnDeckTop($cardsValue['id'], $cardsValue['type_arg']);
        }

        // Notify all players
        $notificationText = clienttranslate( '${player_name} discards ${number} card(s)' );
        self::notifyAllPlayers( "discardCards", $notificationText, array(
            'player_id' => $playerId,
            'player_name' => $this->getPlayerName($playerId),
            'number' => count($cardsId),
            'countDeckCards' => $this->readCountDeckCards(),
            'topDiscardDeck' => $this->readTopDiscardDeck()
        ) );
    }

    function buildStructureInternal($playerId, $structure, $discount){
        $building = $this->arrayFindByProperty($this->playerTokens,'type',$structure);
        $price = $building['price'];
        if ($discount>$price){
            $price = 0;
        } else if ($discount>0){
            $price = $price - $discount;
        }
        if ($this->checkBuildableBuilding($playerId, $structure, $price)==0){
            throw new BgaUserException( self::_("Cannot build structure"));
        }

        //placeBuilding method sends notification
        $this->placeBuilding($playerId,  $structure, $price, true);
    }

    function giveCardInternal($playerId, $cardsSelectedId, $visitorCardId, $visitorCardKey, $playerIdGive, $playersData, $tokens, $playersPrivateHand){
        $playerFullData = $playersData[$playerId];
        $createCardOffer = array();

        foreach ($cardsSelectedId as $cardsSelectedIdKey => $cardsSelectedIdValue) {
            $cardHand = $this->arrayFindByProperty($playersPrivateHand[$playerId]['hand'],'i',$cardsSelectedIdValue);
            if (!$cardHand){
                throw new BgaUserException( self::_("Card not found!") );
            }

            //835	Governor
            //Choose up to 3 opponents to each give you 1 ${token_yellowCard}. Gain ${token_vp1} for each of them who cannot.
            //811   Queen
            // The player on your right must choose 1: lose ${token_vp1}, give you 2 ${token_anyCard}, or pay you ${token_lira3}.
            if ($visitorCardKey == 835 || $visitorCardKey == 811){
                //move card to hand of player
                $this->cards->moveCard( $cardsSelectedIdValue, 'hand', $playerIdGive );

                // Notify all players
                self::notifyAllPlayers( "playBlueCard", clienttranslate( '${player_name} gives ${token_yellowCard} to ${other_player_name} ' ), array(
                    'player_id' => $playerId,
                    'player_name' => $this->getPlayerName($playerId),
                    'other_player_name' => $this->getPlayerName($playerIdGive),
                    'token_yellowCard'=>$cardHand['t']
                ) );
            }

            //623	Importer
            //Draw 3 ${token_blueCardPlus} cards unless all opponents combine to give you 3 visitor cards (total).
            if ($visitorCardKey == 623){

                if ($cardHand['t']!='blueCard' && $cardHand['t']!='yellowCard'){
                    throw new BgaUserException( self::_("Wrong card!") );
                }

                $createCardOffer[] = array('type' => $cardHand['t'], 'type_arg' => $cardHand['k'], 'nbr' => 1);
            }
        }
        //623	Importer
        //Draw 3 ${token_blueCardPlus} cards unless all opponents combine to give you 3 visitor cards (total).
        if ($visitorCardKey == 623){
            $this->cards->createCards( $createCardOffer, 'offerCards', $playerId );

            // Notify all players
            self::notifyAllPlayers( "giveCardInternal", clienttranslate( '${player_name} offers ${count_cards} visitor card(s)' ), array(
                'player_id' => $playerId,
                'player_name' => $this->getPlayerName($playerId),
                'count_cards' => count($createCardOffer),
                'token_blueCard'=>'blueCard'
            ) );
        }

    }

    function playYellowCardInternal($playerId, $locationKey, $structure, $cardId, $cardKey,
            $field, $harvestFieldsId, $wine, $wineValue, $grapesId,
            $buyField, $sellField, $sellGrapesId,
            $uprootVinesId, $orderWinesId, $cardsSelectedId, $workersSelectedId, $otherSelection,
            $visitorCardId, $visitorCardKey, $visitorCardOption,
            $playersData, $tokens, $privateHandCards, $firstOption, $sendNotifications){

        $playerFullData = $playersData[$playerId];
        $cardHand = $this->arrayFindByProperty($privateHandCards[$playerId]['hand'],'i',$visitorCardId);
        if (!$cardHand){
            throw new BgaUserException( self::_("Card not found!") );
        }
        $card = $this->yellowCards[$cardHand['k']];
        $playerWines = $playerFullData['wines'];
        $playerGrapes = $playerFullData['grapes'];
        $playerTokens = $this->playerTokens;
        $lira = (int)$playerFullData['lira'];
        $score = (int)$playerFullData['score'];

        $choices = $this->checkYellowCard($playerId, $playerFullData, $cardHand['t'], $cardHand['k'], $playersData, $tokens, $privateHandCards);
        $check = $choices&$visitorCardOption;
        if ($choices&$visitorCardOption == 0){
            throw new BgaUserException( self::_("Card option not valid!") );
        }

        // Notify all players
        self::notifyAllPlayers( "playYellowCard", clienttranslate( '${player_name} plays summer visitor card ${token_yellowCard} ${cardName}' ), array(
            'player_id' => $playerId,
            'player_name' => $this->getPlayerName($playerId),
            'cardId' => $visitorCardId,
            'cardKey' => $visitorCardKey,
            'cardName' => $card['name'],
            'token_yellowCard' => 'yellowCard|'.$visitorCardKey,
            'i18n'=>array('cardName')
        ) );

        //Update player card played
        $this->DbQuery("UPDATE player SET card_played=$visitorCardKey WHERE player_id='$playerId'");
        if ($firstOption == true){
            $this->addCardPlayedToHistory($playerId, $visitorCardId, $visitorCardKey);
        }
        //updating data read
        $playerFullData['card_played'] = $visitorCardKey;

        $maintainCardInHand = false;

        switch ($card['key']) {
            case 601: //Surveyor
                //Gain ${token_lira2} for each empty field you own OR gain ${token_vp1} for each planted field you own.
                //**special**
                if ($visitorCardOption == 1){
                    $lira=0;
                    //Check if there is at least a vine in one field
                    foreach ($this->fields as $fieldsKey => $fieldsValue) {
                        if ($playerFullData[$fieldsValue['dbField']]>0 && count($playerFullData[$fieldsValue['location']])==0){
                            $lira+=2;
                        }
                    }

                    $this->dbIncLira($playerId, $lira);
                    // Notify all players
                    self::notifyAllPlayers( "playYellowCard", clienttranslate( '${player_name} gets ${token_get}' ), array(
                        'player_id' => $playerId,
                        'player_name' => $this->getPlayerName($playerId),
                        'lira' => $lira,
                        'token_get' => 'lira'.$lira
                    ) );

                }
                if ($visitorCardOption == 2){
                    $vps=0;
                    //Check if there is at least a vine in one field
                    foreach ($this->fields as $fieldsKey => $fieldsValue) {
                        if ($playerFullData[$fieldsValue['dbField']]>0 && count($playerFullData[$fieldsValue['location']])>0){
                            $vps++;
                        }
                    }

                    $this->dbIncScore($playerId, $vps, 'vit_scoring_yellow_card');
                    // Notify all players
                    self::notifyAllPlayers( "playYellowCard", clienttranslate( '${player_name} gets ${token_get}' ), array(
                        'player_id' => $playerId,
                        'player_name' => $this->getPlayerName($playerId),
                        'token_get' => 'vp'.$vps
                    ) );

                }
                break;

            case 602: //Broker
                //Pay ${token_lira9} to gain ${token_vp3} OR lose ${token_vp2} to gain ${token_lira6}
                //payLira_9+getVp_3|loseVp_2+getLira_6
                if ($visitorCardOption == 1){
                    $this->dbIncLira($playerId, -9);
                    $this->dbIncScore($playerId, 3, 'vit_scoring_yellow_card');
                    // Notify all players
                    self::notifyAllPlayers( "playYellowCard", clienttranslate( '${player_name} pays ${token_price} and gets ${token_get}' ), array(
                        'player_id' => $playerId,
                        'player_name' => $this->getPlayerName($playerId),
                        'token_price' => 'lira9',
                        'token_get' => 'vp3'
                    ) );
                }
                if ($visitorCardOption == 2){
                    $this->dbIncScore($playerId, -2, 'vit_scoring_yellow_card');
                    $this->dbIncLira($playerId, 6);
                    // Notify all players
                    self::notifyAllPlayers( "playYellowCard", clienttranslate( '${player_name} loses ${token_vp} gets ${token_get}' ), array(
                        'player_id' => $playerId,
                        'player_name' => $this->getPlayerName($playerId),
                        'token_vp' => 'vp2',
                        'token_get' => 'lira6'
                    ) );
                }
                break;

            case 603: //Wine Critic
                //Draw 2 ${token_blueCardPlus OR discard 1 ${token_wineAny} of value 7 or more to gain ${token_vp4}
                //drawBlueCard_2|dicardWineAny_1_7+getVp_4

                if ($visitorCardOption == 1){
                    $this->drawFromDeck($playerId, DECK_BLUE, 2, true);
                }

                if ($visitorCardOption == 2){
                    $wines = $this->readCardsByPlayerIdAndCardTypeAndCardTypeArg($playerId, $wine, $wineValue);
                    if (count($wines)==0){
                        throw new BgaUserException( self::_("Wine not found!") );
                    }
                    if ($wineValue<7){
                        throw new BgaUserException( self::_("Wrong wine!") );
                    }
                    $wineId = $wines[0]['id'];

                    $this->DbQuery("DELETE FROM card WHERE card_location='playerWines' AND card_location_arg = $playerId AND card_type like 'wine%' and card_id =$wineId");

                    $this->dbIncScore($playerId, 4, 'vit_scoring_yellow_card');

                    $token_wine = $wine.$wineValue;
                    // Notify all players
                    self::notifyAllPlayers( "playYellowCard", clienttranslate( '${player_name} discards ${token_wine} and gets ${token_get}' ), array(
                        'player_id' => $playerId,
                        'player_name' => $this->getPlayerName($playerId),
                        'token_wine' => $token_wine,
                        'vps' => 4,
                        'token_get' => 'vp4'
                    ) );
                }

                break;

            case 604: //Blacksmith
                //Build a structure at a ${token_lira2} discount. If it is a ${token_lira5} or ${token_lira6} structure, also gain ${token_vp1}.
                //buildStructure_1_2_ifgreat5_1vp
                if ($visitorCardOption == 1){
                    $this->buildStructureInternal($playerId, $structure, 2);
                    $building = $this->arrayFindByProperty($this->playerTokens,'type',$structure);
                    $price = $building['price'];
                    if ($price==5||$price==6){
                        $this->dbIncScore($playerId, 1, 'vit_scoring_yellow_card');
                        // Notify all players
                        self::notifyAllPlayers( "playYellowCard", clienttranslate( '${player_name} gets ${token_get}' ), array(
                            'player_id' => $playerId,
                            'player_name' => $this->getPlayerName($playerId),
                            'token_get' => 'vp1'
                        ) );
                    }
                }
                break;

            case 605: //Contractor
                //Choose 2: Gain ${token_vp1}, build 1 structure, or plant 1 ${token_greenCard}.
                //getVp1|buildStructure_1|plant_1
                if ($visitorCardOption == 1){
                    $this->dbIncScore($playerId, 1, 'vit_scoring_yellow_card');
                    // Notify all players
                    self::notifyAllPlayers( "playYellowCard", clienttranslate( '${player_name} gets ${token_get}' ), array(
                        'player_id' => $playerId,
                        'player_name' => $this->getPlayerName($playerId),
                        'token_get' => 'vp1'
                    ) );
                }
                if ($visitorCardOption == 2){
                    $this->buildStructureInternal($playerId, $structure, 0);
                }
                if ($visitorCardOption == 3){
                    $this->plantInternal($playerId, $cardId, $cardKey, $field, $playersData, $tokens, $privateHandCards, true, true);
                }

                $playersFullData = $this->getPlayersFullData();

                $maintainCardInHand = $this->checkAndInsertSecondCardOptionAction($playerId, $visitorCardId, $visitorCardKey, $visitorCardOption, 0, $playersFullData, $tokens, $privateHandCards, false);

                break;

            case 606: //Tour Guide
                //Gain ${token_lira4} OR harvest 1 field.
                //getLira_4|harvestField_1
                if ($visitorCardOption == 1){
                    $this->dbIncLira($playerId, 4);
                    // Notify all players
                    self::notifyAllPlayers( "playYellowCard", clienttranslate( '${player_name} gets ${lira}${token_lira}' ), array(
                        'player_id' => $playerId,
                        'player_name' => $this->getPlayerName($playerId),
                        'lira' => 4,
                        'token_lira' => 'lira'
                    ) );

                }
                if ($visitorCardOption == 2){
                    $this->harvestFieldInternal($playerId, $harvestFieldsId, $playersData, $tokens, $privateHandCards, 1, null);
                }
                break;

            case 607: //Novice Guide
                //Gain ${token_lira3} OR make up to 2 ${token_wineAny}
                //getLira_3|makeWine_2
                if ($visitorCardOption == 1){
                    $this->dbIncLira($playerId, 3);
                    // Notify all players
                    self::notifyAllPlayers( "playYellowCard", clienttranslate( '${player_name} gets ${token_lira}' ), array(
                        'player_id' => $playerId,
                        'player_name' => $this->getPlayerName($playerId),
                        'lira' => 3,
                        'token_lira' => 'lira3'
                    ) );

                }
                if ($visitorCardOption == 2){
                    $this->makeWineInternal($playerId, $wine, $wineValue, $grapesId, $playersData, $tokens, $privateHandCards ,true);
                    $this->insertPlayerAction($playerId, 'makeWine', 0, '', $visitorCardId, $visitorCardKey);
                }

                break;

            case 608: //Uncertified Broker
                //Lose ${token_vp3}  to gain ${token_lira9} OR pay ${token_lira6} to gain ${token_vp2}.
                //loseVp_3+getLira_9|payLira_6+getVp_2
                if ($visitorCardOption == 1){
                    $this->dbIncScore($playerId, -3, 'vit_scoring_yellow_card');
                    $this->dbIncLira($playerId, 9);
                    // Notify all players
                    self::notifyAllPlayers( "playYellowCard", clienttranslate( '${player_name} loses ${token_vp} and gets ${token_get}' ), array(
                        'player_id' => $playerId,
                        'player_name' => $this->getPlayerName($playerId),
                        'token_vp' => 'vp3',
                        'token_get' => 'lira9'
                    ) );
                }
                if ($visitorCardOption == 2){
                    $this->dbIncLira($playerId, -6);
                    $this->dbIncScore($playerId, 2, 'vit_scoring_yellow_card');
                    // Notify all players
                    self::notifyAllPlayers( "playYellowCard", clienttranslate( '${player_name} pays ${token_price} and gets ${token_get}' ), array(
                        'player_id' => $playerId,
                        'player_name' => $this->getPlayerName($playerId),
                        'token_price' => 'lira6',
                        'token_get' => 'vp2'
                    ) );
                }
                break;

            case 609: //Planter
                //Plant up to 2 ${token_greenCard} and gain ${token_lira1} OR uproot and discard 1 ${token_greenCard} to gain ${token_vp2}.
                //plant_2+getLira_1|uprootAndDiscard_1+getVp_2
                if ($visitorCardOption == 1){
                    $this->plantInternal($playerId, $cardId, $cardKey, $field, $playersData, $tokens, $privateHandCards, true, true);
                    $this->dbIncLira($playerId, 1);
                    // Notify all players
                    self::notifyAllPlayers( "playYellowCard", clienttranslate( '${player_name} gets ${token_get}' ), array(
                        'player_id' => $playerId,
                        'player_name' => $this->getPlayerName($playerId),
                        'token_get' => 'lira1'
                    ) );
                    $this->insertPlayerAction($playerId, 'plant', 0, '', $visitorCardId, $visitorCardKey);
                }
                if ($visitorCardOption == 2){
                    $discardVines = false;
                    $countGreen = 0;
                    $hand = $privateHandCards[$playerId]['hand'];
                    foreach ($hand as $handKey => $handValue) {
                        if ($handValue['t']=='greenCard'){
                            $countGreen++;
                        }
                    }
                    if ($countGreen==0){
                        $discardVines = true;
                    }

                    $this->uprootVineInternal($playerId, $uprootVinesId[0], $playerFullData, $discardVines);

                    if ($discardVines){
                        $this->dbIncScore($playerId, 2, 'vit_scoring_yellow_card');
                        // Notify all players
                        self::notifyAllPlayers( "playYellowCard", clienttranslate( '${player_name} gets ${token_get}' ), array(
                            'player_id' => $playerId,
                            'player_name' => $this->getPlayerName($playerId),
                            'token_get' => 'vp2'
                        ) );
                    } else {
                        $this->insertPlayerAction($playerId, 'discardVines', 0, '', $visitorCardId, $visitorCardKey);
                    }

                }
                break;

            case 610: //Buyer
                //3 victory points
                //payLira_2+getGrapeRed_1|payLira_2+getGrapeWhite_1|discardGrapeAny_1+getLira_2+getVp_1
                if ($visitorCardOption == 1){
                    $this->dbIncLira($playerId, -2);
                    $createGrapes = array();
                    $createGrapes[] = array('type' => 'grapeRed', 'type_arg' => 1, 'nbr' => 1);
                    $this->cards->createCards( $createGrapes, 'playerGrapes', $playerId );

                    $playerFullData = $this->getPlayerFullData($playerId);

                    // Notify all players
                    self::notifyAllPlayers( "playYellowCardNewGrapes", clienttranslate( '${player_name} pays ${token_price} and gets ${token_get}' ), array(
                        'player_id' => $playerId,
                        'player_name' => $this->getPlayerName($playerId),
                        'token_price' => 'lira2',
                        'token_get' => 'grapeRed1',
                        'visitorCardId' => $visitorCardId,
                        'newGrapes' => $playerFullData['grapes']
                    ) );
                }
                if ($visitorCardOption == 2){
                    $this->dbIncLira($playerId, -2);
                    $createGrapes = array();
                    $createGrapes[] = array('type' => 'grapeWhite', 'type_arg' => 1, 'nbr' => 1);
                    $this->cards->createCards( $createGrapes, 'playerGrapes', $playerId );

                    $playerFullData = $this->getPlayerFullData($playerId);

                    // Notify all players
                    self::notifyAllPlayers( "playYellowCardNewGrapes", clienttranslate( '${player_name} pays ${token_price} and gets ${token_get}' ), array(
                        'player_id' => $playerId,
                        'player_name' => $this->getPlayerName($playerId),
                        'token_price' => 'lira2',
                        'token_get' => 'grapeWhite1',
                        'visitorCardId' => $visitorCardId,
                        'newGrapes' => $playerFullData['grapes']
                    ) );
                }
                if ($visitorCardOption == 3){
                    $this->dbIncLira($playerId, 2);
                    $this->dbIncScore($playerId, 1, 'vit_scoring_yellow_card');

                    $grapesIdJoin = implode(',',$grapesId);
                    $sql = "SELECT card_id id, card_type type, card_type_arg type_arg FROM card WHERE card_location='playerGrapes' AND card_location_arg = $playerId AND card_type like 'grape%' and card_id in (${grapesIdJoin})";
                    $grapes = self::getObjectListFromDB( $sql);
                    $tokenGrape = $grapes[0]['type'].$grapes[0]['type_arg'];
                    if (count($grapes) != 1){
                        throw new BgaUserException( self::_("Grapes not valid!") );
                    }

                    $this->DbQuery("DELETE FROM card WHERE card_location='playerGrapes' AND card_location_arg = $playerId AND card_type like 'grape%' and card_id in (${grapesIdJoin})");

                    $playerFullData = $this->getPlayerFullData($playerId);

                    // Notify all players
                    self::notifyAllPlayers( "playYellowCardDiscardGrapes", clienttranslate( '${player_name} discards ${token_grape} and gets ${token_get} and ${token_vp}' ), array(
                        'player_id' => $playerId,
                        'player_name' => $this->getPlayerName($playerId),
                        'token_get' => 'lira2',
                        'token_vp' => 'vp1',
                        'token_grape' => $tokenGrape,
                        'visitorCardId' => $visitorCardId,
                        'newGrapes' => $playerFullData['grapes']
                    ) );
                }
                break;

            case 611: //Landscaper
                //Draw 1 ${token_greenCardPlus} and plant up to 1 ${token_greenCard} OR switch 2 ${token_greenCard} on your fields.
                //**special**
                if ($visitorCardOption == 1){
                    $this->drawFromDeck($playerId, DECK_GREEN, 1, true);
                    $this->insertPlayerAction($playerId, 'plant', 0, '', $visitorCardId, $visitorCardKey);
                }
                if ($visitorCardOption == 2){
                    $cardsSelectedIdJoin = implode(',',$cardsSelectedId);
                    $sql = "SELECT card_id id, card_type type, card_type_arg type_arg, card_location location, card_location_arg location_arg FROM card WHERE card_location like 'vine%' AND card_location_arg = $playerId AND card_type like 'green%' and card_id in (${cardsSelectedIdJoin})";
                    $cardsSelected = self::getObjectListFromDB( $sql);
                    if (count($cardsSelected) != 2){
                        throw new BgaUserException( self::_("Cards not valid!") );
                    }
                    if ($cardsSelected[0]['location']==$cardsSelected[1]['location']){
                        throw new BgaUserException( self::_("Must select cards from two different fields!") );
                    }

                    $cardId1=$cardsSelected[0]['id'];
                    $location1=$cardsSelected[0]['location'];
                    $cardId2=$cardsSelected[1]['id'];
                    $location2=$cardsSelected[1]['location'];
                    $card1 = $this->greenCards[$cardsSelected[0]['type_arg']];
                    $card2 = $this->greenCards[$cardsSelected[1]['type_arg']];

                    $sql = "SELECT card_id id, card_type type, card_type_arg type_arg, card_location location, card_location_arg location_arg FROM card WHERE card_location like 'vine%' AND card_location_arg = $playerId AND card_type like 'green%' and card_id != $cardId1 and card_location='${location1}'";
                    $vinesField1 = self::getObjectListFromDB( $sql);
                    $sql = "SELECT card_id id, card_type type, card_type_arg type_arg, card_location location, card_location_arg location_arg FROM card WHERE card_location like 'vine%' AND card_location_arg = $playerId AND card_type like 'green%' and card_id != $cardId2 and card_location='${location2}'";
                    $vinesField2 = self::getObjectListFromDB( $sql);

                    //check maxValue after switch
                    $totField1=0;
                    foreach ($vinesField1 as $vinesFieldKey => $vinesFieldValue) {
                        $card = $this->greenCards[$vinesFieldValue['type_arg']];
                        $totField1+=$card['red']+$card['white'];
                    }
                    $totField2=0;
                    foreach ($vinesField2 as $vinesFieldKey => $vinesFieldValue) {
                        $card = $this->greenCards[$vinesFieldValue['type_arg']];
                        $totField2+=$card['red']+$card['white'];
                    }

                    $totField1+=$card2['red']+$card2['white'];
                    $totField2+=$card1['red']+$card1['white'];
                    $field1 = $this->arrayFindByProperty($this->fields, 'location', $location1);
                    $field2 = $this->arrayFindByProperty($this->fields, 'location', $location2);
                    if ($totField1 > $field1['maxValue']){
                        throw new BgaUserException( self::_("Field max vine value exceeded!") );
                    }
                    if ($totField2 > $field2['maxValue']){
                        throw new BgaUserException( self::_("Field max vine value exceeded!") );
                    }
                    
                    $this->DbQuery("UPDATE card SET card_location='$location2' where card_id=$cardId1");
                    $this->DbQuery("UPDATE card SET card_location='$location1' where card_id=$cardId2");

                    // Notify all players
                    self::notifyAllPlayers( "playYellowCard", clienttranslate( '${player_name} switched ${cardName1} in field ${field1} with ${cardName2} in field ${field2}' ), array(
                        'player_id' => $playerId,
                        'player_name' => $this->getPlayerName($playerId),
                        'cardName1' => $card1['name'],
                        'field1' => $location1,
                        'cardName2' => $card2['name'],
                        'field2' => $location2,
                        'i18n' => array('cardName1','cardName2')
                    ) );

                }
                break;

            case 612: //Architect
                //Build a structure at a ${token_lira3} discount OR gain ${token_vp1} for each ${token_lira4} structure you have built.
                //buildStructure_1_3|getVp_buildings4
                if ($visitorCardOption == 1){
                    $this->buildStructureInternal($playerId, $structure, 3);
                }

                if ($visitorCardOption == 2){
                    $vps=0;
                    foreach ($playerTokens as $playerTokensKey => $playerTokensValue) {
                        //enough money and buildable
                        if ($playerTokensValue['isBuilding'] && $playerTokensValue['price']==4 && $playerFullData[$playerTokensValue['type']]==1){
                            $vps++;
                        }
                    }

                    $this->dbIncScore($playerId, $vps, 'vit_scoring_yellow_card');
                    // Notify all players
                    self::notifyAllPlayers( "playYellowCard", clienttranslate( '${player_name} gets ${token_get}' ), array(
                        'player_id' => $playerId,
                        'player_name' => $this->getPlayerName($playerId),
                        'token_get' => 'vp'.$vps
                    ) );

                }
                break;

            case 613: //Uncertified Architect
                //Lose ${token_vp1} to build a ${token_lira2} or ${token_lira3} structure OR lose ${token_vp2} to build any structure.
                //**special**
                if ($visitorCardOption == 1){
                    $playerToken = $this->arrayFindByProperty($this->playerTokens,'type',$structure);
                    if ($playerToken['price']!=2 && $playerToken['price']!=3){
                        throw new BgaUserException( self::_("Wrong structure!") );
                    }
                    $this->dbIncScore($playerId, -1, 'vit_scoring_yellow_card');
                    // Notify all players
                    self::notifyAllPlayers( "playYellowCard", clienttranslate( '${player_name} loses ${token_vp}' ), array(
                        'player_id' => $playerId,
                        'player_name' => $this->getPlayerName($playerId),
                        'token_vp' => 'vp1'
                    ) );
                    $this->buildStructureInternal($playerId, $structure, 99);
                }
                if ($visitorCardOption == 2){
                    $this->dbIncScore($playerId, -2, 'vit_scoring_yellow_card');
                    // Notify all players
                    self::notifyAllPlayers( "playYellowCard", clienttranslate( '${player_name} loses ${token_vp}' ), array(
                        'player_id' => $playerId,
                        'player_name' => $this->getPlayerName($playerId),
                        'token_vp' => 'vp2'
                    ) );
                    $this->buildStructureInternal($playerId, $structure, 99);
                }

                break;

            case 614: //Patron
                //Gain ${token_lira4} OR draw 1 ${token_purpleCard} card and 1 ${token_blueCard}.
                //getLira_4|drawPurpleCard_1+drawBlueCard_1
                if ($visitorCardOption == 1){
                    $this->dbIncLira($playerId, 4);
                    // Notify all players
                    self::notifyAllPlayers( "playYellowCard", clienttranslate( '${player_name} and gets ${token_get}' ), array(
                        'player_id' => $playerId,
                        'player_name' => $this->getPlayerName($playerId),
                        'token_get' => 'lira4'
                    ) );
                }
                if ($visitorCardOption == 2){
                    $this->drawFromDeck($playerId, DECK_PURPLE, 1, true);
                    $this->drawFromDeck($playerId, DECK_BLUE, 1, true);
                }
                break;

            case 615: //Auctioneer
                //Discard 2 ${token_anyCard} to gain ${token_lira4} OR discard 4 ${token_anyCard} to gain ${token_vp3}.
                //discardCard_2+getLira_4|discardCard_4+getVp_3
                if ($visitorCardOption == 1){
                    $this->discardCardsInternal($playerId, $cardsSelectedId, 2);
                    $this->dbIncLira($playerId, 4);
                    // Notify all players
                    self::notifyAllPlayers( "playYellowCard", clienttranslate( '${player_name} and gets ${token_get}' ), array(
                        'player_id' => $playerId,
                        'player_name' => $this->getPlayerName($playerId),
                        'token_get' => 'lira4'
                    ) );
                }
                if ($visitorCardOption == 2){
                    $this->discardCardsInternal($playerId, $cardsSelectedId, 4);
                    $this->dbIncScore($playerId, 3, 'vit_scoring_yellow_card');
                    // Notify all players
                    self::notifyAllPlayers( "playYellowCard", clienttranslate( '${player_name} gets ${token_get}' ), array(
                        'player_id' => $playerId,
                        'player_name' => $this->getPlayerName($playerId),
                        'token_get' => 'vp3'
                    ) );
                }
                break;

            case 616: //Entertainer
                //Pay ${token_lira4} to draw 3 ${token_blueCardPlus} OR discard 1 ${token_wineAny} and 3 visitor cards to gain ${token_vp3}.
                //**special**
                if ($visitorCardOption == 1){
                    $this->dbIncLira($playerId, -4);
                    // Notify all players
                    self::notifyAllPlayers( "playYellowCard", clienttranslate( '${player_name} pays ${token_price}' ), array(
                        'player_id' => $playerId,
                        'player_name' => $this->getPlayerName($playerId),
                        'token_price' => 'lira4',
                        'token_vp' => 'vp3'
                    ) );
                    $this->drawFromDeck($playerId, DECK_BLUE, 3, true);
                }

                if ($visitorCardOption == 2){
                    $wines = $this->readCardsByPlayerIdAndCardTypeAndCardTypeArg($playerId, $wine, $wineValue);
                    if (count($wines)==0){
                        throw new BgaUserException( self::_("Wine not found!") );
                    }
                    $wineId = $wines[0]['id'];

                    $this->DbQuery("DELETE FROM card WHERE card_location='playerWines' AND card_location_arg = $playerId AND card_type like 'wine%' and card_id =$wineId");

                    $cardsSelectedIdJoin = implode(',',$cardsSelectedId);
                    $sql = "SELECT card_id id, card_type type, card_type_arg type_arg FROM card WHERE card_location='hand' AND card_location_arg = $playerId AND card_type in ('yellowCard','blueCard') and card_type_arg != 616 and card_id in (${cardsSelectedIdJoin}) and card_id not in (select pa.card_id from player_action pa where pa.card_id is not null)";
                    $cardsSelected = self::getObjectListFromDB( $sql);

                    if (count($cardsSelected) != count($cardsSelectedId)){
                        throw new BgaUserException( self::_("Cards not valid!") );
                    }

                    $this->discardCardsInternal($playerId, $cardsSelectedId, 3);

                    $this->dbIncScore($playerId, 3, 'vit_scoring_yellow_card');

                    $token_wine = $wine.$wineValue;
                    // Notify all players
                    self::notifyAllPlayers( "playYellowCard", clienttranslate( '${player_name} discards ${token_wine} and 3 ${token_anyCard} and gets ${token_get}' ), array(
                        'player_id' => $playerId,
                        'player_name' => $this->getPlayerName($playerId),
                        'token_wine' => $token_wine,
                        'token_anyCard' => 'anyCard',
                        'token_get' => 'vp3'
                    ) );
                }
                break;

            case 617: //Vendor
                //Draw 1 ${token_greenCardPlus} , 1 ${token_purpleCardPlus}, and 1 ${token_blueCardPlus}. Each opponent may draw 1 ${token_yellowCardPlus}.
                //drawGreenCard_1+drawPurpleCard_1+drawBlueCard_1
                $this->drawFromDeck($playerId, DECK_GREEN, 1, true);
                $this->drawFromDeck($playerId, DECK_PURPLE, 1, true);
                $this->drawFromDeck($playerId, DECK_BLUE, 1, true);
                foreach ($playersData as $playersDataKey => $playersDataValue) {
                    if ($playersDataKey != $playerId){
                        $this->drawFromDeck($playersDataKey, DECK_YELLOW, 1, true);
                    }
                }
                break;

            case 618: //Handyman
                //All players may build 1 structure at a ${token_lira2} discount. You gain ${token_vp1} for each opponent who does this.
                //**special**
                if ($structure != ''){
                    $this->buildStructureInternal($playerId,  $structure, 2);
                }
                $this->insertPlayerAction($playerId, 'allBuild', 0, '', $visitorCardId, $visitorCardKey);
                break;

            case 619: //Horticulturist
                //Plant 1 ${token_greenCard} even if you don't have the required structure(s) OR uproot and discard 2 ${token_greenCard} to gain ${token_vp3}.
                //**special**
                if ($visitorCardOption == 1){
                    $this->plantInternal($playerId, $cardId, $cardKey, $field, $playersData, $tokens, $privateHandCards, false, true);
                }
                if ($visitorCardOption == 2){
                    if (count($uprootVinesId)!=2){
                        throw new BgaUserException( self::_("Wrong field selection!") );
                    }
                    $discardVines = false;
                    $countGreen = 0;
                    $hand = $privateHandCards[$playerId]['hand'];
                    foreach ($hand as $handKey => $handValue) {
                        if ($handValue['t']=='greenCard'){
                            $countGreen++;
                        }
                    }
                    if ($countGreen==0){
                        $discardVines = true;
                    }
                    foreach ($uprootVinesId as $uprootVinesIdKey => $uprootVinesIdValue) {
                        $this->uprootVineInternal($playerId, $uprootVinesIdValue, $playerFullData, $discardVines);
                    }

                    if ($discardVines){
                        $this->dbIncScore($playerId, 3, 'vit_scoring_yellow_card');
                        // Notify all players
                        self::notifyAllPlayers( "playYellowCard", clienttranslate( '${player_name} gets ${token_get}' ), array(
                            'player_id' => $playerId,
                            'player_name' => $this->getPlayerName($playerId),
                            'token_get' => 'vp3'
                        ) );
                    } else {
                        $this->insertPlayerAction($playerId, 'discardVines', 0, '', $visitorCardId, $visitorCardKey);
                    }

                }
                break;

            case 620: //Peddler
                //Discard 2 ${token_anyCard} to draw 1 of each type of card.
                //**special**
                $cardsSelectedIdJoin = implode(',',$cardsSelectedId);
                $sql = "SELECT card_id id, card_type type, card_type_arg type_arg FROM card WHERE card_location='hand' AND card_location_arg = $playerId and card_type_arg != 620 and card_id in (${cardsSelectedIdJoin}) and card_id not in (select pa.card_id from player_action pa where pa.card_id is not null)";
                $cardsSelected = self::getObjectListFromDB( $sql);
                if (count($cardsSelected) != count($cardsSelectedId)){
                    throw new BgaUserException( self::_("Cards not valid!") );
                }

                $this->discardCardsInternal($playerId, $cardsSelectedId, 2);

                $this->drawFromDeck($playerId, DECK_GREEN, 1, true);
                $this->drawFromDeck($playerId, DECK_YELLOW, 1, true);
                $this->drawFromDeck($playerId, DECK_PURPLE, 1, true);
                $this->drawFromDeck($playerId, DECK_BLUE, 1, true);
                break;

            case 621: //Banker
                //Gain ${token_lira5}. Each opponent may lose ${token_vp1} to gain ${token_lira3}.
                //**special**
                $this->dbIncLira($playerId, 5);
                // Notify all players
                self::notifyAllPlayers( "playYellowCard", clienttranslate( '${player_name} and gets ${token_get}' ), array(
                    'player_id' => $playerId,
                    'player_name' => $this->getPlayerName($playerId),
                    'token_get' => 'lira5'
                ) );
                $this->insertPlayerAction($playerId, 'allChoose', 0, '', $visitorCardId, $visitorCardKey);
                break;

            case 622: //Overseer
                //Build 1 structure at its regular cost and plant 1 ${token_greenCard}. If it is a 4-value ${token_greenCard}, gain ${token_vp1}.
                //buildStructure_1|plant_1_ifgreat4_1vp
                $this->buildStructureInternal($playerId, $structure, 0);
                //check on the 1vp in plantInternal
                $this->insertPlayerAction($playerId, 'plant', 0, '', $visitorCardId, $visitorCardKey);
                break;

            case 623: //Importer
                //Draw 3 ${token_blueCard} cards unless all opponents combine to give you 3 visitor cards (total).
                //**special**
                $totalVisitorCards=0;
                $playersWithBlueCards=array();
                foreach ($playersData as $playersDataKey => $playersDataValue) {
                    if ($playersDataKey!=$playerId){
                        $totalVisitorCards+=$playersDataValue['blueCard'];
                        $totalVisitorCards+=$playersDataValue['yellowCard'];
                        if ($playersDataValue['blueCard']+$playersDataValue['yellowCard']>0){
                            $playersWithBlueCards[]=$playersDataKey;
                        }
                    }
                }
                if ($totalVisitorCards<3){
                    $this->drawFromDeck($playerId, DECK_BLUE, 3, true);
                } else {
                    $this->insertPlayerAction($playerId, 'allGiveCard', 0, implode('_',$playersWithBlueCards), $visitorCardId, $visitorCardKey);
                }
                break;

            case 624: //Sharecropper
                //Plant 1 ${token_greenCard} even if you don't have the required structure(s) OR uproot and discard 1 ${token_greenCard} to gain ${token_vp2}.
                //plant_1_noStructure|uprootAndDiscard_1+getVp_2
                if ($visitorCardOption == 1){
                    $this->plantInternal($playerId, $cardId, $cardKey, $field, $playersData, $tokens, $privateHandCards, false, true);
                }
                if ($visitorCardOption == 2){
                    if (count($uprootVinesId)!=1){
                        throw new BgaUserException( self::_("Wrong field selection!") );
                    }

                    $discardVines = false;
                    $countGreen = 0;
                    $hand = $privateHandCards[$playerId]['hand'];
                    foreach ($hand as $handKey => $handValue) {
                        if ($handValue['t']=='greenCard'){
                            $countGreen++;
                        }
                    }
                    if ($countGreen==0){
                        $discardVines = true;
                    }

                    foreach ($uprootVinesId as $uprootVinesIdKey => $uprootVinesIdValue) {
                        $this->uprootVineInternal($playerId, $uprootVinesIdValue, $playerFullData, $discardVines);
                    }

                    if ($discardVines){
                        $this->dbIncScore($playerId, 2, 'vit_scoring_yellow_card');
                        // Notify all players
                        self::notifyAllPlayers( "playYellowCard", clienttranslate( '${player_name} gets ${token_get}' ), array(
                            'player_id' => $playerId,
                            'player_name' => $this->getPlayerName($playerId),
                            'token_get' => 'vp2'
                        ) );
                    } else {
                        $this->insertPlayerAction($playerId, 'discardVines', 0, '', $visitorCardId, $visitorCardKey);
                    }

                }
                break;

            case 625: //Grower
                //Plant 1 ${token_greenCard}. Then, if you have planted a total of at least 6 ${token_greenCard}, gain ${token_vp2}.
                //plant_1_iftotalgreat_6_vp2
                $this->plantInternal($playerId, $cardId, $cardKey, $field, $playersData, $tokens, $privateHandCards, false, true);
                $playerFullData = $this->getPlayerFullData($playerId);
                $vines=0;
                foreach ($this->fields as $fieldsKey => $fieldsValue) {
                    $vines+=count($playerFullData[$fieldsValue['location']]);
                }
                if ($vines>=6){
                    $this->dbIncScore($playerId, 2, 'vit_scoring_yellow_card');
                    // Notify all players
                    self::notifyAllPlayers( "playYellowCard", clienttranslate( '${player_name} gets ${token_get}' ), array(
                        'player_id' => $playerId,
                        'player_name' => $this->getPlayerName($playerId),
                        'token_get' => 'vp2'
                    ) );
                }
                break;

            case 626: //Negotiator
                //Discard 1 ${token_grapeAny} to gain ${token_residualPayment1} OR discard 1 ${token_wineAny} to gain ${token_residualPayment2} .
                //discardGrape_1+getResidualPayment_1|discardWine_1+getResidualPayment_2
                if ($visitorCardOption == 1){
                    $grapesIdJoin = implode(',',$grapesId);
                    $sql = "SELECT card_id id, card_type type, card_type_arg type_arg FROM card WHERE card_location='playerGrapes' AND card_location_arg = $playerId AND card_type like 'grape%' and card_id in (${grapesIdJoin})";
                    $grapes = self::getObjectListFromDB( $sql);
                    $tokenGrape = $grapes[0]['type'].$grapes[0]['type_arg'];
                    if (count($grapes) != 1){
                        throw new BgaUserException( self::_("Grapes not valid!") );
                    }

                    $this->DbQuery("DELETE FROM card WHERE card_location='playerGrapes' AND card_location_arg = $playerId AND card_type like 'grape%' and card_id in (${grapesIdJoin})");

                    $this->dbIncResidualPayment($playerId, 1);

                    // Notify all players
                    self::notifyAllPlayers( "playYellowCard", clienttranslate( '${player_name} gets ${token_get}' ), array(
                        'player_id' => $playerId,
                        'player_name' => $this->getPlayerName($playerId),
                        'token_get' => 'residualPayment1'
                    ) );
                }
                if ($visitorCardOption == 2){
                    $wines = $this->readCardsByPlayerIdAndCardTypeAndCardTypeArg($playerId, $wine, $wineValue);
                    if (count($wines)==0){
                        throw new BgaUserException( self::_("Wine not found!") );
                    }
                    $wineId = $wines[0]['id'];

                    $this->DbQuery("DELETE FROM card WHERE card_location='playerWines' AND card_location_arg = $playerId AND card_type like 'wine%' and card_id =$wineId");

                    $this->dbIncResidualPayment($playerId, 2);

                    // Notify all players
                    self::notifyAllPlayers( "playYellowCard", clienttranslate( '${player_name} discards ${token_wine} and gets ${token_get}' ), array(
                        'player_id' => $playerId,
                        'player_name' => $this->getPlayerName($playerId),
                        'token_get' => 'residualPayment2',
                        'token_wine' => $wine.$wineValue
                    ) );
                }

                break;

            case 627: //Cultivator
                //Plant 1 ${token_greenCard}. You may plant it on a field even if the total value of that field exceeds the max vine value.
                //plant_1_overMax
                $this->plantInternal($playerId, $cardId, $cardKey, $field, $playersData, $tokens, $privateHandCards, true, false);
                break;

            case 628: //Homesteader
                //Build 1 structure at a ${token_lira3} discount OR plant up to 2 ${token_greenCard}. You may lose ${token_vp1} to do both.
                //buildStructure_1_3|plant_2

                if ($firstOption == false){
                    $this->dbIncScore($playerId,-1, 'vit_scoring_yellow_card');
                    // Notify all players
                    self::notifyAllPlayers( "playYellowCard", clienttranslate( '${player_name} loses ${token_price} to do second option of visitor card' ), array(
                        'player_id' => $playerId,
                        'player_name' => $this->getPlayerName($playerId),
                        'token_price' => 'vp1'
                    ) );
                }
                if ($visitorCardOption == 1){
                    $this->buildStructureInternal($playerId, $structure, 3);
                }
                if ($visitorCardOption == 2){
                    $this->plantInternal($playerId, $cardId, $cardKey, $field, $playersData, $tokens, $privateHandCards, true, true);
                    $this->insertPlayerAction($playerId, 'plant', 0, '', $visitorCardId, $visitorCardKey);
                }

                $playersFullData = $this->getPlayersFullData();

                $maintainCardInHand = $this->checkAndInsertSecondCardOptionAction($playerId, $visitorCardId, $visitorCardKey, $visitorCardOption, 1, $playersFullData, $tokens, $privateHandCards, false);

                break;

            case 629: //Planner
                //Place a worker on an action in a future season. Take that action at the beginning of that season.
                //**special**
                $season = self::getGameStateValue('season');

                $otherSelectionTokens = explode('_', $otherSelection);
                $futureLocation = $otherSelectionTokens[0];
                
                //only seasons before winter
                if ($season>=WINTER){
                    throw new BgaUserException( self::_("Wrong season!") );
                }

                //check location
                $boardLocation = $this->boardLocations[$futureLocation];
                if ($boardLocation['season']<=$season){
                    throw new BgaUserException( self::_("Wrong location!") );
                }
                
                //check workers
                $availableWorkers = $this->readAvailableWorkers($playerId);
                if (count($availableWorkers)==0){
                    throw new BgaUserException( self::_("No more workers!") );
                }
                $worker = null;
                $workerId = null;
                if (count($availableWorkers)==1){
                    $worker = $availableWorkers[0]['type'];
                    $workerId = $availableWorkers[0]['id'];
                } else {
                    //one normal worker
                    foreach ($availableWorkers as $availableWorkersKey => $availableWorkersValue) {
                        if (($availableWorkersValue['type']>='worker_0'&&$availableWorkersValue['type']<='worker_9') || $availableWorkersValue['type']=='worker_t'){
                            $worker = $availableWorkersValue['type'];
                            $workerId = $availableWorkersValue['id'];
                        }
                    }
                    //no worker, then grande worker
                    if ($worker==null){
                        foreach ($availableWorkers as $availableWorkersKey => $availableWorkersValue) {
                            if ($availableWorkersValue['type']=='worker_g'){
                                $worker = $availableWorkersValue['type'];
                                $workerId = $availableWorkersValue['id'];
                            }
                        }
                    }
                }

                //solo mode, decrement/use wakeup bonus
                if ($this->checkIfSoloMode() && $boardLocation['bonus']){
                    $this->useWakeupBonus($playerId);
                }

                //place worker in future slot
                $this->cards->moveCard( $workerId, 'board_'.$futureLocation, $playerId );

                break;

            case 630: //Agriculturist
                //Plant 1 ${token_greenCard}. Then, if you have at least 3 different types of ${token_greenCard} planted on that field, gain ${token_vp2}.
                //**special**
                $this->plantInternal($playerId, $cardId, $cardKey, $field, $playersData, $tokens, $privateHandCards, true, false);
                $playerFullData = $this->getPlayerFullData($playerId);
                $vines = $playerFullData[$this->fields[$field]['location']];
                $vineTypes = array();
                foreach ($vines as $vinesKey => $vinesValue) {
                    $vineTypes[] = $vinesValue['k'];
                }
                $vineTypesCount = array_count_values ($vineTypes);
                if (count($vineTypesCount)>=3){
                    $this->dbIncScore($playerId, 2, 'vit_scoring_yellow_card');
                    // Notify all players
                    self::notifyAllPlayers( "playYellowCard", clienttranslate( '${player_name} gets ${token_get}' ), array(
                        'player_id' => $playerId,
                        'player_name' => $this->getPlayerName($playerId),
                        'token_get' => 'vp2'
                    ) );
                }
                break;

            case 631: //Swindler
                //Each opponent may give you ${token_lira2}. For each opponent who does not, gain ${token_vp1}.
                //**special**
                $this->insertPlayerAction($playerId, 'allChoose', 0, '', $visitorCardId, $visitorCardKey);
                break;

            case 632: //Producer
                //Pay ${token_lira2} to retrieve up to 2 ${token_worker} from other actions. They may be used again this year.
                //**special**
                $workersSelectedIdJoin = implode(',',$workersSelectedId);
                $locationToExclude = '';
                $actionProgress = $this->readPlayerActionInProgress();
                if ($actionProgress!= null ){
                    $locationToExclude = 'board_'.$actionProgress['args'];
                }

                $sql = "SELECT card_id id, card_type type, card_type_arg type_arg FROM card WHERE card_location like 'board_%' AND card_location != '$locationToExclude' AND card_location_arg = $playerId AND card_type like 'worker%' and card_id in (${workersSelectedIdJoin})";
                $workers = self::getObjectListFromDB( $sql);
                if (count($workers) != count($workersSelectedId) || count($workers)>2){
                    throw new BgaUserException( self::_("Workers not valid!") );
                }

                $this->DbQuery("UPDATE card SET card_location='player' WHERE card_location_arg = $playerId AND card_type like 'worker%' and card_id in (${workersSelectedIdJoin})");

                $this->dbIncLira($playerId, -2);

                // Notify all players
                self::notifyAllPlayers( "playYellowCard", clienttranslate( '${player_name} pays ${token_price} and retrieves ${workers} ${token_worker}' ), array(
                    'player_id' => $playerId,
                    'player_name' => $this->getPlayerName($playerId),
                    'workers' => count($workers),
                    'token_price' => 'lira2',
                    'token_worker' => 'worker'
                ) );
                break;

            case 633: //Organizer
                //Move your ${token_rooster} piece to an empty row on the wake-up chart, take the bonus, then pass to the next season.
                //**special**
                $otherSelectionTokens = explode('_', $otherSelection);
                if (count($otherSelectionTokens)==1){
                    $otherSelectionTokens[]='';
                }
                $wakeupChart=(int) $otherSelectionTokens[0];
                $wakeupCardType=$otherSelectionTokens[1];

                //from rules:
                // Note: The "Organizer" summer visitor
                // card action can not move your rooster
                // to row 7.
                if ($this->checkIfSoloMode()>0 && $wakeupChart==7){
                    throw new BgaUserException( self::_("The Organizer summer visitor card action can not move your rooster to row 7") );
                }

                //Saving next player id with current order, before changing it
                $nextPlayerId = $this->getNextActivePlayerByWakeupOrder($playerId, true);
                if ($nextPlayerId != null && $nextPlayerId != $playerId){
                    self::setGameStateValue( 'force_next_player_id',$nextPlayerId);
                }

                $this->chooseWakeup($playerId, $wakeupChart, $wakeupCardType, false, false);

                //pass
                $this->DbQuery("UPDATE player SET pass=1, card_played=0 WHERE player_id=$playerId");
                $this->DbQuery("DELETE FROM card WHERE card_location='card_flags'");

                //recalculate wake up order
                $this->recalculateWakeupOrder();

                break;

            case 634: //Sponsor
                //Draw 2 ${token_greenCardPlus} OR gain ${token_lira3}. You may lose ${token_vp1} to do both.
                //drawGreenCard_2|getLira_3
                if ($firstOption == false){
                    $this->dbIncScore($playerId,-1, 'vit_scoring_yellow_card');
                    // Notify all players
                    self::notifyAllPlayers( "playYellowCard", clienttranslate( '${player_name} loses ${token_price} to do second option of visitor card' ), array(
                        'player_id' => $playerId,
                        'player_name' => $this->getPlayerName($playerId),
                        'token_price' => 'vp1'
                    ) );
                }
                if ($visitorCardOption == 1){
                    $this->drawFromDeck($playerId, DECK_GREEN, 2, true);
                }
                if ($visitorCardOption == 2){
                    $this->dbIncLira($playerId, 3);
                    // Notify all players
                    self::notifyAllPlayers( "playYellowCard", clienttranslate( '${player_name} and gets ${token_get}' ), array(
                        'player_id' => $playerId,
                        'player_name' => $this->getPlayerName($playerId),
                        'token_get' => 'lira3'
                    ) );
                }

                $playersFullData = $this->getPlayersFullData();

                $maintainCardInHand = $this->checkAndInsertSecondCardOptionAction($playerId, $visitorCardId, $visitorCardKey, $visitorCardOption, 1, $playersFullData, $tokens, $privateHandCards, false);

                break;

            case 635: //Artisan
                //Choose 1: Gain ${token_lira3}, build a structure at a ${token_lira1} discount, or plant up to 2 ${token_greenCard}.
                //getLira_3|buildStructure_1_1|plant_2
                if ($visitorCardOption == 1){
                    $this->dbIncLira($playerId, 3);
                    // Notify all players
                    self::notifyAllPlayers( "playYellowCard", clienttranslate( '${player_name} and gets ${token_get}' ), array(
                        'player_id' => $playerId,
                        'player_name' => $this->getPlayerName($playerId),
                        'token_get' => 'lira3'
                    ) );
                }
                if ($visitorCardOption == 2){
                    $this->buildStructureInternal($playerId, $structure, 1);
                }
                if ($visitorCardOption == 3){
                    $this->plantInternal($playerId, $cardId, $cardKey, $field, $playersData, $tokens, $privateHandCards, false, true);
                    $this->insertPlayerAction($playerId, 'plant', 0, '', $visitorCardId, $visitorCardKey);
                }
                break;

            case 636: //Stonemason
                //Pay ${token_lira8} to build any 2 structures (ignore their regular costs)
                //payLira_8+buildStructure_2_free
                if ($lira<8){
                    throw new BgaUserException( self::_("Not enough lira!") );
                }
                $structures = array_unique(explode('_', $otherSelection));
                foreach ($structures as $structuresKey => $structuresValue) {
                    $this->buildStructureInternal($playerId, $structuresValue, 99);
                }

                $this->dbIncLira($playerId, -8);

                // Notify all players
                self::notifyAllPlayers( "playYellowCard", clienttranslate( '${player_name} pays ${token_price} to build any 2 structures' ), array(
                    'player_id' => $playerId,
                    'player_name' => $this->getPlayerName($playerId),
                    'token_price' => 'lira8'
                ) );
                break;

            case 637: //Volunteer Crew
                //All players may plant 1 ${token_greenCard}. Gain ${token_lira2} for each opponent who does this.
                //**special**
                if ($field>0){
                    $this->plantInternal($playerId, $cardId, $cardKey, $field, $playersData, $tokens, $privateHandCards, false, true);
                }
                $this->insertPlayerAction($playerId, 'allPlant', 0, '', $visitorCardId, $visitorCardKey);
                break;

            case 638: //Wedding Party
                //Pay up to 3 opponents ${token_lira2} each. Gain ${token_vp1} for each of those opponents.
                //**special**
                $playersSelected = array_unique(explode('_', $otherSelection));
                if (count($playersSelected)>3 || count($playersSelected)>count($playersData)-1){
                    throw new BgaUserException( self::_("Too many players selected!") );
                }
                foreach ($playersSelected as $playersSelectedKey => $playersSelectedValue) {
                    if ($playersSelectedValue==$playerId){
                        throw new BgaUserException( self::_("Wrong player selection!") );
                    }
                    $this->dbIncLira($playerId,-2);
                    $this->dbIncLira($playersSelectedValue,2);
                    $this->dbIncScore($playerId, 1, 'vit_scoring_yellow_card');
                    // Notify all players
                    self::notifyAllPlayers( "playYellowCard", clienttranslate( '${player_name} gives ${token_lira} to ${other_player_name} and gets ${token_get}' ), array(
                        'player_id' => $playerId,
                        'player_name' => $this->getPlayerName($playerId),
                        'token_lira' => 'lira2',
                        'other_player_name' => $this->getPlayerName($playersSelectedValue),
                        'token_get' => 'vp1'
                    ) );
                }
                break;

            default:
                throw new BgaUserException( self::_("Wrong card!") );

                break;
        }

        if ($this->checkPlayerActionStatusNewWithCard($visitorCardId)==false){
            $this->discardCardOnDeckTop($visitorCardId, $visitorCardKey);
        }

    }

    function playBlueCardInternal($playerId, $locationKey, $structure, $cardId, $cardKey,
            $field, $harvestFieldsId, $wine, $wineValue, $grapesId,
            $buyField, $sellField, $sellGrapesId,
            $uprootVinesId, $orderWinesId, $cardsSelectedId, $workersSelectedId, $otherSelection,
            $visitorCardId, $visitorCardKey, $visitorCardOption,
            $playersData, $tokens, $privateHandCards, $firstOption, $sendNotifications){

        $playerFullData = $playersData[$playerId];
        $cardHand = $this->arrayFindByProperty($privateHandCards[$playerId]['hand'],'i',$visitorCardId);
        if (!$cardHand){
            throw new BgaUserException( self::_("Card not found!") );
        }
        $card = $this->blueCards[$cardHand['k']];
        $playerWines = $playerFullData['wines'];
        $playerGrapes = $playerFullData['grapes'];
        $playerTokens = $this->playerTokens;
        $lira = (int)$playerFullData['lira'];
        $score = (int)$playerFullData['score'];
        $grapesBitArray = $this->playerGrapesToBitArray($playerFullData);

        $choices = $this->checkBlueCard($playerId, $playerFullData, $cardHand['t'], $cardHand['k'], $playersData, $tokens, $privateHandCards);
        $check = $choices&$visitorCardOption;
        if ($choices&$visitorCardOption == 0){
            throw new BgaUserException( self::_("Card option not valid!") );
        }

        $maintainCardInHand = false;

        // Notify all players
        self::notifyAllPlayers( "playBlueCard", clienttranslate( '${player_name} plays winter visitor card ${token_blueCard} ${cardName}' ), array(
            'player_id' => $playerId,
            'player_name' => $this->getPlayerName($playerId),
            'cardId' => $visitorCardId,
            'cardKey' => $visitorCardKey,
            'cardName' => $card['name'],
            'token_blueCard' => 'blueCard|'.$visitorCardKey,
            'i18n'=>array('cardName')
        ) );

        //Update player card played
        $this->DbQuery("UPDATE player SET card_played=$visitorCardKey WHERE player_id='$playerId'");
        if ($firstOption == true){
            $this->addCardPlayedToHistory($playerId, $visitorCardId, $visitorCardKey);
        }
        //updating data read
        $playerFullData['card_played'] = $visitorCardKey;
        $playersData[$playerId]['card_played'] = $visitorCardKey;

        switch ($card['key']) {
            case 801: //Merchant
                //Pay ${token_lira3} to place a ${token_grapeRed1} and a ${token_grapeWhite1}  on your crush pad OR fill 1 ${token_purpleCard} and gain ${token_vp1} extra.
                //payLira_3+getGrapeRed_1+getGrapeWhite_1|fillOrder_1+getVp_1
                if ($visitorCardOption == 1){
                    $this->dbIncLira($playerId, -3);

                    $notificationMessage =  clienttranslate( '${player_name} pays ${token_price} and gets :');
                    $notificationArgs = array(
                        'player_id' => $playerId,
                        'player_name' => $this->getPlayerName($playerId),
                        'token_price' => 'lira3',
                        'visitorCardId' => $visitorCardId,
                    );
                    $createGrapes = array();
                    if ($grapesBitArray['grapeRed'][1]==0){
                        $createGrapes[] = array('type' => 'grapeRed', 'type_arg' => 1, 'nbr' => 1);
                        $notificationMessage=$notificationMessage.'${token_grapeRed1}';
                        $notificationArgs['token_grapeRed1']='grapeRed1';
                    }
                    if ($grapesBitArray['grapeWhite'][1]==0){
                        $createGrapes[] = array('type' => 'grapeWhite', 'type_arg' => 1, 'nbr' => 1);
                        $notificationMessage=$notificationMessage.'${token_grapeWhite1}';
                        $notificationArgs['token_grapeWhite1']='grapeWhite1';
                    }
                    $this->cards->createCards( $createGrapes, 'playerGrapes', $playerId );

                    $playerFullData = $this->getPlayerFullData($playerId);

                    $notificationArgs['newGrapes'] = $playerFullData['grapes'];
                    // Notify all players
                    self::notifyAllPlayers( "playBlueCardNewGrapes", $notificationMessage,  $notificationArgs);
                }

                if ($visitorCardOption == 2){
                    $this->fillOrderInternal($playerId, $cardId, $cardKey, $orderWinesId, $playersData, $tokens, $privateHandCards , 1);
                    // Notify all players
                    self::notifyAllPlayers( "playBlueCard", clienttranslate( '${player_name} gets ${token_get}' ), array(
                        'player_id' => $playerId,
                        'player_name' => $this->getPlayerName($playerId),
                        'token_get' => 'vp1'
                    ) );
                }

                break;

            case 802: //Crusher
                //Gain ${token_lira3} and draw 1 ${token_yellowCard} OR draw 1 ${token_purpleCard} and make up to 2 ${token_wineAny}.
                //GetLira_3+drawYellowCard_1|drawPurpleCard_1+makeWine_2
                if ($visitorCardOption == 1){
                    $this->dbIncLira($playerId, 3);
                    // Notify all players
                    self::notifyAllPlayers( "playBlueCard", clienttranslate( '${player_name} gets ${token_get}' ), array(
                        'player_id' => $playerId,
                        'player_name' => $this->getPlayerName($playerId),
                        'token_get' => 'lira3'
                    ) );
                    $this->drawFromDeck($playerId, DECK_YELLOW, 1, true);
                }
                if ($visitorCardOption == 2){
                    $this->drawFromDeck($playerId, DECK_PURPLE, 1, true);
                    $this->insertPlayerAction($playerId, 'makeWine', 0, '', $visitorCardId, $visitorCardKey);
                    $this->insertPlayerAction($playerId, 'makeWine', 0, '', $visitorCardId, $visitorCardKey);
                }
                break;

            case 803: //Judge
                //Draw 2 ${token_yellowCardPlus} OR discard 1 ${token_wineAny} of value 4 or more to gain ${token_vp3}.
                //drawYellowCard_2|discardWineAny_1_value4+getVp_3
                if ($visitorCardOption == 1){
                    $this->drawFromDeck($playerId, DECK_YELLOW, 2, true);
                }

                if ($visitorCardOption == 2){
                    $wines = $this->readCardsByPlayerIdAndCardTypeAndCardTypeArg($playerId, $wine, $wineValue);
                    if (count($wines)==0){
                        throw new BgaUserException( self::_("Wine not found!") );
                    }
                    if ($wineValue<4){
                        throw new BgaUserException( self::_("Wrong wine!") );
                    }
                    $wineId = $wines[0]['id'];

                    $this->DbQuery("DELETE FROM card WHERE card_location='playerWines' AND card_location_arg = $playerId AND card_type like 'wine%' and card_id =$wineId");

                    $this->dbIncScore($playerId, 3, 'vit_scoring_blue_card');

                    $token_wine = $wine.$wineValue;
                    // Notify all players
                    self::notifyAllPlayers( "playBlueCard", clienttranslate( '${player_name} discards ${token_wine} and gets ${token_get}' ), array(
                        'player_id' => $playerId,
                        'player_name' => $this->getPlayerName($playerId),
                        'token_wine' => $token_wine,
                        'token_get' => 'vp3'
                    ) );
                }
                break;

            case 804: //Oenologist
                //Age all ${token_wineAny} in your cellar twice OR pay ${token_lira3} to upgrade your cellar to the next level.
                //ageWines_2|payLira_2+upgradeCellar
                if ($visitorCardOption == 1){
                    $this->ageWinesPlayer($playerId, $playerFullData);
                    $playerFullData = $this->getPlayerFullData($playerId);
                    $this->ageWinesPlayer($playerId, $playerFullData);
                    // Notify all players
                    self::notifyAllPlayers( "playBlueCard", clienttranslate( '${player_name} ages all ${token_wineAny} twice' ), array(
                        'player_id' => $playerId,
                        'player_name' => $this->getPlayerName($playerId),
                        'token_wineAny' => 'wineAny'
                    ) );
                }

                if ($visitorCardOption == 2){
                    $this->dbIncLira($playerId,-3);

                    // Notify all players
                    self::notifyAllPlayers( "playBlueCard", clienttranslate( '${player_name} pays ${token_price} to upgrade cellar' ), array(
                        'player_id' => $playerId,
                        'player_name' => $this->getPlayerName($playerId),
                        'token_price' => 'lira3'
                    ) );

                    if ($playerFullData['mediumCellar']==0){
                        $this->buildStructureInternal($playerId, 'mediumCellar', 99);
                    } else if ($playerFullData['largeCellar']==0){
                        $this->buildStructureInternal($playerId, 'largeCellar', 99);
                    } else {
                        throw new BgaUserException( self::_("Cannot upgrade cellar!") );
                    }

                }

                break;

            case 805: //Marketer
                //Draw 2 ${token_yellowCardPlus} and gain ${token_lira1} OR fill 1 ${token_purpleCard} and gain ${token_vp1} extra.
                //drawYellowCard_2+getLira_1|fillOrder_1+getVp_1
                if ($visitorCardOption == 1){
                    $this->dbIncLira($playerId, 1);
                    // Notify all players
                    self::notifyAllPlayers( "playBlueCard", clienttranslate( '${player_name} gets ${token_get}' ), array(
                        'player_id' => $playerId,
                        'player_name' => $this->getPlayerName($playerId),
                        'token_get' => 'lira1'
                    ) );
                    $this->drawFromDeck($playerId, DECK_YELLOW, 2, true);
                }
                if ($visitorCardOption == 2){
                    $this->fillOrderInternal($playerId, $cardId, $cardKey, $orderWinesId, $playersData, $tokens, $privateHandCards ,1);
                    // Notify all players
                    self::notifyAllPlayers( "playBlueCard", clienttranslate( '${player_name} gets ${token_get}' ), array(
                        'player_id' => $playerId,
                        'player_name' => $this->getPlayerName($playerId),
                        'token_get' => 'vp1'
                    ) );
                }
                break;

            case 806: //Crush Expert
                //Gain ${token_lira3} and draw 1 ${token_purpleCard} OR make up to 3 ${token_wineAny}.
                //getLira_3+drawPurpleCard|makeWine_3
                if ($visitorCardOption == 1){
                    $this->dbIncLira($playerId, 3);
                    // Notify all players
                    self::notifyAllPlayers( "playBlueCard", clienttranslate( '${player_name} gets ${token_get}' ), array(
                        'player_id' => $playerId,
                        'player_name' => $this->getPlayerName($playerId),
                        'token_get' => 'lira3'
                    ) );
                    $this->drawFromDeck($playerId, DECK_PURPLE, 1, true);
                }
                if ($visitorCardOption == 2){
                    $this->makeWineInternal($playerId, $wine, $wineValue, $grapesId, $playersData, $tokens, $privateHandCards ,true);
                    $this->insertPlayerAction($playerId, 'makeWine', 0, '', $visitorCardId, $visitorCardKey);
                    $this->insertPlayerAction($playerId, 'makeWine', 0, '', $visitorCardId, $visitorCardKey);
                }
                break;

            case 807: //Uncertified Teacher
                //Lose ${token_vp1} to train a ${token_worker} OR gain ${token_vp1} for each opponent who has a total of 6 ${token_worker}.
                //**special**
                if ($visitorCardOption == 1){
                    $workerLocation = $locationKey;
                    if ($workerLocation==0 || $workerLocation == ''){
                        $workerLocation = $this->getNewWorkerLocation($playerId);
                    } else {
                        if (array_key_exists($workerLocation, $this->boardLocations)){
                            $workerLocation = $this->boardLocations[$workerLocation]['sha'].'_new';
                        } else {
                            $workerLocation = $workerLocation.'_new';
                        }
                    }
                    $this->addWorker($playerId,'board_'.$workerLocation, 0, false);
                    $this->dbIncScore($playerId, -1, 'vit_scoring_blue_card');
                    // Notify all players
                    self::notifyAllPlayers( "playBlueCard", clienttranslate( '${player_name} pays ${token_lose} and trains a new ${token_worker}' ), array(
                        'player_id' => $playerId,
                        'player_name' => $this->getPlayerName($playerId),
                        'token_lose' => 'vp1',
                        'token_worker' => 'worker'
                    ) );
                }
                if ($visitorCardOption == 2){
                    $vps=0;
                    foreach ($playersData as $playersDataKey => $playersDataValue) {
                        //opponent
                        if ($playersDataKey != $playerId){
                            $workers = $this->readWorkers($playersDataKey);
                            if (count($workers)>=6){
                                $vps++;
                            }
                        }
                    }
                    $this->dbIncScore($playerId, $vps, 'vit_scoring_blue_card');
                    // Notify all players
                    self::notifyAllPlayers( "playBlueCard", clienttranslate( '${player_name} gets ${token_get}' ), array(
                        'player_id' => $playerId,
                        'player_name' => $this->getPlayerName($playerId),
                        'token_get' => 'vp'.$vps
                    ) );
                }
                break;

            case 808: //Teacher
                //Make up to 2 ${token_wineAny} OR pay ${token_lira2} to train 1 worker.
                //makeWine_2|trainWorker_1_price2
                if ($visitorCardOption == 1){
                    $this->makeWineInternal($playerId, $wine, $wineValue, $grapesId, $playersData, $tokens, $privateHandCards ,true);
                    $this->insertPlayerAction($playerId, 'makeWine', 0, '', $visitorCardId, $visitorCardKey);
                }
                if ($visitorCardOption == 2){
                    $workerLocation = $locationKey;
                    if ($workerLocation==0 || $workerLocation == ''){
                        $workerLocation = $this->getNewWorkerLocation($playerId);
                    } else {
                        if (array_key_exists($workerLocation, $this->boardLocations)){
                            $workerLocation = $this->boardLocations[$workerLocation]['sha'].'_new';
                        } else {
                            $workerLocation = $workerLocation.'_new';
                        }
                    }
                    $this->addWorker($playerId,'board_'.$workerLocation, 0, false);
                    $this->dbIncLira($playerId, -2);
                    // Notify all players
                    self::notifyAllPlayers( "playBlueCard", clienttranslate( '${player_name} pays ${token_lose} and trains a new ${token_worker}' ), array(
                        'player_id' => $playerId,
                        'player_name' => $this->getPlayerName($playerId),
                        'token_lose' => 'lira2',
                        'token_worker' => 'worker'
                    ) );
                }
                break;

            case 809: //Benefactor
                //Draw 1 ${token_greenCard} and 1 ${token_yellowCard} card OR discard 2 visitor cards to gain ${token_vp2}.
                //drawGreenCard+drawYellowCard|discardCard_2+get2Vp
                if ($visitorCardOption == 1){
                    $this->drawFromDeck($playerId, DECK_GREEN, 1, true);
                    $this->drawFromDeck($playerId, DECK_YELLOW, 1, true);
                }
                if ($visitorCardOption == 2){
                    $cardsSelectedIdJoin = implode(',',$cardsSelectedId);
                    $sql = "SELECT card_id id, card_type type, card_type_arg type_arg FROM card WHERE card_location='hand' AND card_location_arg = $playerId AND card_type in ('yellowCard','blueCard') and card_type_arg != 809 and card_id in (${cardsSelectedIdJoin}) and card_id not in (select pa.card_id from player_action pa where pa.card_id is not null)";
                    $cardsSelected = self::getObjectListFromDB( $sql);

                    if (count($cardsSelected) != count($cardsSelectedId)){
                        throw new BgaUserException( self::_("Cards not valid!") );
                    }

                    $this->discardCardsInternal($playerId, $cardsSelectedId, 2);

                    $this->dbIncScore($playerId, 2, 'vit_scoring_blue_card');

                    $token_wine = $wine.$wineValue;
                    // Notify all players
                    self::notifyAllPlayers( "playBlueCard", clienttranslate( '${player_name} discards 2 ${token_anyCard} and gets ${token_get}' ), array(
                        'player_id' => $playerId,
                        'player_name' => $this->getPlayerName($playerId),
                        'token_anyCard' => 'anyCard',
                        'token_get' => 'vp2'
                    ) );
                }
                break;

            case 810: //Assessor
                //Gain ${token_lira1} for each card in your hand OR discard your hand (min of 1 card) to gain ${token_vp2}.
                //**special**
                if ($visitorCardOption == 1){
                    $lira = 0;
                    $hand = $privateHandCards[$playerId]['hand'];
                    foreach ($hand as $handKey => $handValue) {
                        if ($handValue['i']!=$visitorCardId){
                            $lira++;
                        }
                    }
                    $this->dbIncLira($playerId, $lira);
                    // Notify all players
                    self::notifyAllPlayers( "playBlueCard", clienttranslate( '${player_name} gets ${token_get}' ), array(
                        'player_id' => $playerId,
                        'player_name' => $this->getPlayerName($playerId),
                        'token_get' => 'lira'.$lira
                    ) );
                }
                if ($visitorCardOption == 2){
                    $cards = 0;
                    $cardsToDiscard = array();
                    $hand = $privateHandCards[$playerId]['hand'];
                    foreach ($hand as $handKey => $handValue) {
                        if ($handValue['i']!=$visitorCardId){
                            $cards++;
                            $cardsToDiscard[]=$handValue['i'];
                        }
                    }

                    $this->discardCardsInternal($playerId, $cardsToDiscard, $cards);

                    $this->dbIncScore($playerId, 2, 'vit_scoring_blue_card');

                    // Notify all players
                    self::notifyAllPlayers( "playBlueCard", clienttranslate( '${player_name} discards all cards and gets ${token_get}' ), array(
                        'player_id' => $playerId,
                        'player_name' => $this->getPlayerName($playerId),
                        'token_get' => 'vp2'
                    ) );
                }
                break;

            case 811: //Queen
                //The player on your right must choose 1: lose ${token_vp1}, give you 2 ${token_anyCard}, or pay you ${token_lira3}.
                //**special**
                $previousPlayerId = $this->getPreviousPlayer($playerId, true);
                $this->insertPlayerAction($previousPlayerId, 'chooseOptions', 0, $playerId, $visitorCardId, $visitorCardKey);
                break;

            case 812: //Harvester
                //Harvest up to 2 fields and choose 1: Gain ${token_lira2} or gain ${token_vp1}.
                //harvestField_2+getLira_2|harvestField_2+getVp_1
                if ($visitorCardOption == 1){
                    $this->harvestFieldInternal($playerId, $harvestFieldsId, $playersData, $tokens, $privateHandCards, 2, null);
                    $this->dbIncLira($playerId, 2);
                    // Notify all players
                    self::notifyAllPlayers( "playBlueCard", clienttranslate( '${player_name} gets ${token_get}' ), array(
                        'player_id' => $playerId,
                        'player_name' => $this->getPlayerName($playerId),
                        'token_get' => 'lira2'
                    ) );
                }
                if ($visitorCardOption == 2){
                    $this->harvestFieldInternal($playerId, $harvestFieldsId, $playersData, $tokens, $privateHandCards, 2, null);
                    $this->dbIncScore($playerId, 1, 'vit_scoring_blue_card');
                    // Notify all players
                    self::notifyAllPlayers( "playBlueCard", clienttranslate( '${player_name} gets ${token_get}' ), array(
                        'player_id' => $playerId,
                        'player_name' => $this->getPlayerName($playerId),
                        'token_get' => 'vp1'
                    ) );
                }
                break;

            case 813: //Professor
                //Pay ${token_lira2} to train 1 ${token_worker} OR gain ${token_vp2} if you have a total of 6 ${token_worker}.
                //**special**
                if ($visitorCardOption == 1){
                    $workerLocation = $locationKey;
                    if ($workerLocation==0 || $workerLocation == ''){
                        $workerLocation = $this->getNewWorkerLocation($playerId);
                    } else {
                        if (array_key_exists($workerLocation, $this->boardLocations)){
                            $workerLocation = $this->boardLocations[$workerLocation]['sha'].'_new';
                        } else {
                            $workerLocation = $workerLocation.'_new';
                        }
                    }
                    $this->addWorker($playerId,'board_'.$workerLocation, 0, false);
                    $this->dbIncLira($playerId, -2);
                    // Notify all players
                    self::notifyAllPlayers( "playBlueCard", clienttranslate( '${player_name} pays ${token_lose} and trains a new ${token_worker}' ), array(
                        'player_id' => $playerId,
                        'player_name' => $this->getPlayerName($playerId),
                        'token_lose' => 'lira2',
                        'token_worker' => 'worker'
                    ) );
                }
                if ($visitorCardOption == 2){
                    $workers = $this->readWorkers($playerId);
                    if (count($workers)>=6){
                        $this->dbIncScore($playerId, 2, 'vit_scoring_blue_card');
                        // Notify all players
                        self::notifyAllPlayers( "playBlueCard", clienttranslate( '${player_name} gets ${token_get}' ), array(
                            'player_id' => $playerId,
                            'player_name' => $this->getPlayerName($playerId),
                            'token_get' => 'vp2'
                        ) );
                    }
                }
                break;

            case 814: //Master Vintner
                //Upgrade your cellar to the next level at a ${token_lira2} discount OR age 1 ${token_wineAny} and fill 1 ${token_purpleCard}.
                //upgradeCellar_discount2|ageWine1+fillOrder_1
                if ($visitorCardOption == 1){
                    if ($playerFullData['mediumCellar']==0){
                        $this->buildStructureInternal($playerId, 'mediumCellar', 2);
                    } else if ($playerFullData['largeCellar']==0){
                        $this->buildStructureInternal($playerId, 'largeCellar', 2);
                    } else {
                        throw new BgaUserException( self::_("Cannot upgrade cellar!") );
                    }

                    // Notify all players
                    self::notifyAllPlayers( "playBlueCard", clienttranslate( '${player_name} upgrades cellar at ${token_lira} discount' ), array(
                        'player_id' => $playerId,
                        'player_name' => $this->getPlayerName($playerId),
                        'token_lira' => 'lira2'
                    ) );
                }
                if ($visitorCardOption == 2){
                    if ($wine!=''){
                        $this->ageWinesPlayer($playerId, $playerFullData, $wine, $wineValue);
                        // Notify all players
                        self::notifyAllPlayers( "playBlueCard", clienttranslate( '${player_name} ages ${token_wine}' ), array(
                            'player_id' => $playerId,
                            'player_name' => $this->getPlayerName($playerId),
                            'token_wine' => $wine.$wineValue
                        ) );
                    }

                    //check purple cards/fill order
                    $playerFullData = $this->getPlayerFullData($playerId);
                    $tokens = $this->readTokens();
                    $privateHandCards = $this->readPlayersPrivateHand($playersData);
                    $purpleCards = $this->readPossiblePurpleCards($playerId, $playerFullData, $tokens, $privateHandCards, false);
                    if (count($purpleCards)>0){
                        $this->insertPlayerAction($playerId, 'fillOrder', 0, '', $visitorCardId, $visitorCardKey);
                    }
                }
                break;

            case 815: //Uncertified Oenologist
                //Age all ${token_wineAny} in your cellar twice OR lose ${token_vp1} to upgrade your cellar to the next level.
                //ageWines_2|payLVp_1+upgradeCellar
                if ($visitorCardOption == 1){
                    $this->ageWinesPlayer($playerId, $playerFullData);
                    $playerFullData = $this->getPlayerFullData($playerId);
                    $this->ageWinesPlayer($playerId, $playerFullData);
                    // Notify all players
                    self::notifyAllPlayers( "playBlueCard", clienttranslate( '${player_name} ages all ${token_wineAny} twice' ), array(
                        'player_id' => $playerId,
                        'player_name' => $this->getPlayerName($playerId),
                        'token_wineAny' => 'wineAny'
                    ) );
                }

                if ($visitorCardOption == 2){
                    $this->dbIncScore($playerId, -1, 'vit_scoring_blue_card');

                    if ($playerFullData['mediumCellar']==0){
                        $this->buildStructureInternal($playerId, 'mediumCellar', 99);
                    } else if ($playerFullData['largeCellar']==0){
                        $this->buildStructureInternal($playerId, 'largeCellar', 99);
                    } else {
                        throw new BgaUserException( self::_("Cannot upgrade cellar!") );
                    }

                    // Notify all players
                    self::notifyAllPlayers( "playBlueCard", clienttranslate( '${player_name} pays ${token_price} to upgrade cellar' ), array(
                        'player_id' => $playerId,
                        'player_name' => $this->getPlayerName($playerId),
                        'token_price' => 'vp1'
                    ) );
                }
                break;

            case 816: //Promoter
                //Discard a ${token_grapeAny} or ${token_wineAny} to gain ${token_vp1} and ${token_residualPayment1}.
                //discardGrapeAny_1+getVp_1+getResidualPayment_1|discardWineAny_1+getVp_1+getResidualPayment_1|
                if ($visitorCardOption == 1){
                    $this->dbIncScore($playerId, 1, 'vit_scoring_blue_card');
                    $this->dbIncResidualPayment($playerId, 1);

                    $grapesIdJoin = implode(',',$grapesId);
                    $sql = "SELECT card_id id, card_type type, card_type_arg type_arg FROM card WHERE card_location='playerGrapes' AND card_location_arg = $playerId AND card_type like 'grape%' and card_id in (${grapesIdJoin})";
                    $grapes = self::getObjectListFromDB( $sql);
                    $tokenGrape = $grapes[0]['type'].$grapes[0]['type_arg'];
                    if (count($grapes) != 1){
                        throw new BgaUserException( self::_("Grapes not valid!") );
                    }

                    $this->DbQuery("DELETE FROM card WHERE card_location='playerGrapes' AND card_location_arg = $playerId AND card_type like 'grape%' and card_id in (${grapesIdJoin})");

                    $playerFullData = $this->getPlayerFullData($playerId);

                    // Notify all players
                    self::notifyAllPlayers( "playBlueCardDiscardGrapes", clienttranslate( '${player_name} discards ${token_grape} and gets ${token_get} and  ${token_residualPayment}' ), array(
                        'player_id' => $playerId,
                        'player_name' => $this->getPlayerName($playerId),
                        'token_residualPayment' => 'residualPayment1',
                        'token_get' => 'vp1',
                        'token_grape' => $tokenGrape,
                        'visitorCardId' => $visitorCardId,
                        'newGrapes' => $playerFullData['grapes']
                    ) );
                }
                if ($visitorCardOption == 2){
                    $this->dbIncScore($playerId, 1, 'vit_scoring_blue_card');
                    $this->dbIncResidualPayment($playerId, 1);

                    $wines = $this->readCardsByPlayerIdAndCardTypeAndCardTypeArg($playerId, $wine, $wineValue);
                    if (count($wines)==0){
                        throw new BgaUserException( self::_("Wine not found!") );
                    }
                    $wineId = $wines[0]['id'];

                    $this->DbQuery("DELETE FROM card WHERE card_location='playerWines' AND card_location_arg = $playerId AND card_type like 'wine%' and card_id =$wineId");

                    // Notify all players
                    self::notifyAllPlayers( "playBlueCard", clienttranslate( '${player_name} discards ${token_wine} and gets ${token_get} and  ${token_residualPayment}' ), array(
                        'player_id' => $playerId,
                        'player_name' => $this->getPlayerName($playerId),
                        'token_wine' => $wine.$wineValue,
                        'token_get' => 'vp1',
                        'token_residualPayment' => 'residualPayment1'
                    ) );
                }
                break;

            case 817: //Mentor
                //All players may make up to 2 ${token_wineAny}. Draw 1 ${token_greenCardPlus} or ${token_YellowCardPlus} card for each opponent who does this.
                //**special**

                $actionOrder=0;
                //if player makes wine
                if ($wine != 'NO'){
                    $this->makeWineInternal($playerId, $wine, $wineValue, $grapesId, $playersData, $tokens, $privateHandCards ,true);
                    $this->insertPlayerAction($playerId, 'makeWine', $actionOrder++, '', $visitorCardId, $visitorCardKey);
                } else {
                    // Notify all players
                    self::notifyAllPlayers( "playBlueCard", clienttranslate( '${player_name} makes no wines' ), array(
                        'player_id' => $playerId,
                        'player_name' => $this->getPlayerName($playerId)
                    ) );
                }

                //sort by playOrder
                foreach ($playersData as $playersDataKey => $playersDataValue) {
                    $playersData[$playersDataKey]['sort']=$playersDataValue['playorder'];
                    if ($playersDataValue['playorder'] < $playerFullData['playorder']){
                        $playersData[$playersDataKey]['sort']=$playersDataValue['playorder']+10;
                    }
                }
                $playersData = $this->arrayOrderBy($playersData,"sort", true, true);

                //give other players two action makewine
                foreach ($playersData as $playersDataKey => $playersDataValue) {
                    if ($playersDataKey!=$playerId){
                        $wines = $this->checkActionMakeWine($playersDataKey, $playersDataValue, $privateHandCards, $tokens, true, false, 1);
                        if ($wines>0){
                            $this->insertPlayerAction($playersDataKey, 'makeWine', $actionOrder++, $playerId, $visitorCardId, $visitorCardKey);
                            $this->insertPlayerAction($playersDataKey, 'makeWine', $actionOrder++, $playerId, $visitorCardId, $visitorCardKey);
                        }
                    }
                }
                break;

            case 818: //Harvest Expert
                //Harvest 1 field and either draw 1 ${token_greenCardPlus} or pay ${token_lira1} to build a Yoke.
                //harvestField_1+drawGreenCard_1|harvestField_1+buildStructure_1_yoke_price1
                if ($visitorCardOption == 1){
                    $this->harvestFieldInternal($playerId, $harvestFieldsId, $playersData, $tokens, $privateHandCards, 1, null);
                    $this->drawFromDeck($playerId, DECK_GREEN, 1, true);
                }
                if ($visitorCardOption == 2){
                    $this->harvestFieldInternal($playerId, $harvestFieldsId, $playersData, $tokens, $privateHandCards, 1, null);
                    $yoke = $this->arrayFindByProperty($this->playerTokens, 'type', 'yoke');
                    $this->buildStructureInternal($playerId, 'yoke', $yoke['price']-1);
                }
                break;

            case 819: //Innkeeper
                //As you play this card, put the top card of 2 different discard piles in your hand.
                //GetDiscardCard_2
                if (count($cardsSelectedId)!=2){
                    throw new BgaUserException( self::_("Wrong number of cards!") );
                }
                $topDiscardDeck = $this->readTopDiscardDeck();
                foreach ($cardsSelectedId as $cardsSelectedIdKey => $cardsSelectedIdValue) {
                    $found = false;
                    foreach ($topDiscardDeck as $topDiscardDeckKey => $topDiscardDeckValue) {
                        if ($topDiscardDeckValue['i']==$cardsSelectedIdValue){
                            $found = true;
                        }
                    }
                    if (!$found){
                        throw new BgaUserException( self::_("Wrong cards!") );
                    }
                }

                foreach ($cardsSelectedId as $cardsSelectedIdKey => $cardsSelectedIdValue) {
                    $this->cards->moveCard( $cardsSelectedIdValue, 'hand', $playerId );
                }

                $this->notifyPlayer($playerId, 'updateDeck','',array(
                    'hand'=>$this->readPlayerHand($playerFullData),
                    'origin'=>'discard',
                    'target'=>'discard'
                ));

                break;

            case 820: //Jack-of-all-trades
                //Choose 2: Harvest 1 field, make up to 2 ${token_wineAny}, or fill 1 ${token_purpleCard}.
                //HarvestField_1|makeWine_2|fillOrder_1
                if ($visitorCardOption == 1){
                    $this->harvestFieldInternal($playerId, $harvestFieldsId, $playersData, $tokens, $privateHandCards, 1, null);
                }
                if ($visitorCardOption == 2){
                    $this->makeWineInternal($playerId, $wine, $wineValue, $grapesId, $playersData, $tokens, $privateHandCards ,true);
                    $this->insertPlayerAction($playerId, 'makeWine', 0, '', $visitorCardId, $visitorCardKey);
                }
                if ($visitorCardOption == 3){
                    $this->fillOrderInternal($playerId, $cardId, $cardKey, $orderWinesId, $playersData, $tokens, $privateHandCards ,0);
                }

                $playersFullData = $this->getPlayersFullData();

                $maintainCardInHand = $this->checkAndInsertSecondCardOptionAction($playerId, $visitorCardId, $visitorCardKey, $visitorCardOption, 0, $playersFullData, $tokens, $privateHandCards, true);

                break;

            case 821: //Politician
                //If you have less than 0${token_vp}, gain ${token_lira6}. Otherwise, draw 1 ${token_greenCardPlus}, 1 ${token_yellowCardPlus}, and 1 ${token_purpleCardPlus}.
                //**special**
                if ($score<0){
                    $this->dbIncLira($playerId, 6);
                    // Notify all players
                    self::notifyAllPlayers( "playBlueCard", clienttranslate( '${player_name} gets ${token_get}' ), array(
                        'player_id' => $playerId,
                        'player_name' => $this->getPlayerName($playerId),
                        'token_get' => 'lira6'
                    ) );
                } else {
                    $this->drawFromDeck($playerId, DECK_GREEN, 1, true);
                    $this->drawFromDeck($playerId, DECK_YELLOW, 1, true);
                    $this->drawFromDeck($playerId, DECK_PURPLE, 1, true);
                }
                break;

            case 822: //Supervisor
                //Make up to 2 ${token_wineAny}. Gain${token_vp1} for each sparkling wine token you make.
                //makeWine_2_ifmakesparklingwineeach_1vp
                //NOTE: 1vp is added in makeWineInternal based on played_card of player
                $this->makeWineInternal($playerId, $wine, $wineValue, $grapesId, $playersData, $tokens, $privateHandCards ,true);
                $this->insertPlayerAction($playerId, 'makeWine', 0, '', $visitorCardId, $visitorCardKey);
                break;

            case 823: //Scholar
                //Draw 2 ${token_purpleCard} OR pay ${token_lira3} to train 1 ${token_worker}. You may lose ${token_vp1} to do both.
                //drawPurpleCard_2|trainWorker_1_price1
                if ($firstOption==false){
                    $this->dbIncScore($playerId,-1, 'vit_scoring_blue_card');
                    // Notify all players
                    self::notifyAllPlayers( "playBlueCard", clienttranslate( '${player_name} loses ${token_price} to do second option of visitor card' ), array(
                        'player_id' => $playerId,
                        'player_name' => $this->getPlayerName($playerId),
                        'token_price' => 'vp1'
                    ) );
                }
                if ($visitorCardOption == 1){
                    $this->drawFromDeck($playerId, DECK_PURPLE, 2, true);
                }
                if ($visitorCardOption == 2){
                    $this->dbIncLira($playerId, -3);
                    // Notify all players
                    self::notifyAllPlayers( "playBlueCard", clienttranslate( '${player_name} pays ${token_lose} and trains a new ${token_worker}' ), array(
                        'player_id' => $playerId,
                        'player_name' => $this->getPlayerName($playerId),
                        'token_lose' => 'lira3',
                        'token_worker' => 'worker'
                    ) );
                    $workerLocation = $locationKey;
                    if ($workerLocation==0 || $workerLocation == ''){
                        $workerLocation = $this->getNewWorkerLocation($playerId);
                    } else {
                        if (array_key_exists($workerLocation, $this->boardLocations)){
                            $workerLocation = $this->boardLocations[$workerLocation]['sha'].'_new';
                        } else {
                            $workerLocation = $workerLocation.'_new';
                        }
                    }
                    $this->addWorker($playerId,'board_'.$workerLocation, 0, false);
                }

                $playersFullData = $this->getPlayersFullData();

                $maintainCardInHand = $this->checkAndInsertSecondCardOptionAction($playerId, $visitorCardId, $visitorCardKey, $visitorCardOption, 1, $playersFullData, $tokens, $privateHandCards, false);

                break;

            case 824: //Reaper
                //Harvest up to 3 fields. If you harvest 3 fields, gain ${token_vp2}.
                //harvestField_3_ifharvested3fields_2vp
                $this->harvestFieldInternal($playerId, $harvestFieldsId, $playersData, $tokens, $privateHandCards, 3, null);
                if (count($harvestFieldsId)==3){
                    $this->dbIncScore($playerId, 2, 'vit_scoring_blue_card');
                    // Notify all players
                    self::notifyAllPlayers( "playBlueCard", clienttranslate( '${player_name} gets ${token_get}' ), array(
                        'player_id' => $playerId,
                        'player_name' => $this->getPlayerName($playerId),
                        'token_get' => 'vp2'
                    ) );
                }
                break;

            case 825: //Motivator
                //Each player may retrieve their grande worker. Gain ${token_vp1} for each opponent who does this.
                //**special**
                if ($otherSelection=='1'){
                    $worker = $this->readCardsByPlayerIdAndCardType($playerId, 'worker_g');
                    if ($worker[0]['location']=='player'){
                        throw new BgaUserException( self::_("You cannot retrieve worker!") );
                    }
                    $this->DbQuery("UPDATE card SET card_location='player' where card_type='worker_g' and card_location_arg=$playerId");
    
                    // Notify all players
                    self::notifyAllPlayers( "playBlueCard", clienttranslate( '${player_name} retrieves ${token_worker}' ), array(
                        'player_id' => $playerId,
                        'player_name' => $this->getPlayerName($playerId),
                        'token_worker' => 'workerGrande'
                    ) );    
                }

                $this->insertPlayerAction($playerId, 'allChoose', 0, '', $visitorCardId, $visitorCardKey);

                break;

            case 826: //Bottler
                //Make up to 3 ${token_wineAny}. Gain ${token_vp1} for each type of wine you make.
                //makeWine_3_ifdistincttype_get1vp
                //NOTE: 1vp is added in makeWineInternal based on played_card of player
                $this->DbQuery("DELETE FROM card WHERE card_location='card_flags' AND card_location=826");
                $this->makeWineInternal($playerId, $wine, $wineValue, $grapesId, $playersData, $tokens, $privateHandCards ,true);
                $this->insertPlayerAction($playerId, 'makeWine', 0, '', $visitorCardId, $visitorCardKey);
                $this->insertPlayerAction($playerId, 'makeWine', 0, '', $visitorCardId, $visitorCardKey);
                break;

            case 827: //Craftsman
                //Choose 2: Draw 1 ${token_purpleCard}, upgrade your cellar at the regular cost, or gain ${token_vp1}.
                //drawPurpleCard_1|upgradeCellar|getVp_1
                if ($visitorCardOption == 1){
                    $this->drawFromDeck($playerId, DECK_PURPLE, 1, true);
                }
                if ($visitorCardOption == 2){
                    if ($playerFullData['mediumCellar']==0){
                        $this->buildStructureInternal($playerId, 'mediumCellar', 0);
                    } else if ($playerFullData['largeCellar']==0){
                        $this->buildStructureInternal($playerId, 'largeCellar', 0);
                    } else {
                        throw new BgaUserException( self::_("Cannot upgrade cellar!") );
                    }
                }
                if ($visitorCardOption == 3){
                    $this->dbIncScore($playerId, 1, 'vit_scoring_blue_card');
                    // Notify all players
                    self::notifyAllPlayers( "playBlueCard", clienttranslate( '${player_name} gets ${token_get}' ), array(
                        'player_id' => $playerId,
                        'player_name' => $this->getPlayerName($playerId),
                        'token_get' => 'vp1'
                    ) );
                }

                $playersFullData = $this->getPlayersFullData();

                $maintainCardInHand = $this->checkAndInsertSecondCardOptionAction($playerId, $visitorCardId, $visitorCardKey, $visitorCardOption, 0, $playersFullData, $tokens, $privateHandCards, false);

                break;

            case 828: //Exporter
                //Choose 1: Make up to 2 ${token_wineAny}, fill 1 ${token_purpleCard}, or discard 1 ${token_grapeAny} to gain ${token_vp2}.
                //makeWine_2|fillOrder_1|discardGrapeAny+getVp_2
                if ($visitorCardOption == 1){
                    $this->makeWineInternal($playerId, $wine, $wineValue, $grapesId, $playersData, $tokens, $privateHandCards ,true);
                    $this->insertPlayerAction($playerId, 'makeWine', 0, '', $visitorCardId, $visitorCardKey);
                }
                if ($visitorCardOption == 2){
                    $this->fillOrderInternal($playerId, $cardId, $cardKey, $orderWinesId, $playersData, $tokens, $privateHandCards ,0);
                }
                if ($visitorCardOption == 3){
                    $this->dbIncScore($playerId, 2, 'vit_scoring_blue_card');

                    $grapesIdJoin = implode(',',$grapesId);
                    $sql = "SELECT card_id id, card_type type, card_type_arg type_arg FROM card WHERE card_location='playerGrapes' AND card_location_arg = $playerId AND card_type like 'grape%' and card_id in (${grapesIdJoin})";
                    $grapes = self::getObjectListFromDB( $sql);
                    $tokenGrape = $grapes[0]['type'].$grapes[0]['type_arg'];
                    if (count($grapes) != 1){
                        throw new BgaUserException( self::_("Grapes not valid!") );
                    }

                    $this->DbQuery("DELETE FROM card WHERE card_location='playerGrapes' AND card_location_arg = $playerId AND card_type like 'grape%' and card_id in (${grapesIdJoin})");

                    $playerFullData = $this->getPlayerFullData($playerId);

                    // Notify all players
                    self::notifyAllPlayers( "playBlueCardDiscardGrapes", clienttranslate( '${player_name} discards ${token_grape} and gets ${token_get}' ), array(
                        'player_id' => $playerId,
                        'player_name' => $this->getPlayerName($playerId),
                        'token_get' => 'vp2',
                        'token_grape' => $tokenGrape,
                        'visitorCardId' => $visitorCardId,
                        'newGrapes' => $playerFullData['grapes']
                    ) );
                }
                break;

            case 829: //Laborer
                //Harvest up to 2 fields OR make up to 3 ${token_wineAny}. You may lose ${token_vp1} to do both.
                //harvestField_2|makeWine_3
                if ($firstOption == false){
                    $this->dbIncScore($playerId,-1, 'vit_scoring_blue_card');
                    // Notify all players
                    self::notifyAllPlayers( "playBlueCard", clienttranslate( '${player_name} loses ${token_price} to do second option of visitor card' ), array(
                        'player_id' => $playerId,
                        'player_name' => $this->getPlayerName($playerId),
                        'token_price' => 'vp1'
                    ) );
                }
                if ($visitorCardOption == 1){
                    $this->harvestFieldInternal($playerId, $harvestFieldsId, $playersData, $tokens, $privateHandCards, 2, null);
                }
                if ($visitorCardOption == 2){
                    $this->makeWineInternal($playerId, $wine, $wineValue, $grapesId, $playersData, $tokens, $privateHandCards ,true);
                    $this->insertPlayerAction($playerId, 'makeWine', 0, '', $visitorCardId, $visitorCardKey);
                    $this->insertPlayerAction($playerId, 'makeWine', 0, '', $visitorCardId, $visitorCardKey);
                }

                $playersFullData = $this->getPlayersFullData();

                $maintainCardInHand = $this->checkAndInsertSecondCardOptionAction($playerId, $visitorCardId, $visitorCardKey, $visitorCardOption, 1, $playersFullData, $tokens, $privateHandCards, false);

                break;

            case 830: //Designer
                //Build 1 structure at its regular cost. Then, if you have at least 6 structures, gain ${token_vp2}.
                //buildStructure_1_ifstructuturesgt_6_vp2
                $this->buildStructureInternal($playerId,  $structure, 0);
                $playerFullData = $this->getPlayerFullData($playerId);
                $structures=0;
                foreach ($playerTokens as $playerTokensKey => $playerTokensValue) {
                    if ($playerTokensValue['isBuilding'] && $playerFullData[$playerTokensValue['type']]== 1){
                        $structures++;
                    }
                }
                if ($structures>=6){
                    $this->dbIncScore($playerId, 2, 'vit_scoring_blue_card');
                    // Notify all players
                    self::notifyAllPlayers( "playBlueCard", clienttranslate( '${player_name} gets ${token_get}' ), array(
                        'player_id' => $playerId,
                        'player_name' => $this->getPlayerName($playerId),
                        'token_get' => 'vp2'
                    ) );
                }
                break;

            case 831: //Governess
                //Pay ${token_lira3} to train 1 ${token_worker} that you may use this year OR discard 1 ${token_wineAny} to gain ${token_vp2}.
                //**special**
                if ($visitorCardOption == 1){
                    $this->addWorker($playerId,'player', 0, false);
                    $this->dbIncLira($playerId, -3);
                    // Notify all players
                    self::notifyAllPlayers( "playBlueCard", clienttranslate( '${player_name} pays ${token_lose} and trains a new ${token_worker} that they can use this year' ), array(
                        'player_id' => $playerId,
                        'player_name' => $this->getPlayerName($playerId),
                        'token_lose' => 'lira3',
                        'token_worker' => 'worker'
                    ) );
                }
                if ($visitorCardOption == 2){
                    $this->dbIncScore($playerId, 2, 'vit_scoring_blue_card');

                    $wines = $this->readCardsByPlayerIdAndCardTypeAndCardTypeArg($playerId, $wine, $wineValue);
                    if (count($wines)==0){
                        throw new BgaUserException( self::_("Wine not found!") );
                    }
                    $wineId = $wines[0]['id'];

                    $this->DbQuery("DELETE FROM card WHERE card_location='playerWines' AND card_location_arg = $playerId AND card_type like 'wine%' and card_id =$wineId");

                    // Notify all players
                    self::notifyAllPlayers( "playBlueCard", clienttranslate( '${player_name} discards ${token_wine} and gets ${token_get} ' ), array(
                        'player_id' => $playerId,
                        'player_name' => $this->getPlayerName($playerId),
                        'token_wine' => $wine.$wineValue,
                        'token_get' => 'vp2'
                    ) );
                }
                break;

            case 832: //Manager
                //Take any action (no bonus) from a previous season without placing a worker.
                //**special**
                $this->insertPlayerAction($playerId, 'takeActionPrev', 0, $locationKey, $visitorCardId, $visitorCardKey);
                break;

            case 833: //Zymologist
                //Make up to 2 ${token_wineAny} of value 4 or greater, even if you haven't upgraded your cellar.
                //makeWine_2_value4withouthmediumcellar
                $this->makeWineInternal($playerId, $wine, $wineValue, $grapesId, $playersData, $tokens, $privateHandCards ,false);
                $this->insertPlayerAction($playerId, 'makeWine', 0, '', $visitorCardId, $visitorCardKey);
                break;

            case 834: //Noble
                //Pay ${token_lira1} to gain ${token_residualPayment1} OR lose ${token_residualPayment2} to gain ${token_vp2}.
                //payLira_1+getResidualPayment_1|payResidualPayment_2+getVp_2
                if ($visitorCardOption == 1){
                    $this->dbIncLira($playerId, -1);
                    $this->dbIncResidualPayment($playerId, 1);

                    // Notify all players
                    self::notifyAllPlayers( "playYellowCard", clienttranslate( '${player_name} pays ${token_price} and gets ${token_get}' ), array(
                        'player_id' => $playerId,
                        'player_name' => $this->getPlayerName($playerId),
                        'token_price' => 'lira1',
                        'token_get' => 'residualPayment1'
                    ) );
                }
                if ($visitorCardOption == 2){
                    $this->dbIncResidualPayment($playerId, -2);
                    $this->dbIncScore($playerId, 2, 'vit_scoring_blue_card');

                    // Notify all players
                    self::notifyAllPlayers( "playYellowCard", clienttranslate( '${player_name} pays ${token_price} and gets ${token_get}' ), array(
                        'player_id' => $playerId,
                        'player_name' => $this->getPlayerName($playerId),
                        'token_price' => 'residualPayment2',
                        'token_get' => 'vp2'
                    ) );
                }
                break;

            case 835: //Governor
                //Choose up to 3 opponents to each give you 1 ${token_yellowCard}. Gain ${token_vp1} for each of them who cannot.
                //**special**
                $playersSelected = array_unique(explode('_', $otherSelection));
                if (count($playersSelected)>3 || count($playersSelected)>count($playersData)-1){
                    throw new BgaUserException( self::_("Too many players selected!") );
                }
                $playersGiveCards=array();

                foreach ($playersSelected as $playersSelectedKey => $playersSelectedValue) {
                    if ($playersSelectedValue==$playerId){
                        throw new BgaUserException( self::_("Wrong player selection!") );
                    }
                    //no cards
                    if ($playersData[$playersSelectedValue]['yellowCard']==0){
                        $this->dbIncScore($playerId, 1, 'vit_scoring_blue_card');

                        // Notify all players
                        self::notifyAllPlayers( "playBlueCard", clienttranslate( '${player_name} gets ${token_get} because ${other_player_name} has no ${token_yellowCard} to give' ), array(
                            'player_id' => $playerId,
                            'player_name' => $this->getPlayerName($playerId),
                            'other_player_name' => $this->getPlayerName($playersSelectedValue),
                            'token_yellowCard'=>'yellowCard',
                            'token_get' => 'vp1'
                        ) );
                    }
                    //one card... gives it automatically
                    if ($playersData[$playersSelectedValue]['yellowCard']==1){
                        $count=0;
                        $cardId=0;
                        $hand = $privateHandCards[$playersSelectedValue]['hand'];
                        foreach ($hand as $handKey => $handValue) {
                            if ($handValue['t']=='yellowCard'){
                                $count++;
                                $cardId = $handValue['i'];
                            }
                        }
                        if ($count!=1){
                            throw new BgaUserException( self::_("Wrong number of cards!") );
                        }

                        //move card to hand of player
                        $this->cards->moveCard( $cardId, 'hand', $playerId );

                        // Notify all players
                        self::notifyAllPlayers( "playBlueCard", clienttranslate( '${player_name} gets ${token_yellowCard} from ${other_player_name} ' ), array(
                            'player_id' => $playerId,
                            'player_name' => $this->getPlayerName($playerId),
                            'other_player_name' => $this->getPlayerName($playersSelectedValue),
                            'token_yellowCard'=>'yellowCard'
                        ) );
                    }
                    //more than one card... they must select
                    if ($playersData[$playersSelectedValue]['yellowCard']>1){
                        $playersGiveCards[] = $playersSelectedValue;
                    }
                }

                if (count($playersGiveCards)>0){
                    $this->insertPlayerAction($playerId, 'allGiveCard', 0, implode('_',$playersGiveCards), $visitorCardId, $visitorCardKey);
                }

                break;

            case 836: //Taster
                //Discard 1 ${token_wineAny} to gain ${token_lira4}. If it is the most valuable wine token in any player's cellar (no ties), gain 2 ${token_vp2}.
                //**special**
                if ($visitorCardOption == 1){
                    $wines = $this->readCardsByPlayerIdAndCardTypeAndCardTypeArg($playerId, $wine, $wineValue);
                    if (count($wines)==0){
                        throw new BgaUserException( self::_("Wine not found!") );
                    }
                    $wineId = $wines[0]['id'];

                    $this->DbQuery("DELETE FROM card WHERE card_location='playerWines' AND card_location_arg = $playerId AND card_type like 'wine%' and card_id =$wineId");

                    $this->dbIncLira($playerId, 4);

                    // Notify all players
                    self::notifyAllPlayers( "playBlueCard", clienttranslate( '${player_name} discards ${token_wine} and gets ${token_get}' ), array(
                        'player_id' => $playerId,
                        'player_name' => $this->getPlayerName($playerId),
                        'token_get' => 'lira4',
                        'token_wine' => $wine.$wineValue
                    ) );

                    $winesBetter = $this->getUniqueValueFromDB("SELECT count(*) FROM card WHERE card_location='playerWines' AND card_type like 'wine%' and card_type_arg >= $wineValue ");
                    if ($winesBetter==0){
                        $this->dbIncScore($playerId, 2, 'vit_scoring_blue_card');
                        // Notify all players
                        self::notifyAllPlayers( "playBlueCard", clienttranslate( '${player_name} gets ${token_get}' ), array(
                            'player_id' => $playerId,
                            'player_name' => $this->getPlayerName($playerId),
                            'token_get' => 'vp2'
                        ) );
                    }
                }
                break;

            case 837: //Caravan
                //Turn the top card of each deck face up. Draw 2 of those cards and discard the others.
                //**special** requires state with first card of deck
                $this->drawFromDeck($playerId, DECK_GREEN, 1, true, 'chooseCards');
                $this->drawFromDeck($playerId, DECK_YELLOW, 1, true, 'chooseCards');
                $this->drawFromDeck($playerId, DECK_PURPLE, 1, true, 'chooseCards');
                $this->drawFromDeck($playerId, DECK_BLUE, 1, true, 'chooseCards');
                $this->insertPlayerAction($playerId, 'chooseCards', 0, 2, $visitorCardId, $visitorCardKey);
                break;

            case 838: //Guest Speaker
                //All players may pay ${token_lira1} to train ${token_worker}. Gain ${token_vp1} for each opponent who does this.
                //**special**
                if ($otherSelection=='1'){
                    $workerLocation = $locationKey;
                    if ($workerLocation==0 || $workerLocation == ''){
                        $workerLocation = $this->getNewWorkerLocation($playerId);
                    } else {
                        if (array_key_exists($workerLocation, $this->boardLocations)){
                            $workerLocation = $this->boardLocations[$workerLocation]['sha'].'_new';
                        } else {
                            $workerLocation = $workerLocation.'_new';
                        }
                    }
                    $this->addWorker($playerId,'board_'.$workerLocation, 0, false);
                    $this->dbIncLira($playerId, -1);
                    // Notify all players
                    self::notifyAllPlayers( "playBlueCard", clienttranslate( '${player_name} pays ${token_lose} and trains a new ${token_worker}' ), array(
                        'player_id' => $playerId,
                        'player_name' => $this->getPlayerName($playerId),
                        'token_lose' => 'lira1',
                        'token_worker' => 'worker'
                    ) );
                }

                $this->insertPlayerAction($playerId, 'allChoose', 0, $locationKey, $visitorCardId, $visitorCardKey);

                break;


            default:
                throw new BgaUserException( self::_("Wrong card!") );

                break;
        }

        if ($this->checkPlayerActionStatusNewWithCard($visitorCardId)==false){
            $this->discardCardOnDeckTop($visitorCardId, $visitorCardKey);
        }

    }

    function getNewWorkerLocation($playerId){
        //set: 0 
        //to be changed with tuscany
        $sql = "SELECT card_location FROM card WHERE card_location in ('board_351','board_352','board_353') and card_location_arg = $playerId and card_type like 'worker%' order by 1";
        $locations = self::getObjectListFromDB( $sql);
        if (count($locations)>0){
            $location = $locations[0]['card_location'];
            $locationParts = explode('_',$location);
            return $this->boardLocations[$locationParts[1]]['sha'].'_new';
        }
        return '350'.'_new';
    }

    function recalculateWakeupOrder(){
        //recalculate wakeup order
        $playersData = $this->getPlayersData();
        $playersData = $this->arrayOrderBy($playersData,"wakeup_chart", true, true);
        $wakeUpOrder = 0;
        foreach ($playersData as $playersDataKey => $playersDataData) {
            $wakeUpOrder++;
            // change order in season (wakeup_order)
            $sql = "UPDATE player
            SET wakeup_order = $wakeUpOrder
            WHERE player_id = $playersDataKey";
            self::DbQuery($sql);
        }
    }

    function checkFirstOptionCardPlayed($playerId, $playerFullData, $visitorCardKey){
        $action = $this->readPlayerAction($playerId, STATUS_IN_PROGRESS);
        //exists action, so it's the second time, no more choices: exit...
        if ($action!=null && $action['action']=='playCardSecondOption' && $action['card_key']==$visitorCardKey){
            return $action['args'];
        }
        return 0;
    }

    function checkAndInsertSecondCardOptionAction($playerId, $visitorCardId, $visitorCardKey, $visitorCardOption, $vpPrice, $playersFullData, $tokens, $playersPrivateHand, $ignoreCheckChoices){
        $action = $this->readPlayerAction($playerId, STATUS_IN_PROGRESS);
        $playerFullData = $playersFullData[$playerId];

        //action exists, so it's the second time, no more choices: exit...
        if ($action!=null && $action['action']=='playCardSecondOption' && $action['card_key']==$visitorCardKey){
            return false;
        }

        $bitFirstChoice = pow(2, $visitorCardOption-1);

        //pay vp?
        if ($vpPrice> 0 && $playerFullData['score']<-5+$vpPrice){
            return false;
        }

        //choices?
        if ($ignoreCheckChoices==false){
            $cardType = $this->getCardType($visitorCardKey);
            if ($cardType == 'yellowCard'){
                $choices = $this->checkYellowCard($playerId, $playerFullData, $cardType, $visitorCardKey, $playersFullData, $tokens, $playersPrivateHand);
            }
            if ($cardType == 'blueCard'){
                $choices = $this->checkBlueCard($playerId, $playerFullData, $cardType, $visitorCardKey, $playersFullData, $tokens, $playersPrivateHand);
            }
    
            //no choices
            if ($choices==0){
                return false;
            }
    
            //no more different choices
            if ($choices==$bitFirstChoice){
                return false;
            }
        }

        $this->insertPlayerAction($playerId, 'playCardSecondOption', 10, $visitorCardOption, $visitorCardId, $visitorCardKey);

        return true;
    }

    function buyField($playerId, $playerFullData, $buyField){
        $field = $this->fields[$buyField];
        if ($playerFullData[$field['dbField']]>0){
            throw new BgaUserException( self::_("Cannot buy this field!") );
        }

        if ($playerFullData['lira']<$field['price']){
            throw new BgaUserException( self::_("Cannot buy this field, not enough lira!") );
        }

        $this->dbIncLira($playerId, -$field['price']);
        $fieldDb = $field['dbField'];
        $this->DbQuery("UPDATE player SET $fieldDb=1 WHERE player_id='$playerId'");

        // Notify all players
        self::notifyAllPlayers( "buyField", clienttranslate( '${player_name} buys field ${fieldNumber} for ${price}${token_lira}' ), array(
            'player_id' => $playerId,
            'player_name' => $this->getPlayerName($playerId),
            'fieldNumber' => $buyField,
            'price' => $field['price'],
            'token_lira' => 'lira'
        ) );
    }

    function sellField($playerId, $playerFullData, $sellField){
        $field = $this->fields[$sellField];
        if ($playerFullData[$field['dbField']]==0){
            throw new BgaUserException( self::_("Cannot sell this field!") );
        }

        if (count($playerFullData[$field['location']])>0){
            throw new BgaUserException( self::_("Cannot sell this field, there are vines planted!") );
        }

        $this->dbIncLira($playerId, $field['price']);
        $fieldDb = $field['dbField'];
        $this->DbQuery("UPDATE player SET $fieldDb=0 WHERE player_id='$playerId'");

        // Notify all players
        self::notifyAllPlayers( "sellField", clienttranslate( '${player_name} sells field ${fieldNumber} for ${price}${token_lira}' ), array(
            'player_id' => $playerId,
            'player_name' => $this->getPlayerName($playerId),
            'fieldNumber' => $sellField,
            'price' => $field['price'],
            'token_lira' => 'lira'
        ) );
    }

    function sellGrapes($playerId, $playerFullData, $grapesId){
        //check all grapes are present in player grapes
        $grapesIdJoin = implode(',',$grapesId);
        $sql = "SELECT card_id id, card_type type, card_type_arg type_arg FROM card WHERE card_location='playerGrapes' AND card_location_arg = $playerId AND card_type like 'grape%' and card_id in (${grapesIdJoin})";
        $grapes = self::getObjectListFromDB( $sql);

        if (count($grapes) != count($grapesId)){
            throw new BgaUserException( self::_("Grapes not valid!") );
        }

        $price = 0;
        foreach ($grapes as $grapesKey => $grapesValue) {
            $price += $this->grapePrice[$grapesValue['type_arg']];
        }

        $this->dbIncLira($playerId, $price);
        $this->DbQuery("DELETE FROM card WHERE card_location='playerGrapes' AND card_location_arg = $playerId AND card_type like 'grape%' and card_id in (${grapesIdJoin})");

        // Notify all players
        self::notifyAllPlayers( "sellGrapes", clienttranslate( '${player_name} sells ${grapeNumber} grape(s) for ${price}${token_lira}' ), array(
            'player_id' => $playerId,
            'player_name' => $this->getPlayerName($playerId),
            'grapeNumber' => count($grapesId),
            'price' => $price,
            'token_lira' => 'lira',
            'grapes' => 'grapes'
        ) );
    }

    function checkActionMakeWine($playerId, $playerFullData, $privateHandCards, $tokens, $lastFree, $additionalWine, $minimumWineValue){

        //no grapes no wine
        if (count($playerFullData['grapes'])==0){
            return 0;
        }

        $minimumMake=1;
        if ($additionalWine && $lastFree==false){
            if ($this->isFriendlyVariant()){
                $minimumMake=3;
            }
        }

        //no grapes no wine
        if (count($playerFullData['grapes'])<$minimumMake){
            return 0;
        }

        $possibleWines=$this->readPossibleWineMakeable($playerId, $playerFullData, true, $minimumWineValue);

        if (count($possibleWines) >= $minimumMake){
            return 1;
        }
        return 0;
    }

    function readPossiblePurpleCards($playerId, $playerFullData, $tokens, $privateHandCards, $simulateAgeOneWine){
        $result = array();
        $hand = $privateHandCards[$playerId]['hand'];
        if (count($playerFullData['wines'])>0){
            foreach ($hand as $handKey => $handValue) {
                if ($handValue['t']=='purpleCard'){
                    if ($this->checkPurpleCardWines($playerId, $playerFullData, $handValue['t'], $handValue['k'],$simulateAgeOneWine)){
                        $result[]= $handValue['i'];
                    }
                }
            }
        }
        return $result;
    }

    function readPossibleGreenCards($playerId, $playerFullData, $tokens, $privateHandCards, $checkStructure, $checkLimit){
        $result = array();
        $hand = $privateHandCards[$playerId]['hand'];
        foreach ($hand as $handKey => $handValue) {
            if ($handValue['t']=='greenCard'){
                if ($this->checkGreenCardVine($playerId, $playerFullData, $handValue['t'], $handValue['k'], $privateHandCards, $checkStructure, $checkLimit)){
                    $result[]= $handValue['i'];
                }
            }
        }
        return $result;
    }

    function readPossibleYellowCards($playerId, $playerFullData, $playersFullData, $tokens, $privateHandCards){
        $result = array();
        $hand = $privateHandCards[$playerId]['hand'];
        foreach ($hand as $handKey => $handValue) {
            if ($handValue['t']=='yellowCard'){
                $choices  =$this->checkYellowCard($playerId, $playerFullData, $handValue['t'], $handValue['k'], $playersFullData, $tokens, $privateHandCards);
                if ($choices>0){
                    $result[]= array('i'=>$handValue['i'], 'k'=>$handValue['k'], 'c'=>$choices);
                }
            }
        }
        return $result;
    }

    function readPossibleBlueCards($playerId, $playerFullData,$playersFullData, $tokens, $privateHandCards){
        $result = array();
        $hand = $privateHandCards[$playerId]['hand'];
        foreach ($hand as $handKey => $handValue) {
            if ($handValue['t']=='blueCard'){
                $choices  =$this->checkBlueCard($playerId, $playerFullData, $handValue['t'], $handValue['k'], $playersFullData, $tokens, $privateHandCards);
                if ($choices>0){
                    $result[]= array('i'=>$handValue['i'], 'k'=>$handValue['k'], 'c'=>$choices);
                }
            }
        }
        return $result;
    }

    function readPossibleWineMakeable($playerId, $playerFullData, $checkStructures, $minimumWineValue){
        //transform grapes and wines in other 'structures'
        $grapesArray = $this->playerGrapesToArray($playerFullData);
        $winesBitArray = $this->playerWinesToBitArray($playerFullData, $checkStructures);

        $result = array();

        foreach ($winesBitArray as $winesKey => $wine) {
            foreach ($wine as $wineKey => $wineValue) {
                //if wine 'slot' is empty and the wine is makeable then add it
                if ($wineValue == 0 && $wineKey >= $minimumWineValue && $this->checkPossibleWine($playerFullData, $grapesArray, $winesKey, $wineKey, $checkStructures )){
                    $result[]=array('t'=>$winesKey, 'v'=>$wineKey);
                }
            }
        }


        return $result;
    }


    function checkPossibleWine($playerFullData, $grapesArray, $wine, $wineValue, $checkStructures ){

        if ($wine=='wineRed' && count($grapesArray['grapeRed'])==0){
            return false;
        } else if ($wine=='wineWhite' && count($grapesArray['grapeWhite'])==0){
            return false;
        } else if ($wine=='wineBlush' && (count($grapesArray['grapeRed'])==0 || count($grapesArray['grapeWhite'])==0)){
            return false;
        } else if ($wine=='wineSparkling' && (count($grapesArray['grapeRed'])<2 || count($grapesArray['grapeWhite'])==0)){
            return false;
        }

        if ($checkStructures){
            if ($wineValue>=4 && $playerFullData['mediumCellar']==0){
                return false;
            }
            if ($wineValue>=7 && $playerFullData['largeCellar']==0){
                return false;
            }
            if ($wine=='wineBlush' && $playerFullData['mediumCellar']==0){
                return false;
            }
            if ($wine=='wineSparkling' && $playerFullData['largeCellar']==0){
                return false;
            }
        }

        if ($wine == 'wineRed'){
            //look for a red grape with value >=
            foreach ($grapesArray['grapeRed'] as $grape => $grapeValue) {
                if ($grapeValue >= $wineValue){
                    return true;
                }
            }
        }
        if ($wine == 'wineWhite'){
            //look for a red grape with value >=
            foreach ($grapesArray['grapeWhite'] as $grape => $grapeValue) {
                if ($grapeValue >= $wineValue){
                    return true;
                }
            }
        }
        if ($wine == 'wineBlush'){
            //look for a red grape + white value with tot value >=
            foreach ($grapesArray['grapeRed'] as $redGrape => $redGrapeValue) {
                foreach ($grapesArray['grapeWhite'] as $whiteGrape => $whiteGrapeValue) {
                    if ($redGrapeValue+$whiteGrapeValue >= $wineValue){
                        return true;
                    }
                }
            }
        }
        if ($wine == 'wineSparkling'){
            //look for two red grape + white value with tot value >=
            foreach ($grapesArray['grapeRed'] as $redGrape => $redGrapeValue) {
                foreach ($grapesArray['grapeRed'] as $redGrape2 => $redGrapeValue2) {
                    //different grapes
                    if ($redGrape!=$redGrape2){
                        foreach ($grapesArray['grapeWhite'] as $whiteGrape => $whiteGrapeValue) {
                            if ($redGrapeValue+$redGrapeValue2+$whiteGrapeValue >= $wineValue){
                                return true;
                            }
                        }
                    }
                }
            }
        }
        return false;
    }

    function harvestFieldInternal($playerId, $harvestFieldsId, $playersData, $tokens, $playersPrivateHand, $maxHarvest, $workerLocationKey){

        if (count($harvestFieldsId)>$maxHarvest){
            throw new BgaUserException( self::_("Too many fields to harvest!") );
        }
        $playerFullData = $this->getPlayerFullData($playerId);

        foreach ($harvestFieldsId as $fieldKey => $fieldNumber) {

            if ($fieldNumber < 0 || $fieldNumber > 3){
                throw new BgaUserException( self::_("Wrong field!") );
            }
            $field = $this->fields[$fieldNumber];
            if ($playerFullData[$field['dbField']]!=1 || $playerFullData[$field['location'].'Tot']==0){
                throw new BgaUserException( self::_("Field not harvestable!") );
            }

            $grapes = array();
            foreach ($this->grapes as $grape => $grapesValue) {
                $colorAbbr = $grapesValue['colorAbbr'];
                $grapes[$grape]=0;
                $vines = $playerFullData[$field['location']];
                foreach ($vines as $vinesKey => $vinesValue) {
                    if ($vinesValue[$colorAbbr]>0){
                        $grapes[$grape]+=$vinesValue[$colorAbbr];
                        //9 max grape value
                        if ($grapes[$grape]>9){
                            $grapes[$grape]=9;
                        }
                    }
                }
            }

            $actualGrapes = $this->playerGrapesToBitArray($playerFullData);
            $createGrapes = array();
            foreach ($grapes as $grape => $value) {

                $grapeValue = 0;
                for ($i=$value; $i>0 ; $i--) {
                    //check if free grape
                    if ($actualGrapes[$grape][$i]==0){
                        $grapeValue = $i;
                        break;
                    }
                }
                if ($grapeValue>0){
                    $createGrapes[] = array('type' => $grape, 'type_arg' => $grapeValue, 'nbr' => 1);
                }
            }
            /*if (count($createGrapes)==0){
                throw new BgaUserException( self::_("No grapes to place in cellar!") );
            }*/
            if (count($createGrapes)==0){
                $fieldDb = $field['dbField'];
                $this->DbQuery("UPDATE player SET $fieldDb=2 WHERE player_id='$playerId'");
    
                $playerFullData = $this->getPlayerFullData($playerId);
    
                //grapes not placeable in crushpad
                //Can harvest, but grapes are lost
                //https://boardgamegeek.com/thread/1591437/harvesting-question

                // Notify all players
                self::notifyAllPlayers( "harvestField", clienttranslate( '${player_name} harvests field ${field} but all grapes go to dumpster, no room in crush pad' ), array(
                    'player_id' => $playerId,
                    'player_name' => $this->getPlayerName($playerId),
                    'field' => $fieldNumber,
                    'newGrapes' => $playerFullData['grapes']
                ) );
            } else {
                $this->cards->createCards( $createGrapes, 'playerGrapes', $playerId );

                $fieldDb = $field['dbField'];
                $this->DbQuery("UPDATE player SET $fieldDb=2 WHERE player_id='$playerId'");
    
                $playerFullData = $this->getPlayerFullData($playerId);
    
                // Notify all players
                self::notifyAllPlayers( "harvestField", clienttranslate( '${player_name} harvests field ${field}' ), array(
                    'player_id' => $playerId,
                    'player_name' => $this->getPlayerName($playerId),
                    'field' => $fieldNumber,
                    'newGrapes' => $playerFullData['grapes']
                ) );
            }

        }
        //check friendly blocking
        if (count($harvestFieldsId)<$maxHarvest && $workerLocationKey != null){
            $this->manageFriendlyBlocking($playerId, null, 'harvestField_1', $workerLocationKey);
        }
    }

    function fillOrderInternal($playerId, $cardId, $cardKey, $orderWinesId, $playersData, $tokens, $playersPrivateHand ,$vpBonus){
        $playerFullData = $playersData[$playerId];
        $card = $this->arrayFindByProperty($playersPrivateHand[$playerId]['hand'],'i',$cardId);
        if (!$card){
            throw new BgaUserException( self::_("Card not found!") );
        }
        if(!array_key_exists($card['k'], $this->purpleCards)){
            throw new BgaUserException( self::_("Wrong card!") );
        }
        $purpleCard = $this->purpleCards[$card['k']];

        $checkCardPlayable = $playersData[$playerId];
        if (!$this->checkCardPlayable($playerId, $playerFullData,  $card['t'],$card['k'], $playersPrivateHand, true, true)){
            throw new BgaUserException( self::_("Card not playable!") );
        }

        //check all cards are present in player hand
        $orderWinesIdJoin = implode(',',$orderWinesId);
        $sql = "SELECT card_id i, card_type t, card_type_arg v FROM card WHERE card_location='playerWines' AND card_location_arg = $playerId AND card_type like 'wine%' and card_id in (${orderWinesIdJoin}) order by card_type_arg, card_type";
        $wines = self::getObjectListFromDB( $sql);

        if (count($wines) != count($orderWinesId)){
            throw new BgaUserException( self::_("Wines not valid!") );
        }

        $cardWines = $this->purpleCardWinesToArray($cardKey);

        if (count($cardWines) != count($wines)){
            throw new BgaUserException( self::_("Wrong number of wines!") );
        }

        //order by value (first lesser value, than greater)
        $wines = $this->arrayOrderBy($wines,"v", true, true);
        foreach ($wines as $winesKey => $winesValue) {
            //setting used to false
            $wines[$winesKey]['u']=false;
        }

        $wineTokensUsed = array();
        $wineTokensUsedProgr = 0;
        foreach ($cardWines as $cardWinesKey => $cardWinesValue) {
            $wineFound = false;
            foreach ($wines as $winesKey => $winesValue) {
                //if not used
                // and same type
                // and value >= offer
                if ($wines[$winesKey]['u']==false
                && $wines[$winesKey]['t']==$winesValue['t']
                && $wines[$winesKey]['v']>=$winesValue['v']){
                    $wines[$winesKey]['u'] = true;
                    $wineFound = true;
                    //found wine, exit from loop on player wines
                    //Save token for notifications
                    $wineTokensUsedProgr++;
                    $wineTokensUsed['token_wine'.$wineTokensUsedProgr]=$winesValue['t'].$winesValue['v'];
                    break;
                }
            }
            if (!$wineFound){
                throw new BgaUserException( self::_("Wine not found!") );
            }
        }

        //move card to discard
        $this->discardCardOnDeckTop($cardId, $cardKey);
        $this->addCardPlayedToHistory($playerId, $cardId, $cardKey);

        //remove wines
        $this->DbQuery("DELETE FROM card WHERE card_location='playerWines' AND card_location_arg = $playerId AND card_type like 'wine%' and card_id in (${orderWinesIdJoin})");


        $residualPaymentInc = $purpleCard['resid'];
        if ($playerFullData['residual_payment']+$residualPaymentInc>5){
            $residualPaymentInc = 5-$playerFullData['residual_payment'];
        }

        $vps = $purpleCard['vp'] + $vpBonus;
        $this->dbIncScore($playerId, $vps, 'vit_scoring_fill_order');
        $this->dbIncResidualPayment($playerId, $residualPaymentInc);

        $playerFullData = $this->getPlayerFullData($playerId);

        $notificationText = clienttranslate( '${player_name} fills an order ${token_purpleCard} and gets ${token_vp} and ${residualPayment} ${token_residualPayment}. Wines used: ${token_wineToken1} ${token_wineToken2} ${token_wineToken3}' );
        $notificationArgs = array(
            'player_id' => $playerId,
            'player_name' => $this->getPlayerName($playerId),
            'vp' => $vps,
            'token_vp' => 'vp'.$vps,
            'token_purpleCard' => 'purpleCard|'.$cardKey,
            'residualPayment' => $residualPaymentInc,
            'token_residualPayment' => 'residualPayment',
            'cardId' => $cardId,
            'winesUsed' => $wines,
            'wines'=> $playerFullData['wines'],
            'token_wineToken1' => '',
            'token_wineToken2' => '',
            'token_wineToken3' => ''
        );
        $progr = 0;
        foreach ($wineTokensUsed as $wineTokensUsedKey => $wineTokensUsedValue) {
            $progr++;
            $notificationArgs['token_wineToken'.$progr] = $wineTokensUsedValue;
            //$notificationText .= ' ${'.$wineTokensUsedKey.'}';
        }

        // Notify all players
        self::notifyAllPlayers( "fillOrder", $notificationText, $notificationArgs );

    }

    function makeWineInternal($playerId, $wine, $wineValue, $grapesId, $playersData, $tokens, $playersPrivateHand, $checkStructures){
        if ($wine == '' || $wineValue == 0){
            throw new BgaUserException( self::_("No wine selected!") );
        }

        $playerData = $playersData[$playerId];

        $minimumWineValue = 1;
        //833: //Zymologist
        //Make up to 2 ${token_wineAny} of value 4 or greater, even if you haven't upgraded your cellar.
        //makeWine_2_value4withouthmediumcellar
        if ($playerData['card_played'] == 833){
            $minimumWineValue = 4;
        }

        $possibleWines = $this->readPossibleWineMakeable($playerId, $playerData, $checkStructures, $minimumWineValue);

        //Check if wine is one of the possibile ones
        $found = false;
        foreach ($possibleWines as $possibleWinesKey => $possibleWinesValue) {
            if ($possibleWinesValue['t']==$wine && $possibleWinesValue['v'] == $wineValue ){
                $found = true;
            }
        }
        if (!$found){
            throw new BgaUserException( self::_("You cannot make this wine!") );
        }

        //check all grapes are present in player grapes
        $grapesIdJoin = implode(',',$grapesId);
        $sql = "SELECT card_id id, card_type type, card_type_arg type_arg FROM card WHERE card_location='playerGrapes' AND card_location_arg = $playerId AND card_type like 'grape%' and card_id in (${grapesIdJoin})";
        $grapes = self::getObjectListFromDB( $sql);

        if (count($grapes) != count($grapesId)){
            throw new BgaUserException( self::_("Grapes not valid!") );
        }

        //check total of grapes and count by type
        $grapesTotal = 0;
        $grapesByType = array('grapeRed'=>0, 'grapeWhite'=>0);
        foreach ($grapes as $grapesKey => $grapesValue) {
            $grapesByType[$grapesValue['type']]++;
            $grapesTotal+=$grapesValue['type_arg'];
        }

        //check total value of grapes
        if ($grapesTotal < $wineValue){
            throw new BgaUserException( self::_("Cannot make wine, grapes total value less than wine value!") );
        }

        //check grapes types by wine
        if ($wine == 'wineRed' && $grapesByType['grapeRed']==1 && $grapesByType['grapeWhite']==0 ){
            //OK
        } else if ($wine == 'wineWhite' && $grapesByType['grapeRed']==0 && $grapesByType['grapeWhite']==1){
            //OK
        } else if ($wine == 'wineBlush' && $grapesByType['grapeRed']==1 && $grapesByType['grapeWhite']==1){
            //OK
        } else if ($wine == 'wineSparkling' && $grapesByType['grapeRed']==2 && $grapesByType['grapeWhite']==1){
            //OK
        } else {
            throw new BgaUserException( self::_("Cannot make wine, wrong grape selection!") );
        }

        //remove grapes
        $this->DbQuery("DELETE FROM card WHERE card_location='playerGrapes' AND card_location_arg = $playerId AND card_type like 'grape%' and card_id in (${grapesIdJoin})");

        //add wine
        $createWine =array(
            array('type' => $wine, 'type_arg' => $wineValue, 'nbr' => 1)
        );
        $this->cards->createCards( $createWine, 'playerWines', $playerId );

        $playerFullData = $this->getPlayerFullData($playerId);

        // Notify all players
        self::notifyAllPlayers( "makeWine", clienttranslate( '${player_name} makes ${wine_name} ${token_wine}' ), array(
            'player_id' => $playerId,
            'player_name' => $this->getPlayerName($playerId),
            'wine_name' => $this->getWineName($wine),
            'token_wine' => $wine.$wineValue,
            'makeWines' => $playerFullData['wines'],
            'grapesUsed' => $grapes,
            'i18n' => array( 'wine_name')
        ) );

        //Supervisor
        //Make up to 2 ${token_wineAny}. Gain${token_vp1} for each sparkling wine token you make.
        //makeWine_2_ifmakesparklingwineeach_1vp
        if ($playerFullData['card_played'] == 822 && $wine=='wineSparkling'){
            $this->dbIncScore($playerId, 1, 'vit_scoring_blue_card');
            // Notify all players
            self::notifyAllPlayers( "makeWine", clienttranslate( '${player_name} gets ${token_get}' ), array(
                'player_id' => $playerId,
                'player_name' => $this->getPlayerName($playerId),
                'token_get' => 'vp1'
            ) );
        }
        //Bottler
        //Make up to 3 ${token_wineAny}. Gain ${token_vp1} for each type of wine you make.
        //makeWine_3_ifdistincttype_get1vp **needs history of wines**
        if ($playerFullData['card_played'] == 826 ){
            $wineMade = $wine;
            $wineMadeId = $this->getUniqueValueFromDB("SELECT card_id FROM card WHERE card_location='card_flags' and card_location_arg=826 and card_type='${wineMade}' ");
            if ($wineMadeId==null){
                $this->dbIncScore($playerId, 1, 'vit_scoring_blue_card');
                // Notify all players
                self::notifyAllPlayers( "makeWine", clienttranslate( '${player_name} gets ${token_get}' ), array(
                    'player_id' => $playerId,
                    'player_name' => $this->getPlayerName($playerId),
                    'token_get' => 'vp1'
                ) );
                $winesMade = array();
                $winesMade[] = array('type' => $wineMade, 'type_arg' => 1, 'nbr' => 1);
                $this->cards->createCards( $winesMade, 'card_flags', 826);
            }
        }

        //817 Mentor
        //All players may make up to 2 ${token_wineAny}. Draw 1 ${token_greenCardPlus} or ${token_YellowCardPlus} card for each opponent who does this.
        $actionProgress = $this->readPlayerActionInProgress();
        if ($actionProgress!= null && $actionProgress['card_key'] == 817 ){
            $playerIdOrigin = $actionProgress['args'];
            //if it's different player from who played the card, it's an opponent
            if ($playerIdOrigin != '' && $playerId != $playerIdOrigin){
                //check if it's the first wine made then add action 'chooseVisitorCardDraw'
                $player_action_id = $this->getUniqueValueFromDB("SELECT player_action_id FROM player_action WHERE player_id=${playerIdOrigin} AND action='chooseVisitorCardDraw' AND args='$playerId'");
                if ($player_action_id == null){
                    $this->insertPlayerAction($playerIdOrigin, 'chooseVisitorCardDraw', 10, $playerId);
                }
            }
        }
    }

    function uprootVineInternal($playerId, $uprootVineId, $playerFullData, $discardCard){
        $card = $this->readCardByPlayerIdAndCardId($playerId, $uprootVineId);
        if (!$card){
            throw new BgaUserException( self::_("Card not found!") );
        }
        $greenCard = $this->greenCards[$card['type_arg']];

        $field = $this->arrayFindByProperty($this->fields,'location',$card['location']);
        if (!$field){
            throw new BgaUserException( self::_("Field not found!") );
        }
        $fieldNumber =$field['key'];

        //return in hand
        $this->cards->moveCard( $uprootVineId, 'hand', $playerId );

        $playerFullData = $this->getPlayerFullData($playerId);

        if ($discardCard){
            //move card to discard
            $this->discardCardOnDeckTop($card['id'], $card['type_arg']);

            $message = clienttranslate( '${player_name} uproots and discards vine ${cardName} from field ${fieldNumber}' );
        } else {
            $message = clienttranslate( '${player_name} uproots vine ${cardName} from field ${fieldNumber}' );
        }

        //check if there other vines planted, if no more vines and the field is harvested, make it not harvested
        //BUG: #47366: "NOT ABLE TO SELL FIELD AFTER UPROOTING AND HARVESTING THE FIELD"
        if (count($playerFullData[$field['location']])==0 && $playerFullData[$field['dbField']]==2){
            $fieldDb = $field['dbField'];
            $this->DbQuery("UPDATE player SET $fieldDb=1 WHERE player_id='$playerId' and $fieldDb=2");     
            $playerFullData[$field['dbField']]=2;       
        }

        // Notify all players
        self::notifyAllPlayers( "uprootVine", $message, array(
            'player_id' => $playerId,
            'player_name' => $this->getPlayerName($playerId),
            'cardName' => $greenCard['name'],
            'cardId' => $uprootVineId,
            'fieldNumber' => $fieldNumber,
            'vines' => array(
                'vine1'=>$playerFullData['vine1'],
                'vine2'=>$playerFullData['vine2'],
                'vine3'=>$playerFullData['vine3']
            ),
            'i18n' => array( 'cardName' )
        ) );

    }

    function plantInternal($playerId, $cardId, $cardKey, $fieldNumber, $playersData, $tokens, $privateHandCards, $checkStructures, $checkFieldLimit){
        $playerFullData = $playersData[$playerId];
        $card = $this->arrayFindByProperty($privateHandCards[$playerId]['hand'],'i',$cardId);
        if (!$card){
            throw new BgaUserException( self::_("Card not found!") );
        }
        $greenCard = $this->greenCards[$card['k']];
        if (!$greenCard){
            throw new BgaUserException( self::_("Vine Card not found!") );
        }
        $field = $this->fields[$fieldNumber];

        $checkCardPlayable = $playersData[$playerId];
        if (!$this->checkCardPlayable($playerId, $playerFullData,  $card['t'],$card['k'], $privateHandCards, $checkStructures, $checkFieldLimit)){
            throw new BgaUserException( self::_("Card not playable!") );
        }

        if ($checkFieldLimit){
            if ($playerFullData[$field['location'].'Tot']+$greenCard['red']+$greenCard['white']>$field['maxValue']){
                throw new BgaUserException( self::_("Exceeded field capacity!") );
            }
        }

        $this->cards->moveCard( $cardId, $field['location'], $playerId );
        $this->addCardPlayedToHistory($playerId, $cardId, $greenCard['key']);

        $playerFullData = $this->getPlayerFullData($playerId);

        // Notify all players
        self::notifyAllPlayers( "plant", clienttranslate( '${player_name} plants ${cardName} in field ${fieldNumber}' ), array(
            'player_id' => $playerId,
            'player_name' => $this->getPlayerName($playerId),
            'cardName' => $greenCard['name'],
            'fieldNumber' => $fieldNumber,
            'vines' => array(
                'vine1'=>$playerFullData['vine1'],
                'vine2'=>$playerFullData['vine2'],
                'vine3'=>$playerFullData['vine3']
            ),
            'i18n' => array( 'cardName' )
        ) );

        //windmill 1vp if you have wines (only once per turn);
        if ($playerFullData['windmill']==1 && $playerFullData['windmillUsed']==0){
            $this->dbIncScore($playerId, 1 ,'vit_scoring_windmill');
            $this->DbQuery("UPDATE player SET windmillUsed=1 WHERE player_id='$playerId'");
            // Notify all players
            self::notifyAllPlayers( "windmillVp", clienttranslate( '${player_name} gets a vp ${token_vp} from windmill ${token_windmill} effect' ), array(
                'player_id' => $playerId,
                'player_name' => $this->getPlayerName($playerId),
                'token_vp' => 'vp',
                'token_windmill' => 'windmill'
            ) );
        }
        //622 Overseer
        //Build 1 structure at its regular cost and plant 1 ${token_greenCard}. If it is a 4-value ${token_greenCard}, gain ${token_vp1}.
        //buildStructure_1+plant_1_ifgreat4_1vp
        if ($playerFullData['card_played'] == 622 && $greenCard['red']+$greenCard['white']==4){
            $this->dbIncScore($playerId, 1, 'vit_scoring_yellow_card');
            // Notify all players
            self::notifyAllPlayers( "makeWine", clienttranslate( '${player_name} gets ${token_get}' ), array(
                'player_id' => $playerId,
                'player_name' => $this->getPlayerName($playerId),
                'token_get' => 'vp1'
            ) );
        }

    }

    function playerGrapesToArray($playerFullData){
        $result = array(
            'grapeRed'=>array(),
            'grapeWhite'=>array()
        );
        foreach ($playerFullData['grapes'] as $key => $grape) {
            $result[$grape['t']][] = $grape['v'];
        }

        return $result;
    }

    function playerGrapesToBitArray($playerFullData){
        $result = array(
            'grapeRed'=>array(-1,0,0,0,0,0,0,0,0,0),
            'grapeWhite'=>array(-1,0,0,0,0,0,0,0,0,0)
        );
        foreach ($playerFullData['grapes'] as $key => $grape) {
            $result[$grape['t']][$grape['v']] = 1;
        }

        return $result;

    }

    function playerWinesToBitArray($playerFullData, $checkStructures){
        $result = array();
        if ($checkStructures){
            $wines = $this->wines[$playerFullData['mediumCellar']+$playerFullData['largeCellar']];
        } else {
            $wines = $this->wines[2];
        }

        foreach ($wines as $winesKey => $winesValue) {
            $result[$winesKey] = array();
            for ($i=0; $i <= $winesValue['max']; $i++) {
                if ($i <  $winesValue['min']){
                    $result[$winesKey][]=-1;
                } else {
                    $result[$winesKey][]=0;
                }
            }
        }
        foreach ($playerFullData['wines'] as $key => $wine) {
            $result[$wine['t']][$wine['v']] = 1;
        }
        return $result;

    }

    function playerWinesToArray($playerFullData){
        $result = array();
        $wines = $this->wines[$playerData['mediumCellar']+$playerData['largeCellar']];

        foreach ($wines as $winesKey => $winesValue) {
            $result[winesKey] = array();

        }
        foreach ($playerFullData['wines'] as $key => $wine) {
            $result[$wine['t']][] = $wine['v'];
        }
        return $result;

    }

    function getPossibleWines($playerData){
        $result = array();
        $wines = $this->wines[$playerData['mediumCellar']+$playerData['largeCellar']];

        foreach ($wines as $winesKey => $winesValue) {
            for ($i=$winesValue['min']; $i <= $winesValue['max']; $i++) {
                $result[] = array('t'=>$winesKey, 'v'=>i);
            }
        }

        return $result;
    }

    function checkActionTrainWorker($playerId, $playerFullData, $tokens, $discount){
        $lira = $playerFullData['lira']+$discount;

        $workers = 0;
        $workersGrande = 0;
        foreach ($tokens[$playerId] as $tokensKey => $tokensValue) {
            //worker available, enough money
            if ($tokensValue['l'] =='playerOff' && $this->startsWith($tokensValue['t'],'worker') && $lira >= 4){
                return 1;
            }
        }

        return 0;
    }

    function checkActionUproot($playerId, $playerFullData){
        //Check if there is at least a vine in one field
        foreach ($this->fields as $fieldsKey => $fieldsValue) {
            if (count($playerFullData[$fieldsValue['location']])>0){
                return true;
            }
        }
        return false;
    }

    function checkActionHarvest($playerId, $playerFullData, $tokens, $lastFree, $additionalHarvest){
        $mininumHarvest=1;
        if ($additionalHarvest && $lastFree==false){
            if ($this->isFriendlyVariant()){
                $mininumHarvest=2;
            }
        }

        $harvestable = 0;

        $grapesBitArray = $this->playerGrapesToBitArray($playerFullData);

        //check fields with value == 1 and green cards on it
        foreach ($this->fields as $fieldsKey => $fieldsValue) {
            if ($playerFullData[$fieldsValue['dbField']]==1 && count($playerFullData[$fieldsValue['location']])>0){
                //grapes not placeable in crushpad
                //Can harvest, but grapes are lost
                //https://boardgamegeek.com/thread/1591437/harvesting-question

                //check if there is at least one free slot for grapes with value <=
                /*$redGrape = 0;
                $whiteGrape = 0;
                $grapeHarvestable = false;
                foreach ($playerFullData[$fieldsValue['location']] as $vineCardKey => $vineCardValue) {
                    $redGrape+=$vineCardValue['r'];
                    $whiteGrape+=$vineCardValue['w'];
                }
                if ($redGrape>0){
                    for ($i=1; $i <= $redGrape; $i++) { 
                        if ($grapesBitArray['grapeRed'][$i]==0){
                            $grapeHarvestable=true;
                            break;
                        }
                    }
                }
                if ($grapeHarvestable==false && $whiteGrape>0){
                    for ($i=1; $i <= $whiteGrape; $i++) { 
                        if ($grapesBitArray['grapeWhite'][$i]==0){
                            $grapeHarvestable=true;
                            break;
                        }
                    }
                }*/
                //if ($grapeHarvestable){
                $harvestable++;
                //}
            }
        }

        if ($harvestable >= $mininumHarvest){
            return 1;
        }
        return 0;
    }

    function checkActionBuildPlayability($playerId, $playerFullData, $tokens, $discount){
        $lira = $playerFullData['lira']+$discount;

        $playerTokens = $this->playerTokens;
        foreach ($playerTokens as $playerTokensKey => $playerTokensValue) {
            //enough money and buildable
            if ($playerTokensValue['isBuilding'] && $lira>=$playerTokensValue['price']-$discount && $this->checkBuildableBuilding($playerId, $playerTokensValue['type'], $playerTokensValue['price']-$discount)==true){
                return 1;
            }
        }

        return 0;
    }

    function checkActionCardPlayability($playerId, $cardType, $playerFullData, $privateHandCards, $tokens, $lastFree, $additionalCard, $playersData, $cardIdToBeExcluded){
        $minimumPlayable=1;
        if ($additionalCard && $lastFree==false){
            if ($this->isFriendlyVariant()){
                //yellow card and blue card are playable
                //the check is done after playing, if minimum card not played so worker moved to other not-bonus location  (manageFriendlyBlocking)
                if ($cardType!='yellowCard' && $cardType!='blueCard'){
                    $minimumPlayable=2;
                }
            }
        }

        if ($playerFullData[$cardType]<$minimumPlayable){
            return 0;
        }

        $playable=0;
        $hand = $privateHandCards[$playerId]['hand'];
        foreach ($hand as $handKey => $handValue) {
            if ($handValue['t']==$cardType && $handValue['i'] != $cardIdToBeExcluded){
                if ($this->checkCardPlayable($playerId, $playerFullData,  $handValue['t'],$handValue['k'], $privateHandCards, true, true)){
                    if ($handValue['t']=='yellowCard'){
                        $choices = $this->checkYellowCard($playerId, $playerFullData, $handValue['t'], $handValue['k'], $playersData, $tokens, $privateHandCards);
                        if ($choices>0){
                            $playable++;
                        }
                    } else if ($handValue['t']=='blueCard'){
                        $choices = $this->checkBlueCard($playerId, $playerFullData, $handValue['t'], $handValue['k'], $playersData, $tokens, $privateHandCards);
                        if ($choices>0){
                            $playable++;
                        }
                    } else {
                        $playable++;
                    }
                }
            }
        }

        if ($playable >= $minimumPlayable){
            return 1;
        }

        return 0;

    }

    function checkCardPlayable($playerId, $playerFullData, $cardType, $cardKey, $privateHandCards, $checkStructure, $checkLimit){
        $hand = $privateHandCards[$playerId]['hand'];
        $found=false;
        foreach ($hand as $handKey => $handValue) {
            if ($cardType == $handValue['t'] && $cardKey == $handValue['k']){
                $found = true;
            }
        }
        if ($found == false){
            return false;
        }

        $result = false;
        switch ($cardType) {
            case 'greenCard':
                $result  = $this->checkGreenCardVine($playerId, $playerFullData, $cardType, $cardKey, $privateHandCards, $checkStructure, $checkLimit);
                break;

            case 'yellowCard':
            case 'blueCard':
                //playable
                $result  = true;
                break;

            case 'purpleCard':
                $result  = $this->checkPurpleCardWines($playerId, $playerFullData, $cardType, $cardKey, false);
                break;

            default:
                # code...
                break;
        }

        return $result;
    }

    function checkGreenCardVine($playerId, $playerFullData, $cardType, $cardKey, $privateHandCards, $checkStructure, $checkLimit){
        $card = $this->greenCards[$cardKey];

        //check buildings
        if ($checkStructure && $card['trellis']>0 && $playerFullData['trellis']==0){
            return false;
        }
        if ($checkStructure && $card['irrigation']>0 && $playerFullData['irrigation']==0){
            return false;
        }
        $cardTotal = $card['red']+$card['white'];

        //check if there is a vine field available with value + card value < max value of field
        $fields = $this->fields;
        foreach ($fields as $fieldsKey => $fieldsValue) {
            //field not sold
            if ($playerFullData[$fieldsValue['dbField']]>0){
                $fieldTotal = $playerFullData[$fieldsValue['location'].'Tot'];
                if ($checkLimit && $fieldTotal+$cardTotal<=$fieldsValue['maxValue']){
                    return true;
                }
                if ($checkLimit==false){
                    return true;
                }
            }
        }

        return false;
    }

    function checkYellowCard($playerId, $playerFullData, $cardType, $cardKey, $playersFullData, $tokens, $privateHandCards){
        $choices = 0;
        $card = $this->yellowCards[$cardKey];
        $playerWines = $playerFullData['wines'];
        $playerGrapes = $playerFullData['grapes'];
        $playerTokens = $this->playerTokens;
        $lira = (int)$playerFullData['lira'];
        $score = (int)$playerFullData['score'];
        $residual_payment = (int)$playerFullData['residual_payment'];

        $grapesBitArray = $this->playerGrapesToBitArray($playerFullData);

        switch ($card['key']) {
            case 601: //Surveyor
                //Gain ${token_lira2} for each empty field you own OR gain ${token_vp1} for each planted field you own.
                //**special**
                $ownedEmptyFields=0;
                $ownedPlantedFields=0;
                foreach ($this->fields as $fieldsKey => $fieldsValue) {
                    if ($playerFullData[$fieldsValue['dbField']]>0 && count($playerFullData[$fieldsValue['location']])==0){
                        $ownedEmptyFields++;
                    }
                    if ($playerFullData[$fieldsValue['dbField']]>0 && count($playerFullData[$fieldsValue['location']])>0){
                        $ownedPlantedFields++;
                    }
                }
                if ($ownedEmptyFields>0){$choices=$choices|1;}
                if ($ownedPlantedFields>0){$choices=$choices|2;}
                break;

            case 602: //Broker
                //Pay ${token_lira9} to gain ${token_vp3} OR lose ${token_vp2} to gain ${token_lira6}
                //payLira_9+getVp_3|loseVp_2+getLira_6
                if ($lira>=9){$choices=$choices|1;}
                if ($score>=-3){$choices=$choices|2;}
                break;

            case 603: //Wine Critic
                //Draw 2 ${token_blueCardPlus} OR discard 1 ${token_wineAny} of value 7 or more to gain ${token_vp4}
                //drawBlueCard_2|dicardWineAny_1_7+getVp_4
                $choices=$choices|1;
                $foundWine=false;
                foreach ($playerWines as $playerWinesKey => $playerWinesValue) {
                    if ($playerWinesValue['v']>=7){
                        $foundWine = true;
                        break;
                    }
                }
                if ($foundWine){
                    $choices=$choices|2;
                }
                break;

            case 604: //Blacksmith
                //Build a structure at a ${token_lira2} discount. If it is a ${token_lira5} or ${token_lira6} structure, also gain ${token_vp1}.
                //buildStructure_1_2_ifgreat5_1vp
                $foundStructure=false;
                foreach ($playerTokens as $playerTokensKey => $playerTokensValue) {
                    //enough money and buildable
                    if ($playerTokensValue['isBuilding'] && $lira>=$playerTokensValue['price']-2 && $this->checkBuildableBuilding($playerId, $playerTokensValue['type'], $playerTokensValue['price']-2)==true){
                        $foundStructure = true;
                    }
                }
                if ($foundStructure){
                    $choices=$choices|1;
                }
                break;

            case 605: //Contractor
                //Choose 2: Gain ${token_vp1}, build 1 structure, or plant 1 ${token_greenCard}.
                //getVp1|buildStructure_1|plant_1

                $firstChoice = $this->checkFirstOptionCardPlayed($playerId, $playerFullData, $cardKey);
                if ($firstChoice!=1){
                    $choices=$choices|1;
                }
                if ($firstChoice!=2){
                    $foundStructure=false;
                    foreach ($playerTokens as $playerTokensKey => $playerTokensValue) {
                        //enough money and buildable
                        if ($playerTokensValue['isBuilding'] && $lira>=$playerTokensValue['price'] && $this->checkBuildableBuilding($playerId, $playerTokensValue['type'], $playerTokensValue['price'])==true){
                            $foundStructure = true;
                        }
                    }
                    if ($foundStructure){
                        $choices=$choices|2;
                    }
                }
                if ($firstChoice!=3){
                    $possibleGreenCards = $this->readPossibleGreenCards($playerId, $playerFullData, $tokens, $privateHandCards, true, true);
                    if (count($possibleGreenCards)>0){
                        $choices=$choices|4;
                    }
                }
                break;

            case 606: //Tour Guide
                //Gain ${token_lira4} OR harvest 1 field.
                //getLira_4|harvestField_1
                $choices=$choices|1;
                $checkHarvest = $this->checkActionHarvest($playerId, $playerFullData, $tokens, true, false);
                if ($checkHarvest>0){
                    $choices=$choices|2;
                }
                break;

            case 607: //Novice Guide
                //Gain ${token_lira3} OR make up to 2 ${token_wineAny}
                //getLira_3|makeWine_2
                $choices=$choices|1;
                $wines = $this->checkActionMakeWine($playerId, $playerFullData, $privateHandCards, $tokens, true, false, 1);
                if ($wines>0){
                    $choices=$choices|2;
                }
                break;

            case 608: //Uncertified Broker
                //Lose ${token_vp3} to gain ${token_lira9} OR pay ${token_lira6} to gain ${token_vp2}.
                //loseVp_3+getLira_9|payLira_6+getVp_2
                if ($score>=-2){$choices=$choices|1;}
                if ($lira>=6){$choices=$choices|2;}
                break;

            case 609: //Planter
                //Plant up to 2 ${token_greenCard} and gain ${token_lira1} OR uproot and discard 1 ${token_greenCard} to gain ${token_vp2}.
                //plant_2+getLira_1|uprootAndDiscard_1+getVp_2
                $possibleGreenCards = $this->readPossibleGreenCards($playerId, $playerFullData, $tokens, $privateHandCards, true, true);
                if (count($possibleGreenCards)>0){
                    $choices=$choices|1;
                }
                if (count($playerFullData['vine1'])>0||count($playerFullData['vine2'])>0||count($playerFullData['vine3'])>0){
                    $choices=$choices|2;
                }
                break;

            case 610: //Buyer
                //3 victory points
                //payLira_2+getGrapeRed_1|payLira_2+getGrapeWhite_1|discardGrapeAny_1+getLira_2+getVp_1
                if ($lira>=2 && $grapesBitArray['grapeRed'][1]==0){$choices=$choices|1;}
                if ($lira>=2 && $grapesBitArray['grapeWhite'][1]==0){$choices=$choices|2;}
                if (count($playerGrapes)>0){$choices=$choices|4;}
                break;

            case 611: //Landscaper
                //Draw 1 ${token_greenCardPlus} and plant up to 1 ${token_greenCard} OR switch 2 ${token_greenCard} on your fields.
                //**special**
                $choices=$choices|1;
                $fieldsWithVine=0;
                if (count($playerFullData['vine1'])){
                    $fieldsWithVine++;
                }
                if (count($playerFullData['vine2'])){
                    $fieldsWithVine++;
                }
                if (count($playerFullData['vine3'])){
                    $fieldsWithVine++;
                }
                //TODO: check field limit? after switch?
                if ($fieldsWithVine>1){
                    $choices=$choices|2;
                }
                break;

            case 612: //Architect
                //Build a structure at a ${token_lira3} discount OR gain ${token_vp1} for each ${token_lira4} structure you have built.
                //buildStructure_1_3|getVp_buildings4
                $foundStructure=false;
                foreach ($playerTokens as $playerTokensKey => $playerTokensValue) {
                    //enough money and buildable
                    if ($playerTokensValue['isBuilding'] && $lira>=$playerTokensValue['price']-3 && $this->checkBuildableBuilding($playerId, $playerTokensValue['type'], $playerTokensValue['price']-3)==true){
                        $foundStructure = true;
                    }
                }
                if ($foundStructure){
                    $choices=$choices|1;
                }
                $foundStructure=false;
                foreach ($playerTokens as $playerTokensKey => $playerTokensValue) {
                    //enough money and buildable
                    if ($playerTokensValue['isBuilding'] && $playerTokensValue['price']>=4 && $playerFullData[$playerTokensValue['type']]==1){
                        $foundStructure = true;
                    }
                }
                if ($foundStructure){
                    $choices=$choices|2;
                }
                break;

            case 613: //Uncertified Architect
                //Lose ${token_vp1} to build a ${token_lira2} or ${token_lira3} structure OR lose ${token_vp2} to build any structure.
                $foundStructure=false;
                if ($score>=-4){
                    foreach ($playerTokens as $playerTokensKey => $playerTokensValue) {
                        //enough money and buildable
                        if ($playerTokensValue['isBuilding'] && $playerTokensValue['price']<=3 && $this->checkBuildableBuilding($playerId, $playerTokensValue['type'], 0)==true){
                            $foundStructure = true;
                            break;
                        }
                    }
                }
                if ($foundStructure){
                    $choices=$choices|1;
                }
                $foundStructure=false;
                if ($score>=-3){
                    foreach ($playerTokens as $playerTokensKey => $playerTokensValue) {
                        //enough money and buildable
                        if ($playerTokensValue['isBuilding'] && $this->checkBuildableBuilding($playerId, $playerTokensValue['type'], 0)==true){
                            $foundStructure = true;
                            break;
                        }
                    }
                }
                if ($foundStructure){
                    $choices=$choices|2;
                }
                break;

            case 614: //Patron
                //Gain ${token_lira4} OR draw 1 ${token_purpleCard} card and 1 ${token_blueCard}.
                //getLira_4|drawPurpleCard_1+drawBlueCard_1
                $choices=$choices|1;
                $choices=$choices|2;
                break;

            case 615: //Auctioneer
                //Discard 2 ${token_anyCard} to gain ${token_lira4} OR discard 4 ${token_anyCard} to gain ${token_vp3}.
                //discardCard_2+getLira_4|discardCard_4+getVp_3
                $sql = "SELECT card_id id, card_type type, card_type_arg type_arg FROM card WHERE card_location='hand' AND card_location_arg = $playerId and card_type_arg != 615 and card_id not in (select pa.card_id from player_action pa where pa.card_id is not null)";
                $cards = self::getObjectListFromDB( $sql);
                if (count($cards)>=2){
                    $choices=$choices|1;
                }
                if (count($cards)>=4){
                    $choices=$choices|2;
                }
                break;

            case 616: //Entertainer
                //Pay ${token_lira4} to draw 3 ${token_blueCardPlus} OR discard 1 ${token_wineAny} and 3 visitor cards to gain ${token_vp3}.
                //**special**
                if ($lira>=4){$choices=$choices|1;}
                $cardCount = 0;
                foreach ($privateHandCards[$playerId]['hand'] as $handKey => $handValue) {
                    if ($handValue['k']!=616 && ($handValue['t']=='yellowCard' || $handValue['t']=='blueCard' )){
                        $cardCount++;
                    }
                }
                if (count($playerWines)>0 && $cardCount>=3){$choices=$choices|2;}
                break;

            case 617: //Vendor
                //Draw 1 ${token_greenCardPlus} , 1 ${token_purpleCardPlus}, and 1 ${token_blueCardPlus}. Each opponent may draw 1 ${token_yellowCardPlus}.
                //drawGreenCard_1+drawPurpleCard_1+drawBlueCard_1
                $choices=$choices|1;
                break;

            case 618: //Handyman
                //All players may build 1 structure at a ${token_lira2} discount. You gain ${token_vp1} for each opponent who does this.
                //**special**
                $foundStructure=false;
                foreach ($playerTokens as $playerTokensKey => $playerTokensValue) {
                    foreach ($playersFullData as $playersFullDataKey => $playersFullDataValue) {
                        //enough money and buildable
                        if ($playerTokensValue['isBuilding'] && $playersFullDataValue['lira']>=$playerTokensValue['price']-2 && $this->checkBuildableBuilding($playersFullDataKey, $playerTokensValue['type'], $playerTokensValue['price']-2)==true){
                            $foundStructure = true;
                        }
                    }
                }
                if ($foundStructure){
                    $choices=$choices|1;
                }
                break;

            case 619: //Horticulturist
                //Plant 1 ${token_greenCard} even if you don't have the required structure(s) OR uproot and discard 2 ${token_greenCard} to gain ${token_vp3}.
                //**special**
                $possibleGreenCards = $this->readPossibleGreenCards($playerId, $playerFullData, $tokens, $privateHandCards, false, true);
                if (count($possibleGreenCards)>0){
                    $choices=$choices|1;
                }
                if (count($playerFullData['vine1'])+count($playerFullData['vine2'])+count($playerFullData['vine3'])>=2){
                    $choices=$choices|2;
                }
                break;

            case 620: //Peddler
                //Discard 2 ${token_anyCard} to draw 1 of each type of card.
                //**special**
                $cardCount = 0;
                foreach ($privateHandCards[$playerId]['hand'] as $handKey => $handValue) {
                    if ($handValue['k']!=616 ){
                        $cardCount++;
                    }
                }
                if ($cardCount>=2){
                    $choices=$choices|1;
                }
                break;

            case 621: //Banker
                //Gain ${token_lira5}. Each opponent may lose ${token_vp1} to gain ${token_lira3}.
                //**special**
                $choices=$choices|1;
                break;

            case 622: //Overseer
                //Build 1 structure at its regular cost and plant 1 ${token_greenCard}. If it is a 4-value ${token_greenCard}, gain ${token_vp1}.
                //buildStructure_1+plant_1_ifgreat4_1vp
                $possibleGreenCards = $this->readPossibleGreenCards($playerId, $playerFullData, $tokens, $privateHandCards, true, true);
                $foundStructure=false;
                if ($score>=-4){
                    foreach ($playerTokens as $playerTokensKey => $playerTokensValue) {
                        //enough money and buildable
                        if ($playerTokensValue['isBuilding'] && $lira>=$playerTokensValue['price'] && $this->checkBuildableBuilding($playerId, $playerTokensValue['type'], 0)==true){
                            $foundStructure = true;
                        }
                    }
                }
                if (count($possibleGreenCards)>0 || $foundStructure){
                    $choices=$choices|1;
                }
                break;

            case 623: //Importer
                //Draw 3 ${token_blueCard} cards unless all opponents combine to give you 3 visitor cards (total).
                //**special**
                $choices=$choices|1;
                break;

            case 624: //Sharecropper
                //Plant 1 ${token_greenCard} even if you don't have the required structure(s) OR uproot and discard 1 ${token_greenCard} to gain ${token_vp2}.
                //plant_1_noStructure|uprootAndDiscard_1+getVp_2
                $possibleGreenCards = $this->readPossibleGreenCards($playerId, $playerFullData, $tokens, $privateHandCards, false, true);
                if (count($possibleGreenCards)>0){
                    $choices=$choices|1;
                }
                if (count($playerFullData['vine1'])+count($playerFullData['vine2'])+count($playerFullData['vine3'])>=1){
                    $choices=$choices|2;
                }
                break;

            case 625: //Grower
                //Plant 1 ${token_greenCard}. Then, if you have planted a total of at least 6 ${token_greenCard}, gain ${token_vp2}.
                //plant_1_iftotalgreat_6_vp2
                $possibleGreenCards = $this->readPossibleGreenCards($playerId, $playerFullData, $tokens, $privateHandCards, true, true);
                if (count($possibleGreenCards)>0){
                    $choices=$choices|1;
                }
                break;

            case 626: //Negotiator
                //Discard 1 ${token_grapeAny} to gain ${token_residualPayment1} OR discard 1 ${token_wineAny} to gain ${token_residualPayment2} .
                //discardGrape_1+getResidualPayment_1|discardWine_1+getResidualPayment_2
                if (count($playerGrapes)>0 && $residual_payment<5){$choices=$choices|1;}
                if (count($playerWines)>0 && $residual_payment<5){$choices=$choices|2;}
                break;

            case 627: //Cultivator
                //Plant 1 ${token_greenCard}. You may plant it on a field even if the total value of that field exceeds the max vine value.
                //plant_1_overMax
                $possibleGreenCards = $this->readPossibleGreenCards($playerId, $playerFullData, $tokens, $privateHandCards, true, false);
                if (count($possibleGreenCards)>0){
                    $choices=$choices|1;
                }
                break;

            case 628: //Homesteader
                //Build 1 structure at a ${token_lira3} discount OR plant up to 2 ${token_greenCard}. You may lose ${token_vp1} to do both.
                //buildStructure_1_3|plant_2
                $firstChoice = $this->checkFirstOptionCardPlayed($playerId, $playerFullData, $cardKey);
                if ($firstChoice!=1){
                    $foundStructure=false;
                    foreach ($playerTokens as $playerTokensKey => $playerTokensValue) {
                        //enough money and buildable
                        if ($playerTokensValue['isBuilding'] && $lira>=$playerTokensValue['price']-3 && $this->checkBuildableBuilding($playerId, $playerTokensValue['type'], $playerTokensValue['price']-3)==true){
                            $foundStructure = true;
                        }
                    }
                    if ($foundStructure){
                        $choices=$choices|1;
                    }
                }
                if ($firstChoice!=2){
                    $possibleGreenCards = $this->readPossibleGreenCards($playerId, $playerFullData, $tokens, $privateHandCards, true, true);
                    if (count($possibleGreenCards)>0){
                        $choices=$choices|2;
                    }
                }
                break;

            case 629: //Planner
                //Place a worker on an action in a future season. Take that action at the beginning of that season.
                //**special**
                $season = self::getGameStateValue('season');
                //only seasons before winter
                if ($season<WINTER){
                    $playerAction = $this->readPlayerAction($playerId, STATUS_IN_PROGRESS);
                    $minimumWorkers = 1;
                    if ($playerAction==null){
                        //one to activate location and one to place
                        $minimumWorkers = 2;
                    }

                    $availableWorkers = $this->readAvailableWorkers($playerId);
                    if (count($availableWorkers)>=$minimumWorkers){
                        $choices=$choices|1;
                    }
                }
                break;

            case 630: //Agriculturist
                //Plant 1 ${token_greenCard}. Then, if you have at least 3 different types of ${token_greenCard} planted on that field, gain ${token_vp2}.
                //**special**
                $possibleGreenCards = $this->readPossibleGreenCards($playerId, $playerFullData, $tokens, $privateHandCards, true, true);
                if (count($possibleGreenCards)>0){
                    $choices=$choices|1;
                }
                break;

            case 631: //Swindler
                //Each opponent may give you ${token_lira2}. For each opponent who does not, gain ${token_vp1}.
                //**special**
                $choices=$choices|1;
                break;

            case 632: //Producer
                //Pay ${token_lira2} to retrieve up to 2 ${token_worker} from other actions. They may be used again this year.
                //**special**
                if ($lira>=2){
                    $usedWorkers = 0;
                    $locationToExclude = '';
                    $actionProgress = $this->readPlayerActionInProgress();
                    if ($actionProgress!= null ){
                        $locationToExclude = 'board_'.$actionProgress['args'];
                    }
                    foreach ($tokens[$playerId] as $tokensKey => $tokensValue) {
                        if ($this->startsWith($tokensValue['l'],'board') && $tokensValue['l'] != $locationToExclude && $this->startsWith($tokensValue['t'],'worker')){
                            $usedWorkers++;
                        }
                    }
                    if ($usedWorkers>0){
                        $choices=$choices|1;
                    }
                }
                break;

            case 633: //Organizer
                //Move your ${token_rooster} piece to an empty row on the wake-up chart, take the bonus, then pass to the next season.
                //**special**
                $choices=$choices|1;
                break;

            case 634: //Sponsor
                //Draw 2 ${token_greenCardPlus} OR gain ${token_lira3}. You may lose ${token_vp1} to do both.
                //drawGreenCard_2|getLira_3
                $firstChoice = $this->checkFirstOptionCardPlayed($playerId, $playerFullData, $cardKey);
                if ($firstChoice!=1){
                    $choices=$choices|1;
                }
                if ($firstChoice!=2){
                    $choices=$choices|2;
                }
                break;

            case 635: //Artisan
                //Choose 1: Gain ${token_lira3}, build a structure at a ${token_lira1} discount, or plant up to 2 ${token_greenCard}.
                //getLira_3|buildStructure_1_1|plant_2
                $choices=$choices|1;
                $foundStructure=false;
                foreach ($playerTokens as $playerTokensKey => $playerTokensValue) {
                    //enough money and buildable
                    if ($playerTokensValue['isBuilding'] && $lira>=$playerTokensValue['price']-1 && $this->checkBuildableBuilding($playerId, $playerTokensValue['type'], $playerTokensValue['price']-1)==true){
                        $foundStructure = true;
                    }
                }
                if ($foundStructure){
                    $choices=$choices|2;
                }
                $possibleGreenCards = $this->readPossibleGreenCards($playerId, $playerFullData, $tokens, $privateHandCards, true, true);
                if (count($possibleGreenCards)>0){
                    $choices=$choices|4;
                }
                break;

            case 636: //Stonemason
                //Pay ${token_lira8} to build any 2 structures (ignore their regular costs)
                //payLira_8+buildStructure_2_free
                $foundStructure=false;
                foreach ($playerTokens as $playerTokensKey => $playerTokensValue) {
                    //enough money and buildable
                    if ($playerTokensValue['isBuilding'] && $this->checkBuildableBuilding($playerId, $playerTokensValue['type'], 0)==true){
                        $foundStructure = true;
                    }
                }
                if ($lira>=8 && $foundStructure){
                    $choices=$choices|1;
                }
                break;

            case 637: //Volunteer Crew
                //All players may plant 1 ${token_greenCard}. Gain ${token_lira2} for each opponent who does this.
                //**special**
                //this card is playable even without players can plant
                /*$foundPlayer=false;
                foreach ($playersFullData as $playersFullDataKey => $playersFullDataValue) {
                    $possibleGreenCards = $this->readPossibleGreenCards($playersFullDataKey, $playersFullDataValue, $tokens, $privateHandCards, true, true);
                    if (count($possibleGreenCards)>0){
                        $foundPlayer=true;
                        break;
                    }
                }
                if ($foundPlayer){
                    $choices=$choices|1;
                }*/
                $choices=$choices|1;
                break;

            case 638: //Wedding Party
                //Pay up to 3 opponents ${token_lira2} each. Gain ${token_vp1} for each of those opponents.
                //**special**
                if ($lira>=2){
                    $choices=$choices|1;
                }
                break;

            default:
                throw new BgaUserException( self::_("Wrong card!") );
        }

        return $choices;
    }

    function checkBlueCard($playerId, $playerFullData, $cardType, $cardKey, $playersFullData, $tokens, $privateHandCards){
        $choices = 0;
        $card = $this->blueCards[$cardKey];
        $playerWines = $playerFullData['wines'];
        $playerGrapes = $playerFullData['grapes'];
        $playerTokens = $this->playerTokens;
        $lira = (int)$playerFullData['lira'];
        $score = (int)$playerFullData['score'];
        $residual_payment = (int)$playerFullData['residual_payment'];

        $grapesBitArray = $this->playerGrapesToBitArray($playerFullData);

        switch ($card['key']) {
            case 801: //Merchant
                //Pay ${token_lira3} to place a ${token_grapeRed1} and a ${token_grapeWhite1} on your crush pad OR fill 1 ${token_purpleCard} and gain ${token_vp1} extra.
                //payLira_3+getGrapeRed_1+getGrapeWhite_1|fillOrder_1+getVp_1
                if ($lira>=3 && ($grapesBitArray['grapeRed'][1]==0||$grapesBitArray['grapeWhite'][1]==0)){
                    $choices=$choices|1;
                }
                $purpleCards = $this->readPossiblePurpleCards($playerId, $playerFullData, $tokens, $privateHandCards, false);
                if (count($purpleCards)>0){
                    $choices=$choices|2;
                }
                break;

            case 802: //Crusher
                //Gain ${token_lira3} and draw 1 ${token_yellowCard} OR draw 1 ${token_purpleCard} and make up to 2 ${token_wineAny}.
                //GetLira_3+drawYellowCard_1|drawPurpleCard_1+makeWine_2
                $choices=$choices|1;
                $choices=$choices|2; //not required to make a wine
                break;

            case 803: //Judge
                //Draw 2 ${token_yellowCardPlus} OR discard 1 ${token_wineAny} of value 4 or more to gain ${token_vp3}.
                //drawYellowCard_2|discardWineAny_1_value4+getVp_3
                $choices=$choices|1;
                $found=false;
                foreach ($playerWines as $playerWinesKey => $playerWinesValue) {
                    if ($playerWinesValue['v']>=4){
                        $found = true;
                    }
                }
                if ($found){
                    $choices=$choices|2;
                }
                break;

            case 804: //Oenologist
                //Age all ${token_wineAny} in your cellar twice OR pay ${token_lira3} to upgrade your cellar to the next level.
                //ageWines_2|payLira_2+upgradeCellar
                if (count($playerWines)>0){$choices=$choices|1;}
                if ($lira>=3 && ($playerFullData['mediumCellar']==0 || $playerFullData['largeCellar']==0 )){$choices=$choices|2;}
                break;

            case 805: //Marketer
                //Draw 2 ${token_yellowCardPlus} and gain ${token_lira1} OR fill 1 ${token_purpleCard} and gain ${token_vp1} extra.
                //drawYellowCard_2+getLira_1|fillOrder_1+getVp_1
                $choices=$choices|1;
                $purpleCards = $this->readPossiblePurpleCards($playerId, $playerFullData, $tokens, $privateHandCards, false);
                if (count($purpleCards)>0){
                    $choices=$choices|2;
                }
                break;

            case 806: //Crush Expert
                //Gain ${token_lira3} and draw 1 ${token_purpleCard} OR make up to 3 ${token_wineAny}.
                //getLira_3+drawPurpleCard|makeWine_3
                $choices=$choices|1;
                $possibleWines = $this->readPossibleWineMakeable($playerId, $playerFullData, true, 1);
                if (count($possibleWines)>0){
                    $choices=$choices|2;
                }
                break;

            case 807: //Uncertified Teacher
                //Lose ${token_vp1} to train a ${token_worker} OR gain ${token_vp1} for each opponent who has a total of 6 ${token_worker}.
                //**special**
                $availableNewWorkers = $this->readAvailableNewWorkers($playerId);
                if ($score>=-4 && count($availableNewWorkers)>0){
                    $choices=$choices|1;
                }
                $found = false;
                foreach ($playersFullData as $playersDataKey => $playersDataValue){
                    //opponent
                    if ($playersDataKey != $playerId){
                        $workers = $this->readWorkers($playersDataKey);
                        if (count($workers)>=6){
                            $found = true;
                            break;
                        }
                    }
                }
                if ($found){
                    $choices=$choices|2;
                }

                break;

            case 808: //Teacher
                //Make up to 2 ${token_wineAny} OR pay ${token_lira2} to train 1 worker.
                //makeWine_2|trainWorker_1_price2
                $possibleWines = $this->readPossibleWineMakeable($playerId, $playerFullData, true, 1);
                if (count($possibleWines)>0){
                    $choices=$choices|1;
                }
                $availableNewWorkers = $this->readAvailableNewWorkers($playerId);
                if ($lira>=1 && count($availableNewWorkers)>0){
                    $choices=$choices|2;
                }
                break;

            case 809: //Benefactor
                //Draw 1 ${token_greenCard} and 1 ${token_yellowCard} card OR discard 2 visitor cards to gain ${token_vp2}.
                //drawGreenCard+drawYellowCard|discardCard_2+get2Vp
                $choices=$choices|1;
                $cardCount = 0;
                foreach ($privateHandCards[$playerId]['hand'] as $handKey => $handValue) {
                    if ($handValue['k']!=809 && ($handValue['t']=='yellowCard' || $handValue['t']=='blueCard' )){
                        $cardCount++;
                    }
                }
                if ($cardCount>=2){$choices=$choices|2;}
                break;

            case 810: //Assessor
                //Gain ${token_lira1} for each card in your hand OR discard your hand (min of 1 card) to gain ${token_vp2}.
                //**special**
                if (count($privateHandCards[$playerId]['hand'])>1){
                    $choices=$choices|1;
                    $choices=$choices|2;
                }
                break;

            case 811: //Queen
                //The player on your right must choose 1: lose ${token_vp1}, give you 2 ${token_anyCard}, or pay you ${token_lira3}.
                //**special**
                $previousPlayerId = $this->getPreviousPlayer($playerId, true);
                $previousPlayerData = $playersFullData[$previousPlayerId];
                if ($previousPlayerData['score']>=-4 || count($privateHandCards[$previousPlayerId]['hand'])>=2||$previousPlayerData['score']>=3){
                    $choices=$choices|1;
                }
                break;

            case 812: //Harvester
                //Harvest up to 2 fields and choose 1: Gain ${token_lira2} or gain ${token_vp1}.
                //harvestField_2+getLira_2|harvestField_2+getVp_1
                $checkHarvest = $this->checkActionHarvest($playerId, $playerFullData, $tokens, true, true);
                if ($checkHarvest>0){
                    $choices=$choices|1;
                    $choices=$choices|2;
                }
                break;

            case 813: //Professor
                //Pay ${token_lira2} to train 1 ${token_worker} OR gain ${token_vp2} if you have a total of 6 ${token_worker}.
                //**special**
                $availableNewWorkers = $this->readAvailableNewWorkers($playerId);
                $workers = $this->readWorkers($playerId);
                if ($lira>=2 && count($availableNewWorkers)>0){
                    $choices=$choices|1;
                }
                if (count($workers)>=6){
                    $choices=$choices|2;
                }
                break;

            case 814: //Master Vintner
                //Upgrade your cellar to the next level at a ${token_lira2} discount OR age 1 ${token_wineAny} and fill 1 ${token_purpleCard}.
                //upgradeCellar_discount2|ageWine1+fillOrder_1
                $foundStructure=false;
                foreach ($playerTokens as $playerTokensKey => $playerTokensValue) {
                    //enough money and buildable
                    if ( $playerTokensValue['type']=='mediumCellar' || $playerTokensValue['type']=='largeCellar' ){
                        if ($playerTokensValue['isBuilding'] && $lira>=$playerTokensValue['price']-2 && $this->checkBuildableBuilding($playerId, $playerTokensValue['type'], $playerTokensValue['price']-2)==true){
                            $foundStructure = true;
                        }
                    }
                }
                if ($foundStructure){
                    $choices=$choices|1;
                }
                $purpleCards = $this->readPossiblePurpleCards($playerId, $playerFullData, $tokens, $privateHandCards, true);
                if (count($purpleCards)>0){
                    $choices=$choices|2;
                }
                break;

            case 815: //Uncertified Oenologist
                //Age all ${token_wineAny} in your cellar twice OR lose ${token_vp1} to upgrade your cellar to the next level.
                //ageWines_2|payLVp_1+upgradeCellar
                if (count($playerWines)>0){$choices=$choices|1;}
                if ($score>=-4 && ($playerFullData['mediumCellar']==0 || $playerFullData['largeCellar']==0 )){$choices=$choices|2;}
                break;

            case 816: //Promoter
                //Discard a ${token_grapeAny} or ${token_wineAny} to gain ${token_vp1} and ${token_residualPayment1}.
                //discardGrapeAny_1+getVp_1+getResidualPayment_1|discardWineAny_1+getVp_1+getResidualPayment_1|
                if (count($playerGrapes)>0){$choices=$choices|1;}
                if (count($playerWines)>0){$choices=$choices|2;}
                break;

            case 817: //Mentor
                //All players may make up to 2 ${token_wineAny}. Draw 1 ${token_greenCardPlus} or ${token_YellowCardPlus} card for each opponent who does this.
                //**special**
                $found = false;
                foreach ($playersFullData as $playersDataKey => $playersDataValue) {
                    $possibleWines = $this->readPossibleWineMakeable($playersDataKey, $playersDataValue, true, 1);
                    if (count($possibleWines)>0){
                        $found = true;
                        break;
                    }
                }
                if ($found){
                    $choices=$choices|1;
                }
                break;

            case 818: //Harvest Expert
                //Harvest 1 field and either draw 1 ${token_greenCardPlus} or pay ${token_lira1} to build a Yoke.
                //harvestField_1+drawGreenCard_1|harvestField_1+buildStructure_1_yoke_price1
                $checkHarvest = $this->checkActionHarvest($playerId, $playerFullData, $tokens, true, true);
                if ($checkHarvest>0){
                    $choices=$choices|1;
                }
                $foundStructure=false;
                foreach ($playerTokens as $playerTokensKey => $playerTokensValue) {
                    //enough money and buildable
                    if ( $playerTokensValue['type']=='yoke' ){
                        if ($playerTokensValue['isBuilding'] && $lira>=1 && $this->checkBuildableBuilding($playerId, $playerTokensValue['type'], 1)==true){
                            $foundStructure = true;
                        }
                    }
                }
                if ($checkHarvest>0 && $foundStructure){
                    $choices=$choices|2;
                }
                break;

            case 819: //Innkeeper
                //As you play this card, put the top card of 2 different discard piles in your hand.
                //GetDiscardCard_2
                $count = $this->readCountDeckCards();
                $discardDecks = 0;
                if ($count[DISCARD_GREEN]>0){$discardDecks++;}
                if ($count[DISCARD_YELLOW]>0){$discardDecks++;}
                if ($count[DISCARD_PURPLE]>0){$discardDecks++;}
                if ($count[DISCARD_BLUE]>0){$discardDecks++;}
                if ($discardDecks>=2){
                    $choices=$choices|1;
                }
                break;

            case 820: //Jack-of-all-trades
                //Choose 2: Harvest 1 field, make up to 2 ${token_wineAny}, or fill 1 ${token_purpleCard}.
                //HarvestField_1|makeWine_2|fillOrder_1

                $firstChoice = $this->checkFirstOptionCardPlayed($playerId, $playerFullData, $cardKey);
                if ($firstChoice!=1){
                    $checkHarvest = $this->checkActionHarvest($playerId, $playerFullData, $tokens, true, true);
                    if ($checkHarvest>0){
                        $choices=$choices|1;
                    }
                }
                if ($firstChoice!=2){
                    $possibleWines = $this->readPossibleWineMakeable($playerId, $playerFullData, true, 1);
                    if (count($possibleWines)>0){
                        $choices=$choices|2;
                    }
                }
                if ($firstChoice!=3){
                    $purpleCards = $this->readPossiblePurpleCards($playerId, $playerFullData, $tokens, $privateHandCards, false);
                    if (count($purpleCards)>0){
                        $choices=$choices|4;
                    }
                }
                break;

            case 821: //Politician
                //If you have less than 0${token_vp}, gain ${token_lira6}. Otherwise, draw 1 ${token_greenCardPlus}, 1 ${token_yellowCardPlus}, and 1 ${token_purpleCardPlus}.
                //**special**
                if ($score>0){
                    $choices=$choices|1;
                } else {
                    $choices=$choices|2;
                }
                break;

            case 822: //Supervisor
                //Make up to 2 ${token_wineAny}. Gain${token_vp1} for each sparkling wine token you make.
                //makeWine_2_ifmakesparklingwineeach_1vp
                $possibleWines = $this->readPossibleWineMakeable($playerId, $playerFullData, true, 1);
                if (count($possibleWines)>0){
                    $choices=$choices|1;
                }
                break;

            case 823: //Scholar
                //Draw 2 ${token_purpleCard} OR pay ${token_lira3} to train 1 ${token_worker}. You may lose ${token_vp1} to do both.
                //drawPurpleCard_2|trainWorker_1_price1
                $firstChoice = $this->checkFirstOptionCardPlayed($playerId, $playerFullData, $cardKey);
                if ($firstChoice!=1){
                    $choices=$choices|1;
                }
                if ($firstChoice!=2){
                    $availableNewWorkers = $this->readAvailableNewWorkers($playerId);
                    if ($lira>=3 && count($availableNewWorkers)>0){
                        $choices=$choices|2;
                    }
                }
                break;

            case 824: //Reaper
                //Harvest up to 3 fields. If you harvest 3 fields, gain ${token_vp2}.
                //harvestField_3_ifharvested3fields_2vp
                $checkHarvest = $this->checkActionHarvest($playerId, $playerFullData, $tokens, true, true);
                if ($checkHarvest>0){
                    $choices=$choices|1;
                }
                break;

            case 825: //Motivator
                //Each player may retrieve their grande worker. Gain ${token_vp1} for each opponent who does this.
                //**special**
                $grandeUsedOtherPlayers=false;
                $grandeAvailableToPlayer=false;
                //check if players played grande worker
                //or if the active player can place grande worker (no other action currently in play)
                $progress=$this->readPlayerActionInProgress();
                foreach ($playersFullData as $playersDataKey => $playersDataValue) {
                    $availableWorkers = $this->readAvailableWorkers($playersDataKey);
                    $grandeUsed=true;
                    foreach ($availableWorkers as $availableWorkersKey => $availableWorkersValue) {
                        if ($availableWorkersValue['type']=='worker_g'){
                            $grandeUsed=false;
                            //grande worker available and in standard choose location, not in an subsequent action
                            if ($playersDataKey==$playerId && $progress==null){
                                $grandeAvailableToPlayer=true;
                            }
                            break;
                        }
                    }
                    if ($grandeUsed==true){
                        $grandeUsedOtherPlayers=true;
                    }
                }
                if ($grandeUsedOtherPlayers==true||$grandeAvailableToPlayer==true){
                    $choices=$choices|1;
                }
                break;

            case 826: //Bottler
                //Make up to 3 ${token_wineAny}. Gain ${token_vp1} for each type of wine you make.
                //makeWine_3_ifdistincttype_get1vp **needs history of wines**
                $possibleWines = $this->readPossibleWineMakeable($playerId, $playerFullData, true, 1);
                if (count($possibleWines)>0){
                    $choices=$choices|1;
                }
                break;

            case 827: //Craftsman
                //Choose 2: Draw 1 ${token_purpleCard}, upgrade your cellar at the regular cost, or gain ${token_vp1}.
                //drawPurpleCard_1|upgradeCellar|getVp_1
                $firstChoice = $this->checkFirstOptionCardPlayed($playerId, $playerFullData, $cardKey);
                if ($firstChoice!=1){
                    $choices=$choices|1;
                }
                if ($firstChoice!=2){
                    $foundStructure=false;
                    foreach ($playerTokens as $playerTokensKey => $playerTokensValue) {
                        //enough money and buildable
                        if ( $playerTokensValue['type']=='mediumCellar' || $playerTokensValue['type']=='largeCellar' ){
                            if ($playerTokensValue['isBuilding'] && $lira>=$playerTokensValue['price'] && $this->checkBuildableBuilding($playerId, $playerTokensValue['type'], $playerTokensValue['price'])==true){
                                $foundStructure = true;
                            }
                        }
                    }
                    if ($foundStructure){
                        $choices=$choices|2;
                    }
                }
                if ($firstChoice!=3){
                    $choices=$choices|4;
                }
                break;

            case 828: //Exporter
                //Choose 1: Make up to 2 ${token_wineAny}, fill 1 ${token_purpleCard}, or discard 1 ${token_grapeAny} to gain ${token_vp2}.
                //makeWine_2|fillOrder_1|discardGrapeAny+getVp_2
                $possibleWines = $this->readPossibleWineMakeable($playerId, $playerFullData, true, 1);
                if (count($possibleWines)>0){
                    $choices=$choices|1;
                }
                $purpleCards = $this->readPossiblePurpleCards($playerId, $playerFullData, $tokens, $privateHandCards, false);
                if (count($purpleCards)>0){
                    $choices=$choices|2;
                }
                if (count($playerGrapes)>0){
                    $choices=$choices|4;
                }
                break;

            case 829: //Laborer
                //Harvest up to 2 fields OR make up to 3 ${token_wineAny}. You may lose ${token_vp1} to do both.
                //harvestField_2|makeWine_3
                $firstChoice = $this->checkFirstOptionCardPlayed($playerId, $playerFullData, $cardKey);
                if ($firstChoice!=1){
                    $checkHarvest = $this->checkActionHarvest($playerId, $playerFullData, $tokens, true, true);
                    if ($checkHarvest>0){
                        $choices=$choices|1;
                    }
                }
                if ($firstChoice!=2){
                    $possibleWines = $this->readPossibleWineMakeable($playerId, $playerFullData, true, 1);
                    if (count($possibleWines)>0){
                        $choices=$choices|2;
                    }
                }
                break;

            case 830: //Designer
                //Build 1 structure at its regular cost. Then, if you have at least 6 structures, gain ${token_vp2}.
                //buildStructure_1_ifstructuturesgt_6_vp2
                $foundStructure=false;
                foreach ($playerTokens as $playerTokensKey => $playerTokensValue) {
                    //enough money and buildable
                    if ($playerTokensValue['isBuilding'] && $lira>=$playerTokensValue['price'] && $this->checkBuildableBuilding($playerId, $playerTokensValue['type'], $playerTokensValue['price'])==true){
                        $foundStructure = true;
                    }
                }
                if ($foundStructure){
                    $choices=$choices|1;
                }
                break;

            case 831: //Governess
                //Pay ${token_lira3} to train 1 ${token_worker} that you may use this year OR discard 1 ${token_wineAny} to gain ${token_vp2}.
                //**special**
                $availableNewWorkers = $this->readAvailableNewWorkers($playerId);
                if ($lira>=3 && count($availableNewWorkers)>0){
                    $choices=$choices|1;
                }
                if (count($playerWines)>0){
                    $choices=$choices|2;
                }
                break;

            case 832: //Manager
                //Take any action (no bonus) from a previous season without placing a worker.
                //**special**
                $season = self::getGameStateValue('season');
                if ($season > $this->getFirstWorkersSeason()){
                    $choices=$choices|1;
                }
                break;

            case 833: //Zymologist
                //Make up to 2 ${token_wineAny} of value 4 or greater, even if you haven't upgraded your cellar.
                //makeWine_2_value4withouthmediumcellar
                $possibleWines = $this->readPossibleWineMakeable($playerId, $playerFullData, false, 4);
                if (count($possibleWines)>0){
                    $choices=$choices|1;
                }
                break;

            case 834: //Noble
                //Pay ${token_lira1} to gain ${token_residualPayment1} OR lose ${token_residualPayment2} to gain ${token_vp2}.
                //payLira_1+getResidualPayment_1|payResidualPayment_2+getVp_2
                if ($lira>=1 && $residual_payment<5){$choices=$choices|1;}
                if ($residual_payment>=2){$choices=$choices|2;}
                break;

            case 835: //Governor
                //Choose up to 3 opponents to each give you 1 ${token_yellowCard}. Gain ${token_vp1} for each of them who cannot.
                //**special**
                $choices=$choices|1;
                break;

            case 836: //Taster
                //Discard 1 ${token_wineAny} to gain ${token_lira4}. If it is the most valuable wine token in any player's cellar (no ties), gain 2 ${token_vp2}.
                //**special**
                if (count($playerWines)>0){
                    $choices=$choices|1;
                }
                break;

            case 837: //Caravan
                //Turn the top card of each deck face up. Draw 2 of those cards and discard the others.
                //**special** requires state with first card of deck
                $choices=$choices|1;
                break;

            case 838: //Guest Speaker
                //All players may pay ${token_lira1} to train ${token_worker}. Gain ${token_vp1} for each opponent who does this.
                //**special**
                foreach ($playersFullData as $playersDataKey => $playersDataValue) {
                    $availableNewWorkers = $this->readAvailableNewWorkers($playersDataKey);
                    if ($playersDataValue['lira']>=1 && count($availableNewWorkers)>0){
                        $choices=$choices|1;
                        break;
                    }
                }
                break;

            default:
                throw new BgaUserException( self::_("Wrong card!") );
        }

        return $choices;

    }

    function getFirstWorkersSeason(){
        //TO BE CHANGED WITH TUSCANY
        return SUMMER;
    }

    function checkPurpleCardWines($playerId, $playerFullData, $cardType, $cardKey, $simulateAgeOneWine){
        $agedOneWine=false;

        $cardWines = $this->purpleCardWinesToArray($cardKey);

        $playerWines = $playerFullData['wines'];

        //order by value (first lesser value, than greater)
        $playerWines = $this->arrayOrderBy($playerWines,"v", true, true);
        foreach ($playerWines as $playerWinesKey => $playerWinesValue) {
            //setting used to false
            $playerWines[$playerWinesKey]['u']=false;
        }

        foreach ($cardWines as $cardWinesKey => $cardWinesValue) {
            $wineFound = false;
            foreach ($playerWines as $playerWinesKey => $playerWinesValue) {
                //if not used
                // and same type
                // and value >= offer
                if ($playerWines[$playerWinesKey]['u']==false
                && $playerWines[$playerWinesKey]['t']==$cardWinesValue['t']
                && $playerWines[$playerWinesKey]['v']>=$cardWinesValue['v']){
                    $playerWines[$playerWinesKey]['u'] = true;
                    $wineFound = true;
                    //found wine, exit from loop on player wines
                    break;
                }
            }
            //try aging a wine...
            if ($simulateAgeOneWine){
                if (!$wineFound && $agedOneWine==false){
                    foreach ($playerWines as $playerWinesKey => $playerWinesValue) {
                        //if not used
                        // and same type
                        // and value +1 >= offer
                        if ($playerWines[$playerWinesKey]['u']==false
                        && $playerWines[$playerWinesKey]['t']==$cardWinesValue['t']
                        && $playerWines[$playerWinesKey]['v']+1==$cardWinesValue['v']){
                            if ($playerWines[$playerWinesKey]['v']==3 && $playerFullData['mediumCellar']==0){
                                //nope
                            } else if ($playerWines[$playerWinesKey]['v']==6 && $playerFullData['largeCellar']==0){
                                //nope 
                            } else {
                                //ok
                                $playerWines[$playerWinesKey]['u'] = true;
                                $wineFound = true;
    
                                //can age only one wine
                                $agedOneWine = true;
                                //found wine, exit from loop on player wines
                                break;
                            }
                        }
                    }
                }
            }

            if (!$wineFound){
                return false;
            }
        }

        return true;
    }

    function purpleCardWinesToArray($cardKey){
        $result = array();
        $card = $this->purpleCards[$cardKey];
        if ($card['red1']>0){
            $result[] = array('t'=>'wineRed', 'v'=>$card['red1']);
        }
        if ($card['red2']>0){
            $result[] = array('t'=>'wineRed', 'v'=>$card['red2']);
        }
        if ($card['red3']>0){
            $result[] = array('t'=>'wineRed', 'v'=>$card['red3']);
        }
        if ($card['white1']>0){
            $result[] = array('t'=>'wineWhite', 'v'=>$card['white1']);
        }
        if ($card['white2']>0){
            $result[] = array('t'=>'wineWhite', 'v'=>$card['white2']);
        }
        if ($card['white3']>0){
            $result[] = array('t'=>'wineWhite', 'v'=>$card['white3']);
        }
        if ($card['blush1']>0){
            $result[] = array('t'=>'wineBlush', 'v'=>$card['blush1']);
        }
        if ($card['blush2']>0){
            $result[] = array('t'=>'wineBlush', 'v'=>$card['blush2']);
        }
        if ($card['sparkling']>0){
            $result[] = array('t'=>'wineSparkling', 'v'=>$card['sparkling']);
        }
        return $result;
    }

    function readPlayerTokensBySet(){
        $result = array();
        $playersNumber = self::getPlayersNumber();
        foreach ($this->playerTokens as $key => $value) {
            if ($this->isComponentPlayableBySet($value['set'])){
                $result[]=$value;
            }
        }
        return $result;
    }

    function readChooseMamaPapa(){
        $result = array();
        $playersData = $this->getPlayersData();
        foreach ($playersData as $playerId => $playerData) {
            $result[$playerId] = array(
                "mama"=>array(),
                "papa"=>array()
            );
        }
        //read mamas and papas and assign values to player data mama and papa
        $sql = "SELECT card_location_arg playerId, card_type, card_type_arg FROM card WHERE card_location in ('choiceMamas','choicePapas')";
        $mamasPapas = self::getObjectListFromDB( $sql);
        foreach ($mamasPapas as $mamasPapasKey => $mamasPapasValue) {
            $result[$mamasPapasValue['playerId']][$mamasPapasValue['card_type']][]=$mamasPapasValue['card_type_arg'];
        }
        return $result;
    }

    function readMamas(){
        $results = array();
        $sql = "SELECT card_location_arg playerId, card_type, card_type_arg FROM card WHERE card_location in ('choiceMamas','mama')";
        $rows = self::getObjectListFromDB( $sql);
        foreach ($rows as $key => $value) {
            $results[$value['card_type_arg']]=$this->mamas[$value['card_type_arg']];
        }
        return $results;
    }

    function readPapas(){
        $results = array();
        $sql = "SELECT card_location_arg playerId, card_type, card_type_arg FROM card WHERE card_location in ('choicePapas','papa')";
        $rows = self::getObjectListFromDB( $sql);
        foreach ($rows as $key => $value) {
            $results[$value['card_type_arg']]=$this->papas[$value['card_type_arg']];
        }
        return $results;
    }

    function readHistory(){
        $results = array();
        $sql = "SELECT card_location_arg playerId, card_type, card_type_arg, card_location FROM card WHERE card_location like 'history%' order by card_id ";
        $rows = self::getObjectListFromDB( $sql);
        foreach ($rows as $key => $value) {
            //'history_'.$moveNumber.'_'.$turn.'_'.$season
            $cardLocationParts = explode('_',$value['card_location']);
            $moveNumber=0;
            $turn=0;
            $season=0;
            if (count($cardLocationParts)>1){
                $turn = $cardLocationParts[1];
            }
            if (count($cardLocationParts)>2){
                $season = $cardLocationParts[2];
            }
            if (count($cardLocationParts)>3){
                $moveNumber = $cardLocationParts[3];
            }
            $results[]=$value['playerId'].'_'.$value['card_type_arg'].'_'.$turn.'_'.$season.'_'.$moveNumber;
        }
        return $results;
    }

    function getWineName($wine){
        if ($wine=='red'||$wine=='wineRed'){
            return clienttranslate('Red Wine');
        } else if ($wine=='white'||$wine=='wineWhite'){
            return clienttranslate('White Wine');
        } else if ($wine=='blush'||$wine=='wineBlush'){
            return clienttranslate('Blush Wine');
        } else if ($wine=='sparkling'||$wine=='wineSparkling'){
            return clienttranslate('Sparkling Wine');
        }
        return $wine;
    }

    function getGrapeName($grape){
        if ($grape=='red'||$grape=='grapeRed'){
            return clienttranslate('Red Grape');
        } else if ($grape=='white'||$grape=='grapeWhite'){
            return clienttranslate('White Grape');
        }
        return $grape;
    }

    function ageGrapesWinesAndGetResidualPayments(){
        $players = $this->getPlayersFullData();
        foreach ($players as $playerId=> $playerFullData){
            //age grapes and wines
            $this->ageGrapesPlayer($playerId, $playerFullData);
            $this->ageWinesPlayer($playerId, $playerFullData);
            //residual payments
            if ($playerFullData['residual_payment']>0){
                $liraBefore = (int)$playerFullData['lira'];
                $this->dbIncLira($playerId, $playerFullData['residual_payment']);
                $liraAfter =  $this->getUniqueValueFromDB("SELECT lira FROM player WHERE player_id='$playerId'");
                
                // Notify all players
                self::notifyAllPlayers( "getResidualPayment", clienttranslate( '${player_name} gets ${residualPayment}${token_lira} from residual payments and now has ${liraAfter}${token_lira}' ), array(
                    'player_id' => $playerId,
                    'player_name' => $playerFullData['player_name'],
                    'residualPayment' => $playerFullData['residual_payment'],
                    'token_lira'  => 'lira',
                    'liraBefore'  => $liraBefore,
                    'liraAfter'  => $liraAfter
                ) );
            }
            
        }

        $players = $this->getPlayersFullData();

        // Notify all players
        self::notifyAllPlayers( "ageGrapesWinesAndGetResidualPayments", clienttranslate( "All players' grapes and wine age. All residual payments are paid" ), array(
            'player_id' => $playerId,
            'players' => $players
        ) );
    }

    function ageGrapesPlayer($playerId, $playerFullData){
        $grapes = $playerFullData['grapes'];

        //order from greater value to lesser value
        $grapes = $this->arrayOrderBy($grapes ,'v', false, true);

        foreach ($grapes as $grapesKey => $grapesValue) {
            if ($grapes[$grapesKey]['v'] < 9){
                $occupied=false;
                foreach ($grapes as $otherGrapeKey => $otherGrapeValue) {
                    if ($otherGrapeValue['t'] == $grapes[$grapesKey]['t'] && $otherGrapeValue['v'] == $grapes[$grapesKey]['v']+1){
                        $occupied = true;
                    }
                }
                if (!$occupied){
                    $grapes[$grapesKey]['v']++;
                    $cardTypeArg = $grapes[$grapesKey]['v'];
                    $cardId =  $grapes[$grapesKey]['i'];
                    $this->DbQuery("UPDATE card SET card_type_arg=$cardTypeArg where card_id=$cardId");
                }
            }
        }
    }

    function ageWinesPlayer($playerId, $playerFullData, $wineFilter='', $wineValueFilter=0){
        $winesParams = $this->wines[$playerFullData['mediumCellar']+$playerFullData['largeCellar']];
        $wines = $playerFullData['wines'];
        //order from greater value to lesser value
        $wines = $this->arrayOrderBy($wines ,'v', false, true);
        foreach ($wines as $winesKey => $winesValue) {
            //all wines or only one wine
            if ($wineFilter=='' || ($wines[$winesKey]['v']==$wineValueFilter && $wines[$winesKey]['t']==$wineFilter)){
                //if a wine is in 4-6 or 7-9 then it can age
                //https://boardgamegeek.com/thread/1707435/zymologist
                if ($wines[$winesKey]['v']==9){
                    //max value
                } else if (($wines[$winesKey]['v'] < $winesParams[$wines[$winesKey]['t']]['max'])||
                           ($wines[$winesKey]['v']==4)||($wines[$winesKey]['v']==5)||($wines[$winesKey]['v']==7)||($wines[$winesKey]['v']==8)){
                    $occupied=false;
                    foreach ($wines as $otherWineKey => $otherWineValue) {
                        if ($otherWineValue['t'] == $wines[$winesKey]['t'] && $otherWineValue['v'] == $wines[$winesKey]['v']+1){
                            $occupied = true;
                        }
                    }
                    if (!$occupied){
                        $wines[$winesKey]['v']++;
                        $cardTypeArg = $wines[$winesKey]['v'];
                        $cardId =  $wines[$winesKey]['i'];
                        $this->DbQuery("UPDATE card SET card_type_arg=$cardTypeArg where card_id=$cardId");
                    }
                }
            }
        }
    }

    /**
     * checks if it's the last turn (when any player reaches or exceeds 20 victory)
     */
    function checkIfPlayersCauseEndGame($playersData, $setGameEndSolo){
        $result = array();
        $gameEnd = self::getGameStateValue('game_end');
        
        if ($this->checkIfSoloMode()==0){
            
            //When a player reaches 20 points, the current year will be played through the year end, and the player with the most points wins.
            if ($gameEnd==1){
                foreach ($playersData as $playerId => $playerData) {
                    if ($playerData['score'] >= END_GAME_SCORING){
                        $result[] = array('id'=>$playerData['id'], 'player_name'=>$playerData['player_name'], 'player_color'=>$playerData['player_color'], 'score'=>$playerData['score']);
                    }
                }
            } else {
                foreach ($playersData as $playerId => $playerData) {
                    if ($playerData['score'] >= END_GAME_SCORING){
                        $result[] = array('id'=>$playerData['id'], 'player_name'=>$playerData['player_name'], 'player_color'=>$playerData['player_color'], 'score'=>$playerData['score']);
                    }
                }
                if (count($result)>0){
                    self::setGameStateValue('game_end', 1);
                }
        
                $result = $this->arrayOrderBy($result,"score", false, false);
            }   

        } else {
            
            $turn = self::getGameStateValue('turn');
            $soloParameters =  $this->soloParameters[$this->getSoloDifficulty()];
            $aggressive = $this->getSoloAggressive();

            if ($turn == $soloParameters['turns']){

                $automaScore = $this->getAutomaScore();
                $playerId = $this->array_key_first($playersData);
                $playerData = $playersData[$playerId];

                if ($playerData['score']>$automaScore){
                    $result[] = array('id'=>$playerData['id'], 'player_name'=>$playerData['player_name'], 'player_color'=>$playerData['player_color'], 'score'=>$playerData['score'] ,'endGameType'=>1);
                    $result[] = array('id'=>SOLO_PLAYER_ID, 'player_name'=>'Automa', 'player_color'=>$this->getAutomaColor(), 'score'=>$automaScore, 'endGameType'=>1 );
                } else {
                    $result[] = array('id'=>SOLO_PLAYER_ID, 'player_name'=>'Automa', 'player_color'=>$this->getAutomaColor(), 'score'=>$automaScore, 'endGameType'=>2 );
                    $result[] = array('id'=>$playerData['id'], 'player_name'=>$playerData['player_name'], 'player_color'=>$playerData['player_color'], 'score'=>$playerData['score'] ,'endGameType'=>2);
                }

            } else if ($aggressive==1){

                $automaScore = $this->getAutomaScore();
                $playerId = $this->array_key_first($playersData);
                $playerData = $playersData[$playerId];

                if ($automaScore >= $playerData['score']){
                    $result[] = array('id'=>SOLO_PLAYER_ID, 'player_name'=>'Automa', 'player_color'=>$this->getAutomaColor(), 'score'=>$automaScore, 'endGameType'=>3 );
                }
            }


            if ($setGameEndSolo && count($result) > 0){
                self::setGameStateValue('game_end', 1);
            }
        }

        return $result;
    }

    /**
     * checks if playing in solo mode
     */
    function checkIfSoloMode(){
        return self::getGameStateValue('solo');
    }

    /**
     * gets solo mode difficulty
     */
    function getSoloDifficulty(){
        return self::getGameStateValue('soloDifficulty');
    }

    /**
     * gets solo mode aggressive
     */
    function getSoloAggressive(){
        return self::getGameStateValue('soloAggressive');
    }

    /**
     * calculate tie breaker score
     * The player with the most points wins. In the case of a tie,
     * tiebreakers are (in order):
     * 1. Most lira
     * 2. Total value of wine in the cellar
     * 3. Total value of grapes on the crush pad
     */
    function processTieBreakerScore($playersFullData){

        foreach ($playersFullData as $playerId => $playerData) {
            $wines = $playerData['wines'];
            $grapes = $playerData['grapes'];

            $auxScore = 0;
            $auxScore += $playerData['lira']*100000;

            $totalWine = 0;
            foreach ($wines as $winesKey => $winesValue) {
                $totalWine += $winesValue['v'];
            }
            $auxScore += $totalWine*100;

            $totalGrape = 0;
            foreach ($grapes as $grapesKey => $grapesValue) {
                $totalGrape += $grapesValue['v'];
            }
            $auxScore += $totalGrape;

            $this->dbSetAuxScore($playerId, $auxScore);
        }
    }

    /*
        order an array by field and direction
    */
    function arrayOrderBy(&$data, $field, $ascendent, $mantainsKeyAssociations)
    {
      if ($ascendent){
          $code = "return strnatcmp(\$a['$field'], \$b['$field']);";
      } else {
          $code = "return strnatcmp(\$b['$field'], \$a['$field']);";
      }
      if ($mantainsKeyAssociations){
          uasort($data, create_function('$a,$b', $code));
      } else {
          usort($data, create_function('$a,$b', $code));
      }

      return $data;
    }

    /*
        get next element in array based on key and eventually cycling to first if no more elements found
    */
    function arrayGetNext($array, $key, $cycle) {
       $currentKey = key($array);
       while ($currentKey !== null && $currentKey != $key) {
           next($array);
           $currentKey = key($array);
       }

       $result = next($array);
       if ($cycle === true && $result === false){
           $result = reset($array);
       }

       return $result;
    }

    /**
     * finds an element in array by property a
     */
    function arrayFindByProperty($arrayToSearch, $property, $value){
        if ($arrayToSearch == null){
            return null;
        }

        foreach ($arrayToSearch as $arrayToSearchKey => $arrayToSearchValue) {
            if ($arrayToSearchValue[$property]==$value){
                return $arrayToSearchValue;
            }
        }

        return null;
    }

    /**
     * Function that groups an array of associative arrays by some key.
     *
     * @param {String} $key Property to sort by.
     * @param {Array} $data Array that stores multiple associative arrays.
     */
    function group_by($key, $data) {
        $result = array();

        foreach($data as $val) {
            if(array_key_exists($key, $val)){
                $result[$val[$key]][] = $val;
            }else{
                $result[""][] = $val;
            }
        }

        return $result;
    }


    function array_key_first($array) {
        return $array ? array_keys($array)[0] : null;
    }

    function array_key_last($array) {
        if (!is_array($array) || empty($array)) {
            return NULL;
        }

        return array_keys($array)[count($array)-1];
    }

    function readCardsByCardType($cardType){
        //read deck
        $sql = "SELECT card_id id, card_type type, card_type_arg type_arg, card_location location, card_location_arg location_arg FROM card WHERE card_type='$cardType' order by type_arg";
        return self::getObjectListFromDB( $sql);
    }

    function readCardByPlayerIdAndCardId($playerId, $cardId){
        //read deck
        $sql = "SELECT card_id id, card_type type, card_type_arg type_arg, card_location location, card_location_arg location_arg FROM card WHERE card_location_arg=$playerId and card_id=$cardId";
        return self::getObjectFromDb( $sql );
    }

    function readCardsByPlayerIdAndCardType($playerId, $cardType){
        //read deck
        $sql = "SELECT card_id id, card_type type, card_type_arg type_arg, card_location location, card_location_arg location_arg FROM card WHERE card_location_arg=$playerId and card_type='$cardType' order by type_arg";
        return self::getObjectListFromDB( $sql);
    }
    
    function readCardsByPlayerIdAndLocationAndCardType($playerId, $location, $cardType){
        //read deck
        $sql = "SELECT card_id id, card_type type, card_location location, card_location_arg location_arg FROM card WHERE card_location_arg=$playerId and card_location='$location' and card_type='$cardType'";
        return self::getObjectListFromDB( $sql);
    }

    function readCardsByPlayerIdAndCardTypeAndCardTypeArg($playerId, $cardType, $cardTypeArg){
        //read deck
        $sql = "SELECT card_id id, card_type type, card_location location, card_location_arg location_arg FROM card WHERE card_location_arg=$playerId and card_type='$cardType' and card_type_arg=$cardTypeArg";
        return self::getObjectListFromDB( $sql);
    }

    function readCardsByPlayerIdAndLocationAndCardTypeAndCardTypeArg($playerId, $location, $cardType, $cardTypeArg){
        //read deck
        $sql = "SELECT card_id id, card_type type, card_location location, card_location_arg location_arg FROM card WHERE card_location_arg=$playerId and card_location='$location' and card_type='$cardType' and card_type_arg=$cardTypeArg";
        return self::getObjectListFromDB( $sql);
    }

    /**
     * return the first player by playorder
     */
    function getFirstPlayer(){
        return $this->getPlayerByPlayOrder(1);
    }

    /**
     * return the last player by playorder
     */
    function getLastPlayer(){
        return $this->getPlayerByPlayOrder(self::getPlayersNumber());
    }

    /**
     * return the first player by wakeup_order
     */
    function getFirstPlayerByWakeupOrder(){
        $first = null;

        $players = $this->getAllNextActvivePlayersWithWakeupOrder(0, false);
        if (count($players)>0){
            $first = $players[0]['player_id'];
        }
        return $first;
    }

    /**
     * return the first player by playorder
     */
    function getPlayerByPlayOrder($playOrder){
        $sql = "SELECT p.player_id
        FROM player p
        WHERE p.playorder = $playOrder";
        $next = self::getUniqueValueFromDB( $sql);
        return $next;
    }

    /**
     * return the first player by wakeup_order
     */
    function getPlayerByWakeupOrder($wakeUpOrder){
        $sql = "SELECT p.player_id
        FROM player p
        WHERE p.wakeup_order = $wakeUpOrder";
        $next = self::getUniqueValueFromDB( $sql);
        return $next;
    }

    /**
     * return the next player by playorder
     */
    function getNextPlayer($playerId, $cycle){
        $sql = "SELECT p.player_id id
            FROM player p, player p2
            WHERE p.playorder = p2.playorder+1
            AND   p2.player_id = $playerId";
        $next = self::getUniqueValueFromDB( $sql);

        if ($next==null && $cycle){
            $next = $this->getFirstPlayer();
        }
        return $next;
    }

    /**
     * return the next player by playorder
     */
    function getPreviousPlayer($playerId, $cycle){
        $sql = "SELECT p.player_id id
            FROM player p, player p2
            WHERE p.playorder = p2.playorder-1
            AND   p2.player_id = $playerId";
        $previous = self::getUniqueValueFromDB( $sql);

        if ($previous==null && $cycle){
            $previous = $this->getLastPlayer();
        }
        return $previous;
    }

    /**
     * return the next player by wakeup_order
     */
    function getNextActivePlayerByWakeupOrder($playerId, $cycle){
        $next = null;
        $playerData = $this->getPlayerData($playerId);

        $nextPlayers = $this->getAllNextActvivePlayersWithWakeupOrder($playerData['wakeup_order'], $cycle);

        if (count($nextPlayers)>0){
            $next = $nextPlayers[0]['player_id'];
        }
        return $next;
    }

    function getAllNextActvivePlayersWithWakeupOrder($currentPlayerWakeupOrder, $cycle){

        $playersNumber = self::getPlayersNumber();

        $sql = "SELECT player_id, wakeup_order, wakeup_order  progr
            FROM player
            WHERE wakeup_order > $currentPlayerWakeupOrder
            AND   pass = 0 ";
        if ($cycle){
            $sql = $sql." UNION
            SELECT player_id, wakeup_order, wakeup_order + $playersNumber progr
            FROM player
            WHERE wakeup_order <= $currentPlayerWakeupOrder
            AND   pass = 0 ";
        }
        $sql = $sql." order by progr";
        $players = self::getObjectListFromDB($sql);

        return $players;
    }


    /*
     * Counts array element by property value
     */
    function countArrayByProperty($array, $property, $propertyValue){

        if ($array==0 || count($array)==0){
            return 0;
        }

        $count = 0;
        foreach ($array as $key => $value) {
            if (array_key_exists($property, $value)==true && $value[$property] == $propertyValue){
                $count = $count + 1;
            }
        }

        return $count;

    }

    function readBasicDataForClient(){
        $playersData = $this->getPlayersFullData();

        //read deck
        //DEBUG: only for debug
        //$sql = "SELECT * FROM card";
        //$_deck = self::getObjectListFromDB( $sql); //DEBUG
        //DEBUG
        //$duplicates = self::getObjectListFromDB("select card_location_arg, card_location, card_type, card_type_arg, count(*) from card where card_location in ('playerWines','playerGrapes') group by card_location_arg, card_location, card_type, card_type_arg having count(*)>1"); // NOI18N //DEBUG
        //if (count($duplicates)>0){
        //    throw new BgaUserException( self::_("Duplicate grape/wine!") );
        //}

        // return values:
        $result = array(
            'players' => $playersData,
            'turn' => self::getGameStateValue('turn'),
            'season' => self::getGameStateValue('season'),
            'tokens' => $this->readTokens(),
            'pceg' => $this->checkIfPlayersCauseEndGame($playersData, false),
             'gameEnd' => self::getGameStateValue('game_end'),
            '_private' =>  $this->readPlayersPrivateHand($playersData),
            'cdc' => $this->readCountDeckCards(),
            'tdd' => $this->readTopDiscardDeck(),
            'actionProgress' => $this->readPlayerActionInProgress(),
            'acs' => $this->getAutomaCardsSeason()
            //,'_deck' => $_deck
        );

        if ($this->checkIfSoloMode()>0){
            $result['automaPlayerData'] = $this->getAutomaPlayerData();
        }

        return $result;
    }

    function chooseRandomWakeup($playerId){
        $playersData = $this->getPlayersData();
        $wakeupChart = array();
        for ($i=1; $i <=7; $i++) {
            if ($this->arrayFindByProperty($playersData, 'wakeup_chart', $i)==null){
                $wakeupChart[]=$i;
            }
        }
        shuffle($wakeupChart);
        $wakeupChart = reset($wakeupChart);

        $card = '';
        if ($wakeupChart==5){
            $card = 'yellow';
        }

        $this->chooseWakeup($playerId, $wakeupChart, $card, true, false);
    }

    function chooseRandomMamaPapa($playerId){
        $mamaCard = $this->readCardsByPlayerIdAndCardType($playerId, 'mama');
        shuffle($mamaCard);
        $mamaCard = reset($mamaCard);

        $papaCard = $this->readCardsByPlayerIdAndCardType($playerId, 'papa');
        shuffle($papaCard);
        $papaCard = reset($papaCard);

        $this->chooseMamaPapa($playerId, $mamaCard['type_arg'], $papaCard['type_arg'], false);
    }

    function chooseRandomPapaOption($playerId){
        $options = array('lira','bonus');
        shuffle($options);
        $options = reset($options);

        $this->choosePapaOption($playerId, $options, false);
    }

    function chooseRandomFallCard($playerId, $twoCards){
        $options = array('yellow','blue');
        shuffle($options);
        $firstDeck = reset($options);
        $secondDeck = null;
        if ($twoCards==1){
            $options = array('yellow','blue');
            shuffle($options);
            $secondDeck = reset($options);
        }

        $this->chooseFallCard($playerId, $firstDeck, $secondDeck, false);
    }

    
    function chooseRandomVisitorCard($playerId){
        $options = array('yellow','green');
        shuffle($options);
        $options = reset($options);

        $this->chooseVisitorCardDraw($playerId, $options, false);
    }

    function chooseRandomChooseOption($playerId){
        $playersFullData = $this->getPlayersFullData();
        $playerFullData = $playersFullData[$playerId];
        $playersPrivateHand = $this->readPlayersPrivateHand($playersFullData);
        $handCard = $playersPrivateHand[$playerId]['hand'];
        shuffle($handCard);


        //811: //Queen
        //The player on your right must choose 1: lose ${token_vp1}, give you 2 ${token_anyCard}, or pay you ${token_lira3}.
        //**special**
        $toGive = 2;
        if (count($handCard)<2){
            $toGive = count($handCard);
        }

        $cardsId = array();
        for ($i=0; $i < $toGive; $i++) {
            $cardsId[]=$handCard[$i]['i'];
        }

        $choices = array();
        if ($playerFullData['score']>=-4){
            $choices[]=1;
        }
        if (count($handCard)>=2){
            $choices[]=2;
        }
        if ($playerFullData['lira']>=3){
            $choices[]=3;
        }
        if (count($choices)==0){
            $choices[]=2;
        }
        shuffle($choices);

        $this->chooseOptions($playerId, $choices[0], $cardsId, false);
    }

    function chooseRandomDiscardCards($playerId){
        $playersFullData = $this->getPlayersFullData();
        $playersPrivateHand = $this->readPlayersPrivateHand($playersFullData);
        $handCard = $playersPrivateHand[$playerId]['hand'];
        shuffle($handCard);

        $toDiscard = count($handCard)-7;

        $discardCardId = array();
        for ($i=0; $i < $toDiscard; $i++) {
            $discardCardId[]=$handCard[$i]['i'];
        }

        $this->discardCards($playerId, $discardCardId, false);
    }

    function randomDiscardCard($playerId){
        $playersFullData = $this->getPlayersFullData();
        $playersPrivateHand = $this->readPlayersPrivateHand($playersFullData);
        $handCard = $playersPrivateHand[$playerId]['hand'];
        shuffle($handCard);

        $toDiscard = 1;
        $cardType = 'greenCard';

        foreach ($handCard as $handCardKey => $handCardValue) {
            if ($handCardValue['t'] == $cardType){
                $discardCardId[]=$handCardValue['i'];
                if (count($discardCardId)>=$toDiscard){
                    break;
                }
            }
        }

        $this->discardCard($playerId, $discardCardId, false);

    }

    function chooseRandomChooseCards($playerId){
        $playersPrivateHand = $this->readPlayersPrivateHand($playersData);
        $playersData = $this->getPlayersFullData();

        $sql = "SELECT card_id id, card_type type, card_type_arg type_arg FROM card WHERE card_location='chooseCards' AND card_location_arg = $playerId";
        $chooseCards = self::getObjectListFromDB( $sql);
        
        shuffle($chooseCards);

        $cardsSelectedId = array();
        for ($i=0; $i < 2; $i++) {
            $cardsSelectedId[]=$chooseCards[$i]['i'];
        }

        $this->chooseCards($playerId, $cardsSelectedId, false);
    }

    function chooseRandomGiveCards($playerId){
        $playersData = $this->getPlayersFullData();
        $playersPrivateHand = $this->readPlayersPrivateHand($playersData);
        $handCard = $playersPrivateHand[$playerId]['hand'];
        shuffle($handCard);

        $actionProgress = $this->readPlayerActionInProgress();

        $cardsSelectedId = array();

        //835	Governor
        //Choose up to 3 opponents to each give you 1 ${token_yellowCard}. Gain ${token_vp1} for each of them who cannot.
        if ($actionProgress['card_key'] == 835){
            foreach ($handCard as $handCardKey => $handCardValue) {
                if ($handCardValue['t']=='yellowCard'){
                    $cardsSelectedId[]=$handCardValue['i'];
                    break;
                }
            }
        }
        
        //623	Importer
        //Draw 3 ${token_blueCardPlus} cards unless all opponents combine to give you 3 visitor cards (total).
        if ($actionProgress['card_key'] == 623){
            $this->refuse($playerId, 'allGiveCard', true);
            return;
        }

        $this->allGiveCard($playerId, $cardsSelectedId, false);
    }

    /**
     * read players private hand
     * returns array by playerId
     */
    function readPlayersPrivateHand($playersFullData){
        $result = array();

        //read players hand cards
        foreach ($playersFullData as $playerId => $playerFullData) {
            $result[$playerId] = array('hand'=>$this->readPlayerHand($playerFullData));
        }
        return $result;
    }

    /**
     * read player hand
     * returns array of arrays k (card type arg), t(type)
     */
    function readPlayerHand($playerFullData){
        $result = array();

        $playerId = $playerFullData['id'];

        //read player hand cards
        $handLocation = HAND;
        $sql = "SELECT card_id id, card_type, card_type_arg FROM card WHERE card_location = '$handLocation' and card_location_arg = $playerId";
        $cards = self::getObjectListFromDB( $sql);
        foreach ($cards as $cardsKey => $cardsValue) {
            $result[]=array('i'=>$cardsValue['id'], 'k'=>$cardsValue['card_type_arg'], 't'=>$cardsValue['card_type']);
        }
        return $result;
    }

    /**
     * read player choose cards
     * returns array of arrays k (card type arg), t(type)
     */
    function readPlayerChooseCards($playerId){
        $result = array();

        //read player hand cards
        $location = 'chooseCards';
        $sql = "SELECT card_id id, card_type, card_type_arg FROM card WHERE card_location = '$location' and card_location_arg = $playerId";
        $cards = self::getObjectListFromDB( $sql);
        foreach ($cards as $cardsKey => $cardsValue) {
            $result[]=array('i'=>$cardsValue['id'], 'k'=>$cardsValue['card_type_arg'], 't'=>$cardsValue['card_type']);
        }
        return $result;
    }


    /**
     * read players token position
     * returns array by playerId
     */
    function readTokens(){
        $result = array();
        $playersData = $this->getPlayersDataWithSolo();
        $playersId = implode(',',array_keys($playersData));
        foreach ($playersData as $playerId => $playerData) {
            $result[$playerId] = array();
        }

        //read player tokens
        $handLocation = HAND;
        $sql = "SELECT card_id id, card_location_arg playerId, card_location, card_type, card_type_arg 
                FROM card
                WHERE card_location not in ('$handLocation','mama','papa','choiceMamas','papa','choicePapas','playerGrapes','playerWines','vine1','vine2','vine3','deckGreen','deckBlue','deckPurple','deckGreen','deckYellow','deckPurple','deckBlue','discardGreen','discardYellow','discardPurple','discardBlue','card_flags','chooseCards','offerCards')
                and card_location not like 'history%'
                and card_location_arg in ($playersId,0)
                and card_type not in ('color')
                order by card_location, card_location_arg, card_type, card_type_arg";
        $tokens = self::getObjectListFromDB( $sql);
        foreach ($tokens as $tokensKey => $tokensValue) {
            $result[$tokensValue['playerId']][]=array( "i"=>$tokensValue['id'], "t"=>$tokensValue['card_type'], "a"=>$tokensValue['card_type_arg'], "l"=>$tokensValue['card_location']);
        }
        return $result;
    }

    function startsWith($haystack, $needle) {
        // search backwards starting from haystack length characters from the end
        return $needle === "" || strrpos($haystack, $needle, - strlen($haystack)) !== false;
    }

    function endsWith($haystack, $needle) {
        $length = strlen($needle);
        return $length === 0 || (substr($haystack, - $length) === $needle);
    }

    // increment lira (can be negative too)
    function dbIncLira($playerId, $inc) {
        $lira =  $this->getUniqueValueFromDB("SELECT lira FROM player WHERE player_id='$playerId'");
        if ($lira+$inc<0){
            throw new BgaUserException( self::_("Not enough lira!") );
        }
        $this->DbQuery("UPDATE player SET lira=lira+$inc WHERE player_id='$playerId'");
    }

    // increment residual_payment
    function dbIncResidualPayment($playerId, $inc) {
        $residual_payment =  $this->getUniqueValueFromDB("SELECT residual_payment FROM player WHERE player_id='$playerId'");
        if ($residual_payment+$inc<0){
            throw new BgaUserException( self::_("Not enough residual payment!") );
        }
        if ($residual_payment+$inc>5){
            $this->DbQuery("UPDATE player SET residual_payment=5 WHERE player_id='$playerId'");
        } else {
            $this->DbQuery("UPDATE player SET residual_payment=residual_payment+$inc WHERE player_id='$playerId'");
        }
    }

    // get score
    function dbGetScore($playerId) {
        return $this->getUniqueValueFromDB("SELECT player_score FROM player WHERE player_id='$playerId'");
    }
    // set score
    function dbSetScore($playerId, $count) {
        $this->DbQuery("UPDATE player SET player_score='$count' WHERE player_id='$playerId'");
    }
    // set aux score (tie breaker)
    function dbSetAuxScore($playerId, $score) {
        $this->DbQuery("UPDATE player SET player_score_aux=$score WHERE player_id='$playerId'");
    }
    // increment score (can be negative too)
    function dbIncScore($playerId, $inc, $statistic) {
        $count = $this->dbGetScore($playerId);
        if ($inc != 0) {
            if ($count<=-5 && $inc<0){
                throw new BgaUserException( self::_("Minimum negative score!") );
            }
            $initialValue = $count;
            $count += $inc;
            if ($count < -5){
                $count = -5;
            }
            if ($statistic){
                self::incStat( $count-$initialValue, $statistic, $playerId );
            }

            $this->dbSetScore($playerId, $count);
            
            //When a player reaches 20 points, the current year will be played
            //through the year end, and the player with the most points wins.
            //only in non-solo mode
            if ($count >= END_GAME_SCORING && $this->checkIfSoloMode() == 0){
                $gameEnd = self::getGameStateValue('game_end');
                if ($gameEnd==0){
                    self::setGameStateValue('game_end', 1);
                }
            }
            
        }
        return $count;
    }

     /*
   * loadBug: in studio, type loadBug(20762) into the table chat to load a bug report from production
   * client side JavaScript will fetch each URL below in sequence, then refresh the page
   */
  public function loadBug($reportId)
  {
    $db = explode('_', self::getUniqueValueFromDB("SELECT SUBSTRING_INDEX(DATABASE(), '_', -2)"));
    $game = $db[0];
    $tableId = $db[1];
    self::notifyAllPlayers('loadBug', "Trying to load <a href='https://boardgamearena.com/bug?id=$reportId' target='_blank'>bug report $reportId</a>", [
      'urls' => [
        // Emulates "load bug report" in control panel
        "https://studio.boardgamearena.com/admin/studio/getSavedGameStateFromProduction.html?game=$game&report_id=$reportId&table_id=$tableId",
        
        // Emulates "load 1" at this table
        "https://studio.boardgamearena.com/table/table/loadSaveState.html?table=$tableId&state=1",
        
        // Calls the function below to update SQL
        "https://studio.boardgamearena.com/1/$game/$game/loadBugSQL.html?table=$tableId&report_id=$reportId",
        
        // Emulates "clear PHP cache" in control panel
        // Needed at the end because BGA is caching player info
        "https://studio.boardgamearena.com/admin/studio/clearGameserverPhpCache.html?game=$game",
      ]
    ]);
  }
  
  /*
   * loadBugSQL: in studio, this is one of the URLs triggered by loadBug() above
   */
  public function loadBugSQL($reportId)
  {
    $studioPlayer = self::getCurrentPlayerId();
    $players = self::getObjectListFromDb("SELECT player_id FROM player", true);
  
    // Change for your game
    // We are setting the current state to match the start of a player's turn if it's already game over
    $sql = [
      "UPDATE global SET global_value=2 WHERE global_id=1 AND global_value=99"
    ];
    foreach ($players as $pId) {
      // All games can keep this SQL
      $sql[] = "UPDATE player SET player_id=$studioPlayer WHERE player_id=$pId";
      $sql[] = "UPDATE global SET global_value=$studioPlayer WHERE global_value=$pId";
      $sql[] = "UPDATE stats SET stats_player_id=$studioPlayer WHERE stats_player_id=$pId";
  
      // Add game-specific SQL update the tables for your game
      $sql[] = "UPDATE card SET card_location_arg=$studioPlayer WHERE card_location_arg=$pId";
      $sql[] = "UPDATE player_action SET player_id=$studioPlayer WHERE player_id=$pId";
  
      // This could be improved, it assumes you had sequential studio accounts before loading
      // e.g., quietmint0, quietmint1, quietmint2, etc. are at the table
      $studioPlayer++;
    }
    $msg = "<b>Loaded <a href='https://boardgamearena.com/bug?id=$reportId' target='_blank'>bug report $reportId</a></b><hr><ul><li>" . implode(';</li><li>', $sql) . ';</li></ul>';
    self::warn($msg);
    self::notifyAllPlayers('message', $msg, []);
  
    foreach ($sql as $q) {
      self::DbQuery($q);
    }
    self::reloadPlayersBasicInfos();
  }

//////////////////////////////////////////////////////////////////////////////
//////////// Player actions
////////////

    /*
        Each time a player is doing some game action, one of the methods below is called.
        (note: each method below must match an input method in viticulture.action.php)
    */

    /*

    Example:

    function playCard( $card_id )
    {
        // Check that this is the player's turn and that it is a "possible action" at this game state (see states.inc.php)
        self::checkAction( 'playCard' );

        $player_id = self::getActivePlayerId();

        // Add your game logic to play a card there
        ...

        // Notify all players about the card played
        self::notifyAllPlayers( "cardPlayed", clienttranslate( '${player_name} plays ${card_name}' ), array(
            'player_id' => $player_id,
            'player_name' => self::getActivePlayerName(),
            'card_name' => $card_name,
            'card_id' => $card_id
        ) );

    }

    */

    function chooseMamaPapa($active_player_id, $mama, $papa, $changeGameStateAndCheckAction){
        // Check that this is the player's turn and that it is a "possible action" at this game state (see states.inc.php)
        if ($changeGameStateAndCheckAction){
            self::checkAction( 'chooseMamaPapa' );
        }

        if ($active_player_id!=0){
            $playerId = $active_player_id;
        } else {
            $playerId = $this->getCurrentPlayerId(); // CURRENT!!! not active
        }

        $mamaCard = $this->readCardsByPlayerIdAndCardTypeAndCardTypeArg($playerId, 'mama', $mama);
        if (count($mamaCard)==0){
            throw new BgaUserException( self::_("Mama not valid!") );
        }
        $mamaCard = reset($mamaCard);


        $papaCard = $this->readCardsByPlayerIdAndCardTypeAndCardTypeArg($playerId, 'papa', $papa);
        if (count($papaCard)==0){
            throw new BgaUserException( self::_("Papa not valid!") );
        }
        $papaCard = reset($papaCard);

        // Move cards from choice to player
        $this->cards->moveCard( $mamaCard['id'], 'mama', $playerId );
        $this->cards->moveCard( $papaCard['id'], 'papa', $playerId );

        $mamaInfo = $this->mamas[$mama];
        $papaInfo = $this->papas[$papa];

        // Notify all players
        self::notifyAllPlayers( "chooseMamaPapa", clienttranslate( '${player_name} chooses mama ${mamaName} and papa ${papaName}' ), array(
            'player_id' => $playerId,
            'player_name' => $this->getPlayerName($playerId),
            'mamaName' => $mamaInfo['name'],
            'papaName' => $papaInfo['name'],
            'i18n' => array( 'mamaName', 'papaName' )
        ) );

        if ($changeGameStateAndCheckAction){
            // Go to next game state
            $this->gamestate->setPlayerNonMultiactive($playerId, 'next'); // deactivate player; if none left, transition to 'next' state
        }

    }

    function pass($active_player_id, $changeGameStateAndCheckAction){
        // Check that this is the player's turn and that it is a "possible action" at this game state (see states.inc.php)
        if ($changeGameStateAndCheckAction){
            self::checkAction( 'pass' );
        }

        if ($active_player_id!=0){
            $playerId = $active_player_id;
        } else {
            $playerId = $this->getCurrentPlayerId(); // CURRENT!!! not active
        }
        $this->DbQuery("UPDATE player SET pass=1, card_played=0 WHERE player_id=$playerId");
        $this->DbQuery("DELETE FROM card WHERE card_location='card_flags'");

        $season = self::getGameStateValue('season');
        if ($season==WINTER){
            $availableWorkers = $this->readAvailableWorkers($playerId);
            foreach ($availableWorkers as $availableWorkersKey => $availableWorkersValue) {
                $worker_g=false;
                if ($availableWorkersValue['type']=='worker_g'){
                    $worker_g=true;
                }
                $this->placeWorker( $playerId, 801, $worker_g, '', 0, 0,
                0, array(), '', 0, array(), 0, 0, array(),
                array(), array(), array(), array(), '',
                0, 0, 0, false);
            }
        }

        // Notify all players pass action
        self::notifyAllPlayers( "pass", clienttranslate( '${player_name} passes' ), array(
            'player_id' => $playerId,
            'player_name' => $this->getPlayerName($playerId)
        ) );

        if ($changeGameStateAndCheckAction){
            // Go to next game state
            $this->gamestate->nextState( 'next' );
        }

    }

    function refuse($active_player_id, $refuseAction, $changeGameStateAndCheckAction){
        // Check that this is the player's turn and that it is a "possible action" at this game state (see states.inc.php)
        if ($changeGameStateAndCheckAction){
            self::checkAction( 'refuse' );
        }

        if ($active_player_id!=0){
            $playerId = $active_player_id;
        } else {
            $playerId = $this->getCurrentPlayerId(); // CURRENT!!! not active
        }

        $state = $this->gamestate->state();

        // Notify all players refuse action
        self::notifyAllPlayers( "refuse", clienttranslate( '${player_name} refuses action' ), array(
            'player_id' => $playerId,
            'player_name' => $this->getPlayerName($playerId)
        ) );
        
        //delete from next actions
        if ($refuseAction){

            $actionInfo = $this->readPlayerActionByAction($playerId, $refuseAction, STATUS_IN_PROGRESS);
            if ($actionInfo==null){
                $actionInfo = $this->readPlayerActionByAction(0, $refuseAction, STATUS_IN_PROGRESS);
            }

            //discard card
            if ($refuseAction == 'playCardSecondOption'){
                if ($actionInfo==null){
                    throw new BgaUserException( self::_("Wrong refuse action!") );
                }
            }

            //631 Swindler
            //Each opponent may give you ${token_lira2}. For each opponent who does not, gain ${token_vp1}.
            if ($actionInfo['card_key']==631){
                $this->dbIncScore($actionInfo['player_id'], 1, 'vit_scoring_yellow_card');
                // Notify all players
                self::notifyAllPlayers( "refuse", clienttranslate( '${player_name} gets ${token_get}' ), array(
                    'player_id' => $actionInfo['player_id'],
                    'player_name' => $this->getPlayerName($actionInfo['player_id']),
                    'token_get' => 'vp1'
                ) );
            }

            //remove action in progress
            if ($state['type'] !== "multipleactiveplayer") {
                $this->checkAndRemovePlayerActionInProgress($playerId, $refuseAction, true);
            }

            if ($actionInfo!=null && $actionInfo['card_key'] == null && 
                ($refuseAction == 'makeWine' || $refuseAction == 'playYellowCard' || $refuseAction == 'playBlueCard' 
                || $refuseAction == 'fillOrder' || $refuseAction == 'plant' 
                || $refuseAction == 'executeLocation' //planned location on future season
                )){
                $this->manageFriendlyBlocking($playerId,  $actionInfo['id'], $actionInfo['action'], $actionInfo['args']);
            }
        }

        if ($changeGameStateAndCheckAction){

            $nextState = 'next';
            /*switch ($refuseAction) {
                case 'plant':
                case 'makeWine':
            }*/

            if ($state['type'] === "multipleactiveplayer") {
                // Go to next game state
                $this->gamestate->setPlayerNonMultiactive($playerId, $nextState); // deactivate player; if none left, transition to 'next' state
            } else {
                // Go to next game state
                $this->gamestate->nextState( $nextState );
            }
        }

    }

    function cancelAction($active_player_id, $cancelAction, $changeGameStateAndCheckAction){
        // Check that this is the player's turn and that it is a "possible action" at this game state (see states.inc.php)
        if ($changeGameStateAndCheckAction){
            self::checkAction( 'cancelAction' );
        }

        if ($active_player_id!=0){
            $playerId = $active_player_id;
        } else {
            $playerId = $this->getCurrentPlayerId(); // CURRENT!!! not active
        }

        $state = $this->gamestate->state();

        $nextState = 'same';

        //delete from next actions
        if ($cancelAction){

            $actionInfo = $this->readPlayerActionByAction($playerId, $cancelAction, STATUS_IN_PROGRESS);

            //832	Manager	Take any action (no bonus) from a previous season without placing a worker.
            if ($actionInfo['action']=='takeActionPrev'){
                //first action of playBlueCard_1 or playBlueCard_1+playBlueCard_1
                //return worker and remove card from history, remove if present other playBlueCard
                if ($actionInfo['args']>0){

                    $boardLocation = 'board_'.$actionInfo['args'];
                    $this->DbQuery("UPDATE card SET card_location='player' where card_type like 'worker_%' and card_location='$boardLocation' and card_location_arg=$playerId");
                    
                    //remove last card
                    $sql = "SELECT card_id, card_type_arg, card_location FROM card WHERE card_location like 'history%' and card_location_arg = '${playerId}' order by card_id desc ";
                    $rows = self::getObjectListFromDB( $sql);
                    if (count($rows)>0){
                        if ($rows[0]['card_type_arg']==832){
                            $cardIdToDelete = $rows[0]['card_id'];
                            $this->DbQuery("DELETE FROM card WHERE card_id=${cardIdToDelete}");
                            //'history_'.$moveNumber.'_'.$turn.'_'.$season
                            $cardLocationParts = explode('_',$rows[0]['card_location']);
                            $moveNumber=0;
                            $turn=0;
                            $season=0;
                            if (count($cardLocationParts)>1){
                                $turn = $cardLocationParts[1];
                            }
                            if (count($cardLocationParts)>2){
                                $season = $cardLocationParts[2];
                            }
                            if (count($cardLocationParts)>3){
                                $moveNumber = $cardLocationParts[3];
                            }

                            self::notifyAllPlayers( "removeLastCardPlayedToHistory", '', array(
                                'card' => $playerId.'_'.$rows[0]['card_type_arg'].'_'.$turn.'_'.$season.'_'.$moveNumber
                            ) );
                        }
                    }

                    //remove other playBlueCard actions
                    $sql = "DELETE FROM player_action WHERE player_id = '${playerId}' and action='playBlueCard'";
                    $this->DbQuery( $sql);
                    
                } else {
                    
                    //remove last card
                    $sql = "SELECT card_id, card_type_arg, card_location FROM card WHERE card_location like 'history%' and card_location_arg = '${playerId}' order by card_id desc ";
                    $rows = self::getObjectListFromDB( $sql);
                    if (count($rows)>0){
                        if ($rows[0]['card_type_arg']==832){
                            $cardIdToDelete = $rows[0]['card_id'];
                            $this->DbQuery("DELETE FROM card WHERE card_id=${cardIdToDelete}");
                            
                            //'history_'.$moveNumber.'_'.$turn.'_'.$season
                            $cardLocationParts = explode('_',$rows[0]['card_location']);
                            $moveNumber=0;
                            $turn=0;
                            $season=0;
                            if (count($cardLocationParts)>1){
                                $turn = $cardLocationParts[1];
                            }
                            if (count($cardLocationParts)>2){
                                $season = $cardLocationParts[2];
                            }
                            if (count($cardLocationParts)>3){
                                $moveNumber = $cardLocationParts[3];
                            }

                            self::notifyAllPlayers( "removeLastCardPlayedToHistory", '', array(
                                'card' => $playerId.'_'.$rows[0]['card_type_arg'].'_'.$turn.'_'.$season.'_'.$moveNumber
                            ) );
                        }
                    }
                    
                    //return player action
                    $this->insertPlayerAction($playerId, 'playBlueCard', 0, '', null, null, STATUS_IN_PROGRESS);

                    $nextState  = 'playBlueCard';
                }
            }

            //remove action in progress
            if ($state['type'] !== "multipleactiveplayer") {
                $this->checkAndRemovePlayerActionInProgress($playerId, 'takeActionPrev', false);
            }
        }

        // Notify all players cancel action
        self::notifyAllPlayers( "cancelAction", clienttranslate( '${player_name} cancels last action' ), array(
            'player_id' => $playerId,
            'player_name' => $this->getPlayerName($playerId)
        ) );

        if ($changeGameStateAndCheckAction){

            /*switch ($refuseAction) {
                case 'plant':
                case 'makeWine':
            }*/

            if ($state['type'] === "multipleactiveplayer") {
                // Go to next game state
                $this->gamestate->setPlayerNonMultiactive($playerId, $nextState); // deactivate player; if none left, transition to 'next' state
            } else {
                // Go to next game state
                $this->gamestate->nextState( $nextState );
            }
        }

    }

    function choosePapaOption($active_player_id, $option, $changeGameStateAndCheckAction){
        // Check that this is the player's turn and that it is a "possible action" at this game state (see states.inc.php)
        if ($changeGameStateAndCheckAction){
            self::checkAction( 'choosePapaOption' );
        }

        if ($active_player_id!=0){
            $playerId = $active_player_id;
        } else {
            $playerId = $this->getCurrentPlayerId(); // CURRENT!!! not active
        }

        $playerData =$this->getPlayerFullData($playerId);

        $papaCard = $this->readCardsByPlayerIdAndCardTypeAndCardTypeArg($playerId, 'papa', $playerData['papa']);
        $papaInfo = $this->papas[$playerData['papa']];

        $lira = $papaInfo['lira'];
        if ($option=='lira'){
            $statOption = 2;
            $lira+=$papaInfo['choice_lira'];
            $notificationText = clienttranslate( '${player_name} chooses papa ${papaName} additional lira: ${lira}+${choice_lira}=${total_lira} ${token_lira}' );
            // Notify all players papa option
            self::notifyAllPlayers( "choosePapaOption", $notificationText, array(
                'player_id' => $playerId,
                'player_name' => $this->getPlayerName($playerId),
                'papaName' => $papaInfo['name'],
                'token_lira' => 'lira',
                'lira' => $papaInfo['lira'],
                'choice_lira' => $papaInfo['choice_lira'],
                'total_lira' => $lira,
                'i18n' => array('papaName' )
            ) );
        } else if ($option=='bonus'){
            $statOption = 1;
            $this->applyPapaEffect($playerId, $papaInfo['name'], $papaInfo['choice_bonus']);
        } else {
            throw new BgaUserException( self::_("Option not valid!") );
        }
        $this->dbIncLira($playerId, $lira);

        self::setStat($playerData['papa'], 'vit_papa', $playerId);
        self::setStat($statOption, 'vit_papa_option', $playerId);

        if ($changeGameStateAndCheckAction){
            // Go to next game state
            $this->gamestate->nextState( 'next' );
        }

    }

    function chooseWakeup($active_player_id, $value, $card, $startYearChoose, $changeGameStateAndCheckAction){
        // Check that this is the player's turn and that it is a "possible action" at this game state (see states.inc.php)
        if ($changeGameStateAndCheckAction){
            self::checkAction( 'chooseWakeup' );
        }

        if ($active_player_id!=0){
            $playerId = $active_player_id;
        } else {
            $playerId = $this->getCurrentPlayerId(); // CURRENT!!! not active
        }

        $playersData =$this->getPlayersData();
        if ($value <1 || $value >7){
            throw new BgaUserException( self::_("Wakeup not valid!") );
        }
        foreach ($playersData as $playersDataKey => $playersDataValue) {
            if ($playersDataKey != $playerId && $playersDataValue['wakeup_chart'] == $value){
                throw new BgaUserException( self::_("Wakeup not available!") );
            }
        }

        $wakeupBonusTokenId=null;
        if ($startYearChoose){
            //in solo mode, only wakeup with bonus marker
            if ($this->checkIfSoloMode()>0){
                $bonuses = $this->readCardsByPlayerIdAndCardType(0,'wakeup_bonus');
                $foundBonus = false;
                foreach ($bonuses as $bonusesKey => $bonusesValue) {
                    if ($bonusesValue['type_arg']==$value){
                        $foundBonus=true;
                        $wakeupBonusTokenId=$bonusesValue['id'];
                        break;
                    }
                }
                if (!$foundBonus){
                    throw new BgaUserException( self::_("Wakeup not available!") );
                }
            }
        }

        if ($value==5 && $card != 'yellow' && $card != 'blue'){
            throw new BgaUserException( self::_("Deck not valid!") );
        }

        //setting wakeup
        $this->DbQuery("UPDATE player SET pass=0, wakeup_chart=$value, wakeup_order=0, card_played=0 WHERE player_id = ${playerId}");
        $this->DbQuery("DELETE FROM card WHERE card_location='card_flags'");

        //in solo mode player gets one bonus
        if ($startYearChoose){
            if ($this->checkIfSoloMode()>0){
                $this->DbQuery("UPDATE player SET bonuses=bonuses+1 WHERE player_id = ${playerId}");
                //moving wakeup bonus to player
                $this->cards->moveCard( $wakeupBonusTokenId, 'player', $playerId );
            }
        }

        $rooster = $this->readCardsByPlayerIdAndCardType($playerId, 'rooster');
        $this->cards->moveCard( $rooster[0]['id'], 'board_'.$value, $playerId );

        $notificationText = clienttranslate( '${player_name} chooses wakeup order ${value}' );
        switch ($value) {
            case 1:
                //nothing
                $notificationText = clienttranslate( '${player_name} chooses wakeup order ${value}' );
                break;

            case 2:
                //2 green card
                if ($this->drawFromDeck($playerId, DECK_GREEN, 1, false)>0){
                    $notificationText = clienttranslate( '${player_name} chooses wakeup order 2 and gets a vine card ${token_greenCard}' );
                }
                break;

            case 3:
                //3 purple card
                if ($this->drawFromDeck($playerId, DECK_PURPLE, 1, false)>0){
                    $notificationText = clienttranslate( '${player_name} chooses wakeup order 3 and gets a wine order card ${token_purpleCard}' );
                }
                break;

            case 4:
                //4 lira
                $this->dbIncLira($playerId, 1);
                $notificationText = clienttranslate( '${player_name} chooses wakeup order 4 and gets ${token_lira1}' );
                break;

            case 5:
                //5 yellow card or blue card
                if ($card == 'yellow'){
                    if ($this->drawFromDeck($playerId, DECK_YELLOW, 1, false)>0){
                        $notificationText = clienttranslate( '${player_name} chooses wakeup order 5 and gets a summer visitor card ${token_yellowCard}' );
                    }
                }
                if ($card == 'blue'){
                    if ($this->drawFromDeck($playerId, DECK_BLUE, 1, false)>0){
                        $notificationText = clienttranslate( '${player_name} chooses wakeup order 5 and gets a winter visitor card ${token_blueCard}' );
                    }
                }
                break;

            case 6:
                //6 vp
                $this->dbIncScore($playerId, 1 , 'vit_scoring_wakeup');
                $notificationText = clienttranslate( '${player_name} chooses wakeup order 6 and gets ${token_vp1}' );
                break;

            case 7:
                //7 temporary worker
                $this->getTemporaryWorker($playerId);
                $notificationText = clienttranslate( '${player_name} chooses wakeup order 7 and gets the temporary worker ${token_worker_t}' );
                break;
        }

        self::incStat(1, "vit_wakeup_chart_".$value, $playerId );


        // Notify all players
        self::notifyAllPlayers( "chooseWakeup", $notificationText, array(
            'player_id' => $playerId,
            'player_name' => $this->getPlayerName($playerId),
            'value' => $value, 
            'token_greenCard' => 'greenCard',
            'token_yellowCard' => 'yellowCard',
            'token_purpleCard' => 'purpleCard',
            'token_blueCard' => 'blueCard',
            'token_lira1' => 'lira1',
            'token_vp1' => 'vp1',
            'token_worker_t' => 'worker_t'
        ) );

        if ($changeGameStateAndCheckAction){
            // Go to next game state
            $this->gamestate->nextState( 'next' );
        }

    }

    function placeWorker($active_player_id, $locationKey, $workerGrande, $structure, $cardId, $cardKey,
                         $field, $harvestFieldsId, $wine, $wineValue, $grapesId,
                         $buyField, $sellField, $sellGrapesId,
                         $uprootVinesId, $orderWinesId, $cardsSelectedId, $workersSelectedId, $otherSelection,
                         $visitorCardId, $visitorCardKey, $visitorCardOption, $changeGameStateAndCheckAction){

        // Check that this is the player's turn and that it is a "possible action" at this game state (see states.inc.php)
        if ($changeGameStateAndCheckAction){
            self::checkAction( 'placeWorker' );
        }

        if ($active_player_id!=0){
            $playerId = $active_player_id;
        } else {
            $playerId = $this->getCurrentPlayerId(); // CURRENT!!! not active
        }

        $playersData = $this->getPlayersFullData();
        $tokens = $this->readTokens();
        $playersPrivateHand = $this->readPlayersPrivateHand($playersData);

        $activeLocations = $this->readActiveLocations($playerId, $playersData, $tokens, $playersPrivateHand);
        if ($this->countArrayByProperty($activeLocations, 't', $locationKey)==0){
            throw new BgaUserException( self::_("Placement not valid!") );
        }

        //location occupied?
        //if occupied change location to shared location
        $occupied=false;
        $total=0;
        $location= $this->boardLocations[$locationKey];
        foreach ($tokens as $tokensPl => $tokensPlValues) {
            foreach ($tokensPlValues as $tokensKey => $tokensValue) {
                if ($location['key']==901){
                    //yoke
                    if ($tokensValue['l']=='board_'.$location['key'] && $tokensPl==$playerId){
                        $total++;
                    }
                } else if ($tokensValue['l']=='board_'.$location['key']){
                    $total++;
                }
            }
        }
        //check max occupations
        if ($total>=$location['max']){
            $occupied = true;
        }
        //if occupied change location to shared location
        if ($occupied){
            $locationKey = $this->boardLocations[$locationKey]['sha'];
        }

        $needsGrandeWorker = false;
        foreach ($activeLocations as $activeLocationsKey => $activeLocationsValue) {
            if ($activeLocationsValue['t']==$locationKey && $activeLocationsValue['a']==2){
                $needsGrandeWorker = true;
                break;
            }
        }

        if ($needsGrandeWorker && $workerGrande==false){
            throw new BgaUserException( self::_("Grande worker needed!") );
        }

        $availableWorkers = $this->readAvailableWorkers($playerId);

        $workerId = 0;
        if ($workerGrande==true){
            foreach ($availableWorkers as $availableWorkersKey => $availableWorkersValue) {
                if ( $availableWorkersValue['type']=='worker_g'){
                    $workerId = $availableWorkersValue['id'];
                    break;
                } 
            }  
        }
        if ($workerId == 0){
            foreach ($availableWorkers as $availableWorkersKey => $availableWorkersValue) {
                if ($needsGrandeWorker && $availableWorkersValue['type']=='worker_g'){
                    $workerId = $availableWorkersValue['id'];
                    break;
                } else if ($needsGrandeWorker==false && $availableWorkersValue['type']!='worker_g'){
                    $workerId = $availableWorkersValue['id'];
                    break;
                }
            }
        }
        if ($workerId == 0){
            throw new BgaUserException( self::_("No workers available!") );
        }

        //resetting card_played
        $this->DbQuery("UPDATE player SET card_played=0 WHERE player_id = ${playerId}");
        $this->DbQuery("DELETE FROM card WHERE card_location='card_flags'");

        //place worker
        $this->cards->moveCard( $workerId, 'board_'.$locationKey, $playerId );
        
        //solo mode, decrement/use wakeup bonus
        if (array_key_exists($locationKey, $this->boardLocations)){
            $boardLocation = $this->boardLocations[$locationKey];
            if ($this->checkIfSoloMode() && $boardLocation['bonus']){
                $this->useWakeupBonus($playerId);
            }
        }

        $this->executeLocationActionInternal($playerId, $locationKey, $structure, $cardId, $cardKey,
                                     $field, $harvestFieldsId, $wine, $wineValue, $grapesId,
                                     $buyField, $sellField, $sellGrapesId,
                                     $uprootVinesId, $orderWinesId, $cardsSelectedId, $workersSelectedId, $otherSelection,
                                     $visitorCardId, $visitorCardKey, $visitorCardOption,
                                     $playersData, $tokens, $playersPrivateHand, true);

        if ($changeGameStateAndCheckAction){
            // Go to next game state
            $this->gamestate->nextState( 'next' );
        }

    }

    /**
     * decrement/use wakeup bonus
     */
    function useWakeupBonus($playerId){

        $playerData = $this->getPlayerData($playerId);
        if ($playerData['bonuses']<=0){
            throw new BgaUserException( self::_("Player has no wakeup bonus!") );
        }

        $bonuses = $this->readCardsByPlayerIdAndLocationAndCardType($playerId,'player','wakeup_bonus');

        $this->DbQuery("UPDATE player SET bonuses=bonuses-1 WHERE player_id = ${playerId}");

        //moving wakeup bonus to discard player
        $this->cards->moveCard( $bonuses[0]['id'], 'token_discard', $playerId );
    }

    /**
     * occupy locations drawing automa cards
     */
    function automaOccupyLocations($playersData){
        $soloParameters = $this->soloParameters[$this->getSoloDifficulty()];
        $season = self::getGameStateValue('season');
        $mustDrawCard = true;
        $availableWorker = $this->readAvailableWorkers(SOLO_PLAYER_ID);
        $locationsOccupied = 0;
        $set = self::getGameStateValue('set');
    
        while (count($availableWorker) > 0 && $mustDrawCard){
            $tokens = $this->readTokens();
            $cards = $this->drawFromDeck(SOLO_PLAYER_ID, DECK_AUTOMA, 1, true);

            $card = reset($cards);
            $automaCard = $this->automaCards[$card['type_arg']];

            //loop over 4 bars of automa card
            for ($i=1; $i < 5; $i++) { 
                //check set (tuscany) and season 
                if ($this->isComponentPlayableBySet( $automaCard['set'.$i]) &&
                    $this->checkIfAutomaSeason($set, $season, $automaCard['sea'.$i])){
                    
                    $action = $automaCard['act'.$i];
                    $text = $automaCard['des'.$i];
                    $actionTokens = explode(',',$action);

                    //read all locations withouth bonus locations
                    $locations = $this->readLocationsBySetAndPlayers(false);
                    foreach ($locations as $locationsKey => $locationsValue) {

                        $locationKey = $locationsValue['key'];

                        //check location season
                        if ($this->checkIfAutomaSeason($set, $locationsValue['season'], $automaCard['sea'.$i])){
                            $found=false;

                            //check location action
                            foreach ($actionTokens as $actionTokensKey => $actionTokensValue) {
                                if ($locationsValue['action'] == $actionTokensValue){
                                    $found = true;
                                }
                            }

                            //check if occupied
                            $occupied=false;
                            if ($found){
                                foreach ($playersData as $playersDataKey => $playersDataValue) {
                                    foreach ($tokens[$playersDataKey] as $tokensKey => $tokensValue) {
                                        if ($this->startsWith($tokensValue['t'], 'worker_') && $this->startsWith($tokensValue['l'], 'board_')) {
                                            $workerLocationParts = explode('_',$tokensValue['l']);
                                            $workerLocationKey = intval($workerLocationParts[1]);
                                            if ($locationKey==$workerLocationKey){
                                                $occupied=true;
                                            }
                                        }
                                    }
                                }
                            }

                            if ($found && $occupied==false){
                                $worker = array_shift($availableWorker);
                                //place worker in future slot
                                $this->cards->moveCard( $worker['id'], 'board_'.$locationKey, SOLO_PLAYER_ID );
                                
                                self::notifyAllPlayers( "automaOccupyLocations", clienttranslate( '${player_name} occupies: ${action}' ), array(
                                    'player_id' => SOLO_PLAYER_ID,
                                    'player_name' => 'Automa',
                                    'action' => $text,
                                    'i18n' => Array('action')
                                ) );

                                $locationsOccupied++;
                            }
                        }
                    }

                }
            }

            //move card to discard
            $this->discardCardOnDeckTop($card['id'], $card['type_arg']);
            $this->addCardPlayedToHistory(SOLO_PLAYER_ID, $card['id'], $card['type_arg']);

            if ($soloParameters['occupyAtLeastTwoLocations']==0){
                $mustDrawCard = false;
            } else if ($locationsOccupied>=2){
                $mustDrawCard = false;
            }

            //read available workers
            $availableWorker = $this->readAvailableWorkers(SOLO_PLAYER_ID);
        }
        
    }

    /**
     * check season of automa 'bar'
     * in normal board (not tuscany)
     * in summer automa uses Spring and Summer actions
     * in winter automa uses Fall and Winter actions
     */
    function checkIfAutomaSeason($set, $season, $seasonAutomaBar){
        if ($season==$seasonAutomaBar){
            return true;
        }
        if ($set==SET_BASE){
            if ($season==SUMMER && $seasonAutomaBar==SPRING){
                return true;
            }
            if ($season==WINTER && $seasonAutomaBar==FALL){
                return true;
            }
        }
        return false;
    }
    
    function executeLocation($active_player_id, $locationKey, $workerGrande, $structure, $cardId, $cardKey,
                         $field, $harvestFieldsId, $wine, $wineValue, $grapesId,
                         $buyField, $sellField, $sellGrapesId,
                         $uprootVinesId, $orderWinesId, $cardsSelectedId, $workersSelectedId, $otherSelection,
                         $visitorCardId, $visitorCardKey, $visitorCardOption, $changeGameStateAndCheckAction){

        // Check that this is the player's turn and that it is a "possible action" at this game state (see states.inc.php)
        if ($changeGameStateAndCheckAction){
            self::checkAction( 'executeLocation' );
        }

        if ($active_player_id!=0){
            $playerId = $active_player_id;
        } else {
            $playerId = $this->getCurrentPlayerId(); // CURRENT!!! not active
        }

        $playersData = $this->getPlayersFullData();
        $tokens = $this->readTokens();
        $playersPrivateHand = $this->readPlayersPrivateHand($playersData);

        $actionProgress = $this->checkPlayerActionInProgress($playerId, 'executeLocation');

        //resetting card_played
        $this->DbQuery("UPDATE player SET card_played=0 WHERE player_id = ${playerId}");
        $this->DbQuery("DELETE FROM card WHERE card_location='card_flags'");

        $this->executeLocationActionInternal($playerId, $locationKey, $structure, $cardId, $cardKey,
                                     $field, $harvestFieldsId, $wine, $wineValue, $grapesId,
                                     $buyField, $sellField, $sellGrapesId,
                                     $uprootVinesId, $orderWinesId, $cardsSelectedId, $workersSelectedId, $otherSelection,
                                     $visitorCardId, $visitorCardKey, $visitorCardOption,
                                     $playersData, $tokens, $playersPrivateHand, true);

        $this->removePlayerAction($actionProgress['id']);

        if ($changeGameStateAndCheckAction){
            // Go to next game state
            $this->gamestate->nextState( 'next' );
        }

    }

    function takeActionPrev($active_player_id, $locationKey, $structure, $cardId, $cardKey,
                         $field, $harvestFieldsId, $wine, $wineValue, $grapesId,
                         $buyField, $sellField, $sellGrapesId,
                         $uprootVinesId, $orderWinesId, $cardsSelectedId, $workersSelectedId, $otherSelection,
                         $visitorCardId, $visitorCardKey, $visitorCardOption, $changeGameStateAndCheckAction){

        // Check that this is the player's turn and that it is a "possible action" at this game state (see states.inc.php)
        if ($changeGameStateAndCheckAction){
            self::checkAction( 'takeActionPrev' );
        }

        if ($active_player_id!=0){
            $playerId = $active_player_id;
        } else {
            $playerId = $this->getCurrentPlayerId(); // CURRENT!!! not active
        }

        $playersData = $this->getPlayersFullData();
        $tokens = $this->readTokens();
        $playersPrivateHand = $this->readPlayersPrivateHand($playersData);
        $season = self::getGameStateValue('season');

        $actionProgress = $this->checkPlayerActionInProgress($playerId, 'takeActionPrev');

        $location = $this->boardLocations[$locationKey];
        if ($location['season']>=$season){
            throw new BgaUserException( self::_("Season location not valid!") );
        }

        $activeLocations = $this->readActiveLocations($playerId, $playersData, $tokens, $playersPrivateHand, $location['season'], false, false, false);
        if ($this->countArrayByProperty($activeLocations, 't', $locationKey)==0){
            throw new BgaUserException( self::_("Placement not valid!") );
        }

        //resetting card_played
        $this->DbQuery("UPDATE player SET card_played=0 WHERE player_id = ${playerId}");
        $this->DbQuery("DELETE FROM card WHERE card_location='card_flags'");

        $this->executeLocationActionInternal($playerId, $locationKey, $structure, $cardId, $cardKey,
                                     $field, $harvestFieldsId, $wine, $wineValue, $grapesId,
                                     $buyField, $sellField, $sellGrapesId,
                                     $uprootVinesId, $orderWinesId, $cardsSelectedId, $workersSelectedId, $otherSelection,
                                     $visitorCardId, $visitorCardKey, $visitorCardOption,
                                     $playersData, $tokens, $playersPrivateHand, true);

        $this->removePlayerAction($actionProgress['id']);

        if ($changeGameStateAndCheckAction){
            // Go to next game state
            $this->gamestate->nextState( 'next' );
        }

    }

    function discardCards($active_player_id, $cardsId, $changeGameStateAndCheckAction){
        // Check that this is the player's turn and that it is a "possible action" at this game state (see states.inc.php)
        if ($changeGameStateAndCheckAction){
            self::checkAction( 'discardCards' );
        }

        if ($active_player_id!=0){
            $playerId = $active_player_id;
        } else {
            $playerId = $this->getCurrentPlayerId(); // CURRENT!!! not active
        }
        
        $playersFullData = $this->getPlayersFullData();
        $playersPrivateHand = $this->readPlayersPrivateHand($playersFullData);
        $handCard = $playersPrivateHand[$playerId]['hand'];

        $toDiscard = count($handCard)-7;
        $this->discardCardsInternal($playerId, $cardsId, $toDiscard);

        $this->notifyPlayer($playerId, 'discardCardsUpdateHand','',array(
            'hand'=>$this->readPlayerHand($playersFullData[$playerId]),
            'origin'=>'discard',
            'target'=>'discard'
        ));

        if ($changeGameStateAndCheckAction){
            $this->gamestate->setPlayerNonMultiactive($playerId, 'next'); // deactivate player; if none left, transition to 'next' state
        }
    }
    
    function discardVines($active_player_id, $cardsId, $changeGameStateAndCheckAction){
        // Check that this is the player's turn and that it is a "possible action" at this game state (see states.inc.php)
        if ($changeGameStateAndCheckAction){
            self::checkAction( 'discardVines' );
        }

        if ($active_player_id!=0){
            $playerId = $active_player_id;
        } else {
            $playerId = $this->getCurrentPlayerId(); // CURRENT!!! not active
        }
        
        $actionProgress = $this->readPlayerActionInProgress();
        $playersFullData = $this->getPlayersFullData();
        $playersPrivateHand = $this->readPlayersPrivateHand($playersFullData);
        $handCard = $playersPrivateHand[$playerId]['hand'];

        $toDiscard = 0;
        if ($actionProgress!=null){
            //624: //Sharecropper
            //Plant 1 ${token_greenCard} even if you don't have the required structure(s) OR uproot and discard 1 ${token_greenCard} to gain ${token_vp2}.
            //plant_1_noStructure|uprootAndDiscard_1+getVp_2
            //609: //Planter
            //Plant up to 2 ${token_greenCard} and gain ${token_lira1} OR uproot and discard 1 ${token_greenCard} to gain ${token_vp2}.
            //plant_2+getLira_1|uprootAndDiscard_1+getVp_2
            if ( $actionProgress['card_key']==624 || $actionProgress['card_key']==609){
                $toDiscard = 1;
            }
            //619: //Horticulturist
            //Plant 1 ${token_greenCard} even if you don't have the required structure(s) OR uproot and discard 2 ${token_greenCard} to gain ${token_vp3}.
            //**special**
            if ( $actionProgress['card_key']==619){
                $toDiscard = 2;
            }
        }

        $this->discardCardsInternal($playerId, $cardsId, $toDiscard);

        if ($actionProgress!=null){
            //624: //Sharecropper
            //Plant 1 ${token_greenCard} even if you don't have the required structure(s) OR uproot and discard 1 ${token_greenCard} to gain ${token_vp2}.
            //plant_1_noStructure|uprootAndDiscard_1+getVp_2
            //609: //Planter
            //Plant up to 2 ${token_greenCard} and gain ${token_lira1} OR uproot and discard 1 ${token_greenCard} to gain ${token_vp2}.
            //plant_2+getLira_1|uprootAndDiscard_1+getVp_2
            if ( $actionProgress['card_key']==624 || $actionProgress['card_key']==609){
                $this->dbIncScore($actionProgress['player_id'], 2,'vit_scoring_yellow_card');
                // Notify all players
                self::notifyAllPlayers( "allBuild", clienttranslate( '${player_name} gets ${token_get}' ), array(
                    'player_id' => $actionProgress['player_id'],
                    'player_name' => $this->getPlayerName($actionProgress['player_id']),
                    'token_get' => 'vp2'
                ) );
            }

            //619: //Horticulturist
            //Plant 1 ${token_greenCard} even if you don't have the required structure(s) OR uproot and discard 2 ${token_greenCard} to gain ${token_vp3}.
            //**special**
            if ( $actionProgress['card_key']==619){
                $this->dbIncScore($actionProgress['player_id'], 3,'vit_scoring_yellow_card');
                // Notify all players
                self::notifyAllPlayers( "allBuild", clienttranslate( '${player_name} gets ${token_get}' ), array(
                    'player_id' => $actionProgress['player_id'],
                    'player_name' => $this->getPlayerName($actionProgress['player_id']),
                    'token_get' => 'vp3'
                ) );
            }

            $this->removePlayerAction($actionProgress['id']);

        }

        $this->notifyPlayer($playerId, 'discardCardsUpdateHand','',array(
            'hand'=>$this->readPlayerHand($playersFullData[$playerId]),
            'origin'=>'discard',
            'target'=>'discard'
        ));

        if ($changeGameStateAndCheckAction){
            $nextState = 'next';

            // Go to next game state
            $this->gamestate->nextState( $nextState );
        }
    }

    function plant($active_player_id, $cardId, $cardKey, $field, $changeGameStateAndCheckAction){
        // Check that this is the player's turn and that it is a "possible action" at this game state (see states.inc.php)
        if ($changeGameStateAndCheckAction){
            self::checkAction( 'plant' );
        }

        if ($active_player_id!=0){
            $playerId = $active_player_id;
        } else {
            $playerId = $this->getCurrentPlayerId(); // CURRENT!!! not active
        }

        //action in progress
        $actionProgress = $this->checkPlayerActionInProgress($playerId, 'plant');

        $playersData = $this->getPlayersFullData();
        $tokens = $this->readTokens();
        $playersPrivateHand = $this->readPlayersPrivateHand($playersData);

        //plant method sends notification
        $this->plantInternal($playerId, $cardId, $cardKey, $field, $playersData, $tokens, $playersPrivateHand, true, true);

        $this->removePlayerAction($actionProgress['id']);

        if ($changeGameStateAndCheckAction){
            $nextState = 'next';

            // Go to next game state
            $this->gamestate->nextState( $nextState );
        }

    }

    function allPlant($active_player_id, $cardId, $cardKey, $field, $changeGameStateAndCheckAction){
        // Check that this is the player's turn and that it is a "possible action" at this game state (see states.inc.php)
        if ($changeGameStateAndCheckAction){
            self::checkAction( 'allPlant' );
        }

        if ($active_player_id!=0){
            $playerId = $active_player_id;
        } else {
            $playerId = $this->getCurrentPlayerId(); // CURRENT!!! not active
        }

        //action in progress
        $actionProgress = $this->checkPlayerActionInProgress(0, 'allPlant');

        $playersData = $this->getPlayersFullData();
        $tokens = $this->readTokens();
        $playersPrivateHand = $this->readPlayersPrivateHand($playersData);

        //plant method sends notification
        $this->plantInternal($playerId, $cardId, $cardKey, $field, $playersData, $tokens, $playersPrivateHand, true, true);

        //637 Volunteer Crew
        //All players may plant 1 ${token_greenCard}. Gain ${token_lira2} for each opponent who does this.
        //2lira bonus
        if ($actionProgress != null && $actionProgress['card_key'] == 637){
            $this->dbIncLira($actionProgress['player_id'], 2);
            // Notify all players
            self::notifyAllPlayers( "allPlant", clienttranslate( '${player_name} gets ${token_get}' ), array(
                'player_id' => $actionProgress['player_id'],
                'player_name' => $this->getPlayerName($actionProgress['player_id']),
                'token_get' => 'lira2'
            ) );
        }

        if ($changeGameStateAndCheckAction){
            $nextState = 'next';

            // Go to next game state
            $this->gamestate->setPlayerNonMultiactive($playerId, $nextState); // deactivate player; if none left, transition to 'next' state
        }

    }

    function allGiveCard($active_player_id, $cardsSelectedId, $changeGameStateAndCheckAction){
        // Check that this is the player's turn and that it is a "possible action" at this game state (see states.inc.php)
        if ($changeGameStateAndCheckAction){
            self::checkAction( 'allGiveCard' );
        }

        if ($active_player_id!=0){
            $playerId = $active_player_id;
        } else {
            $playerId = $this->getCurrentPlayerId(); // CURRENT!!! not active
        }

        //action in progress
        $actionProgress = $this->checkPlayerActionInProgress(0, 'allGiveCard');

        $playersData = $this->getPlayersFullData();
        $tokens = $this->readTokens();
        $playersPrivateHand = $this->readPlayersPrivateHand($playersData);

        //plant method sends notification
        $this->giveCardInternal($playerId, $cardsSelectedId, $actionProgress['card_id'],$actionProgress['card_key'],$actionProgress['player_id'], $playersData, $tokens, $playersPrivateHand);

        if ($changeGameStateAndCheckAction){
            $nextState = 'next';

            // Go to next game state
            $this->gamestate->setPlayerNonMultiactive($playerId, $nextState); // deactivate player; if none left, transition to 'next' state
        }

    }

    function allBuild($active_player_id, $structure, $changeGameStateAndCheckAction){
        // Check that this is the player's turn and that it is a "possible action" at this game state (see states.inc.php)
        if ($changeGameStateAndCheckAction){
            self::checkAction( 'allBuild' );
        }

        if ($active_player_id!=0){
            $playerId = $active_player_id;
        } else {
            $playerId = $this->getCurrentPlayerId(); // CURRENT!!! not active
        }

        //action in progress
        $actionProgress = $this->checkPlayerActionInProgress(0, 'allBuild');

        $playersData = $this->getPlayersFullData();
        $tokens = $this->readTokens();
        $playersPrivateHand = $this->readPlayersPrivateHand($playersData);

        //discount?
        $discount=0;

        if ($actionProgress!=null){
            $discount=$this->getCardBuildDiscount($actionProgress['card_key']);
        }

        //plant method sends notification
        $this->buildStructureInternal($playerId, $structure, $discount);

        //618: //Handyman
        //All players may build 1 structure at a ${token_lira2} discount. You gain ${token_vp1} for each opponent who does this.
        //**special**
        //1vp bonus
        if ($actionProgress != null && $actionProgress['card_key'] == 618){
            $this->dbIncScore($actionProgress['player_id'], 1,'vit_scoring_yellow_card');
            // Notify all players
            self::notifyAllPlayers( "allBuild", clienttranslate( '${player_name} gets ${token_get}' ), array(
                'player_id' => $actionProgress['player_id'],
                'player_name' => $this->getPlayerName($actionProgress['player_id']),
                'token_get' => 'vp1'
            ) );
        }


        if ($changeGameStateAndCheckAction){
            $nextState = 'next';

            // Go to next game state
            $this->gamestate->setPlayerNonMultiactive($playerId, $nextState); // deactivate player; if none left, transition to 'next' state
        }

    }

    function allChoose($active_player_id, $choice, $changeGameStateAndCheckAction){
        // Check that this is the player's turn and that it is a "possible action" at this game state (see states.inc.php)
        if ($changeGameStateAndCheckAction){
            self::checkAction( 'allChoose' );
        }

        if ($active_player_id!=0){
            $playerId = $active_player_id;
        } else {
            $playerId = $this->getCurrentPlayerId(); // CURRENT!!! not active
        }

        //action in progress
        $actionProgress = $this->checkPlayerActionInProgress(0, 'allChoose');

        $playersData = $this->getPlayersFullData();
        $tokens = $this->readTokens();
        $playersPrivateHand = $this->readPlayersPrivateHand($playersData);

        //discount?
        $discount=0;

        $actionProgress = $this->readPlayerActionInProgress();

        //621: //Banker
        //Gain ${token_lira5}. Each opponent may lose ${token_vp1} to gain ${token_lira3}.
        if ($actionProgress['card_key'] == 621){
            $this->dbIncScore($playerId, -1,'vit_scoring_yellow_card');
            $this->dbIncLira($playerId, 3);
            // Notify all players
            self::notifyAllPlayers( "allChoose", clienttranslate( '${player_name} pays ${token_price} and gets ${token_get}' ), array(
                'player_id' => $playerId,
                'player_name' => $this->getPlayerName($playerId),
                'token_price' => 'vp1',
                'token_get' => 'lira3'
            ) );
        }

        //631 Swindler
        //Each opponent may give you ${token_lira2}. For each opponent who does not, gain ${token_vp1}.
        if ($actionProgress['card_key'] == 631){
            $this->dbIncLira($playerId, -2);
            $this->dbIncLira($actionProgress['player_id'], 2);
            // Notify all players
            self::notifyAllPlayers( "allChoose", clienttranslate( '${player_name} gives ${token_lira} to ${other_player_name}' ), array(
                'player_id' => $playerId,
                'player_name' => $this->getPlayerName($playerId),
                'other_player_name' => $this->getPlayerName($actionProgress['player_id']),
                'token_lira' => 'lira2'
            ) );
        }

        //825 Motivator
        //Each player may retrieve their grande worker. Gain ${token_vp1} for each opponent who does this.
        if ($actionProgress['card_key'] == 825){
            $worker = $this->readCardsByPlayerIdAndCardType($playerId, 'worker_g');
            if ($worker[0]['location']=='player'){
                throw new BgaUserException( self::_("You cannot retrieve worker!") );
            }
            $this->DbQuery("UPDATE card SET card_location='player' where card_type='worker_g' and card_location_arg=$playerId");

            // Notify all players
            self::notifyAllPlayers( "playBlueCard", clienttranslate( '${player_name} retrieves ${token_worker}' ), array(
                'player_id' => $playerId,
                'player_name' => $this->getPlayerName($playerId),
                'token_worker' => 'workerGrande'
            ) );

            $this->dbIncScore($actionProgress['player_id'], 1, 'vit_scoring_blue_card');
            // Notify all players
            self::notifyAllPlayers( "refuse", clienttranslate( '${player_name} gets ${token_get}' ), array(
                'player_id' => $actionProgress['player_id'],
                'player_name' => $this->getPlayerName($actionProgress['player_id']),
                'token_get' => 'vp1'
            ) );
        }

        //838 Guest Speaker
        //All players may pay ${token_lira1} to train ${token_worker}. Gain ${token_vp1} for each opponent who does this.
        if ($actionProgress['card_key'] == 838){
            $workerLocation = $actionProgress['args'];
            if ($workerLocation==0 || $workerLocation==''){
                $workerLocation = $this->getNewWorkerLocation($playerId);
            } else {
                if (array_key_exists($workerLocation, $this->boardLocations)){
                    $workerLocation = $this->boardLocations[$workerLocation]['sha'].'_new';
                } else {
                    $workerLocation = $workerLocation.'_new';
                }
            }
            $this->addWorker($playerId,'board_'.$workerLocation, 0, false);

            $this->dbIncLira($playerId, -1);
            // Notify all players
            self::notifyAllPlayers( "playBlueCard", clienttranslate( '${player_name} pays ${token_lose} and trains a new ${token_worker}' ), array(
                'player_id' => $playerId,
                'player_name' => $this->getPlayerName($playerId),
                'token_lose' => 'lira1',
                'token_worker' => 'worker'
            ) );

            $this->dbIncScore($actionProgress['player_id'], 1, 'vit_scoring_blue_card');
            // Notify all players
            self::notifyAllPlayers( "refuse", clienttranslate( '${player_name} gets ${token_get}' ), array(
                'player_id' => $actionProgress['player_id'],
                'player_name' => $this->getPlayerName($actionProgress['player_id']),
                'token_get' => 'vp1'
            ) );
        }

        if ($changeGameStateAndCheckAction){
            $nextState = 'next';

            // Go to next game state
            $this->gamestate->setPlayerNonMultiactive($playerId, $nextState); // deactivate player; if none left, transition to 'next' state
        }

    }

    function makeWine($active_player_id, $wine, $wineValue, $grapesId, $changeGameStateAndCheckAction){
        // Check that this is the player's turn and that it is a "possible action" at this game state (see states.inc.php)
        if ($changeGameStateAndCheckAction){
            self::checkAction( 'makeWine' );
        }

        if ($active_player_id!=0){
            $playerId = $active_player_id;
        } else {
            $playerId = $this->getCurrentPlayerId(); // CURRENT!!! not active
        }

        //action in progress
        $actionProgress = $this->checkPlayerActionInProgress($playerId, 'makeWine');

        $playersData = $this->getPlayersFullData();
        $tokens = $this->readTokens();
        $playersPrivateHand = $this->readPlayersPrivateHand($playersData);

        $checkStructures = true;
        if ($actionProgress!=null && $actionProgress['card_key']==833){
            $checkStructures = false;
        }

        //plant method sends notification
        $this->makeWineInternal($playerId, $wine, $wineValue, $grapesId, $playersData, $tokens, $playersPrivateHand , $checkStructures);

        $state = $this->gamestate->state();
        if ($state['type'] != "multipleactiveplayer") {
            $this->removePlayerAction($actionProgress['id']);
        }

        if ($changeGameStateAndCheckAction){
            $nextState = 'next';

            if ($state['type'] === "multipleactiveplayer") {
                // Go to next game state
                $this->gamestate->setPlayerNonMultiactive($playerId, $nextState); // deactivate player; if none left, transition to 'next' state
            } else {
                // Go to next game state
                $this->gamestate->nextState( $nextState );
            }
        }

    }

    function playYellowCard($active_player_id, $structure, $cardId, $cardKey,
        $field, $harvestFieldsId, $wine, $wineValue, $grapesId,
        $buyField, $sellField, $sellGrapesId,
        $uprootVinesId, $orderWinesId, $cardsSelectedId, $workersSelectedId, $otherSelection,
        $visitorCardId, $visitorCardKey, $visitorCardOption, $changeGameStateAndCheckAction){

        // Check that this is the player's turn and that it is a "possible action" at this game state (see states.inc.php)
        if ($changeGameStateAndCheckAction){
            self::checkAction( 'playYellowCard' );
        }

        if ($active_player_id!=0){
            $playerId = $active_player_id;
        } else {
            $playerId = $this->getCurrentPlayerId(); // CURRENT!!! not active
        }

        //action in progress
        $actionProgress = $this->checkPlayerActionInProgress($playerId, 'playYellowCard');

        $playersData = $this->getPlayersFullData();
        $tokens = $this->readTokens();
        $playersPrivateHand = $this->readPlayersPrivateHand($playersData);

        //plant method sends notification
        $this->playYellowCardInternal($playerId, 0, $structure, $cardId, $cardKey,
            $field, $harvestFieldsId, $wine, $wineValue, $grapesId,
            $buyField, $sellField, $sellGrapesId,
            $uprootVinesId, $orderWinesId, $cardsSelectedId, $workersSelectedId, $otherSelection,
            $visitorCardId, $visitorCardKey, $visitorCardOption,
            $playersData, $tokens, $playersPrivateHand, true, true);

        $this->removePlayerAction($actionProgress['id']);

        if ($changeGameStateAndCheckAction){
            // Go to next game state
            $this->gamestate->nextState( 'next' );
        }

    }

    function playBlueCard($active_player_id, $structure, $cardId, $cardKey,
        $field, $harvestFieldsId, $wine, $wineValue, $grapesId,
        $buyField, $sellField, $sellGrapesId,
        $uprootVinesId, $orderWinesId, $cardsSelectedId, $workersSelectedId, $otherSelection,
        $visitorCardId, $visitorCardKey, $visitorCardOption, $changeGameStateAndCheckAction){

        // Check that this is the player's turn and that it is a "possible action" at this game state (see states.inc.php)
        if ($changeGameStateAndCheckAction){
            self::checkAction( 'playBlueCard' );
        }

        if ($active_player_id!=0){
            $playerId = $active_player_id;
        } else {
            $playerId = $this->getCurrentPlayerId(); // CURRENT!!! not active
        }

        //action in progress
        $actionProgress = $this->checkPlayerActionInProgress($playerId, 'playBlueCard');

        $playersData = $this->getPlayersFullData();
        $tokens = $this->readTokens();
        $playersPrivateHand = $this->readPlayersPrivateHand($playersData);

        //plant method sends notification
        $this->playBlueCardInternal($playerId, 0, $structure, $cardId, $cardKey,
            $field, $harvestFieldsId, $wine, $wineValue, $grapesId,
            $buyField, $sellField, $sellGrapesId,
            $uprootVinesId, $orderWinesId, $cardsSelectedId, $workersSelectedId, $otherSelection,
            $visitorCardId, $visitorCardKey, $visitorCardOption,
            $playersData, $tokens, $playersPrivateHand, true, true);

        $this->removePlayerAction($actionProgress['id']);

        if ($changeGameStateAndCheckAction){
            // Go to next game state
            $this->gamestate->nextState( 'next' );
        }

    }

    function playCardSecondOption($active_player_id, $structure, $cardId, $cardKey,
        $field, $harvestFieldsId, $wine, $wineValue, $grapesId,
        $buyField, $sellField, $sellGrapesId,
        $uprootVinesId, $orderWinesId, $cardsSelectedId, $workersSelectedId, $otherSelection,
        $visitorCardId, $visitorCardKey, $visitorCardOption, $changeGameStateAndCheckAction){

        // Check that this is the player's turn and that it is a "possible action" at this game state (see states.inc.php)
        if ($changeGameStateAndCheckAction){
            self::checkAction( 'playCardSecondOption' );
        }

        if ($active_player_id!=0){
            $playerId = $active_player_id;
        } else {
            $playerId = $this->getCurrentPlayerId(); // CURRENT!!! not active
        }

        //action in progress
        $actionProgress = $this->checkPlayerActionInProgress($playerId, 'playCardSecondOption');

        $playersData = $this->getPlayersFullData();
        $tokens = $this->readTokens();
        $playersPrivateHand = $this->readPlayersPrivateHand($playersData);

        $cardType = $this->getCardType($visitorCardKey);

        if ($cardType == 'yellowCard'){
            //plant method sends notification
            $this->playYellowCardInternal($playerId, 0, $structure, $cardId, $cardKey,
                $field, $harvestFieldsId, $wine, $wineValue, $grapesId,
                $buyField, $sellField, $sellGrapesId,
                $uprootVinesId, $orderWinesId, $cardsSelectedId, $workersSelectedId, $otherSelection,
                $visitorCardId, $visitorCardKey, $visitorCardOption,
                $playersData, $tokens, $playersPrivateHand, false, true);

        } else if ($cardType == 'blueCard'){
            //plant method sends notification
            $this->playBlueCardInternal($playerId, 0, $structure, $cardId, $cardKey,
                $field, $harvestFieldsId, $wine, $wineValue, $grapesId,
                $buyField, $sellField, $sellGrapesId,
                $uprootVinesId, $orderWinesId, $cardsSelectedId, $workersSelectedId, $otherSelection,
                $visitorCardId, $visitorCardKey, $visitorCardOption,
                $playersData, $tokens, $playersPrivateHand, false, true);
        } else {

            throw new BgaUserException( self::_("Wrong card!") );

        }

        $this->removePlayerAction($actionProgress['id']);

        if ($changeGameStateAndCheckAction){
            // Go to next game state
            $this->gamestate->nextState( 'next' );
        }

    }


    function fillOrder($active_player_id, $cardId, $cardKey, $orderWinesId, $changeGameStateAndCheckAction){
        // Check that this is the player's turn and that it is a "possible action" at this game state (see states.inc.php)
        if ($changeGameStateAndCheckAction){
            self::checkAction( 'fillOrder' );
        }

        if ($active_player_id!=0){
            $playerId = $active_player_id;
        } else {
            $playerId = $this->getCurrentPlayerId(); // CURRENT!!! not active
        }

        //action in progress
        $actionProgress = $this->checkPlayerActionInProgress($playerId, 'fillOrder');

        $playersData = $this->getPlayersFullData();
        $tokens = $this->readTokens();
        $playersPrivateHand = $this->readPlayersPrivateHand($playersData);

        //plant method sends notification
        $this->fillOrderInternal($playerId, $cardId, $cardKey, $orderWinesId, $playersData, $tokens, $playersPrivateHand ,0);

        $this->removePlayerAction($actionProgress['id']);

        if ($changeGameStateAndCheckAction){
            // Go to next game state
            $this->gamestate->nextState( 'next' );
        }

    }

    function chooseFallCard($active_player_id, $card, $cardSecond, $changeGameStateAndCheckAction){
        // Check that this is the player's turn and that it is a "possible action" at this game state (see states.inc.php)
        if ($changeGameStateAndCheckAction){
            self::checkAction( 'chooseFallCard' );
        }

        if ($active_player_id!=0){
            $playerId = $active_player_id;
        } else {
            $playerId = $this->getCurrentPlayerId(); // CURRENT!!! not active
        }

        $playerData = $this->getPlayerData($playerId);

        if ($card != 'yellow' && $card != 'blue'){
            throw new BgaUserException( self::_("Deck not valid!") );
        }

        if ($playerData['cottage']==1){
            if ($cardSecond != 'yellow' && $cardSecond != 'blue'){
                throw new BgaUserException( self::_("Second deck not valid!") );
            }
        }

        if ($card == 'yellow'){
            //drawFromDeck method sends notification
            $this->drawFromDeck($playerId, DECK_YELLOW, 1, true);
        }
        if ($card == 'blue'){
            //drawFromDeck method sends notification
            $this->drawFromDeck($playerId, DECK_BLUE, 1, true);
        }

        if ($playerData['cottage']==1){
            if ($cardSecond == 'yellow'){
                //drawFromDeck method sends notification
                $this->drawFromDeck($playerId, DECK_YELLOW, 1, true);
            }
            if ($cardSecond == 'blue'){
                //drawFromDeck method sends notification
                $this->drawFromDeck($playerId, DECK_BLUE, 1, true);
            }
        }

        if ($changeGameStateAndCheckAction){
            // Go to next game state
            $this->gamestate->nextState( 'next' );
        }

    }

    function chooseVisitorCardDraw($active_player_id, $card, $changeGameStateAndCheckAction){
        // Check that this is the player's turn and that it is a "possible action" at this game state (see states.inc.php)
        if ($changeGameStateAndCheckAction){
            self::checkAction( 'chooseVisitorCardDraw' );
        }

        if ($active_player_id!=0){
            $playerId = $active_player_id;
        } else {
            $playerId = $this->getCurrentPlayerId(); // CURRENT!!! not active
        }

        //action in progress
        $actionProgress = $this->checkPlayerActionInProgress($playerId, 'chooseVisitorCardDraw');

        if ($card != 'yellow' && $card != 'green'){
            throw new BgaUserException( self::_("Deck not valid!") );
        }

        if ($card == 'yellow'){
            //drawFromDeck method sends notification
            $this->drawFromDeck($playerId, DECK_YELLOW, 1, true);
        }
        if ($card == 'green'){
            //drawFromDeck method sends notification
            $this->drawFromDeck($playerId, DECK_GREEN, 1, true);
        }

        $this->removePlayerAction($actionProgress['id']);

        if ($changeGameStateAndCheckAction){
            // Go to next game state
            $this->gamestate->nextState( 'next' );
        }

    }

    function chooseCards($active_player_id, $cardsSelectedId, $changeGameStateAndCheckAction){
        // Check that this is the player's turn and that it is a "possible action" at this game state (see states.inc.php)
        if ($changeGameStateAndCheckAction){
            self::checkAction( 'chooseCards' );
        }

        if ($active_player_id!=0){
            $playerId = $active_player_id;
        } else {
            $playerId = $this->getCurrentPlayerId(); // CURRENT!!! not active
        }

        //action in progress
        $actionProgress = $this->checkPlayerActionInProgress($playerId, 'chooseCards');

        //check all cards are present in player hand
        $cardsIdJoin = implode(',',$cardsSelectedId);
        $sql = "SELECT card_id id, card_type type, card_type_arg type_arg FROM card WHERE card_location='chooseCards' AND card_location_arg = $playerId and card_id in (${cardsIdJoin})";
        $cards = self::getObjectListFromDB( $sql);

        if (count($cards) != count($cardsSelectedId)){
            throw new BgaUserException( self::_("Cards not valid!") );
        }

        if (count($cards) != 2){
            throw new BgaUserException( self::_("Wrong card selection") );
        }

        foreach ($cardsSelectedId as $cardsSelectedIdKey => $cardsSelectedIdValue) {
            $this->cards->moveCard( $cardsSelectedIdValue, 'hand', $playerId );
        }

        //discard all other cards
        $sql = "SELECT card_id id, card_type type, card_type_arg type_arg FROM card WHERE card_location='chooseCards' AND card_location_arg = $playerId";
        $otherCards = self::getObjectListFromDB( $sql);
        foreach ($otherCards as $otherCardsKey => $otherCardsValue){
            //discard on top
            $this->discardCardOnDeckTop($otherCardsValue['id'], $otherCardsValue['type_arg']);
        }

        $this->removePlayerAction($actionProgress['id']);

        if ($changeGameStateAndCheckAction){
            // Go to next game state
            $this->gamestate->nextState( 'next' );
        }

    }

    function chooseOptions($active_player_id, $option, $cardsSelectedId, $changeGameStateAndCheckAction){
        // Check that this is the player's turn and that it is a "possible action" at this game state (see states.inc.php)
        if ($changeGameStateAndCheckAction){
            self::checkAction( 'chooseOptions' );
        }

        if ($active_player_id!=0){
            $playerId = $active_player_id;
        } else {
            $playerId = $this->getCurrentPlayerId(); // CURRENT!!! not active
        }

        $playersData = $this->getPlayersFullData();
        $tokens = $this->readTokens();
        $playersPrivateHand = $this->readPlayersPrivateHand($playersData);

        //action in progress
        $actionProgress = $this->checkPlayerActionInProgress($playerId, 'chooseOptions');

        if ($option<1 || $option > 3){
            throw new BgaUserException( self::_("Wrong option") );
        }

        $otherPlayerId=$actionProgress['args'];

        //811 Queen
        //The player on your right must choose 1: lose ${token_vp1}, give you 2 ${token_anyCard}, or pay you ${token_lira3}.
        if ($option == 1){
            $this->dbIncScore($playerId, -1, 'vit_scoring_blue_card');
            // Notify all players
            self::notifyAllPlayers( "chooseOptions", clienttranslate( '${player_name} loses ${token_lose}' ), array(
                'player_id' => $playerId,
                'player_name' => $this->getPlayerName($playerId),
                'token_lose' => 'vp1'
            ) );
        } else if ($option == 2){

            if (count($cardsSelectedId)!=2){
                throw new BgaUserException( self::_("Wrong card selection") );
            }
            $this->giveCardInternal($playerId, $cardsSelectedId, $actionProgress['card_id'], $actionProgress['card_key'], $otherPlayerId, $playersData, $tokens, $playersPrivateHand);
        } else if ($option == 3){
            $this->dbIncLira($playerId, -3);
            $this->dbIncLira($otherPlayerId, 3);
            // Notify all players
            self::notifyAllPlayers( "chooseOptions", clienttranslate( '${player_name} gives ${token_lose} to ${other_player_name}' ), array(
                'player_id' => $playerId,
                'player_name' => $this->getPlayerName($playerId),
                'other_player_name' => $this->getPlayerName($otherPlayerId),
                'token_lose' => 'lira3'
            ) );
        }

        $this->removePlayerAction($actionProgress['id']);

        if ($changeGameStateAndCheckAction){
            // Go to next game state
            $this->gamestate->nextState( 'next' );
        }

    }



//////////////////////////////////////////////////////////////////////////////
//////////// Game state arguments
////////////

    /*
        Here, you can create methods defined as "game state arguments" (see "args" property in states.inc.php).
        These methods function is to return some additional information that is specific to the current
        game state.
    */

    /*

    Example for game state "MyGameState":

    function argMyGameState()
    {
        // Get some values from the current game situation in database...

        // return values:
        return array(
            'variable1' => $value1,
            'variable2' => $value2,
            ...
        );
    }
    */
    function argMamaPapaChoose(){
        $basicData = $this->readBasicDataForClient();

        $data = array(
            "chooseMamaPapa" => $this->readChooseMamaPapa()
        );

        $data = array_merge($data, $basicData);

        // return values:
        return $data;
    }

    function argPapaOptionChoose(){
        $basicData = $this->readBasicDataForClient();

        $data = array(
        );
        $data = array_merge($data, $basicData);

        // return values:
        return $data;
    }

    function argSpringChooseWakeup(){
        $basicData = $this->readBasicDataForClient();

        $data = array(
            'activeWakeupOrder'=>$this->readActiveWakeupOrder()
        );

        $data = array_merge($data, $basicData);

        // return values:
        return $data;
    }

    function argSeasonWorkers(){
        $playerId = self::getActivePlayerId();
        $basicData = $this->readBasicDataForClient();

        $activeLocations = $this->readActiveLocations($playerId, $basicData['players'], $basicData['tokens'], $basicData['_private']);
        $possibleWines = $this->readPossibleWineMakeable($playerId, $basicData['players'][$playerId], true, 1);

        $possibleGreenCards = $this->readPossibleGreenCards($playerId, $basicData['players'][$playerId], $basicData['tokens'], $basicData['_private'], true, true);
        $possibleYellowCards = $this->readPossibleYellowCards($playerId, $basicData['players'][$playerId], $basicData['players'], $basicData['tokens'], $basicData['_private']);
        $possiblePurpleCards = $this->readPossiblePurpleCards($playerId, $basicData['players'][$playerId], $basicData['tokens'], $basicData['_private'], false);
        $possibleBlueCards = $this->readPossibleBlueCards($playerId, $basicData['players'][$playerId], $basicData['players'], $basicData['tokens'], $basicData['_private']);

        $this->enrichPrivateHandGreenCards($basicData['_private'], $playerId, $possibleGreenCards);
        $this->enrichPrivateHandYellowCards($basicData['_private'], $playerId, $possibleYellowCards);
        $this->enrichPrivateHandBlueCards($basicData['_private'], $playerId, $possibleBlueCards);
        $this->enrichPrivateHandPurpleCards($basicData['_private'], $playerId, $possiblePurpleCards);

        $data = array(
            'activeLocations' => $activeLocations,
            'possibleWines' => $possibleWines,
            'possibleBlueCards' => $possibleBlueCards
        );

        //833	Zymologist
        //Make up to 2 ${token_wineAny} of value 4 or greater, even if you haven't upgraded your cellar.
        if ($this->arrayFindByProperty($basicData['_private'][$playerId]['hand'],'k',833)!=null){
            $data['possibleWinesWS'] = $this->readPossibleWineMakeable($playerId, $basicData['players'][$playerId], false, 4);
        }

        $data = array_merge($data, $basicData);

        // return values:
        return $data;
    }

    function argFallChooseCard(){
        $playerId = self::getActivePlayerId();
        $basicData = $this->readBasicDataForClient();

        $secondCardChoice='0';
        $customStateDescription = 'descriptionChoose1';
        if ($basicData['players'][$playerId]['cottage']==1){
            $secondCardChoice='1';
            $customStateDescription = 'descriptionChoose2';
        }

        $data = array(
            "secondCardChoice" => $secondCardChoice,
            "customStateDescription" => $customStateDescription
        );

        $data = array_merge($data, $basicData);

        // return values:
        return $data;
    }

    function argPlant(){
        $playerId = self::getActivePlayerId();
        $basicData = $this->readBasicDataForClient();

        $possibleGreenCards = $this->readPossibleGreenCards($playerId, $basicData['players'][$playerId], $basicData['tokens'], $basicData['_private'], true, true);
        $this->enrichPrivateHandGreenCards($basicData['_private'], $playerId, $possibleGreenCards);

        $checkLimit = true;
        $checkStructures = true;

        $actionProgress = $basicData['actionProgress'];

        if ($actionProgress!=null){
            //627: //Cultivator
            //Plant 1 ${token_greenCard}. You may plant it on a field even if the total value of that field exceeds the max vine value.
            //plant_1_overMax
            if ($actionProgress['card_key']==627){
                $checkLimit = false;
            }
            //619: //Horticulturist
            //Plant 1 ${token_greenCard} even if you don't have the required structure(s) OR uproot and discard 2 ${token_greenCard} to gain ${token_vp3}.
            //**special**
            //624: //Sharecropper
            //Plant 1 ${token_greenCard} even if you don't have the required structure(s) OR uproot and discard 1 ${token_greenCard} to gain ${token_vp2}.
            //plant_1_noStructure|uprootAndDiscard_1+getVp_2
            if ($actionProgress['card_key']==619||$actionProgress['card_key']==624){
                $checkStructures = false;
            }
        }

        $data = array(
            'checkLimit'=>$checkLimit,
            'checkStructures'=>$checkStructures
        );

        $data = array_merge($data, $basicData);

        // return values:
        return $data;
    }

    function argAllPlant(){
        $playerId = self::getActivePlayerId();
        $basicData = $this->readBasicDataForClient();
        foreach ($basicData['players'] as $playersKey => $playersValue) {
            $possibleGreenCards = $this->readPossibleGreenCards($playersKey, $playersValue, $basicData['tokens'], $basicData['_private'], true, true);
            $this->enrichPrivateHandGreenCards($basicData['_private'], $playersKey, $possibleGreenCards);
        }

        $data = array(
        );

        $data = array_merge($data, $basicData);

        // return values:
        return $data;
    }


    function argAllGiveCard(){
        $playerId = self::getActivePlayerId();
        $basicData = $this->readBasicDataForClient();

        $token_card = '';
        $playerIdGive = '';
        $playerNameGive = '';
        $minGiveCard = 0;
        $maxGiveCard = 0;
        $cardTypes = array();
        $actionProgress = $basicData['actionProgress'];
        $offerCardsCount = array();
        $playersData = $basicData['players'];
        $privateHandCards = $basicData['_private'];
        $token_card1 = '';
        $token_card2 = '';
        $token_card = '';

        $customStateDescription = '';

        if ($actionProgress!=null){
            //835 Governor
            //Choose up to 3 opponents to each give you 1 ${token_yellowCard}. Gain ${token_vp1} for each of them who cannot.
            if ($actionProgress['card_key']==835){
                $playerIdGive = $actionProgress['player_id'];
                $playerNameGive = $this->getPlayerName($actionProgress['player_id']);
                $minGiveCard = 1;
                $maxGiveCard = 1;
                $cardTypes = array('yellowCard');
                $token_card = 'yellowCard';

                $customStateDescription = 'description835';
            }
            //623	Importer
            //Draw 3 ${token_blueCardPlus} cards unless all opponents combine to give you 3 visitor cards (total).
            if ($actionProgress['card_key']==623){
                $playerIdGive = $actionProgress['player_id'];
                $playerNameGive = $this->getPlayerName($actionProgress['player_id']);
                $minGiveCard = 0;
                $maxGiveCard = 3;
                $cardTypes = array('blueCard','yellowCard');
                $token_card = '';
                $token_card1 = 'yellowCard';
                $token_card2 = 'blueCard';
                foreach ($playersData as $playersDataKey => $playersDataValue) {
                    if ($playersDataKey!=$actionProgress['player_id']){
                        $offerCardsCount[$playersDataKey]=0;
                        $privateHandCards[$playersDataKey]['offerCards']=array();
                    }
                }
                $offerCards = $this->cards->getCardsInLocation('offerCards');
                foreach ($offerCards as $offerCardsKey => $offerCardsValue) {
                    $offerCardsCount[$offerCardsValue['location_arg']]++;
                    $privateHandCards[$playersDataKey]['offerCards'][]=$offerCardsValue;
                }
                $customStateDescription = 'description623';

                
            }
        }

        $data = array(
            'token_card' => $token_card,
            'token_card1' => $token_card1,
            'token_card2' => $token_card2,
            'customStateDescription' => $customStateDescription,
            'playerIdGive' => $playerIdGive,
            'playerNameGive' => $playerNameGive,
            'minGiveCard' => $minGiveCard,
            'maxGiveCard' => $maxGiveCard,
            'cardTypes' => $cardTypes,
            'offerCardsCount' => $offerCardsCount
        );

        $data = array_merge($data, $basicData);

        // return values:
        return $data;
    }

    function argAllBuild(){
        $playerId = self::getActivePlayerId();
        $basicData = $this->readBasicDataForClient();

        $discount = 0;
        $actionProgress = $basicData['actionProgress'];
        //618: //Handyman
        //All players may build 1 structure at a ${token_lira2} discount. You gain ${token_vp1} for each opponent who does this.
        //**special**
        if ($actionProgress!=null){
            $discount=$this->getCardBuildDiscount($actionProgress['card_key']);
        }

        $data = array(
            'discount'=>$discount
        );

        $data = array_merge($data, $basicData);

        // return values:
        return $data;
    }


    function argAllChoose(){
        $playerId = self::getActivePlayerId();
        $basicData = $this->readBasicDataForClient();

        $chooseText = 0;
        $actionProgress = $basicData['actionProgress'];
        $other_player_name = $this->getPlayerName($actionProgress['player_id']);

        //621 Banker
        //Gain ${token_lira5}. Each opponent may lose ${token_vp1} to gain ${token_lira3}.
        if ($actionProgress!=null && $actionProgress['card_key']==621){
            //$chooseText = clienttranslate('lose ${token_vp1} to gain ${token_lira3}');
            $chooseText = 'description621';
        }

        //631 Swindler
        //Each opponent may give you ${token_lira2}. For each opponent who does not, gain ${token_vp1}.
        if ($actionProgress!=null && $actionProgress['card_key']==631){
            //$chooseText = str_replace('${other_player_name}',$other_player_name,clienttranslate('give ${token_lira2} to ${other_player_name}, or ${other_player_name} gains ${token_vp1}'));
            $chooseText = 'description631';
        }

        //825 Motivator
        //Each player may retrieve their grande worker. Gain ${token_vp1} for each opponent who does this.
        if ($actionProgress!=null && $actionProgress['card_key']==825){
            //$chooseText = str_replace('${other_player_name}',$other_player_name,clienttranslate('retrieve grande worker ${token_workerGrande} and ${other_player_name} gains ${token_vp1}'));
            $chooseText = 'description825';
        }

        //838 Guest Speaker
        //All players may pay ${token_lira1} to train ${token_worker}. Gain ${token_vp1} for each opponent who does this.
        if ($actionProgress!=null && $actionProgress['card_key']==838){
            //$chooseText = str_replace('${other_player_name}',$other_player_name,clienttranslate('pay ${token_lira1} to train a worker and ${other_player_name} gains ${token_vp1}'));
            $chooseText = 'description838';
        }

        $data = array(
            'customStateDescription'=>$chooseText,
            'token_vp1'=>'vp1',
            'token_lira1'=>'lira1',
            'token_lira2'=>'lira2',
            'token_lira3'=>'lira3',
            'other_player_name'=>$other_player_name,
            'token_workerGrande'=>'workerGrande'
        );

        $data = array_merge($data, $basicData);

        // return values:
        return $data;
    }

    function argMakeWine(){
        $playerId = self::getActivePlayerId();
        $basicData = $this->readBasicDataForClient();

        $checkStructures = true;

        $actionProgress = $basicData['actionProgress'];

        //833	Zymologist
        //Make up to 2 ${token_wineAny} of value 4 or greater, even if you haven't upgraded your cellar.
        $minimumWineValue = 1;
        if ($actionProgress!=null && $actionProgress['card_key']==833){
            $checkStructures = false;
            $minimumWineValue = 4;
        }
        $possibleWines = $this->readPossibleWineMakeable($playerId, $basicData['players'][$playerId], $checkStructures, $minimumWineValue);

        $data = array(
            'possibleWines' => $possibleWines,
            'possibleWinesWS' => $possibleWines,
            'checkStructures' => $checkStructures
        );

        $data = array_merge($data, $basicData);

        // return values:
        return $data;
    }
    
    function argExecuteLocation(){
        $playerId = self::getActivePlayerId();
        $basicData = $this->readBasicDataForClient();

        $actionProgress = $basicData['actionProgress'];

        $activeLocations = $this->readActiveLocations($playerId, $basicData['players'], $basicData['tokens'], $basicData['_private']);

        $possibleWines = $this->readPossibleWineMakeable($playerId, $basicData['players'][$playerId], true, 1);

        $possibleGreenCards = $this->readPossibleGreenCards($playerId, $basicData['players'][$playerId], $basicData['tokens'], $basicData['_private'], true, true);
        $possibleYellowCards = $this->readPossibleYellowCards($playerId, $basicData['players'][$playerId], $basicData['players'], $basicData['tokens'], $basicData['_private']);
        $possiblePurpleCards = $this->readPossiblePurpleCards($playerId, $basicData['players'][$playerId], $basicData['tokens'], $basicData['_private'], false);
        $possibleBlueCards = $this->readPossibleBlueCards($playerId, $basicData['players'][$playerId], $basicData['players'], $basicData['tokens'], $basicData['_private']);

        $this->enrichPrivateHandGreenCards($basicData['_private'], $playerId, $possibleGreenCards);
        $this->enrichPrivateHandYellowCards($basicData['_private'], $playerId, $possibleYellowCards);
        $this->enrichPrivateHandBlueCards($basicData['_private'], $playerId, $possibleBlueCards);
        $this->enrichPrivateHandPurpleCards($basicData['_private'], $playerId, $possiblePurpleCards);

        $data = array(
            'location' => $actionProgress['args'],
            'activeLocations' => $activeLocations,
            'possibleWines' => $possibleWines
        );

        $data = array_merge($data, $basicData);

        // return values:
        return $data;
    }
        
    function argTakeActionPrev(){
        $playerId = self::getActivePlayerId();
        $basicData = $this->readBasicDataForClient();

        $season = self::getGameStateValue('season');

        $activeLocationsPrev=array();
        $locationSeasons = $this->getLocationSeasons();
        foreach ($locationSeasons as $locationSeasonsKey => $locationSeasonsValue) {
            if ($locationSeasonsValue<$season){
                $activeLocationsSeason = $this->readActiveLocations($playerId, $basicData['players'], $basicData['tokens'], $basicData['_private'], $locationSeasonsValue, false, false, false);
                
                foreach ($activeLocationsSeason as $activeLocationsSeasonKey => $activeLocationsSeasonValue) {
                    $activeLocationsSeason[$activeLocationsSeasonKey]['s']=$locationSeasonsValue;
                    $activeLocationsPrev[]=$activeLocationsSeasonValue;
                }
            }
        }

        $possibleWines = $this->readPossibleWineMakeable($playerId, $basicData['players'][$playerId], true, 1);

        $possibleGreenCards = $this->readPossibleGreenCards($playerId, $basicData['players'][$playerId], $basicData['tokens'], $basicData['_private'], true, true);
        $possibleYellowCards = $this->readPossibleYellowCards($playerId, $basicData['players'][$playerId], $basicData['players'], $basicData['tokens'], $basicData['_private']);
        $possiblePurpleCards = $this->readPossiblePurpleCards($playerId, $basicData['players'][$playerId], $basicData['tokens'], $basicData['_private'], false);
        $possibleBlueCards = $this->readPossibleBlueCards($playerId, $basicData['players'][$playerId], $basicData['players'], $basicData['tokens'], $basicData['_private']);

        $this->enrichPrivateHandGreenCards($basicData['_private'], $playerId, $possibleGreenCards);
        $this->enrichPrivateHandYellowCards($basicData['_private'], $playerId, $possibleYellowCards);
        $this->enrichPrivateHandBlueCards($basicData['_private'], $playerId, $possibleBlueCards);
        $this->enrichPrivateHandPurpleCards($basicData['_private'], $playerId, $possiblePurpleCards);

        $data = array(
            'activeLocationsPrev' => $activeLocationsPrev,
            'possibleWines' => $possibleWines
        );

        $data = array_merge($data, $basicData);

        // return values:
        return $data;
    }

    function argPlayYellowCard(){
        $playerId = self::getActivePlayerId();
        $basicData = $this->readBasicDataForClient();

        $possibleWines = $this->readPossibleWineMakeable($playerId, $basicData['players'][$playerId], true, 1);

        $possibleGreenCards = $this->readPossibleGreenCards($playerId, $basicData['players'][$playerId], $basicData['tokens'], $basicData['_private'], true, true);
        $possibleYellowCards = $this->readPossibleYellowCards($playerId, $basicData['players'][$playerId], $basicData['players'], $basicData['tokens'], $basicData['_private']);
        $possiblePurpleCards = $this->readPossiblePurpleCards($playerId, $basicData['players'][$playerId], $basicData['tokens'], $basicData['_private'], false);
        $possibleBlueCards = $this->readPossibleBlueCards($playerId, $basicData['players'][$playerId], $basicData['players'], $basicData['tokens'], $basicData['_private']);

        $this->enrichPrivateHandGreenCards($basicData['_private'], $playerId, $possibleGreenCards);
        $this->enrichPrivateHandYellowCards($basicData['_private'], $playerId, $possibleYellowCards);
        $this->enrichPrivateHandBlueCards($basicData['_private'], $playerId, $possibleBlueCards);
        $this->enrichPrivateHandPurpleCards($basicData['_private'], $playerId, $possiblePurpleCards);

        $data = array(
            'possibleWines' => $possibleWines
        );

        //833	Zymologist
        //Make up to 2 ${token_wineAny} of value 4 or greater, even if you haven't upgraded your cellar.
        if ($this->arrayFindByProperty($basicData['_private'][$playerId]['hand'],'k',833)!=null){
            $data['possibleWinesWS'] = $this->readPossibleWineMakeable($playerId, $basicData['players'][$playerId], false, 4);
        }

        $data = array_merge($data, $basicData);

        // return values:
        return $data;
    }

    function argPlayBlueCard(){
        $playerId = self::getActivePlayerId();
        $basicData = $this->readBasicDataForClient();

        $possibleWines = $this->readPossibleWineMakeable($playerId, $basicData['players'][$playerId], true, 1);

        $possibleGreenCards = $this->readPossibleGreenCards($playerId, $basicData['players'][$playerId], $basicData['tokens'], $basicData['_private'], true, true);
        $possibleYellowCards = $this->readPossibleYellowCards($playerId, $basicData['players'][$playerId], $basicData['players'], $basicData['tokens'], $basicData['_private']);
        $possiblePurpleCards = $this->readPossiblePurpleCards($playerId, $basicData['players'][$playerId], $basicData['tokens'], $basicData['_private'], false);
        $possibleBlueCards = $this->readPossibleBlueCards($playerId, $basicData['players'][$playerId], $basicData['players'], $basicData['tokens'], $basicData['_private']);

        $this->enrichPrivateHandGreenCards($basicData['_private'], $playerId, $possibleGreenCards);
        $this->enrichPrivateHandYellowCards($basicData['_private'], $playerId, $possibleYellowCards);
        $this->enrichPrivateHandBlueCards($basicData['_private'], $playerId, $possibleBlueCards);
        $this->enrichPrivateHandPurpleCards($basicData['_private'], $playerId, $possiblePurpleCards);

        $data = array(
            'possibleWines' => $possibleWines
        );

        //833	Zymologist
        //Make up to 2 ${token_wineAny} of value 4 or greater, even if you haven't upgraded your cellar.
        if ($this->arrayFindByProperty($basicData['_private'][$playerId]['hand'],'k',833)!=null){
            $data['possibleWinesWS'] = $this->readPossibleWineMakeable($playerId, $basicData['players'][$playerId], false, 4);
        }

        $data = array_merge($data, $basicData);

        // return values:
        return $data;
    }

    function argPlayCardSecondOption(){
        $playerId = self::getActivePlayerId();
        $basicData = $this->readBasicDataForClient();

        $playerAction = $this->readPlayerAction($playerId, STATUS_IN_PROGRESS);
        $vpPrice = $this->getCardVpPriceBothActions($playerAction['card_key']);

        $possibleGreenCards = $this->readPossibleGreenCards($playerId, $basicData['players'][$playerId], $basicData['tokens'], $basicData['_private'], true, true);
        $possibleYellowCards = $this->readPossibleYellowCards($playerId, $basicData['players'][$playerId], $basicData['players'], $basicData['tokens'], $basicData['_private']);
        $possiblePurpleCards = $this->readPossiblePurpleCards($playerId, $basicData['players'][$playerId], $basicData['tokens'], $basicData['_private'], false);
        $possibleBlueCards = $this->readPossibleBlueCards($playerId, $basicData['players'][$playerId], $basicData['players'], $basicData['tokens'], $basicData['_private']);

        $this->enrichPrivateHandGreenCards($basicData['_private'], $playerId, $possibleGreenCards);
        $this->enrichPrivateHandYellowCards($basicData['_private'], $playerId, $possibleYellowCards);
        $this->enrichPrivateHandBlueCards($basicData['_private'], $playerId, $possibleBlueCards);
        $this->enrichPrivateHandPurpleCards($basicData['_private'], $playerId, $possiblePurpleCards);
        
        $possibleWines = $this->readPossibleWineMakeable($playerId, $basicData['players'][$playerId], true, 1);
        
        $data = array(
            'possibleWines' => $possibleWines,
            'cardSecondOption' => array('visitorCardId'=>$playerAction['card_id'],
               'visitorCardKey'=>$playerAction['card_key'],
               'visitorCardFirstOption'=>$playerAction['args'],
               'vpPrice'=>$vpPrice)
        );

        $data = array_merge($data, $basicData);

        // return values:
        return $data;
    }

    function argFillOrder(){
        $playerId = self::getActivePlayerId();
        $basicData = $this->readBasicDataForClient();
       
        $possiblePurpleCards = $this->readPossiblePurpleCards($playerId, $basicData['players'][$playerId], $basicData['tokens'], $basicData['_private'], false);
        $this->enrichPrivateHandPurpleCards($basicData['_private'], $playerId, $possiblePurpleCards);

        $data = array(
        );

        $data = array_merge($data, $basicData);

        // return values:
        return $data;
    }

    function argDiscardCards(){
        $basicData = $this->readBasicDataForClient();

        $data = array(
        );

        $data = array_merge($data, $basicData);

        // return values:
        return $data;
    }

    function argDiscardVines(){
        $basicData = $this->readBasicDataForClient();

        $actionProgress = $basicData['actionProgress'];

        //624: //Sharecropper
        //Plant 1 ${token_greenCard} even if you don't have the required structure(s) OR uproot and discard 1 ${token_greenCard} to gain ${token_vp2}.
        //plant_1_noStructure|uprootAndDiscard_1+getVp_2
        //609: //Planter
        //Plant up to 2 ${token_greenCard} and gain ${token_lira1} OR uproot and discard 1 ${token_greenCard} to gain ${token_vp2}.
        //plant_2+getLira_1|uprootAndDiscard_1+getVp_2
        $minCards = 1;
        $maxCards = 1;
        //619: //Horticulturist
        //Plant 1 ${token_greenCard} even if you don't have the required structure(s) OR uproot and discard 2 ${token_greenCard} to gain ${token_vp3}.
        //**special**
        if ($actionProgress!=null && $actionProgress['card_key']==619){
            $minCards = 2;
            $maxCards = 2;
        }

        $data = array(
            'type' => 'greenCard',
            'minCards' => $minCards,
            'maxCards' => $maxCards,
            'token_card'=>'greenCard'
        );

        $data = array_merge($data, $basicData);

        // return values:
        return $data;
    }

    function argChooseVisitorCardDraw(){
        $basicData = $this->readBasicDataForClient();

        $data = array(
        );

        $data = array_merge($data, $basicData);

        // return values:
        return $data;
    }

    function argChooseCards(){
        $playerId = self::getActivePlayerId();
        $basicData = $this->readBasicDataForClient();

        $playerChooseCards = $this->readPlayerChooseCards($playerId);
        $basicData['_private'][$playerId]['chooseCards'] = $playerChooseCards;
        
        $playerAction = $this->readPlayerAction($playerId, STATUS_IN_PROGRESS);

        $data = array(
            'maxCards'=>$playerAction['args']
        );

        $data = array_merge($data, $basicData);

        // return values:
        return $data;
    }

    function argChooseOptions(){
        $playerId = self::getActivePlayerId();
        $basicData = $this->readBasicDataForClient();
        $playerFullData = $basicData['players'][$playerId];
        $hand = $basicData['_private'][$playerId]['hand'];
        $lira = (int)$playerFullData['lira'];
        $score = (int)$playerFullData['score'];

        $choice1 = false;
        $choice2 = false;
        $choice3 = false;

        $playerIdGive = '';
        $playerNameGive = '';
        $actionProgress = $basicData['actionProgress'];

        //811 Queen
        //The player on your right must choose 1: lose ${token_vp1}, give you 2 ${token_anyCard}, or pay you ${token_lira3}.
        if ($actionProgress!=null){
            if ($actionProgress['card_key']==811){
                $playerIdGive = $actionProgress['player_id'];
                $playerNameGive = $this->getPlayerName($actionProgress['player_id']);
                if ($playerFullData['score']>=-4){
                    $choice1 = true;
                }
                if (count($hand)>=2){
                    $choice2 = true;
                }
                if ($playerFullData['lira']>=3){
                    $choice3 = true;
                }
            }
        }

        $data = array(
            'choice1'=>$choice1,
            'choice2'=>$choice2,
            'choice3'=>$choice3,
            'playerIdGive' => $playerIdGive,
            'playerNameGive' => $playerNameGive
        );

        $data = array_merge($data, $basicData);

        // return values:
        return $data;
    }


//////////////////////////////////////////////////////////////////////////////
//////////// Game state actions
////////////

    /*
        Here, you can create methods defined as "game state actions" (see "action" property in states.inc.php).
        The action method of state X is called everytime the current game state is set to X.
    */

    /*

    Example for game state "MyGameState":

    function stMyGameState()
    {
        // Do some stuff ...

        // (very often) go to another gamestate
        $this->gamestate->nextState( 'some_gamestate_transition' );
    }
    */

    function stStartGame(){
        $mamas = array_values($this->mamas);
        shuffle($mamas);
        $papas = array_values($this->papas);
        shuffle($papas);

        $progrMama = 0;
        $progrPapa = 0;

        $players = $this->getPlayersData();
        if ($this->isMamaPapaChoice()){
            //select 2 mamas and papas for player and then let the player choose
            foreach ($players as $playerId => $playerData) {
                $cards = array();
                $cards[] = array('type' => 'mama', 'type_arg' => $mamas[$progrMama]['key'], 'nbr' => 1);
                $progrMama++;
                $cards[] = array('type' => 'mama', 'type_arg' => $mamas[$progrMama]['key'], 'nbr' => 1);
                $progrMama++;
                $this->cards->createCards( $cards, 'choiceMamas', $playerId);
                $cards = array();
                $cards[] = array('type' => 'papa', 'type_arg' => $papas[$progrPapa]['key'], 'nbr' => 1);
                $progrPapa++;
                $cards[] = array('type' => 'papa', 'type_arg' => $papas[$progrPapa]['key'], 'nbr' => 1);
                $progrPapa++;
                $this->cards->createCards( $cards, 'choicePapas', $playerId);
            }

            $this->gamestate->setAllPlayersMultiactive();

            $this->gamestate->nextState( 'choose' );
        } else {
            //shuffle and select 1 mamas and 1 papas for each player
            foreach ($players as $playerId => $playerData) {
                $cards = array();
                $cards[] = array('type' => 'mama', 'type_arg' => $mamas[$progrMama]['key'], 'nbr' => 1);
                $progrMama++;
                $this->cards->createCards( $cards, 'mama', $playerId);
                $cards = array();
                $cards[] = array('type' => 'papa', 'type_arg' => $papas[$progrPapa]['key'], 'nbr' => 1);
                $progrPapa++;
                $this->cards->createCards( $cards, 'papa', $playerId);
            }
            $this->gamestate->nextState( 'mamaEffect' );
        }
    }

    function stMamaEffect(){

        $players = $this->getPlayersData();

        //apply mama effects
        foreach ($players as $playerId => $playerData) {
            $mamaCard = $this->cards->getCardsInLocation('mama',$playerId);
            $mamaCard = reset($mamaCard);
            $mama = $this->mamas[$mamaCard['type_arg']];
            if ($mama['green']>0){
                $this->drawFromDeck($playerId, DECK_GREEN, $mama['green'], true);
            };
            if ($mama['yellow']>0){
                $this->drawFromDeck($playerId, DECK_YELLOW, $mama['yellow'], true);
            };
            if ($mama['purple']>0){
                $this->drawFromDeck($playerId, DECK_PURPLE, $mama['purple'], true);
            };
            if ($mama['blue']>0){
                $this->drawFromDeck($playerId, DECK_BLUE, $mama['blue'], true);
            };
            if ($mama['lira']>0){
                $this->dbIncLira($playerId,  $mama['lira']);
            };

            self::setStat($mamaCard['type_arg'], 'vit_mama', $playerId);

        }

        //activate first player
        $firstPlayerId = $this->getFirstPlayer();
        self::giveExtraTime( $firstPlayerId );
        $this->gamestate->changeActivePlayer( $firstPlayerId );
        $this->setGameStateValue( 'active_player', $firstPlayerId );

        $this->gamestate->nextState( 'next' );

    }

    function stPapaOptionChooseNext(){
        $playerId = self::getActivePlayerId();

        $nextPlayerId = $this->getNextPlayer($playerId, false);
        if ($nextPlayerId == null){
            $this->gamestate->nextState( 'end' );
        } else {
            self::giveExtraTime( $nextPlayerId );
            $this->gamestate->changeActivePlayer( $nextPlayerId );
            $this->setGameStateValue( 'active_player', $nextPlayerId );
            $this->gamestate->nextState( 'next' );
        }
    }

    function stStartTurn(){
        $playersNumber = self::getPlayersNumber();

        //increment turn and resetting season
        $turn = self::getGameStateValue('turn');
        $turn++;
        $this->setGameStateValue('turn', $turn);
        $this->setGameStateValue('season', SPRING);
        $this->setGameStateValue('force_next_player_id',0);

        //resetting player
        $this->DbQuery("UPDATE player SET pass=0, wakeup_chart=0, wakeup_order=0, card_played=0, tastingRoomUsed=0, windmillUsed=0");
        $this->DbQuery("DELETE FROM card WHERE card_location='card_flags'");

        //resetting field (2 harvested ->1 not harvested)
        $this->DbQuery("UPDATE player SET field1=1 where field1=2");
        $this->DbQuery("UPDATE player SET field2=1 where field2=2");
        $this->DbQuery("UPDATE player SET field3=1 where field3=2");

        //resetting rooster
        $this->DbQuery("UPDATE card SET card_location='player' where card_type='rooster'");

        //returning player workers to player board
        $this->DbQuery("UPDATE card SET card_location='player' where card_type like 'worker%' and card_location like 'board%' and card_type != 'worker_t'");

        //returning temporary worker to wakeup chart/board
        $this->DbQuery("UPDATE card SET card_location='board', card_location_arg=0 where card_type='worker_t'");

        //change first player
        if ($turn>1){
            // change order in counter-clockwise order
            $sql = "UPDATE player
                    SET playorder = playorder+1";
            self::DbQuery($sql);
            // the player with order = 0 will be last
            $sql = "UPDATE player
                    SET playorder = 1
                    WHERE playorder > $playersNumber";
            self::DbQuery($sql);
        }

        //if solo and if 8^ turn, then I must re-add bonus marker to wakeup slots
        if ($this->checkIfSoloMode()>0 && $turn==8){
            //bonus (wakeup slots)
            for ($wakeup=1; $wakeup <= 7; $wakeup++) { 
                $cards = array();
                $cards[] = array('type' =>'wakeup_bonus', 'type_arg' => $wakeup, 'nbr' => 1);
                $this->cards->createCards( $cards, 'board', 0);
            }
        }

        //activate first player
        $firstPlayerId = $this->getFirstPlayer();
        self::giveExtraTime( $firstPlayerId );
        $this->gamestate->changeActivePlayer( $firstPlayerId );
        $this->setGameStateValue( 'active_player', $firstPlayerId );

        $this->calculateProgression($this->getPlayersData());

        // Notify all players
        self::notifyAllPlayers( "startTurn", clienttranslate( 'Starting year ${year}' ), array(
            'year'=>$turn
        ) );

        $this->gamestate->nextState( 'next' );
    }

    function stSpringChooseWakeupNext(){
        $playerId = self::getActivePlayerId();

        $nextPlayerId = $this->getNextPlayer($playerId, false);
        if ($nextPlayerId == null){

            $this->recalculateWakeupOrder();

            //new Season
            $this->setGameStateValue('season', SUMMER);
            $this->setGameStateValue('force_next_player_id',0);

            $this->calculateProgression($this->getPlayersData());

            $this->gamestate->nextState( 'end' );
        } else {
            self::giveExtraTime( $nextPlayerId );
            $this->gamestate->changeActivePlayer( $nextPlayerId );
            $this->setGameStateValue( 'active_player', $nextPlayerId );
            $this->gamestate->nextState( 'next' );
        }
    }

    function stStartSeasonWorkers(){
        $season = self::getGameStateValue('season');

        // Notify all players
        self::notifyAllPlayers( "startSeason", clienttranslate( 'Starting season ${season}' ), array(
            'season'=>$this->getSeasonText($season),
            'i18n' => array( 'season' )
        ) );

        $this->calculateProgression($this->getPlayersData());
        
        $this->DbQuery("UPDATE player SET pass=0, card_played=0");
        $this->DbQuery("DELETE FROM card WHERE card_location='card_flags'");

        $playersData = $this->getplayersData();
        $playersData = $this->arrayOrderBy($playersData,"wakeup_order", true, true);

        $tokens = $this->readTokens();

        $this->setGameStateValue( 'active_player', 0 );

        //check if board locations contains workers
        //629 Planner
        //Place a worker on an action in a future season. Take that action at the beginning of that season.
        $occupied=false;
        $total=0;
        foreach ($playersData as $playersDataKey => $playersDataValue) {
            foreach ($tokens[$playersDataKey] as $tokensKey => $tokensValue) {
                if ($this->startsWith($tokensValue['t'], 'worker_') && $this->startsWith($tokensValue['l'], 'board_')) {
                    $locationParts = explode('_',$tokensValue['l']);
                    $locationKey = (int)$locationParts[1];
                    if (array_key_exists($locationKey, $this->boardLocations)){
                        $boardLocation = $this->boardLocations[$locationKey];
                        if ($boardLocation['season'] == $season){
                            $this->executePlayerOccupiedLocation($playersDataKey, $locationKey);
                        }
                    }
                }
            }
        }

        //occupy location in solo mode drawing automa card
        if ($this->checkIfSoloMode()){
            $this->automaOccupyLocations($this->getPlayersFullDataWithSolo());
        }

        $this->gamestate->nextState( 'next' );

    }

    function stSeasonWorkersNext(){
        $season = self::getGameStateValue('season');

        $this->calculateProgression($this->getPlayersData());

        //next actions
        $nextAction = $this->readPlayerAction(0, STATUS_NEW);
        while ($nextAction != null){
            $playerId = $nextAction['player_id'];

            $this->changeStatusPlayerAction($nextAction['id'], STATUS_IN_PROGRESS);

            if ($this->activateNextAction($playerId, $nextAction)){
                //changed state to new state and action
                return;
            }

            $nextAction = $this->readPlayerAction(0, STATUS_NEW);
        }

        $playerId = $this->getGameStateValue( 'active_player' );

        //go to next player or go to next season
        $searchNext = true;
        while ($searchNext){

            //check if forced player (for ex. next player before playing organizer)
            $forceNextPlayerId = self::getGameStateValue( 'force_next_player_id');

            if ($forceNextPlayerId > 0){
                $nextPlayerId = $forceNextPlayerId;
                //resetting
                self::setGameStateValue( 'force_next_player_id',0);
            } else if ($playerId == 0){
                $nextPlayerId = $this->getFirstPlayerByWakeupOrder();
            } else {
                $nextPlayerId = $this->getNextActivePlayerByWakeupOrder($playerId, true);
            }

            //check if there are next players
            if ($nextPlayerId){
                //if they have workers
                $availableWorker = $this->readAvailableWorkers($nextPlayerId);
                if (count($availableWorker) == 0){
                    //no workers... pass
                    $this->DbQuery("UPDATE player SET pass=1 WHERE player_id = $nextPlayerId");

                    // Notify all players pass action
                    self::notifyAllPlayers( "pass", clienttranslate( '${player_name} has no more workers so they have to pass' ), array(
                        'player_id' => $nextPlayerId,
                        'player_name' => $this->getPlayerName($nextPlayerId)
                    ) );

                } else {
                    //player with workers
                    $searchNext = false;
                }
            } else {
                // no more players
                $searchNext = false;
            }

        }

        if ($nextPlayerId == null){
            //Summer, go to Fall
            if ($season == SUMMER){
                //new Season
                $this->setGameStateValue('season', FALL);
                $this->setGameStateValue('force_next_player_id',0);
                $this->DbQuery("UPDATE player SET pass=0, card_played=0");
                $this->DbQuery("DELETE FROM card WHERE card_location='card_flags'");

                //activate first player
                $firstPlayerId = $this->getFirstPlayerByWakeupOrder();
                self::giveExtraTime( $firstPlayerId );
                $this->gamestate->changeActivePlayer( $firstPlayerId );
                $this->setGameStateValue( 'active_player', $firstPlayerId );

                $playerData = $this->getPlayerData($firstPlayerId);
                /*if ($playerData['cottage']==1){
                    self::setGameStateValue( 'fall_two_cards', 1 );
                } else {
                    self::setGameStateValue( 'fall_two_cards', 0 );
                }*/

                // Notify all players
                self::notifyAllPlayers( "startSeason", clienttranslate( 'Starting season ${season}' ), array(
                    'season'=>$this->getSeasonText(FALL),
                    'i18n' => array( 'season' )
                    )
                );

                $this->gamestate->nextState( 'chooseFallCard' );
            }

            //Winter, go to end year
            if ($season == WINTER){
                $this->gamestate->nextState( 'end' );
            }

        } else {

            //next player
            //resetting card played and card_flags for all players
            //I resetted only next player before bug #46148: ""BOTTLER" CARD CONTINUES TO GIVE VPS THROUGH OTHER PLAYER TURNS"
            //but it was wrong
            $this->DbQuery("UPDATE player SET card_played=0");
            $this->DbQuery("DELETE FROM card WHERE card_location='card_flags'");

            self::giveExtraTime( $nextPlayerId );
            $this->gamestate->changeActivePlayer( $nextPlayerId );
            $this->setGameStateValue( 'active_player', $nextPlayerId );
            $this->gamestate->nextState( 'next' );
        }
    }

    function stAllActionEnd(){
        $playerId = $this->getGameStateValue( 'active_player' );

        $playerAction = $this->readPlayerAction($playerId, STATUS_IN_PROGRESS);

        $this->removePlayerAction( $playerAction['id']);

        //623	Importer
        //Draw 3 ${token_blueCardPlus} cards unless all opponents combine to give you 3 visitor cards (total).
        if ($playerAction['card_key']==623){
            $offerCards = $this->cards->getCardsInLocation('offerCards');
            $offerCardsKeys = array();
            foreach ($offerCards as $offerCardsKey => $offerCardsValue) {
                $offerCardsKeys[]=$offerCardsValue['type_arg'];
            }
            if (count($offerCardsKeys)==0){
                $offerCardsKeys[]=0;
            }
            $offerCardsKeysJoin = implode(',',$offerCardsKeys);

            $sql = "SELECT card_id id, card_type type, card_type_arg type_arg FROM card WHERE card_location='hand' AND card_type_arg in (${offerCardsKeysJoin})";
            $cards = self::getObjectListFromDB( $sql);

            if (count($offerCards)!=count($cards)){
                throw new BgaUserException( self::_("Wrong cards!") );
            }

            if (count($offerCards)<3){
                // Notify all players
                self::notifyAllPlayers( "playYellowCard", clienttranslate( 'Players offer ${count_card} visitor card(s) so ${player_name} draws 3 ${token_blueCard}' ), array(
                    'player_id' => $playerId,
                    'player_name' => $this->getPlayerName($playerId),
                    'count_card' => count($offerCards),
                    'token_blueCard' => 'blueCard'
                ) );

                $this->DbQuery("DELETE FROM card WHERE card_location='offerCards'");

                $this->drawFromDeck($playerId, DECK_BLUE, 3, false);
            } else if (count($offerCards)>3){
                // Notify all players
                self::notifyAllPlayers( "playYellowCard", clienttranslate( 'Players offer more than 3 visitor cards: ${count_card}' ), array(
                    'player_id' => $playerId,
                    'player_name' => $this->getPlayerName($playerId),
                    'count_card' => count($offerCards),
                    'token_blueCard' => 'blueCard'
                ) );

                $this->DbQuery("DELETE FROM card WHERE card_location='offerCards'");

                //Retry a second time
                $this->insertPlayerAction($playerAction['player_id'], 'allGiveCard', $playerAction['play_order'], $playerAction['args'], $playerAction['card_id'], $playerAction['card_key']);

            } else {
                $this->DbQuery("DELETE FROM card WHERE card_location='offerCards'");
                foreach ($cards as $cardsKey => $cardsValue) {
                    $this->cards->moveCard($cardsValue['id'],'hand',$playerId);
                }
                // Notify all players
                self::notifyAllPlayers( "playYellowCard", clienttranslate(  '${player_name} gets 3 visitor cards from other players' ), array(
                    'player_id' => $playerId,
                    'player_name' => $this->getPlayerName($playerId),
                    'token_blueCard' => 'blueCard'
                ) );
            }
        }


        $this->gamestate->nextState( 'next' );
    }

    function stFallChooseCardNext(){
        $playerId = self::getActivePlayerId();

        $this->calculateProgression($this->getPlayersData());

        //cottage two cards!!!!
        /*if (self::getGameStateValue('fall_two_cards')==1){
            // cottage second card
            self::setGameStateValue('fall_two_cards', 0 );
            $this->gamestate->nextState( 'next' );
            return;
        }*/

        $nextPlayerId = $this->getNextActivePlayerByWakeupOrder($playerId, false);

        if ($nextPlayerId == null){
            //new Season
            $this->setGameStateValue('season', WINTER);
            $this->setGameStateValue('force_next_player_id',0);

            $this->gamestate->nextState( 'end' );
        } else {
            self::giveExtraTime( $nextPlayerId );
            $this->gamestate->changeActivePlayer( $nextPlayerId );
            $this->setGameStateValue( 'active_player', $nextPlayerId );
            $this->gamestate->nextState( 'next' );

            /*$playerData = $this->getPlayerData($nextPlayerId);
            if ($playerData['cottage']==1){
                self::setGameStateValue( 'fall_two_cards', 1 );
            } else {
                self::setGameStateValue( 'fall_two_cards', 0 );
            }*/
        }
    }

    function stEndTurn(){
        $playersData = $this->getPlayersFullData();

        //age grapes and wines
        //collect residual payments
        $this->ageGrapesWinesAndGetResidualPayments();

        $soloMode = $this->checkIfSoloMode();
        if ($soloMode>0){
            $this->checkIfPlayersCauseEndGame($playersData, true);
        }

        $gameEnd = self::getGameStateValue('game_end');

        if ($gameEnd == 1){

            $playersData = $this->getPlayersFullData();

            $turn = self::getGameStateValue('turn');
            self::setStat($turn, 'vit_turns_number');

            foreach ($playersData as $playerId => $playerData) {
                self::setStat($playerData['residual_payment'], 'vit_residual_payment', $playerId);
                self::setStat($playerData['trellis'], 'vit_trellis', $playerId);
                self::setStat($playerData['irrigation'], 'vit_irrigation', $playerId);
                self::setStat($playerData['yoke'], 'vit_yoke', $playerId);
                self::setStat($playerData['tastingRoom'], 'vit_tasting_room', $playerId);
                self::setStat($playerData['cottage'], 'vit_cottage', $playerId);
                self::setStat($playerData['windmill'], 'vit_windmill', $playerId);
                self::setStat($playerData['mediumCellar'], 'vit_medium_cellar', $playerId);
                self::setStat($playerData['largeCellar'], 'vit_large_cellar', $playerId);
            }

            // Notify all players
            self::notifyAllPlayers( "updateAll", '', array(
                'players' => $this->getPlayersFullDataWithSolo(),
                'tokens' => $this->readTokens(),
                'privateData' =>  $this->readPlayersPrivateHand($playersData),
                'cdc' => $this->readCountDeckCards(),
                'tdd' => $this->readTopDiscardDeck(),
                'pceg' => $this->checkIfPlayersCauseEndGame($playersData, false),
                'actionProgress' => $this->readPlayerActionInProgress()
            ) );

            $this->setGameStateValue('progression', 100);
            $this->processTieBreakerScore($playersData);

            //solo win/lose
            $soloMode = $this->checkIfSoloMode();
            $result["soloMode"] = $soloMode;
            if ($soloMode>0){
                $automaScore = $this->getAutomaScore();
                $playerId = $this->array_key_first($playersData);
                $playerData = $playersData[$playerId];
                //UPDATE stat vit_solo_win
                if ($playerData['score']>$automaScore){
                    self::setStat(1, 'vit_solo_win', $playerId);
                    // Notify all players
                    self::notifyAllPlayers( "soloEnd", '', array(
                        'automaScore' => $automaScore,
                        'score' => $playerData['score'],
                        'win' => 1
                    ) );
                } else {
                    self::setStat(0, 'vit_solo_win', $playerId);
                    // Notify all players
                    self::notifyAllPlayers( "soloEnd", '', array(
                        'automaScore' => $automaScore,
                        'score' => $playerData['score'],
                        'win' => 0
                    ) );
                }
            }

            $this->gamestate->nextState( 'end' );
            return;
        }

        $this->calculateProgression($this->getPlayersData());

        $discard=false;
        $playersToDiscard = array();
        foreach ($playersData as $playerId => $playerData) {
            if ($playerData['handSize']>7){
               $discard=true;
               $playersToDiscard[] = $playerId;
            }
        }
        if ($discard){
            $this->gamestate->setPlayersMultiactive( $playersToDiscard, 'end', true );
            $this->gamestate->nextState( 'discard' );
            return;
        }

        $this->gamestate->nextState( 'next' );
    }

//////////////////////////////////////////////////////////////////////////////
//////////// Zombie
////////////

    /*
        zombieTurn:

        This method is called each time it is the turn of a player who has quit the game (= "zombie" player).
        You can do whatever you want in order to make sure the turn of this player ends appropriately
        (ex: pass).
    */

    function zombieTurn( $state, $active_player )
    {
        self::warn('zombieTurn---');
        //self::dump('state',$state);
        self::dump('active_player',$active_player);
    	$statename = $state['name'];
        self::warn($statename);
        $playerData = $this->getPlayerData($active_player);

        if ($state['type'] === "activeplayer") {
            switch ($statename) {

                case 'papaOptionChoose': //STATE_PAPA_OPTION_CHOOSE
                    //choose papa option
                    $this->chooseRandomPapaOption($active_player);
                    break;

                case 'springChooseWakeup': //STATE_SPRING_CHOOSE_WAKEUP
                    //choose random wakeup
                    $this->chooseRandomWakeup($active_player);
                    break;

                case 'seasonWorkers': //STATE_SEASON_WORKERS
                    //choose lira1 space
                    $worker_g=false;

                    $availableWorkers = $this->readAvailableWorkers($active_player);
                    if (count($availableWorkers)==1 && $availableWorkers[0]['type']=='worker_g'){
                        $worker_g=true;
                    }

                    $this->placeWorker( $active_player, 801, $worker_g, '', 0, 0,
                            0, array(), '', 0, array(), 0, 0, array(),
                            array(), array(), array(), array(), '',
                            0, 0, 0, false);
                    break;

                case 'fallChooseCard': //STATE_FALL_CHOOSE_CARD
                    //choose random wakeup
                    $twoCards=0;
                    if ($playerData['cottage']==1){
                        $twoCards=1;
                    }
                    $this->chooseRandomFallCard($active_player, $twoCards);
                    break;

                case 'plant': //STATE_PLANT
                    $this->refuse($active_player, 'plant', false);
                    break;

                case 'makeWine': //STATE_MAKE_WINE
                    $this->refuse($active_player, 'makeWine', false);
                    break;

                case 'playYellowCard': //STATE_PLAY_YELLOW_CARD
                    $this->refuse($active_player, 'playYellowCard', false);
                    break;

                case 'playBlueCard': //STATE_PLAY_BLUE_CARD
                    $this->refuse($active_player, 'playBlueCard', false);
                    break;

                case 'playCardSecondOption': //STATE_PLAY_CARD_SECOND_OPTION
                    $this->refuse($active_player, 'playCardSecondOption', false);
                    break;

                case 'chooseVisitorCardDraw': //STATE_CHOOSE_VISITOR_CARD_DRAW
                    //choose random visitor card
                    $this->chooseRandomVisitorCard($active_player);
                    break;

                case 'chooseCards': //STATE_CHOOSE_CARDS
                    //choose random cards
                    $this->chooseRandomChooseCards($active_player);
                    break;

                case 'chooseOptions': //STATE_CHOOSE_OPTIONS
                    //choose random choice
                    $this->chooseRandomChooseOption($active_player);
                    break;
                
                case 'executeLocation': //STATE_EXECUTE_LOCATION
                    //refuse action
                    $this->refuse($active_player, 'executeLocation', false);
                    break;

                case 'takeActionPrev': //STATE_TAKE_ACTION_PREV
                    //refuse action
                    $this->refuse($active_player, 'takeActionPrev', false);
                    break;

                case 'discardVines': //STATE_DISCARD_VINES
                    //choose random vines
                    $this->discardVinesRandom($active_player);
                    break;

                default:
                	break;
            }
            $this->gamestate->nextState( "zombiePass" );

            return;
        }

        if ($state['type'] === "multipleactiveplayer") {
            switch ($statename) {
                case 'mamaPapaChoose': //STATE_MAMA_PAPA_CHOOSE
                    //choose one mama and papa
                    $this->chooseRandomMamaPapa($active_player);
                    break;

                case 'discardCards': //STATE_DISCARD_CARDS
                    $this->chooseRandomDiscardCards($active_player);
                    break;

                case 'allBuild': //STATE_ALL_BUILD
                    $this->refuse($active_player, 'allBuild', false);
                    break;

                case 'allPlant': //STATE_ALL_PLANT
                    $this->refuse($active_player, 'allPlant', false);
                    break;

                case 'allGiveCard': //STATE_ALL_GIVE_CARD
                    $this->chooseRandomGiveCards($active_player);
                    break;

                case 'allChoose': //STATE_ALL_CHOOSE
                    $this->refuse($active_player, 'allChoose', false);
                    break;

                default:
                    break;
            }

            // Make sure player is in a non blocking status for role turn
            $this->gamestate->setPlayerNonMultiactive( $active_player, 'next' );

            return;
        }

        throw new feException( "Zombie mode not supported at this game state: ".$statename );
    }

///////////////////////////////////////////////////////////////////////////////////:
////////// DB upgrade
//////////

    /*
        upgradeTableDb:

        You don't have to care about this until your game has been published on BGA.
        Once your game is on BGA, this method is called everytime the system detects a game running with your old
        Database scheme.
        In this case, if you change your Database scheme, you just have to apply the needed changes in order to
        update the game database and allow the game to continue to run with your new version.

    */

    function upgradeTableDb( $from_version )
    {
        // $from_version is the current version of this game database, in numerical form.
        // For example, if the game was running with a release of your game named "140430-1345",
        // $from_version is equal to 1404301345

        // Example:
//        if( $from_version <= 1404301345 )
//        {
//            // ! important ! Use DBPREFIX_<table_name> for all tables
//
//            $sql = "ALTER TABLE DBPREFIX_xxxxxxx ....";
//            self::applyDbUpgradeToAllDB( $sql );
//        }
//        if( $from_version <= 1405061421 )
//        {
//            // ! important ! Use DBPREFIX_<table_name> for all tables
//
//            $sql = "CREATE TABLE DBPREFIX_xxxxxxx ....";
//            self::applyDbUpgradeToAllDB( $sql );
//        }
//        // Please add your future database scheme changes here
//
//
        if( $from_version <= 2104091525 )
        {
            // You DB schema update request.
            // Note: all tables names should be prefixed by "DBPREFIX_" to be compatible with the applyDbUpgradeToAllDB method you should use below
            $sql = "UPDATE DBPREFIX_card SET card_location='discardYellow' WHERE card_location='hand' and card_type_arg in (select card_key from DBPREFIX_player_action WHERE action='discardVines' and status=1)";
            self::applyDbUpgradeToAllDB( $sql );

            $sql = "DELETE FROM DBPREFIX_player_action WHERE action='discardVines' and status=1";
            self::applyDbUpgradeToAllDB( $sql );

        }
        if( $from_version <= 2104301700 )
        {
            // You DB schema update request.
            // Note: all tables names should be prefixed by "DBPREFIX_" to be compatible with the applyDbUpgradeToAllDB method you should use below

            //max grape value is 9
            $sql = "UPDATE DBPREFIX_card set card_type_arg = 9 where card_type like 'grape%' and card_type_arg > 9 and card_location like 'player%'";
            self::applyDbUpgradeToAllDB( $sql );

        }
        if( $from_version <= 2105281236 )
        {
            // You DB schema update request.
            // Note: all tables names should be prefixed by "DBPREFIX_" to be compatible with the applyDbUpgradeToAllDB method you should use below

            //adding bonuses to player
            $sql = "ALTER TABLE DBPREFIX_player ADD `bonuses` INT UNSIGNED NOT NULL DEFAULT 0";
            self::applyDbUpgradeToAllDB( $sql );

        }
        if( $from_version <= 2107021024 )
        {
            //removing wrong cards from history
            $sql = "DELETE FROM DBPREFIX_card WHERE card_location like 'history%' and card_type_arg=0";
            self::applyDbUpgradeToAllDB( $sql );

        }
        if( $from_version <= 2107061616 )
        {
            //removing wrong cards from history
            $sql = "UPDATE DBPREFIX_card SET card_location = 'playerOff' WHERE card_location = 'player_off' and card_type = 'windmill'";
            self::applyDbUpgradeToAllDB( $sql );

        }
    }
}
