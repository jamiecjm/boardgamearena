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
 * viticulture.action.php
 *
 * viticulture main action entry point
 *
 *
 * In this file, you are describing all the methods that can be called from your
 * user interface logic (javascript).
 *
 * If you define a method "myAction" here, then you can call it from your javascript code with:
 * this.ajaxcall( "/viticulture/viticulture/myAction.html", ...)
 *
 */


  class action_viticulture extends APP_GameAction
  {
    // Constructor: please do not modify
   	public function __default()
  	{
  	    if( self::isArg( 'notifwindow') )
  	    {
            $this->view = "common_notifwindow";
  	        $this->viewArgs['table'] = self::getArg( "table", AT_posint, true );
  	    }
  	    else
  	    {
            $this->view = "viticulture_viticulture";
            self::trace( "Complete reinitialization of board game" );
      }
  	}


    /*

    Example:

    public function myAction()
    {
        self::setAjaxMode();

        // Retrieve arguments
        // Note: these arguments correspond to what has been sent through the javascript "ajaxcall" method
        $arg1 = self::getArg( "myArgument1", AT_posint, true );
        $arg2 = self::getArg( "myArgument2", AT_posint, true );

        // Then, call the appropriate method in your game logic, like "playCard" or "myAction"
        $this->game->myAction( $arg1, $arg2 );

        self::ajaxResponse( );
    }

    */

    public function chooseMamaPapa()
    {
        self::setAjaxMode();

        // Retrieve arguments
        $mama = self::getArg( "mama", AT_posint, true );
        $papa = self::getArg( "papa", AT_posint, true );

        //call game action
        $this->game->chooseMamaPapa( 0, $mama, $papa, true);

        self::ajaxResponse( );
    }

    public function choosePapaOption()
    {
        self::setAjaxMode();

        // Retrieve arguments
        $option = self::getArg( "option", AT_alphanum, true );

        //call game action
        $this->game->choosePapaOption( 0, $option, true);

        self::ajaxResponse( );
    }
    
    public function chooseWakeup()
    {
        self::setAjaxMode();

        // Retrieve arguments
        $value = self::getArg( "value", AT_posint, true );
        $card = self::getArg( "card", AT_alphanum, false ,'' );
        
        //call game action
        $this->game->chooseWakeup( 0, $value, $card, true, true);

        self::ajaxResponse( );
    }
    
    public function placeWorker()
    {
        self::setAjaxMode();

        // Retrieve arguments
        $location = self::getArg( "location", AT_posint, true );
        $worker_g = self::getArg( "worker_g", AT_posint, true );
        $structure = self::getArg( "structure", AT_alphanum, false, '' );
        $cardId = self::getArg( "cardId", AT_posint, false, 0 );
        $cardKey = self::getArg( "cardKey", AT_posint, false, 0 );
        $field = self::getArg( "field", AT_posint, false, 0 );
        $harvestFieldsId_raw = self::getArg( "harvestFieldsId", AT_numberlist, false,'' );
        if( $harvestFieldsId_raw == '' ){
            $harvestFieldsId = array();
        } else {
            $harvestFieldsId = explode( ',', $harvestFieldsId_raw );
        }

        $wine = self::getArg( "wine", AT_alphanum, false, '' );
        $wineValue = self::getArg( "wineValue", AT_posint, false, 0 );
        $grapesId_raw = self::getArg( "grapesId", AT_numberlist, false,'' );
        if( $grapesId_raw == '' ){
            $grapesId = array();
        } else {
            $grapesId = explode( ',', $grapesId_raw );
        }

        $buyField = self::getArg( "buyField", AT_posint, false, 0 );
        $sellField = self::getArg( "sellField", AT_posint, false, 0 );
        $sellGrapesId_raw = self::getArg( "sellGrapesId", AT_numberlist, false,'' );
        if( $sellGrapesId_raw == '' ){
            $sellGrapesId = array();
        } else {
            $sellGrapesId = explode( ',', $sellGrapesId_raw );
        }

        $uprootVinesId_raw = self::getArg( "uprootVinesId", AT_numberlist, false,'' );
        if( $uprootVinesId_raw == '' ){
            $uprootVinesId = array();
        } else {
            $uprootVinesId = explode( ',', $uprootVinesId_raw );
        }

        $orderWinesId_raw = self::getArg( "orderWinesId", AT_numberlist, false,'' );
        if( $orderWinesId_raw == '' ){
            $orderWinesId = array();
        } else {
            $orderWinesId = explode( ',', $orderWinesId_raw );
        }

        $cardsSelectedId_raw = self::getArg( "cardsSelectedId", AT_numberlist, false,'' );
        if( $cardsSelectedId_raw == '' ){
            $cardsSelectedId = array();
        } else {
            $cardsSelectedId = explode( ',', $cardsSelectedId_raw );
        }

        $workersSelectedId_raw = self::getArg( "workersSelectedId", AT_numberlist, false,'' );
        if( $workersSelectedId_raw == '' ){
            $workersSelectedId = array();
        } else {
            $workersSelectedId = explode( ',', $workersSelectedId_raw );
        }

        $otherSelection = self::getArg( "otherSelection", AT_alphanum, false, '' );
        
        $visitorCardId = self::getArg( "visitorCardId", AT_posint, false, 0 );
        $visitorCardKey = self::getArg( "visitorCardKey", AT_posint, false, 0 );
        $visitorCardOption = self::getArg( "visitorCardOption", AT_posint, false, 0 );

        //call game action
        $this->game->placeWorker( 0, $location, $worker_g, $structure, $cardId, $cardKey,
            $field, $harvestFieldsId, $wine, $wineValue, $grapesId, $buyField, $sellField, $sellGrapesId, 
            $uprootVinesId, $orderWinesId, $cardsSelectedId, $workersSelectedId, $otherSelection,
            $visitorCardId, $visitorCardKey, $visitorCardOption, true);
        
        self::ajaxResponse( );
    }

    public function playYellowCard()
    {
        self::setAjaxMode();

        // Retrieve arguments
        $structure = self::getArg( "structure", AT_alphanum, false, '' );
        $cardId = self::getArg( "cardId", AT_posint, false, 0 );
        $cardKey = self::getArg( "cardKey", AT_posint, false, 0 );
        $field = self::getArg( "field", AT_posint, false, 0 );
        $harvestFieldsId_raw = self::getArg( "harvestFieldsId", AT_numberlist, false,'' );
        if( $harvestFieldsId_raw == '' ){
            $harvestFieldsId = array();
        } else {
            $harvestFieldsId = explode( ',', $harvestFieldsId_raw );
        }

        $wine = self::getArg( "wine", AT_alphanum, false, '' );
        $wineValue = self::getArg( "wineValue", AT_posint, false, 0 );
        $grapesId_raw = self::getArg( "grapesId", AT_numberlist, false,'' );
        if( $grapesId_raw == '' ){
            $grapesId = array();
        } else {
            $grapesId = explode( ',', $grapesId_raw );
        }

        $buyField = self::getArg( "buyField", AT_posint, false, 0 );
        $sellField = self::getArg( "sellField", AT_posint, false, 0 );
        $sellGrapesId_raw = self::getArg( "sellGrapesId", AT_numberlist, false,'' );
        if( $sellGrapesId_raw == '' ){
            $sellGrapesId = array();
        } else {
            $sellGrapesId = explode( ',', $sellGrapesId_raw );
        }

        $uprootVinesId_raw = self::getArg( "uprootVinesId", AT_numberlist, false,'' );
        if( $uprootVinesId_raw == '' ){
            $uprootVinesId = array();
        } else {
            $uprootVinesId = explode( ',', $uprootVinesId_raw );
        }

        $orderWinesId_raw = self::getArg( "orderWinesId", AT_numberlist, false,'' );
        if( $orderWinesId_raw == '' ){
            $orderWinesId = array();
        } else {
            $orderWinesId = explode( ',', $orderWinesId_raw );
        }

        $cardsSelectedId_raw = self::getArg( "cardsSelectedId", AT_numberlist, false,'' );
        if( $cardsSelectedId_raw == '' ){
            $cardsSelectedId = array();
        } else {
            $cardsSelectedId = explode( ',', $cardsSelectedId_raw );
        }

        $workersSelectedId_raw = self::getArg( "workersSelectedId", AT_numberlist, false,'' );
        if( $workersSelectedId_raw == '' ){
            $workersSelectedId = array();
        } else {
            $workersSelectedId = explode( ',', $workersSelectedId_raw );
        }

        $otherSelection = self::getArg( "otherSelection", AT_alphanum, false, '' );

        $visitorCardId = self::getArg( "visitorCardId", AT_posint, true );
        $visitorCardKey = self::getArg( "visitorCardKey", AT_posint, true);
        $visitorCardOption = self::getArg( "visitorCardOption", AT_posint, true );

        //call game action
        $this->game->playYellowCard( 0, $structure, $cardId, $cardKey,
            $field, $harvestFieldsId, $wine, $wineValue, $grapesId, $buyField, $sellField, $sellGrapesId, 
            $uprootVinesId, $orderWinesId, $cardsSelectedId, $workersSelectedId, $otherSelection,
            $visitorCardId, $visitorCardKey, $visitorCardOption, true);
        
        self::ajaxResponse( );
    }

    public function playBlueCard()
    {
        self::setAjaxMode();

        // Retrieve arguments
        $structure = self::getArg( "structure", AT_alphanum, false, '' );
        $cardId = self::getArg( "cardId", AT_posint, false, 0 );
        $cardKey = self::getArg( "cardKey", AT_posint, false, 0 );
        $field = self::getArg( "field", AT_posint, false, 0 );
        $harvestFieldsId_raw = self::getArg( "harvestFieldsId", AT_numberlist, false,'' );
        if( $harvestFieldsId_raw == '' ){
            $harvestFieldsId = array();
        } else {
            $harvestFieldsId = explode( ',', $harvestFieldsId_raw );
        }

        $wine = self::getArg( "wine", AT_alphanum, false, '' );
        $wineValue = self::getArg( "wineValue", AT_posint, false, 0 );
        $grapesId_raw = self::getArg( "grapesId", AT_numberlist, false,'' );
        if( $grapesId_raw == '' ){
            $grapesId = array();
        } else {
            $grapesId = explode( ',', $grapesId_raw );
        }

        $buyField = self::getArg( "buyField", AT_posint, false, 0 );
        $sellField = self::getArg( "sellField", AT_posint, false, 0 );
        $sellGrapesId_raw = self::getArg( "sellGrapesId", AT_numberlist, false,'' );
        if( $sellGrapesId_raw == '' ){
            $sellGrapesId = array();
        } else {
            $sellGrapesId = explode( ',', $sellGrapesId_raw );
        }

        $uprootVinesId_raw = self::getArg( "uprootVinesId", AT_numberlist, false,'' );
        if( $uprootVinesId_raw == '' ){
            $uprootVinesId = array();
        } else {
            $uprootVinesId = explode( ',', $uprootVinesId_raw );
        }

        $orderWinesId_raw = self::getArg( "orderWinesId", AT_numberlist, false,'' );
        if( $orderWinesId_raw == '' ){
            $orderWinesId = array();
        } else {
            $orderWinesId = explode( ',', $orderWinesId_raw );
        }

        $cardsSelectedId_raw = self::getArg( "cardsSelectedId", AT_numberlist, false,'' );
        if( $cardsSelectedId_raw == '' ){
            $cardsSelectedId = array();
        } else {
            $cardsSelectedId = explode( ',', $cardsSelectedId_raw );
        }

        $workersSelectedId_raw = self::getArg( "workersSelectedId", AT_numberlist, false,'' );
        if( $workersSelectedId_raw == '' ){
            $workersSelectedId = array();
        } else {
            $workersSelectedId = explode( ',', $workersSelectedId_raw );
        }

        $otherSelection = self::getArg( "otherSelection", AT_alphanum, false, '' );

        $visitorCardId = self::getArg( "visitorCardId", AT_posint, true );
        $visitorCardKey = self::getArg( "visitorCardKey", AT_posint, true );
        $visitorCardOption = self::getArg( "visitorCardOption", AT_posint, false, 0 );

        //call game action
        $this->game->playBlueCard( 0, $structure, $cardId, $cardKey,
            $field, $harvestFieldsId, $wine, $wineValue, $grapesId, $buyField, $sellField, $sellGrapesId, 
            $uprootVinesId, $orderWinesId, $cardsSelectedId, $workersSelectedId, $otherSelection,
            $visitorCardId, $visitorCardKey, $visitorCardOption, true);
        
        self::ajaxResponse( );
    }
       
    public function playCardSecondOption()
    {
        self::setAjaxMode();

        // Retrieve arguments
        $structure = self::getArg( "structure", AT_alphanum, false, '' );
        $cardId = self::getArg( "cardId", AT_posint, false, 0 );
        $cardKey = self::getArg( "cardKey", AT_posint, false, 0 );
        $field = self::getArg( "field", AT_posint, false, 0 );
        $harvestFieldsId_raw = self::getArg( "harvestFieldsId", AT_numberlist, false,'' );
        if( $harvestFieldsId_raw == '' ){
            $harvestFieldsId = array();
        } else {
            $harvestFieldsId = explode( ',', $harvestFieldsId_raw );
        }

        $wine = self::getArg( "wine", AT_alphanum, false, '' );
        $wineValue = self::getArg( "wineValue", AT_posint, false, 0 );
        $grapesId_raw = self::getArg( "grapesId", AT_numberlist, false,'' );
        if( $grapesId_raw == '' ){
            $grapesId = array();
        } else {
            $grapesId = explode( ',', $grapesId_raw );
        }

        $buyField = self::getArg( "buyField", AT_posint, false, 0 );
        $sellField = self::getArg( "sellField", AT_posint, false, 0 );
        $sellGrapesId_raw = self::getArg( "sellGrapesId", AT_numberlist, false,'' );
        if( $sellGrapesId_raw == '' ){
            $sellGrapesId = array();
        } else {
            $sellGrapesId = explode( ',', $sellGrapesId_raw );
        }

        $uprootVinesId_raw = self::getArg( "uprootVinesId", AT_numberlist, false,'' );
        if( $uprootVinesId_raw == '' ){
            $uprootVinesId = array();
        } else {
            $uprootVinesId = explode( ',', $uprootVinesId_raw );
        }

        $orderWinesId_raw = self::getArg( "orderWinesId", AT_numberlist, false,'' );
        if( $orderWinesId_raw == '' ){
            $orderWinesId = array();
        } else {
            $orderWinesId = explode( ',', $orderWinesId_raw );
        }

        $cardsSelectedId_raw = self::getArg( "cardsSelectedId", AT_numberlist, false,'' );
        if( $cardsSelectedId_raw == '' ){
            $cardsSelectedId = array();
        } else {
            $cardsSelectedId = explode( ',', $cardsSelectedId_raw );
        }

        $workersSelectedId_raw = self::getArg( "workersSelectedId", AT_numberlist, false,'' );
        if( $workersSelectedId_raw == '' ){
            $workersSelectedId = array();
        } else {
            $workersSelectedId = explode( ',', $workersSelectedId_raw );
        }

        $otherSelection = self::getArg( "otherSelection", AT_alphanum, false, '' );

        $visitorCardId = self::getArg( "visitorCardId", AT_posint, true);
        $visitorCardKey = self::getArg( "visitorCardKey", AT_posint, true);
        $visitorCardOption = self::getArg( "visitorCardOption", AT_posint, true );

        //call game action
        $this->game->playCardSecondOption( 0, $structure, $cardId, $cardKey,
            $field, $harvestFieldsId, $wine, $wineValue, $grapesId, $buyField, $sellField, $sellGrapesId, 
            $uprootVinesId, $orderWinesId, $cardsSelectedId, $workersSelectedId, $otherSelection,
            $visitorCardId, $visitorCardKey, $visitorCardOption, true);
        
        self::ajaxResponse( );
    }
       
    public function plant()
    {
        self::setAjaxMode();

        // Retrieve arguments
        $cardId = self::getArg( "cardId", AT_posint, true);
        $cardKey = self::getArg( "cardKey", AT_posint, true );
        $field = self::getArg( "field", AT_posint, true );

        //call game action
        $this->game->plant( 0, $cardId, $cardKey, $field, true);
        
        self::ajaxResponse( );
    }

    public function chooseVisitorCardDraw()
    {
        self::setAjaxMode();

        // Retrieve arguments
        $card = self::getArg( "card", AT_alphanum, true );
        
        //call game action
        $this->game->chooseVisitorCardDraw( 0, $card, true);

        self::ajaxResponse( );
    }
       
    public function chooseCards()
    {
        self::setAjaxMode();

        // Retrieve arguments
        $cardsId_raw = self::getArg( "cardsSelectedId", AT_numberlist, true);
        if( $cardsId_raw == '' ){
            $cardsId = array();
        } else {
            $cardsId = explode( ',', $cardsId_raw );
        }

        //call game action
        $this->game->chooseCards( 0, $cardsId, true);
        
        self::ajaxResponse( );
    }

    public function chooseOptions()
    {
        self::setAjaxMode();

        // Retrieve arguments
        $choice = self::getArg( "choice", AT_posint, true );
        $cardsId_raw = self::getArg( "cardsSelectedId", AT_numberlist, false);
        if( $cardsId_raw == '' ){
            $cardsId = array();
        } else {
            $cardsId = explode( ',', $cardsId_raw );
        }

        //call game action
        $this->game->chooseOptions( 0, $choice, $cardsId, true);
        
        self::ajaxResponse( );
    }

    public function executeLocation()
    {
      
        // Retrieve arguments
        $location = self::getArg( "location", AT_posint, true );
        $worker_g = self::getArg( "worker_g", AT_posint, false, 0 );
        $structure = self::getArg( "structure", AT_alphanum, false, '' );
        $cardId = self::getArg( "cardId", AT_posint, false, 0 );
        $cardKey = self::getArg( "cardKey", AT_posint, false, 0 );
        $field = self::getArg( "field", AT_posint, false, 0 );
        $harvestFieldsId_raw = self::getArg( "harvestFieldsId", AT_numberlist, false,'' );
        if( $harvestFieldsId_raw == '' ){
            $harvestFieldsId = array();
        } else {
            $harvestFieldsId = explode( ',', $harvestFieldsId_raw );
        }

        $wine = self::getArg( "wine", AT_alphanum, false, '' );
        $wineValue = self::getArg( "wineValue", AT_posint, false, 0 );
        $grapesId_raw = self::getArg( "grapesId", AT_numberlist, false,'' );
        if( $grapesId_raw == '' ){
            $grapesId = array();
        } else {
            $grapesId = explode( ',', $grapesId_raw );
        }

        $buyField = self::getArg( "buyField", AT_posint, false, 0 );
        $sellField = self::getArg( "sellField", AT_posint, false, 0 );
        $sellGrapesId_raw = self::getArg( "sellGrapesId", AT_numberlist, false,'' );
        if( $sellGrapesId_raw == '' ){
            $sellGrapesId = array();
        } else {
            $sellGrapesId = explode( ',', $sellGrapesId_raw );
        }

        $uprootVinesId_raw = self::getArg( "uprootVinesId", AT_numberlist, false,'' );
        if( $uprootVinesId_raw == '' ){
            $uprootVinesId = array();
        } else {
            $uprootVinesId = explode( ',', $uprootVinesId_raw );
        }

        $orderWinesId_raw = self::getArg( "orderWinesId", AT_numberlist, false,'' );
        if( $orderWinesId_raw == '' ){
            $orderWinesId = array();
        } else {
            $orderWinesId = explode( ',', $orderWinesId_raw );
        }

        $cardsSelectedId_raw = self::getArg( "cardsSelectedId", AT_numberlist, false,'' );
        if( $cardsSelectedId_raw == '' ){
            $cardsSelectedId = array();
        } else {
            $cardsSelectedId = explode( ',', $cardsSelectedId_raw );
        }

        $workersSelectedId_raw = self::getArg( "workersSelectedId", AT_numberlist, false,'' );
        if( $workersSelectedId_raw == '' ){
            $workersSelectedId = array();
        } else {
            $workersSelectedId = explode( ',', $workersSelectedId_raw );
        }

        $otherSelection = self::getArg( "otherSelection", AT_alphanum, false, '' );
        
        $visitorCardId = self::getArg( "visitorCardId", AT_posint, false, 0 );
        $visitorCardKey = self::getArg( "visitorCardKey", AT_posint, false, 0 );
        $visitorCardOption = self::getArg( "visitorCardOption", AT_posint, false, 0 );

        //call game action
        $this->game->executeLocation( 0, $location, $worker_g, $structure, $cardId, $cardKey,
            $field, $harvestFieldsId, $wine, $wineValue, $grapesId, $buyField, $sellField, $sellGrapesId, 
            $uprootVinesId, $orderWinesId, $cardsSelectedId, $workersSelectedId, $otherSelection,
            $visitorCardId, $visitorCardKey, $visitorCardOption, true);
        
        self::ajaxResponse( );
    }

    public function takeActionPrev()
    {
      
        // Retrieve arguments
        $location = self::getArg( "location", AT_posint, true );
        $structure = self::getArg( "structure", AT_alphanum, false, '' );
        $cardId = self::getArg( "cardId", AT_posint, false, 0 );
        $cardKey = self::getArg( "cardKey", AT_posint, false, 0 );
        $field = self::getArg( "field", AT_posint, false, 0 );
        $harvestFieldsId_raw = self::getArg( "harvestFieldsId", AT_numberlist, false,'' );
        if( $harvestFieldsId_raw == '' ){
            $harvestFieldsId = array();
        } else {
            $harvestFieldsId = explode( ',', $harvestFieldsId_raw );
        }

        $wine = self::getArg( "wine", AT_alphanum, false, '' );
        $wineValue = self::getArg( "wineValue", AT_posint, false, 0 );
        $grapesId_raw = self::getArg( "grapesId", AT_numberlist, false,'' );
        if( $grapesId_raw == '' ){
            $grapesId = array();
        } else {
            $grapesId = explode( ',', $grapesId_raw );
        }

        $buyField = self::getArg( "buyField", AT_posint, false, 0 );
        $sellField = self::getArg( "sellField", AT_posint, false, 0 );
        $sellGrapesId_raw = self::getArg( "sellGrapesId", AT_numberlist, false,'' );
        if( $sellGrapesId_raw == '' ){
            $sellGrapesId = array();
        } else {
            $sellGrapesId = explode( ',', $sellGrapesId_raw );
        }

        $uprootVinesId_raw = self::getArg( "uprootVinesId", AT_numberlist, false,'' );
        if( $uprootVinesId_raw == '' ){
            $uprootVinesId = array();
        } else {
            $uprootVinesId = explode( ',', $uprootVinesId_raw );
        }

        $orderWinesId_raw = self::getArg( "orderWinesId", AT_numberlist, false,'' );
        if( $orderWinesId_raw == '' ){
            $orderWinesId = array();
        } else {
            $orderWinesId = explode( ',', $orderWinesId_raw );
        }

        $cardsSelectedId_raw = self::getArg( "cardsSelectedId", AT_numberlist, false,'' );
        if( $cardsSelectedId_raw == '' ){
            $cardsSelectedId = array();
        } else {
            $cardsSelectedId = explode( ',', $cardsSelectedId_raw );
        }

        $workersSelectedId_raw = self::getArg( "workersSelectedId", AT_numberlist, false,'' );
        if( $workersSelectedId_raw == '' ){
            $workersSelectedId = array();
        } else {
            $workersSelectedId = explode( ',', $workersSelectedId_raw );
        }

        $otherSelection = self::getArg( "otherSelection", AT_alphanum, false, '' );
        
        $visitorCardId = self::getArg( "visitorCardId", AT_posint, false, 0 );
        $visitorCardKey = self::getArg( "visitorCardKey", AT_posint, false, 0 );
        $visitorCardOption = self::getArg( "visitorCardOption", AT_posint, false, 0 );

        //call game action
        $this->game->takeActionPrev( 0, $location, $structure, $cardId, $cardKey,
            $field, $harvestFieldsId, $wine, $wineValue, $grapesId, $buyField, $sellField, $sellGrapesId, 
            $uprootVinesId, $orderWinesId, $cardsSelectedId, $workersSelectedId, $otherSelection,
            $visitorCardId, $visitorCardKey, $visitorCardOption, true);
        
        self::ajaxResponse( );
    }
       
    public function allPlant()
    {
        self::setAjaxMode();

        // Retrieve arguments
        $cardId = self::getArg( "cardId", AT_posint, true);
        $cardKey = self::getArg( "cardKey", AT_posint, true );
        $field = self::getArg( "field", AT_posint, true );

        //call game action
        $this->game->allPlant( 0, $cardId, $cardKey, $field, true);
        
        self::ajaxResponse( );
    }

    public function allGiveCard()
    {
        self::setAjaxMode();

        // Retrieve arguments
        $cardsId_raw = self::getArg( "cardsSelectedId", AT_numberlist, true);
        if( $cardsId_raw == '' ){
            $cardsId = array();
        } else {
            $cardsId = explode( ',', $cardsId_raw );
        }

        //call game action
        $this->game->allGiveCard( 0, $cardsId, true);
        
        self::ajaxResponse( );
    }

    public function allBuild()
    {
        self::setAjaxMode();

        // Retrieve arguments
        $structure = self::getArg( "structure", AT_alphanum, true );

        //call game action
        $this->game->allBuild( 0, $structure, true);
        
        self::ajaxResponse( );
    }

    public function allChoose()
    {
        self::setAjaxMode();

        // Retrieve arguments
        $choice = self::getArg( "choice", AT_posint, true );

        //call game action
        $this->game->allChoose( 0, $choice, true);
        
        self::ajaxResponse( );
    }
           
    public function makeWine()
    {
        self::setAjaxMode();

        // Retrieve arguments
        $wine = self::getArg( "wine", AT_alphanum, true);
        $wineValue = self::getArg( "wineValue", AT_posint, true);
        $grapesId_raw = self::getArg( "grapesId", AT_numberlist, true );
        if( $grapesId_raw == '' ){
            $grapesId = array();
        } else {
            $grapesId = explode( ',', $grapesId_raw );
        }

        //call game action
        $this->game->makeWine( 0, $wine, $wineValue, $grapesId, true);
        
        self::ajaxResponse( );
    }

    public function fillOrder()
    {
        self::setAjaxMode();

        // Retrieve arguments
        $cardId = self::getArg( "cardId", AT_posint, true);
        $cardKey = self::getArg( "cardKey", AT_posint, true );

        $orderWinesId_raw = self::getArg( "orderWinesId", AT_numberlist, true );
        if( $orderWinesId_raw == '' ){
            $orderWinesId = array();
        } else {
            $orderWinesId = explode( ',', $orderWinesId_raw );
        }

        //call game action
        $this->game->fillOrder( 0, $cardId, $cardKey,
            $orderWinesId, true);
        
        self::ajaxResponse( );
    }

    public function pass()
    {
        self::setAjaxMode();

        $this->game->pass( 0, true);

        self::ajaxResponse( );
    }

    public function refuse()
    {
        self::setAjaxMode();

        $actionRefuse = self::getArg( "actionRefuse", AT_alphanum, true );

        $this->game->refuse( 0, $actionRefuse, true);

        self::ajaxResponse( );
    }

    public function cancelAction()
    {
        self::setAjaxMode();

        $actionCancel = self::getArg( "actionCancel", AT_alphanum, true );

        $this->game->cancelAction( 0, $actionCancel, true);

        self::ajaxResponse( );
    }
    
    public function chooseFallCard()
    {
        self::setAjaxMode();

        // Retrieve arguments
        $card = self::getArg( "card", AT_alphanum, true );
        $cardSecond = self::getArg( "cardSecond", AT_alphanum, false );
        
        //call game action
        $this->game->chooseFallCard( 0, $card, $cardSecond, true);

        self::ajaxResponse( );
    }
    
    public function discardCards()
    {
        self::setAjaxMode();

        // Retrieve arguments
        $cardsId_raw = self::getArg( "cardsSelectedId", AT_numberlist, true);
        if( $cardsId_raw == '' ){
            $cardsId = array();
        } else {
            $cardsId = explode( ',', $cardsId_raw );
        }

        //call game action
        $this->game->discardCards( 0, $cardsId, true);

        self::ajaxResponse( );
    }
       
    public function discardVines()
    {
        self::setAjaxMode();

        // Retrieve arguments
        $cardsId_raw = self::getArg( "cardsSelectedId", AT_numberlist, true);
        if( $cardsId_raw == '' ){
            $cardsId = array();
        } else {
            $cardsId = explode( ',', $cardsId_raw );
        }

        //call game action
        $this->game->discardVines( 0, $cardsId, true);

        self::ajaxResponse( );
    }

    public function loadBugSQL() {
        self::setAjaxMode();
        $reportId = (int) self::getArg('report_id', AT_int, true);
        $this->game->loadBugSQL($reportId);
        self::ajaxResponse();
    }
    

  }


