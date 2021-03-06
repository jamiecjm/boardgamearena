/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * BigTwo implementation : © <Your name here> <Your email address here>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * bigtwo.js
 *
 * BigTwo user interface script
 *
 * In this file, you are describing the logic of your user interface, in Javascript language.
 *
 */

define([
    "dojo","dojo/_base/declare",
    "ebg/core/gamegui",
    "ebg/counter",
    "ebg/stock"
],
function (dojo, declare) {
    return declare("bgagame.bigtwo", ebg.core.gamegui, {
        constructor: function(){
            console.log('bigtwo constructor');

            // Here, you can init the global variables of your user interface
            // Example:
            // this.myGlobalValue = 0;
            this.playerHand = null;
            this.playerTable = {};
            this.cardwidth = 70;
            this.cardheight = 98;
            this.cards = null;

        },

        /*
            setup:

            This method must set up the game user interface according to current game situation specified
            in parameters.

            The method is called each time the game interface is displayed to a player, ie:
            _ when the game starts
            _ when a player refreshes the game page (F5)

            "gamedatas" argument contains all datas retrieved by your "getAllDatas" PHP method.
        */

        setup: function( gamedatas )
        {
            console.log( "Starting game setup" );
            console.log('gamedatas', gamedatas);

            // Setting up player boards
            for( var player_id in gamedatas.players )
            {
                var player = gamedatas.players[player_id];

                // TODO: Setting up players boards if needed

                this.setupTableCards(player_id, `playertablecard_${player_id}`);

                const cardsOnTable = Object.values(gamedatas.table);
                const cardsPlayedByPlayer = cardsOnTable.filter(card => card.location_arg === player_id);
                cardsPlayedByPlayer.forEach(card => {
                    var suit = card.type;
                    var rank = card.type_arg;
                    this.playerTable[player_id].addToStockWithId( this.getCardUniqueId( suit, rank ), card.id );
                });
            }

            // TODO: Set up your game interface here, according to "gamedatas"
            this.cards = gamedatas['cards'];

            // Player hand
            this.setupTableCards('playerHand', 'myhand');
            this.playerHand = this.playerTable['playerHand'];

            // Cards in player's hand
            for( var i in this.gamedatas.hand )
            {
                var card = this.gamedatas.hand[i];
                var suit = card.type;
                var rank = card.type_arg;
                this.playerHand.addToStockWithId( this.getCardUniqueId( suit, rank ), card.id );
            }

            // Setup game notifications to handle (see "setupNotifications" method below)
            this.setupNotifications();

            console.log( "Ending game setup" );
        },


        ///////////////////////////////////////////////////
        //// Game & client states

        // onEnteringState: this method is called each time we are entering into a new game state.
        //                  You can use this method to perform some user interface changes at this moment.
        //
        onEnteringState: function( stateName, args )
        {
            console.log( 'Entering state: '+stateName );

            switch( stateName )
            {
                case 'playerTurn':
                    const activePlayerId = this.getActivePlayerId();
                    this.playerTable[activePlayerId].removeAll();
                    break;

            /* Example:

            case 'myGameState':

                // Show some HTML block at this game state
                dojo.style( 'my_html_block_id', 'display', 'block' );

                break;
           */
            }
        },

        // onLeavingState: this method is called each time we are leaving a game state.
        //                 You can use this method to perform some user interface changes at this moment.
        //
        onLeavingState: function( stateName )
        {
            console.log( 'Leaving state: '+stateName );

            switch( stateName )
            {

            /* Example:

            case 'myGameState':

                // Hide the HTML block we are displaying only during this game state
                dojo.style( 'my_html_block_id', 'display', 'none' );

                break;
           */


            case 'dummmy':
                break;
            }
        },

        // onUpdateActionButtons: in this method you can manage "action buttons" that are displayed in the
        //                        action status bar (ie: the HTML links in the status bar).
        //
        onUpdateActionButtons: function( stateName, args )
        {
            console.log( 'onUpdateActionButtons: '+stateName );

            if( this.isCurrentPlayerActive() )
            {
                switch( stateName )
                {
                    case 'firstPlayerTurn':
                        this.addActionButton( 'playCards', _('Play cards'), 'onPlayCards' );
                        break;
                    case 'playerTurn':
                        this.addActionButton( 'playCards', _('Play cards'), 'onPlayCards' );
                        this.addActionButton( 'pass', _('Pass'), 'onPass' );
                        break;
                    case 'newTrick':
                        this.addActionButton( 'playCards', _('Play cards'), 'onPlayCards' );
                        break;
/*
                 Example:

                 case 'myGameState':

                    // Add 3 action buttons in the action status bar:

                    this.addActionButton( 'button_1_id', _('Button 1 label'), 'onMyMethodToCall1' );
                    this.addActionButton( 'button_2_id', _('Button 2 label'), 'onMyMethodToCall2' );
                    this.addActionButton( 'button_3_id', _('Button 3 label'), 'onMyMethodToCall3' );
                    break;
*/
                }
            }
        },

        ///////////////////////////////////////////////////
        //// Utility methods

        /*

            Here, you can defines some utility methods that you can use everywhere in your javascript
            script.

        */


        getCardUniqueId: function( suit, rank )
        {
            return (suit-1)*13+(rank-3);
        },

        playCardsOnTable: function( player_id, cards )
        {
            if( player_id != this.player_id )
            {
                // Some opponent played a card
                // Move card from player panel
                for (key in cards) {
                    const card = cards[key];
                    this.playerTable[player_id].addToStockWithId( this.getCardUniqueId( card.type, card.type_arg ), card.id, `player_board_${player_id}` );
                }
            }
            else
            {
                // You played a card. If it exists in your hand, move card from there and remove
                // corresponding item
                for (key in cards) {
                    const card = cards[key];
                    if( $('myhand_item_'+card.id) )
                    {
                        this.playerTable[player_id].addToStockWithId( this.getCardUniqueId( card.type, card.type_arg ), card.id, 'myhand_item_'+card.id );
                        this.playerHand.removeFromStockById( card.id );
                    }
                }
            }
        },

        setupTableCards: function(playerId, handId)
        {
            // Player hand
            this.playerTable[playerId] = new ebg.stock();
            this.playerTable[playerId].create( this, $(handId), this.cardwidth, this.cardheight );
            this.playerTable[playerId].image_items_per_row = 13;
            this.playerTable[playerId].extraClasses = 'cards'
            this.playerTable[playerId].centerItems = true;

            // Create cards types:
            let weight = 0;
            for( var rank=3;rank<=15;rank++ )
            {
                for( var suit=1;suit<=4;suit++ )
                {
                    // Build card type id
                    var card_type_id = this.getCardUniqueId( suit, rank );
                    this.playerTable[playerId].addItemType( card_type_id, weight, g_gamethemeurl+'img/cards.jpg', card_type_id );
                    weight += 1;
                }
            }
        },


        ///////////////////////////////////////////////////
        //// Player's action

        /*

            Here, you are defining methods to handle player's action (ex: results of mouse click on
            game objects).

            Most of the time, these methods:
            _ check the action is possible at this game state.
            _ make a call to the game server

        */
       onPlayCards: function ()
       {
           if( this.checkAction('playCards'))
           {
               const items = this.playerHand.getSelectedItems();
               const playerHand = this.playerHand.getAllItems();
               console.log('items', items);

               if ( items.length === 0 ) {
                   return;
               }

               if (items.length === 4) {
                   this.showMessage(_("Invalid card combinations"), 'error');
                   return;
               }

               // first player must play the three of diamonds
               const hasThreeOfDiamond = playerHand.find((item) => item.type === 0);
               if (hasThreeOfDiamond){
                   const threeOfDiamonds = items.find((item) => item.type === 0);
                   if (!threeOfDiamonds) {
                       this.showMessage(_("You must play the three of diamonds"), 'error');
                       return;
                   }
               }

               const cardIds = items.map(item => item.id);
               console.log('cardIds', cardIds);
                this.ajaxcall( "/bigtwo/bigtwo/playCards.html", { cards: cardIds.join(','), lock: true }, this, function( result ) {
                }, function( is_error) { } );
           }
       },

       onPass: function() {
           this.ajaxcall( "/bigtwo/bigtwo/pass.html", { lock: true }, this, function( result ) {}, function( is_error) { } );
       },


        /* Example:

        onMyMethodToCall1: function( evt )
        {
            console.log( 'onMyMethodToCall1' );

            // Preventing default browser reaction
            dojo.stopEvent( evt );

            // Check that this action is possible (see "possibleactions" in states.inc.php)
            if( ! this.checkAction( 'myAction' ) )
            {   return; }

            this.ajaxcall( "/bigtwo/bigtwo/myAction.html", {
                                                                    lock: true,
                                                                    myArgument1: arg1,
                                                                    myArgument2: arg2,
                                                                    ...
                                                                 },
                         this, function( result ) {

                            // What to do after the server call if it succeeded
                            // (most of the time: nothing)

                         }, function( is_error) {

                            // What to do after the server call in anyway (success or failure)
                            // (most of the time: nothing)

                         } );
        },

        */


        ///////////////////////////////////////////////////
        //// Reaction to cometD notifications

        /*
            setupNotifications:

            In this method, you associate each of your game notifications with your local method to handle it.

            Note: game notification names correspond to "notifyAllPlayers" and "notifyPlayer" calls in
                  your bigtwo.game.php file.

        */
        setupNotifications: function()
        {
            console.log( 'notifications subscriptions setup' );

            dojo.subscribe( 'playCards', this, "notif_playCards" );

            // TODO: here, associate your game notifications with local methods

            // Example 1: standard notification handling
            // dojo.subscribe( 'cardPlayed', this, "notif_cardPlayed" );

            // Example 2: standard notification handling + tell the user interface to wait
            //            during 3 seconds after calling the method in order to let the players
            //            see what is happening in the game.
            // dojo.subscribe( 'cardPlayed', this, "notif_cardPlayed" );
            // this.notifqueue.setSynchronous( 'cardPlayed', 3000 );
            //
        },

        // TODO: from this point and below, you can write your game notifications handling methods

        notif_playCards: function( notif )
        {
            // Play a card on the table
            this.playCardsOnTable( notif.args.player_id, notif.args.cards );
        },

        /*
        Example:

        notif_cardPlayed: function( notif )
        {
            console.log( 'notif_cardPlayed' );
            console.log( notif );

            // Note: notif.args contains the arguments specified during you "notifyAllPlayers" / "notifyPlayer" PHP call

            // TODO: play the card in the user interface.
        },

        */
   });
});
