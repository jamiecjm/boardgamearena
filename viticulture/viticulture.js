/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * viticulture implementation : © Leo Bartoloni bartololeo74@gmail.com
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * viticulture.js
 *
 * viticulture user interface script
 *
 * In this file, you are describing the logic of your user interface, in Javascript language.
 *
 */

define([
    "dojo","dojo/_base/declare",
    "ebg/core/gamegui",
    "ebg/counter",
    "ebg/stock",
    "ebg/expandablesection"
],
function (dojo, declare) {
    return declare("bgagame.viticulture", ebg.core.gamegui, {
        constructor: function(){
            console.log('viticulture constructor');

            // Here, you can init the global variables of your user interface
            // Example:
            // this.myGlobalValue = 0;

            this.clientStateArgs = {};

            this.SOLO_PLAYER_ID=-1;

            this.actionSlots=[];
            this.actionSlots[0]={
                wakeupOrder_slot: {
                    offsetX:-30,
                    offsetY:0
                }
            };

            this.residualPayment=[];
            this.residualPayment[0]={
                x:418,
                y:240,
                offset: 40,
                ddx: 9,
                ddy: 12,
                offsetPlayer: [[{x:0,y:0}],
                [{x:-0.7,y:0},{x:0.7,y:0}],
                [{x:-0.7,y:0.8},{x:0.7,y:0.8},{x:0,y:-0.8}],
                [{x:-0.7,y:-1},{x:0.7,y:-1},{x:0.7,y:1},{x:-0.7,y:1}],
                [{x:-1,y:-1},{x:1,y:-1},{x:0,y:0},{x:-1,y:1},{x:1,y:1}],
                [{x:-1,y:-1},{x:0,y:-1},{x:1,y:-1},{x:-1,y:1},{x:0,y:1},{x:1,y:1}]]
            };

            this.scoringTrack=[];
            this.scoringTrack[0]={
                x: -4,
                y: 346,
                zeroX:-300,
                dx: 29.3,
                ddx: 9,
                ddy: 12,
                offsetPlayer: [[{x:0,y:0}],
                [{x:-0.7,y:0},{x:0.7,y:0}],
                [{x:-0.7,y:0.8},{x:0.7,y:0.8},{x:0,y:-0.8}],
                [{x:-0.7,y:-1},{x:0.7,y:-1},{x:0.7,y:1},{x:-0.7,y:1}],
                [{x:-1,y:-1},{x:1,y:-1},{x:0,y:0},{x:-1,y:1},{x:1,y:1}],
                [{x:-1,y:-1},{x:0,y:-1},{x:1,y:-1},{x:-1,y:1},{x:0,y:1},{x:1,y:1}]]
            };

            this.cardTypes={
                'greenCard': {deck:'deckGreen',discard:'discardGreen'},
                'yellowCard': {deck:'deckYellow',discard:'discardYellow'},
                'blueCard': {deck:'deckBlue',discard:'discardBlue'},
                'purpleCard': {deck:'deckPurple',discard:'discardPurple'},
                'automaCard': {deck:'deckAutoma',discard:'dicardAutoma'}
            };

            this.cardDecks=[];
            this.cardDecks[0]={
                deckGreen: {type:'greenCard',x:-420,y:-292},
                deckYellow: {type:'yellowCard',x:-180,y:-292},
                deckPurple: {type:'purpleCard',x:60,y:-292},
                deckBlue: {type:'blueCard',x:300,y:-292}
            };

            this.countDecks=[];
            this.countDecks[0]={
                deckGreen: {x:-419,y:-233},
                deckYellow: {x:-180,y:-233},
                deckPurple: {x:60,y:-233},
                deckBlue: {x:300,y:-233}
            };

            this.discardDecks=[];
            this.discardDecks[0]={
                deckGreen: {key:'discardGreen',x:-300,y:-292},
                deckYellow: {key:'discardYellow',x:-60,y:-292},
                deckPurple: {key:'discardPurple',x:180,y:-292},
                deckBlue: {key:'discardBlue',x:420,y:-292}
            };

            this.locations=[];
            this.locations[0]={
                101:{x:-202,y:-106}, //2 : playYellowCard_1
                102:{x:-167,y:-93}, //3 : playYellowCard_1
                103:{x:-131,y:-80}, //4 : playYellowCard_1

                111:{x:-266,y:20}, //2 : drawGreenCard_1
                112:{x:-235,y:-1}, //3 : drawGreenCard_1
                113:{x:-209,y:-21}, //4 : drawGreenCard_1

                121:{x:-348,y:165}, //2 : getLira_2
                122:{x:-315,y:146}, //3 : getLira_2
                123:{x:-285,y:125}, //4 : getLira_2

                131:{x:-244,y:291}, //2 : buildStructure_1
                132:{x:-220,y:267}, //3 : buildStructure_1
                133:{x:-198,y:244}, //4 : buildStructure_1

                141:{x:-149,y:40}, //2 : sellGrapes_1|buySellVine_1
                142:{x:-109,y:44}, //3 : sellGrapes_1|buySellVine_1
                143:{x:-71,y:47}, //4 : sellGrapes_1|buySellVine_1

                151:{x:-91,y:127}, //2 : plant_1
                152:{x:-56,y:141}, //3 : plant_1
                153:{x:-21,y:154}, //4 : plant_1

                301:{x:7,y:-16}, //2 : drawPurpleCard_1
                302:{x:44,y:-28}, //3 : drawPurpleCard_1
                303:{x:82,y:-41}, //4 : drawPurpleCard_1

                311:{x:83,y:46}, //2 : harvestField_1
                312:{x:114,y:27}, //3 : harvestField_1
                313:{x:145,y:3}, //4 : harvestField_1

                321:{x:112,y:226}, //2 : trainWorker_1
                322:{x:147,y:213}, //3 : trainWorker_1
                323:{x:182,y:204}, //4 : trainWorker_1

                331:{x:253,y:202}, //2 : wineOrder_1
                332:{x:283,y:182}, //3 : wineOrder_1
                333:{x:309,y:163}, //4 : wineOrder_1

                341:{x:246,y:46}, //2 : makeWine_2
                342:{x:281,y:61}, //3 : makeWine_2
                343:{x:316,y:76}, //4 : makeWine_2

                351:{x:232,y:-123}, //2 : playBlueCard_1
                352:{x:270,y:-115}, //3 : playBlueCard_1
                353:{x:309,y:-110}, //4 : playBlueCard_1

                801:{x:40,y:257}, //2 : getLira_1

                901:{x: -47,y:-67} //2 : uproot_1|harvestField_1

            };

            this.sharedLocations=[];
            this.sharedLocations[0]={
                100:{x:-160,y:-133}, //3 : playYellowCard_1

                110:{x:-260,y:-33}, //3 : drawGreenCard_1

                120:{x:-330,y:112}, //3 : getLira_2

                130:{x:-248,y:240}, //3 : buildStructure_1

                140:{x:-109,y:10}, //3 : sellGrapes_1|buySellVine_1

                150:{x:-70,y:185}, //3 : plant_1

                300:{x:38,y:-66}, //3 : drawPurpleCard_1

                310:{x:140,y:66}, //3 : harvestField_1

                320:{x:133,y:178}, //3 : trainWorker_1

                330:{x:273,y:155}, //3 : wineOrder_1

                340:{x:292,y:25}, //3 : makeWine_2

                350:{x:280,y:-155} //3 : playBlueCard_1

            };

            this.grapePrice = {1:1,2:1,3:1,4:2,5:2,6:2,7:3,8:3,9:3};

            //retrieve css rotation style name
            dojo.forEach(['transform', 'WebkitTransform', 'msTransform', 'MozTransform', 'OTransform'],
               dojo.hitch(this, function(name) {
                  if (typeof dojo.body().style[name] != 'undefined') {
                     this.rotationTransform = name;
                     switch (name) {
                        case 'transform':
                             this.rotationTransformCssStyle='transform';
                             break;

                        case 'WebkitTransform':
                             this.rotationTransformCssStyle='-webkit-transform';
                             break;

                        case 'msTransform':
                            this.rotationTransformCssStyle='-ms-transform';
                            break;

                        case 'MozTransform':
                            this.rotationTransformCssStyle='-moz-transform';
                            break;

                        case 'OTransform':
                            this.rotationTransformCssStyle='-o-transform';
                            break;
                         default:
                             break;
                     }
                  }
               })
            );
            //debug: to test translation on client side
            if (this.getUrlParams()._i18ndebug){
                var _orig = _;
                _=function(){
                    console.log(arguments);
                    return "§"+_orig.apply(_orig,arguments)+"§";
                };
            }
        },

        /*
            setup:

            This method must set up the game user interface according to current game situation specified
            in parameters.

            The method is called each time the game interface is displayed to a player, ie:
            _ when the game starts
            _ when a player refreshes the game page (F5)

            "gamedatas" argument co-ntains all datas retrieved by your "getAllDatas" PHP method.
        */

        setup: function( gamedatas )
        {
            console.log( "Starting game setup" );

            this.gamedatas_local = dojo.clone(gamedatas);
            if (this.gamedatas_local.soloMode>0){
                dojo.addClass("vit_game", "vit_solo vit_solo"+this.gamedatas_local.soloMode);
                this.gamedatas_local.players = this.addAutomaPlayer(this.gamedatas_local.players, this.gamedatas_local.automaPlayerData);
            }

            //DEBUG:
            //this.debugDumpDeck();

            /*iphone hack for scaled text*/
            try{
                if(navigator.userAgent.match(/iPhone/) && navigator.userAgent.match(/Safari/)) {
                    dojo.addClass('vit_game','vit_iphone_safari');
                }
                if(navigator.userAgent.match(/iPad/) && navigator.userAgent.match(/Safari/)) {
                    dojo.addClass('vit_game','vit_ipad_safari');
                }
            } catch (error){
                console.log(error);
            }

            this.setupPreference();

            var playerId = this.getThisPlayerId();

            this.grapes = {
                grapeRed:{
                    label: _('Red Grape')
                },
                grapeWhite:{
                    label: _('White Grape')
                }
            };

            this.wines = {
                wineRed:{
                    label: _('Red Wine'),
                    origin: {grapeRed:1, grapeWhite:0},
                    min:1,
                    max:9,
                    x: 22,
                    y: 57,
                    dx: 28,
                    dxx: 8
                },
                wineWhite:{
                    label: _('White Wine'),
                    origin: {grapeRed:0, grapeWhite:1},
                    min:1,
                    max:9,
                    x: 22,
                    y: 89,
                    dx: 28,
                    dxx: 8
                },
                wineBlush:{
                    label: _('Blush Wine'),
                    origin: {grapeRed:1, grapeWhite:1},
                    min:4,
                    max:9,
                    x: 22,
                    y: 120,
                    dx: 28,
                    dxx: 8
                },
                wineSparkling:{
                    label: _('Sparkling Wine'),
                    origin: {grapeRed:2, grapeWhite:1},
                    min:7,
                    max:9,
                    x: 22,
                    y: 151,
                    dx: 28,
                    dxx: 8
                }
            };

            //positions
            this.wakeupOrder=[];
            this.wakeupOrder[0]={
                x:-430,
                y:61,
                dx: 0,
                dy: 33.6,
                slots:[
                    {
                        value:1, tooltip: _('First, no bonus')
                    },
                    {
                        value:2, tooltip: _('Second, draw a ${token_greenCardPlus}')
                    },
                    {
                        value:3, tooltip: _('Third, draw a ${token_purpleCardPlus}')
                    },
                    {
                        value:4, tooltip: _('Fourth, get a ${token_lira1}')
                    },
                    {
                        value:5, tooltip: _('Fifth, draw a ${token_yellowCardPlus} or ${token_blueCardPlus}')
                    },
                    {
                        value:6, tooltip: _('Sixth, gain a ${token_vp1}')
                    },
                    {
                        value:7, tooltip: _('Seventh, get ${token_worker_t} for this year')
                    }
                ]
            };

            this.boardLabels=[];
            this.boardLabels[0]={
                102:{x:-177,y:-100, label:_('Play ${token_yellowCard}'), rot:24}, //3 : playYellowCard_1

                112:{x:-215,y:-20, label:_('Draw ${token_greenCardPlus}'), rot:-36}, //3 : drawGreenCard_1

                122:{x:-295,y:115, label:_('Give tour to gain ${token_lira2}'), rot:-35}, //3 : getLira_2

                132:{x:-190,y:235, label:_('Build one structure'), rot:-42}, //3 : buildStructure_1

                142:{x:-115,y:54, label:_('Sell at least one grape or buy/sell one field'), rot:6}, //3 : sellGrapes_1|buySellVine_1

                152:{x:-38,y:94, label:_('Plant ${token_greenCard}'), rot:21}, //3 : plant_1

                302:{x:56,y:-32, label:_('Draw ${token_purpleCardPlus}'), rot:-20}, //3 : drawPurpleCard_1

                312:{x:90,y:-22, label:_('Harvest one field'), rot:-33}, //3 : harvestField_1

                322:{x:155,y:202, label:_('Pay ${token_lira4} to train one ${token_worker}'), rot:-20},  //3 : trainWorker_1

                332:{x:300,y:163, label:_('Fill ${token_purpleCard}'), rot:-33},   //3 : wineOrder_1

                342:{x:261,y:51, label:_('Make up to two wines'), rot:25}, //3 : makeWine_2

                352:{x:260,y:-110, label:_('Play ${token_blueCard}'), rot:10}, //3 : playBlueCard_1

                801:{x:40,y:285, label:_('Gain ${token_lira1}'), rot:0}, //2 : getLira_1,

                wakeuporder2:{x:-370,y:90, label:_('(draw)'), cls:'boardLabelsWakeupOrder'},
                wakeuporder3:{x:-370,y:125, label:_('(draw)'), cls:'boardLabelsWakeupOrder'},
                wakeuporder5:{x:-346,y:190, label:_('(draw)'), cls:'boardLabelsWakeupOrder'},
                wakeuporder7:{x:-360,y:260, label:_('for this year'), cls:'boardLabelsWakeupOrder'},

                sideSpring:{x:430,y:-190, label:_('Spring'), cls:'boardLabelsSeasonTitle'},
                sideSpringText:{x:430,y:-177, label:_('Choose wake-up positions'), cls:'boardLabelsSeasonText'},
                sideSummer:{x:430,y:-152, label:_('Summer'), cls:'boardLabelsSeasonTitle'},
                sideSummerText1:{x:430,y:-138, label:_('Worker Placement'), cls:'boardLabelsSeasonText'},
                sideSummerText2:{x:425,y:-126, label:_('2 players'), cls:'boardLabelsSeasonText'},
                sideSummerText3:{x:425,y:-112, label:_('3-4 players'), cls:'boardLabelsSeasonText'},
                sideSummerText4:{x:425,y:-97, label:_('5-6 players'), cls:'boardLabelsSeasonText'},
                sideFall:{x:425,y:-73, label:_('Fall'), cls:'boardLabelsSeasonTitle'},
                sideFallText:{x:425,y:-60, label:_('Draw 1 Visitor card'), cls:'boardLabelsSeasonText'},
                sideWinter:{x:420,y:-35, label:_('Winter'), cls:'boardLabelsSeasonTitle'},
                sideWinterText1:{x:420,y:-23, label:_('Worker Placement'), cls:'boardLabelsSeasonText'},
                sideWinterText2:{x:420,y:-9, label:_('2 players'), cls:'boardLabelsSeasonText'},
                sideWinterText3:{x:420,y: 5, label:_('3-4 players'), cls:'boardLabelsSeasonText'},
                sideWinterText4:{x:420,y: 19, label:_('5-6 players'), cls:'boardLabelsSeasonText'},
                sideYearEnd:{x:420,y:40, label:_('Year End'), cls:'boardLabelsSeasonTitle'},
                sideYearEndText:{x:422,y:53, label:_('Age grape and wine tokens<br/>Retrieve workers<br/>Collect residual payments<br/>Discard down to 7 cards<br/>Rotate first player<br/>counter-clockwise'), cls:'boardLabelsSeasonTextSmall'},

            };

            this.playerBoardLabels={
                field1:{x:-150,y:-110, label:'${token_lira5} '+_('Field'), rot:0},
                fieldMax1:{x:-150,y:-93, label:dojo.string.substitute(_('(max vine value:<strong>${value}</strong>)'),{value:5}), rot:0, cls: 'playerBoardLabelsSmall'},
                field2:{x:-10, y:-110, label:'${token_lira6} '+_('Field'), rot:0},
                fieldMax2:{x:-10,y:-93, label:dojo.string.substitute(_('(max vine value:<strong>${value}</strong>)'),{value:6}), rot:0, cls: 'playerBoardLabelsSmall'},
                field3:{x:140,y:-110, label:'${token_lira7} '+_('Field'), rot:0},
                fieldMax3:{x:140,y:-93, label:dojo.string.substitute(_('(max vine value:<strong>${value}</strong>)'),{value:7}), rot:0, cls: 'playerBoardLabelsSmall'},
                grape:{x:-130,y:85, label:_('Grape'), rot:0},
                salePrices:{x:-130,y:120, label:_('Sale prices'), rot:0},
                crushPad:{x:-145,y:170, label:_('Crush Pad'), rot:0},
                smallCellar:{x:40,y:185, label:_('Small Cellar'), rot:0},
                wineLegenda:{x:51,y:114, label:_('Blush & Sparkling Wine Key'), rot:0, cls:'playerBoardLabelsWineLegenda',
                            tooltip: _('Blush & Sparkling Wine Key: <br/>${token_grapeWhite}+${token_grapeRed}= Blush Wine (needs Medium Cellar ${token_mediumCellar}) <br/>${token_grapeWhite}+${token_grapeRed}+${token_grapeRed}= Sparkling Wine (needs Large Cellar ${token_largeCellar})')},
                availableWorkers:{x:225,y:-10, label:_('Available workers'), rot:0},
                workersInfo:{x:100,y:-20, label:_('You can select Grande worker before selecting location placement'), rot:0}
            };

            this.playerBoard = {
                worker: {x: 173, y: -3, dx: 22},
                rooster: {x: 40, y: 10},
                trellis: {x: -247,y:-170, offx:-10, offy:20, label:_('${token_lira2} ${token_trellis} Trellis')},// NOI18N
                windmill: {x: -180,y:30,  offx: -60, offy: -30,label:_('${token_lira5} ${token_windmill} Windmill'), text:_('when you plant a vine gain ${token_vp1} (max 1 VP/year)')},// NOI18N
                irrigation: {x: 250,y:-100, offx: 20, offy: -40, label:_('${token_lira3} ${token_irrigation} Irrigation')},// NOI18N
                yoke: {x: 25,y:-49,  offx: -30, offy: -26, label:_('${token_lira2} ${token_yoke} Yoke'), text:_('uproot 1 vine or harvest 1 field')},// NOI18N
                tastingRoom: {x: 222,y:-50, offx: -90, label:_('${token_lira6} ${token_tastingRoom} Tasting Room'), text:_('when you give a vineyard tour, if you have at least 1 ${token_wineAny} in your cellar, gain ${token_vp1}(max 1 VP/year)')},// NOI18N
                mediumCellar: {x: 130,y:185, offx: -30, offy: -10, label:_('${token_lira4}<br/>${token_mediumCellar} Medium Cellar')},// NOI18N
                largeCellar: {x: 240,y:185,  offx: -30, offy: -10, label:_('${token_lira6}<br/>${token_largeCellar} Large Cellar')}, // NOI18N
                cottage: {x: -60,y:0, offx: -80, offy: -30, label:_('${token_lira4} ${token_cottage} Cottage'), text:_('draw ${token_yellowCardPlus} or ${token_blueCardPlus} each fall')},// NOI18N
                vines: {x: 0, y:45, dy:-21},
                field: {
                    x:-152,
                    y:-149,
                    dx:145
                },
                grapeRed: {
                    1: {x:-248,y:75},
                    2: {x:-219,y:75},
                    3: {x:-189,y:75},
                    4: {x:-221,y:112},
                    5: {x:-192,y:112},
                    6: {x:-162,y:112},
                    7: {x:-269,y:149},
                    8: {x:-239,y:149},
                    9: {x:-210,y:149}
                },
                grapeWhite: {
                    1: {x:-79,y:75},
                    2: {x:-49,y:75},
                    3: {x:-20,y:75},
                    4: {x:-102,y:112},
                    5: {x:-72,y:112},
                    6: {x:-42,y:112},
                    7: {x:-91,y:149},
                    8: {x:-61,y:149},
                    9: {x:-31,y:149}
                },
                wakeup_bonus: {
                    x:23,y:27, dx:30, dy:0
                }
            };

            //i18n
            for( var key in this.gamedatas_local.playerTokens ){
                this.gamedatas_local.playerTokens[key].name = _(this.gamedatas_local.playerTokens[key].name); // NOI18N
            }
            for( var key in this.gamedatas_local.mamas ){
                this.gamedatas_local.mamas[key].name = _(this.gamedatas_local.mamas[key].name);// NOI18N
            }
            for( var key in this.gamedatas_local.papas ){
                this.gamedatas_local.papas[key].name = _(this.gamedatas_local.papas[key].name);// NOI18N
            }
            for( var key in this.gamedatas_local.greenCards ){
                this.gamedatas_local.greenCards[key].name = _(this.gamedatas_local.greenCards[key].name);// NOI18N
                this.gamedatas_local.greenCards[key].description = _(this.gamedatas_local.greenCards[key].description);// NOI18N
            }
            for( var key in this.gamedatas_local.yellowCards ){
                this.gamedatas_local.yellowCards[key].name = _(this.gamedatas_local.yellowCards[key].name);// NOI18N
                this.gamedatas_local.yellowCards[key].description = _(this.gamedatas_local.yellowCards[key].description);// NOI18N
            }
            for( var key in this.gamedatas_local.purpleCards ){
                this.gamedatas_local.purpleCards[key].name = _(this.gamedatas_local.purpleCards[key].name);// NOI18N
                this.gamedatas_local.purpleCards[key].description = _(this.gamedatas_local.purpleCards[key].description);// NOI18N
            }
            for( var key in this.gamedatas_local.blueCards ){
                this.gamedatas_local.blueCards[key].name = _(this.gamedatas_local.blueCards[key].name);// NOI18N
                this.gamedatas_local.blueCards[key].description = _(this.gamedatas_local.blueCards[key].description);// NOI18N
            }
            if (this.gamedatas_local.automaCards){
                for( var key in this.gamedatas_local.automaCards ){
                    this.gamedatas_local.automaCards[key].name = _(this.gamedatas_local.automaCards[key].name);// NOI18N
                    this.gamedatas_local.automaCards[key].des1 = _(this.gamedatas_local.automaCards[key].des1);// NOI18N
                    this.gamedatas_local.automaCards[key].des2 = _(this.gamedatas_local.automaCards[key].des2);// NOI18N
                    this.gamedatas_local.automaCards[key].des3 = _(this.gamedatas_local.automaCards[key].des3);// NOI18N
                    this.gamedatas_local.automaCards[key].des4 = _(this.gamedatas_local.automaCards[key].des4);// NOI18N
                }
            }

            this.updatePlayerSummaries();

            var playerData = this.getPlayerData(this.getThisPlayerId());

            this.player_color = playerData.player_color;
            this.player_color_back = playerData.color_back;
            this.players_number = this.gamedatas_local.playersNumber;


            dojo.addClass("vit_game", "players_"+this.players_number);
            dojo.addClass("vit_game", "set_"+this.gamedatas_local.set);

            var wakeupOrder = this.wakeupOrder[this.gamedatas_local.set].slots;
            for (var i=0;i<wakeupOrder.length;i++){
                var item = {
                    elementId: 'wakeupOrder_slot_'+wakeupOrder[i].value,
                    cssClass: 'wakeupOrder_slot',
                    type: 'wakeupOrder_slot',
                    position: 'position:absolute;',
                    style: '',
                    arg: wakeupOrder[i].value,
                    x: wakeupOrder[i].value,
                    y: wakeupOrder[i].value,
                    phase: 1,
                    action: 'wakeupOrder',
                    label: ''
                };
                var element = dojo.place( this.format_block('jstpl_action_slot', item), 'board', 'last' );
                this.placeOnObjectPos( element, 'board', this.wakeupOrder[this.gamedatas_local.set].x+this.wakeupOrder[this.gamedatas_local.set].dx*i, this.wakeupOrder[this.gamedatas_local.set].y+this.wakeupOrder[this.gamedatas_local.set].dy*i, 1500);
                
                //IOS: removing tooltips on actions
                if(!navigator.userAgent.match(/iPhone/)) {
                    this.addTooltipHtml(element.id, this.getDescriptionWithTokens(wakeupOrder[i].tooltip));
                }
            }

            var residualPayment = this.residualPayment[this.gamedatas_local.set];
            var residualPaymentItem = {
                elementId: 'residualPayment_slot',
                cssClass: '',
                type: 'residualPayment_slot',
                position: 'position:absolute;',
                style: '',
                arg: 0,
                x: 0,
                y: 0,
                phase: 0,
                action: '',
                label: ''
            };
            var residualPaymentElement = dojo.place( this.format_block('jstpl_action_slot', residualPaymentItem), 'board', 'last' );
            this.placeOnObjectPos( residualPaymentElement, 'board', residualPayment.x, residualPayment.y, 1500);

            var scoringTrack = this.scoringTrack[this.gamedatas_local.set];
            var scoringTrackItem = {
                elementId: 'scoringTrack_slot',
                cssClass: '',
                type: 'scoringTrack_slot',
                position: 'position:absolute;',
                style: '',
                arg: 0,
                x: 0,
                y: 0,
                phase: 0,
                action: '',
                label: ''
            };
            var scoringTrackElement = dojo.place( this.format_block('jstpl_action_slot', scoringTrackItem), 'board', 'last' );
            this.placeOnObjectPos( scoringTrackElement, 'board', scoringTrack.x, scoringTrack.y, 1500);

            var worker_t_id = 0;
            for (var playerIdToken in this.gamedatas_local.tokens){
                var tokensPlayer = this.gamedatas_local.tokens[playerIdToken];
                for (var i=0;i<tokensPlayer.length;i++){
                    if (tokensPlayer[i].t == 'worker_t'){
                        worker_t_id = tokensPlayer[i].i;
                    }
                }
            }
            var workerTemporaryItem = {
                elementId: this.calculateTokenId(0,'worker_t'),
                cssClass: '',
                type: 'worker_t',
                position: 'position:absolute;',
                style: '',
                arg: 0,
                x: 0,
                y: 0,
                id: worker_t_id,
                tooltip: this.getTokenDescription('worker_t')
            };
            var workerTemporaryElement = dojo.place( this.format_block('jstpl_token', workerTemporaryItem), 'board', 'last' );
            this.placeOnObjectPos( workerTemporaryElement, 'board', 0,0, 1500);

            // Setting up player boards
            for( var player_id in this.gamedatas_local.players )
            {

                var player = this.gamedatas_local.players[player_id];
                player.first_player_tooltip=_('First player');
    
                //only for real players
                if (player_id != this.SOLO_PLAYER_ID){
                     // Setting up players boards if needed
                    var player_board_div = $('player_board_'+player.id);
                    dojo.place( this.format_block('jstpl_player_side', player ), player_board_div );
                    if (this.gamedatas_local.soloMode>0){
                      dojo.place( this.format_block('jstpl_player_side_automa', this.gamedatas_local.players[this.SOLO_PLAYER_ID]), player_board_div );
                    }
                //} else {
                //    dojo.place( this.format_block('jstpl_player_side_automa', player), $('player_boards'));
                }

            }

            playerData.labelHandSpectator=_('Cards not visible in spectator mode');

            // Setting up players
            // current player is first
            playerData.playerboard_row_class='playerboard_row_1';
            playerData.preferencesHtml='';
            if (!this.isSpectator){
                playerData.preferencesHtml=this.format_block('jstpl_preferences', {
                    label_preference_100:_('Show values for vp and lira icons'),
                    label_preference_101:_('Winter Pass Action Enabled'),
                } );
            }
            dojo.place( this.format_block('jstpl_player_board', playerData ), 'vit_boards_wrapper','last' );

            var playerProgr=1;
            var playerIdOrdered = this.getPlayerIdOrdered();

            // Setting up players boards/zone
            for( var playerIndex=0;playerIndex<playerIdOrdered.length;playerIndex++ )
            {
                var id = playerIdOrdered[playerIndex];
                var playerBoardId = 'playerboard_'+id;
                player = this.gamedatas_local.players[id];
                player.actions = '';
                player.labelHandSpectator=_('Cards not visible in spectator mode');

                if (id!=this.getThisPlayerId()){
                    playerProgr++;
                    player.playerboard_row_class='playerboard_row_other playerboard_row_'+playerProgr;
                    // Setting up players
                    player.preferencesHtml='';
                    dojo.place( this.format_block('jstpl_player_board', player ), 'vit_boards_wrapper','last' );
                }

                if (id == this.SOLO_PLAYER_ID){
                    dojo.place( '<div id="automa_cards_stock_wrapper"><div id="automa_cards_stock"></div></div>', 'playerboard_row_'+id,'last' );
                }

                //Setting up tokens
                for (var i=0;i<gamedatas.tokens[id].length;i++){
                    var elementId = this.calculateTokenId(id,gamedatas.tokens[id][i].t,gamedatas.tokens[id][i].i);
                    if (this.queryCount('#'+elementId)==0){
                        var tooltip = this.getTokenDescription(gamedatas.tokens[id][i].t);
                        if (gamedatas.tokens[id][i].t=='rooster'){
                            tooltip = player.player_name;
                        }
                        var cssClass='component';
                        if (gamedatas.tokens[id][i].t.indexOf('worker_')==0){
                            cssClass+=' worker';
                        }
                        if (gamedatas.tokens[id][i].t == 'worker_g' && id == this.getThisPlayerId()){
                            tooltip='';
                        }
                        var item = {
                            elementId: elementId,
                            cssClass: cssClass,
                            type: gamedatas.tokens[id][i].t+' '+gamedatas.tokens[id][i].t+'_'+player.player_color,
                            position: 'position:absolute;',
                            style: '',
                            arg: gamedatas.tokens[id][i].t,
                            x: 0,
                            y: 0,
                            id: gamedatas.tokens[id][i].i,
                            tooltip: tooltip
                        };
                        var element = dojo.place( this.format_block('jstpl_token', item), playerBoardId, 'last' );
                        if (gamedatas.tokens[id][i].t == 'worker_g' && id == this.getThisPlayerId()){
                            this.addTooltipHtml(element, _("Grande Worker") + "<br/>"+_('You can select Grande worker before selecting location placement')) ;
                        }
                    }
                }
                var scoringItem = {
                    elementId: 'scoringToken_'+id,
                    cssClass: 'small scoring scoring_'+player.player_color,
                    type: 'scoringToken',
                    position: 'position:absolute;',
                    style: '',
                    arg: 0,
                    x: 0,
                    y: 0,
                    id: 'scoringToken_'+id,
                    tooltip: player.player_name
                };
                var scoringElement = dojo.place( this.format_block('jstpl_token', scoringItem), 'board', 'last' );

                var residualPaymentItem = {
                    elementId: 'residualPaymentToken_'+id,
                    cssClass: 'bottle small bottle_'+player.player_color,
                    type: 'residualPaymentToken',
                    position: 'position:absolute;',
                    style: '',
                    arg: 0,
                    x: 0,
                    y: 0,
                    id: 'residualPaymentToken_'+id,
                    tooltip: player.player_name
                };
                var residualPaymentItemElement = dojo.place( this.format_block('jstpl_token', residualPaymentItem), 'board', 'last' );

                for (var i in this.gamedatas_local.fields){
                    var item = {
                        elementId: 'field_slot_'+id+'_'+this.gamedatas_local.fields[i].key,
                        cssClass: 'action_slot field_slot field',
                        type: 'field',
                        position: 'position:absolute;',
                        style: '',
                        arg: i,
                        x: i,
                        y: i,
                        phase: 99,
                        action: 'plant',
                        arg: i,
                        label: ''
                    };
                    var element = dojo.place( this.format_block('jstpl_action_slot', item), playerBoardId, 'last' );
                    this.placeOnObjectPos( element, playerBoardId, this.playerBoard.field.x+(Number(this.gamedatas_local.fields[i].key)-1)*this.playerBoard.field.dx, this.playerBoard.field.y, 1500);
                }

                if (id == this.getThisPlayerId()){
                    for (var i=0; i< this.gamedatas_local.playerTokens.length;i++){
                        var playerToken = this.gamedatas_local.playerTokens[i];
                        if (playerToken.isBuilding){
                            var content = this.getDescriptionWithTokens(this.playerBoard[playerToken.type].label)||'';
                            if (this.playerBoard[playerToken.type].text){
                                content+='<div class="text">'+this.getDescriptionWithTokens(this.playerBoard[playerToken.type].text)+'</div>';
                            }
                            var item = {
                                elementId: 'building_slot_'+playerToken.type,
                                cssClass: 'action_slot building_slot building_slot_'+playerToken.type,
                                type: 'building',
                                position: 'position:absolute;',
                                style: '',
                                arg: i,
                                x: i,
                                y: i,
                                phase: 99,
                                arg: playerToken.type,
                                label: content,
                                action: 'build',
                            };
                            var element = dojo.place( this.format_block('jstpl_action_slot', item), playerBoardId, 'last' );
                            var pos = this.playerBoard[playerToken.type];
                            this.placeOnObjectPos( element, playerBoardId, pos.x, pos.y, 1500);
                            this.addTooltipHtml(element.id, content);
                        }
                    }

                    for (var wine in this.wines){
                        for (var i=this.wines[wine].min; i<= this.wines[wine].max; i++){
                            var item = {
                                elementId: 'wine_'+wine+'_'+i,
                                cssClass: 'action_slot wine_slot wine_slot_'+wine,
                                type: wine,
                                position: 'position:absolute;',
                                style: '',
                                arg: i,
                                x: i,
                                y: i,
                                phase: 99,
                                arg: i,
                                label: '',
                                action: 'wine'
                            };
                            var element = dojo.place( this.format_block('jstpl_action_slot', item), playerBoardId, 'last' );
                            var pos = this.calculateWinePos(wine, i);
                            this.placeOnObjectPos( element, playerBoardId, pos.x , pos.y, 1500);
                        }
                    }
                } else {
                    for (var i=0; i< this.gamedatas_local.playerTokens.length;i++){
                        var playerToken = this.gamedatas_local.playerTokens[i];
                        if (playerToken.isBuilding){
                            var content = this.getDescriptionWithTokens(this.playerBoard[playerToken.type].label)||'';
                            if (this.playerBoard[playerToken.type].text){
                                content+='<div class="text">'+this.getDescriptionWithTokens(this.playerBoard[playerToken.type].text)+'</div>';
                            }
                            var item = {
                                elementId: 'building_slot_'+playerToken.type,
                                cssClass: 'building_slot building_slot_'+playerToken.type,
                                type: 'building',
                                position: 'position:absolute;',
                                style: '',
                                arg: i,
                                x: i,
                                y: i,
                                phase: 99,
                                arg: playerToken.type,
                                label: content,
                                action: 'build',
                            };
                            var element = dojo.place( this.format_block('jstpl_action_slot', item), playerBoardId, 'last' );
                            var pos = this.playerBoard[playerToken.type];
                            this.placeOnObjectPos( element, playerBoardId, pos.x, pos.y, 1500);
                        }
                    }
                }

                //playerboard labels
                var playerBoardLabels = this.playerBoardLabels;
                for (var key in playerBoardLabels){
                    var cssClass = 'label_playerBoardLabels';
                    if (playerBoardLabels[key].cls){
                        cssClass+=' '+playerBoardLabels[key].cls;
                    }
                    var label = this.getDescriptionWithTokens(playerBoardLabels[key].label)||'';
                    var item = {
                        elementId: 'label_boardLabels_'+key+'_'+id,
                        cssClass: cssClass,
                        style: this.getRotationStyle(playerBoardLabels[key].rot),
                        label: label
                    };

                    var element = dojo.place( this.format_block('jstpl_label', item), playerBoardId, 'last' );
                    var size = dojo.position(element);
                    //position by top not center of div
                    this.placeOnObjectPos( element, playerBoardId, playerBoardLabels[key].x, playerBoardLabels[key].y+size.h/2, 1500);
                    if (label){
                        var tooltip=label;
                        if (playerBoardLabels[key].tooltip){
                            tooltip = '<div class="tooltip_boardLabels_'+key+'">'+this.getDescriptionWithTokens(playerBoardLabels[key].tooltip)+'</div>';
                        }
                        this.addTooltipHtml(element.id, tooltip);
                    }
                }

            }

            //adding empty players for css layout
            if (playerProgr<6){
                for (var i=playerProgr+1;i<=6;i++){
                    dojo.place( this.format_block('jstpl_player_board_empty', {playerboard_row_class:'playerboard_row_other playerboard_row_'+i} ), 'vit_boards_wrapper','last' );
                }
            }

            //labels
            var boardLabels = this.boardLabels[this.gamedatas_local.set];
            for (var key in boardLabels){
                var cssClass = 'label_boardLabels';
                if (boardLabels[key].cls){
                    cssClass+=' '+boardLabels[key].cls;
                }
                var item = {
                    elementId: 'label_boardLabels_'+key,
                    cssClass: cssClass,
                    style: this.getRotationStyle(boardLabels[key].rot),
                    label: this.getDescriptionWithTokens(boardLabels[key].label)||''
                };

                var element = dojo.place( this.format_block('jstpl_label', item), 'board', 'last' );
                var size = dojo.position(element);
                //position by top not center of div
                this.placeOnObjectPos( element, 'board', boardLabels[key].x, boardLabels[key].y+size.h/2, 1500);
                this.addTooltipHtml(item.elementId, item.label);
            }

            //locations
            var locations = this.gamedatas_local.locations;
            var locationPos = this.locations[this.gamedatas_local.set];
            for (var i=0;i<locations.length;i++){
                var cssClass = 'action_slot action_slot_worker ';
                if (locations[i].max>1){
                    cssClass += ' action_slot_worker_big';
                }
                var item = {
                    elementId: 'action_slot_'+locations[i].key,
                    cssClass: cssClass,
                    type: 'action_slot',
                    position: 'position:absolute;',
                    style: '',
                    arg: locations[i].key,
                    x: locations[i].key,
                    y: locations[i].key,
                    phase: locations[i].season,
                    action: locations[i].action,
                    label: ''
                };

                //901 (yoke) on player board!
                if (locations[i].key == 901){
                    for (var playerIdYoke in this.gamedatas_local.players){
                        if (playerIdYoke != this.SOLO_PLAYER_ID){
                            item.elementId = 'action_slot_'+locations[i].key+'_'+playerIdYoke;
                            var playerBoardId = 'playerboard_'+playerIdYoke;
                            var element = dojo.place( this.format_block('jstpl_action_slot', item), playerBoardId, 'last' );
                            this.placeOnObjectPos( element, playerBoardId, locationPos[Number(locations[i].key)].x, locationPos[Number(locations[i].key)].y, 1500);
                            //DEBUG:json info
                            //this.addTooltipHtml(element.id,'<div>'+JSON.stringify(locations[i],null,2)+'</div>');
                        }
                    }
                } else {
                    var element = dojo.place( this.format_block('jstpl_action_slot', item), 'board', 'last' );
                    this.placeOnObjectPos( element, 'board', locationPos[Number(locations[i].key)].x-2, locationPos[Number(locations[i].key)].y-2, 1500);
                    //DEBUG:json info
                    //this.addTooltipHtml(element.id,'<div>'+JSON.stringify(locations[i],null,2)+'</div>');
                }
            }
            //locations not available
            for (var loc in locationPos){
                var available = false;
                for (var i=0;i<locations.length;i++){
                    if (locations[i].key==loc){
                        available = true;
                        break;
                    }
                }
                if (!available){
                    var cssClass = 'disabled_action_slot disabled_action_slot_worker ';
                    var elementId = 'disabled_action_slot_'+loc;
                    var item = {
                        elementId: elementId,
                        cssClass: cssClass,
                        type: 'disabled_action_slot',
                        position: 'position:absolute;',
                        style: '',
                        arg: loc,
                        x: loc,
                        y: loc,
                        phase:0,
                        action: '',
                        label: ''
                    };
                    var element = dojo.place( this.format_block('jstpl_action_slot', item), 'board', 'last' );
                    this.placeOnObjectPos( element, 'board', locationPos[loc].x+1, locationPos[loc].y, 1500);
                    this.addTooltipHtml(elementId,dojo.string.substitute(_('Location not available in a ${players_number}-player game'),{players_number:this.players_number}));
                }
            }

            
            //locations
            var sharedLocationsPos = this.sharedLocations[this.gamedatas_local.set];
            for (var sharedLocationKey in sharedLocationsPos){
                var cssClass = 'shared_location ';
                var shareLocation = sharedLocationsPos[sharedLocationKey];
                var item = {
                    elementId: 'shared_location_'+sharedLocationKey,
                    cssClass: cssClass,
                    type: 'shared_location',
                    position: 'position:absolute;',
                    style: '',
                    arg: sharedLocationKey,
                    x: sharedLocationKey,
                    y: sharedLocationKey,
                    label: ''
                };

                var element = dojo.place( this.format_block('jstpl_shared_location', item), 'board', 'last' );
                this.placeOnObjectPos( element, 'board', shareLocation.x-2, shareLocation.y-2, 1500);
            }

            // Setting board header
            var boardHeader={
                turnLabel: _('Year'),
                turn: gamedatas.turn,
                seasonLabel: _('Season'),
                seasonTr: '',
                season: gamedatas.season
            };

            var turnHeaderHtml = this.format_block('jstpl_turn_header', boardHeader );
            dojo.place( turnHeaderHtml, 'turn_header', 'only' );

            //choose mama papa zone
            var chooseMamaPapaStock = new ebg.stock();
            chooseMamaPapaStock.create( this, $('choose_mama_papa_stock'), 241, 170 );
            chooseMamaPapaStock.jstpl_stock_item= "<div id=\"${id}\" class=\"stockitem\" style=\"top:${top}px;left:${left}px;width:${width}px;height:${height}px;z-index:${position};\"></div>";
            for(var key in gamedatas.mamas){
                chooseMamaPapaStock.addItemType(gamedatas.mamas[key].key, 0, '', 0);
            }
            for(var key in gamedatas.papas){
                chooseMamaPapaStock.addItemType(gamedatas.papas[key].key, 0, '', 0);
            }
            chooseMamaPapaStock.onItemCreate = dojo.hitch( this, 'stockSetupMamaPapaCard' );
            chooseMamaPapaStock.setSelectionMode( 2 );
            chooseMamaPapaStock.setSelectionAppearance('class');
            this.chooseMamaPapaStock = chooseMamaPapaStock;

            //hand zone
            var handZone = new ebg.stock();
            handZone.create( this, $('playerboard_hand_zone_'+this.getThisPlayerId()), 96, 150 );
            handZone.jstpl_stock_item= "<div id=\"${id}\" class=\"stockitem\" style=\"top:${top}px;left:${left}px;width:${width}px;height:${height}px;z-index:${position};\"></div>";
            for(var key in gamedatas.greenCards){
                handZone.addItemType(gamedatas.greenCards[key].key, this.getCardWeight(gamedatas.greenCards[key].key,'greenCard'), '', 0);
            }
            for(var key in gamedatas.yellowCards){
                handZone.addItemType(gamedatas.yellowCards[key].key, this.getCardWeight(gamedatas.yellowCards[key].key,'yellowCard'), '', 0);
            }
            for(var key in gamedatas.purpleCards){
                handZone.addItemType(gamedatas.purpleCards[key].key, this.getCardWeight(gamedatas.purpleCards[key].key,'purpleCard'), '', 0);
            }
            for(var key in gamedatas.blueCards){
                handZone.addItemType(gamedatas.blueCards[key].key, this.getCardWeight(gamedatas.blueCards[key].key,'blueCard'), '', 0);
            }
            handZone.onItemCreate = dojo.hitch( this, 'stockSetupCard' );
            handZone.setSelectionMode( 0);
            handZone.setSelectionAppearance('class');
            this.handZone = handZone;

            //choose Cards stock
            var chooseCardsStock = new ebg.stock();
            chooseCardsStock.create( this, $('choose_cards_stock'), 96, 150 );
            chooseCardsStock.jstpl_stock_item= "<div id=\"${id}\" class=\"stockitem\" style=\"top:${top}px;left:${left}px;width:${width}px;height:${height}px;z-index:${position};\"></div>";
            for(var key in gamedatas.greenCards){
                chooseCardsStock.addItemType(gamedatas.greenCards[key].key, this.getCardWeight(gamedatas.greenCards[key].key,'greenCard'), '', 0);
            }
            for(var key in gamedatas.yellowCards){
                chooseCardsStock.addItemType(gamedatas.yellowCards[key].key, this.getCardWeight(gamedatas.yellowCards[key].key,'yellowCard'), '', 0);
            }
            for(var key in gamedatas.purpleCards){
                chooseCardsStock.addItemType(gamedatas.purpleCards[key].key, this.getCardWeight(gamedatas.purpleCards[key].key,'purpleCard'), '', 0);
            }
            for(var key in gamedatas.blueCards){
                chooseCardsStock.addItemType(gamedatas.blueCards[key].key, this.getCardWeight(gamedatas.blueCards[key].key,'blueCard'), '', 0);
            }
            chooseCardsStock.onItemCreate = dojo.hitch( this, 'stockSetupCard' );
            chooseCardsStock.setSelectionMode( 0);
            chooseCardsStock.setSelectionAppearance('class');
            chooseCardsStock.autowidth = true;
            this.chooseCardsStock = chooseCardsStock;

            this.placeExpandableSection('history_section','history_exandable', '<div id="history_stock"></div>', _("Played cards history"));

            //history played Cards stock
            //no card weight (0) to preserve playorder
            var historyStock = new ebg.stock();
            historyStock.create( this, $('history_stock'), 96, 180 );
            historyStock.jstpl_stock_item= "<div id=\"${id}\" class=\"stockitem\" style=\"top:${top}px;left:${left}px;width:${width}px;height:${height}px;z-index:${position};\"></div>";
            for(var key in gamedatas.greenCards){
                historyStock.addItemType(gamedatas.greenCards[key].key, 0, '', 0);
            }
            for(var key in gamedatas.yellowCards){
                historyStock.addItemType(gamedatas.yellowCards[key].key, 0, '', 0);
            }
            for(var key in gamedatas.purpleCards){
                historyStock.addItemType(gamedatas.purpleCards[key].key, 0, '', 0);
            }
            for(var key in gamedatas.blueCards){
                historyStock.addItemType(gamedatas.blueCards[key].key, 0, '', 0);
            }
            if (gamedatas.automaCards){
                for(var key in gamedatas.automaCards){
                    historyStock.addItemType(gamedatas.automaCards[key].key, 0, '', 0);
                }
            }
            historyStock.onItemCreate = dojo.hitch( this, 'historyStockSetupCard' );
            historyStock.setSelectionMode( 0);
            historyStock.setSelectionAppearance('class');
            historyStock.autowidth = true;
            this.historyStock = historyStock;


            this.historyExpandable = new ebg.expandablesection();
            this.historyExpandable.create(this, "history_exandable");
            //this.historyExpandable.expand();   // show
            this.historyExpandable.collapse(); // hide
            //this.historyExpandable.toggle();   // switch show/hide

            //automa played Cards stock
            //no card weight (0) to preserve playorder
            if ($('automa_cards_stock')){
                var automaCardsStock = new ebg.stock();
                automaCardsStock.create( this, $('automa_cards_stock'), 96, 180 );
                automaCardsStock.jstpl_stock_item= "<div id=\"${id}\" class=\"stockitem\" style=\"top:${top}px;left:${left}px;width:${width}px;height:${height}px;z-index:${position};\"></div>";
                if (gamedatas.automaCards){
                    for(var key in gamedatas.automaCards){
                        automaCardsStock.addItemType(gamedatas.automaCards[key].key, 0, '', 0);
                    }
                }
                automaCardsStock.onItemCreate = dojo.hitch( this, 'automaCardsStockSetupCard' );
                automaCardsStock.setSelectionMode( 0);
                automaCardsStock.setSelectionAppearance('class');
                automaCardsStock.autowidth = true;
                this.automaCardsStock = automaCardsStock;
            }
           
            if (this.gamedatas_local.soloMode>0){
                var htmlSolo = _('<h1>Solo Mode Instructions</h1>');

                var aggressiveYesNo = _('No');
                if (this.gamedatas_local.soloAggressive==1){
                    var aggressiveYesNo = _('Yes');
                }

                htmlSolo += dojo.string.substitute( _('Selected difficulty: ${difficulty}<br/>Years: ${years}<br/>Starting score: ${startScoring}${token_vp}<br/>Target score: ${targetScore}${token_vp}<br/>Aggressive variant: ${aggressiveYesNo}'),
                  {
                    difficulty:this.gamedatas_local.soloParameters.description,
                    years:this.gamedatas_local.soloParameters.turns,
                    startScoring:this.gamedatas_local.soloParameters.startScoring,
                    targetScore:this.gamedatas_local.soloParameters.targetScore,
                    aggressiveYesNo: aggressiveYesNo,
                    token_vp: this.getTokenSymbol('vp')
                    }
                );

                
                htmlSolo += _('<h1>Goal</h1>Have more victory points (VPs) than the Automa at the end of the game, which consists of 7 years (8 in very easy difficulty)');
                
                htmlSolo += _("<h1>Setup</h1>"+
                "Set up your own vineyard as usual.</br>"+
                "1. Choose a player color for the Automa and place that VP marker on the End space on the victory point track.<br/>"+
                "2. Place 1 glass token on each row of the wake-up chart.<br/>"+
                "3. Shuffle the Automa deck and place it next to the wake-up chart.<br/>"+
                "4. Remove any visitor cards that only give you a benefit if another player takes an action. Alternatively you can keep them in the deck and just redraw when you draw one of them.");

                htmlSolo += _("<h1>Gameplay</h1>"+
                "The game plays out season by season using the 2-player action spaces (the far-left space on each action).");

                htmlSolo += _("<h1>Wake-Up Chart</h1>"+
                "Place your rooster token as usual except that you can only place it on a space where there is a glass token. Remove the glass token and place it to the side of your player mat. This becomes a bonus action token. Note: The \"Organizer\" summer visitor card action can not move your rooster to row 7.");

                htmlSolo += _("<h1>Automa Cards</h1>"+
                "At the beginning of each season where you are to place workers, first draw 1 Automa card from the deck. Then place either 0, 1, 2, or 3 Automa workers on action spaces on the board in the current season as indicated on the Automa card. To maintain compatibility with the extended board from the Tuscany expansion, Automa cards have the four seasonal colors. In Viticulture, yellow and green correspond to summer and red and blue correspond to winter. Additionally, some actions are specific to expansions from Tuscany. These are marked with a \"T\" on the Automa card and they should be ignored if you're not using the corresponding expansion. Automa workers do not actually take any actions, nor do they gain bonuses. They simply block action spaces to simulate an opponent.");

                htmlSolo += _("<h1>Bonus Action Tokens</h1>"+
                "Whenever you choose your wake-up slot at the beginning of the year, remove the glass token from the slot and place it to  the side of your vineyard mat. You may accumulate these Bonus Action Tokens from year to year. You may spend 1 of those tokens per turn when you place 1 of your workers on the board. Discard a Bonus Action Token and in addition to taking the action, also gain the bonus action of that action. If you do, you may take the action and the bonus in any order.");

                htmlSolo += _("<h1>Year End</h1>"+
                "In addition to the usual end-of-year upkeep, retrieve all Automa workers from the board.");

                htmlSolo += this.getDescriptionWithTokens(_("<h1>Difficulty Levels</h1>"+
                "You can play against the Automa on five different difficulty levels: <ul><li>Very Easy: Add an eighth year. In this eighth year you can choose any wake-up chart row and you get a bonus action token.</li> <li>Easy: Start at ${token_vp3}.</li> <li>Normal: As described in the rules above.</li> <li>Hard: Keep drawing Automa cards and place workers until the Automa has placed at least 2 workers in the current season. Place workers on all the spots listed on the extra cards, but don't place on actions where there's already a worker. If the Automa places its sixth worker, it doesn't place any more. If you run out of Automa cards, then reshuffle the deck. </li> <li>Very Hard: Same as the hard difficulty level, but the Automa starts at 23 ${token_vp} .</li></ul>"));
                
                htmlSolo += _("<h1>Aggressive Variant</h1>"+
                "This \aggressive variant\" can be played at easy, normal, and hard difficulty levels. At the beginning of each year place the Automa VP marker at the position on the VP track listed in the table below, and make sure that you end the year ahead of this number.");

                htmlSolo += "<table class='vit_solovariantscoring'><tr><td>"+_('Year')+"</td><td>1</td><td>2</td><td>3</td><td>4</td><td>5</td><td>6</td><td>7</td></tr>";
                htmlSolo += "<tr><td> </td><td>-1</td><td>0</td><td>1</td><td>4</td><td>8</td><td>13</td><td>20</td></tr></table>";

                dojo.place( htmlSolo, 'soloModeInstructions','only');
                this.show('soloModeInstructions');
            }

            this.setupCustomPreferences();

            this.updateTokens(false);
            this.updateDecks(false);
            this.updatePlayerFlags(false);
            this.updateVines(false);
            this.updateGrapesWines(false);
            this.updateMamasPapas(false);
            this.updateScoreAndResidualPayment(false);
            this.updateHand(false);
            this.updateHistory(false);
            this.updateAutomaCards(false);

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

            if (args && args.args){
                this.applyGameDatas(args.args);
                dojo.setAttr("vit_game", "data-season", args.args.season);
            }

            dojo.setAttr("vit_game", "data-state", stateName);

            var playerData = this.getPlayerData(this.getThisPlayerId());
            var gamedatas = this.gamedatas_local;
            var playerId = this.getThisPlayerId();
            if (this.last_server_state != this.gamedatas_local.previousLastServerState){
                this.clientStateArgs = {};
                this.gamedatas_local.previousLastServerState = this.last_server_state;
                this.clearInteractiveItems();
                this.hide('choose_players_section');
            }

            this.hide('choose_mama_papa_section');
            this.hide('choose_papa_option_section');
            this.hide('play_card_section');
            this.hide('choose_cards_section');
            this.show('turn_header');
            this.updatePlayerEndGame();
            this.updatePreviewTokens();
            this.updatePlayCard();

            //custom state description
            if (args && args.args && args.args.customStateDescription && stateName.indexOf('client')==-1) {
                if (this.gamedatas.gamestate[args.args.customStateDescription]){
                    this.gamedatas.gamestate.description = this.gamedatas.gamestate[args.args.customStateDescription];
                }
                if ( this.gamedatas.gamestate[args.args.customStateDescription+'myturn']){
                    this.gamedatas.gamestate.descriptionmyturn = this.gamedatas.gamestate[args.args.customStateDescription+'myturn'];
                }
                this.updatePageTitle();
            }

            switch( stateName )
            {

                case 'mamaPapaChoose':
                    this.show('choose_mama_papa_section');
                    this.hide('turn_header');
                    if (gamedatas.chooseMamaPapa[playerId] && this.chooseMamaPapaStock.count()==0){
                        var chooseMamaPapa = gamedatas.chooseMamaPapa[playerId];
                        for( var i=0;i<chooseMamaPapa.mama.length;i++){
                            this.chooseMamaPapaStock.addToStockWithId(Number(chooseMamaPapa.mama[i]), 'choose_mama_papa_'+chooseMamaPapa.mama[i]);
                        }
                        if (playerData['mama']){
                            this.chooseMamaPapaStock.addToStockWithId(Number(playerData['mama']), 'choose_mama_papa_'+playerData['mama']);
                        }
                        if (playerData['papa']){
                            this.chooseMamaPapaStock.addToStockWithId(Number(playerData['papa']), 'choose_mama_papa_'+playerData['papa']);
                        }
                        for( var i=0;i<chooseMamaPapa.papa.length;i++){
                            this.chooseMamaPapaStock.addToStockWithId(Number(chooseMamaPapa.papa[i]), 'choose_mama_papa_'+chooseMamaPapa.papa[i]);
                        }
                        this.disconnect( this.chooseMamaPapaStock,'onChangeSelection');
                        this.connect( this.chooseMamaPapaStock, 'onChangeSelection', 'onChooseMamaPapaStockSelection' );
                    }
                    if( this.isCurrentPlayerActive() ){
                        dojo.addClass('choose_mama_papa_section','stock_active_slot');
                        dojo.removeClass('choose_mama_papa_section','stock_confirmed_selection');
                        dojo.removeClass('choose_mama_papa_section','stock_confirm_selection');
                        this.chooseMamaPapaStock.setSelectionMode(2);
                    } else {
                        //recreate selection
                        dojo.removeClass('choose_mama_papa_section','stock_active_slot');
                        dojo.addClass('choose_mama_papa_section','stock_confirmed_selection');
                        dojo.removeClass('choose_mama_papa_section','stock_confirm_selection');
                        //disable user selection
                        this.chooseMamaPapaStock.setSelectionMode(0);
                        //select cards
                        if (playerData['mama']>0){
                            this.chooseMamaPapaStock.selectItem('choose_mama_papa_'+playerData['mama']);
                        }
                        if (playerData['papa']>0){
                            this.chooseMamaPapaStock.selectItem('choose_mama_papa_'+playerData['papa']);
                        }
                    }
                    break;

                case 'client_mamaPapaChoose_confirm':
                    this.hide('turn_header');
                    this.show('choose_mama_papa_section');
                    dojo.removeClass('choose_mama_papa_section','stock_confirmed_selection');
                    dojo.addClass('choose_mama_papa_section','stock_confirm_selection');
                    if(!this.isCurrentPlayerActive() ){
                        this.chooseMamaPapaStock.setSelectionMode(0);
                    }

                    break;

                case 'papaOptionChoose':
                    this.hide('turn_header');
                    this.show('choose_papa_option_section');
                    dojo.place( this.format_block('jstpl_choose_papa_option_section', {
                        mama: this.getHtmlMamaPapaCard(playerData.mama, 'mama_card','mama_card','medium'),
                        papa: this.getHtmlMamaPapaCard(playerData.papa, 'papa_card','papa_card','medium'),
                    } ), 'choose_papa_option_section','only' );
                    this.addTooltipHtml('mama_card',this.getTooltipHtmlMamaPapaCard(playerData.mama));
                    this.addTooltipHtml('papa_card',this.getTooltipHtmlMamaPapaCard(playerData.papa));
                    break;

                case 'client_choosePapaOption_confirm':
                    this.hide('turn_header');
                    this.show('choose_papa_option_section');
                    break;

                case 'springChooseWakeup':
                case 'client_springChooseWakeup_confirm':
                case 'client_springChooseWakeupChooseCard_confirm':
                    this.queryAndDisconnectEvent('#board .active_slot','click');
                    this.queryAndDisconnectEvent('#playerboard_row .active_slot','click');
                    this.queryAndRemoveClass('#board .active_slot','active_slot');
                    this.queryAndRemoveClass('#playerboard_row .active_slot','active_slot');
                    if( this.isCurrentPlayerActive() ){
                        for (var i = 0;i<this.gamedatas_local.activeWakeupOrder.length;i++){
                            this.queryAndAddEvent('#wakeupOrder_slot_'+this.gamedatas_local.activeWakeupOrder[i],'click','onWakeupOrderSlotClick');
                            this.queryAndAddClass('#wakeupOrder_slot_'+this.gamedatas_local.activeWakeupOrder[i],'active_slot');
                        }
                    }
                    break;

                case 'seasonWorkers':
                    this.queryAndDisconnectEvent('#board .active_slot','click');
                    this.queryAndDisconnectEvent('#playerboard_row .active_slot','click');
                    this.queryAndRemoveClass('#board .active_slot','active_slot');
                    this.queryAndRemoveClass('#playerboard_row .active_slot','active_slot');
                    this.queryAndSetAttribute('#playerboard_row .active_slot','data-worker','0');
                    if( this.isCurrentPlayerActive() ){
                        this.enableBoardLocations();
                    }
                    break;

                case 'plant':
                    this.clientStateArgs = {};
                    this.clientStateArgs.action = 'plant';
                    this.clientStateArgs.refuseButton = true;
                    if( this.isCurrentPlayerActive() ){
                        this.enableActionPlant(this.gamedatas_local.checkStructures, this.gamedatas_local.checkLimit, null, false);
                    }
                    break;

                case 'allPlant':
                    this.clientStateArgs = {};
                    this.clientStateArgs.action = 'allPlant';
                    this.clientStateArgs.refuseButton = true;
                    if( this.isCurrentPlayerActive() ){
                        this.enableActionPlant(true, true, null, false);
                    }
                    break;

                case 'allGiveCard':
                    this.clientStateArgs = {};
                    this.clientStateArgs.action = 'allGiveCard';
                    if( this.isCurrentPlayerActive() ){
                        this.enableActionGiveCards(this.gamedatas_local.minGiveCard, this.gamedatas_local.maxGiveCard, this.gamedatas_local.playerIdGive, this.gamedatas_local.playerNameGive, this.gamedatas_local.cardTypes);
                    }
                    break;

                case 'allBuild':
                    this.clientStateArgs = {};
                    this.clientStateArgs.action = 'allBuild';
                    this.clientStateArgs.refuseButton = true;
                    if( this.isCurrentPlayerActive() ){
                        this.enableActionBuild(this.gamedatas_local.discount,this.getDescriptionWithTokens(_('Build a structure at a ${token_lira} discount'),false,{token_lira:this.getTokenSymbol('lira'+this.gamedatas_local.discount)}),null,false);
                    }
                    break;

                case 'allChoose':
                    this.clientStateArgs = {};
                    this.clientStateArgs.action = 'allChoose';
                    this.clientStateArgs.refuseButton = true;
                    if( this.isCurrentPlayerActive() ){
                        //nothing... buttons create in onUpdateActionButtons
                    }
                    break;

                case 'makeWine':
                    this.clientStateArgs = {};
                    this.clientStateArgs.action = 'makeWine';
                    this.clientStateArgs.refuseButton = true;
                    if( this.isCurrentPlayerActive() ){
                        this.enableActionMakeWine(this.gamedatas_local.checkStructures, false);
                    }
                    break;

                case 'playYellowCard':
                    this.clientStateArgs = {};
                    this.clientStateArgs.action = 'playYellowCard';
                    this.clientStateArgs.refuseButton = true;
                    if( this.isCurrentPlayerActive() ){
                        this.enableActionPlayYellowCard();
                    }
                    break;

                case 'playBlueCard':
                    this.clientStateArgs = {};
                    this.clientStateArgs.action = 'playBlueCard';
                    this.clientStateArgs.refuseButton = true;
                    if( this.isCurrentPlayerActive() ){
                        this.enableActionPlayBlueCard();
                    }
                    break;

                case 'fillOrder':
                    this.clientStateArgs = {};
                    this.clientStateArgs.action = 'fillOrder';
                    this.clientStateArgs.refuseButton = true;
                    if( this.isCurrentPlayerActive() ){
                        this.enableActionFillOrder();
                    }
                    break;

                case 'playCardSecondOption':
                    this.clientStateArgs = {};
                    this.clientStateArgs.action = 'playCardSecondOption';
                    this.clientStateArgs.refuseButton = true;
                    this.clientStateArgs.visitorCardSecondOption = true;
                    this.clientStateArgs.visitorCardId = Number(this.gamedatas_local.cardSecondOption.visitorCardId);
                    this.clientStateArgs.visitorCardKey = Number(this.gamedatas_local.cardSecondOption.visitorCardKey);
                    this.clientStateArgs.vpPrice = Number(this.gamedatas_local.cardSecondOption.vpPrice);
                    if( this.isCurrentPlayerActive() ){
                        var cardType = this.getCardType(this.clientStateArgs.visitorCardKey);
                        if (cardType == 'yellowCard'){
                            this.processPlayYellowCardEffects();
                        } else if (cardType == 'blueCard'){
                            this.processPlayBlueCardEffects();
                        }
                    }
                    break;

                case 'chooseCards':
                    this.clientStateArgs = {};
                    this.clientStateArgs.action = 'chooseCards';
                    this.enableActionChooseCards();
                    break;

                case 'client_chooseCards_choose':
                case 'client_chooseCards_confirm':
                    this.show('choose_cards_section');
                    break;

                case 'chooseOptions':
                    this.clientStateArgs = {};
                    this.clientStateArgs.action = 'chooseOptions';
                    break;

                case 'takeActionPrev':
                    this.clientStateArgs = {};
                    this.clientStateArgs.action = 'takeActionPrev';
                    this.enableActionSelectPreviousLocation();
                    break;

                case 'executeLocation':
                    this.clientStateArgs = {};
                    this.clientStateArgs.action = 'executeLocation';
                    this.clientStateArgs.location = this.gamedatas_local.location;
                    this.clientStateArgs.refuseButton = true;
                    if( this.isCurrentPlayerActive() ){
                        this.processActionSlot();
                    }
                    break;

                case 'discardCards':
                    this.clientStateArgs = {};
                    this.clientStateArgs.action = 'discardCards';
                    var playerData = this.getPlayerData(this.getThisPlayerId());
                    var cards = Number(playerData.greenCard)+Number(playerData.yellowCard)+Number(playerData.blueCard)+Number(playerData.purpleCard);
                    if( this.isCurrentPlayerActive() ){
                        this.enableActionDiscardCards(cards-7, cards-7,null, null, false ,'');
                    }
                    break;

                case 'discardVines':
                    this.clientStateArgs = {};
                    this.clientStateArgs.action = 'discardVines';
                    if( this.isCurrentPlayerActive() ){
                        this.enableActionDiscardCards(this.gamedatas_local.minCards,this.gamedatas_local.maxCards,  ['greenCard'], 0, false ,'');
                    }
                    break;

                case 'client_selectPlayers_choose':
                case 'client_selectPlayers_confirm':
                    this.show('choose_players_section');
                    break;

            case 'dummmy':
                break;
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
            var me = this;

            //update gamedatas only on game state that are not client state
            if (stateName.indexOf('client_')!=0 && args){
                this.applyGameDatas(args);
            }

            if( this.isCurrentPlayerActive() )
            {
                var playerId = this.getThisPlayerId();
                var playerData = this.getPlayerData(playerId);
                var cancelDisabled = false;
                var refuseButton = false;
                var confirmDisabled = false;
                var passButton = false;
                var translated;

                switch( stateName )
                {
                    case 'mamaPapaChoose':
                    case 'client_mamaPapaChoose_choose':
                    case 'client_mamaPapaChoose_confirm':
                        cancelDisabled = true;
                        break;

                    case 'papaOptionChoose':
                        confirmDisabled = true;
                        cancelDisabled = true;
                        var papaInfo = this.gamedatas_local.papas[Number(playerData.papa)];
                        var choiceBonus = this.getPapaChoiceDescription(papaInfo.choice_bonus, playerId);
                        this.addActionButton('papaChoiceBonus', choiceBonus, 'onPapaChoiceBonusClick');
                        this.addActionButton('papaChoiceLira', this.getTokenSymbol('lira'+papaInfo.choice_lira), 'onPapaChoiceLiraClick');
                        break;

                    case 'springChooseWakeup':
                        cancelDisabled = true;
                        break;

                    case 'client_springChooseWakeupChooseCard_confirm':
                        confirmDisabled = true;
                        this.addActionButton('wakeupChooseYellowCard', this.getTokenSymbol('yellowCardPlus'), 'onWakeupChooseYellowCardClick');
                        this.addActionButton('wakeupChooseBlueCard', this.getTokenSymbol('blueCardPlus'), 'onWakeupChooseBlueCardClick');
                        break;

                    case 'client_changeWakeupChooseCard_confirm':
                        confirmDisabled = true;
                        this.addActionButton('wakeupChooseYellowCard', this.getTokenSymbol('yellowCardPlus'), 'onChangeWakeupChooseYellowCardClick');
                        this.addActionButton('wakeupChooseBlueCard', this.getTokenSymbol('blueCardPlus'), 'onChangeWakeupChooseBlueCardClick');
                        break;

                    case 'seasonWorkers':
                        confirmDisabled = true;
                        passButton = true;
                        cancelDisabled = true;
                        break;

                    case 'fallChooseCard':
                        if (this.gamedatas_local.secondCardChoice == '1'){
                            this.addActionButton('fallChooseTwoYellowCard', this.getTokenSymbol('yellowCardPlus')+ this.getTokenSymbol('yellowCardPlus'), 'onFallChooseTwoYellowCardClick');
                            this.addActionButton('fallChooseYellowCardAndBlueCard', this.getTokenSymbol('yellowCardPlus')+this.getTokenSymbol('blueCardPlus'), 'onFallChooseYellowCardAndBlueCardClick');
                            this.addActionButton('fallChooseTwoBlueCard', this.getTokenSymbol('blueCardPlus')+this.getTokenSymbol('blueCardPlus'), 'onFallChooseTwoBlueCardClick');
                        } else {
                            this.addActionButton('fallChooseYellowCard', this.getTokenSymbol('yellowCardPlus'), 'onFallChooseYellowCardClick');
                            this.addActionButton('fallChooseBlueCard', this.getTokenSymbol('blueCardPlus'), 'onFallChooseBlueCardClick');
                        }
                        cancelDisabled = true;
                        break;

                    case 'chooseVisitorCardDraw':
                        this.addActionButton('chooseVisitorCardDrawYellowCard', this.getTokenSymbol('yellowCardPlus'), 'onChooseVisitorCardDrawYellowCard');
                        this.addActionButton('chooseVisitorCardDrawGreenCard', this.getTokenSymbol('greenCardPlus'), 'onChooseVisitorCardDrawGreenCard');
                        cancelDisabled = true;
                        break;

                    case 'client_buildStructure_choose':
                        if (this.clientStateArgs.canBuildNothing){
                            this.addActionButton('proceedWithoutBuildingButton',_('Proceed without building'), 'onProceedWithoutBuildingButtonClick');
                        }
                        break;

                    case 'client_makeWine_choose':
                        if (this.clientStateArgs.canProceedWithoutMakingWine){
                            this.addActionButton('proceedWithoutMakingWineButton',_('Proceed without making wine'), 'onProceedWithoutMakingWineButtonClick');
                        }
                        break;  

                    case 'allChoose':

                        var buttonOkLabel=_('Ok');
                        if (this.gamedatas_local.actionProgress && this.gamedatas_local.actionProgress.card_key){
                            //631: //Swindler
                            //Each opponent may give you ${token_lira2}. For each opponent who does not, gain ${token_vp1}.
                            //**special**
                            if (this.gamedatas_local.actionProgress.card_key==631){
                                buttonOkLabel=this.getDescriptionWithTokens(_('Give ${token_lira2}'));
                            }
                            //621: //Banker
                            //Gain ${token_lira5}. Each opponent may lose ${token_vp1} to gain ${token_lira3}.
                            //**special**
                            if (this.gamedatas_local.actionProgress.card_key==621){
                                buttonOkLabel=this.getDescriptionWithTokens(_('Lose ${token_vp1}'));
                            }
                            //825: //Motivator
                            //Each player may retrieve their grande worker. Gain ${token_vp1} for each opponent who does this.
                            //**special**
                            if (this.gamedatas_local.actionProgress.card_key==825){
                                buttonOkLabel=this.getDescriptionWithTokens(_('Retrieve grande worker'));
                            }
                            //838: //Guest Speaker
                            //All players may pay ${token_lira1} to train ${token_worker}. Gain ${token_vp1} for each opponent who does this.
                            //**special**
                            if (this.gamedatas_local.actionProgress.card_key==838){
                                buttonOkLabel=this.getDescriptionWithTokens(_('Pay ${token_lira1}'));
                            }
                        }

                        this.addActionButton('chooseOk', buttonOkLabel, 'onChooseOkClick');
                        refuseButton = true;
                        cancelDisabled = true;
                        break;

                    case 'client_uprootHarvest_choose':
                        if (playerData.vine1.length+playerData.vine2.length+playerData.vine3.length>0){
                            this.addActionButton('chooseUproot', _('Uproot a vine'), 'onChooseUprootClick');
                            if (playerData.field1==1||playerData.field2==1||playerData.field3==1){
                                this.addActionButton('chooseHarvest', _('Harvest a field'), 'onChooseHarvestClick');
                            }
                        }
                        break;

                    case 'chooseOptions':
                        cancelDisabled = true;
                        this.gamedatas_local.choiceText1 = this.getDescriptionWithTokens(_('Lose ${token_vp1}'));
                        this.gamedatas_local.choiceText2 = this.getDescriptionWithTokens(_('Give 2 ${token_anyCard}'));
                        this.gamedatas_local.choiceText3 = this.getDescriptionWithTokens(_('Give ${token_lira3}'));
                        if (this.gamedatas_local.choice1){
                            this.addActionButton('chooseOptions1',this.gamedatas_local.choiceText1 , function(evt){me.onChooseOptionsChoose(evt,1);});
                        }
                        if (this.gamedatas_local.choice2){
                            this.addActionButton('chooseOptions2', this.gamedatas_local.choiceText2, function(evt){me.onChooseOptionsChoose(evt,2);});
                        }
                        if (this.gamedatas_local.choice3){
                            this.addActionButton('chooseOptions3', this.gamedatas_local.choiceText3, function(evt){me.onChooseOptionsChoose(evt,3);});
                        }
                        break;

                    case 'client_playCard_choose':
                        if (this.clientStateArgs.choice1){
                            this.addActionButton('choice1Button',this.clientStateArgs.choice1, function(evt){me.onPlayCardOptionChoose(evt,1);});
                        }
                        if (this.clientStateArgs.choice2){
                            this.addActionButton('choice2Button',this.clientStateArgs.choice2, function(evt){me.onPlayCardOptionChoose(evt,2);});
                        }
                        if (this.clientStateArgs.choice3){
                            this.addActionButton('choice3Button',this.clientStateArgs.choice3, function(evt){me.onPlayCardOptionChoose(evt,3);});
                        }
                        if (this.clientStateArgs.visitorCardSecondOption){
                            cancelDisabled = true;
                        }
                        break;

                    case 'takeActionPrev':
                        this.addActionButton('cancelActionButton',_('Cancel card choice'), 'onCancelActionButtonClick');
                        cancelDisabled = true;
                        break;

                    case 'playCardSecondOption':
                        cancelDisabled = true;
                        break;

                    case 'client_playYellowCard_choose':
                        if (this.last_server_state.name=='playYellowCard'){
                            cancelDisabled = true;
                        }
                        break;

                    case 'client_playBlueCard_choose':
                        if (this.last_server_state.name=='playBlueCard'){
                            cancelDisabled = true;
                        }
                        break;

                    case 'client_fillOrder_choose':
                        if (this.last_server_state.name=='fillOrder'){
                            cancelDisabled = true;
                        }
                        break;

                    case 'client_plant_choose':
                        if (this.last_server_state.name=='plant'){
                            cancelDisabled = true;
                        }
                        if (this.clientStateArgs.canProceedWithoutPlanting){
                            this.addActionButton('proceedWithoutMakingPlantingButton',_('Proceed without planting'), 'onProceedWithoutPlantingButtonClick');
                        }
                        break;

                    case 'allGiveCard':
                    case 'client_allGiveCard_choose':
                    case 'client_allGiveCard_confirm':
                        if (this.gamedatas_local.minGiveCard == 0){
                            refuseButton = true;
                        }
                        break;

                    case 'client_cardChooseOption_choose':
                        if (this.clientStateArgs.chooseOptionText1){
                            this.addActionButton('chooseOption1Button',this.clientStateArgs.chooseOptionText1, function(evt){me.onCardChooseOptionClick(evt,1);});
                        }
                        if (this.clientStateArgs.chooseOptionText2){
                            this.addActionButton('chooseOption2Button',this.clientStateArgs.chooseOptionText2, function(evt){me.onCardChooseOptionClick(evt,2);});
                        }
                        refuseButton = false;
                        confirmDisabled = true;
                        break;

                    case 'discardCards':
                        cancelDisabled = true;
                        break;

                    case 'chooseCards':
                        cancelDisabled = true;
                        break;

                    case 'client_ageWine_nowine':
                        this.addActionButton('proceedWithoutAgeingButton',_('Proceed without ageing wine'), 'onProceedWithoutAgeingButtonClick');

                }

                //confirm button
                if (confirmDisabled==false && this.endsWith(stateName,'confirm') && this.clientStateArgs && !this.clientStateArgs.confirmDisabled){
                    var translated = _("Confirm");
                    if (this.clientStateArgs && this.clientStateArgs.confirmButtonText){
                        translated = this.clientStateArgs.confirmButtonText;
                    }
                    if (this.clientStateArgs.askConfirm){
                        this.addActionButton('button_confirm', translated, 'onButtonConfirmAskConfirmClick');
                    } else {
                        this.addActionButton('button_confirm', translated, 'ajaxClientStateHandler');
                    }
                }

                //refuse button
                if (refuseButton || this.clientStateArgs.refuseButton){
                    var translated = _("Refuse");
                    this.addActionButton('button_refuse', translated, 'onRefuseClick',null,false,'red');
                }

                //pass button
                if (passButton){
                    if (this.checkPassActionEnabled()){
                        var translated = _("Pass");
                        this.addActionButton('button_pass', translated, 'onPassClick',null,false,'red');
                        this.addTooltipHtml( 'button_pass', dojo.string.substitute( _("Pass and end of season"),{}),300);    
                    } else {
                        var translated = _("Pass Disabled");
                        this.addActionButton('button_pass', translated, 'onPassClick',null,false,'red');
                        $('button_pass').setAttribute('disabled','disabled');
                        dojo.addClass('button_pass','disabled');
                        this.addTooltipHtml( 'button_pass', dojo.string.substitute( _("Pass and end of season disabled"),{}),300);  
                    }
                    
                }

                //cancel button
                //???: this.on_client_state
                if (!$('button_cancel') && !cancelDisabled && !this.clientStateArgs.cancelDisabled) {
                      this.addActionButton('button_cancel', _('Cancel'), dojo.hitch(this, function() {
                            this.cancelLocalStateEffects();
                      }));
                }
            }
        },

        ///////////////////////////////////////////////////
        //// Utility methods

        /*

            Here, you can defines some utility methods that you can use everywhere in your javascript
            script.

        */

        setupCustomPreferences: function(){
            var preferencesSection = $('vit_preferences');
            if (!preferencesSection){
                return;
            }
            var preferences = dojo.query('#vit_preferences .vit_preference');
            for (var i = 0;i<preferences.length;i++){
                var pref = Number(preferences[i].getAttribute('data-preference'));
                var value = Number(preferences[i].getAttribute('value'));
                //set checked
                if (this.prefs[pref].value == value){
                    preferences[i].checked = true;
                }
                //add event listener
                this.queryAndAddEvent(preferences[i],'change','onPreferenceChanged');

            }

        },

        onPreferenceChanged: function(evt){
            var element = evt.currentTarget;
            var pref = Number(element.getAttribute('data-preference'));
            var value = Number(element.getAttribute('data-value-unchecked'));
            if (element.checked){
                value = Number(element.getAttribute('value'));
            }
            this.updatePreference(pref, value);
            
        },

        updatePreference: function(prefId, newValue) {
            // Select preference value in control:
            dojo.query('#preference_control_' + prefId + ' > option[value="' + newValue
            // Also select fontrol to fix a BGA framework bug:
                + '"], #preference_fontrol_' + prefId + ' > option[value="' + newValue
                + '"]').forEach((value) => dojo.attr(value, 'selected', true));
            // Generate change event on control to trigger callbacks:
            const newEvt = document.createEvent('HTMLEvents');
            newEvt.initEvent('change', false, true);
            $('preference_control_' + prefId).dispatchEvent(newEvt);
        },
        
        /**
         * checks if pass action enable
         */
        checkPassActionEnabled: function(){
            //pass other seasons
            if (this.gamedatas_local.season != 4){
                return true;
            }
            //winter season, based on preference 101
            if (this.gamedatas_local.season == 4 && this.prefs[101].value == 1){
                return true;
            }
            return false;
        },

        /**
         * returns array of player id ordered like the side bar
         */
        getPlayerIdOrdered: function(){
            var result = [];
            var query = dojo.query('.player_board_content');

            //if query returns data and the same size of players
            if (query && query.length == this.gamedatas_local.playersNumber){
                for (var i = 0; i< query.length;i++){
                    var elementId = query[i].id;
                    var playerId = elementId.substr(13); 
                    if (this.gamedatas_local.players[playerId]){
                        result.push(playerId);
                    }
                }

            } 

            //if something goes wrong... get ids
            if (result.length < this.gamedatas_local.playersNumber){
                result = [];
                for (var id in this.gamedatas_local.players){
                    result.push(id);
                }
            }

            if (this.gamedatas_local.soloMode>0 && result.length==1){
                result.push(this.SOLO_PLAYER_ID);
            }

            return result;
        },

        updatePlayerEndGame: function(){
            if (this.gamedatas_local.pceg != null && Object.keys(this.gamedatas_local.pceg).length>0){
                var playersHtml='';
                var endGameType;
                var playerData = this.getPlayerData(this.getThisPlayerId());

                for (var plKey in this.gamedatas_local.pceg){
                    playersHtml+=this.format_block('jstpl_last_turn_player',this.gamedatas_local.pceg[plKey]);
                    endGameType = this.gamedatas_local.pceg[plKey].endGameType;
                }

                if (this.gamedatas_local.soloMode==0){
                    dojo.place( this.format_block('jstpl_last_turn', {
                        labelEndTurn: this.getDescriptionWithTokens(_('Warning, this is the last year!')),
                        labelPlayers: this.getDescriptionWithTokens(_('Players with 20${token_vp} or more:')),
                        playersHtml: playersHtml
                    } ), 'last_turn_section','only' );
                } else {
                    if (endGameType==1){
                        //player wins at end
                        dojo.place( this.format_block('jstpl_last_turn', {
                            labelEndTurn: this.getDescriptionWithTokens(_('Warning, this is the last year!')),
                            labelPlayers: this.getDescriptionWithTokens(_('You are winning: ')), 
                            playersHtml: playersHtml
                        }), 'last_turn_section','only' );
                    }
                    if (endGameType==2){
                        //automa wins at end
                        dojo.place( this.format_block('jstpl_last_turn', {
                            labelEndTurn: this.getDescriptionWithTokens(_('Warning, this is the last year!')),
                            labelPlayers: this.getDescriptionWithTokens(_('You are losing, you must earn more points than the automa to win:')),
                            playersHtml: playersHtml
                        } ), 'last_turn_section','only' );
                    }
                    if (endGameType==3){
                        //player losing in aggressive
                        dojo.place( this.format_block('jstpl_last_turn', {
                            labelEndTurn: this.getDescriptionWithTokens(_('Warning, this could be the last year.')),
                            labelPlayers: this.getDescriptionWithTokens(
                                dojo.string.substitute(_('You must earn more points than the automa to proceed: ${player} '), 
                                {player: this.format_block('jstpl_last_turn_player',playerData)})),
                            playersHtml: playersHtml
                        } ), 'last_turn_section','only' );
                    }

                }

                this.show('last_turn_section');
            } else if (this.gamedatas_local.gameEnd == 1){
                var playersHtml='';
                dojo.place( this.format_block('jstpl_last_turn', {
                    labelEndTurn: this.getDescriptionWithTokens(_('Warning, this is the last year!')),
                    labelPlayers: this.getDescriptionWithTokens(_('A player reached 20${token_vp} or more')),
                    playersHtml: ''
                } ), 'last_turn_section','only' );
                this.show('last_turn_section');
            } else {
                this.hide('last_turn_section');
            }

        },

        setupPreference: function () {
            // Extract the ID and value from the UI control
            var _this = this;
            function onchange(e) {
              var match = e.target.id.match(/^preference_[cf]ontrol_(\d+)$/);
              if (!match) {
                return;
              }
              var prefId = +match[1];
              var prefValue = +e.target.value;
              _this.prefs[prefId].value = prefValue;
              _this.onPreferenceChange(prefId, prefValue);
            }
            
            // Call onPreferenceChange() when any value changes
            dojo.query(".preference_control").connect("onchange", onchange);
            
            // Call onPreferenceChange() now
            dojo.forEach(
              dojo.query("#ingame_menu_content .preference_control"),
              function (el) {
                onchange({ target: el });
              }
            );
        },

        onPreferenceChange: function (prefId, prefValue) {
            console.log("Preference changed", prefId, prefValue);
            if (prefId==100){
                if (prefValue==1){
                    dojo.removeClass(document.documentElement,'vit_tokens_with_value');
                    dojo.addClass(document.documentElement,'vit_tokens_without_value');
                } else {
                    dojo.addClass(document.documentElement,'vit_tokens_with_value');
                    dojo.removeClass(document.documentElement,'vit_tokens_without_value');
                }
            }
        },

        updatePreviewTokens: function(){
            var playerBoardId = 'playerboard_'+this.getThisPlayerId();
            this.queryAndDestroy('.preview-token');
            this.queryAndRemoveClass('#'+playerBoardId+' .preview-token-origin','preview-token-origin');
            if (!this.clientStateArgs.previewTokens){
                return;
            }
            for (var key in this.clientStateArgs.previewTokens){
                var selectedPos = [];
                var zIndex = 900;
                var playerId = this.getThisPlayerId();
                var previewToken = this.clientStateArgs.previewTokens[key];
                var elementId = 'preview-token_'+key;

                if (this.clientStateArgs && previewToken ){

                    var elementPlayerBoard = dojo.query('#'+playerBoardId+' .component.'+previewToken.t);
                    if (elementPlayerBoard.length==1){
                        dojo.addClass(elementPlayerBoard[0],'preview-token-origin');
                    }

                    var item = {
                        elementId: elementId,
                        cssClass: 'preview-token',
                        type: previewToken.t+' '+previewToken.t+'_'+this.getThisPlayerColor(),
                        position: 'position:absolute;',
                        style: 'z-index:'+zIndex+';',
                        arg: previewToken.t,
                        x: 0,
                        y: 0,
                        id: elementId,
                        tooltip: this.getTokenDescription(previewToken.t)
                    };
                    if (previewToken.e){
                        dojo.place( this.format_block('jstpl_token', item), previewToken.e, 'last' );
                        var actionSlotType = $(previewToken.e).getAttribute('data-type');
                        var x = 0;
                        var y = 0;
                        if (actionSlotType && this.actionSlots[this.gamedatas_local.set][actionSlotType]){
                            x = this.actionSlots[this.gamedatas_local.set][actionSlotType].offsetX||0;
                            y = this.actionSlots[this.gamedatas_local.set][actionSlotType].offsetY||0;
                        } else if (previewToken.t.indexOf('worker')==0){
                            y = -13;
                        }
                        if (previewToken.t.indexOf('rooster')==0){
                            x-=10;
                        }
                        if (previewToken.t != 'rooster'){
                            var others = this.queryCount('#'+previewToken.e+' .token:not(.preview-token)');
                            if (others>0){
                                y += 20*others;
                            }
                        }

                        this.moveObject( elementId, previewToken.e, x,y , false, 1500, 0, true);
                    } else {
                        var pos = this.calculateTokenPos(playerId, elementId, previewToken.t, previewToken.l, previewToken.a, this.gamedatas_local.tokens);
                        dojo.place( this.format_block('jstpl_token', item), pos.target, 'last' );
                        this.moveObject( elementId, pos.target, pos.x, pos.y, false, 1500, 0, true);
                        if (pos.zIndex){
                            this.setElementZIndex(elementId, pos.zIndex);
                        }
                    }
                }

            }

        },

        selectWorkerToPlace: function(){
            var actionValue = 0;
            if (!this.clientStateArgs.location){
                return;
            }

            for (var i=0;i< this.gamedatas_local.activeLocations.length;i++){
                if (this.gamedatas_local.activeLocations[i].t == this.clientStateArgs.location){
                    actionValue = this.gamedatas_local.activeLocations[i].a;
                    if (actionValue<=0){
                        return;
                    }
                    break;
                }
            }
            if (actionValue==2 || this.clientStateArgs.forceWorkerPlacement=='worker_g'){
                this.clientStateArgs.worker_g = 1;
                this.clientStateArgs.tokenWorker = 'worker_g';
            } else {
                var availableWorkers = this.getAvailableWorkers(this.getThisPlayerId());
                if (availableWorkers.length == 1 ){
                    this.clientStateArgs.tokenWorker = availableWorkers[0].t;
                } else {
                    for (var i=0;i<availableWorkers.length;i++){
                        if (availableWorkers[i].t!='worker_g'){
                            this.clientStateArgs.tokenWorker = availableWorkers[i].t;
                            break;
                        }
                    }
                }
            }
        },

        addCardPlayedToHistory: function(card){
            if (this.gamedatas_local.history==null){
                this.gamedatas_local.history.push(playerId+'_'+cardKey);
            }
            var cardParts = card.split('_');
            this.historyStock.addToStockWithId(cardParts[1], card+'_'+(this.historyStock.count()+1));
            if (this.historyStock.count()>0){
                $('history_exandable_count').innerHTML = '('+this.historyStock.count()+')';
            }
        },

        removeLastCardPlayedToHistory: function(card){
            if (this.gamedatas_local.history!=null && this.gamedatas_local.history.length>0){
                var last = this.gamedatas_local.history[this.gamedatas_local.history.length-1];
                if (last.indexOf(card)==0){
                    this.gamedatas_local.history.pop();
                }
            }
            var items = this.historyStock.getAllItems();
            if (items.length>0){
                var last = items[items.length-1];
                if (last.id.indexOf(card)==0){
                    this.historyStock.removeFromStockById(last.id);
                }
            }
            if (this.historyStock.count()>0){
                $('history_exandable_count').innerHTML = '('+this.historyStock.count()+')';
            }
        },

        placeExpandableSection: function(parent_id, id, content, label) {
            html = this.format_block('jstpl_expandablesection', { id: id, content: content, count:'', label:label });
            dojo.place(html, parent_id);
        },

        setElementZIndex: function(elementId, zIndex){
            if (!elementId || !zIndex){
                return;
            }
            $(elementId).style.zIndex=zIndex;
        },

        playerGrapesToBitArray: function(playerId){
            var grapes = this.getPlayerData(playerId).grapes;
            var result = {'grapeRed':[0,0,0,0,0,0,0,0,0,0],'grapeWhite':[0,0,0,0,0,0,0,0,0,0]};
            for (var i=0;i<grapes.length;i++){
                result[grapes[i].t][grapes[i].v]=1;
            }
            return result;
        },

        getPapaChoiceDescription: function(pChoice, pPlayerId){
            var choice = '';
            if (pChoice.indexOf('vp')>=0||pChoice.indexOf('lira')>=0){
                choice = this.getTokenSymbol(pChoice);
            } else {
                var description ='';
                if (pChoice=='worker'){
                    description = _('Additional worker');
                } else {
                    description = this.getStructureDescription(pChoice);
                }
                if (pPlayerId){
                    choice = description +' '+ this.getTokenPlayerSymbol(pPlayerId, pChoice);
                } else {
                    choice = description +' '+ this.getTokenSymbol(pChoice);
                }
            }
            return choice;
        },

        getStructureDescription: function(pStructure){
            if (!pStructure){
                return '';
            }
            
            var token = this.arrayFindByProperty(this.gamedatas_local.playerTokens, 'type', pStructure);

            if (token){
                return _(token.name);
            }

            return '';
        },

        updatePlayCard: function(){

            if (!this.clientStateArgs.playCard){
                this.hide('play_card_section');
                return;
            }

            this.show('play_card_section');

            var cardType = this.getCardType(this.clientStateArgs.playCard);

            var labelPlay;
            if (this.clientStateArgs.playCardPlayerId == this.getThisPlayerId()){
                labelPlay = dojo.string.substitute(_('You play ${token_card}'),{ token_card:this.getTokenSymbol(cardType)});
            } else {
                var playerData = this.getPlayerData(this.clientStateArgs.playCardPlayerId);
                labelPlay = dojo.string.substitute(_('${playerName} plays ${token_card}'),{playerName: playerData.player_name, token_card:this.getTokenSymbol(cardType)});
            }

            var item = this.cloneCard(this.clientStateArgs.playCard);
            item.elementId='preview_card'+this.clientStateArgs.playCard;
            item.name=item.name||'';
            item.description=this.getDescriptionWithTokens(item.description, true,null,this.clientStateArgs.playCardPlayerId)||'';
            item.labelPlay=labelPlay;

            dojo.place( this.format_block( 'jstpl_play_card', item), 'play_card_section','only' );
            this.addTooltipHtml( item.elementId, this.getTooltipHtmlCard(this.clientStateArgs.playCard,this.clientStateArgs.playCardPlayerId));

        },

        onRefuseClick: function(evt){
            console.log( '$$$$ Event : onRefuseClick' );
            dojo.stopEvent( evt );

            var action="";
            var actionConfirm="";

            if ( !this.isCurrentPlayerActive() ) {
                return;
            }
            var actionRefuse = this.clientStateArgs.action;

            action = 'refuse';
            actionConfirm = 'client_refuse_confirm';

            if (action){
                if( ! this.checkAction( action ) )
                { return; }

                this.clientStateArgs = {};

                this.clientStateArgs.action = action;
                this.clientStateArgs.actionRefuse = actionRefuse;
                var translated =_("Refuse action?");
                this.setClientStateAction(actionConfirm,translated);
            }

        },

        onButtonConfirmAskConfirmClick: function(evt){
            dojo.stopEvent( evt );

            this.confirmationDialog(this.clientStateArgs.askConfirm, dojo.hitch( this, function() {
                this.ajaxClientStateAction();
            } ) );
            return; // nothing should be called or done after calling this, all action must be done in the handler

        },

        onCancelActionButtonClick: function(evt){
            console.log( '$$$$ Event : onCancelActionButtonClick' );
            dojo.stopEvent( evt );

            var action="";
            var actionConfirm="";

            if ( !this.isCurrentPlayerActive() ) {
                return;
            }
            var actionCancel = this.clientStateArgs.action;

            action = 'cancelAction';
            actionConfirm = 'client_cancelAction_confirm';

            if (action){
                if( ! this.checkAction( action ) )
                { return; }

                this.clientStateArgs = {};

                this.clientStateArgs.action = action;
                this.clientStateArgs.actionCancel = actionCancel;
                var translated =_("Cancel card played?");
                this.setClientStateAction(actionConfirm,translated);
            }
        },

        onChooseOkClick: function(evt){
            console.log( '$$$$ Event : onChooseOkClick' );
            dojo.stopEvent( evt );

            var action="";

            if ( !this.isCurrentPlayerActive() ) {
                return;
            }

            action = this.clientStateArgs.action;

            if (action){
                if( ! this.checkAction( action ) )
                { return; }

                this.clientStateArgs.choice = 1;

                this.ajaxClientStateAction();
            }
        },

        onProceedWithoutAgeingButtonClick: function(evt){
            console.log( '$$$$ Event : onProceedWithoutAgeingButtonClick' );
            dojo.stopEvent( evt );
            var action=this.clientStateArgs.action;
            var actionConfirm;

            if ( !this.isCurrentPlayerActive() ) {
                return;
            }

            if (action){
                if( ! this.checkAction( action ) )
                { return; }

                this.clientStateArgs.wine = '';
                this.clientStateArgs.wineValue = 0;
                translated=_('Proceed without ageing wine?');

                actionConfirm = 'client_ageWine_confirm';
                this.setClientStateAction(actionConfirm,translated);
            }
        },
        
        onProceedWithoutBuildingButtonClick: function(evt){
            console.log( '$$$$ Event : onProceedWithoutBuildingButtonClick' );
            dojo.stopEvent( evt );
            var action=this.clientStateArgs.action;
            var actionConfirm;

            if ( !this.isCurrentPlayerActive() ) {
                return;
            }

            if (action){
                if( ! this.checkAction( action ) )
                { return; }

                this.queryAndRemoveClass( '.action_slot.building_slot.selected', 'selected');

                this.clientStateArgs.structure = '';
                translated=_('Proceed without building?');

                actionConfirm = 'client_build_confirm';
                this.setClientStateAction(actionConfirm,translated);
            }
        },

        onProceedWithoutMakingWineButtonClick: function(evt){
            console.log( '$$$$ Event : onProceedWithoutMakingWineButtonClick' );
            dojo.stopEvent( evt );
            var action=this.clientStateArgs.action;
            var actionConfirm;
            var playerBoardId = 'playerboard_'+this.getThisPlayerId();

            if ( !this.isCurrentPlayerActive() ) {
                return;
            }

            if (action){
                if( ! this.checkAction( action ) )
                { return; }
                
                this.queryAndRemoveClass('#'+playerBoardId+' .wine_slot.selected','selected');
                this.queryAndRemoveClass('#'+playerBoardId+' .crushPad.grape.selected','selected');

                this.clientStateArgs.wine='NO';
                this.clientStateArgs.wineValue=0;
                this.clientStateArgs.grapesId='';

                translated=_('Proceed without making wine?');

                actionConfirm = 'client_makeWine_confirm';
                this.setClientStateAction(actionConfirm,translated);
            }
        },
        
        onProceedWithoutPlantingButtonClick: function(evt){
            console.log( '$$$$ Event : onProceedWithoutPlantingButtonClick' );
            dojo.stopEvent( evt );
            var action=this.clientStateArgs.action;
            var actionConfirm;
            var playerBoardId = 'playerboard_'+this.getThisPlayerId();

            if ( !this.isCurrentPlayerActive() ) {
                return;
            }

            if (action){
                if( ! this.checkAction( action ) )
                { return; }
                
                this.queryAndRemoveClass('.action_slot.field_slot.field.selected','selected');

                this.clientStateArgs.field=0;
                this.clientStateArgs.cardKey=0;

                translated=_('Proceed without planting?');

                actionConfirm = 'client_plant_confirm';
                this.setClientStateAction(actionConfirm,translated);
            }
        },

        onPassClick: function(evt){
            console.log( '$$$$ Event : onPassClick' );
            dojo.stopEvent( evt );

            if (this.checkPassActionEnabled()==false){
                return;
            }

            var playerData = this.getPlayerData(this.getThisPlayerId());

            var action="";
            var actionConfirm="";

            if ( !this.isCurrentPlayerActive() ) {
                return;
            }

            action = 'pass';
            actionConfirm = 'client_playerTurn_pass_confirm';

            if (action){
                if( ! this.checkAction( action ) )
                { return; }

                this.clientStateArgs = {};

                this.clientStateArgs.action = action;
                if (this.gamedatas_local.season==4){
                    var message = dojo.string.substitute(
                        _('<strong>WARNING!!!</strong><br/>Are you sure you want to pass and stop taking actions for the remainder of the <strong>year</strong>?<br/>All of your workers (${workers}) will be placed in the "GAIN ${token_lira1}" location'),{season_name: this.gamedatas_local.seasonTr, token_lira1: this.getTokenSymbol('lira1'), workers: playerData.workersSummary});
                } else {
                    var message = dojo.string.substitute(
                        _('Are you sure you want to pass and stop taking actions for the remainder of this season (${season_name})?'),{season_name: this.gamedatas_local.seasonTr});
                }

                // confirmation dialog to avoid mistakes on passing
                this.confirmationDialog(message, dojo.hitch( this, function() {
                    this.ajaxClientStateAction();
                } ) );
                return; // nothing should be called or done after calling this, all action must be done in the handler
            }

        },

        onChooseMamaPapaStockSelection: function(control_name, item_id){
            console.log( '$$$$ Event : onChooseMamaPapaStockSelection' );

            var stock = this.chooseMamaPapaStock;

            var action="chooseMamaPapa";
            var actionConfirm = 'client_mamaPapaChoose_confirm';

            if (stock.selectable==0){
                return;
            }
            if ( !this.isCurrentPlayerActive() ) {
                if (stock.isSelected(item_id)){
                    stock.unselectItem(item_id);
                } else {
                    stock.selectItem(item_id);
                }
                return;
            }

            if( ! this.checkAction( action ) )
            { return; }

            if (action){

                var selected = stock.getSelectedItems();
                if (selected.length<2){
                    //to return to initial state message and buttons
                    this.cancelLocalStateEffects();
                    return;
                }

                //deselect previous selection same type of card
                var type = this.queryFirst('#'+stock.getItemDivId(item_id)+' .card_wrapper').getAttribute('data-cardtype');
                for (var i=0;i<selected.length;i++){
                    if (item_id!=selected[i].id){
                        var type2 = this.queryFirst('#'+stock.getItemDivId(selected[i].id)+' .card_wrapper').getAttribute('data-cardtype');
                        if (type2==type){
                            stock.unselectItem(selected[i].id);
                        }
                    }
                }

                var mama = 0;
                var papa = 0;
                selected = stock.getSelectedItems();
                for (var i=0;i<selected.length;i++){
                    var type2 = this.queryFirst('#'+stock.getItemDivId(selected[i].id)+' .card_wrapper').getAttribute('data-cardtype');
                    if (type2=='papa'){
                        papa = selected[i].type;
                    }
                    if (type2=='mama'){
                        mama = selected[i].type;
                    }
                }

                if (selected.length != 2){
                    //to return to initial state message and buttons
                    this.cancelLocalStateEffects();
                } else {
                    this.clientStateArgs = {};
                    this.clientStateArgs.action = action;
                    this.clientStateArgs.mama = mama;
                    this.clientStateArgs.papa = papa;
                    var translated = dojo.string.substitute( _("Confirm selection?"));
                    this.setClientStateAction(actionConfirm,translated);
                }

            }
        },

        onPapaChoiceBonusClick: function(evt){
            console.log( '$$$$ Event : onPapaChoiceBonusClick' );
            dojo.stopEvent( evt );
            var me = this;

            var action="";
            var actionConfirm="";

            if ( !this.isCurrentPlayerActive() ) {
                return;
            }

            action = 'choosePapaOption';
            actionConfirm = 'client_choosePapaOption_confirm';

            if (action){
                if( ! this.checkAction( action ) )
                { return; }

                var playerData = this.getPlayerData(this.getThisPlayerId());
                var papaInfo = this.gamedatas_local.papas[Number(playerData.papa)];

                this.clientStateArgs = {};
                this.clientStateArgs.action = action;
                this.clientStateArgs.option = 'bonus';
                var choiceBonus = this.getPapaChoiceDescription(papaInfo.choice_bonus, this.getThisPlayerId());
                var translated = dojo.string.substitute( _("Confirm option '${tokenChoice}' selection?"),{tokenChoice:choiceBonus});
                this.setClientStateAction(actionConfirm,translated);
            }
        },

        onChooseCardsStockSelection: function(control_name, item_id){
            console.log( '$$$$ Event : onChooseCardsStockSelection' );

            var stock = this.chooseCardsStock;

            var action=this.clientStateArgs.action;
            var actionConfirm = 'client_chooseCards_confirm';

            if (stock.selectable==0){
                return;
            }
            if ( !this.isCurrentPlayerActive() ) {
                if (stock.isSelected(item_id)){
                    stock.unselectItem(item_id);
                } else {
                    stock.selectItem(item_id);
                }
                return;
            }

            if( ! this.checkAction( action ) )
            { return; }

            if (action){

                var selected = stock.getSelectedItems();
                if (selected.length != 2){
                    //to return to initial state message and buttons
                    actionConfirm = 'client_chooseCards_choose';
                    var translated = dojo.string.substitute( _("Select two cards"));
                    this.setClientStateAction(actionConfirm,translated);
                    return;
                }

                var selectedId = [];
                for (var i=0;i<selected.length;i++){
                    selectedId.push(selected[i].id);
                }
                this.clientStateArgs.cardsSelectedId = selectedId.join(',');
                var translated = dojo.string.substitute( _("Confirm selection?"));
                this.setClientStateAction(actionConfirm,translated);

            }
        },

        onPlayCardOptionChoose: function(evt, choice){
            console.log( '$$$$ Event : onPlayCardOptionChoose' );
            dojo.stopEvent( evt );

            this.processPlayCardOptionChoose(choice);

        },

        onCardChooseOptionClick: function(evt, choice){
            console.log( '$$$$ Event : onCardChooseOptionClick' );
            dojo.stopEvent( evt );

            this.clientStateArgs.otherSelection=choice;

            switch (this.clientStateArgs.visitorCardKey) {
                case 825: //Motivator
                    this.clientStateArgs.worker_g = 1;
                    this.clientStateArgs.tokenWorker = 'worker_g';
                    if (this.clientStateArgs.previewTokens.worker){
                        this.activatePreviewToken(this.clientStateArgs.tokenWorker,this.clientStateArgs.previewTokens.worker.e, 'worker', true);
                    }
                    break;
            }

            var translated = this.clientStateArgs.chooseOptionText+':'+this.clientStateArgs['chooseOptionText'+choice];
            var actionConfirm = 'client_cardChooseOption_confirm';
            this.setClientStateAction(actionConfirm,translated);

        },

        onChooseOptionsChoose: function(evt, choice){
            console.log( '$$$$ Event : onChooseOptionsChoose' );
            dojo.stopEvent( evt );

            this.clientStateArgs.choice = choice;

            switch (choice) {
                case 1:
                case 3:
                    var actionConfirm = 'client_chooseOption_confirm';
                    this.setClientStateAction(actionConfirm,this.gamedatas_local['choiceText'+choice]);
                    break;
                case 2:
                    this.enableActionGiveCards(2, 2, this.gamedatas_local.playerIdGive, this.gamedatas_local.playerNameGive, null);
                    break;

                default:
                    break;
            }


        },

        processPlayCardOptionChoose: function(choice){
            var playerId = this.getThisPlayerId();
            var playerData = this.getPlayerData(playerId);
            var actionConfirm = null;
            var translated = null;

            this.clientStateArgs.visitorCardOption = choice;

            switch (this.clientStateArgs.visitorCardKey) {
                case 601: //Surveyor
                    //Gain ${token_lira2} for each empty field you own OR gain ${token_vp1} for each planted field you own.
                    //**special**
                    //getVp_buildings4{
                    this.processStandardPlayCardOptionConfirm(choice);
                    break;

                case 602: //Broker
                    //Pay ${token_lira9} to gain ${token_vp3} OR lose ${token_vp2} to gain ${token_lira6}
                    //payLira_9+getVp_3|loseVp_2+getLira_6
                    this.processStandardPlayCardOptionConfirm(choice);
                    break;

                case 603: //Wine Critic
                    //Draw 2 ${token_blueCardPlus OR discard 1 ${token_wineAny} of value 7 or more to gain ${token_vp4}
                    //drawBlueCard_2|dicardWineAny_1_7+getVp_4
                    if (choice=='1'){
                        this.processStandardPlayCardOptionConfirm(choice);
                    }
                    if (choice=='2'){
                        this.enableActionDiscardWine('',7,9,this.getDescriptionWithTokens(_('Choose a wine ${token_wineAny} of value 7 or more to gain ${token_vp4}')), _('Confirm selection?'));
                    }
                    break;

                case 604: //Blacksmith
                    //Build a structure at a ${token_lira2} discount. If it is a ${token_lira5} or ${token_lira6} structure, also gain ${token_vp1}.
                    //buildStructure_1_2_ifgreat5_1vp
                    //NO CHOICE
                    break;

                case 605: //Contractor
                    //Choose 2: Gain ${token_vp1}, build 1 structure, or plant 1 ${token_greenCard}.
                    //getVp1|buildStructure_1|plant_1
                    if (choice=='1'){
                        this.processStandardPlayCardOptionConfirm(choice);
                    }
                    if (choice=='2'){
                        this.enableActionBuild(0, dojo.string.substitute( _("Choose a structure to build"),
                        {}),null,false);
                    }
                    if (choice=='3'){
                        this.enableActionPlant(true, true, null, false);
                    }
                    break;

                case 606: //Tour Guide
                    //Gain ${token_lira4} OR harvest 1 field.
                    //getLira_4|harvestField_1
                    if (choice=='1'){
                        this.processStandardPlayCardOptionConfirm(choice);
                    }
                    if (choice=='2'){
                        this.enableActionHarvestField(1);
                    }
                    break;


                case 607: //Novice Guide
                    //Gain ${token_lira3} OR make up to 2 ${token_wineAny}
                    //getLira_3|makeWine_2
                    if (choice=='1'){
                        this.processStandardPlayCardOptionConfirm(choice);
                    }
                    if (choice=='2'){
                        this.enableActionMakeWine(true, false);
                    }
                    break;


                case 608: //Uncertified Broker
                    //Lose ${token_vp3}  to gain ${token_lira9} OR pay ${token_lira6} to gain ${token_vp2}.
                    //loseVp_3+getLira_9|payLira_6+getVp_2
                    this.processStandardPlayCardOptionConfirm(choice);
                    break;


                case 609: //Planter
                    //Plant up to 2 ${token_greenCard} and gain ${token_lira1} OR uproot and discard 1 ${token_greenCard} to gain ${token_vp2}.
                    //plant_2+getLira_1|uprootAndDiscard_1+getVp_2
                    if (choice=='1'){
                        this.enableActionPlant(true, true, null, false);
                    }
                    if (choice=='2'){
                        this.enableActionUproot(1,1);
                    }
                    break;


                case 610: //Buyer
                    //Pay ${token_lira2} to place a ${token_grapeAny1} on your crush pad OR discard 1 ${token_grapeAny} to gain ${token_lira2} and ${token_vp1}
                    //payLira_2+getGrapeRed_1|payLira_2+getGrapeWhite_1|discardGrapeAny_1+getLira_2+getVp_1
                    if (choice=='1'){
                        this.processStandardPlayCardOptionConfirm(choice);
                    }
                    if (choice=='2'){
                        this.processStandardPlayCardOptionConfirm(choice);
                    }
                    if (choice=='3'){
                        this.enableActionDiscardGrape('',1,9, this.getDescriptionWithTokens(_('Choose a grape ${token_grapeAny} to gain ${token_lira2} and ${token_vp1}')), _('Confirm selection?'));
                    }
                    break;


                case 611: //Landscaper
                    //Draw 1 ${token_greenCardPlus} and plant up to 1 ${token_greenCard} OR switch 2 ${token_greenCard} on your fields.
                    //**special**
                    if (choice=='1'){
                        this.processStandardPlayCardOptionConfirm(choice);
                    }
                    if (choice=='2'){
                        this.enableActionSwitchVine();
                    }
                    break;


                case 612: //Architect;
                    //Build a structure at a ${token_lira3} discount OR gain ${token_vp1} for each ${token_lira4} structure you have built.
                    //buildStructure_1_3
                    if (choice=='1'){
                        this.enableActionBuild(3,this.getDescriptionWithTokens(_('Build a structure at a ${token_lira} discount'),false,{token_lira:this.getTokenSymbol('lira3')}),null,false);
                    }
                    //getVp_buildings4{
                    if (choice=='2') {
                        this.processStandardPlayCardOptionConfirm(choice);
                    }
                    break;

                case 613: //Uncertified Architect
                    //Lose ${token_vp1} to build a ${token_lira2} or ${token_lira3} structure OR lose ${token_vp2} to build any structure.
                    //**special**
                    if (choice=='1'){
                        this.enableActionBuild(99,this.getDescriptionWithTokens(_('Lose ${token_vp1} to build a ${token_lira2} or ${token_lira3} structure')),[2,3],false );
                    }
                    if (choice=='2'){
                        this.enableActionBuild(99,this.getDescriptionWithTokens(_('Lose ${token_vp2} to build any structure' )),null,false);
                    }
                    break;


                case 614: //Patron
                    //Gain ${token_lira4} OR draw 1 ${token_purpleCard} card and 1 ${token_blueCard}.
                    //getLira_4|drawPurpleCard_1+drawBlueCard_1
                    this.processStandardPlayCardOptionConfirm(choice);
                    break;


                case 615: //Auctioneer
                    //Discard 2 ${token_anyCard} to gain ${token_lira4} OR discard 4 ${token_anyCard} to gain ${token_vp3}.
                    //discardCard_2+getLira_4|discardCard_4+getVp_3
                    if (choice=='1'){
                        this.enableActionDiscardCards(2,2, null, this.clientStateArgs.visitorCardId, false ,'');
                        actionConfirm = 'client_discardCards_choose';
                        translated = this.clientStateArgs.choice1;
                    }
                    if (choice=='2'){
                        this.enableActionDiscardCards(4,4, null, this.clientStateArgs.visitorCardId, false ,'');
                        actionConfirm = 'client_discardCards_choose';
                        translated = this.clientStateArgs.choice2;
                    }
                    break;


                case 616: //Entertainer
                    //Pay ${token_lira4} to draw 3 ${token_blueCardPlus} OR discard 1 ${token_wineAny} and 3 visitor cards to gain ${token_vp3}.
                    //**special**
                    if (choice=='1'){
                        this.processStandardPlayCardOptionConfirm(choice);
                    }
                    if (choice=='2'){
                        this.enableActionDiscardCards(3,3, ['yellowCard','blueCard'], this.clientStateArgs.visitorCardId, true, '');
                        actionConfirm = 'client_discardCards_choose';
                        translated = this.clientStateArgs.choice2;
                    }
                    break;


                case 617: //Vendor
                    //Draw 1 ${token_greenCardPlus} , 1 ${token_purpleCardPlus}, and 1 ${token_blueCardPlus}. Each opponent may draw 1 ${token_yellowCardPlus}.
                    //drawGreenCard_1+drawPurpleCard_1+drawBlueCard_1
                    //Only one choice
                    break;


                case 618: //Handyman
                    //All players may build 1 structure at a ${token_lira2} discount. You gain ${token_vp1} for each opponent who does this.
                    //**special**
                    //one choice
                    break;


                case 619: //Horticulturist
                    //Plant 1 ${token_greenCard} even if you don't have the required structure(s) OR uproot and discard 2 ${token_greenCard} to gain ${token_vp3}.
                    //**special**
                    if (choice=='1'){
                        this.enableActionPlant(false, true, null, false);
                    }
                    if (choice=='2'){
                        this.enableActionUproot(2,2);
                    }
                    break;

                case 620: //Peddler
                    //Discard 2 ${token_anyCard} to draw 1 of each type of card.
                    //**special**
                    //one choice
                    break;

                case 621: //Banker
                    //Gain ${token_lira5}. Each opponent may lose ${token_vp1} to gain ${token_lira3}.
                    //**special**
                    //one choice
                    break;

                case 622: //Overseer
                    //Build 1 structure at its regular cost and plant 1 ${token_greenCard}. If it is a 4-value ${token_greenCard}, gain ${token_vp1}.
                    //buildStructure_1|plant_1_ifgreat4_1vp
                    //one choice
                    break;

                case 623: //Importer
                    //Draw 3 ${token_blueCard} cards unless all opponents combine to give you 3 visitor cards (total).
                    //**special**
                    //one choice
                    break;

                case 624: //Sharecropper
                    //Plant 1 ${token_greenCard} even if you don't have the required structure(s) OR uproot and discard 1 ${token_greenCard} to gain ${token_vp2}.
                    //plant_1_noStructure|uprootAndDiscard_1+getVp_2
                    if (choice=='1'){
                        this.enableActionPlant(false, true, null, false);
                    }
                    if (choice=='2'){
                        this.enableActionUproot(1,1);
                    }
                    break;

                case 625: //Grower
                    //Plant 1 ${token_greenCard}. Then, if you have planted a total of at least 6 ${token_greenCard}, gain ${token_vp2}.
                    //plant_1_iftotalgreat_6_vp2
                    //one choice
                    break;

                case 626: //Negotiator
                    //Discard 1 ${token_grapeAny} to gain ${token_residualPayment1} OR discard 1 ${token_wineAny} to gain ${token_residualPayment2} .
                    //discardGrape_1+getResidualPayment_1|discardWine_1+getResidualPayment_2
                    if (choice=='1'){
                        this.enableActionDiscardGrape('',1,9, this.getDescriptionWithTokens(_('Choose a grape ${token_grapeAny} to gain ${token_residualPayment1}')), _('Confirm selection?'));
                    }
                    if (choice=='2'){
                        this.enableActionDiscardWine('',1,9,this.getDescriptionWithTokens(_('Discard 1 ${token_wineAny} to gain ${token_residualPayment2}')), _('Confirm selection?'));
                    }
                    break;

                case 627: //Cultivator
                    //Plant 1 ${token_greenCard}. You may plant it on a field even if the total value of that field exceeds the max vine value.
                    //plant_1_overMax
                    //one choice
                    break;

                case 628: //Homesteader
                    //Build 1 structure at a ${token_lira3} discount OR plant up to 2 ${token_greenCard}. You may lose ${token_vp1} to do both.
                    //buildStructure_1_3|plant_2
                    if (choice=='1'){
                        this.enableActionBuild(3,this.getDescriptionWithTokens(_('Build a structure at a ${token_lira} discount'),false,{token_lira:this.getTokenSymbol('lira3')}),null,false);
                    }
                    if (choice=='2'){
                        this.enableActionPlant(true, true, null, false);
                    }
                    break;

                case 629: //Planner
                    //Place a worker on an action in a future season. Take that action at the beginning of that season.
                    //**special**
                    //one choice
                    break;

                case 630: //Agriculturist
                    //Plant 1 ${token_greenCard}. Then, if you have at least 3 different types of ${token_greenCard} planted on that field, gain ${token_vp2}.
                    //**special**
                    //one choice
                    break;

                case 631: //Swindler
                    //Each opponent may give you ${token_lira2}. For each opponent who does not, gain ${token_vp1}.
                    //**special**
                    //one choice
                    break;

                case 632: //Producer
                    //Pay ${token_lira2} to retrieve up to 2 ${token_worker} from other actions. They may be used again this year.
                    //**special**
                    //one choice
                    break;

                case 633: //Organizer
                    //Move your ${token_rooster} piece to an empty row on the wake-up chart, take the bonus, then pass to the next season.
                    //**special**
                    //one choice
                    break;

                case 634: //Sponsor
                    //Draw 2 ${token_greenCardPlus} OR gain ${token_lira3}. You may lose ${token_vp1} to do both.
                    //drawGreenCard_2|getLira_3
                    this.processStandardPlayCardOptionConfirm(choice);
                    break;

                case 635: //Artisan
                    //Choose 1: Gain ${token_lira3}, build a structure at a ${token_lira1} discount, or plant up to 2 ${token_greenCard}.
                    //getLira_3|buildStructure_1_1|plant_2
                    if (choice=='1'){
                        this.processStandardPlayCardOptionConfirm(choice);
                    }
                    if (choice=='2'){
                        this.enableActionBuild(1,this.getDescriptionWithTokens(_('Build a structure at a ${token_lira} discount'),false,{token_lira:this.getTokenSymbol('lira1')}),null,false);
                    }
                    if (choice=='3'){
                        this.enableActionPlant(true, true, null, false);
                    }
                    break;

                case 636: //Stonemason
                    //Pay ${token_lira8} to build any 2 structures (ignore their regular costs)
                    //payLira_8+buildStructure_2_free
                    //one choice
                    break;

                case 637: //Volunteer Crew
                    //All players may plant 1 ${token_greenCard}. Gain ${token_lira2} for each opponent who does this.
                    //**special**
                    //one action
                    break;

                case 638: //Wedding Party
                    //Pay up to 3 opponents ${token_lira2} each. Gain ${token_vp1} for each of those opponents.
                    //**special**
                    //one action
                    break;

                case 801: //Merchant
                    //Pay ${token_lira3} to place a ${token_grapeRed1} and a ${token_grapeWhite1}  on your crush pad OR fill 1 ${token_purpleCard} and gain ${token_vp1} extra.
                    //payLira_3+getGrapeRed_1+getGrapeWhite_1|fillOrder_1+getVp_1
                    if (choice=='1'){
                        this.processStandardPlayCardOptionConfirm(choice);
                    }
                    if (choice=='2'){
                        this.enableActionFillOrder();
                    }
                    break;

                case 802: //Crusher
                    //Gain ${token_lira3} and draw 1 ${token_yellowCard} OR draw 1 ${token_purpleCard} and make up to 2 ${token_wineAny}.
                    //GetLira_3+drawYellowCard_1|drawPurpleCard_1+makeWine_2
                    this.processStandardPlayCardOptionConfirm(choice);
                    break;

                case 803: //Judge
                    //Draw 2 ${token_yellowCardPlus} OR discard 1 ${token_wineAny} of value 4 or more to gain ${token_vp3}.
                    //drawYellowCard_2|discardWineAny_1_value4+getVp_3
                    if (choice=='1'){
                        this.processStandardPlayCardOptionConfirm(choice);
                    }
                    if (choice=='2'){
                        this.enableActionDiscardWine('',4,9,this.getDescriptionWithTokens(_('Choose a wine ${token_wineAny} of value 4 or more to gain ${token_vp3}')), _('Confirm selection?'));
                    }
                    break;

                case 804: //Oenologist
                    //Age all ${token_wineAny} in your cellar twice OR pay ${token_lira3} to upgrade your cellar to the next level.
                    //ageWines_2|payLira_2+upgradeCellar
                    this.processStandardPlayCardOptionConfirm(choice);
                    break;

                case 805: //Marketer
                    //Draw 2 ${token_yellowCardPlus} and gain ${token_lira1} OR fill 1 ${token_purpleCard} and gain ${token_vp1} extra.
                    //drawYellowCard_2+getLira_1|fillOrder_1+getVp_1
                    if (choice=='1'){
                        this.processStandardPlayCardOptionConfirm(choice);
                    }
                    if (choice=='2'){
                        this.enableActionFillOrder();
                    }
                    break;

                case 806: //Crush Expert
                    //Gain ${token_lira3} and draw 1 ${token_purpleCard} OR make up to 3 ${token_wineAny}.
                    //getLira_3+drawPurpleCard|makeWine_3
                    if (choice=='1'){
                        this.processStandardPlayCardOptionConfirm(choice);
                    }
                    if (choice=='2'){
                        this.enableActionMakeWine(true, false);
                    }
                    break;

                case 807: //Uncertified Teacher
                    //Lose ${token_vp1} to train a ${token_worker} OR gain ${token_vp1} for each opponent who has a total of 6 ${token_worker}.
                    //**special**
                    this.processStandardPlayCardOptionConfirm(choice);
                    break;

                case 808: //Teacher
                    //Make up to 2 ${token_wineAny} OR pay ${token_lira2} to train 1 worker.
                    //makeWine_2|trainWorker_1_price2
                    if (choice=='1'){
                        this.enableActionMakeWine(true, false);
                    }
                    if (choice=='2'){
                        this.processStandardPlayCardOptionConfirm(choice);
                    }
                    break;

                case 809: //Benefactor
                    //Draw 1 ${token_greenCard} and 1 ${token_yellowCard} card OR discard 2 visitor cards to gain ${token_vp2}.
                    //drawGreenCard+drawYellowCard|discardCard_2+get2Vp
                    if (choice=='1'){
                        this.processStandardPlayCardOptionConfirm(choice);
                    }
                    if (choice=='2'){
                        this.enableActionDiscardCards(2,2, ['yellowCard','blueCard'], this.clientStateArgs.visitorCardId, false ,'');
                        actionConfirm = 'client_discardCards_choose';
                        translated = this.clientStateArgs.choice2;
                    }
                    break;

                case 810: //Assessor
                    //Gain ${token_lira1} for each card in your hand OR discard your hand (min of 1 card) to gain ${token_vp2}.
                    //**special**
                    this.clientStateArgs.askConfirm = '';

                    //ask confirm: #42694: "ASK FOR CONFIRMATION WHEN PLAYING CERTAIN CARDS"
                    if (choice==2){
                        this.clientStateArgs.askConfirm = _("You will discard ALL your cards!</strong><br/>Do you confirm to discard ALL?");
                    } 
                    
                    this.processStandardPlayCardOptionConfirm(choice);
                    break;

                case 811: //Queen
                    //The player on your right must choose 1: lose ${token_vp1}, give you 2 ${token_anyCard}, or pay you ${token_lira3}.
                    //**special**
                    //one choice
                    break;

                case 812: //Harvester
                    //Harvest up to 2 fields and choose 1: Gain ${token_lira2} or gain ${token_vp1}.
                    //harvestField_2+getLira_2|harvestField_2+getVp_1
                    this.enableActionHarvestField(2);
                    break;

                case 813: //Professor
                    //Pay ${token_lira2} to train 1 ${token_worker} OR gain ${token_vp2} if you have a total of 6 ${token_worker}.
                    //**special**
                    this.processStandardPlayCardOptionConfirm(choice);
                    break;

                case 814: //Master Vintner
                    //Upgrade your cellar to the next level at a ${token_lira2} discount OR age 1 ${token_wineAny} and fill 1 ${token_purpleCard}.
                    //upgradeCellar_discount2|ageWine1+fillOrder_1
                    if (choice=='1'){
                        this.processStandardPlayCardOptionConfirm(choice);
                    }
                    if (choice=='2'){
                        this.enableActionAgeWine(this.clientStateArgs.choice2, _('Confirm selected wine?'));
                    }
                    break;

                case 815: //Uncertified Oenologist
                    //Age all ${token_wineAny} in your cellar twice OR lose ${token_vp1} to upgrade your cellar to the next level.
                    //ageWines_2|payLVp_1+upgradeCellar
                    this.processStandardPlayCardOptionConfirm(choice);
                    break;

                case 816: //Promoter
                    //Discard a ${token_grapeAny} or ${token_wineAny} to gain ${token_vp1} and ${token_residualPayment1}.
                    //discardGrapeAny_1+getVp_1+getResidualPayment_1|discardWineAny_1+getVp_1+getResidualPayment_1|
                    if (choice=='1'){
                        this.enableActionDiscardGrape('',1,9, this.getDescriptionWithTokens(_('Choose a grape ${token_grapeAny} to gain ${token_vp1} and ${token_residualPayment1}')), _('Confirm selection?'));
                    }
                    if (choice=='2'){
                        this.enableActionDiscardWine('',1,9,this.getDescriptionWithTokens(_('Choose a wine ${token_wineAny} to gain ${token_vp1} and ${token_residualPayment1}')), _('Confirm selection?'));
                    }
                    break;

                case 817: //Mentor
                    //All players may make up to 2 ${token_wineAny}. Draw 1 ${token_greenCardPlus} or ${token_YellowCardPlus} card for each opponent who does this.
                    //**special**
                    //one choice
                    break;

                case 818: //Harvest Expert
                    //Harvest 1 field and either draw 1 ${token_greenCardPlus} or pay ${token_lira1} to build a Yoke.
                    //harvestField_1+drawGreenCard_1|harvestField_1+buildStructure_1_yoke_price1
                    this.enableActionHarvestField(1);
                    break;

                case 819: //Innkeeper
                    //As you play this card, put the top card of 2 different discard piles in your hand.
                    //GetDiscardCard_2
                    //one choice
                    break;

                case 820: //Jack-of-all-trades
                    //Choose 2: Harvest 1 field, make up to 2 ${token_wineAny}, or fill 1 ${token_purpleCard}.
                    //HarvestField_1|makeWine_2|fillOrder_1
                    if (choice=='1'){
                        this.enableActionHarvestField(1);
                    }
                    if (choice=='2'){
                        this.enableActionMakeWine(true, false);
                    }
                    if (choice=='3'){
                        this.enableActionFillOrder();
                    }
                    break;

                case 821: //Politician
                    //If you have less than 0${token_vp}, gain ${token_lira6}. Otherwise, draw 1 ${token_greenCardPlus}, 1 ${token_yellowCardPlus}, and 1 ${token_purpleCardPlus}.
                    //**special**
                    //one choice
                    break;

                case 822: //Supervisor
                    //Make up to 2 ${token_wineAny}. Gain${token_vp1} for each sparkling wine token you make.
                    //makeWine_2_ifmakesparklingwineeach_1vp
                    //one choice
                    break;

                case 823: //Scholar
                    //Draw 2 ${token_purpleCard} OR pay ${token_lira3} to train 1 ${token_worker}. You may lose ${token_vp1} to do both.
                    //drawPurpleCard_2|trainWorker_1_price1
                    this.processStandardPlayCardOptionConfirm(choice);
                    break;

                case 824: //Reaper
                    //Harvest up to 3 fields. If you harvest 3 fields, gain ${token_vp2}.
                    //harvestField_3_ifharvested3fields_2vp
                    //one choice
                    break;

                case 825: //Motivator
                    //Each player may retrieve their grande worker. Gain ${token_vp1} for each opponent who does this.
                    //**special**
                    //one choice
                    break;

                case 826: //Bottler
                    //Make up to 3 ${token_wineAny}. Gain ${token_vp1} for each type of wine you make.
                    //makeWine_3_ifdistincttype_get1vp **needs history of wines**
                    //one choice
                    break;

                case 827: //Craftsman
                    //Choose 2: Draw 1 ${token_purpleCard}, upgrade your cellar at the regular cost, or gain ${token_vp1}.
                    //drawPurpleCard_1|upgradeCellar|getVp_1
                    this.processStandardPlayCardOptionConfirm(choice);
                    break;

                case 828: //Exporter
                    //Choose 1: Make up to 2 ${token_wineAny}, fill 1 ${token_purpleCard}, or discard 1 ${token_grapeAny} to gain ${token_vp2}.
                    //makeWine_2|fillOrder_1|discardGrapeAny+getVp_2
                    if (choice=='1'){
                        this.enableActionMakeWine(true, false);
                    }
                    if (choice=='2'){
                        this.enableActionFillOrder();
                    }
                    if (choice=='3'){
                        this.enableActionDiscardGrape('',1,9, this.getDescriptionWithTokens(_('Choose a grape ${token_grapeAny} to gain ${token_vp2}')), _('Confirm selection?'));
                    }
                    break;

                case 829: //Laborer
                    //Harvest up to 2 fields OR make up to 3 ${token_wineAny}. You may lose ${token_vp1} to do both.
                    //harvestField_2|makeWine_3
                    if (choice=='1'){
                        this.enableActionHarvestField(2);
                    }
                    if (choice=='2'){
                        this.enableActionMakeWine(true, false);
                    }
                    break;

                case 830: //Designer
                    //Build 1 structure at its regular cost. Then, if you have at least 6 structures, gain ${token_vp2}.
                    //buildStructure_1_ifstructuturesgt_6_vp2
                    //one choice
                    break;

                case 831: //Governess
                    //Pay ${token_lira3} to train 1 ${token_worker} that you may use this year OR discard 1 ${token_wineAny} to gain ${token_vp2}.
                    //**special**
                    if (choice=='1'){
                        this.processStandardPlayCardOptionConfirm(choice);
                    }
                    if (choice=='2'){
                        this.enableActionDiscardWine('',1,9,this.getDescriptionWithTokens(_('Choose a wine ${token_wineAny} to gain ${token_vp2}')), _('Confirm selection?'));
                    }
                    break;

                case 832: //Manager
                    //Take any action (no bonus) from a previous season without placing a worker.
                    //**special**
                    //one choice
                    break;

                case 833: //Zymologist
                    //Make up to 2 ${token_wineAny} of value 4 or greater, even if you haven't upgraded your cellar.
                    //makeWine_2_value4withouthmediumcellar
                    //one choice
                    break;

                case 834: //Noble
                    //Pay ${token_lira1} to gain ${token_residualPayment1} OR lose ${token_residualPayment2} to gain ${token_vp2}.
                    //payLira_1+getResidualPayment_1|payResidualPayment_2+getVp_2
                    this.processStandardPlayCardOptionConfirm(choice);
                    break;

                case 835: //Governor
                    //Choose up to 3 opponents to each give you 1 ${token_yellowCard}. Gain ${token_vp1} for each of them who cannot.
                    //**special**
                    //one choice
                    break;

                case 836: //Taster
                    //Discard 1 ${token_wineAny} to gain ${token_lira4}. If it is the most valuable wine token in any player's cellar (no ties), gain 2 ${token_vp2}.
                    //**special**
                    //onechoice
                    break;

                case 837: //Caravan
                    //Turn the top card of each deck face up. Draw 2 of those cards and discard the others.
                    //**special** requires state with first card of deck
                    //one choice
                    break;

                case 838: //Guest Speaker
                    //All players may pay ${token_lira1} to train ${token_worker}. Gain ${token_vp1} for each opponent who does this.
                    //**special**
                    //one choice
                    break;

                default:
                    break;
            }

            if (actionConfirm && translated){
                this.setClientStateAction(actionConfirm,translated);
            }

        },

        processStandardPlayCardOptionConfirm: function(choice) {
            var actionConfirm = this.clientStateArgs['choice'+choice+'actionConfirm']||'client_playCard_option_confirm';
            this.setClientStateAction(actionConfirm,this.clientStateArgs['choice'+choice]);
        },

        enableBoardLocations: function(){
            var playerId = this.getThisPlayerId();
            var playerData = this.getPlayerData(playerId);
            var playerBoardId = 'playerboard_'+this.getThisPlayerId();

            this.queryAndAddClass('.action_slot_worker','action_slow_worker_disabled');
            for (var i = 0;i<this.gamedatas_local.activeLocations.length;i++){
                var locationInfo=this.arrayFindByProperty(this.gamedatas_local.locations, 'key', this.gamedatas_local.activeLocations[i].t);
                var actionSlotId = 'action_slot_'+this.gamedatas_local.activeLocations[i].t;
                if (this.gamedatas_local.activeLocations[i].t==901){
                    actionSlotId = 'action_slot_'+this.gamedatas_local.activeLocations[i].t+'_'+playerId;
                }
                this.queryAndAddEvent('#'+actionSlotId,'click','onBoardActionSlotWorkerClick');
                this.queryAndAddClass('#'+actionSlotId,'active_slot');
                this.queryAndRemoveClass('#'+actionSlotId,'action_slow_worker_disabled');
                this.queryAndSetAttribute('#'+actionSlotId,'data-worker',this.gamedatas_local.activeLocations[i].a);
                this.removeTooltip(actionSlotId);
                if (locationInfo && locationInfo.des){
                    this.addTooltipHtml(actionSlotId, this.getDescriptionWithTokens(_(locationInfo.des)));
                }
            }
            var disabledLocations = dojo.query('.action_slow_worker_disabled');
            var season = Number(this.gamedatas_local.season);
            for (var i=0;i<disabledLocations.length;i++){
                var location=Number(disabledLocations[i].getAttribute('data-arg'));
                var locationInfo=this.arrayFindByProperty(this.gamedatas_local.locations, 'key', location);
                if (this.isCurrentPlayerActive()){
                    var actionDescription = '';
                    if (locationInfo && locationInfo.des){
                        actionDescription="<br/>"+this.getDescriptionWithTokens(_(locationInfo.des));
                    }
                    if (location==901){
                        var locationOccupied=this.isPlayerLocationOccupied(playerId, location);
                        if (playerData.yoke==0){
                            this.addTooltipHtml(disabledLocations[i].id,this.encapsulateTag('strong',this.getDescriptionWithTokens(_('To use this location you must buy Yoke ${token_yoke}'))));
                        } else {
                            if (locationOccupied){
                                this.addTooltipHtml(disabledLocations[i].id,this.encapsulateTag('strong',_('You can use this location only once'))+actionDescription);
                            } else {
                                this.addTooltipHtml(disabledLocations[i].id,this.encapsulateTag('strong',_('You cannot activate this location'))+actionDescription);
                            }
                        }
                    } else if (locationInfo && locationInfo.season<9 && locationInfo.season != season){
                        this.addTooltipHtml(disabledLocations[i].id,this.encapsulateTag('strong',_('You cannot use this location in this season'))+actionDescription);
                    } else {
                        var locationOccupied=this.isLocationOccupied(location);
                        if (locationOccupied){
                            //not bonus location, no solo, no grande worker
                            if (locationInfo.bonus == ''&& this.checkIfAvailableGrandeWorker(playerId) == false){
                                this.addTooltipHtml(disabledLocations[i].id,this.encapsulateTag('strong',_('You cannot activate this location because it\'s occupied and you already used the grande worker'))+actionDescription);
                            } else if (locationInfo.bonus != '' && this.gamedatas_local.soloMode == 0){
                                this.addTooltipHtml(disabledLocations[i].id,this.encapsulateTag('strong',_('You cannot activate this bonus location because it\'s occupied (Bonus locations can be used only once)'))+actionDescription);
                            } else {
                                this.addTooltipHtml(disabledLocations[i].id,this.encapsulateTag('strong',_('You cannot activate this location'))+actionDescription);
                            }  
                        } else if (locationInfo.bonus != '' && this.gamedatas_local.soloMode > 0 && playerData.bonuses==0){
                            this.addTooltipHtml(disabledLocations[i].id,this.encapsulateTag('strong',_('You cannot activate this bonus location because you don\'t have bonus tokens'))+actionDescription);
                        } else {
                            this.addTooltipHtml(disabledLocations[i].id,this.encapsulateTag('strong',_('You cannot activate this location'))+actionDescription);
                        }
                        
                    }
                } else {
                    this.removeTooltip(disabledLocations[i].id);
                    if (locationInfo && locationInfo.des){
                        this.addTooltipHtml(actionSlotId, this.getDescriptionWithTokens(_(locationInfo.des)));
                    }
                }
            }

            if (this.isCurrentPlayerActive()){
                this.queryAndAddClass('#'+playerBoardId+' .component.worker_g','active_slot');
                this.queryAndAddEvent('#'+playerBoardId+' .component.worker_g.active_slot','click','onSelectWorkerClick');
            }
        },

        enableActionCardChooseOption: function(pChooseOptionText, pTextChoice1, pTextChoice2){
            this.clientStateArgs.chooseOptionText=pChooseOptionText;
            this.clientStateArgs.chooseOptionText1=pTextChoice1;
            this.clientStateArgs.chooseOptionText2=pTextChoice2;
            var actionConfirm = 'client_cardChooseOption_choose';
            var translated = pChooseOptionText;
            this.setClientStateAction(actionConfirm,translated);
        },

        enableActionSelectPreviousLocation: function(){
            var playerId = this.getThisPlayerId();
            var playerData = this.getPlayerData(playerId);

            this.queryAndDisconnectEvent('#board .active_slot','click');
            this.queryAndDisconnectEvent('#playerboard_row .active_slot','click');
            this.queryAndRemoveClass('#board .active_slot','active_slot');
            this.queryAndRemoveClass('#playerboard_row .active_slot','active_slot');
            this.queryAndSetAttribute('#playerboard_row .active_slot','data-worker','0');

            this.queryAndAddClass('.action_slot_worker','action_slow_worker_disabled');

            if( this.isCurrentPlayerActive() ){
                for (var i = 0;i<this.gamedatas_local.activeLocationsPrev.length;i++){
                    var actionSlotId = 'action_slot_'+this.gamedatas_local.activeLocationsPrev[i].t;
                    if (this.gamedatas_local.activeLocationsPrev[i].t==901){
                        actionSlotId = 'action_slot_'+this.gamedatas_local.activeLocationsPrev[i].t+'_'+playerId;
                    }
                    this.queryAndAddEvent('#'+actionSlotId,'click','onBoardTakeActionPrevClick');
                    this.queryAndAddClass('#'+actionSlotId,'active_slot');
                    this.queryAndSetAttribute('#'+actionSlotId,'data-worker',this.gamedatas_local.activeLocationsPrev[i].a);
                    this.queryAndRemoveClass('#'+actionSlotId,'action_slow_worker_disabled');
                    this.removeTooltip(actionSlotId);
                }
            }
            var disabledLocations = dojo.query('.action_slow_worker_disabled');
            var season = Number(this.gamedatas_local.season);
            for (var i=0;i<disabledLocations.length;i++){
                if (this.isCurrentPlayerActive()){
                    var location=Number(disabledLocations[i].getAttribute('data-arg'));
                    var locationInfo=this.arrayFindByProperty(this.gamedatas_local.locations, 'key', location);
                    if (location==901){
                        if (playerData.yoke==0){
                            this.addTooltipHtml(disabledLocations[i].id,this.getDescriptionWithTokens(_('To use this location you must buy Yoke ${token_yoke}')));
                        } else {
                            this.addTooltipHtml(disabledLocations[i].id,_('You cannot use this location now'));
                        }
                    } else if (locationInfo && locationInfo.season<9 && locationInfo.season != season){
                        this.addTooltipHtml(disabledLocations[i].id,_('You cannot use this location now'));
                    } else {
                        this.addTooltipHtml(disabledLocations[i].id,_('You cannot activate this location'));
                    }
                } else {
                    this.removeTooltip(disabledLocations[i].id);
                }
            }
        },

        enableActionSelectFutureLocation: function(){
            var playerData = this.getPlayerData(this.getThisPlayerId());

            this.queryAndDisconnectEvent('#board .active_slot','click');
            this.queryAndDisconnectEvent('#playerboard_row .active_slot','click');
            this.queryAndRemoveClass('#board .active_slot','active_slot');
            this.queryAndRemoveClass('#playerboard_row .active_slot','active_slot');

            if (this.clientStateArgs.location){
                dojo.addClass('action_slot_'+this.clientStateArgs.location,'selected');
            }

            var availableWorkers = this.getAvailableWorkers(this.getThisPlayerId());
            var workerSelectToken = null;
            var workerSelect = null;
            //remove used player token
            if (this.clientStateArgs.tokenWorker == 'worker_t' || this.clientStateArgs.tokenWorker == 'worker_t'){
                for (var i=0;i<availableWorkers.length;i++){
                    if (availableWorkers[i].t==this.clientStateArgs.tokenWorker){
                        availableWorkers.splice(i,1);
                        break;
                    }
                }
            } else if (this.clientStateArgs.tokenWorker == 'worker'){
                for (var i=0;i<availableWorkers.length;i++){
                    if (availableWorkers[i].t>='worker_1'&&availableWorkers[i].t<='worker_9'){
                        availableWorkers.splice(i,1);
                        break;
                    }
                }
            }
            //find first worker available
            for (var i=0;i<availableWorkers.length;i++){
                if ((availableWorkers[i].t>='worker_1'&&availableWorkers[i].t<='worker_9')||availableWorkers[i].t=='worker_t'){
                    workerSelectToken = 'worker';
                    workerSelect = availableWorkers[i].t;
                    break;
                }
            }
            if (workerSelect==null){
                for (var i=0;i<availableWorkers.length;i++){
                    if (availableWorkers[i].t=='worker_g'){
                        workerSelectToken = 'worker_g';
                        workerSelect = availableWorkers[i].t;
                    }
                }
            }
            this.clientStateArgs.tokenFutureWorker = workerSelectToken;
            this.clientStateArgs.futureWorker = workerSelect;

            for (var i = 0;i<this.gamedatas_local.locations.length;i++){
                if (this.gamedatas_local.locations[i].season<9 && this.gamedatas_local.locations[i].season > Number(this.gamedatas_local.season)){
                    //solo mode, bonus locations only if player has bonuses (wakeup bonus)
                    if (this.gamedatas_local.soloMode>0 && this.gamedatas_local.locations[i].bonus && playerData.bonuses==0 ){
                        continue;
                    }
                    var actionSlotId = '#action_slot_'+this.gamedatas_local.locations[i].key;
                    this.queryAndAddEvent(actionSlotId,'click','onBoardSelectFutureLocationClick');
                    this.queryAndAddClass(actionSlotId,'active_slot');
                }
            }
        },

        enableActionUproot: function(minUproot, maxUproot){
            var playerBoardId = 'playerboard_'+this.getThisPlayerId();
            this.clientStateArgs.minUproot=minUproot;
            this.clientStateArgs.maxUproot=maxUproot;
            this.queryAndAddEvent('#'+playerBoardId+' .vine','click','onChooseUprootVineClick');
            this.queryAndAddClass('#'+playerBoardId+' .vine','active_slot');
            //TODO: autoselect if only one?
            this.gotoUproot();
        },

        gotoUproot: function(message){
            var playerId = this.getThisPlayerId();
            var actionConfirm;
            var translated;
            if (message){
                translated = message;
            } else {
                translated = dojo.string.substitute(_('Select ${vines} vine(s) to uproot'),{vines:this.clientStateArgs.minUproot});
            }
            actionConfirm = 'client_uproot_choose';
            this.scrollTo('playerboard_row_'+playerId);
            this.setClientStateAction(actionConfirm,translated);
        },

        onChooseUprootClick: function(evt){
            console.log( '$$$$ Event : onChooseUprootClick' );
            dojo.stopEvent( evt );
            var playerBoardId = 'playerboard_'+this.getThisPlayerId();
            var me = this;

            var action="";
            var actionConfirm="";

            if ( !this.isCurrentPlayerActive() ) {
                return;
            }

            action = this.clientStateArgs.action;
            actionConfirm = 'client_uproot_choose';

            if (action){
                if( ! this.checkAction( action ) )
                { return; }

                this.enableActionUproot(1,1);
            }
        },

        onChooseUprootVineClick: function(evt){
            console.log( '$$$$ Event : onChooseUprootVineClick' );
            dojo.stopEvent( evt );
            var element = evt.currentTarget;
            var action=this.clientStateArgs.action;
            var vineId = Number(element.getAttribute('data-id'));
            var playerBoardId = 'playerboard_'+this.getThisPlayerId();
            var actionConfirm = 'client_uproot_confirm';

            if ( !this.isCurrentPlayerActive() ) {
                return;
            }

            if (action){
                if( ! this.checkAction( action ) )
                { return; }

                if (dojo.hasClass(element,'selected')){
                    dojo.removeClass(element,'selected');
                } else {
                    if (this.clientStateArgs.maxUproot == 1){
                        this.queryAndRemoveClass('#'+playerBoardId+' .vine.selected','selected');
                    }
                    dojo.addClass(element,'selected');
                }
                this.clientStateArgs.uprootVines = [];
                var vinesSelected = dojo.query('#'+playerBoardId+' .vine.selected');
                for (var i=0;i<vinesSelected.length;i++){
                    this.clientStateArgs.uprootVines.push(Number(vinesSelected[i].getAttribute('data-id')));
                }

                this.clientStateArgs.uprootVinesId = this.clientStateArgs.uprootVines.join(',');

                if (this.clientStateArgs.uprootVines.length>=this.clientStateArgs.minUproot && this.clientStateArgs.uprootVines.length<=this.clientStateArgs.maxUproot){
                    var translated = dojo.string.substitute( _("Confirm uproot of selected vine(s)?"),
                    {
                    });
                    this.setClientStateAction(actionConfirm,translated);
                } else {
                    this.gotoUproot();
                }

            }
        },

        onChooseSwitchVineClick: function(evt){
            console.log( '$$$$ Event : onChooseSwitchVineClick' );
            dojo.stopEvent( evt );
            var element = evt.currentTarget;
            var action=this.clientStateArgs.action;
            var vineId = Number(element.getAttribute('data-id'));
            var playerBoardId = 'playerboard_'+this.getThisPlayerId();
            var actionConfirm = 'client_uproot_confirm';

            if ( !this.isCurrentPlayerActive() ) {
                return;
            }

            if (action){
                if( ! this.checkAction( action ) )
                { return; }

                if (dojo.hasClass(element,'selected')){
                    dojo.removeClass(element, 'selected');
                } else {
                    dojo.addClass(element, 'selected');
                }
                this.clientStateArgs.cardsSelected = [];

                var vinesSelected = dojo.query('#'+playerBoardId+' .vine.selected');
                for (var i=0;i<vinesSelected.length;i++){
                    this.clientStateArgs.cardsSelected.push(
                        {
                            id: vinesSelected[i].getAttribute('data-id'),
                            type: vinesSelected[i].getAttribute('data-type')
                        });
                }

                this.checkSwitchVines();
            }
        },

        onChooseHarvestClick: function(evt){
            console.log( '$$$$ Event : onChooseHarvestClick' );
            dojo.stopEvent( evt );
            var me = this;

            var action="";
            var actionConfirm="";

            if ( !this.isCurrentPlayerActive() ) {
                return;
            }

            action = this.clientStateArgs.action;
            actionConfirm = 'client_uproot_choose';

            if (action){
                if( ! this.checkAction( action ) )
                { return; }
                this.enableActionHarvestField(1);
            }
        },


        onChooseCardDiscardSelection: function(control_name, item_id){
            console.log( '$$$$ Event : onChooseCardDiscardSelection' );
            var stock = this.handZone;

            var action= this.clientStateArgs.action;

            if ( !this.isCurrentPlayerActive() ) {
                return;
            }

            if (action){
                if( ! this.checkAction( action ) )
                { return; }

                if (this.queryCount('#'+stock.getItemDivId(item_id)+'.disabled')){
                    if (stock.isSelected(item_id)){
                        stock.unselectItem(item_id);
                    }
                }

                this.checkDiscardCardsAndWine();
            }
        },

        checkDiscardCardsAndWine: function(){
            var translated;
            var actionConfirm="";
            var stock = this.handZone;

            actionConfirm = 'client_discardCards_confirm';

            this.queryAndRemoveClass('.playerboard_hand_zone.stock_confirm_selection','stock_confirm_selection');

            var selected = stock.getSelectedItems();
            if (selected.length < this.clientStateArgs.minCardsToDiscard || selected.length > this.clientStateArgs.maxCardsToDiscard){
                if (this.clientStateArgs.minCardsToDiscard==this.clientStateArgs.maxCardsToDiscard){
                    translated = dojo.string.substitute( _("You must select ${cards} card(s)"),
                    {
                        cards: this.clientStateArgs.minCardsToDiscard
                    });
                } else {
                    translated = dojo.string.substitute( _("You must select from ${minCards} to ${maxCards} cards" ),
                    {
                        minCards: this.clientStateArgs.minCardsToDiscard,
                        maxCards: this.clientStateArgs.maxCardsToDiscard
                    });
                }
                actionConfirm = 'client_discardCards_choose';
            } else {
                if (this.clientStateArgs.discardWine == false){
                    translated = dojo.string.substitute( _("Confirm discard of selected card(s)?"),
                    {
                    });
                    this.queryAndAddClass('.playerboard_hand_zone','stock_confirm_selection');
                } else if (this.clientStateArgs.wine && this.clientStateArgs.wineValue){
                    translated = dojo.string.substitute( _("Confirm discard of selected cards and wine?"),
                    {
                    });
                    this.queryAndAddClass('.playerboard_hand_zone','stock_confirm_selection');
                } else {
                    translated = dojo.string.substitute( _("You must select a wine to discard"),
                                        {
                                        });
                    actionConfirm = 'client_discardCards_choose';
                }

            }

            var selectedId = [];
            for (var i=0;i<selected.length;i++){
                selectedId.push(selected[i].id);
            }
            this.clientStateArgs.cardsSelectedId = selectedId.join(',');
            this.setClientStateAction(actionConfirm,translated);
        },

        onChooseCardGiveSelection: function(control_name, item_id){
            console.log( '$$$$ Event : onChooseCardGiveSelection' );
            var stock = this.handZone;

            var action= this.clientStateArgs.action;

            if ( !this.isCurrentPlayerActive() ) {
                return;
            }

            if (action){
                if( ! this.checkAction( action ) )
                { return; }

                if (this.queryCount('#'+stock.getItemDivId(item_id)+'.disabled')){
                    if (stock.isSelected(item_id)){
                        stock.unselectItem(item_id);
                    }
                }

                this.checkGiveCards();
            }
        },

        checkGiveCards: function(){
            var translated;
            var actionConfirm="";
            var stock = this.handZone;

            actionConfirm = 'client_giveCards_confirm';

            var selected = stock.getSelectedItems();
            if (selected.length < this.clientStateArgs.minCards || selected.length > this.clientStateArgs.maxCards){
                if (this.clientStateArgs.minGiveCard==this.clientStateArgs.maxGiveCard){
                    translated = dojo.string.substitute( _("You must select ${cards} card(s)"),
                    {
                        cards: this.clientStateArgs.minCards
                    });
                } else {
                    translated = dojo.string.substitute( _("You must select from ${minCards} to ${maxCards} cards" ),
                    {
                        minCards: this.clientStateArgs.minCards,
                        maxCards: this.clientStateArgs.maxCards
                    });
                }
                actionConfirm = 'client_giveCards_choose';
            } else {
                translated = dojo.string.substitute( _("Confirm selected card(s)?"),
                {
                });
            }

            var selectedId = [];
            for (var i=0;i<selected.length;i++){
                selectedId.push(selected[i].id);
            }
            this.clientStateArgs.cardsSelectedId = selectedId.join(',');
            this.setClientStateAction(actionConfirm,translated);
        },

        onPapaChoiceLiraClick: function(evt){
            console.log( '$$$$ Event : onPapaChoiceLiraClick' );
            dojo.stopEvent( evt );
            var me = this;

            var action="";
            var actionConfirm="";

            if ( !this.isCurrentPlayerActive() ) {
                return;
            }

            action = 'choosePapaOption';
            actionConfirm = 'client_choosePapaOption_confirm';

            if (action){
                if( ! this.checkAction( action ) )
                { return; }

                var playerData = this.getPlayerData(this.getThisPlayerId());
                var papaInfo = this.gamedatas_local.papas[Number(playerData.papa)];
                this.clientStateArgs = {};
                this.clientStateArgs.action = action;
                this.clientStateArgs.option = 'lira';
                var translated = dojo.string.substitute( _("Confirm option ${tokenChoice} selection?"),{tokenChoice:this.getTokenSymbol('lira'+papaInfo.choice_lira)});
                this.setClientStateAction(actionConfirm,translated);
            }
        },

        onWakeupChooseYellowCardClick: function(evt){
            console.log( '$$$$ Event : onWakeupChooseYellowCardClick' );
            dojo.stopEvent( evt );
            var me = this;

            var action="";
            var actionConfirm="";

            if ( !this.isCurrentPlayerActive() ) {
                return;
            }

            action = 'chooseWakeup';
            actionConfirm = 'client_springChooseWakeupChooseCard_confirm';

            if (action){
                if( ! this.checkAction( action ) )
                { return; }

                this.clientStateArgs.card = 'yellow';

                this.ajaxClientStateAction();
            }
        },

        onWakeupChooseBlueCardClick: function(evt){
            console.log( '$$$$ Event : onWakeupChooseBlueCardClick' );
            dojo.stopEvent( evt );
            var me = this;

            var action="";
            var actionConfirm="";

            if ( !this.isCurrentPlayerActive() ) {
                return;
            }

            action = 'chooseWakeup';
            actionConfirm = 'client_springChooseWakeupChooseCard_confirm';

            if (action){
                if( ! this.checkAction( action ) )
                { return; }

                this.clientStateArgs.card = 'blue';

                this.ajaxClientStateAction();
            }
        },

        onChangeWakeupChooseYellowCardClick: function(evt){
            console.log( '$$$$ Event : onChangeWakeupChooseYellowCardClick' );
            dojo.stopEvent( evt );
            var me = this;

            var action="";
            var actionConfirm="";

            if ( !this.isCurrentPlayerActive() ) {
                return;
            }

            action = this.clientStateArgs.action;
            actionConfirm = 'client_changeWakeupChooseCard_confirm';

            if (action){
                if( ! this.checkAction( action ) )
                { return; }

                this.clientStateArgs.otherSelection = this.clientStateArgs.wakeupChart+'_yellow';

                this.ajaxClientStateAction();
            }
        },

        onChangeWakeupChooseBlueCardClick: function(evt){
            console.log( '$$$$ Event : onChangeWakeupChooseBlueCardClick' );
            dojo.stopEvent( evt );
            var me = this;

            var action="";
            var actionConfirm="";

            if ( !this.isCurrentPlayerActive() ) {
                return;
            }

            action = this.clientStateArgs.action;
            actionConfirm = 'client_changeWakeupChooseCard_confirm';

            if (action){
                if( ! this.checkAction( action ) )
                { return; }

                this.clientStateArgs.otherSelection = this.clientStateArgs.wakeupChart+'_blue';

                this.ajaxClientStateAction();
            }
        },


        onFallChooseYellowCardClick: function(evt){
            console.log( '$$$$ Event : onFallChooseYellowCardClick' );
            dojo.stopEvent( evt );
            var me = this;

            var action="";
            var actionConfirm="";

            if ( !this.isCurrentPlayerActive() ) {
                return;
            }

            action = 'chooseFallCard';
            actionConfirm = 'client_fallChooseCard_confirm';

            if (action){
                if( ! this.checkAction( action ) )
                { return; }

                this.clientStateArgs = {};
                this.clientStateArgs.action = action;
                this.clientStateArgs.card = 'yellow';
                this.clientStateArgs.cardSecond = null;

                this.ajaxClientStateAction();
            }
        },

        onFallChooseBlueCardClick: function(evt){
            console.log( '$$$$ Event : onFallChooseBlueCardClick' );
            dojo.stopEvent( evt );
            var me = this;

            var action="";
            var actionConfirm="";

            if ( !this.isCurrentPlayerActive() ) {
                return;
            }

            action = 'chooseFallCard';
            actionConfirm = 'client_fallChooseCard_confirm';

            if (action){
                if( ! this.checkAction( action ) )
                { return; }

                this.clientStateArgs = {};
                this.clientStateArgs.action = action;
                this.clientStateArgs.card = 'blue';
                this.clientStateArgs.cardSecond = null;

                this.ajaxClientStateAction();
            }
        },


        onFallChooseTwoYellowCardClick: function(evt){
            console.log( '$$$$ Event : onFallChooseTwoYellowCardClick' );
            dojo.stopEvent( evt );
            var me = this;

            var action="";
            var actionConfirm="";

            if ( !this.isCurrentPlayerActive() ) {
                return;
            }

            action = 'chooseFallCard';
            actionConfirm = 'client_fallChooseCard_confirm';

            if (action){
                if( ! this.checkAction( action ) )
                { return; }

                this.clientStateArgs = {};
                this.clientStateArgs.action = action;
                this.clientStateArgs.card = 'yellow';
                this.clientStateArgs.cardSecond = 'yellow';

                this.ajaxClientStateAction();
            }
        },

        onFallChooseTwoBlueCardClick: function(evt){
            console.log( '$$$$ Event : onFallChooseTwoBlueCardClick' );
            dojo.stopEvent( evt );
            var me = this;

            var action="";
            var actionConfirm="";

            if ( !this.isCurrentPlayerActive() ) {
                return;
            }

            action = 'chooseFallCard';
            actionConfirm = 'client_fallChooseCard_confirm';

            if (action){
                if( ! this.checkAction( action ) )
                { return; }

                this.clientStateArgs = {};
                this.clientStateArgs.action = action;
                this.clientStateArgs.card = 'blue';
                this.clientStateArgs.cardSecond = 'blue';

                this.ajaxClientStateAction();
            }
        },

        onFallChooseYellowCardAndBlueCardClick: function(evt){
            console.log( '$$$$ Event : onFallChooseYellowCardAndBlueCardClick' );
            dojo.stopEvent( evt );
            var me = this;

            var action="";
            var actionConfirm="";

            if ( !this.isCurrentPlayerActive() ) {
                return;
            }

            action = 'chooseFallCard';
            actionConfirm = 'client_fallChooseCard_confirm';

            if (action){
                if( ! this.checkAction( action ) )
                { return; }

                this.clientStateArgs = {};
                this.clientStateArgs.action = action;
                this.clientStateArgs.card = 'yellow';
                this.clientStateArgs.cardSecond = 'blue';

                this.ajaxClientStateAction();
            }
        },

        onChooseVisitorCardDrawYellowCard: function(evt){
            console.log( '$$$$ Event : onChooseVisitorCardDrawYellowCard' );
            dojo.stopEvent( evt );
            var me = this;

            var action="";
            var actionConfirm="";

            if ( !this.isCurrentPlayerActive() ) {
                return;
            }

            action = 'chooseVisitorCardDraw';
            actionConfirm = 'client_chooseVisitorCardDraw_confirm';

            if (action){
                if( ! this.checkAction( action ) )
                { return; }

                this.clientStateArgs = {};
                this.clientStateArgs.action = action;
                this.clientStateArgs.card = 'yellow';

                this.ajaxClientStateAction();
            }
        },

        onChooseVisitorCardDrawGreenCard: function(evt){
            console.log( '$$$$ Event : onChooseVisitorCardDrawGreenCard' );
            dojo.stopEvent( evt );
            var me = this;

            var action="";
            var actionConfirm="";

            if ( !this.isCurrentPlayerActive() ) {
                return;
            }

            action = 'chooseVisitorCardDraw';
            actionConfirm = 'client_chooseVisitorCardDraw_confirm';

            if (action){
                if( ! this.checkAction( action ) )
                { return; }

                this.clientStateArgs = {};
                this.clientStateArgs.action = action;
                this.clientStateArgs.card = 'green';

                this.ajaxClientStateAction();
            }
        },


        onWakeupOrderSlotClick: function(evt){
            console.log( '$$$$ Event : onWakeupOrderSlotClick' );
            dojo.stopEvent( evt );
            var me = this;
            var element = evt.currentTarget;
            var action="";
            var actionConfirm="";
            var value = element.getAttribute('data-arg');

            if ( !this.isCurrentPlayerActive() ) {
                return;
            }

            action = 'chooseWakeup';

            if (action){
                if( ! this.checkAction( action ) )
                { return; }

                this.queryAndRemoveClass('.wakeupOrder_slot.selected','selected');
                dojo.addClass(element,'selected');

                this.clientStateArgs = {};
                this.clientStateArgs.action = action;
                this.clientStateArgs.value = value;
                this.activatePreviewToken('rooster',element.id, 'rooster', true);
                var translated;
                if (value==5){
                    translated = dojo.string.substitute( _("Confirm wakeup order ${value} selection and choose card to draw "),{value:value});
                    actionConfirm = 'client_springChooseWakeupChooseCard_confirm';
                } else {
                    var effect;
                    if (value==1){
                        effect=_('First player');
                    } else if (value==2){
                        effect=dojo.string.substitute(_('Draw ${token_card}'),{token_card:this.getTokenSymbol('greenCardPlus')});
                    } else if (value==3){
                        effect=dojo.string.substitute(_('Draw ${token_card}'),{token_card:this.getTokenSymbol('purpleCardPlus')});
                    } else if (value==4){
                        effect=dojo.string.substitute(_('Get ${token_get}'),{token_get:this.getTokenSymbol('lira1')});
                    } else if (value==6){
                        effect=dojo.string.substitute(_('Get ${token_get}'),{token_get:this.getTokenSymbol('vp1')});
                    } else if (value==7){
                        effect=dojo.string.substitute(_('Get ${token_get}'),{token_get:this.getTokenSymbol('worker_t')});
                    }
                    translated = dojo.string.substitute( _("Confirm wakeup order ${value}:"),{value:value})+' '+effect;
                    actionConfirm = 'client_springChooseWakeup_confirm';
                }
                this.setClientStateAction(actionConfirm,translated);
            }
        },

        onChangeWakeupOrderSlotClick: function(evt){
            console.log( '$$$$ Event : onChangeWakeupOrderSlotClick' );
            dojo.stopEvent( evt );
            var me = this;
            var element = evt.currentTarget;
            var action="";
            var actionConfirm="";
            var value = element.getAttribute('data-arg');

            if ( !this.isCurrentPlayerActive() ) {
                return;
            }

            action = this.clientStateArgs.action;

            if (action){
                if( ! this.checkAction( action ) )
                { return; }

                this.queryAndRemoveClass('.wakeupOrder_slot.selected','selected');
                dojo.addClass(element,'selected');

                this.clientStateArgs.otherSelection = value;
                this.clientStateArgs.wakeupChart = value;
                this.activatePreviewToken('rooster',element.id, 'rooster', true);
                var translated;
                if (value==5){
                    translated = dojo.string.substitute( _("Confirm wakeup order ${value} selection and choose card to draw "),{value:value});
                    actionConfirm = 'client_changeWakeupChooseCard_confirm';
                } else {
                    translated = dojo.string.substitute( _("Confirm wakeup order ${value} selection?"),{value:value});
                    actionConfirm = 'client_changeWakeup_confirm';
                }
                this.setClientStateAction(actionConfirm,translated);
            }
        },

        onBoardActionSlotWorkerClick: function(evt){
            console.log( '$$$$ Event : onBoardActionSlotWorkerClick' );
            dojo.stopEvent( evt );
            var me = this;
            var element = evt.currentTarget;
            var action="";
            var actionConfirm="";
            var value = Number(element.getAttribute('data-arg'));

            if ( !this.isCurrentPlayerActive() ) {
                return;
            }

            action = 'placeWorker';

            if (action){
                if( ! this.checkAction( action ) )
                { return; }

                var actionValue = 0;
                for (var i=0;i< this.gamedatas_local.activeLocations.length;i++){
                    if (this.gamedatas_local.activeLocations[i].t == value){
                        actionValue = this.gamedatas_local.activeLocations[i].a;
                        if (actionValue<=0){
                            return;
                        }
                        break;
                    }
                }

                this.queryAndRemoveClass('.action_slot_worker.selected','selected');

                var forceWorkerPlacement = this.clientStateArgs.forceWorkerPlacement;
                this.clientStateArgs = {};
                this.clientStateArgs.forceWorkerPlacement = forceWorkerPlacement;
                this.clientStateArgs.action = action;
                this.clientStateArgs.location = value;
                this.clientStateArgs.worker_g = 0;
                this.clientStateArgs.tokenWorker = 'worker';
                this.selectWorkerToPlace();

                this.clearInteractiveItems(true,true);
                this.enableBoardLocations();

                dojo.addClass(element,'selected');

                this.activatePreviewToken(this.clientStateArgs.tokenWorker,element.id, 'worker', true);

                this.processActionSlot();
            }
        },

        onBoardTakeActionPrevClick: function(evt){
            console.log( '$$$$ Event : onBoardTakeActionPrevClick' );
            dojo.stopEvent( evt );
            var me = this;
            var element = evt.currentTarget;
            var action="";
            var actionConfirm="";
            var value = Number(element.getAttribute('data-arg'));

            if ( !this.isCurrentPlayerActive() ) {
                return;
            }

            action = this.clientStateArgs.action;

            if (action){
                if( ! this.checkAction( action ) )
                { return; }

                var actionValue = 0;
                for (var i=0;i< this.gamedatas_local.activeLocationsPrev.length;i++){
                    if (this.gamedatas_local.activeLocationsPrev[i].t == value){
                        actionValue = this.gamedatas_local.activeLocationsPrev[i].a;
                        if (actionValue<=0){
                            return;
                        }
                        break;
                    }
                }

                this.queryAndRemoveClass('.action_slot_worker.selected','selected');
                dojo.addClass(element,'selected');

                this.clientStateArgs = {};
                this.clientStateArgs.action = action;
                this.clientStateArgs.location = value;

                this.processActionSlot();
            }
        },

        onBoardSelectFutureLocationClick: function(evt){
            console.log( '$$$$ Event : onBoardSelectFutureLocationClick' );
            dojo.stopEvent( evt );
            var me = this;
            var element = evt.currentTarget;
            var action="";
            var actionConfirm="";
            var value = Number(element.getAttribute('data-arg'));
            var translated;

            if ( !this.isCurrentPlayerActive() ) {
                return;
            }

            action = this.clientStateArgs.action;
            actionConfirm = 'client_selectFutureLocation_confirm';

            if (action){
                if( ! this.checkAction( action ) )
                { return; }

                this.queryAndRemoveClass('.action_slot_worker.selected','selected');
                if (this.clientStateArgs.location){
                    dojo.addClass('action_slot_'+this.clientStateArgs.location,'selected');
                }
                dojo.addClass(element,'selected');

                this.clientStateArgs.otherSelection = value+'_'+this.clientStateArgs.futureWorker;

                this.activatePreviewToken(this.clientStateArgs.tokenFutureWorker,element.id, 'futureWorker', false);

                translated = _('Confirm worker placement?');

                this.setClientStateAction(actionConfirm,translated);

            }
        },

        processActionSlot: function(){
            var actionConfirm;
            var translated;
            var playerId = this.getThisPlayerId();
            var playerData = this.gamedatas_local.players[playerId];
            var location = this.arrayFindByProperty(this.gamedatas_local.locations, 'key', this.clientStateArgs.location);
            var lira = Number(playerData.lira);

            this.queryAndDisconnectEvent('.playerboard .active_slot:not(.action_slot_worker )','click');
            this.queryAndRemoveClass('.playerboard .active_slot:not(.action_slot_worker )','active_slot');
            this.queryAndRemoveClass('.playerboard .selected:not(.action_slot_worker )','selected');

            switch (location.action) {
                case 'playYellowCard_1':
                    this.enableActionPlayYellowCard();
                    break;

                case 'buildStructure_1':
                    if (location.bonus == 'getDiscountLira1'){
                        this.enableActionBuild(1, dojo.string.substitute( _("Choose a structure to build with discount ${token_lira1}"),
                        {token_lira1: this.getTokenSymbol('lira1')}),null,false);
                    } else {
                        this.enableActionBuild(0, dojo.string.substitute( _("Choose a structure to build"),
                        {}),null,false);
                    }
                    break;

                case 'sellGrapes_1|buySellVine_1':
                    this.enableActionSellGrapesSellBuyVine(true, true);
                    break;

                case 'plant_1':
                    this.enableActionPlant(true, true, null, false);
                    break;

                case 'harvestField_1':
                    if (location.bonus == 'harvestField_1'){
                        this.enableActionHarvestField(2);
                    } else {
                        this.enableActionHarvestField(1);
                    }
                    break;

                case 'fillOrder_1':
                    this.enableActionFillOrder();
                    break;

                case 'makeWine_2':
                    this.enableActionMakeWine(true, false);
                    break;

                case 'playBlueCard_1':
                    this.enableActionPlayBlueCard();
                    break;

                case 'uproot_1|harvestField_1':
                    actionConfirm='client_uprootHarvest_choose';
                    translated = dojo.string.substitute( _("Choose the action"),{});
                    break;

                default:
                    var effect = '';
                    var liraPreviewIncrement = 0;
                    var action = location.action;
                    if (location.bonus){
                        action+='+'+location.bonus;
                    }
                    if (action=='drawGreenCard_1'){
                        effect = dojo.string.substitute( _('Draw ${token_card}'),{token_card: this.getTokenSymbol('greenCardPlus')});
                    } else if (action=='drawGreenCard_1+drawGreenCard_1'){
                        effect = dojo.string.substitute( _('Draw ${token_card}'),{token_card: this.getTokenSymbol('greenCardPlus')+this.getTokenSymbol('greenCardPlus')});
                    } else if (action=='getLira_1'){
                        effect = dojo.string.substitute(_('Get ${token_get}'),{token_get: this.getTokenSymbol('lira1')});
                        liraPreviewIncrement=1;
                    } else if (action=='getLira_2'){
                        effect = dojo.string.substitute(_('Get ${token_get}'),{token_get: this.getTokenSymbol('lira2')});
                        liraPreviewIncrement=2;
                    } else if (action=='getLira_2+getLira_1'){
                        effect = dojo.string.substitute(_('Get ${token_get}'),{token_get: this.getTokenSymbol('lira3')});
                        liraPreviewIncrement=3;
                    } else if (action=='drawPurpleCard_1'){
                        effect = dojo.string.substitute( _('Draw ${token_card}'),{token_card: this.getTokenSymbol('purpleCardPlus')});
                    } else if (action=='drawPurpleCard_1+drawPurpleCard_1'){
                        effect = dojo.string.substitute( _('Draw ${token_card}'),{token_card: this.getTokenSymbol('purpleCardPlus')+this.getTokenSymbol('purpleCardPlus')});
                    } else if (action=='trainWorker_1'){
                        effect = dojo.string.substitute( _('Train ${token_train} for ${token_price}'),{token_train: this.getTokenSymbol('worker'), token_price:this.getTokenSymbol('lira4')});
                        liraPreviewIncrement=-4;
                    } else if (action=='trainWorker_1+getDiscountLira1'){
                        effect = dojo.string.substitute( _('Train ${token_train} for ${token_price}'),{token_train: this.getTokenSymbol('worker'), token_price:this.getTokenSymbol('lira3')});
                        liraPreviewIncrement=-3;
                    }
                    if (effect){
                        effect=effect+'. ';
                    }
                    var liraEffect = '';
                    if (liraPreviewIncrement!=0){
                        liraEffect = ' '+this.getPreviewLira(this.getThisPlayerId(), liraPreviewIncrement);
                    }
                    if (this.clientStateArgs.action){
                        translated = effect+dojo.string.substitute( _("Confirm location selection?"),{})+liraEffect;
                    } else {
                        translated = effect+dojo.string.substitute( _("Confirm ${token_worker} placement?"),{token_worker: this.getTokenPlayerSymbol(this.getThisPlayerId(), this.clientStateArgs.tokenWorker )})+liraEffect;
                    }
                    actionConfirm = 'client_workerPlacement_confirm';
                    break;
            }

            if (actionConfirm){
                this.setClientStateAction(actionConfirm,translated);
            }
        },

        enableActionSelectStructures: function(message){
            var playerId = this.getThisPlayerId();
            var playerData = this.getPlayerData(playerId);

            for (var i=0; i< this.gamedatas_local.playerTokens.length;i++){
                var playerToken = this.gamedatas_local.playerTokens[i];
                //building, not yet builded
                if (playerToken.isBuilding  && playerData[playerToken.type]==0){
                    this.queryAndAddClass( '.action_slot.building_slot.building_slot_'+playerToken.type, 'active_slot');
                    this.queryAndAddEvent( '.action_slot.building_slot.building_slot_'+playerToken.type, 'click', 'onSelectStructuresClick' );
                }
            }
            actionConfirm = 'client_selectStructures_choose';
            this.scrollTo('playerboard_row_'+playerId);
            if (message){
                this.setClientStateAction(actionConfirm,message);
            }
        },

        enableActionBuild: function(discount, message, structureFilterPrices, canBuildNothing){
            var playerId = this.getThisPlayerId();
            var playerData = this.getPlayerData(playerId);
            var lira = Number(playerData.lira);

            this.clientStateArgs.discount = discount;
            this.clientStateArgs.canBuildNothing = canBuildNothing;

            for (var i=0; i< this.gamedatas_local.playerTokens.length;i++){
                var playerToken = this.gamedatas_local.playerTokens[i];
                var structurePrice=true;
                if (structureFilterPrices){
                    if (this.arrayFind(structureFilterPrices, Number(playerToken.price))==null){
                        structurePrice=false;
                    }
                }

                if (playerToken.type=='largeCellar' && playerData['mediumCellar']==0){
                    //largeCellar can be upgraded only if mediumCellar built
                } else if (playerToken.isBuilding && structurePrice && playerData[playerToken.type]==0 && lira+this.clientStateArgs.discount >= playerToken.price){
                    //building, not yet builded, enough lira
                    this.queryAndAddClass( '.action_slot.building_slot.building_slot_'+playerToken.type, 'active_slot');
                    this.queryAndAddEvent( '.action_slot.building_slot.building_slot_'+playerToken.type, 'click', 'onChooseBuilding' );
                }
            }
            actionConfirm = 'client_buildStructure_choose';
            this.scrollTo('playerboard_row_'+playerId);
            if (message){
                this.setClientStateAction(actionConfirm,message);
            }
        },

        enableActionDiscardCards: function(minCardsToDiscard, maxCardsToDiscard, cardTypes, cardIdToExclude, discardWine, wineTypeRequired){
            var playerBoardId = 'playerboard_'+this.getThisPlayerId();
            var playerId = this.getThisPlayerId();

            this.clientStateArgs.minCardsToDiscard = minCardsToDiscard;
            this.clientStateArgs.maxCardsToDiscard = maxCardsToDiscard;
            this.clientStateArgs.cardTypes = cardTypes;
            this.clientStateArgs.cardIdToExclude = cardIdToExclude;
            this.clientStateArgs.discardWine = discardWine;

            if (minCardsToDiscard==1&&maxCardsToDiscard==1){
                this.handZone.setSelectionMode(1);
            } else {
                this.handZone.setSelectionMode(2);
            }
            var playerHand = $('playerboard_hand_zone_'+playerId);
            if (playerHand){
                playerHand.setAttribute('data-selectiontype','discard');
            }
            this.disconnect( this.handZone,'onChangeSelection');
            this.connect( this.handZone, 'onChangeSelection', 'onChooseCardDiscardSelection' );

            var items = this.handZone.getAllItems();
            for (var i = 0;i<items.length;i++){
                var id = items[i].id;
                var disabled = false;
                if (cardIdToExclude && id == cardIdToExclude){
                    disabled = true;
                }
                if (this.gamedatas_local.actionProgress && this.gamedatas_local.actionProgress.card_id){
                    if (id == this.gamedatas_local.actionProgress.card_id){
                        disabled = true;
                    }
                }
                if (cardTypes){
                    var cardType = this.getCardType(items[i].type);
                    if (this.arrayFind(cardTypes, cardType)==null){
                        disabled = true;
                    }
                }
                if (disabled){
                    dojo.addClass(this.handZone.getItemDivId(id), 'disabled');
                } else {
                    dojo.removeClass(this.handZone.getItemDivId(id), 'disabled');
                }
            }

            if (discardWine){
                //loop over winetypes
                for (var wineType in this.wines){
                    if (wineTypeRequired=='' || wineTypeRequired==wineType){
                        this.queryAndAddClass('#'+playerBoardId+' .wine.'+wineType, 'active_slot');
                        this.queryAndAddEvent('#'+playerBoardId+' .wine.'+wineType, 'click', 'onChooseWineWithCardsDiscard' );
                    }
                }
                var wines=dojo.query('#'+playerBoardId+' .wine.active_slot');

                this.scrollTo('playerboard_row_'+playerId);

                if (wines.length==1){
                    this.sendEventClick(wines[0]);
                } else {
                    this.setClientStateAction('client_discardWines_choose',_('Choose the wine to discard'));
                }
            }

            this.scrollTo('playerboard_row_'+this.getThisPlayerId());
        },

        enableActionGiveCards: function(minCards, maxCards, playerIdGive, playerNameGive, cardTypes, cardIdToExclude){
            this.clientStateArgs.minCards = minCards;
            this.clientStateArgs.maxCards = maxCards;
            this.clientStateArgs.playerIdGive = playerIdGive;
            this.clientStateArgs.playerNameGive = playerNameGive;
            this.clientStateArgs.cardTypes = cardTypes;
            this.clientStateArgs.cardIdToExclude = cardIdToExclude;

            if (minCards==1&&maxCards==1){
                this.handZone.setSelectionMode(1);
            } else {
                this.handZone.setSelectionMode(2);
            }
            this.disconnect( this.handZone,'onChangeSelection');
            this.connect( this.handZone, 'onChangeSelection', 'onChooseCardGiveSelection' );

            var items = this.handZone.getAllItems();
            for (var i = 0;i<items.length;i++){
                var id = items[i].id;
                var disabled = false;
                if (cardIdToExclude && id == cardIdToExclude){
                    disabled = true;
                }
                if (cardTypes){
                    var cardType = this.getCardType(items[i].type);
                    if (this.arrayFind(cardTypes, cardType)==null){
                        disabled = true;
                    }
                }
                if (disabled){
                    dojo.addClass(this.handZone.getItemDivId(id), 'disabled');
                } else {
                    dojo.removeClass(this.handZone.getItemDivId(id), 'disabled');
                }
            }

            this.scrollTo('playerboard_row_'+this.getThisPlayerId());
        },

        enableActionChooseCards: function(){
            // Choose cards by player

            for( var i=0;i<this.gamedatas_local.chooseCards.length;i++)
            {
                var card = this.gamedatas_local.chooseCards[i];

                //add if not present
                if (! this.chooseCardsStock.getItemById(card.i)){
                    //TODO: animation? from
                    this.chooseCardsStock.addToStockWithId(card.k, card.i);
                }
            }
            this.chooseCardsStock.unselectAll();

            this.chooseCardsStock.setSelectionMode(2);
            this.disconnect( this.chooseCardsStock,'onChangeSelection');
            this.connect( this.chooseCardsStock, 'onChangeSelection', 'onChooseCardsStockSelection' );

            this.show('choose_cards_section');

        },

        enableActionDiscardWine: function(wineTypeRequired, minWineValue, maxWineValue, text, textConfirm){
            var playerId = this.getThisPlayerId();
            this.clientStateArgs.minWineValue = minWineValue;
            this.clientStateArgs.maxWineValue = maxWineValue;
            this.clientStateArgs.wineTypeRequired = wineTypeRequired;
            this.clientStateArgs.discardWineConfirm = textConfirm;
            var playerBoardId = 'playerboard_'+this.getThisPlayerId();

            //loop over winetypes
            for (var wineType in this.wines){
                if (wineTypeRequired=='' || wineTypeRequired==wineType){
                    for (var i=minWineValue;i<=maxWineValue;i++){
                        this.queryAndAddClass('#'+playerBoardId+' .wine.'+wineType+'[data-arg='+i+']','active_slot');
                        this.queryAndAddEvent('#'+playerBoardId+' .wine.'+wineType+'[data-arg='+i+']', 'click', 'onChooseWineDiscard' );
                    }
                }
            }
            var wines=dojo.query('#'+playerBoardId+' .wine.active_slot');

            this.scrollTo('playerboard_row_'+playerId);

            if (wines.length==1){
                this.sendEventClick(wines[0]);
            } else {
                this.setClientStateAction('client_discardWines_choose',text);
            }

        },

        onChooseWineDiscard: function(evt){
            console.log( '$$$$ Event : onChooseWineDiscard' );
            dojo.stopEvent( evt );
            var element = evt.currentTarget;
            var action=this.clientStateArgs.action;
            var wineType = element.getAttribute('data-type');
            var wineValue = Number(element.getAttribute('data-arg'));
            var playerBoardId = 'playerboard_'+this.getThisPlayerId();
            var actionConfirm;
            translated = this.clientStateArgs.discardWineConfirm;

            if ( !this.isCurrentPlayerActive() ) {
                return;
            }

            if (action){
                if( ! this.checkAction( action ) )
                { return; }

                this.queryAndRemoveClass('#'+playerBoardId+' .wine.selected','selected');
                dojo.addClass(element,'selected');

                this.clientStateArgs.wine = wineType;
                this.clientStateArgs.wineValue = wineValue;

                actionConfirm = 'client_discardWine_confirm';
                this.setClientStateAction(actionConfirm,translated);
            }
        },

        enableActionAgeWine: function(text, textConfirm){
            var playerId = this.getThisPlayerId();
            var playerData = this.getPlayerData(playerId);
            this.clientStateArgs.ageWineConfirm = textConfirm;
            var playerBoardId = 'playerboard_'+this.getThisPlayerId();

            //loop over winetypes
            for (var wineType in this.wines){
                for (var i=1;i<=8;i++){
                    var age=i+1;
                    var canAge=true;
                    //wine can be in 4-6 without having mediumCellar, in this case it can age up to 6
                    //wine can be in 7-9 without having largeCellar, in this case it can age up to 9
                    if (i==3 && playerData.mediumCellar==0){
                        canAge=false;
                    }
                    if (i==6 && playerData.largeCellar==0){
                        canAge=false;
                    }
                    if (canAge){
                        if (this.queryCount('#'+playerBoardId+' .wine.'+wineType+'[data-arg='+age+']')==0){
                            this.queryAndAddClass('#'+playerBoardId+' .wine.'+wineType+'[data-arg='+i+']','active_slot');
                            this.queryAndAddEvent('#'+playerBoardId+' .wine.'+wineType+'[data-arg='+i+']', 'click', 'onChooseAgeWine' );
                        }
                    }
                }
            }
            var wines=dojo.query('#'+playerBoardId+' .wine.active_slot');

            this.scrollTo('playerboard_row_'+playerId);

            if (wines.length==1){
                this.sendEventClick(wines[0]);
            } else if (wines.length==0){
                this.setClientStateAction('client_ageWine_nowine',text);
            } else {
                this.setClientStateAction('client_ageWine_choose',text);
            }

        },

        onChooseAgeWine: function(evt){
            console.log( '$$$$ Event : onChooseAgeWine' );
            dojo.stopEvent( evt );
            var element = evt.currentTarget;
            var action=this.clientStateArgs.action;
            var wineType = element.getAttribute('data-type');
            var wineValue = Number(element.getAttribute('data-arg'));
            var playerBoardId = 'playerboard_'+this.getThisPlayerId();
            var actionConfirm;
            translated = this.clientStateArgs.ageWineConfirm;

            if ( !this.isCurrentPlayerActive() ) {
                return;
            }

            if (action){
                if( ! this.checkAction( action ) )
                { return; }

                this.queryAndRemoveClass('#'+playerBoardId+' .wine.selected','selected');
                dojo.addClass(element,'selected');

                this.clientStateArgs.wine = wineType;
                this.clientStateArgs.wineValue = wineValue;

                actionConfirm = 'client_ageWine_confirm';
                this.setClientStateAction(actionConfirm,translated);
            }
        },

        onChooseWineWithCardsDiscard: function(evt){
            console.log( '$$$$ Event : onChooseWineWithCardsDiscard' );
            dojo.stopEvent( evt );

            var element = evt.currentTarget;
            var action=this.clientStateArgs.action;
            var wineType = element.getAttribute('data-type');
            var wineValue = Number(element.getAttribute('data-arg'));
            var playerBoardId = 'playerboard_'+this.getThisPlayerId();
            var actionConfirm;
            translated = this.clientStateArgs.discardWineConfirm;

            if ( !this.isCurrentPlayerActive() ) {
                return;
            }

            if (action){
                if( ! this.checkAction( action ) )
                { return; }

                this.queryAndRemoveClass('#'+playerBoardId+' .wine.selected','selected');
                dojo.addClass(element,'selected');

                this.clientStateArgs.wine = wineType;
                this.clientStateArgs.wineValue = wineValue;

                this.checkDiscardCardsAndWine();
            }
        },

        enableActionChangeWakeup: function(text){
            var playerId = this.getThisPlayerId();
            this.queryAndDisconnectEvent('#board .active_slot','click');
            this.queryAndDisconnectEvent('#playerboard_row .active_slot','click');
            this.queryAndRemoveClass('#board .active_slot','active_slot');
            this.queryAndRemoveClass('#playerboard_row .active_slot','active_slot');
            for (var i = 1;i<=7;i++){
                var found = false;
                for (var playerId in this.gamedatas_local.players){
                    if (this.gamedatas_local.players[playerId].wakeup_chart==i){
                        found = true;
                    }
                }

                //in solo mode cannot change rooster to position 7
                //Organizer
                if (this.gamedatas_local.soloMode>0){
                    if (i==7){
                        found = true;
                    }
                }

                if (!found){
                    this.queryAndAddEvent('#wakeupOrder_slot_'+i,'click','onChangeWakeupOrderSlotClick');
                    this.queryAndAddClass('#wakeupOrder_slot_'+i,'active_slot');
                }
            }
            //TODO: autoselect if only only one?
            this.scrollTo('board');
            this.setClientStateAction('client_changeWakeup_choose',text);
        },

        enableActionDiscardGrape: function(grapeTypeRequired, minGrapeValue, maxGrapeValue, text, textConfirm){
            var playerId = this.getThisPlayerId();
            this.clientStateArgs.minGrapeValue = minGrapeValue;
            this.clientStateArgs.maxGrapeValue = maxGrapeValue;
            this.clientStateArgs.grapeTypeRequired = grapeTypeRequired;
            this.clientStateArgs.discardGrapeConfirm = textConfirm;
            var playerBoardId = 'playerboard_'+this.getThisPlayerId();

            //loop over grapes
            for (var grapeType in this.grapes){
                if (grapeTypeRequired=='' || grapeTypeRequired==grapeType){
                    for (var i=minGrapeValue;i<=maxGrapeValue;i++){
                        this.queryAndAddClass('#'+playerBoardId+' .grape.'+grapeType+'[data-arg='+i+']','active_slot');
                        this.queryAndAddEvent('#'+playerBoardId+' .grape.'+grapeType+'[data-arg='+i+']', 'click', 'onChooseGrapeDiscard' );
                    }
                }
            }
            var grapes=dojo.query('#'+playerBoardId+' .grape.active_slot');

            this.scrollTo('playerboard_row_'+playerId);

            if (grapes.length==1){
                this.sendEventClick(grapes[0]);
            } else {
                this.setClientStateAction('client_discardGrape_choose',text);
            }

        },

        enableActionSelectPlayers(minPlayers, maxPlayers, text, textConfirm){
            var playerId = this.getThisPlayerId();
            this.clientStateArgs.minPlayers = minPlayers;
            this.clientStateArgs.maxPlayers = maxPlayers;
            this.clientStateArgs.selectPlayersText = text;
            this.clientStateArgs.selectPlayersConfirm = textConfirm;

            var playersHtml='';
            var otherPlayerId = '';
            for (var playerId in this.gamedatas_local.players){
                var player = this.gamedatas_local.players[playerId];
                if (playerId != this.getThisPlayerId()){
                    playersHtml+=this.format_block('jstpl_player_selection', player );
                    otherPlayerId = playerId;
                }
            }
            dojo.place(playersHtml,'choose_players_section','only');
            this.queryAndAddEvent('.player_selection input[type=checkbox]','click','onSelectPlayerClick');

            this.setClientStateAction('client_selectPlayers_choose',text);

            //auto-select other players
            if (this.gamedatas_local.players_number==2){
                var checkbox = $('chk_'+otherPlayerId);
                checkbox.checked = true;
                this.sendEventClick(checkbox);
            }

            this.scrollTo('choose_players_section');
        },


        onSelectPlayerClick: function(evt){
            console.log( '$$$$ Event : onSelectPlayerClick' );
            //dojo.stopEvent( evt );
            var element = evt.currentTarget;
            var action=this.clientStateArgs.action;
            var actionConfirm;
            translated = this.clientStateArgs.discardGrapeConfirm;

            if ( !this.isCurrentPlayerActive() ) {
                return;
            }

            if (action){
                if( ! this.checkAction( action ) )
                { return; }

                var selected = [];

                if (element.checked){
                    dojo.addClass(element.parentElement, 'selected');
                } else {
                    dojo.removeClass(element.parentElement, 'selected');
                }

                var playersSelected = dojo.query('.player_selection input[type=checkbox]:checked');
                for (var i=0;i<playersSelected.length;i++){
                    selected.push(playersSelected[i].value);
                }

                if (selected.length<this.clientStateArgs.minPlayers || selected.length>this.clientStateArgs.maxPlayers ){
                    this.setClientStateAction('client_selectPlayers_choose',this.clientStateArgs.selectPlayersText);
                    return;
                }

                this.clientStateArgs.otherSelection = selected.join('_');
                var text=this.clientStateArgs.selectPlayersConfirm;

                if (this.clientStateArgs.visitorCardKey==638){
                    var lira = 2*selected.length;
                    var vp = selected.length;
                    text = dojo.string.substitute(_('Confirm to pay ${token_lira} to get ${token_vp}'),{token_lira: this.getTokenSymbol('lira'+lira), token_vp:this.getTokenSymbol('vp'+vp)});
                    text+=' '+this.getPreviewLira(this.getThisPlayerId(),-lira);
                }

                actionConfirm = 'choose_players_confirm';
                this.setClientStateAction(actionConfirm,text);
            }
        },

        onSelectWorkerClick: function(evt){
            console.log( '$$$$ Event : onSelectWorkerClick' );
            dojo.stopEvent( evt );
            var element = evt.currentTarget;
            if (this.clientStateArgs.forceWorkerPlacement == 'worker_g'){
                dojo.removeClass(element,'selected');
                this.clientStateArgs.forceWorkerPlacement = null;
                this.selectWorkerToPlace();
            } else {
                dojo.addClass(element,'selected');
                this.clientStateArgs.forceWorkerPlacement = 'worker_g';
                this.clientStateArgs.worker_g = 1;
                this.clientStateArgs.tokenWorker = 'worker_g';
            }
            if (this.clientStateArgs.previewTokens.worker){
                this.activatePreviewToken(this.clientStateArgs.tokenWorker,this.clientStateArgs.previewTokens.worker.e, 'worker', true);
            }
        },

        enableActionSelectWorkers: function(minWorkers, maxWorkers, text, textConfirm){
            var playerId = this.getThisPlayerId();
            this.clientStateArgs.minWorkers = minWorkers;
            this.clientStateArgs.maxWorkers = maxWorkers;
            this.clientStateArgs.selectWorkersText = text;
            this.clientStateArgs.selectWorkersConfirm = textConfirm;

            this.queryAndDisconnectEvent('#board .active_slot','click');
            this.queryAndRemoveClass('#board .active_slot','active_slot');

            var locationToExclude='';
            if (this.gamedatas_local.actionProgress){
                locationToExclude = 'board_'+this.gamedatas_local.actionProgress.args;
            }

            for (var i=0;i<this.gamedatas_local.tokens[playerId].length;i++){
                var token = this.gamedatas_local.tokens[playerId][i];
                if (token.l.indexOf('board')==0 && token.l.indexOf('_new')==-1 && token.t.indexOf('worker')==0 && token.l!=locationToExclude){
                    var tokenId = this.calculateTokenId(playerId, token.t, token.i);
                    this.queryAndAddClass('#'+tokenId,'active_slot');
                    this.queryAndAddEvent('#'+tokenId, 'click', 'onChooseWorkerClick' );
                }
            }
            //TODO: autoselect only workers equals to maxWorkers?
            this.setClientStateAction('client_selectWorkers_choose',text);

        },

        onChooseWorkerClick: function(evt){
            console.log( '$$$$ Event : onChooseWorkerClick' );
            dojo.stopEvent( evt );
            var element = evt.currentTarget;
            var action=this.clientStateArgs.action;
            translated = this.clientStateArgs.discardGrapeConfirm;

            if ( !this.isCurrentPlayerActive() ) {
                return;
            }

            if (action){
                if( ! this.checkAction( action ) )
                { return; }

                if (dojo.hasClass(element,'selected')){
                    dojo.removeClass(element,'selected');
                } else {
                    if (this.clientStateArgs.maxWorkers == 1){
                        this.queryAndRemoveClass('.worker.selected','selected');
                    }
                    dojo.addClass(element,'selected');
                }
                this.clientStateArgs.workersSelected = [];
                var workersSelected = dojo.query('.worker.selected');
                for (var i=0;i<workersSelected.length;i++){
                    this.clientStateArgs.workersSelected.push(Number(workersSelected[i].getAttribute('data-id')));
                }

                this.checkSelectWorkersAction();
            }
        },

        enableActionChooseDiscard: function(){
            this.clientStateArgs.minCards = 2;
            this.clientStateArgs.maxCards = 2;
            this.clientStateArgs.selectDiscardText = _('Select top card of 2 different discard piles');
            this.clientStateArgs.selectDiscardConfirm = _('Confirm selection?');
            var text = this.clientStateArgs.selectDiscardText;

            this.queryAndDisconnectEvent('#board .active_slot','click');
            this.queryAndRemoveClass('#board .active_slot','active_slot');

            this.queryAndAddClass('#board .card_discard','active_slot');
            this.queryAndAddEvent('#board .card_discard', 'click', 'onChooseDiscardCardClick' );
            //TODO: autoselect only cards equals to minCards/maxCards?
            this.setClientStateAction('client_selectDiscardCard_choose',text);

        },

        onChooseDiscardCardClick: function(evt){
            console.log( '$$$$ Event : onChooseDiscardCardClick' );
            dojo.stopEvent( evt );
            var element = evt.currentTarget;
            var action=this.clientStateArgs.action;
            translated = this.clientStateArgs.selectDiscardConfirm;

            if ( !this.isCurrentPlayerActive() ) {
                return;
            }

            if (action){
                if( ! this.checkAction( action ) )
                { return; }

                if (dojo.hasClass(element,'selected')){
                    dojo.removeClass(element,'selected');
                } else {
                    if (this.clientStateArgs.maxCards == 1){
                        this.queryAndRemoveClass('#board .card_discard.selected','selected');
                    }
                    dojo.addClass(element,'selected');
                }
                this.clientStateArgs.cardsSelected = [];
                var cardsSelected = dojo.query('#board .card_discard.selected');
                for (var i=0;i<cardsSelected.length;i++){
                    this.clientStateArgs.cardsSelected.push(Number(cardsSelected[i].getAttribute('data-id')));
                }

                this.checkSelectDiscardCardsAction();
            }
        },

        onChooseGrapeDiscard: function(evt){
            console.log( '$$$$ Event : onChooseGrapeDiscard' );
            dojo.stopEvent( evt );
            var element = evt.currentTarget;
            var action=this.clientStateArgs.action;
            var grapeType = element.getAttribute('data-type');
            var grapeValue = Number(element.getAttribute('data-arg'));
            var playerBoardId = 'playerboard_'+this.getThisPlayerId();
            var actionConfirm;
            translated = this.clientStateArgs.discardGrapeConfirm;

            if ( !this.isCurrentPlayerActive() ) {
                return;
            }

            if (action){
                if( ! this.checkAction( action ) )
                { return; }

                this.queryAndRemoveClass('#'+playerBoardId+' .grape.selected','selected');
                dojo.addClass(element,'selected');

                this.clientStateArgs.grapesId = element.id.split('_')[2];

                actionConfirm = 'client_discardGrape_confirm';
                this.setClientStateAction(actionConfirm,translated);
            }
        },

        enableActionSellGrapesSellBuyVine: function(sellGrapes, sellBuyVine){
            var actionConfirm;
            var translated;
            var playerId = this.getThisPlayerId();
            var playerData = this.gamedatas_local.players[playerId];
            var playerBoardId = 'playerboard_'+this.getThisPlayerId();
            var lira = Number(this.getPlayerData(this.getThisPlayerId()).lira);

            if (sellBuyVine){
                for (var i in this.gamedatas_local.fields){
                    var field = this.gamedatas_local.fields[i];
                    if (playerData[field.dbField]==0 && lira>=field.price ){
                        this.queryAndAddClass('#field_slot_'+playerId+'_'+field.key, 'active_slot');
                        this.queryAndAddEvent('#field_slot_'+playerId+'_'+field.key, 'click', 'onChooseFieldBuy' );
                    }
                    if (playerData[field.dbField]==1 && playerData[field.location+'Tot']==0){
                        this.queryAndAddClass('#field_slot_'+playerId+'_'+field.key, 'active_slot');
                        this.queryAndAddEvent('#field_slot_'+playerId+'_'+field.key, 'click', 'onChooseFieldSell' );
                    }
                }
            }
            //TODO: autoselect the only one field? if no grapes? or choose grape if only grape and no fields?

            if (sellGrapes){
                this.queryAndAddEvent('#'+playerBoardId+' .grape.crushPad','click','onChooseGrapeSell');
                this.queryAndAddClass('#'+playerBoardId+' .grape.crushPad','active_slot');
            }

            this.gotoSellGrapesSellBuyVine();

        },

        onChooseFieldBuy: function(evt){
            console.log( '$$$$ Event : onChooseFieldBuy' );
            dojo.stopEvent( evt );
            var element = evt.currentTarget;
            var action=this.clientStateArgs.action;
            var fieldNumber = Number(element.getAttribute('data-arg'));
            var price = this.gamedatas_local.fields[fieldNumber].price;
            var playerBoardId = 'playerboard_'+this.getThisPlayerId();

            if ( !this.isCurrentPlayerActive() ) {
                return;
            }

            if (action){
                if( ! this.checkAction( action ) )
                { return; }

                this.queryAndRemoveClass('.action_slot.field_slot.field.selected','selected');
                dojo.addClass(element,'selected');
                this.queryAndRemoveClass('#'+playerBoardId+' .grape','selected');

                this.clientStateArgs.type = 'buyField';
                this.clientStateArgs.price = price;
                this.clientStateArgs.sellField = null;
                this.clientStateArgs.buyField = fieldNumber;
                this.clientStateArgs.sellGrapes = [];

                this.checkSellGrapesSellBuyVine();
            }
        },

        onChooseFieldSell: function(evt){
            console.log( '$$$$ Event : onChooseFieldSell' );
            dojo.stopEvent( evt );
            var element = evt.currentTarget;
            var action=this.clientStateArgs.action;
            var fieldNumber = Number(element.getAttribute('data-arg'));
            var price = this.gamedatas_local.fields[fieldNumber].price;
            var playerBoardId = 'playerboard_'+this.getThisPlayerId();

            if ( !this.isCurrentPlayerActive() ) {
                return;
            }

            if (action){
                if( ! this.checkAction( action ) )
                { return; }

                this.queryAndRemoveClass('.action_slot.field_slot.field.selected','selected');
                dojo.addClass(element,'selected');
                this.queryAndRemoveClass('#'+playerBoardId+' .grape','selected');

                this.clientStateArgs.type = 'sellField';
                this.clientStateArgs.price = price;
                this.clientStateArgs.sellField = fieldNumber;
                this.clientStateArgs.buyField = null;
                this.clientStateArgs.sellGrapes = [];

                this.checkSellGrapesSellBuyVine();

            }
        },

        onChooseGrapeSell: function(evt){
            console.log( '$$$$ Event : onChooseGrapeSell' );
            dojo.stopEvent( evt );
            var element = evt.currentTarget;
            var action=this.clientStateArgs.action;
            var grapeType = element.getAttribute('data-type');
            var grapeValue = Number(element.getAttribute('data-arg'));
            var wine = this.wines[this.clientStateArgs.wine];
            var playerBoardId = 'playerboard_'+this.getThisPlayerId();

            if ( !this.isCurrentPlayerActive() ) {
                return;
            }

            if (action){
                if( ! this.checkAction( action ) )
                { return; }

                this.queryAndRemoveClass('.action_slot.field_slot.field.selected','selected');

                if (dojo.hasClass(element,'selected')){
                    dojo.removeClass(element, 'selected');
                } else {
                    dojo.addClass(element, 'selected');
                }
                this.clientStateArgs.type = 'sellGrapes';
                this.clientStateArgs.price = 0;
                this.clientStateArgs.sellField = null;
                this.clientStateArgs.buyField = null;
                this.clientStateArgs.sellGrapes = [];

                var grapesSelected = dojo.query('#'+playerBoardId+' .grape.selected');
                for (var i=0;i<grapesSelected.length;i++){
                    var value = Number(grapesSelected[i].getAttribute('data-arg'));
                    this.clientStateArgs.sellGrapes.push(
                        {
                            id: grapesSelected[i].id.split('_')[2],
                            type: grapesSelected[i].getAttribute('data-type'),
                            value: value,
                            price: this.grapePrice[value],
                            token: this.getTokenSymbol(grapesSelected[i].getAttribute('data-type')+value)
                        });
                }

                this.checkSellGrapesSellBuyVine();
            }
        },

        enableActionPlant: function(checkStructures, checkLimit, message, canProceedWithoutPlanting){
            var actionConfirm;
            var translated;
            var playerId = this.getThisPlayerId();
            var playerData = this.gamedatas_local.players[playerId];

            this.clientStateArgs.checkStructures = checkStructures;
            this.clientStateArgs.checkLimit = checkLimit;
            this.clientStateArgs.canProceedWithoutPlanting = canProceedWithoutPlanting;

            for (var i in this.gamedatas_local.fields){
                var field = this.gamedatas_local.fields[i];
                if (playerData[field.dbField]!=0 && (playerData[field.location+'Tot']<Number(field.maxValue) || checkLimit==false)){
                    this.queryAndAddClass('#field_slot_'+playerId+'_'+field.key, 'active_slot');
                    this.queryAndAddEvent('#field_slot_'+playerId+'_'+field.key, 'click', 'onChooseFieldPlant' );
                }
            }
            //TODO: autoselect the only one field?

            this.handZone.setSelectionMode(1);
            var items = this.handZone.getAllItems();

            var possible = 0;
            var possibleItemId = 0;
            for (var i = 0;i<items.length;i++){
                var id = items[i].id;
                var card = this.gamedatas_local.greenCards[items[i].type];
                if (!card){
                    dojo.addClass(this.handZone.getItemDivId(id), 'disabled');
                } else if (checkLimit==false && (playerData.irrigation>=card.irrigation && playerData.trellis>=card.trellis) ){
                    dojo.removeClass(this.handZone.getItemDivId(id), 'disabled');
                    possible++;
                    possibleItemId = id;
                } else if (checkStructures==false || this.arrayFindByProperty(this.gamedatas_local.hand,'i', id).c==1){
                    dojo.removeClass(this.handZone.getItemDivId(id), 'disabled');
                    possible++;
                    possibleItemId = id;
                } else {
                    dojo.addClass(this.handZone.getItemDivId(id), 'disabled');
                }
            }

            this.disconnect( this.handZone,'onChangeSelection');
            this.connect( this.handZone, 'onChangeSelection', 'onChooseGreenCardPlantSelection' );
            if (message){
                translated = message;
            } else {
                translated = dojo.string.substitute( _("Choose a field and a vine card ${token_greenCard}"),
                {token_greenCard: this.getTokenSymbol('greenCard')});
            }
            actionConfirm = 'client_plant_choose';
            this.scrollTo('playerboard_row_'+playerId);
            this.setClientStateAction(actionConfirm,translated);

            //choose the only one card
            if (possible==1){
                this.handZone.selectItem(possibleItemId);
                this.onChooseGreenCardPlantSelection();
            }
        },

        enableActionSwitchVine: function(){
            var playerBoardId = 'playerboard_'+this.getThisPlayerId();
            this.queryAndAddEvent('#'+playerBoardId+' .vine','click','onChooseSwitchVineClick');
            this.queryAndAddClass('#'+playerBoardId+' .vine','active_slot');
            //TODO: autoselect if only two?
            this.gotoSwitchVines();

        },

        enableActionFillOrder: function(){
            var playerId = this.getThisPlayerId();
            var playerBoardId = 'playerboard_'+this.getThisPlayerId();

            this.handZone.setSelectionMode(1);

            var items = this.handZone.getAllItems();
            var possible = 0;
            var possibleItemId = 0;
            for (var i = 0;i<items.length;i++){
                var id = items[i].id;
                var card = this.gamedatas_local.purpleCards[items[i].type];
                if (!card){
                    dojo.addClass(this.handZone.getItemDivId(id), 'disabled');
                } else if (this.arrayFindByProperty(this.gamedatas_local.hand,'i', id).c>0){
                    dojo.removeClass(this.handZone.getItemDivId(id), 'disabled');
                    possible++;
                    possibleItemId = id;
                } else {
                    dojo.addClass(this.handZone.getItemDivId(id), 'disabled');
                }
            }

            this.disconnect( this.handZone,'onChangeSelection');
            this.connect( this.handZone, 'onChangeSelection', 'onChoosePurpleCardFillOrderSelection' );

            this.gotoFillOrderChooseState();
            this.scrollTo('playerboard_row_'+playerId);

            //choose the only one card
            if (possible==1){
                this.handZone.selectItem(possibleItemId);
                this.onChoosePurpleCardFillOrderSelection();
            }

        },

        enableActionPlayYellowCard: function(){
            var playerId = this.getThisPlayerId();
            var playerBoardId = 'playerboard_'+this.getThisPlayerId();

            this.handZone.setSelectionMode(1);

            var items = this.handZone.getAllItems();
            var possible = 0;
            var possibleItemId = 0;
            for (var i = 0;i<items.length;i++){
                var id = items[i].id;
                var card = this.gamedatas_local.yellowCards[items[i].type];
                if (!card){
                    dojo.addClass(this.handZone.getItemDivId(id), 'disabled');
                }else if (this.arrayFindByProperty(this.gamedatas_local.hand,'i', id).c>0){
                    dojo.removeClass(this.handZone.getItemDivId(id), 'disabled');
                    possible++;
                    possibleItemId = id;
                } else {
                    dojo.addClass(this.handZone.getItemDivId(id), 'disabled');
                }
            }

            this.disconnect( this.handZone,'onChangeSelection');
            this.connect( this.handZone, 'onChangeSelection', 'onChoosePlayYellowCardSelection' );

            this.gotoPlayYellowCardChooseState();
            this.scrollTo('playerboard_row_'+playerId);

            //choose the only one card
            //removed, not so clear
            /*if (possible==1){
                this.handZone.selectItem(possibleItemId);
                this.onChoosePlayYellowCardSelection();
            }*/
        },

        enableActionPlayBlueCard: function(){
            var playerId = this.getThisPlayerId();
            var playerBoardId = 'playerboard_'+this.getThisPlayerId();

            this.handZone.setSelectionMode(1);

            var items = this.handZone.getAllItems();
            var possible = 0;
            var possibleItemId = 0;
            for (var i = 0;i<items.length;i++){
                var id = items[i].id;
                var card = this.gamedatas_local.blueCards[items[i].type];
                if (!card){
                    dojo.addClass(this.handZone.getItemDivId(id), 'disabled');
                } else if (this.arrayFindByProperty(this.gamedatas_local.hand,'i', id).c>0){
                    dojo.removeClass(this.handZone.getItemDivId(id), 'disabled');
                    possible++;
                    possibleItemId = id;
                } else {
                    dojo.addClass(this.handZone.getItemDivId(id), 'disabled');
                }
            }

            this.disconnect( this.handZone,'onChangeSelection');
            this.connect( this.handZone, 'onChangeSelection', 'onChoosePlayBlueCardSelection' );

            this.gotoPlayBlueCardChooseState();
            this.scrollTo('playerboard_row_'+playerId);

            //choose the only one card
            //removed, not so clear
            /*if (possible==1){
                this.handZone.selectItem(possibleItemId);
                this.onChoosePlayBlueCardSelection();
            }*/
        },

        enableActionHarvestField: function(maxHarvest){
            var playerId = this.getThisPlayerId();
            var playerData = this.gamedatas_local.players[playerId];

            this.clientStateArgs.maxHarvest = maxHarvest;

            this.clientStateArgs.harvestFields=[];

            for (var i in this.gamedatas_local.fields){
                var field = this.gamedatas_local.fields[i];
                if (playerData[field.dbField]==1 && playerData[field.location+'Tot']>0){

                    //grapes not placeable in crushpad
                    //Can harvest, but grapes are lost
                    //https://boardgamegeek.com/thread/1591437/harvesting-question

                    //check for empty grape slot
                    /*var fieldVines = playerData[field.location];
                    var grapesBitArray = this.playerGrapesToBitArray(playerId);
                    var redGrape = 0;
                    var whiteGrape = 0;
                    var canHarvest = false;
                    for (var j=0;j<fieldVines.length;j++){
                        redGrape+=fieldVines[j].r;
                        whiteGrape+=fieldVines[j].w;
                    }
                    if (redGrape>0){
                        for (var j=1;j<=redGrape;j++){
                            if (grapesBitArray['grapeRed'][j]==0){
                                canHarvest=true;
                                break;
                            }
                        }
                    }
                    if (!canHarvest && whiteGrape>0){
                        for (var j=1;j<=whiteGrape;j++){
                            if (grapesBitArray['grapeWhite'][j]==0){
                                canHarvest=true;
                                break;
                            }
                        }
                    }*/
                    //if (canHarvest){
                    this.queryAndAddClass('#field_slot_'+playerId+'_'+field.key, 'active_slot');
                    this.queryAndAddEvent('#field_slot_'+playerId+'_'+field.key, 'click', 'onChooseFieldHarvest' );
                    //}
                }
            }
            //TODO: autoselect the only one field?
            this.gotoHarvestFieldChooseState();
            this.scrollTo('playerboard_row_'+playerId);

        },

        gotoHarvestFieldChooseState: function(){
            var actionConfirm;
            var translated;

            if (this.clientStateArgs.maxHarvest == 1){
                translated = dojo.string.substitute( _("Choose a field to harvest"),{});
            } else {
                translated = dojo.string.substitute( _("Choose ${number} fields to harvest"),{number: this.clientStateArgs.maxHarvest});
            }
            actionConfirm = 'client_harvestField_choose';
            this.setClientStateAction(actionConfirm,translated);
        },

        enableActionMakeWine: function(checkStructures, canProceedWithoutMakingWine){
            var playerId = this.getThisPlayerId();

            this.clientStateArgs.wine;
            this.clientStateArgs.grapes=[];
            this.clientStateArgs.checkStructures=checkStructures;
            this.clientStateArgs.canProceedWithoutMakingWine=canProceedWithoutMakingWine;

            var possibleWines = this.gamedatas_local.possibleWines;
            if (!this.clientStateArgs.checkStructures){
                possibleWines =  this.gamedatas_local.possibleWinesWS;
            }

            for (var i in possibleWines){
                var possibleWine = possibleWines[i];
                this.queryAndAddClass('#wine_'+possibleWine.t+'_'+possibleWine.v, 'active_slot');
                this.queryAndAddEvent('#wine_'+possibleWine.t+'_'+possibleWine.v, 'click', 'onChooseWineMakeWine' );
            }
            //TODO: autoselect the only one wine?
            this.gotoMakeWineChooseState();
            this.scrollTo('playerboard_row_'+playerId);

        },

        gotoSellGrapesSellBuyVine: function(message){
            var playerId = this.getThisPlayerId();
            var actionConfirm;
            var translated;
            if (message){
                translated = message;
            } else {
                translated = dojo.string.substitute( _("Choose at least one grape to sell or buy/sell one field"),{});
            }
            actionConfirm = 'client_harvestField_choose';
            this.scrollTo('playerboard_row_'+playerId);
            this.setClientStateAction(actionConfirm,translated);
        },

        gotoSwitchVines: function(message){
            var playerId = this.getThisPlayerId();
            var actionConfirm;
            var translated;
            if (message){
                translated = message;
            } else {
                translated = dojo.string.substitute( _("Select two vines to switch"),{});
            }
            actionConfirm = 'client_switchVines_choose';
            this.scrollTo('playerboard_row_'+playerId);
            this.setClientStateAction(actionConfirm,translated);
        },

        gotoMakeWineChooseState: function(message){
            var actionConfirm;
            var translated;
            if (message){
                translated = message;
            } else {
                translated = dojo.string.substitute( _("Choose wine to make"),{});
            }
            actionConfirm = 'client_makeWine_choose';
            this.setClientStateAction(actionConfirm,translated);
        },

        gotoFillOrderChooseState: function(message){
            var actionConfirm;
            var translated;
            if (message){
                translated = message;
            } else {
                translated = this.getDescriptionWithTokens( _("Choose wine order ${token_purpleCard} to fill"));
            }
            actionConfirm = 'client_fillOrder_choose';
            this.setClientStateAction(actionConfirm,translated);
        },

        gotoPlayYellowCardChooseState: function(message){
            var actionConfirm;
            var translated;
            if (message){
                translated = message;
            } else {
                translated = this.getDescriptionWithTokens( _("Choose summer visitor ${token_yellowCard} to play"));
            }
            actionConfirm = 'client_playYellowCard_choose';
            this.setClientStateAction(actionConfirm,translated);
        },

        gotoPlayBlueCardChooseState: function(message){
            var actionConfirm;
            var translated;
            if (message){
                translated = message;
            } else {
                translated = this.getDescriptionWithTokens( _("Choose winter visitor ${token_blueCard} to play"));
            }
            actionConfirm = 'client_playBlueCard_choose';
            this.setClientStateAction(actionConfirm,translated);
        },

        onChooseWineMakeWine: function(evt){
            console.log( '$$$$ Event : onChooseWineMakeWine' );
            dojo.stopEvent( evt );
            var element = evt.currentTarget;
            var action=this.clientStateArgs.action;
            var wineType = element.getAttribute('data-type');
            var wineValue = Number(element.getAttribute('data-arg'));
            var wine = this.wines[wineType];
            var playerBoardId = 'playerboard_'+this.getThisPlayerId();

            if ( !this.isCurrentPlayerActive() ) {
                return;
            }

            if (action){
                if( ! this.checkAction( action ) )
                { return; }

                this.queryAndRemoveClass('#'+playerBoardId+' .wine_slot.selected','selected');
                dojo.addClass(element, 'selected');
                this.clientStateArgs.wine = wineType;
                this.clientStateArgs.wineValue = wineValue;

                this.queryAndDisconnectEvent('#'+playerBoardId+' .crushPad.grape.active_slot','click');
                this.queryAndRemoveClass('#'+playerBoardId+' .crushPad.grape.active_slot','active_slot');
                this.queryAndRemoveClass('#'+playerBoardId+' .crushPad.grape.selected','selected');
                this.clientStateArgs.grapes = [];

                for (var grape in wine.origin){
                    if (wine.origin[grape]>0){
                        this.queryAndAddEvent('#'+playerBoardId+' .crushPad.grape.'+grape,'click','onChooseGrapeMakeWineClick');
                        this.queryAndAddClass('#'+playerBoardId+' .crushPad.grape.'+grape,'active_slot');
                        var possible = dojo.query('#'+playerBoardId+' .crushPad.grape.'+grape+'.active_slot');
                        //Autoselect
                        if (possible.length == wine.origin[grape]){
                            for (var i=0;i<possible.length;i++){
                                dojo.addClass(possible[i], 'selected');
                            }
                        }
                    }
                }

                this.clientStateArgs.grapes = [];
                var grapesSelected = dojo.query('#'+playerBoardId+' .grape.selected');
                for (var i=0;i<grapesSelected.length;i++){
                    this.clientStateArgs.grapes.push(
                        {
                            id:grapesSelected[i].id.split('_')[2],
                            type:grapesSelected[i].getAttribute('data-type'),
                            value: Number(grapesSelected[i].getAttribute('data-arg'))
                        });
                }

                this.checkMakeWineAction();
            }
        },

        onChooseGrapeMakeWineClick: function(evt){
            console.log( '$$$$ Event : onChooseGrapeMakeWineClick' );
            dojo.stopEvent( evt );
            var element = evt.currentTarget;
            var action=this.clientStateArgs.action;
            var grapeType = element.getAttribute('data-type');
            var wine = this.wines[this.clientStateArgs.wine];
            var playerBoardId = 'playerboard_'+this.getThisPlayerId();

            if ( !this.isCurrentPlayerActive() ) {
                return;
            }

            if (action){
                if( ! this.checkAction( action ) )
                { return; }

                if (wine.origin[grapeType]==1){
                    this.queryAndRemoveClass('#'+playerBoardId+' .grape.'+grapeType+'.selected','selected');
                    dojo.addClass(element, 'selected');
                } else if (wine.origin[grapeType]>1){
                    if (dojo.hasClass(element,'selected')){
                        dojo.removeClass(element, 'selected');
                    } else {
                        dojo.addClass(element, 'selected');
                    }
                }
                this.clientStateArgs.grapes = [];
                var grapesSelected = dojo.query('#'+playerBoardId+' .grape.selected');
                for (var i=0;i<grapesSelected.length;i++){
                    this.clientStateArgs.grapes.push(
                        {
                            id:grapesSelected[i].id.split('_')[2],
                            type:grapesSelected[i].getAttribute('data-type'),
                            value: Number(grapesSelected[i].getAttribute('data-arg'))
                        });
                }

                this.checkMakeWineAction();
            }
        },

        onChooseWineFillOrderClick: function(evt){
            console.log( '$$$$ Event : onChooseWineFillOrderClick' );
            dojo.stopEvent( evt );
            var element = evt.currentTarget;
            var action=this.clientStateArgs.action;
            var wineType = element.getAttribute('data-type');
            var playerBoardId = 'playerboard_'+this.getThisPlayerId();

            if ( !this.isCurrentPlayerActive() ) {
                return;
            }

            if (action){
                if( ! this.checkAction( action ) )
                { return; }

                if (this.clientStateArgs[wineType].length==1){
                    this.queryAndRemoveClass('#'+playerBoardId+' .wine.'+wineType+'.selected','selected');
                    dojo.addClass(element, 'selected');
                } else if (this.clientStateArgs[wineType].length>1){
                    if (dojo.hasClass(element,'selected')){
                        dojo.removeClass(element, 'selected');
                    } else {
                        dojo.addClass(element, 'selected');
                    }
                }
                this.clientStateArgs.orderWines = [];
                var wineSelected = dojo.query('#'+playerBoardId+' .wine.selected');
                for (var i=0;i<wineSelected.length;i++){
                    this.clientStateArgs.orderWines.push(
                        {
                            id:wineSelected[i].id.split('_')[2],
                            type:wineSelected[i].getAttribute('data-type'),
                            value: Number(wineSelected[i].getAttribute('data-arg'))
                        });
                }

                this.checkFillOrder();
            }
        },

        onChooseFieldHarvest: function(evt){
            console.log( '$$$$ Event : onChooseFieldHarvest' );
            dojo.stopEvent( evt );
            var me = this;
            var element = evt.currentTarget;
            var action=this.clientStateArgs.action;
            var actionConfirm="";
            var fieldNumber = Number(element.getAttribute('data-arg'));

            if ( !this.isCurrentPlayerActive() ) {
                return;
            }

            if (action){
                if( ! this.checkAction( action ) )
                { return; }

                if (dojo.hasClass(element,'selected')){
                    dojo.removeClass(element,'selected');
                } else {
                    if (this.clientStateArgs.maxHarvest == 1){
                        this.queryAndRemoveClass('.action_slot.field_slot.field.selected','selected');
                    }
                    dojo.addClass(element,'selected');
                }
                this.clientStateArgs.harvestFields = [];
                var fieldsSelected = dojo.query('.action_slot.field_slot.field.selected');
                for (var i=0;i<fieldsSelected.length;i++){
                    this.clientStateArgs.harvestFields.push(Number(fieldsSelected[i].getAttribute('data-arg')));
                }

                this.checkHarvestFieldAction();
            }
        },

        onChooseFieldPlant: function(evt){
            console.log( '$$$$ Event : onChooseFieldPlant' );
            dojo.stopEvent( evt );
            var element = evt.currentTarget;
            var action=this.clientStateArgs.action;
            var fieldNumber = Number(element.getAttribute('data-arg'));

            if ( !this.isCurrentPlayerActive() ) {
                return;
            }

            if (action){
                if( ! this.checkAction( action ) )
                { return; }

                this.queryAndRemoveClass('.action_slot.field_slot.field.selected','selected');
                dojo.addClass(element,'selected');

                this.clientStateArgs.field = fieldNumber;

                this.checkPlantAction();
            }
        },

        onChooseGreenCardPlantSelection: function(control_name, item_id){
            console.log( '$$$$ Event : onChooseGreenCardPlantSelection' );

            var playerId = this.getThisPlayerId();
            var playerData = this.gamedatas_local.players[playerId];
            var stock = this.handZone;

            var action=this.clientStateArgs.action;

            if (stock.selectable==0){
                return;
            }

            var selected = stock.getSelectedItems();
            if (selected.length<1){
                return;
            }
            var key = 0;
            var card = null;
            var cardId = null;
            for (var i=0;i<selected.length;i++){
                key = Number(selected[i].type);
                card = this.gamedatas_local.greenCards[key];
                if (card){
                    cardId = selected[i].id;
                    break;
                }
            }

            if (!card){
                stock.unselectItem( selected[0].id );
                if (this.clientStateArgs.cardId){
                    stock.selectItem( this.clientStateArgs.cardId );
                    cardId = this.clientStateArgs.cardId;
                    selected = stock.getSelectedItems();
                    key = Number(selected[0].type);
                    card = this.gamedatas_local.greenCards[key];
                } else {
                    return;
                }
            }

            if( ! this.checkAction( action, false ) )
            { return; }

            if (action){

                if (this.clientStateArgs.checkStructures==true && (card.irrigation>playerData.irrigation || card.trellis>playerData.trellis )){
                    stock.unselectItem( cardId );
                    if (this.clientStateArgs.cardId){
                        stock.selectItem( this.clientStateArgs.cardId );
                    }
                    return;
                }
                this.clientStateArgs.cardId = Number(cardId);
                this.clientStateArgs.cardKey = key;

                this.checkPlantAction();

            }
        },

        onChoosePlayYellowCardSelection: function(control_name, item_id){
            console.log( '$$$$ Event : onChoosePlayYellowCardSelection' );

            var stock = this.handZone;

            var action=this.clientStateArgs.action;

            if (stock.selectable==0){
                return;
            }
            var selected = stock.getSelectedItems();
            if (selected.length > 0 && this.queryCount('#'+stock.getItemDivId(selected[0].id)+'.disabled')){
                stock.unselectItem(selected[0].id);
                if (this.clientStateArgs.visitorCardId){
                    stock.selectItem( this.clientStateArgs.visitorCardId );
                }
                selected = stock.getSelectedItems();
            }

            if( ! this.checkAction( action, false ) )
            { return; }

            if (action){

                if (selected.length<1){
                    this.clearInteractiveItems(true, true);
                    this.gotoPlayYellowCardChooseState();
                    return;
                }

                var key = Number(selected[0].type);
                var card = this.gamedatas_local.yellowCards[key];
                if (!card){
                    stock.unselectItem( selected[0].id );
                    selected = stock.getSelectedItems();
                }

                if (selected.length == 0 && this.clientStateArgs.visitorCardId){
                    stock.selectItem( this.clientStateArgs.visitorCardId );
                    selected = stock.getSelectedItems();
                    key = Number(selected[0].type);
                    card = this.gamedatas_local.yellowCards[key];
                }

                if (selected.length<1){
                    this.clearInteractiveItems(true, true);
                    this.gotoPlayYellowCardChooseState();
                    return;
                }

                this.clientStateArgs.visitorCardId = Number(selected[0].id);
                this.clientStateArgs.visitorCardKey = key;

                this.processPlayYellowCardEffects();

            }

        },

        onChoosePlayBlueCardSelection: function(control_name, item_id){
            console.log( '$$$$ Event : onChoosePlayBlueCardSelection' );

            var stock = this.handZone;

            var action=this.clientStateArgs.action;

            if (stock.selectable==0){
                return;
            }

            var selected = stock.getSelectedItems();
            if (selected.length > 0 && this.queryCount('#'+stock.getItemDivId(selected[0].id)+'.disabled')){
                stock.unselectItem(selected[0].id);
                if (this.clientStateArgs.visitorCardId){
                    stock.selectItem( this.clientStateArgs.visitorCardId );
                }
                selected = stock.getSelectedItems();
            }

            if( ! this.checkAction( action, false ) )
            { return; }

            if (action){

                if (selected.length<1){
                    this.clearInteractiveItems(true, true);
                    this.gotoPlayBlueCardChooseState();
                    return;
                }

                var key = Number(selected[0].type);
                var card = this.gamedatas_local.blueCards[key];
                if (!card){
                    stock.unselectItem( selected[0].id );
                    selected = stock.getSelectedItems();
                }

                if (selected.length == 0 && this.clientStateArgs.visitorCardId){
                    stock.selectItem( this.clientStateArgs.visitorCardId );
                    selected = stock.getSelectedItems();
                    key = Number(selected[0].type);
                    card = this.gamedatas_local.blueCards[key];
                }

                if (selected.length<1){
                    this.clearInteractiveItems(true, true);
                    this.gotoPlayBlueCardChooseState();
                    return;
                }

                this.clientStateArgs.visitorCardId = Number(selected[0].id);
                this.clientStateArgs.visitorCardKey = key;

                this.processPlayBlueCardEffects();

            }

        },

        processPlayYellowCardEffects: function(){
            console.log('processPlayYellowCardEffects');
            this.clientStateArgs.playCard = this.clientStateArgs.visitorCardKey;
            this.clientStateArgs.playCardPlayerId = this.getThisPlayerId();
            this.updatePlayCard();
            var playerData = this.getPlayerData(this.getThisPlayerId());

            if (!this.clientStateArgs.visitorCardKey){
                return;
            }
            var card = this.gamedatas_local.yellowCards[this.clientStateArgs.visitorCardKey];

            var choiceText1 = _('Choose one');
            var choiceText2 = _('Choose first option');
            if (this.clientStateArgs.visitorCardSecondOption){
                if (this.clientStateArgs.vpPrice>0){
                    choiceText2 = dojo.string.substitute(_('You can choose ${cardName} second option, it will cost ${token_vp}'),{token_vp:this.getTokenSymbol('vp'+this.clientStateArgs.vpPrice), cardName:card.name});
                } else {
                    choiceText2 = dojo.string.substitute(_('Choose ${cardName} second option'),{cardName:card.name});
                }
            }
            this.clientStateArgs.choice1='';
            this.clientStateArgs.choice2='';
            this.clientStateArgs.choice3='';

            switch (this.clientStateArgs.visitorCardKey) {
                case 601: //Surveyor
                    //Gain ${token_lira2} for each empty field you own OR gain ${token_vp1} for each planted field you own.
                    //**special**
                    this.clientStateArgs.choiceText=choiceText1;

                    var lira=0;
                    if (playerData.field1>0 && playerData.vine1Tot==0){lira+=2};
                    if (playerData.field2>0 && playerData.vine2Tot==0){lira+=2};
                    if (playerData.field3>0 && playerData.vine3Tot==0){lira+=2};
                    this.clientStateArgs.choice1lira=lira;
                    this.processPlayCardChoice(1, this.getDescriptionWithTokens(_('Gain ${token_lira2} for each empty field you own: ${lira}${token_lira}'),false,{lira:lira}));

                    var vps=0;
                    if (playerData.field1>0 && playerData.vine1Tot>0){vps+=1};
                    if (playerData.field2>0 && playerData.vine2Tot>0){vps+=1};
                    if (playerData.field3>0 && playerData.vine3Tot>0){vps+=1};
                    this.clientStateArgs.choice2vps=vps;
                    this.processPlayCardChoice(2, this.getDescriptionWithTokens(_('Gain ${token_vp1} for each planted field you own: ${vps}${token_vp}'), false, {vps:vps}));
                    this.clientStateArgs.actionConfirm='client_playCard_choose';
                    break;


                case 602: //Broker
                    //Pay ${token_lira9} to gain ${token_vp3} OR lose ${token_vp2} to gain ${token_lira6}
                    //payLira_9+getVp_3|loseVp_2+getLira_6
                    this.clientStateArgs.choiceText=choiceText1;
                    this.processPlayCardChoice(1, this.getDescriptionWithTokens(_('Pay ${token_lira9} to gain ${token_vp3}')));
                    this.processPlayCardChoice(2, this.getDescriptionWithTokens(_('Lose ${token_vp2} to gain ${token_lira6}')));
                    this.clientStateArgs.actionConfirm='client_playCard_choose';
                    break;


                case 603: //Wine Critic
                    //Draw 2 ${token_blueCardPlus} OR discard 1 ${token_wineAny} of value 7 or more to gain ${token_vp4}
                    //drawBlueCard_2|dicardWineAny_1_7+getVp_4
                    this.clientStateArgs.choiceText=choiceText1;
                    this.processPlayCardChoice(1, this.getDescriptionWithTokens(_('Draw 2 ${token_blueCardPlus}')));
                    this.processPlayCardChoice(2, this.getDescriptionWithTokens(_('Discard 1 ${token_wineAny} of value 7 or more to gain ${token_vp4}')));
                    this.clientStateArgs.actionConfirm='client_playCard_choose';
                    break;

                case 604: //Blacksmith
                    //Build a structure at a ${token_lira2} discount. If it is a ${token_lira5} or ${token_lira6} structure, also gain ${token_vp1}.
                    //buildStructure_1_2_ifgreat5_1vp
                    this.enableActionBuild(2,this.getDescriptionWithTokens(card.description),null,false);
                    this.clientStateArgs.visitorCardOption=1;
                    break;


                case 605: //Contractor
                    //Choose 2: Gain ${token_vp1}, build 1 structure, or plant 1 ${token_greenCard}.
                    //getVp1|buildStructure_1|plant_1
                    this.clientStateArgs.choiceText=choiceText2;
                    this.processPlayCardChoice(1, this.getDescriptionWithTokens(_('Gain ${token_vp1}')));
                    this.processPlayCardChoice(2, this.getDescriptionWithTokens(_('Build 1 structure')));
                    this.processPlayCardChoice(3, this.getDescriptionWithTokens(_('Plant 1 ${token_greenCard}')));
                    this.clientStateArgs.actionConfirm='client_playCard_choose';
                    break;


                case 606: //Tour Guide
                    //Gain ${token_lira4} OR harvest 1 field.
                    //getLira_4|harvestField_1
                    this.clientStateArgs.choiceText=choiceText1;
                    this.processPlayCardChoice(1, this.getDescriptionWithTokens(_('Gain ${token_lira4}')));
                    this.processPlayCardChoice(2, this.getDescriptionWithTokens(_('Harvest 1 field')));
                    this.clientStateArgs.actionConfirm='client_playCard_choose';
                    break;


                case 607: //Novice Guide
                    //Gain ${token_lira3} OR make up to 2 ${token_wineAny}
                    //getLira_3|makeWine_2
                    this.clientStateArgs.choiceText=choiceText1;
                    this.processPlayCardChoice(1, this.getDescriptionWithTokens(_('Gain ${token_lira3}')));
                    this.processPlayCardChoice(2, this.getDescriptionWithTokens(_('Make up to 2 ${token_wineAny}')));
                    this.clientStateArgs.actionConfirm='client_playCard_choose';
                    break;


                case 608: //Uncertified Broker
                    //Lose ${token_vp3}  to gain ${token_lira9} OR pay ${token_lira6} to gain ${token_vp2}.
                    //loseVp_3+getLira_9|payLira_6+getVp_2
                    this.clientStateArgs.choiceText=choiceText1;
                    this.processPlayCardChoice(1, this.getDescriptionWithTokens(_('Lose ${token_vp3} to gain ${token_lira9}')));
                    this.processPlayCardChoice(2, this.getDescriptionWithTokens(_('Pay ${token_lira6} to gain ${token_vp2}')));
                    this.clientStateArgs.actionConfirm='client_playCard_choose';
                    break;


                case 609: //Planter
                    //Plant up to 2 ${token_greenCard} and gain ${token_lira1} OR uproot and discard 1 ${token_greenCard} to gain ${token_vp2}.
                    //plant_2+getLira_1|uprootAndDiscard_1+getVp_2
                    this.clientStateArgs.choiceText=choiceText1;
                    this.processPlayCardChoice(1, this.getDescriptionWithTokens(_('Plant up to 2 ${token_greenCard} and gain ${token_lira1}')));
                    this.processPlayCardChoice(2, this.getDescriptionWithTokens(_('Uproot and discard 1 ${token_greenCard} to gain ${token_vp2}')));
                    this.clientStateArgs.actionConfirm='client_playCard_choose';
                    break;


                case 610: //Buyer
                    //Pay ${token_lira2} to place a ${token_grapeAny1} on your crush pad OR discard 1 ${token_grapeAny} to gain ${token_lira2} and ${token_vp1}
                    //payLira_2+getGrapeAny_1|discardGrapeAny_1+getLira_2+getVp_1
                    this.clientStateArgs.choiceText=choiceText1;
                    this.processPlayCardChoice(1, this.getDescriptionWithTokens(_('Pay ${token_lira2} to get a ${token_grapeRed1}')));
                    this.processPlayCardChoice(2, this.getDescriptionWithTokens(_('Pay ${token_lira2} to get a ${token_grapeWhite1}')));
                    this.processPlayCardChoice(3, this.getDescriptionWithTokens(_('Discard 1 ${token_grapeAny} to gain ${token_lira2} and ${token_vp1}')));
                    this.clientStateArgs.actionConfirm='client_playCard_choose';
                    break;


                case 611: //Landscaper
                    //Draw 1 ${token_greenCardPlus} and plant up to 1 ${token_greenCard} OR switch 2 ${token_greenCard} on your fields.
                    //**special**
                    this.clientStateArgs.choiceText=choiceText1;
                    this.processPlayCardChoice(1, this.getDescriptionWithTokens(_('Draw 1 ${token_greenCardPlus} and plant up to 1 ${token_greenCard}')));
                    this.processPlayCardChoice(2, this.getDescriptionWithTokens(_('Switch 2 ${token_greenCard} on your fields')));
                    this.clientStateArgs.actionConfirm='client_playCard_choose';
                    break;


                case 612:// Architect;
                    //Build a structure at a ${token_lira3} discount OR gain ${token_vp1} for each ${token_lira4} structure you have built.
                    //buildStructure_1_3|getVp_buildings4
                    this.clientStateArgs.choiceText=choiceText1;
                    this.processPlayCardChoice(1, this.getDescriptionWithTokens(_('Build a structure at a ${token_lira} discount'),false,{token_lira:this.getTokenSymbol('lira3')}));

                    var vps = 0;
                    for (var i=0; i< this.gamedatas_local.playerTokens.length;i++){
                        var playerToken = this.gamedatas_local.playerTokens[i];
                        //building, not yet builded, enough lira
                        if (playerToken.isBuilding && playerData[playerToken.type]==1 && playerToken.price==4){
                            vps++;
                        }
                    }
                    this.clientStateArgs.choice2vps = vps;
                    this.processPlayCardChoice(2, this.getDescriptionWithTokens(_('Gain ${token_vp1} for each ${token_lira4} structure you have built: ${vps}${token_vp}'),false,{vps:vps}));
                    this.clientStateArgs.actionConfirm='client_playCard_choose';
                    break;

                case 613: //Uncertified Architect
                    //Lose ${token_vp1} to build a ${token_lira2} or ${token_lira3} structure OR lose ${token_vp2} to build any structure.
                    //**special**
                    this.clientStateArgs.choiceText=choiceText1;
                    this.processPlayCardChoice(1, this.getDescriptionWithTokens(_('Lose ${token_vp1} to build a ${token_lira2} or ${token_lira3} structure')));
                    this.processPlayCardChoice(2, this.getDescriptionWithTokens(_('Lose ${token_vp2} to build any structure')));
                    this.clientStateArgs.actionConfirm='client_playCard_choose';
                    break;

                case 614: //Patron
                    //Gain ${token_lira4} OR draw 1 ${token_purpleCard} card and 1 ${token_blueCard}.
                    //getLira_4|drawPurpleCard_1+drawBlueCard_1
                    this.clientStateArgs.choiceText=choiceText1;
                    this.processPlayCardChoice(1, this.getDescriptionWithTokens(_('Gain ${token_lira4}')));
                    this.processPlayCardChoice(2, this.getDescriptionWithTokens(_('Draw 1 ${token_purpleCard} card and 1 ${token_blueCard}')));
                    this.clientStateArgs.actionConfirm='client_playCard_choose';
                    break;

                case 615: //Auctioneer
                    //Discard 2 ${token_anyCard} to gain ${token_lira4} OR discard 4 ${token_anyCard} to gain ${token_vp3}.
                    //discardCard_2+getLira_4|discardCard_4+getVp_3
                    this.clientStateArgs.choiceText=choiceText1;
                    this.processPlayCardChoice(1, this.getDescriptionWithTokens(_('Discard 2 ${token_anyCard} to gain ${token_lira4}')));
                    this.processPlayCardChoice(2, this.getDescriptionWithTokens(_('Discard 4 ${token_anyCard} to gain ${token_vp3}')));
                    this.clientStateArgs.actionConfirm='client_playCard_choose';
                    break;

                case 616: //Entertainer
                    //Pay ${token_lira4} to draw 3 ${token_blueCardPlus} OR discard 1 ${token_wineAny} and 3 visitor cards to gain ${token_vp3}.
                    //**special**
                    this.clientStateArgs.choiceText=choiceText1;
                    this.processPlayCardChoice(1, this.getDescriptionWithTokens(_('Pay ${token_lira4} to draw 3 ${token_blueCardPlus}')));
                    this.processPlayCardChoice(2, this.getDescriptionWithTokens(_('Discard 1 ${token_wineAny} and 3 visitor cards to gain ${token_vp3}')));
                    this.clientStateArgs.actionConfirm='client_playCard_choose';
                    break;


                case 617: //Vendor
                    //Draw 1 ${token_greenCardPlus} , 1 ${token_purpleCardPlus}, and 1 ${token_blueCardPlus}. Each opponent may draw 1 ${token_yellowCardPlus}.
                    //drawGreenCard_1+drawPurpleCard_1+drawBlueCard_1
                    this.clientStateArgs.choiceText=this.getDescriptionWithTokens(_('Draw 1 ${token_greenCardPlus} , 1 ${token_purpleCardPlus}, and 1 ${token_blueCardPlus}. Each opponent may draw 1 ${token_yellowCardPlus}.'));
                    this.clientStateArgs.actionConfirm='client_playCard_confirm';
                    this.clientStateArgs.visitorCardOption=1;
                    break;


                case 618: //Handyman
                    //All players may build 1 structure at a ${token_lira2} discount. You gain ${token_vp1} for each opponent who does this.
                    //**special**
                    this.enableActionBuild(2,this.getDescriptionWithTokens(card.description),null,true);
                    this.clientStateArgs.visitorCardOption=1;
                    break;


                case 619: //Horticulturist
                    //Plant 1 ${token_greenCard} even if you don't have the required structure(s) OR uproot and discard 2 ${token_greenCard} to gain ${token_vp3}.
                    //**special**
                    this.clientStateArgs.choiceText=choiceText1;
                    this.processPlayCardChoice(1, this.getDescriptionWithTokens(_('Plant 1 ${token_greenCard} even if you don\'t have the required structure(s)')));
                    this.processPlayCardChoice(2, this.getDescriptionWithTokens(_('Uproot and discard 2 ${token_greenCard} to gain ${token_vp3}.')));
                    this.clientStateArgs.actionConfirm='client_playCard_choose';
                    break;


                case 620: //Peddler
                    //Discard 2 ${token_anyCard} to draw 1 of each type of card.
                    //**special**
                    this.clientStateArgs.choiceText=this.getDescriptionWithTokens(_('Discard 2 ${token_anyCard} to draw 1 of each type of card.'));
                    this.enableActionDiscardCards(2,2, null, this.clientStateArgs.visitorCardId, false ,'');
                    this.clientStateArgs.actionConfirm = 'client_discardCards_choose';
                    this.clientStateArgs.visitorCardOption=1;
                    break;


                case 621: //Banker
                    //Gain ${token_lira5}. Each opponent may lose ${token_vp1} to gain ${token_lira3}.
                    //**special**
                    this.clientStateArgs.choiceText=this.getDescriptionWithTokens(card.description);
                    this.clientStateArgs.actionConfirm='client_playCard_confirm';
                    this.clientStateArgs.visitorCardOption=1;
                    break;

                case 622: //Overseer
                    //Build 1 structure at its regular cost and plant 1 ${token_greenCard}. If it is a 4-value ${token_greenCard}, gain ${token_vp1}.
                    //buildStructure_1|plant_1_ifgreat4_1vp
                    this.enableActionBuild(0,this.getDescriptionWithTokens(card.description),null,false);
                    this.clientStateArgs.visitorCardOption=1;
                    break;

                case 623: //Importer
                    //Draw 3 ${token_blueCard} cards unless all opponents combine to give you 3 visitor cards (total).
                    //**special**
                    this.clientStateArgs.choiceText=this.getDescriptionWithTokens(card.description);
                    this.clientStateArgs.actionConfirm='client_playCard_confirm';
                    this.clientStateArgs.visitorCardOption=1;
                    break;

                case 624: //Sharecropper
                    //Plant 1 ${token_greenCard} even if you don't have the required structure(s) OR uproot and discard 1 ${token_greenCard} to gain ${token_vp2}.
                    //plant_1_noStructure|uprootAndDiscard_1+getVp_2
                    this.clientStateArgs.choiceText=choiceText1;
                    this.processPlayCardChoice(1, this.getDescriptionWithTokens(_('Plant 1 ${token_greenCard} even if you don\'t have the required structure(s)')));
                    this.processPlayCardChoice(2, this.getDescriptionWithTokens(_('Uproot and discard 1 ${token_greenCard} to gain ${token_vp2}.')));
                    this.clientStateArgs.actionConfirm='client_playCard_choose';
                    break;

                case 625: //Grower
                    //Plant 1 ${token_greenCard}. Then, if you have planted a total of at least 6 ${token_greenCard}, gain ${token_vp2}.
                    //plant_1_iftotalgreat_6_vp2
                    this.clientStateArgs.choiceText=this.getDescriptionWithTokens(_('Plant 1 ${token_greenCard}. Then, if you have planted a total of at least 6 ${token_greenCard}, gain ${token_vp2}.'));
                    this.enableActionPlant(true, true, null, false);
                    this.clientStateArgs.visitorCardOption=1;
                    break;


                case 626: //Negotiator
                    //Discard 1 ${token_grapeAny} to gain ${token_residualPayment1} OR discard 1 ${token_wineAny} to gain ${token_residualPayment2} .
                    //discardGrape_1+getResidualPayment_1|discardWine_1+getResidualPayment_2
                    this.clientStateArgs.choiceText=choiceText1;
                    this.processPlayCardChoice(1, this.getDescriptionWithTokens(_('Discard 1 ${token_grapeAny} to gain ${token_residualPayment1}')));
                    this.processPlayCardChoice(2, this.getDescriptionWithTokens(_('Discard 1 ${token_wineAny} to gain ${token_residualPayment2}')));
                    this.clientStateArgs.actionConfirm='client_playCard_choose';
                    break;

                case 627: //Cultivator
                    //Plant 1 ${token_greenCard}. You may plant it on a field even if the total value of that field exceeds the max vine value.
                    //plant_1_overMax
                    this.getDescriptionWithTokens(card.description);
                    this.enableActionPlant(true, false, null, false);
                    this.clientStateArgs.actionConfirm='client_plant_choose';
                    this.clientStateArgs.visitorCardOption=1;
                    break;

                case 628: //Homesteader
                    //Build 1 structure at a ${token_lira3} discount OR plant up to 2 ${token_greenCard}. You may lose ${token_vp1} to do both.
                    //buildStructure_1_3|plant_2
                    this.clientStateArgs.choiceText=choiceText2;
                    this.processPlayCardChoice(1, this.getDescriptionWithTokens(_('Build 1 structure at a ${token_lira3} discount')));
                    this.processPlayCardChoice(2, this.getDescriptionWithTokens(_('Plant up to 2 ${token_greenCard}')));
                    this.clientStateArgs.actionConfirm='client_playCard_choose';
                    break;

                case 629: //Planner
                    //Place a worker on an action in a future season. Take that action at the beginning of that season.
                    //**special**
                    this.clientStateArgs.choiceText=this.getDescriptionWithTokens(card.description);
                    this.enableActionSelectFutureLocation();
                    this.clientStateArgs.actionConfirm='client_selectFutureLocation_choose';
                    this.clientStateArgs.visitorCardOption=1;
                    break;

                case 630: //Agriculturist
                    //Plant 1 ${token_greenCard}. Then, if you have at least 3 different types of ${token_greenCard} planted on that field, gain ${token_vp2}.
                    //**special**
                    this.clientStateArgs.choiceText=this.getDescriptionWithTokens(card.description);
                    this.enableActionPlant(true, true, null, false);
                    this.clientStateArgs.actionConfirm='client_plant_choose';
                    this.clientStateArgs.visitorCardOption=1;
                    break;

                case 631: //Swindler
                    //Each opponent may give you ${token_lira2}. For each opponent who does not, gain ${token_vp1}.
                    //**special**
                    this.clientStateArgs.choiceText=this.getDescriptionWithTokens(card.description);
                    this.clientStateArgs.actionConfirm='client_playCard_confirm';
                    this.clientStateArgs.visitorCardOption=1;
                    break;

                case 632: //Producer
                    //Pay ${token_lira2} to retrieve up to 2 ${token_worker} from other actions. They may be used again this year.
                    //**special**
                    this.clientStateArgs.choiceText=this.getDescriptionWithTokens(_('Pay ${token_lira2} to retrieve up to 2 ${token_worker} from other actions'));
                    this.enableActionSelectWorkers(1,2,this.clientStateArgs.choiceText,'Confirm selected workers?');
                    this.clientStateArgs.visitorCardOption=1;
                    break;

                case 633: //Organizer
                    //Move your ${token_rooster} piece to an empty row on the wake-up chart, take the bonus, then pass to the next season.
                    //**special**
                    this.clientStateArgs.choiceText=this.getDescriptionWithTokens(card.description);
                    this.enableActionChangeWakeup(this.clientStateArgs.choiceText);
                    this.clientStateArgs.visitorCardOption=1;
                    break;

                case 634: //Sponsor
                    //Draw 2 ${token_greenCardPlus} OR gain ${token_lira3}. You may lose ${token_vp1} to do both.
                    //drawGreenCard_2|getLira_3
                    this.clientStateArgs.choiceText=choiceText2;
                    this.processPlayCardChoice(1, this.getDescriptionWithTokens(_('Draw 2 ${token_greenCardPlus}')));
                    this.processPlayCardChoice(2, this.getDescriptionWithTokens(_('Gain ${token_lira3}')));
                    this.clientStateArgs.actionConfirm='client_playCard_choose';
                    break;

                case 635: //Artisan
                    //Choose 1: Gain ${token_lira3}, build a structure at a ${token_lira1} discount, or plant up to 2 ${token_greenCard}.
                    //getLira_3|buildStructure_1_1|plant_2
                    this.clientStateArgs.choiceText=choiceText1;
                    this.processPlayCardChoice(1, this.getDescriptionWithTokens(_('Gain ${token_lira3}')));
                    this.processPlayCardChoice(2, this.getDescriptionWithTokens(_('Build a structure at a ${token_lira} discount'),false,{token_lira:this.getTokenSymbol('lira1')}));
                    this.processPlayCardChoice(3, this.getDescriptionWithTokens(_('Plant up to 2 ${token_greenCard}')));
                    this.clientStateArgs.actionConfirm='client_playCard_choose';
                    break;

                case 636: //Stonemason
                    //Pay ${token_lira8} to build any 2 structures (ignore their regular costs)
                    //payLira_8+buildStructure_2_free
                    this.enableActionSelectStructures(_('Choose up to two structures'));
                    this.clientStateArgs.visitorCardOption=1;
                    break;

                case 637: //Volunteer Crew
                    //All players may plant 1 ${token_greenCard}. Gain ${token_lira2} for each opponent who does this.
                    //**special**
                    this.clientStateArgs.choiceText=this.getDescriptionWithTokens(card.description);
                    this.enableActionPlant(true, true, null, true);
                    this.clientStateArgs.actionConfirm='client_plant_choose';
                    this.clientStateArgs.visitorCardOption=1;
                    break;

                case 638: //Wedding Party
                    //Pay up to 3 opponents ${token_lira2} each. Gain ${token_vp1} for each of those opponents.
                    //**special**
                    this.clientStateArgs.choiceText=this.getDescriptionWithTokens(card.description);
                    var lira = Number(playerData.lira);
                    var maxPlayers = 0;
                    var minPlayers = 0;
                    if (lira>=2){maxPlayers++;minPlayers++;}
                    if (lira>=4 && this.players_number>=3){maxPlayers++;}
                    if (lira>=6 && this.players_number>=4){maxPlayers++;}
                    this.enableActionSelectPlayers(minPlayers,maxPlayers, this.getDescriptionWithTokens(card.description), _('Confirm selection?'));
                    this.clientStateArgs.actionConfirm='client_selectPlayers_choose';
                    this.clientStateArgs.visitorCardOption=1;
                    break;

                default:
                    break;
            }

            if (this.clientStateArgs.actionConfirm && this.clientStateArgs.choiceText){
                this.setClientStateAction(this.clientStateArgs.actionConfirm, this.clientStateArgs.choiceText);
            }

        },

        processPlayBlueCardEffects: function(){
            console.log('processPlayBlueCardEffects');
            this.clientStateArgs.playCard = this.clientStateArgs.visitorCardKey;
            this.clientStateArgs.playCardPlayerId = this.getThisPlayerId();
            this.updatePlayCard();
            var playerId = this.getThisPlayerId();
            var playerData = this.getPlayerData(playerId);

            if (!this.clientStateArgs.visitorCardKey){
                return;
            }

            var card = this.gamedatas_local.blueCards[this.clientStateArgs.visitorCardKey];

            var choiceText1 = _('Choose one');
            var choiceText2 = _('Choose first option');
            if (this.clientStateArgs.visitorCardSecondOption){
                if (this.clientStateArgs.vpPrice>0){
                    choiceText2 = dojo.string.substitute(_('You can choose ${cardName} second option, it will cost ${token_vp}'),{token_vp:this.getTokenSymbol('vp'+this.clientStateArgs.vpPrice), cardName:card.name});
                } else {
                    choiceText2 = dojo.string.substitute(_('Choose ${cardName} second option'),{cardName:card.name});
                }
            }
            this.clientStateArgs.choice1='';
            this.clientStateArgs.choice2='';
            this.clientStateArgs.choice3='';
            this.clientStateArgs.choiceText='';

            switch (this.clientStateArgs.visitorCardKey) {
                case 801: //Merchant
                    //Pay ${token_lira3} to place a ${token_grapeRed1} and a ${token_grapeWhite1}  on your crush pad OR fill 1 ${token_purpleCard} and gain ${token_vp1} extra.
                    //payLira_3+getGrapeRed_1+getGrapeWhite_1|fillOrder_1+getVp_1
                    this.clientStateArgs.choiceText=choiceText1;
                    this.processPlayCardChoice(1, this.getDescriptionWithTokens(_('Pay ${token_lira3} to place a ${token_grapeRed1} and a ${token_grapeWhite1}')));
                    this.processPlayCardChoice(2, this.getDescriptionWithTokens(_('Fill 1 ${token_purpleCard} and gain ${token_vp1} extra')));
                    this.clientStateArgs.actionConfirm='client_playCard_choose';
                    break;

                case 802: //Crusher
                    //Gain ${token_lira3} and draw 1 ${token_yellowCard} OR draw 1 ${token_purpleCard} and make up to 2 ${token_wineAny}.
                    //GetLira_3+drawYellowCard_1|drawPurpleCard_1+makeWine_2
                    this.clientStateArgs.choiceText=choiceText1;
                    this.processPlayCardChoice(1, this.getDescriptionWithTokens(_('Gain ${token_lira3} and draw 1 ${token_yellowCard}')));
                    this.processPlayCardChoice(2, this.getDescriptionWithTokens(_('Draw 1 ${token_purpleCard} and make up to 2 ${token_wineAny}')));
                    this.clientStateArgs.actionConfirm='client_playCard_choose';
                    break;

                case 803: //Judge
                    //Draw 2 ${token_yellowCardPlus} OR discard 1 ${token_wineAny} of value 4 or more to gain ${token_vp3}.
                    //drawYellowCard_2|discardWineAny_1_value4+getVp_3
                    this.clientStateArgs.choiceText=choiceText1;
                    this.processPlayCardChoice(1, this.getDescriptionWithTokens(_('Draw 2 ${token_yellowCardPlus}')));
                    this.processPlayCardChoice(2, this.getDescriptionWithTokens(_('Discard 1 ${token_wineAny} of value 4 or more to gain ${token_vp3}')));
                    this.clientStateArgs.actionConfirm='client_playCard_choose';
                    break;

                case 804: //Oenologist
                    //Age all ${token_wineAny} in your cellar twice OR pay ${token_lira3} to upgrade your cellar to the next level.
                    //ageWines_2|payLira_2+upgradeCellar
                    this.clientStateArgs.choiceText=choiceText1;
                    this.processPlayCardChoice(1, this.getDescriptionWithTokens(_('Age all ${token_wineAny} in your cellar twice')));
                    this.processPlayCardChoice(2, this.getDescriptionWithTokens(_('Pay ${token_lira3} to upgrade your cellar to the next level')));
                    this.clientStateArgs.actionConfirm='client_playCard_choose';
                    break;

                case 805: //Marketer
                    //Draw 2 ${token_yellowCardPlus} and gain ${token_lira1} OR fill 1 ${token_purpleCard} and gain ${token_vp1} extra.
                    //drawYellowCard_2+getLira_1|fillOrder_1+getVp_1
                    this.clientStateArgs.choiceText=choiceText1;
                    this.processPlayCardChoice(1, this.getDescriptionWithTokens(_('Draw 2 ${token_yellowCardPlus} and gain ${token_lira1}')));
                    this.processPlayCardChoice(2, this.getDescriptionWithTokens(_('Fill 1 ${token_purpleCard} and gain ${token_vp1} extra')));
                    this.clientStateArgs.actionConfirm='client_playCard_choose';
                    break;

                case 806: //Crush Expert
                    //Gain ${token_lira3} and draw 1 ${token_purpleCard} OR make up to 3 ${token_wineAny}.
                    //getLira_3+drawPurpleCard|makeWine_3
                    this.clientStateArgs.choiceText=choiceText1;
                    this.processPlayCardChoice(1, this.getDescriptionWithTokens(_('Gain ${token_lira3} and draw 1 ${token_purpleCard}')));
                    this.processPlayCardChoice(2, this.getDescriptionWithTokens(_('Make up to 3 ${token_wineAny}')));
                    this.clientStateArgs.actionConfirm='client_playCard_choose';
                    break;

                case 807: //Uncertified Teacher
                    //Lose ${token_vp1} to train a ${token_worker} OR gain ${token_vp1} for each opponent who has a total of 6 ${token_worker}.
                    //**special**
                    this.clientStateArgs.choiceText=choiceText1;
                    
                    //calculate possible vps
                    var vps=0;
                    for (var player_id in this.gamedatas_local.players){
                        if (player_id != this.getThisPlayerId()){
                            if (this.getWorkers(player_id).length>=6){
                                vps+=1;
                            }
                        }
                    }

                    this.clientStateArgs.choice2vps=vps;

                    this.processPlayCardChoice(1, this.getDescriptionWithTokens(_('Lose ${token_vp1} to train a ${token_worker}')));
                    this.processPlayCardChoice(2, this.getDescriptionWithTokens(_('Gain ${token_vp1} for each opponent who has a total of 6 ${token_worker}: ${vps}${token_vp}'), false, {vps:vps}));
                    this.clientStateArgs.actionConfirm='client_playCard_choose';
                    break;

                case 808: //Teacher
                    //Make up to 2 ${token_wineAny} OR pay ${token_lira2} to train 1 worker.
                    //makeWine_2|trainWorker_1_price2
                    this.clientStateArgs.choiceText=choiceText1;
                    this.processPlayCardChoice(1, this.getDescriptionWithTokens(_('Make up to 2 ${token_wineAny}')));
                    this.processPlayCardChoice(2, this.getDescriptionWithTokens(_('Pay ${token_lira2} to train 1 worker.')));
                    this.clientStateArgs.actionConfirm='client_playCard_choose';
                    break;

                case 809: //Benefactor
                    //Draw 1 ${token_greenCard} and 1 ${token_yellowCard} card OR discard 2 visitor cards to gain ${token_vp2}.
                    //drawGreenCard+drawYellowCard|discardCard_2+get2Vp
                    this.clientStateArgs.choiceText=choiceText1;
                    this.processPlayCardChoice(1, this.getDescriptionWithTokens(_('Draw 1 ${token_greenCard} and 1 ${token_yellowCard}')));
                    this.processPlayCardChoice(2, this.getDescriptionWithTokens(_('Discard 2 visitor cards to gain ${token_vp2}')));
                    this.clientStateArgs.actionConfirm='client_playCard_choose';
                    break;

                case 810: //Assessor
                    //Gain ${token_lira1} for each card in your hand OR discard your hand (min of 1 card) to gain ${token_vp2}.
                    //**special**
                    this.clientStateArgs.choiceText=choiceText1;
                    var lira=this.gamedatas_local.hand.length-1;
                    this.processPlayCardChoice(1, this.getDescriptionWithTokens(_('Gain ${token_lira1} for each card in your hand: ${lira}${token_lira}'),false,{lira:lira}));
                    this.processPlayCardChoice(2, this.getDescriptionWithTokens(_('Discard your hand (min of 1 card) to gain ${token_vp2}'), false, {}));
                    this.clientStateArgs.actionConfirm='client_playCard_choose';
                    break;

                case 811: //Queen
                    //The player on your right must choose 1: lose ${token_vp1}, give you 2 ${token_anyCard}, or pay you ${token_lira3}.
                    //**special**
                    var previousPlayOrder=Number(playerData.playorder)-1;
                    if (previousPlayOrder==0){
                        previousPlayOrder=this.players_number;
                    }
                    var previousPlayer = this.objectFindByProperty(this.gamedatas_local.players, 'playorder', previousPlayOrder);
                    this.clientStateArgs.choiceText=dojo.string.substitute(_('${playerName} must choose 1: lose ${token_vp1}, give you 2 ${token_anyCard}, or pay you ${token_lira3}.'),
                      {
                          playerName: this.getPlayerNameWithColor(previousPlayer.id),
                          token_vp1: this.getTokenSymbol('vp1'),
                          token_anyCard: this.getTokenSymbol('anyCard'),
                          token_lira3: this.getTokenSymbol('lira3'),
                      }
                    );
                    this.clientStateArgs.actionConfirm='client_playCard_confirm';
                    this.clientStateArgs.visitorCardOption=1;
                    break;

                case 812: //Harvester
                    //Harvest up to 2 fields and choose 1: Gain ${token_lira2} or gain ${token_vp1}.
                    //harvestField_2+getLira_2|harvestField_2+getVp_1
                    this.clientStateArgs.choiceText=choiceText1;
                    this.processPlayCardChoice(1, this.getDescriptionWithTokens(_('Harvest up to 2 fields and gain ${token_gain}'),false,{token_gain:this.getTokenSymbol('lira2')}));
                    this.processPlayCardChoice(2, this.getDescriptionWithTokens(_('Harvest up to 2 fields and gain ${token_gain}'), false, {token_gain:this.getTokenSymbol('vp1')}));
                    this.clientStateArgs.actionConfirm='client_playCard_choose';
                    break;

                case 813: //Professor
                    //Pay ${token_lira2} to train 1 ${token_worker} OR gain ${token_vp2} if you have a total of 6 ${token_worker}.
                    //**special**
                    this.clientStateArgs.choiceText=choiceText1;
                    this.processPlayCardChoice(1, this.getDescriptionWithTokens(_('Pay ${token_lira2} to train 1 ${token_worker}'),false,{}));
                    this.processPlayCardChoice(2, this.getDescriptionWithTokens(_('Gain ${token_vp2} if you have a total of 6 ${token_worker}'), false, {}));
                    this.clientStateArgs.actionConfirm='client_playCard_choose';
                    break;

                case 814: //Master Vintner
                    //Upgrade your cellar to the next level at a ${token_lira2} discount OR age 1 ${token_wineAny} and fill 1 ${token_purpleCard}.
                    //upgradeCellar_discount2|ageWine1+fillOrder_1
                    this.clientStateArgs.choiceText=choiceText1;
                    var price='';
                    if (playerData.mediumCellar==0){
                        price = Number(this.arrayFindByProperty(this.gamedatas_local.playerTokens, 'type', 'mediumCellar').price)-2;
                    } else if (playerData.largeCellar==0){
                        price = Number(this.arrayFindByProperty(this.gamedatas_local.playerTokens, 'type', 'largeCellar').price)-2;
                    }
                    this.processPlayCardChoice(1, dojo.string.substitute(_('Upgrade your cellar to the next level for ${token_price}'),{token_price:this.getTokenSymbol('lira'+price)})+'. '+this.getPreviewLira(this.getThisPlayerId(), -price));
                    this.processPlayCardChoice(2, this.getDescriptionWithTokens(_('Age 1 ${token_wineAny} and fill 1 ${token_purpleCard}'), false, {}));
                    this.clientStateArgs.actionConfirm='client_playCard_choose';
                    break;

                case 815: //Uncertified Oenologist
                    //Age all ${token_wineAny} in your cellar twice OR lose ${token_vp1} to upgrade your cellar to the next level.
                    //ageWines_2|payLVp_1+upgradeCellar
                    this.clientStateArgs.choiceText=choiceText1;
                    this.processPlayCardChoice(1, this.getDescriptionWithTokens(_('Age all ${token_wineAny} in your cellar twice'),false,{}));
                    this.processPlayCardChoice(2, this.getDescriptionWithTokens(_('Lose ${token_vp1} to upgrade your cellar to the next level'), false, {}));
                    this.clientStateArgs.actionConfirm='client_playCard_choose';
                    break;

                case 816: //Promoter
                    //Discard a ${token_grapeAny} or ${token_wineAny} to gain ${token_vp1} and ${token_residualPayment1}.
                    //discardGrapeAny_1+getVp_1+getResidualPayment_1|discardWineAny_1+getVp_1+getResidualPayment_1|
                    this.clientStateArgs.choiceText=choiceText1;
                    this.processPlayCardChoice(1, this.getDescriptionWithTokens(_('Discard a ${token_grapeAny} to gain ${token_vp1} and ${token_residualPayment1}'),false,{}));
                    this.processPlayCardChoice(2, this.getDescriptionWithTokens(_('Discard a ${token_wineAny} to gain ${token_vp1} and ${token_residualPayment1}'), false, {}));
                    this.clientStateArgs.actionConfirm='client_playCard_choose';
                    break;

                case 817: //Mentor
                    //All players may make up to 2 ${token_wineAny}. Draw 1 ${token_greenCardPlus} or ${token_YellowCardPlus} card for each opponent who does this.
                    //**special**
                    this.enableActionMakeWine(true, true);
                    this.clientStateArgs.visitorCardOption=1;
                    break;

                case 818: //Harvest Expert
                    //Harvest 1 field and either draw 1 ${token_greenCardPlus} or pay ${token_lira1} to build a Yoke.
                    //harvestField_1+drawGreenCard_1|harvestField_1+buildStructure_1_yoke_price1
                    this.clientStateArgs.choiceText=choiceText1;
                    this.processPlayCardChoice(1, this.getDescriptionWithTokens(_('Harvest 1 field and draw 1 ${token_greenCardPlus}'),false,{}));
                    this.processPlayCardChoice(2, this.getDescriptionWithTokens(_('Harvest 1 field and pay ${token_lira1} to build a Yoke'), false, {}));
                    this.clientStateArgs.actionConfirm='client_playCard_choose';
                    break;

                case 819: //Innkeeper
                    //As you play this card, put the top card of 2 different discard piles in your hand.
                    //GetDiscardCard_2
                    this.enableActionChooseDiscard();
                    this.clientStateArgs.visitorCardOption=1;
                    break;

                case 820: //Jack-of-all-trades
                    //Choose 2: Harvest 1 field, make up to 2 ${token_wineAny}, or fill 1 ${token_purpleCard}.
                    //HarvestField_1|makeWine_2|fillOrder_1
                    this.clientStateArgs.choiceText=choiceText2;
                    this.processPlayCardChoice(1, this.getDescriptionWithTokens(_('Harvest 1 field')));
                    this.processPlayCardChoice(2, this.getDescriptionWithTokens(_('Make up to 2 ${token_wineAny}')));
                    this.processPlayCardChoice(3, this.getDescriptionWithTokens(_('Fill 1 ${token_purpleCard}')));
                    this.clientStateArgs.actionConfirm='client_playCard_choose';
                    break;

                case 821: //Politician
                    //If you have less than 0${token_vp}, gain ${token_lira6}. Otherwise, draw 1 ${token_greenCardPlus}, 1 ${token_yellowCardPlus}, and 1 ${token_purpleCardPlus}.
                    //**special**
                    if (Number(playerData.score)<0){
                        this.clientStateArgs.choiceText=this.getDescriptionWithTokens(_('Gain ${token_lira6}'));
                    } else {
                        this.clientStateArgs.choiceText=this.getDescriptionWithTokens(_('Draw 1 ${token_greenCardPlus}, 1 ${token_yellowCardPlus}, and 1 ${token_purpleCardPlus}'));
                    }
                    this.clientStateArgs.actionConfirm='client_playCard_confirm';
                    this.clientStateArgs.visitorCardOption=1;
                    break;

                case 822: //Supervisor
                    //Make up to 2 ${token_wineAny}. Gain${token_vp1} for each sparkling wine token you make.
                    //makeWine_2_ifmakesparklingwineeach_1vp
                    this.enableActionMakeWine(true, false);
                    this.clientStateArgs.visitorCardOption=1;
                    break;

                case 823: //Scholar
                    //Draw 2 ${token_purpleCard} OR pay ${token_lira3} to train 1 ${token_worker}. You may lose ${token_vp1} to do both.
                    //drawPurpleCard_2|trainWorker_1_price1
                    this.clientStateArgs.choiceText=choiceText2;
                    this.processPlayCardChoice(1, this.getDescriptionWithTokens(_('Draw 2 ${token_purpleCard}')));
                    this.processPlayCardChoice(2, this.getDescriptionWithTokens(_('Pay ${token_lira3} to train 1 ${token_worker}')));
                    this.clientStateArgs.actionConfirm='client_playCard_choose';
                    break;

                case 824: //Reaper
                    //Harvest up to 3 fields. If you harvest 3 fields, gain ${token_vp2}.
                    //harvestField_3_ifharvested3fields_2vp
                    this.enableActionHarvestField(3);
                    this.clientStateArgs.visitorCardOption=1;
                    break;

                case 825: //Motivator
                    //Each player may retrieve their grande worker. Gain ${token_vp1} for each opponent who does this.
                    //**special**
                    this.clientStateArgs.choiceText=this.getDescriptionWithTokens(card.description);
                    var chooseOptionText1='';
                    var chooseOptionText2='';
                    var availableWorkers = this.getAvailableWorkers(playerId);
                    var availableGrande = false;
                    if (this.arrayFindByProperty(availableWorkers,'t','worker_g')!=null){
                        availableGrande = true;
                    }
                    if (availableGrande && availableWorkers.length>1 ){
                        if (this.gamedatas_local.actionProgress == null){
                            chooseOptionText1=dojo.string.substitute(_('Use ${token_worker_g} and retrieve it'), {token_worker_g: this.getTokenPlayerSymbol(this.getThisPlayerId(),'worker_g')});
                            if (this.clientStateArgs.previewTokens.worker.t != 'worker_g'){
                                chooseOptionText2=dojo.string.substitute(_('Proceed without using  ${token_worker_g}'), {token_worker_g: this.getTokenPlayerSymbol(this.getThisPlayerId(),'worker_g')});
                            }
                            this.enableActionCardChooseOption(this.clientStateArgs.choiceText, chooseOptionText1,chooseOptionText2);
                            this.clientStateArgs.visitorCardOption=1;
                            this.clientStateArgs.actionConfirm='';
                        } else {
                            this.clientStateArgs.visitorCardOption=1;
                            this.clientStateArgs.otherSelection='2';
                            this.clientStateArgs.actionConfirm='client_playCard_confirm';
                        }
                    } else {
                        this.clientStateArgs.visitorCardOption=1;
                        this.clientStateArgs.otherSelection='1';
                        this.clientStateArgs.actionConfirm='client_playCard_confirm';
                    }
                    break;

                case 826: //Bottler
                    //Make up to 3 ${token_wineAny}. Gain ${token_vp1} for each type of wine you make.
                    //makeWine_3_ifdistincttype_get1vp **needs history of wines**
                    this.enableActionMakeWine(true, false);
                    this.clientStateArgs.visitorCardOption=1;
                    break;

                case 827: //Craftsman
                    //Choose 2: Draw 1 ${token_purpleCard}, upgrade your cellar at the regular cost, or gain ${token_vp1}.
                    //drawPurpleCard_1|upgradeCellar|getVp_1
                    this.clientStateArgs.choiceText=choiceText2;
                    var price='';
                    if (playerData.mediumCellar==0){
                        price = Number(this.arrayFindByProperty(this.gamedatas_local.playerTokens, 'type', 'mediumCellar').price);
                    } else if (playerData.largeCellar==0){
                        price = Number(this.arrayFindByProperty(this.gamedatas_local.playerTokens, 'type', 'largeCellar').price);
                    }
                    this.processPlayCardChoice(1, this.getDescriptionWithTokens(_('Draw 1 ${token_purpleCard}')));
                    this.processPlayCardChoice(2, dojo.string.substitute(_('Upgrade your cellar at the regular cost ${token_price}'),{token_price:this.getTokenSymbol('lira'+price)})+'. '+this.getPreviewLira(this.getThisPlayerId(), -price));
                    this.processPlayCardChoice(3, this.getDescriptionWithTokens(_('Gain ${token_vp1}')));
                    this.clientStateArgs.actionConfirm='client_playCard_choose';
                    break;

                case 828: //Exporter
                    //Choose 1: Make up to 2 ${token_wineAny}, fill 1 ${token_purpleCard}, or discard 1 ${token_grapeAny} to gain ${token_vp2}.
                    //makeWine_2|fillOrder_1|discardGrapeAny+getVp_2
                    this.clientStateArgs.choiceText=choiceText1;
                    this.processPlayCardChoice(1, this.getDescriptionWithTokens(_('Make up to 2 ${token_wineAny}'),false,{}));
                    this.processPlayCardChoice(2, this.getDescriptionWithTokens(_('Fill 1 ${token_purpleCard}'), false, {}));
                    this.processPlayCardChoice(3, this.getDescriptionWithTokens(_('Discard 1 ${token_grapeAny} to gain ${token_vp2}'), false, {}));
                    this.clientStateArgs.actionConfirm='client_playCard_choose';
                    break;

                case 829: //Laborer
                    //Harvest up to 2 fields OR make up to 3 ${token_wineAny}. You may lose ${token_vp1} to do both.
                    //harvestField_2|makeWine_3
                    this.clientStateArgs.choiceText=choiceText2;
                    this.processPlayCardChoice(1, this.getDescriptionWithTokens(_('Harvest up to 2 fields')));
                    this.processPlayCardChoice(2, this.getDescriptionWithTokens(_('Make up to 3 ${token_wineAny}')));
                    this.clientStateArgs.actionConfirm='client_playCard_choose';
                    break;

                case 830: //Designer
                    //Build 1 structure at its regular cost. Then, if you have at least 6 structures, gain ${token_vp2}.
                    //buildStructure_1_ifstructuturesgt_6_vp2
                    this.enableActionBuild(0,this.getDescriptionWithTokens(card.description ),null,false);
                    this.clientStateArgs.visitorCardOption=1;
                    break;

                case 831: //Governess
                    //Pay ${token_lira3} to train 1 ${token_worker} that you may use this year OR discard 1 ${token_wineAny} to gain ${token_vp2}.
                    //**special**
                    this.clientStateArgs.choiceText=choiceText1;
                    this.processPlayCardChoice(1, this.getDescriptionWithTokens(_('Pay ${token_lira3} to train 1 ${token_worker} that you may use this year'),false,{}));
                    this.processPlayCardChoice(2, this.getDescriptionWithTokens(_('Discard 1 ${token_wineAny} to gain ${token_vp2}'), false, {}));
                    this.clientStateArgs.actionConfirm='client_playCard_choose';
                    break;

                case 832: //Manager
                    //Take any action (no bonus) from a previous season without placing a worker.
                    //**special**
                    this.clientStateArgs.choiceText=this.getDescriptionWithTokens(card.description);
                    this.clientStateArgs.actionConfirm='client_playCard_confirm';
                    this.clientStateArgs.visitorCardOption=1;
                    this.clientStateArgs.confirmButtonText=_('Proceed to location choice');
                    break;

                case 833: //Zymologist
                    //Make up to 2 ${token_wineAny} of value 4 or greater, even if you haven't upgraded your cellar.
                    //makeWine_2_value4withouthmediumcellar
                    this.enableActionMakeWine(false, false);
                    this.clientStateArgs.visitorCardOption=1;
                    break;

                case 834: //Noble
                    //Pay ${token_lira1} to gain ${token_residualPayment1} OR lose ${token_residualPayment2} to gain ${token_vp2}.
                    //payLira_1+getResidualPayment_1|payResidualPayment_2+getVp_2
                    this.clientStateArgs.choiceText=choiceText1;
                    this.processPlayCardChoice(1, this.getDescriptionWithTokens(_('Pay ${token_lira1} to gain ${token_residualPayment1}'),false,{}));
                    this.processPlayCardChoice(2, this.getDescriptionWithTokens(_('Lose ${token_residualPayment2} to gain ${token_vp2}'), false, {}));
                    this.clientStateArgs.actionConfirm='client_playCard_choose';
                    break;

                case 835: //Governor
                    //Choose up to 3 opponents to each give you 1 ${token_yellowCard}. Gain ${token_vp1} for each of them who cannot.
                    //**special**
                    this.clientStateArgs.choiceText=this.getDescriptionWithTokens(card.description);
                    var maxPlayers = 1;
                    var minPlayers = 1;
                    if (this.players_number>=3){maxPlayers++;}
                    if (this.players_number>=4){maxPlayers++;}
                    this.enableActionSelectPlayers(minPlayers,maxPlayers, this.getDescriptionWithTokens(card.description), _('Confirm selection?'));
                    this.clientStateArgs.actionConfirm='client_selectPlayers_choose';
                    this.clientStateArgs.visitorCardOption=1;
                    break;

                case 836: //Taster
                    //Discard 1 ${token_wineAny} to gain ${token_lira4}. If it is the most valuable wine token in any player's cellar (no ties), gain 2 ${token_vp2}.
                    //**special**
                    this.enableActionDiscardWine('',1,9,this.getDescriptionWithTokens(_('Choose a wine ${token_wineAny} to gain ${token_lira4}. If it is the most valuable wine token in any player\'s cellar (no ties), gain 2 ${token_vp2}')), _('Confirm selection?'));
                    this.clientStateArgs.visitorCardOption=1;
                    break;

                case 837: //Caravan
                    //Turn the top card of each deck face up. Draw 2 of those cards and discard the others.
                    //**special** requires state with first card of deck
                    this.clientStateArgs.choiceText=this.getDescriptionWithTokens(card.description);
                    this.clientStateArgs.actionConfirm='client_playCard_confirm';
                    this.clientStateArgs.visitorCardOption=1;
                    break;

                case 838: //Guest Speaker
                    //All players may pay ${token_lira1} to train ${token_worker}. Gain ${token_vp1} for each opponent who does this.
                    //**special**
                    this.clientStateArgs.choiceText=this.getDescriptionWithTokens(card.description);
                    var chooseOptionText1='';
                    var chooseOptionText2='';
                    var availableNewWorkers = this.getAvailableNewWorkers(playerId);
                    if (playerData.lira>0 && availableNewWorkers.length>0){
                        chooseOptionText1=this.getDescriptionWithTokens(_('Pay ${token_lira1} to train ${token_worker}'));
                        chooseOptionText2=_('Proceed without training new worker');
                    } else {
                        chooseOptionText1='';
                        chooseOptionText2=_('Proceed without training new worker');
                    }
                    this.enableActionCardChooseOption(this.clientStateArgs.choiceText, chooseOptionText1,chooseOptionText2);
                    this.clientStateArgs.visitorCardOption=1;
                    this.clientStateArgs.actionConfirm='';
                    break;

                default:
                    break;
            }

            if (this.clientStateArgs.actionConfirm && this.clientStateArgs.choiceText){
                this.setClientStateAction(this.clientStateArgs.actionConfirm, this.clientStateArgs.choiceText);
            }
        },

        processPlayCardChoice: function(choice, text){
            var choices = this.arrayFindByProperty(this.gamedatas_local.hand,'i', this.clientStateArgs.visitorCardId).c;
            var bitChoice = Math.pow(2,choice-1);
            if (choices && (choices&bitChoice)==bitChoice){
                this.clientStateArgs['choice'+choice] = text;
            }
            return;
        },

        onChoosePurpleCardFillOrderSelection: function(control_name, item_id){
            console.log( '$$$$ Event : onChoosePurpleCardFillOrderSelection' );

            var playerId = this.getThisPlayerId();
            var playerData = this.gamedatas_local.players[playerId];
            var playerBoardId = 'playerboard_'+playerId;
            var stock = this.handZone;

            var action=this.clientStateArgs.action;

            if (stock.selectable==0){
                return;
            }

            if( ! this.checkAction( action, false ) )
            { return; }

            if (action){

                var selected = stock.getSelectedItems();
                if (selected.length<1){
                    this.queryAndDisconnectEvent('#'+playerBoardId+' .wine.active_slot','click');
                    this.queryAndRemoveClass('#'+playerBoardId+' .wine.active_slot','active_slot');
                    this.queryAndRemoveClass('#'+playerBoardId+' .wine.selected','selected');
                    this.gotoFillOrderChooseState();
                    return;
                }

                //check card, must be purple and must not be disabled
                var card;
                var key;
                var cardId;
                for (var i=0;i<selected.length;i++){
                    key = Number(selected[i].type);
                    card = this.gamedatas_local.purpleCards[key];
                    cardId = Number(selected[i].id);
                    if (card){
                        if (this.queryCount('#'+stock.getItemDivId(cardId)+'.disabled')){
                            stock.unselectItem(cardId);
                            this.gotoFillOrderChooseState();
                            return;
                        }
                        break;
                    }
                }

                if (!card){
                    stock.unselectItem( cardId );
                    if (this.clientStateArgs.cardId){
                        stock.selectItem( this.clientStateArgs.cardId );
                        selected = stock.getSelectedItems();
                        key = Number(selected[0].type);
                        cardId = selected[0].id;
                        card = this.gamedatas_local.purpleCards[key];
                    } else {
                        return;
                    }
                }

                if (!card){
                    return;
                }

                this.queryAndDisconnectEvent('#'+playerBoardId+' .wine.active_slot','click');
                this.queryAndRemoveClass('#'+playerBoardId+' .wine.active_slot','active_slot');
                this.queryAndRemoveClass('#'+playerBoardId+' .wine.selected','selected');
                this.clientStateArgs.grapes = [];
                this.clientStateArgs.orderWines = [];

                this.clientStateArgs.wineRed = [];
                this.clientStateArgs.wineWhite = [];
                this.clientStateArgs.wineBlush = [];
                this.clientStateArgs.wineSparkling = [];
                this.arrayPushElementsIfNotZero(this.clientStateArgs.wineRed, card.red1, card.red2, card.red3);
                this.arrayPushElementsIfNotZero(this.clientStateArgs.wineWhite, card.white1, card.white2, card.white3);
                this.arrayPushElementsIfNotZero(this.clientStateArgs.wineBlush, card.blush1, card.blush2);
                this.arrayPushElementsIfNotZero(this.clientStateArgs.wineSparkling, card.sparkling);
                this.clientStateArgs.wineTypes = [];
                if (this.clientStateArgs.wineRed.length>0){ this.clientStateArgs.wineTypes.push('wineRed');};
                if (this.clientStateArgs.wineWhite.length>0){ this.clientStateArgs.wineTypes.push('wineWhite');};
                if (this.clientStateArgs.wineBlush.length>0){ this.clientStateArgs.wineTypes.push('wineBlush');};
                if (this.clientStateArgs.wineSparkling.length>0){ this.clientStateArgs.wineTypes.push('wineSparkling');};

                for (var i=0;i<this.clientStateArgs.wineTypes.length;i++){
                    var minimumValue = 9;
                    var wineValues=this.clientStateArgs[this.clientStateArgs.wineTypes[i]];
                    for (var j=0;j<wineValues.length;j++){
                        if (wineValues[j]<minimumValue){
                            minimumValue = wineValues[j];
                        }
                    }
                    for (var j=minimumValue;j<=9;j++){
                        this.queryAndAddEvent('#'+playerBoardId+' .wine.'+this.clientStateArgs.wineTypes[i]+'[data-arg='+j+']','click','onChooseWineFillOrderClick');
                        this.queryAndAddClass('#'+playerBoardId+' .wine.'+this.clientStateArgs.wineTypes[i]+'[data-arg='+j+']','active_slot');
                    }
                }

                //auto-select wines if no other choices
                var selectableWines = dojo.query('#'+playerBoardId+' .wine.active_slot');
                if (selectableWines.length==this.clientStateArgs.wineRed.length +
                    this.clientStateArgs.wineWhite.length +
                    this.clientStateArgs.wineBlush.length + this.clientStateArgs.wineSparkling.length){
                    this.queryAndAddClass('#'+playerBoardId+' .wine.active_slot','selected');
                    var wineSelected = dojo.query('#'+playerBoardId+' .wine.selected');
                    for (var i=0;i<wineSelected.length;i++){
                        this.clientStateArgs.orderWines.push(
                            {
                                id:wineSelected[i].id.split('_')[2],
                                type:wineSelected[i].getAttribute('data-type'),
                                value: Number(wineSelected[i].getAttribute('data-arg'))
                            });
                    }
                }

                this.clientStateArgs.cardId = Number(cardId);
                this.clientStateArgs.cardKey = key;

                this.checkFillOrder();

            }
        },

        checkPlantAction: function(){
            if (!this.clientStateArgs.cardId || !this.clientStateArgs.field ){
                return;
            }

            var field = this.gamedatas_local.fields[this.clientStateArgs.field];
            var card = this.gamedatas_local.greenCards[this.clientStateArgs.cardKey];

            //check for green card and max vine total
            if (this.clientStateArgs.checkLimit==true){
                var totalField = 0;
                var fieldVines = this.gamedatas_local.players[this.getThisPlayerId()]['vine'+this.clientStateArgs.field];
                for (var i=0;i<fieldVines.length;i++){
                    totalField+=fieldVines[i].r+fieldVines[i].w;
                }
                if (totalField+card.red+card.white>Number(field.maxValue)){
                    var translated = dojo.string.substitute( _("You cannot plant ${cardName} in field ${fieldNumber}, it exceeds field max value"),
                    {
                        cardName: _(card.name),
                        fieldNumber: this.clientStateArgs.field
                    });
                    var actionConfirm = 'client_plant_choose';
                    this.setClientStateAction(actionConfirm,translated);
                    return;
                }
            }

            var translated = dojo.string.substitute( _("Confirm to plant ${cardName} in field ${fieldNumber}?"),
                {
                    cardName: _(card.name),
                    fieldNumber: this.clientStateArgs.field
                });
            var actionConfirm = 'client_plant_confirm';
            this.setClientStateAction(actionConfirm,translated);
        },

        checkMakeWineAction: function(){
            if (!this.clientStateArgs.wine || !this.clientStateArgs.wineValue){
                this.gotoMakeWineChooseState('');
                return;
            }

            var wine = this.wines[this.clientStateArgs.wine];
            var grapes = this.clientStateArgs.grapes;
            if (grapes.length==0){
                this.gotoMakeWineChooseState(dojo.string.substitute(_('Choose grape(s)')));
                return;
            }

            var totalGrapes = 0;
            for (var grape in wine.origin){
                var found=0;
                for (var i=0;i<grapes.length;i++){
                    if (grapes[i].type==grape){
                        found++;
                        totalGrapes+=grapes[i].value;
                    }
                }
                if (found<wine.origin[grape]){
                    this.gotoMakeWineChooseState(dojo.string.substitute(_('Choose ${number} grape(s) of type ${grapeType}'),{grapeType:this.getGrapeName(grape), number:wine.origin[grape]-found}));
                    return;
                }
                if (found>wine.origin[grape]){
                    this.gotoMakeWineChooseState(dojo.string.substitute(_('Too many grapes chosen of type ${grapeType}'),{grapeType:this.getGrapeName(grape)}));
                    return;
                }
            }
            if (totalGrapes<this.clientStateArgs.wineValue){
                this.gotoMakeWineChooseState(dojo.string.substitute(_('Total of selected grapes (${value}) is less than wine value (${wineValue})'),
                    {
                        value:totalGrapes,
                        wineValue: this.clientStateArgs.wineValue
                    }));
                return;
            }

            var translated = dojo.string.substitute( _("Confirm this wine?"),{ });

            var actionConfirm = 'client_makeWine_confirm';
            var tmpGrapesId = [];
            for (var i=0;i<grapes.length;i++){
                tmpGrapesId.push(grapes[i].id);
            }
            this.clientStateArgs.grapesId = tmpGrapesId.join(',');
            this.setClientStateAction(actionConfirm,translated);
        },

        checkFillOrder: function(){
            if (!this.clientStateArgs.cardId ){
                this.gotoFillOrderChooseState('');
                return;
            }

            var orderWines = this.clientStateArgs.orderWines;
            if (orderWines.length==0){
                this.gotoMakeWineChooseState(dojo.string.substitute(_('Choose wine(s)'),{}));
                //TODO: better description/tokens
                return;
            }

            //correct total number of wines selected
            if (orderWines.length == this.clientStateArgs.wineRed.length +
                this.clientStateArgs.wineWhite.length +
                this.clientStateArgs.wineBlush.length + this.clientStateArgs.wineSparkling.length){

                //check correct type and value

                //sort all by values (lesser to greater) for easy compare
                var selectedWines = dojo.clone(orderWines);
                this.arraySortByProperty(selectedWines,'value');
                this.clientStateArgs.wineRed.sort();
                this.clientStateArgs.wineWhite.sort();
                this.clientStateArgs.wineBlush.sort();
                this.clientStateArgs.wineSparkling.sort();

                //loop over winetypes
                for (var wineType in this.wines){
                    //loop over values of winetype in order (from less value to great value)
                    for (var i=0;i<this.clientStateArgs[wineType].length;i++){
                        //search
                        var found = false;
                        for (var j=0;j<selectedWines.length;j++){
                            if (selectedWines[j].type==wineType && selectedWines[j].value>=this.clientStateArgs[wineType][i]){
                                //remove wine
                                selectedWines.splice(j,1);
                                found = true;
                                break;
                            }
                        }
                        if (!found){
                            this.gotoMakeWineChooseState(dojo.string.substitute(_('Not enough wines chosen with type ${wineType}${wineToken} and value >= ${value} '),{wineType:this.getWineName(wineType), wineToken:this.getTokenSymbol(wineType), value: this.clientStateArgs[wineType][i]}));
                            return;
                        }
                    }
                }
                //if no wines then all wines used!
                if (selectedWines.length!=0){
                    this.gotoMakeWineChooseState(dojo.string.substitute(_('Wrong wines chosen'),{}));
                    return;
                }

            } else {
                this.gotoMakeWineChooseState(dojo.string.substitute(_('Wrong wines chosen'),{}));
                return;
            }

            var translated = dojo.string.substitute( _("Confirm this order?"),{ });

            var actionConfirm = 'client_fillOrder_confirm';
            var tmpOrderWines = [];
            for (var i=0;i<orderWines.length;i++){
                tmpOrderWines.push(orderWines[i].id);
            }
            this.clientStateArgs.orderWinesId = tmpOrderWines.join(',');
            this.setClientStateAction(actionConfirm,translated);
        },

        checkSellGrapesSellBuyVine: function(){
            var translated;

            if (!this.clientStateArgs.type
                || (this.clientStateArgs.type  == 'buyField' && !this.clientStateArgs.buyField)
                || (this.clientStateArgs.type  == 'sellField' && !this.clientStateArgs.sellField)
                || (this.clientStateArgs.type  == 'sellGrapes' && this.clientStateArgs.sellGrapes.length==0)){
                this.gotoSellGrapesSellBuyVine('');
                return;
            }

            var initialLira = Number(this.getPlayerData(this.getThisPlayerId()).lira);

            var actionConfirm = 'client_sellGrapesSellBuyVine_confirm';
            var tmpGrapesId = [];
            var grapes = this.clientStateArgs.sellGrapes;
            var priceGrapes = 0;
            var tokenGrapes = '';
            if (grapes){
                for (var i=0;i<grapes.length;i++){
                    tmpGrapesId.push(grapes[i].id);
                    priceGrapes+=grapes[i].price;
                    tokenGrapes+=grapes[i].token;
                }
            }

            if (this.clientStateArgs.type  == 'buyField'){
                translated = dojo.string.substitute( _("Confirm to buy the field for ${token_lira}?"),{
                    token_lira: this.getTokenSymbol('lira'+this.clientStateArgs.price)
                 })+' '+this.getPreviewLira(this.getThisPlayerId(), -this.clientStateArgs.price);
            }

            if (this.clientStateArgs.type  == 'sellField'){
                translated = dojo.string.substitute( _("Confirm to sell the field for ${token_lira}?"),{
                    token_lira: this.getTokenSymbol('lira'+this.clientStateArgs.price)
                 })+' '+this.getPreviewLira(this.getThisPlayerId(), this.clientStateArgs.price);
            }

            if (this.clientStateArgs.type  == 'sellGrapes'){
                translated = dojo.string.substitute( _("Confirm to sell ${grapes} grape(s) for ${token_lira}?"),{
                    price: priceGrapes,
                    token_lira: this.getTokenSymbol('lira'+priceGrapes),
                    grapes: tokenGrapes
                 })+' '+this.getPreviewLira(this.getThisPlayerId(), priceGrapes);
            }

            this.clientStateArgs.sellGrapesId = tmpGrapesId.join(',');
            this.setClientStateAction(actionConfirm,translated);
        },

        checkSwitchVines: function(){
            var translated;

            if (this.clientStateArgs.cardsSelected.length != 2){
                this.gotoSwitchVines('');
                return;
            }

            var actionConfirm = 'client_switchVines_confirm';
            var tmpCardsSelectedId = [];
            var cardsSelected = this.clientStateArgs.cardsSelected;
            if (cardsSelected){
                for (var i=0;i<cardsSelected.length;i++){
                    tmpCardsSelectedId.push(cardsSelected[i].id);
                }
            }

            var fieldVine1 = this.getFieldOfVine(this.getThisPlayerId(), tmpCardsSelectedId[0]);
            var fieldVine2 = this.getFieldOfVine(this.getThisPlayerId(), tmpCardsSelectedId[1]);
            if (fieldVine1==fieldVine2){
                this.gotoSwitchVines(_('Select two vines to switch in different fields'));
                return;
            }

            var translated = dojo.string.substitute( _("Confirm switch of the selected fields?"),{ });
            this.clientStateArgs.cardsSelectedId = tmpCardsSelectedId.join(',');
            this.setClientStateAction(actionConfirm,translated);
        },

        getFieldOfVine: function(playerId, vineId){
            var playerData = this.getPlayerData(playerId);
            if (this.arrayFindByProperty(playerData.vine1,'i',vineId)){
                return 1;
            }
            if (this.arrayFindByProperty(playerData.vine2,'i',vineId)){
                return 2;
            }
            if (this.arrayFindByProperty(playerData.vine3,'i',vineId)){
                return 3;
            }
            return null;
        },

        getGrapeName: function(type){
            if (type=='red'||type=='grapeRed'){
                return _('Red Grape');
            }
            if (type=='white'||type=='grapeWhite'){
                return _('White Grape');
            }
            return type;
        },

        getWineName: function(type){
            return this.wines[type].label;
        },


        checkHarvestFieldAction: function(){
            if (!this.clientStateArgs.harvestFields || this.clientStateArgs.harvestFields.length==0 ){
                this.gotoHarvestFieldChooseState();
                return;
            }

            var translated = dojo.string.substitute( _("Confirm harvest of selected field(s)?"),{ });

            this.clientStateArgs.askConfirm = '';
            if (this.clientStateArgs.harvestFields.length < this.clientStateArgs.maxHarvest){
                if (this.queryCount('.action_slot.field_slot.field.active_slot:not(.selected)')>0){
                    translated = dojo.string.substitute( _("Confirm harvest of selected field(s)? You can select up to ${number} fields"),{number: this.clientStateArgs.maxHarvest});
                    this.clientStateArgs.askConfirm = dojo.string.substitute( _("You chose only ${selected} fields. <strong>You can select up to ${number} field(s)!</strong><br/>Do you confirm to harvest the selected field(s) only?"),{selected: this.clientStateArgs.harvestFields.length, number: this.clientStateArgs.maxHarvest});
                }
            }

            var actionConfirm = 'client_harvestField_confirm';
            this.clientStateArgs.harvestFieldsId = this.clientStateArgs.harvestFields.join(',');
            this.setClientStateAction(actionConfirm,translated);
        },

        checkSelectWorkersAction: function(){
            if (!this.clientStateArgs.workersSelected || this.clientStateArgs.workersSelected.length<this.clientStateArgs.minWorkers ){
                this.setClientStateAction('client_selectWorkers_choose',this.clientStateArgs.selectWorkersText);
                return;
            }

            var translated = this.clientStateArgs.selectWorkersConfirm;

            this.clientStateArgs.askConfirm='';
            if (this.clientStateArgs.workersSelected.length < this.clientStateArgs.maxWorkers){
                if (this.queryCount('.worker.active_slot:not(.selected)')>0){
                    translated = dojo.string.substitute( _("Confirm selected worker(s)? You can select up to ${number} worker(s)"),{number: this.clientStateArgs.maxWorkers});
                    this.clientStateArgs.askConfirm = dojo.string.substitute( _("You chose only ${selected} worker(s). <strong>You can select up to ${number} worker(s)!</strong><br/>Do you confirm the selected worker(s) only?"),{selected: this.clientStateArgs.workersSelected.length, number: this.clientStateArgs.maxWorkers});
                }
            }

            var actionConfirm = 'client_selectWorkers_confirm';
            this.clientStateArgs.workersSelectedId = this.clientStateArgs.workersSelected.join(',');
            this.setClientStateAction(actionConfirm,translated);
        },

        checkSelectDiscardCardsAction: function(){
            if (!this.clientStateArgs.cardsSelected || this.clientStateArgs.cardsSelected.length<this.clientStateArgs.minCards ){
                this.setClientStateAction('client_selectDiscard_choose',this.clientStateArgs.selectDiscardText);
                return;
            }

            var translated = this.clientStateArgs.selectDiscardConfirm;

            if (this.clientStateArgs.cardsSelected.length < this.clientStateArgs.maxCards){
                if (this.queryCount('#board .card_discard.active_slot:not(.selected)')>0){
                    translated = dojo.string.substitute( _("Confirm selected card(s)? You can select up to ${number} cards"),{number: this.clientStateArgs.maxCards});
                }
            }

            var actionConfirm = 'client_selectDiscard_confirm';
            this.clientStateArgs.cardsSelectedId = this.clientStateArgs.cardsSelected.join(',');
            this.setClientStateAction(actionConfirm,translated);
        },

        onChooseBuilding: function(evt){
            console.log( '$$$$ Event : onChooseBuilding' );
            dojo.stopEvent( evt );
            var me = this;
            var element = evt.currentTarget;
            var action=this.clientStateArgs.action;
            var actionConfirm="";
            var type = element.getAttribute('data-arg');
            var structure = this.arrayFindByProperty(this.gamedatas_local.playerTokens, 'type', type);
            var price = structure.price - this.clientStateArgs.discount;
            if (price < 0){
                price = 0;
            }
            var initialLira = this.getPlayerData(this.getThisPlayerId()).lira;
            var finalLira = initialLira-price;


            if ( !this.isCurrentPlayerActive() ) {
                return;
            }

            actionConfirm = 'client_buildStructure_confirm';

            if (action){
                if( ! this.checkAction( action ) )
                { return; }

                this.queryAndRemoveClass('.action_slot.building_slot.selected','selected');
                dojo.addClass(element,'selected');

                this.clientStateArgs.structure = type;
                this.activatePreviewToken(type, null, 'structure',false,'player');

                if (this.clientStateArgs.discount>0){
                    translated = dojo.string.substitute( _("Confirm construction of ${structure_name}${token_structure} for ${token_lira} (discount ${token_lira_discount})?"),
                    {
                        structure_name: _(structure.name),
                        token_structure: this.getTokenPlayerSymbol(this.getThisPlayerId(), type),
                        token_lira: this.getTokenSymbol('lira'+price),
                        token_lira_discount: this.getTokenSymbol('lira'+this.clientStateArgs.discount)
                    })+' '+this.getPreviewLira(this.getThisPlayerId(), -price);
                } else {
                    translated = dojo.string.substitute( _("Confirm construction of ${structure_name}${token_structure} for ${token_lira}?"),
                    {
                        structure_name: _(structure.name),
                        token_structure: this.getTokenPlayerSymbol(this.getThisPlayerId(), type),
                        token_lira: this.getTokenSymbol('lira'+price)
                    })+' '+this.getPreviewLira(this.getThisPlayerId(), -price);
                }

                this.setClientStateAction(actionConfirm,translated);
            }
        },

        onSelectStructuresClick: function(evt){
            console.log( '$$$$ Event : onSelectStructuresClick' );
            dojo.stopEvent( evt );

            var me = this;
            var element = evt.currentTarget;
            var action=this.clientStateArgs.action;
            var actionConfirm="";
            var type = element.getAttribute('data-arg');
            var price = 8;
            var initialLira = this.getPlayerData(this.getThisPlayerId()).lira;
            var finalLira = initialLira-price;
            var token_structures='';

            if ( !this.isCurrentPlayerActive() ) {
                return;
            }

            actionConfirm = 'client_selectStructures_confirm';

            if (action){
                if( ! this.checkAction( action ) )
                { return; }

                if (dojo.hasClass(element,'selected')){
                    dojo.removeClass(element,'selected');
                    this.activatePreviewToken(null, null, 'structure', false,'player');
                } else {
                    dojo.addClass(element,'selected');
                    this.activatePreviewToken(type, null, 'structure', false,'player');
                }

                var structures = [];
                var structureElements = dojo.query('.action_slot.building_slot.selected');

                if (structureElements.length==0||structureElements.length>2){
                    this.setClientStateAction('client_selectStructures_choose',_('Choose up to two structures'));
                    return;
                }

                for (var i=0;i<structureElements.length;i++){
                    structures.push(structureElements[i].getAttribute('data-arg'));
                    token_structures+=this.getTokenSymbol(structureElements[i].getAttribute('data-arg'));
                }

                this.clientStateArgs.otherSelection = structures.join('_');
                this.clientStateArgs.askConfirm = '';
                var message = _("Confirm construction of selected structures ${token_structures} for ${token_lira}?");
                if (structures.length<2 && dojo.query('.action_slot.building_slot.active_slot').length>1){
                    message += " "+_("(You can choose a second building)");
                    this.clientStateArgs.askConfirm = dojo.string.substitute( _('You chose only one building. <strong>You can choose one more!</strong><br/>Do you confirm only one building :${token_structures}?'),
                        {
                            token_structures: token_structures,
                            token_lira: this.getTokenSymbol('lira'+price)
                        });
                }

                translated = dojo.string.substitute( message,
                    {
                        token_structures: token_structures,
                        token_lira: this.getTokenSymbol('lira'+price)
                    }) +' '+this.getPreviewLira(this.getThisPlayerId(), -price);
                this.setClientStateAction(actionConfirm,translated);
            }

        },

        activatePreviewToken: function(type, elementPositionId, key, reset, location){
            if (!this.clientStateArgs.previewTokens || reset){
                this.clientStateArgs.previewTokens = {};
            }
            if (type){
                this.clientStateArgs.previewTokens[key] = {t:type, e:elementPositionId, l:location};
            } else {
                this.clientStateArgs.previewTokens[key] = null;
            }
            this.updatePreviewTokens();
        },

        cloneCardMamaPapa: function(cardId){
            var card = this.gamedatas_local.mamas[Number(cardId)];
            if (!card){
                card = this.gamedatas_local.papas[Number(cardId)];
                card.type = 'papa';
            } else {
                card.type = 'mama';
            }
            var card = dojo.clone(card);
            return card;
        },

        cloneCard: function(cardId){
            var card = this.gamedatas_local.greenCards[Number(cardId)];
            if (!card){
                var card = this.gamedatas_local.yellowCards[Number(cardId)];
                if (!card){
                    var card = this.gamedatas_local.purpleCards[Number(cardId)];
                    if (!card){
                        card = this.gamedatas_local.blueCards[Number(cardId)];
                        if (!card){
                            card = this.gamedatas_local.automaCards[Number(cardId)];
                            card.type = 'automaCard';
                        } else {
                            card.type = 'blueCard';
                        }
                    } else {
                        card.type = 'purpleCard';
                    }
                } else {
                    card.type = 'yellowCard';
                }
            } else {
                card.type = 'greenCard';
            }
            var card = dojo.clone(card);
            return card;
        },

        getCardWeight: function(cardKey, cardType){
            var cardNumber = Number(cardKey);
            switch (cardType){
                case "greenCard":
                    return cardNumber%100;
                case "yellowCard":
                    return cardNumber%100+100;
                case "blueCard":
                    return cardNumber%100+200;
                case "purpleCard":
                    return cardNumber%100+300;
            }
            return cardNumber;
        },

        getCardType: function(cardId){
            var cardType = null;
            var card = this.gamedatas_local.greenCards[Number(cardId)];
            if (!card){
                var card = this.gamedatas_local.yellowCards[Number(cardId)];
                if (!card){
                    var card = this.gamedatas_local.purpleCards[Number(cardId)];
                    if (!card){
                        var card = this.gamedatas_local.blueCards[Number(cardId)];
                        if (!card){
                            var card = this.gamedatas_local.automaCards[Number(cardId)];
                            if (!card){
                                cardType = '';
                            } else {
                                cardType = 'automaCard'
                            }
                        } else {
                            cardType = 'blueCard'
                        }
                    } else {
                        cardType = 'purpleCard';
                    }
                } else {
                    cardType = 'yellowCard';
                }
            } else {
                cardType = 'greenCard';
            }
            return cardType;
        },

        getHtmlMamaPapaCard: function(type_id, id, elementId, cssClass){
            var item = this.cloneCardMamaPapa(type_id);
            if (elementId){
                item.elementId=elementId;
            } else {
                item.elementId='card_inner_'+type_id;
            }
            item.cssClass="shadow "+cssClass+" "+item.type;
            item.title=_(item.type);
            item.cardType=item.type;
            item.type=type_id;
            item.id=id;
            item.name=item.name||'';
            item.description=item.description||'';
            item.position=""; //position:absolute;z-index:4;left:${left}px;top:${top}px;
            item.x=type_id;
            item.y='';
            item.style='';
            return this.format_block( 'jstpl_card_mama_papa', item);
        },

        stockSetupMamaPapaCard: function(div, type_id, id){
            var item = this.cloneCardMamaPapa(type_id);
            item.elementId=div.id+'|card_inner_'+type_id;
            item.cssClass="shadow medium "+item.type;
            item.title=_(item.type);
            item.cardType=item.type;
            item.type=type_id;
            item.id=id;
            item.name=item.name||'';
            item.description=item.description||'';
            item.position=""; //position:absolute;z-index:4;left:${left}px;top:${top}px;
            item.x=type_id;
            item.y='';
            item.style='';
            dojo.place( this.format_block( 'jstpl_card_mama_papa', item), div.id );
            this.addTooltipHtml( item.elementId, this.getTooltipHtmlMamaPapaCard(type_id));
        },

        stockSetupCard: function(div, type_id, id){
            var item = this.cloneCard(type_id);

            if (item.type == 'automaCard'){
                item.elementId=div.id+'|card_inner_'+type_id;
                item.cssClass="shadow small "+item.type;
                item.topCssClass="card_stock";
                item.cardType=item.type;
                item.type=type_id;
                item.location='hand';
                item.id=id;
                item.name=item.name||'';
                item.des1=this.getDescriptionWithTokens(item.des1, true,null,this.getThisPlayerId())||'';
                item.des2=this.getDescriptionWithTokens(item.des2, true,null,this.getThisPlayerId())||'';
                item.des3=this.getDescriptionWithTokens(item.des3, true,null,this.getThisPlayerId())||'';
                item.des4=this.getDescriptionWithTokens(item.des4, true,null,this.getThisPlayerId())||'';
                item.cls1='automaCardBar automaCardBar1 automaCardBarSea'+item.sea1+' automaCardBarSet'+item.set1;
                item.cls2='automaCardBar automaCardBar2 automaCardBarSea'+item.sea2+' automaCardBarSet'+item.set2;
                item.cls3='automaCardBar automaCardBar3 automaCardBarSea'+item.sea3+' automaCardBarSet'+item.set3;
                item.cls4='automaCardBar automaCardBar4 automaCardBarSea'+item.sea4+' automaCardBarSet'+item.set4;
                item.position=""; //position:absolute;z-index:4;left:${left}px;top:${top}px;
                item.x=type_id;
                item.y='';
                item.style='';

                if (item.name.length>24){
                    item.cssClass += ' veryLongName';
                } else if (item.name.length>10){
                    item.cssClass += ' longName';
                }

                dojo.place( this.format_block( 'jstpl_card_automa', item), div.id );
            } else {
                item.elementId=div.id+'|card_inner_'+type_id;
                item.cssClass="shadow small "+item.type;
                item.topCssClass="card_stock";
                item.cardType=item.type;
                item.type=type_id;
                item.location='hand';
                item.id=id;
                item.name=item.name||'';
                item.description=this.getDescriptionWithTokens(item.description, true,null,this.getThisPlayerId())||'';
                item.position=""; //position:absolute;z-index:4;left:${left}px;top:${top}px;
                item.x=type_id;
                item.y='';
                item.style='';

                if (item.name.length>24){
                    item.cssClass += ' veryLongName';
                } else if (item.name.length>10){
                    item.cssClass += ' longName';
                }

                dojo.place( this.format_block( 'jstpl_card', item), div.id );
            }
            
            this.addTooltipHtml( item.elementId, this.getTooltipHtmlCard(type_id,this.getThisPlayerId()));
        },

        historyStockSetupCard: function(div, type_id, id){
            var idParts = id.split('_');
            var playerId = idParts[idParts.length-6];
            var year = idParts[idParts.length-4];
            var season = idParts[idParts.length-3];
            var moveNumber = idParts[idParts.length-2];
            var playerData = this.getPlayerData(playerId);
            var item = this.cloneCard(type_id);
            
            if (item.type =='automaCard'){
                item.elementId=div.id+'|card_inner_'+type_id;
                item.cssClass="shadow small "+item.type;
                item.topCssClass="card_stock";
                item.cardType=item.type;
                item.type=type_id;
                item.location='hand';
                item.id=id;
                item.name=item.name||'';
                item.des1=this.getDescriptionWithTokens(item.des1, true,null,this.getThisPlayerId())||'';
                item.des2=this.getDescriptionWithTokens(item.des2, true,null,this.getThisPlayerId())||'';
                item.des3=this.getDescriptionWithTokens(item.des3, true,null,this.getThisPlayerId())||'';
                item.des4=this.getDescriptionWithTokens(item.des4, true,null,this.getThisPlayerId())||'';
                item.cls1='automaCardBar automaCardBar1 automaCardBarSea'+item.sea1+' automaCardBarSet'+item.set1;
                item.cls2='automaCardBar automaCardBar2 automaCardBarSea'+item.sea2+' automaCardBarSet'+item.set2;
                item.cls3='automaCardBar automaCardBar3 automaCardBarSea'+item.sea3+' automaCardBarSet'+item.set3;
                item.cls4='automaCardBar automaCardBar4 automaCardBarSea'+item.sea4+' automaCardBarSet'+item.set4;
                item.position=""; //position:absolute;z-index:4;left:${left}px;top:${top}px;
                item.x=type_id;
                item.y='';
                item.style='';
                item.player_id=playerId;
                item.player_name=playerData.player_name;
                item.player_color=playerData.player_color;
                item.historyMoment = '';
                if (moveNumber != '0' && year != '0' && season!='0'){
                    item.historyMoment='#'+moveNumber+' '+_('Year')+' '+year+' '+this.getSeasonDescription(season);
                }

                if (item.name.length>24){
                    item.cssClass += ' veryLongName';
                } else if (item.name.length>10){
                    item.cssClass += ' longName';
                }

                dojo.place( this.format_block( 'jstpl_card_automa_history', item), div.id );
            } else {
                item.elementId=div.id+'|card_inner_'+type_id;
                item.cssClass="shadow small "+item.type;
                item.topCssClass="card_stock";
                item.cardType=item.type;
                item.type=type_id;
                item.location='hand';
                item.id=id;
                item.name=item.name||'';
                item.description=this.getDescriptionWithTokens(item.description, true, null, playerId)||'';
                item.position=""; //position:absolute;z-index:4;left:${left}px;top:${top}px;
                item.x=type_id;
                item.y='';
                item.style='';
                item.player_id=playerId;
                item.player_name=playerData.player_name;
                item.player_color=playerData.player_color;
                item.historyMoment = '';
                if (moveNumber != '0' && year != '0' && season!='0'){
                    item.historyMoment='#'+moveNumber+' '+_('Year')+' '+year+' '+this.getSeasonDescription(season);
                }
    
                if (item.name.length>24){
                    item.cssClass += ' veryLongName';
                } else if (item.name.length>10){
                    item.cssClass += ' longName';
                }
    
                dojo.place( this.format_block( 'jstpl_card_history', item), div.id );
            }

            this.addTooltipHtml( item.elementId, this.getTooltipHtmlCard(type_id, playerId));
        },

        
        automaCardsStockSetupCard: function(div, type_id, id){
            var item = this.cloneCard(type_id);

            if (item.type == 'automaCard'){
                item.elementId=div.id+'|card_inner_'+type_id;
                item.cssClass="shadow small "+item.type;
                item.topCssClass="card_stock";
                item.cardType=item.type;
                item.type=type_id;
                item.location='hand';
                item.id=id;
                item.name=item.name||'';
                item.des1=this.getDescriptionWithTokens(item.des1, true,null,this.getThisPlayerId())||'';
                item.des2=this.getDescriptionWithTokens(item.des2, true,null,this.getThisPlayerId())||'';
                item.des3=this.getDescriptionWithTokens(item.des3, true,null,this.getThisPlayerId())||'';
                item.des4=this.getDescriptionWithTokens(item.des4, true,null,this.getThisPlayerId())||'';
                item.cls1='automaCardBar automaCardBar1 automaCardBarSea'+item.sea1+' automaCardBarSet'+item.set1;
                item.cls2='automaCardBar automaCardBar2 automaCardBarSea'+item.sea2+' automaCardBarSet'+item.set2;
                item.cls3='automaCardBar automaCardBar3 automaCardBarSea'+item.sea3+' automaCardBarSet'+item.set3;
                item.cls4='automaCardBar automaCardBar4 automaCardBarSea'+item.sea4+' automaCardBarSet'+item.set4;
                item.position=""; //position:absolute;z-index:4;left:${left}px;top:${top}px;
                item.x=type_id;
                item.y='';
                item.style='';

                if (item.name.length>24){
                    item.cssClass += ' veryLongName';
                } else if (item.name.length>10){
                    item.cssClass += ' longName';
                }

                dojo.place( this.format_block( 'jstpl_card_automa', item), div.id );
            } else {
                item.elementId=div.id+'|card_inner_'+type_id;
                item.cssClass="shadow small "+item.type;
                item.topCssClass="card_stock";
                item.cardType=item.type;
                item.type=type_id;
                item.location='hand';
                item.id=id;
                item.name=item.name||'';
                item.description=this.getDescriptionWithTokens(item.description, true,null,this.getThisPlayerId())||'';
                item.position=""; //position:absolute;z-index:4;left:${left}px;top:${top}px;
                item.x=type_id;
                item.y='';
                item.style='';

                if (item.name.length>24){
                    item.cssClass += ' veryLongName';
                } else if (item.name.length>10){
                    item.cssClass += ' longName';
                }

                dojo.place( this.format_block( 'jstpl_card', item), div.id );
            }
            
            this.addTooltipHtml( item.elementId, this.getTooltipHtmlCard(type_id,this.getThisPlayerId()));
        },

        getTooltipHtmlMamaPapaCard: function(cardId){
            var item = this.cloneCardMamaPapa(cardId);
            item.cssClass=item.type;
            item.cardType=item.type;
            item.title=_(item.type);
            item.type=cardId;
            item.style='';
            item.text='';
            item.name=item.name||'';
            item.description=item.description||'';
            if (item.cardType=='mama'){
                var startingResources=this.encapsulateTag('li',this.concatenateMultipleString(this.getTokenSymbol('worker'),2));
                if (Number(item.green)){
                    startingResources = startingResources + this.encapsulateTag('li',this.getTokenDescription('greenCard')+':'+this.concatenateMultipleString(this.getTokenSymbol('greenCard'),Number(item.green)));
                }
                if (Number(item.yellow)){
                    startingResources = startingResources + this.encapsulateTag('li',this.getTokenDescription('yellowCard')+':'+this.concatenateMultipleString(this.getTokenSymbol('yellowCard'),Number(item.yellow)));
                }
                if (Number(item.purple)){
                    startingResources = startingResources + this.encapsulateTag('li',this.getTokenDescription('purpleCard')+':'+this.concatenateMultipleString(this.getTokenSymbol('purpleCard'),Number(item.purple)));
                }
                if (Number(item.blue)){
                    startingResources = startingResources + this.encapsulateTag('li',this.getTokenDescription('blueCard')+':'+this.concatenateMultipleString(this.getTokenSymbol('blueCard'),Number(item.blue)));
                }
                if (Number(item.lira)){
                    startingResources = startingResources + this.encapsulateTag('li',this.getTokenSymbol('lira'+item.lira));
                }
                item.text = _('Mama starting resources:')+'<ul>'+startingResources+'</ul>';
            }
            if (item.cardType=='papa'){
                var startingResources = this.encapsulateTag('li',this.getTokenSymbol('lira'+item.lira)) + this.encapsulateTag('li',this.getTokenDescription('workerGrande')+this.getTokenSymbol('workerGrande'));
                var choice1 = this.getPapaChoiceDescription(item.choice_bonus);
                var choice2 = this.getTokenSymbol('lira'+item.choice_lira);
                item.text = _('Papa starting resources:')+'<ul>'+startingResources+'</ul><hr/>'+dojo.string.substitute(_('Papa starting options: <ul><li>${choice1}</li><li>${choice2}</li></ul>'),{choice1:choice1, choice2:choice2});
            }
            return this.format_block('jstpl_card_mama_papa_tooltip', item );
        },

        getTooltipHtmlCard: function(cardId, playerId){
            var item = this.cloneCard(cardId);
            item.cssClass="shadow "+item.type;
            item.cardType=item.type;
            item.type=cardId;
            item.style='';
            item.text='';
            item.name=item.name||'';
            item.tooltipName=item.name;
            item.description=this.getDescriptionWithTokens(item.description, false, null, playerId)||'';
            item.cardTypeDescription=this.getCardTypeDescription(item.cardType);

            if (item.cardType=='purpleCard'){
                item.tooltipName='&nbsp;';//_("Wine order");
                item.text=_("Wines:");
                item.text+='<ul>';
                if (item.red1>0){item.text+=this.encapsulateTag('li',this.getWineSymbol('red',item.red1,'','small')+' : '+dojo.string.substitute(_('Red Wine (${value})'),{value: item.red1}));}
                if (item.red2>0){item.text+=this.encapsulateTag('li',this.getWineSymbol('red',item.red2,'','small')+' : '+dojo.string.substitute(_('Red Wine (${value})'),{value: item.red2}));}
                if (item.red3>0){item.text+=this.encapsulateTag('li',this.getWineSymbol('red',item.red3,'','small')+' : '+dojo.string.substitute(_('Red Wine (${value})'),{value: item.red3}));}
                if (item.white1>0){item.text+=this.encapsulateTag('li',this.getWineSymbol('white',item.white1,'','small')+' : '+dojo.string.substitute(_('White Wine (${value})'),{value: item.white1}));}
                if (item.white2>0){item.text+=this.encapsulateTag('li',this.getWineSymbol('white',item.white2,'','small')+' : '+dojo.string.substitute(_('White Wine (${value})'),{value: item.white2}));}
                if (item.white3>0){item.text+=this.encapsulateTag('li',this.getWineSymbol('white',item.white3,'','small')+' : '+dojo.string.substitute(_('White Wine (${value})'),{value: item.white3}));}
                if (item.blush1>0){item.text+=this.encapsulateTag('li',this.getWineSymbol('blush',item.blush1,'','small')+' : '+dojo.string.substitute(_('Blush Wine (${value})'),{value: item.blush1}));}
                if (item.blush2>0){item.text+=this.encapsulateTag('li',this.getWineSymbol('blush',item.blush2,'','small')+' : '+dojo.string.substitute(_('Blush Wine (${value})'),{value: item.blush2}));}
                if (item.sparkling>0){item.text+=this.encapsulateTag('li',this.getWineSymbol('sparkling',item.sparkling,'','small')+' : '+dojo.string.substitute(_('Sparkling Wine (${value})'),{value: item.sparkling}));}
                item.text+='</ul>';
                item.text+=_("Gain:");
                item.text+='<ul>';
                if (item.vp>0){item.text+=this.encapsulateTag('li',this.getTokenSymbol('vp'+item.vp)+' : '+dojo.string.substitute(_('+${value} Victory Points'),{value: item.vp}));};
                if (item.resid>0){item.text+=this.encapsulateTag('li',this.getTokenSymbol('residualPayment'+item.resid)+' : '+dojo.string.substitute(_('+${value} Residual payment'),{value: item.resid}));};
                item.text+='</ul>';
            } else if (item.cardType=='greenCard'){
                item.text=_("Grapes:");
                item.text+='<ul>';
                if (item.red>0){
                    item.text+=this.encapsulateTag('li',this.getGrapeSymbol('red',item.red,'','small')+' : '+dojo.string.substitute(_('Red Grape (${value})'),{value: item.red}));
                }
                if (item.white>0){
                    item.text+=this.encapsulateTag('li',this.getGrapeSymbol('white',item.white,'','small')+' : '+dojo.string.substitute(_('White Grape (${value})'),{value: item.white}));
                }
                item.text+='</ul>';
                if (item.irrigation>0 || item.trellis> 0){
                    item.text+=_('Requires:');
                    item.text+='<ul>';
                    if (item.irrigation>0){item.text+=this.encapsulateTag('li',this.getTokenSymbol('irrigation')+' : '+_('Irrigation'));}
                    if (item.trellis>0){item.text+=this.encapsulateTag('li',this.getTokenSymbol('trellis')+' : '+_('Trellis'));}
                    item.text+='</ul>';
                }
            } else if (item.cardType=='automaCard'){
                item.des1=this.getDescriptionWithTokens(item.des1, true,null,this.getThisPlayerId())||'';
                item.des2=this.getDescriptionWithTokens(item.des2, true,null,this.getThisPlayerId())||'';
                item.des3=this.getDescriptionWithTokens(item.des3, true,null,this.getThisPlayerId())||'';
                item.des4=this.getDescriptionWithTokens(item.des4, true,null,this.getThisPlayerId())||'';
                item.cls1='automaCardBar automaCardBar1 automaCardBarSea'+item.sea1+' automaCardBarSet'+item.set1;
                item.cls2='automaCardBar automaCardBar2 automaCardBarSea'+item.sea2+' automaCardBarSet'+item.set2;
                item.cls3='automaCardBar automaCardBar3 automaCardBarSea'+item.sea3+' automaCardBarSet'+item.set3;
                item.cls4='automaCardBar automaCardBar4 automaCardBarSea'+item.sea4+' automaCardBarSet'+item.set4;item.text+='</ul>';
            } else {
                item.text+=item.description;
            }

            var additionalTooltipInfos=this.getCardAdditionalTooltipInfos(cardId);
            if (additionalTooltipInfos){
                item.text+="<hr>"+additionalTooltipInfos;
            }

            if (item.name.length>24){
                item.cssClass += ' veryLongName';
            } else if (item.name.length>10){
                item.cssClass += ' longName';
            }

            if (item.cardType=='automaCard'){
                return this.format_block('jstpl_card_automa_tooltip', item );
            } else {
                return this.format_block('jstpl_card_tooltip', item );
            }
        },

        getCardAdditionalTooltipInfos(pCardKey){
            var additionalTooltipInfos = '';

            switch (Number(pCardKey)){
                //825: //Motivator
                //Each player may retrieve their grande worker. Gain ${token_vp1} for each opponent who does this.
                //**special**
                case 825:
                    additionalTooltipInfos = this.encapsulateTag('strong',_('Only active players (players who have not passed) can retrieve their grande worker.'));
                    break;
                
                case 807: //Uncertified Teacher
                    //Lose ${token_vp1} to train a ${token_worker} OR gain ${token_vp1} for each opponent who has a total of 6 ${token_worker}.
                    //**special**
                    additionalTooltipInfos = this.encapsulateTag('strong',_('The large worker and the temporary worker are also included in the count of workers'));
                    break;

                case 813: //Professor
                    //Pay ${token_lira2} to train 1 ${token_worker} OR gain ${token_vp2} if you have a total of 6 ${token_worker}.
                    //**special**
                    additionalTooltipInfos = this.encapsulateTag('strong',_('The large worker and the temporary worker are also included in the count of workers'));
                    break;

                case 633: //Organizer
                    //Move your ${token_rooster} piece to an empty row on the wake-up chart, take the bonus, then pass to the next season.
                    //**special**
                    additionalTooltipInfos = this.encapsulateTag('strong',_('Warning: this will be your last move in this season'));
                    
                    if (this.gamedatas_local.soloMode>0){
                        additionalTooltipInfos += '<br/>'+ this.encapsulateTag('strong',_('In solo mode you cannot move your rooster to row 7'));
                    }
                    break;

                case 628: //Homesteader
                    //Build 1 structure at a ${token_lira3} discount OR plant up to 2 ${token_greenCard}. You may lose ${token_vp1} to do both.
                    additionalTooltipInfos = this.encapsulateTag('strong',_('Warning: to plant a vine card you must have required structures and a field with enough space (you cannot exceed it\'s max value)'));
                    break;

                case 625: //Grower
                    //Plant 1 ${token_greenCard}. Then, if you have planted a total of at least 6 ${token_greenCard}, gain ${token_vp2}.
                    additionalTooltipInfos = this.encapsulateTag('strong',this.getDescriptionWithTokens(_('Warning: to gain  ${token_vp2} you must have planted at least 6 vine cards, the total number of grapes on cards does not count')));
                    break;
                    
                case 612: //Architect
                    //Build a structure at a ${token_lira3} discount OR gain ${token_vp1} for each ${token_lira4} structure you have built.
                    additionalTooltipInfos = this.encapsulateTag('strong',this.getDescriptionWithTokens(_('Warning: you gain ${token_vp1} only for structure built with cost equal to ${token_lira4}')));
                    break;
            }

            return additionalTooltipInfos;
        },

        show: function(elementId){
            dojo.removeClass( elementId, 'hidden' );
        },

        hide: function(elementId){
            dojo.addClass( elementId, 'hidden' );
        },

        setVisible(elementId, visible){
            if (visible){
                this.show(elementId);
            } else {
                this.hide(elementId);
            }
        },

        encapsulateTag: function(tag, content, cssClass){
            if (cssClass){
                return "<"+tag+" class=\""+cssClass+"\">"+content+"</"+tag+">";  
            }
            return "<"+tag+">"+content+"</"+tag+">";  
        },

        concatenateMultipleString: function(value, times){
            if (times<=0){
                return '';
            }
            var result='';
            for(var i=0;i<times;i++){
                result+=value;
            }
            return result;

        },

        addArrayElements: function(arrayToUpdate, arrayToBeAdded){
            if (!arrayToBeAdded || arrayToBeAdded.length==0){
                return arrayToUpdate;
            }
            for (var i=0;i<arrayToBeAdded.length;i++){
                arrayToUpdate.push(arrayToBeAdded[i]);
            }
            return arrayToUpdate;
        },

        arraySortByProperty: function(array, property, ascending){
            var sortOrder = 0;
            if (ascending==null || ascending == true){
                sortOrder = 1;
            } else {
                sortOrder = -1;
            }

            if (array==null||array.length==0){
                return array;
            }

            array.sort(function(a,b) {
                var result = (a[property] < b[property]) ? -1 : (a[property] > b[property]) ? 1 : 0;
                return result * sortOrder;
            });
        },

        /**
         * find array element
         * @param {} arrayToSearch
         * @param {*} value
         */
        arrayFind: function(arrayToSearch, value){
            if (arrayToSearch==null){
                return null;
            }
            for (var i=0;i<arrayToSearch.length;i++){
                if (arrayToSearch[i]==value){
                    return arrayToSearch[i];
                }
            }
            return null;
        },

        /**
         * find array element by property and value
         * @param {} arrayToSearch
         * @param {*} property
         * @param {*} value
         */
        arrayFindByProperty: function(arrayToSearch, property, value){
            if (arrayToSearch==null){
                return null;
            }
            for (var i=0;i<arrayToSearch.length;i++){
                if (arrayToSearch[i][property]==value){
                    return arrayToSearch[i];
                }
            }
            return null;
        },

        /**
         * find object element by property and value
         * @param {} objectToSearch
         * @param {*} property
         * @param {*} value
         */
        objectFindByProperty: function(objectToSearch, property, value){
            if (objectToSearch==null){
                return null;
            }
            for (var key in objectToSearch){
                if (objectToSearch[key][property]==value){
                    return objectToSearch[key];
                }
            }
            return null;
        },

        /**
         * push elements to array if not null or zero
         * @param {*} arrayToPush
         * @param {*} varargs
         */
        arrayPushElementsIfNotZero: function(arrayToPush, varargs){
            for (var i=1;i<arguments.length;i++){
                if (arguments[i] != null && arguments[i] != 0){
                    arrayToPush.push(arguments[i]);
                }
            }
            return arguments;
        },

        /**
         * function for string endsWith, not present in IE10,IE11
         */
        endsWith: function(str, suffix) {
            return str.indexOf(suffix, str.length - suffix.length) !== -1;
        },

        /**
         * returns value if number is negative, zero or positive
         * @param {*} value
         * @param {*} negativeResult
         * @param {*} zeroResult
         * @param {*} positiveResult
         */
        conditionNumberSign: function(value, negativeResult, zeroResult, positiveResult){
            if (value==0 || value == null){
                return zeroResult;
            }
            if (value < 0){
                return negativeResult;
            }
            return positiveResult;
        },

        queryAndDestroy: function(query, className){
            var queueEntries = dojo.query(query);
            for(var i=0; i<queueEntries.length; i++) {
               dojo.destroy( queueEntries[i] );
            }
        },

        queryAndRemoveClass: function(query, className){
            var queueEntries = dojo.query(query);
            for(var i=0; i<queueEntries.length; i++) {
               dojo.removeClass( queueEntries[i], className );
            }
        },

        queryAndRemoveElements: function(query){
            var queueEntries = dojo.query(query);
            for(var i=0; i<queueEntries.length; i++) {
               dojo.destroy( queueEntries[i]);
            }
        },

        queryAndDisconnectEvent: function(query, event){
            var queueEntries = dojo.query(query);
            for(var i=0; i<queueEntries.length; i++) {
               this.disconnect( queueEntries[i], event );
            }
        },

        queryAndAddEvent: function(query, event, callback){
            var queueEntries = dojo.query(query);
            for(var i=0; i<queueEntries.length; i++) {
               this.disconnect(queueEntries[i], event); //disconnect same event
               this.connect(queueEntries[i], event, callback);
            }
        },

        queryAndAddClass: function(query, className){
            var queueEntries = dojo.query(query);
            for(var i=0; i<queueEntries.length; i++) {
               dojo.addClass( queueEntries[i], className );
            }
        },

        queryAndSetAttribute: function(query, attribute, value){
            var queueEntries = dojo.query(query);
            for(var i=0; i<queueEntries.length; i++) {
               queueEntries[i].setAttribute(attribute, value);
            }
        },

        queryAndDisableInput: function(query, value){
            var queueEntries = dojo.query(query);
            for(var i=0; i<queueEntries.length; i++) {
               queueEntries[i].disabled = value;
            }
        },

        queryCount: function(query){
            var queueEntries = dojo.query(query);
            return queueEntries.length;
        },

        queryAndAddTooltip: function(query, tooltip){
            var queueEntries = dojo.query(query);
            for(var i=0; i<queueEntries.length; i++) {
               this.addTooltipHtml( queueEntries[i].id, tooltip );
            }
        },

        queryFirst: function(query){
            var queueEntries = dojo.query(query);
            return queueEntries[0];
        },

        bindData: function(object){
            //console.log("bindData");
            var queueEntries = dojo.query("*[data-binding]");
            for(var i=0; i<queueEntries.length; i++) {
                var context = object;
                var js = queueEntries[i].getAttribute("data-binding");
                var _me = this;
                try{
                    var value = function() { var me=_me; var b=this; return eval(js); }.call(context);
                    if (queueEntries[i].innerHTML !== value){
                        dojo.removeClass(queueEntries[i],"change_value");
                        dojo.addClass(queueEntries[i],"change_value");
                        queueEntries[i].innerHTML = value;
                    }
                } catch(error){
                    //nothing
                }
            }
        },

        setClientStateAction : function(stateName, desc) {
            var args = dojo.clone(this.gamedatas.gamestate.args);
            if (args==null){
                args = {};
            }

            if (this.clientStateArgs && this.clientStateArgs.previewTokens && this.clientStateArgs.previewTokens.worker){
                if (this.clientStateArgs.previewTokens.worker.t == 'worker_g'){
                    desc+=' '+dojo.string.substitute(_('(Using grande ${token_worker_g})'),{
                        token_worker_g: this.getTokenPlayerSymbol(this.getThisPlayerId(),'worker_g')
                    });
                }
            }

            args.actname = this.getTr(this.clientStateArgs.action);
            this.setClientState(stateName, {
                descriptionmyturn : this.getTr(desc),
                args : args
            });
        },

        clearInteractiveItems: function(dontClearHandZone, dontClearPreviewToken){

            this.queryAndDisconnectEvent('#board .active_slot','click');
            this.queryAndRemoveClass('#board .active_slot','active_slot');
            this.queryAndRemoveClass('#board .selected','selected');

            this.queryAndDisconnectEvent('.playerboard .active_slot','click');
            this.queryAndRemoveClass('.playerboard .active_slot','active_slot');
            this.queryAndRemoveClass('.playerboard .selected','selected');

            if (dontClearHandZone){
            } else {
                this.handZone.setSelectionMode(0);
                this.handZone.unselectAll();
                var playerId = this.getThisPlayerId();
                var playerHand = $('playerboard_hand_zone_'+playerId);
                if (playerHand){
                    playerHand.setAttribute('data-selectiontype','');
                }
                this.queryAndRemoveClass('.playerboard_hand_zone .disabled','disabled');
                this.queryAndRemoveClass('.playerboard_hand_zone.stock_confirm_selection','stock_confirm_selection');
            }

            if (dontClearPreviewToken){
            } else {
                this.clientStateArgs.previewTokens = {};
                this.updatePreviewTokens();
            }

            this.updatePlayCardFromGameDatas();

        },

        updatePlayCardFromGameDatas: function(){
            this.clientStateArgs.playCard = null;
            this.clientStateArgs.playCardPlayerId = null;
            if (this.gamedatas_local.actionProgress){
                this.clientStateArgs.playCard = this.gamedatas_local.actionProgress.card_key;
                var player = this.objectFindByProperty(this.gamedatas_local.players,'card_played', this.clientStateArgs.playCard);
                if (player){
                    this.clientStateArgs.playCardPlayerId = player.id;
                } else {
                    this.clientStateArgs.playCardPlayerId = this.gamedatas_local.actionProgress.player_id;
                }

            }
        },

        getTr : function(name) {
            if (typeof name.log != 'undefined') {
                name = this.format_string_recursive(name.log, name.args);
            } else {
                name = this.clienttranslate_string(name);
            }
            return name;
        },

        /** More convenient version of ajaxcall, do not to specify game name, and any of the handlers */
        ajaxAction : function(action, args, func, err, lock) {
            // console.log("ajax action " + action);
            if (!args) {
                args = [];
            }
            // console.log(args);
            delete args.action;
            if (!args.hasOwnProperty('lock') || args.lock) {
                args.lock = true;
            } else {
                delete args.lock;
            }
            if (typeof func == "undefined" || func == null) {
                var self = this;
                func = function(result) {
                    // console.log('server ack');
                };
            }
            // restore server server if error happened
            if (typeof err == "undefined") {
                var self = this;
                err = function(iserr, message) {
                    if (iserr) {
                        console.log('restoring server state, error: ' + message);
                        self.cancelLocalStateEffects();
                    }
                };
            }
            var name = this.game_name;
            if (this.checkAction(action)) {
                // args.lock = true;
                this.ajaxcall("/" + name + "/" + name + "/" + action + ".html", args,//
                this, func, err);
            }
        },

        ajaxClientStateHandler : function(event) {
            dojo.stopEvent(event);
            // this.boardMarkup(this.gamedatas.gamestate.args);
            this.ajaxClientStateAction();
        },

        ajaxClientStateAction : function(action) {
            if (typeof action == 'undefined') {
                action = this.clientStateArgs.action;
            }
            if (this.clientStateArgs.handler) {
                var handler = this.clientStateArgs.handler;
                delete this.clientStateArgs.handler;
                handler();
                return;
            }
            console.log("sending " + action);
            this.ajaxAction(action, this.clientStateArgs);
        },

        cancelLocalStateEffects : function() {

            this.clearInteractiveItems();

            if (this.on_client_state) {
                this.clientStateArgs = {
                    action : 'none',
                };
                this.gamedatas_local = dojo.clone(this.gamedatas);
                if (this.restoreList) {
                    var restoreList = this.restoreList;
                    this.restoreList = [];
                    for (var i = 0; i < restoreList.length; i++) {
                        //var token = restoreList[i];
                        //var tokenInfo = this.gamedatas.tokens[token];
                        //this.placeTokenWithTips(token, tokenInfo, true);
                    }
                }
            }
            //workaround for problem restoreServerGameState and error calculating reflexion times...
            try{
                if (this.last_server_state && this.last_server_state && this.last_server_state.reflexion && !this.last_server_state.reflexion.initial_ts){
                    this.last_server_state.reflexion.initial_ts = dojo.clone(this.gamedatas.gamestate.reflexion.initial_ts);
                }
                if (this.last_server_state && this.last_server_state && this.last_server_state.reflexion && !this.last_server_state.reflexion.initial){
                    this.last_server_state.reflexion.initial = dojo.clone(this.gamedatas.gamestate.reflexion.initial);
                }
            } catch(err){
                //nothing
            }
            this.restoreServerGameState();
        },

        randomInt : function(minimum, maximum){
            return Math.floor(Math.random() * (maximum-minimum)) + minimum;
        },

        getPlayerData: function(playerId){
            return this.gamedatas_local.players[playerId];
        },

        getPlayerNameWithColor: function(playerId){
            var player = this.getPlayerData(playerId);
            var playerData = {
                player_color: player.player_color,
                background_color: 'transparent',
                player_name: player.player_name
            };
            if (playerData.player_color=='ffffff'){
                playerData.background_color = '#bbbbbb';
            }
            return this.format_block( 'jstpl_player_name_with_color', playerData);
        },
        
        getWorkers: function(playerId){
            var workers = [];
            for (var i=0;i<this.gamedatas_local.tokens[playerId].length;i++){
                var token = this.gamedatas_local.tokens[playerId][i];
                if (token.l!='playerOff' && token.t.indexOf('worker')==0){
                    workers.push(token);
                }
            }
            return workers;
        },

        getAvailableWorkers: function(playerId){
            var workers = [];
            for (var i=0;i<this.gamedatas_local.tokens[playerId].length;i++){
                var token = this.gamedatas_local.tokens[playerId][i];
                if (token.l=='player' && token.t.indexOf('worker')==0){
                    workers.push(token);
                }
            }
            return workers;
        },

        checkIfAvailableGrandeWorker: function(playerId){
            var availableWorkers = this.getAvailableWorkers(playerId);
            var availableGrande = false;
            if (this.arrayFindByProperty(availableWorkers,'t','worker_g')!=null){
                availableGrande = true;
            }
            return availableGrande;
        },

        getAvailableNewWorkers: function(playerId){
            var workers = [];
            for (var i=0;i<this.gamedatas_local.tokens[playerId].length;i++){
                var token = this.gamedatas_local.tokens[playerId][i];
                if (token.l=='playerOff' && token.t.indexOf('worker')==0){
                    workers.push(token);
                }
            }
            return workers;
        },

        isPlayerLocationOccupied: function(playerId, location){
            var occupied=false;
            for (var i=0;i<this.gamedatas_local.tokens[playerId].length;i++){
                var token = this.gamedatas_local.tokens[playerId][i];
                if (token.l=='board_'+location && token.t.indexOf('worker')==0){
                    occupied=true;
                    break;
                }
            }
            return occupied;
        },
        
        isLocationOccupied: function(location){
            var occupied=false;
            for (var playerId in this.gamedatas_local.tokens){
                for (var i=0;i<this.gamedatas_local.tokens[playerId].length;i++){
                    var token = this.gamedatas_local.tokens[playerId][i];
                    if (token.l=='board_'+location && token.t.indexOf('worker')==0){
                        occupied=true;
                        break;
                    }
                }
            }
            return occupied;
        },

        getState : function() {
            return this.gamedatas.gamestate.name;
        },

        sendEventClick: function(target){
            var event = new Event("click");
            event.currentTarget = target;
            target.dispatchEvent(event );
        },

        /**
         * extracts from object only properties not equal to zero
         */
        propertiesNotZero: function(pObject){
            var result = {};
            for (var key in pObject){
                if (pObject[key]!==0){
                    result[key] = pObject[key];
                }
            }
            return result;
        },

        /**
         * function wrapper for this.player_id,
         * it checks if spectator
         */
        getThisPlayerId: function(){
            if (!this.isSpectator){
                return this.player_id;
            }
            if (!this.vitFirstPlayerId){
                this.vitFirstPlayerId = this.getPlayerIdOrdered()[0];
            }
            return this.vitFirstPlayerId;
        },

        /**
         * returns current player color
         */
        getThisPlayerColor: function(){
           return this.gamedatas_local.players[this.getThisPlayerId()].player_color;
        },

        applyGameDatas: function( gamedatas){
            console.log('applyGameDatas');
            //console.log(JSON.stringify(gamedatas).length);
            //console.log(JSON.stringify(gamedatas.map));
            //console.log(JSON.stringify(gamedatas.map[0]));
            //console.log(JSON.stringify(gamedatas.players[Object.keys(gamedatas.players)[0]]));
            //for (key in gamedatas){
            //    console.log(key+":"+JSON.stringify(gamedatas[key]));
            //}
            if (!this.isEmpty(gamedatas)){
                var soloMode = gamedatas.soloMode;
                if (soloMode==null && this.gamedatas_local){
                    soloMode = this.gamedatas_local.soloMode;
                }
                for (var key in gamedatas){
                    if (key=='_private'){
                        for (var privateKey in gamedatas._private){
                            this.gamedatas_local[privateKey] = gamedatas._private[privateKey];
                        }
                    } else {
                        if (key=='players' && soloMode > 0){
                            this.gamedatas_local[key] = this.addAutomaPlayer(gamedatas[key], gamedatas.automaPlayerData);
                        } else {
                            this.gamedatas_local[key] = gamedatas[key];
                        }
                    }
                }
                this.gamedatas_local.map = this.decompressMap(this.gamedatas_local.map);
                //console.log('applyGameDatas'); //DEBUG
                //console.log(this.gamedatas_local); //DEBUG

                this.updatePlayerSummaries();

                if (this.gamedatas_local.players){
                    //update scoring
                    for (var player_id in this.gamedatas_local.players){
                        var playerData = this.getPlayerData(player_id);
                        if (this.scoreCtrl[ player_id ]){
                            this.scoreCtrl[ player_id ].toValue( playerData.score );
                        }
                    }
                }

                //DEBUG:
                //this.debugDumpDeck();
                this.updatePlayCardFromGameDatas();

                this.updateTurn(true);
                this.updateTokens(true);
                this.updateDecks(true);
                this.updatePlayerFlags(true);
                this.updateVines(true);
                this.updateGrapesWines(true);
                this.updateMamasPapas(true);
                this.updateScoreAndResidualPayment(true);
                this.updateHand(true);
                this.updateAutomaCards(true);

                this.bindData(this.gamedatas_local);
            }
        },

        addAutomaPlayer: function(players, automaPlayerData){
            var players = dojo.clone(players);
            players[automaPlayerData.id] = automaPlayerData;

            return players;
        },

        updatePlayerSummaries: function(){
            //update scoring
            for (var player_id in this.gamedatas_local.players){
                var playerData = this.getPlayerData(player_id);
                var handCardsSummary = '';
                for (var i=0;i<Number(playerData.greenCard);i++){
                    handCardsSummary+=this.getTokenSymbol('greenCard');
                }
                for (var i=0;i<Number(playerData.yellowCard);i++){
                    handCardsSummary+=this.getTokenSymbol('yellowCard');
                }
                for (var i=0;i<Number(playerData.purpleCard);i++){
                    handCardsSummary+=this.getTokenSymbol('purpleCard');
                }
                for (var i=0;i<Number(playerData.blueCard);i++){
                    handCardsSummary+=this.getTokenSymbol('blueCard');
                }
                if (handCardsSummary==''){
                    handCardsSummary=_('No cards');
                }
                playerData.handCardsSummary=handCardsSummary;

                var workersSummary='';
                var workers = this.getWorkers(player_id);
                var availableWorkers = this.getAvailableWorkers(player_id);
                workersSummary = availableWorkers.length+'/'+workers.length+': ';
                for (var i=0;i<availableWorkers.length;i++){
                    workersSummary+=this.getTokenPlayerSymbol(player_id, availableWorkers[i].t);
                }
                if (player_id != this.SOLO_PLAYER_ID){
                    if (availableWorkers.length==0){
                        if (playerData.pass==0){
                            workersSummary+=_('No more workers');                        
                        } else {
                            workersSummary+=_('Passed');                        
                        }
                    } else {
                        if (playerData.pass==1){
                            workersSummary+=' ('+_('Passed')+')';                        
                        }
                    }
                }
       
                playerData.workersSummary=workersSummary;

                var playerBoard = dojo.byId("overall_player_board_" + player_id);
                if (playerBoard) {
                    if (playerData.pass==0) {
                        dojo.removeClass(playerBoard, "vit_passed");
                    }
                    else {
                        dojo.addClass(playerBoard, "vit_passed");
                    }
                }
                playerBoard = dojo.byId("playerboard_row_" + player_id);
                if (playerBoard) {
                    if (playerData.pass==0) {
                        dojo.removeClass(playerBoard, "vit_passed");
                    }
                    else {
                        dojo.addClass(playerBoard, "vit_passed");
                    }
                }
            }
        },

        updateTurn: function(){
            this.gamedatas_local.seasonTr = this.getSeasonDescription(Number(this.gamedatas_local.season));
        },

        getSeasonDescription: function(seasonNumber){
            switch (Number(seasonNumber)) {
                case 1:
                    return _('Spring');

                case 2:
                    return _('Summer');

                case 3:
                    return _('Fall');

                case 4:
                    return _('Winter');

                default:
                    return seasonNumber;
                    
            }
        },

        //DEBUG: dump deck
        /*debugDumpDeck: function(){
            if (this.gamedatas_local._deck && this.gamedatas_local._deck.length>0){
                dojo.place(this.dumpArrayToHtmlTable(this.gamedatas_local._deck,'_deck'),'_deck','only');
            }
        },*/

        dumpArrayToHtmlTable: function(data, cssClass){
            var html='<table class="'+cssClass+'"><tr>';
            for (var key in data[0]){
                html+="<th>"+key+"</th>";
            }
            html += "</tr>";
            for (var i=0;i<data.length;i++){
                html+="<tr>";
                for (var key in data[i]){
                    html+="<td>"+data[i][key]+"</td>";
                }
                html+="</tr>";
            }
            html += "</table>";
            return html;
        },

        calculateTokenId: function(playerId, type, type_arg){
            if (type == 'worker_t'){
                return  'token_'+type;
            }
            if (type == 'wakeup_bonus'){
                return  'token_'+type+'_'+type_arg;
            }
            return 'token_'+playerId+'_'+type;
        },

        calculateWinePos: function(wineType, wineValue){
            var pos = {
                x:0,
                y:0
            };
            var winePos = this.wines[wineType];
            pos.x = winePos.x+winePos.dx*(wineValue-1);
            if (wineValue>3){
                pos.x+=winePos.dxx;
            }
            if (wineValue>6){
                pos.x+=winePos.dxx;
            }
            pos.y = winePos.y;
            return pos;
        },

        calculateTokenPos: function(playerId, elementId, type, location, progr, tokens){
            var pos = {};

            //workaround for
            if (location=='board_0_new'){
                location = 'board_352_new';
            }

            var locationTokens = location.split('_');
            var locationType = locationTokens[0];
            var locationProg = 0;
            var locationFlags = '';

            if (locationTokens.length>1){
                locationProg = Number(locationTokens[1]);
            }
            if (locationTokens.length>2){
                locationFlags = locationTokens[2];
            }

            if (locationType == 'board'){
                var posLocation = this.calculateBoardLocationPos(playerId, location, locationProg, locationFlags, type, tokens, progr);
                pos.target = posLocation.target;
                pos.x = posLocation.x;
                pos.y = posLocation.y;
                pos.zIndex = posLocation.zIndex;
            } else if (locationType == 'playerOff'){
                pos.target = 'playerboard_row_header_'+playerId;
                var othersCount = 0;
                var others = dojo.query('#'+pos.target+' .component.token');
                for (var i=0;i<others.length;i++){
                    if (others[i].id<elementId){
                        othersCount++;
                    }
                }
                var posLocation = this.calculatePlayerOffBoardLocationPos(playerId, type, othersCount, tokens);
                pos.x = posLocation.x;
                pos.y = posLocation.y;
                pos.zIndex = posLocation.zIndex;
            } else if (locationType == 'player'){
                pos.target = 'playerboard_'+playerId;
                var posLocation = this.calculatePlayerBoardLocationPos(playerId, type, tokens, progr);
                pos.x = posLocation.x;
                pos.y = posLocation.y;
                pos.zIndex = posLocation.zIndex;
            }

            return pos;
        },

        calculateBoardLocationPos: function(playerId, tokenLocation, locationProg, locationFlags, type, tokens, typeArg){
            var pos = {
                x: 0,
                y: 0,
                target: 'board'
            };
            
            var actionSlotType = '';
            var actionSlot = '';
            if (type=='rooster'){
                actionSlot = 'wakeupOrder_slot_'+locationProg;
                pos.target = actionSlot;
                if (this.gamedatas_local.season < 2
                    || (this.gamedatas_local.season == 2 && this.gamedatas_local.players[playerId].pass==0)
                    || (this.gamedatas_local.season == 4 && this.gamedatas_local.players[playerId].pass==1)){
                    //normal
                    pos.x = -10;
                } else {
                    //on the right
                    pos.x = 30;
                }
            } else if (type=='wakeup_bonus'){
                actionSlot = 'wakeupOrder_slot_'+typeArg;
                pos.target = actionSlot;
                pos.x = -35;
            } else if (type=='worker_t' && locationProg == 0){
                actionSlot = 'wakeupOrder_slot_'+7;
                pos.x = 25;
                pos.target = actionSlot;
            } else if (locationProg == 901){
                //901 (yoke) on player board
                actionSlot = 'action_slot_'+locationProg+'_'+playerId;
                pos.target = actionSlot;
            } else {
                actionSlot = 'action_slot_'+locationProg;
                var actionSlotElement = $(actionSlot);
                if (!actionSlotElement){
                    actionSlot =  'shared_location_'+locationProg;
                }
                pos.target = actionSlot;
                pos.y = -13;

                var others = 0;
                var total = 0;
                var locationTokens = tokenLocation.split('_');
                for (var owner in tokens){
                    for (var i=0;i<tokens[owner].length;i++){
                        var otherToken = tokens[owner][i];
                        var otherLocationTokens = otherToken.l.split('_');
                        if (otherLocationTokens[1]==locationTokens[1]){
                            total++;
                            if (otherToken.t+'_'+owner < type+'_'+playerId){
                                others++;
                            }
                        }
                    }
                }
                //worker 'displacement' if more than one worker in location
                if (this.sharedLocations[0][Number(locationProg)]){
                    //shared location, horizontal
                    pos.zIndex = 15;
                    if (total>0){
                        pos.zIndex = 15+others ;
                        pos.x = pos.x - (total)*12 + others*24+12;
                    }
                } else {
                    //action location, from the second: below and horizontally
                    pos.zIndex = 15;
                    if (others>0){
                        pos.y += 20;
                        pos.zIndex = 15+others ;
                    }
                    if (others>0 && total>2){
                        pos.x = pos.x - (total)*12 + others*24;
                    }
                }
            }

            if (actionSlot){
                var actionSlotElement = $(actionSlot);
                var actionSlotType = actionSlotElement.getAttribute('data-type');
                if (actionSlotType && this.actionSlots[this.gamedatas_local.set][actionSlotType]){
                    pos.x += this.actionSlots[this.gamedatas_local.set][actionSlotType].offsetX||0;
                    pos.y += this.actionSlots[this.gamedatas_local.set][actionSlotType].offsetY||0;
                }
            }
            return pos;
        },

        calculatePlayerOffBoardLocationPos: function(playerId, type, progr, tokens){
            var playerBoardId = 'playerboard_row_header_'+playerId;
            var position = dojo.position(playerBoardId);
            var y = 0;
            var x = 10-(position.w-1200)/2;
            var vw = Math.max(document.documentElement.clientWidth || 0, window.innerWidth || 0);
            if (playerId==this.getThisPlayerId() || vw<2240){
                x+=progr*31;
            } else {
                y+=32+progr*31;
            }
            var pos = {
                x: x,
                y: y
            };
            return pos;
        },

        calculatePlayerBoardLocationPos: function(playerId, type, tokens, typeArg){
            var pos = {
                x: 0,
                y: 0
            };
            if (type.indexOf('worker')==0){
                var others = 0;
                var total = 0;
                for (var i=0;i<tokens[playerId].length;i++){
                    var otherToken = tokens[playerId][i];
                    if (otherToken.l=='player' && (otherToken.t.indexOf('worker')==0 && otherToken.t<type)){
                        others++;
                    }
                    if (otherToken.l=='player' && (otherToken.t.indexOf('worker')==0)){
                        total++;
                    }
                }
                pos.x = this.playerBoard.worker.x + this.playerBoard.worker.dx * others;
                pos.y = this.playerBoard.worker.y;
                if (total>5){
                    pos.x-=this.playerBoard.worker.dx/2;
                }
                if (total>6){
                    pos.x-=this.playerBoard.worker.dx/2;
                }
            } else if (type.indexOf('wakeup_bonus')==0){
                var others = 0;
                var total = 0;
                for (var i=0;i<tokens[playerId].length;i++){
                    var otherToken = tokens[playerId][i];
                    if (otherToken.l=='player' && otherToken.t=='wakeup_bonus'){
                        total++;
                    }
                    if (otherToken.l=='player' && otherToken.t=='wakeup_bonus' && otherToken.a<typeArg){
                        others++;
                    }
                }
                pos.x = this.playerBoard.wakeup_bonus.x + this.playerBoard.wakeup_bonus.dx * others;
                pos.y = this.playerBoard.wakeup_bonus.y + this.playerBoard.wakeup_bonus.dy * others;
            } else {
                pos.x = this.playerBoard[type].x;
                pos.y = this.playerBoard[type].y;
                if (this.playerBoard[type].offx){
                    pos.x+=this.playerBoard[type].offx;
                }
                if (this.playerBoard[type].offy){
                    pos.y+=this.playerBoard[type].offy;
                }
            }
            return pos;
        },

        updateTokens: function(animate){

            for (var playerId in this.gamedatas_local.tokens){
                var tokensPlayer = this.gamedatas_local.tokens[playerId];

                for (var i=0;i<tokensPlayer.length;i++){
                    var tokenElementId = this.calculateTokenId(playerId, tokensPlayer[i].t, tokensPlayer[i].i);

                    //if wakeup bonus, and not exists, create it
                    if (!$(tokenElementId) && tokensPlayer[i].t=='wakeup_bonus'){
                        var wakeupBonusItem = {
                            elementId: tokenElementId,
                            cssClass: '',
                            type: 'wakeup_bonus',
                            position: 'position:absolute;',
                            style: '',
                            arg: 0,
                            x: 0,
                            y: 0,
                            id: tokensPlayer[i].i,
                            tooltip: this.getTokenDescription('wakeup_bonus')
                        };
                        var wakeupBonusElement = dojo.place( this.format_block('jstpl_token', wakeupBonusItem), 'board', 'last' );
                        this.placeOnObjectPos( wakeupBonusElement, 'board', 0,0, 1500); 
                    }
                    //update token
                    if ($(tokenElementId)){
                        if (tokensPlayer[i].l=='discard' || tokensPlayer[i].l=='token_discard'){
                            dojo.destroy(tokenElementId);
                        } else {
                            var pos = this.calculateTokenPos(playerId, tokenElementId, tokensPlayer[i].t, tokensPlayer[i].l, tokensPlayer[i].a, this.gamedatas_local.tokens);
                            if (pos.zIndex){
                                this.setElementZIndex(tokenElementId, pos.zIndex);
                            }
                            this.moveObject( tokenElementId, pos.target, pos.x, pos.y, animate, 1500, 0, true);
                        }
                    }
                } 
            }
        },

        updateDecks: function(animate, origin){
            //count decks cards
            for (var deck in this.gamedatas_local.cdc){
                if (deck.indexOf('discard')==-1 && this.countDecks[this.gamedatas_local.set][deck]){
                    var elementBackId = 'deck_back_card_'+deck;
                    if (!$(elementBackId)){
                        var cardDeck = this.cardDecks[this.gamedatas_local.set][deck];
                        var item = {};
                        item.elementId=elementBackId;
                        item.cssClass="card_back shadow small "+cardDeck.type;
                        item.topCssClass="card_back";
                        item.cardType=cardDeck.type;
                        item.location='card_back';
                        item.type=cardDeck.type;
                        item.id=0;
                        item.name='';
                        item.description='';
                        item.position="position:absolute;";
                        item.x=0
                        item.y='';
                        item.style='';
                        dojo.place( this.format_block( 'jstpl_card', item), 'board', 'last' );
                        this.moveObject( elementBackId, 'board', cardDeck.x, cardDeck.y, false, 0,0, true);
                    }

                    var count = this.gamedatas_local.cdc[deck];
                    
                    var elementId = 'deck_count_'+deck;
                    if ($(elementId)){
                        $(elementId).innerHTML = count;
                    } else {
                        var item = {};
                        item.elementId=elementId;
                        item.cssClass = 'deck_count deck_count_'+deck;
                        item.count=count;
                        item.position="position:absolute;";
                        dojo.place( this.format_block( 'jstpl_deck_count', item), 'board', 'last' );
                        this.moveObject( elementId, 'board', this.countDecks[this.gamedatas_local.set][deck].x, this.countDecks[this.gamedatas_local.set][deck].y, false, 0,0, true);
                    }
                } else {
                    var discardDeck = this.objectFindByProperty(this.discardDecks[this.gamedatas_local.set],'key',deck);
                    if (discardDeck){
                        var elementId = 'discardCard_slot_'+deck;
                        if (!$(elementId)){
                            var item = {
                                elementId: elementId,
                                cssClass: 'discardCard_slot',
                                type: 'discardCard_slot',
                                position: 'position:absolute;',
                                style: '',
                                arg: deck,
                                x: 0,
                                y: 0,
                                phase: 1,
                                action: '',
                                label: ''
                            };
                            dojo.place( this.format_block( 'jstpl_action_slot', item), 'board', 'last' );
                            this.moveObject( elementId, 'board', discardDeck.x, discardDeck.y, false, 0,0, true);
                        }
                    }
                }
            }

            //discard cards
            this.queryAndSetAttribute('#board .card_discard[data-location=discard]','data-destroy','1');
            for (var deck in this.gamedatas_local.tdd){
                if (this.discardDecks[this.gamedatas_local.set][deck]){
                    var card = this.gamedatas_local.tdd[deck];
                    var elementId = 'discard_'+card.i;
                    if ($(elementId)){
                        $(elementId).removeAttribute('data-destroy');
                    } else {
                        var item = this.cloneCard(card.k);
                        item.elementId=elementId;
                        item.cssClass="discard shadow small "+item.type;
                        item.topCssClass="card_discard";
                        item.cardType=item.type;
                        item.location='discard';
                        item.type=card.k;
                        item.id=card.i;
                        item.name=item.name||'';
                        item.description=this.getDescriptionWithTokens(item.description, true)||'';
                        item.position="position:absolute;";
                        item.x=card.k;
                        item.y='';
                        item.style='';
                        if (item.name.length>24){
                            item.cssClass += ' veryLongName';
                        } else if (item.name.length>10){
                            item.cssClass += ' longName';
                        }
                        dojo.place( this.format_block( 'jstpl_card', item), 'board', 'last' );
                        this.addTooltipHtml( item.elementId, this.getTooltipHtmlCard(card.k));
                    }
                    if (animate && origin=='hand'){
                        var originId = 'side_player_handCards_'+playerData.player_color;
                        if ($('playerboard_hand_zone_'+playerId)){
                            originId = 'playerboard_hand_zone_'+playerId;
                            if ($('playerboard_hand_zone_'+playerId+'_item_'+card.i)){
                                originId = 'playerboard_hand_zone_'+playerId+'_item_'+card.i;
                            }
                        }
                        this.moveObject( elementId, originId, 0,0, false, 0,0, false);
                        this.moveObject( elementId, 'board', this.discardDecks[this.gamedatas_local.set][deck].x, this.discardDecks[this.gamedatas_local.set][deck].y, true, 1500,0, true);
                    } else {
                        this.moveObject( elementId, 'board', this.discardDecks[this.gamedatas_local.set][deck].x, this.discardDecks[this.gamedatas_local.set][deck].y, false, 0,0, true);
                    }
                }
            }

            //remove cards
            this.queryAndDestroy('#board .card_discard[data-location=discard][data-destroy=1]');

        },

        updatePlayerFlags: function(animate){
            for (var playerId in this.gamedatas_local.players){
                var playerBoardId = 'playerboard_'+playerId;
                var playerData = this.gamedatas_local.players[playerId];
                var structures = {tastingRoom: 'tastingRoomUsed', windmill: 'windmillUsed'};
                for (var structure in structures){
                    //Used
                    var usedElementId = 'used_'+structures[structure]+'_'+playerId;
                    if (playerData[structures[structure]]==1){
                        //used tastingRoom exists?
                        if (!$(usedElementId)){
                            //used token
                            var item = {
                                elementId: usedElementId,
                                cssClass: 'used used_'+structures[structure],
                                type: 'used',
                                position: 'position:absolute;',
                                style: '',
                                arg: structure,
                                x: 0,
                                y: 0,
                                id: usedElementId,
                                tooltip: _('Used in this year')
                            };
                            var element = dojo.place( this.format_block('jstpl_token', item), playerBoardId, 'last' );
                            $(usedElementId).innerHTML = _('Used');
                            this.moveObject( usedElementId, playerBoardId, this.playerBoard[structure].x,  this.playerBoard[structure].y, false, 0, 0, true);
                            if (animate){
                                dojo.style(element, "opacity", "0");
                                dojo.fadeIn({
                                    node: element,
                                    duration: 1500
                                }).play();
                            }
                        }
                    } else {
                        //remove used
                        if ($(usedElementId)){
                            this.fadeOutAndDestroy(usedElementId, 1500, 0);
                        }
                    }
                }
            }
        },

        updateVines: function(animate){
            for (var playerId in this.gamedatas_local.players){
                this.updateVinesPlayer(playerId, animate, null, null);
            }
        },

        updateVinesPlayer: function(playerId, animate, origin, destination){
            var playerData = this.gamedatas_local.players[playerId];
            for (var i in this.gamedatas_local.fields){
                var field = this.gamedatas_local.fields[i];
                var vines = this.gamedatas_local.players[playerId][field.location];
                var fieldElementId = 'field_slot_'+playerId+'_'+i;

                //Sold
                if (playerData[field.dbField]==0){
                    //sold exists?
                    var elementId = fieldElementId+'_sold';
                    if (!$(elementId)){
                        //sold token
                        var item = {
                            elementId: fieldElementId+'_sold',
                            cssClass: 'sold',
                            type: 'sold',
                            position: 'position:absolute;',
                            style: '',
                            arg: i,
                            x: 0,
                            y: 0,
                            id: fieldElementId+'_sold',
                            tooltip: ''//this.getTokenDescription('sold')
                        };
                        var element = dojo.place( this.format_block('jstpl_token', item), fieldElementId, 'last' );
                        this.moveObject( fieldElementId+'_sold', fieldElementId, 0,0, false, 0,0, true);
                        this.addTooltipHtml( item.elementId, dojo.string.substitute(_('Field sold for ${token_lira}, you can rebuy it for ${token_lira}'),{token_lira:this.getTokenSymbol('lira'+field.price)}));
                        if (animate){
                            dojo.style(element, "opacity", "0");
                            dojo.fadeIn({
                                node: element,
                                duration: 1500
                            }).play();
                        }
                    }
                } else {
                    //remove sold
                    if ($(fieldElementId+'_sold')){
                        this.fadeOutAndDestroy(fieldElementId+'_sold', 1500, 0);
                    }
                }

                //Harvested
                if (playerData[field.dbField]==2){
                    //harvested exists?
                    var elementId = fieldElementId+'_harvested';
                    if (!$(elementId)){
                        //harvested token
                        var item = {
                            elementId: elementId,
                            cssClass: 'harvested',
                            type: 'harvested',
                            position: 'position:absolute;',
                            style: '',
                            arg: i,
                            x: 0,
                            y: 0,
                            id: fieldElementId+'_harvested',
                            tooltip: _('Field harvested this year')
                        };
                        var element = dojo.place( this.format_block('jstpl_token', item), fieldElementId, 'last' );
                        $(elementId).innerHTML=_('Harvested');
                        this.moveObject( fieldElementId+'_harvested', fieldElementId, 0,0, false, 0,0, true);
                        if (animate){
                            dojo.style(element, "opacity", "0");
                            dojo.fadeIn({
                                node: element,
                                duration: 1500
                            }).play();
                        }
                    }
                } else {
                    //remove harvested
                    if ($(fieldElementId+'_harvested')){
                        this.fadeOutAndDestroy(fieldElementId+'_harvested', 1500, 0);
                    }
                }

                //vines
                this.queryAndSetAttribute('#'+fieldElementId+' .vine','data-destroy','1');
                for (var j=0;j<vines.length;j++){
                    var elementId = 'vine_'+vines[j].i;
                    if ($(elementId)){
                        $(elementId).removeAttribute('data-destroy');
                    } else {
                        var newVine=true;
                        var item = this.cloneCard(vines[j].k);
                        item.elementId=elementId;
                        item.cssClass="shadow small ";
                        if (item.name.length>16){
                            item.cssClass+="veryLongName";
                        } else if (item.name.length>11){
                            item.cssClass+="longName";
                        }
                        item.location='vine';
                        item.cardType=item.type;
                        item.type=vines[j].k;
                        item.id=vines[j].i;
                        item.name=item.name||'';
                        item.description=item.description||'';
                        item.position="position:absolute;z-index:"+(20-j)+";";
                        item.x=vines[j].k;
                        item.y='';
                        item.style='';
                        if (item.white>0){
                            item.white=this.getGrapeSymbol('white', item.white,'','small');
                        } else {
                            item.white='';
                        }
                        if (item.red>0){
                            item.red=this.getGrapeSymbol('red', item.red,'','small');
                        } else {
                            item.red='';
                        }
                        dojo.place( this.format_block( 'jstpl_card_vine', item), fieldElementId, 'last' );
                        this.addTooltipHtml( item.elementId, this.getTooltipHtmlCard(vines[j].k));
                        if (animate && origin=='hand'){
                            var originId = 'side_player_handCards_'+playerData.player_color;
                            if ($('playerboard_hand_zone_'+playerId)){
                                originId = 'playerboard_hand_zone_'+playerId;
                                if ($('playerboard_hand_zone_'+playerId+'_item_'+vines[j].i)){
                                    originId = 'playerboard_hand_zone_'+playerId+'_item_'+vines[j].i;
                                }
                            }
                            this.moveObject( elementId, originId, 0,0, false, 0,0, false);
                        }
                    }

                    this.moveObject( elementId, fieldElementId, this.playerBoard.vines.x, this.playerBoard.vines.y+this.playerBoard.vines.dy*j, animate, 1500,0, true);
                }
            }

            for (var i in this.gamedatas_local.fields){
                var field = this.gamedatas_local.fields[i];
                var vines = this.gamedatas_local.players[playerId][field.location];
                var fieldElementId = 'field_slot_'+playerId+'_'+i;
                //remove vines
                if (animate && destination=='hand'){
                    var destinationId = 'side_player_handCards_'+playerData.player_color;
                    if ($('playerboard_hand_zone_'+playerId)){
                        destinationId = 'playerboard_hand_zone_'+playerId;
                    }
                    var vinesToDestroy = dojo.query('#'+fieldElementId+' .vine[data-destroy=1]');
                    for (var i=0;i<vinesToDestroy.length;i++){
                        this.slideToObjectAndDestroy(vinesToDestroy[i].id, destinationId, 1500, 0);
                    }
                } else {
                    this.queryAndDestroy('#'+fieldElementId+' .vine[data-destroy=1]');
                }
            }
        },

        /**
         * returns description with tokens (${token_xxx}) resolved with token in html
         **/
        getDescriptionWithTokens: function(description, smaller, substitutions, playerId){
            var playerNameRight = '';

            if (!description){
                return description;
            }

            if (description.indexOf('${playerNameRight}')>=0){
                if (playerId){
                    var playerData = this.getPlayerData(playerId);
                    var previousPlayOrder=Number(playerData.playorder)-1;
                    if (previousPlayOrder==0){
                        previousPlayOrder=this.players_number;
                    }
                    var previousPlayer = this.objectFindByProperty(this.gamedatas_local.players, 'playorder', previousPlayOrder);
                    if (previousPlayer){
                        playerNameRight='('+ this.getPlayerNameWithColor(previousPlayer.id)+')';
                    }
                    description = this.replaceAll(description,'${playerNameRight}', playerNameRight);
                }
            }

            if (substitutions){
                for (var substKey in substitutions){
                    description = this.replaceAll(description,'${'+substKey+'}', substitutions[substKey]);
                }
            }
            var tokens = description.match(/\$\{([^\s\:\}]*)(?:\:([^\s\:\}]+))?\}/g);
            if (tokens == null || tokens.length==0){
                return description;
            }

            var tokensValue  = {};
            for (var i=0;i<tokens.length;i++){
                var token = tokens[i].substr(2,tokens[i].length-3);
                var tokenType = token.substr(6,token.length-6);
                tokensValue[token] = this.getTokenSymbol(tokenType, smaller)||'';
            }

            return dojo.string.substitute(description, tokensValue);
        },

        getCardTypeDescription: function(type){
            if (!type){
                return '';
            }

            switch (type) {
                case 'greenCard':
                    return _('Vine');

                case 'yellowCard':
                    return _('Summer Visitor');
                    
                case 'blueCard':
                    return _('Winter Visitor');
                                
                case 'purpleCard':
                    return _('Wine Order');
                                                    
                case 'automaCard':
                    return _('Automa Card');

                default:
                    break;
            }

            return type;

        },

        /**
         * returns token description for tooltips
         */
        getTokenDescription: function(token){
            var playerToken = this.objectFindByProperty(this.gamedatas_local.playerTokens, 'type', token);
            if (playerToken){
                return playerToken.name;
            }

            var tokenValue='';
            var tokenType=token;
            var lastChar = token.substr(token.length - 1);
            if (lastChar>='0'&&lastChar<='9'){
                tokenValue = lastChar;
                tokenType = token.substr(0, token.length-1);
            }

            switch (tokenType) {
                case 'worker_t':
                    return _('Temporary worker');
                case 'worker':
                    return _('Worker');

                case 'workerGrande':
                    return _('Grande Worker');

                case 'lira':
                    if (tokenValue){
                        return _('Lira')+': '+tokenValue;
                    } else {
                        return _('Lira');
                    }

                case 'vp':
                    if (tokenValue){
                        return _('Victory point')+': '+tokenValue;
                    } else {
                        return _('Victory point');
                    }

                case 'residualPayment':
                    if (tokenValue){
                        return _('Residual payment')+': '+tokenValue;
                    } else {
                        return _('Residual payment');
                    }

                case 'grapeRed':
                    if (tokenValue){
                        return _('Red grape')+': '+tokenValue;
                    } else {
                        return _('Red grape');
                    }

                case 'grapeWhite':
                    if (tokenValue){
                        return _('White grape')+': '+tokenValue;
                    } else {
                        return _('White grape');
                    }

                case 'grapeAny':
                    if (tokenValue){
                        return _('Any grape')+': '+tokenValue;
                    } else {
                        return _('Any grape');
                    }

                case 'wineRed':
                    if (tokenValue){
                        return _('Red wine')+': '+tokenValue;
                    } else {
                        return _('Red wine');
                    }

                case 'wineWhite':
                    if (tokenValue){
                        return _('White wine')+': '+tokenValue;
                    } else {
                        return _('White wine');
                    }

                case 'wineBlush':
                    if (tokenValue){
                        return _('Blush wine')+': '+tokenValue;
                    } else {
                        return _('Blush wine');
                    }

                case 'wineSparkling':
                    if (tokenValue){
                        return _('Sparkling wine')+': '+tokenValue;
                    } else {
                        return _('Sparkling wine');
                    }

                case 'wineAny':
                    if (tokenValue>0){
                        return _('Any wine')+': '+tokenValue;
                    } else {
                        return _('Any wine');
                    }

                case 'greenCard':
                    return _('Vine card');

                case 'greenCardPlus':
                    return _('Draw a vine card');

                case 'yellowCard':
                    return _('Summer visitor card');

                case 'yellowCardPlus':
                    return _('Draw a summer visitor card');

                case 'purpleCard':
                    return _('Wine order card');

                case 'purpleCardPlus':
                    return _('Draw a wine order card');

                case 'blueCard':
                    return _('Winter visitor card');

                case 'blueCardPlus':
                    return _('Draw a winter visitor card');

                case 'anyCard':
                    return _('Any card');

                case 'rooster':
                    return _('Rooster for wakeup order');

                case 'wakeup_bonus':
                    return _('Wakeup bonus');
                    
                case 'star':
                    return _('Star');

                default:
                    break;
            }
            //DEBUG
            console.log('missing token description:'+token);
            //alert('missing token description:'+token);
            return '???'+token+'???';
        },

        getTokenTextDescription: function (token, smaller, tooltip){
            var tokenValue='';
            var tokenType=token;
            var lastChar = token.substr(token.length - 1);
            if (lastChar>='0'&&lastChar<='9'){
                tokenValue = lastChar;
                tokenType = token.substr(0, token.length-1);
            }
            switch (tokenType) {
                case 'lira':
                    if (tokenValue!=''){
                        return this.getTokenTextDescriptionValue(token, smaller, tooltip, tokenValue);
                    }

                case 'vp':
                    if (tokenValue!=''){
                        return this.getTokenTextDescriptionValue(token, smaller, tooltip, tokenValue);
                    }

                case 'residualPayment':
                    if (tokenValue!=''){
                        return this.getTokenTextDescriptionValue(token, smaller, tooltip, tokenValue);
                    }
            }
            return '';
        },

        getTokenTextDescriptionValue: function(token, smaller, tooltip, tokenValue){
            return this.encapsulateTag('span','('+tokenValue+')','vit_token_value');
        },

        updateGrapesWines: function(animate){
            for (var playerId in this.gamedatas_local.players){
                var grapesPlayer = this.gamedatas_local.players[playerId].grapes;
                this.updateGrapesPlayer(playerId, grapesPlayer, null, animate);
                var winesPlayer = this.gamedatas_local.players[playerId].wines;
                this.updateWinesPlayer(playerId, winesPlayer, null, animate);
            }
        },

        updateGrapesPlayer: function(playerId, grapesPlayer, origin, animate){
            var playerBoardId = 'playerboard_'+playerId;
            var playerData = this.getPlayerData(playerId);

            for (var i=0;i<grapesPlayer.length;i++){
                var elementId = 'grape_'+playerId+'_'+grapesPlayer[i].i;
                var grapeElement = $(elementId);
                var pos = this.playerBoard[grapesPlayer[i].t][grapesPlayer[i].v];
                if (!grapeElement){
                    //new Grape
                    var item = {
                        elementId: elementId,
                        cssClass: 'crushPad grapeMarker grapeMarker'+grapesPlayer[i].t+' grape',
                        type: grapesPlayer[i].t,
                        position: 'position:absolute;',
                        style: '',
                        arg: grapesPlayer[i].v,
                        x: 0,
                        y: 0,
                        id: grapesPlayer[i].i,
                        tooltip: this.getTokenDescription(grapesPlayer[i].t+grapesPlayer[i].v)
                    };
                    dojo.place( this.format_block('jstpl_marker', item), playerBoardId, 'last' );
                    if (animate && origin){
                        if (origin<=4){
                            var fieldElementId = 'field_slot_'+playerId+'_'+origin;
                            this.moveObject(elementId, fieldElementId, 0, 0, false, 0,0, false);
                        } else {
                            var originId = 'side_player_handCards_'+playerData.player_color;
                            if ($('playerboard_hand_zone_'+playerId)){
                                originId = 'playerboard_hand_zone_'+playerId;
                                if ($('playerboard_hand_zone_'+playerId+'_item_'+origin)){
                                    originId = 'playerboard_hand_zone_'+playerId+'_item_'+origin;
                                }
                            }
                            this.moveObject(elementId, originId, 0, 0, false, 0,0, false);
                        }
                    }
                } else {
                    grapeElement.setAttribute('data-arg',grapesPlayer[i].v);
                    grapeElement.alt = this.getTokenDescription(grapesPlayer[i].t+grapesPlayer[i].v);
                    grapeElement.title = this.getTokenDescription(grapesPlayer[i].t+grapesPlayer[i].v);
                }
                this.moveObject(elementId, playerBoardId, pos.x, pos.y, animate, 1500,0, true);
            }
            var grapeElements = dojo.query('#'+playerBoardId+' .grape.grapeMarker');
            for (var i=0;i<grapeElements.length;i++){
                var id = grapeElements[i].id.split('_')[2];
                if (grapeElements[i].id.indexOf('destroy')==-1){
                    var grape = this.arrayFindByProperty(grapesPlayer,'i',id);
                    if (!grape){
                        dojo.destroy(grapeElements[i].id);
                    }
                }
            }
        },

        updateWinesPlayer: function(playerId, winesPlayer, grapes, animate, destination){
            var me = this;
            var playerBoardId = 'playerboard_'+playerId;
            var playerData = this.getPlayerData(playerId);
            //Create and update wines
            for (var i=0;i<winesPlayer.length;i++){
                var elementId = 'wine_'+playerId+'_'+winesPlayer[i].i;
                var wineElement = $(elementId);
                var pos = this.calculateWinePos(winesPlayer[i].t, winesPlayer[i].v);
                if (!wineElement){
                    //new Wine
                    var item = {
                        elementId: elementId,
                        cssClass: 'cellar wineMarker wineMarker'+winesPlayer[i].t+' wine',
                        type: winesPlayer[i].t,
                        position: 'position:absolute;',
                        style: '',
                        arg: winesPlayer[i].v,
                        x: 0,
                        y: 0,
                        id: winesPlayer[i].i,
                        tooltip: this.getTokenDescription(winesPlayer[i].t+winesPlayer[i].v)
                    };
                    dojo.place( this.format_block('jstpl_marker', item), playerBoardId, 'last' );
                    if (animate && grapes){
                        var progr = 0;
                        for (var j = 0;j<grapes.length;j++){
                            var grapeElementId =  'grape_'+playerId+'_'+grapes[j].id;
                            //grape elementid
                            var grapeElement = $(grapeElementId);
                            var grapeTokenId = grapeElement.getAttribute('data-id');
                            if (grapeElement){
                                progr++;
                                if (progr==1){
                                    //move to position instantly
                                    this.moveObject(elementId, playerBoardId, pos.x, pos.y, false, 1500, 0, true);
                                    //hide it
                                    dojo.style(elementId, 'visibility','hidden');
                                    //Change id and move it to wine and destroy it
                                    grapeElement.id = 'grapeslideanddestroy_'+grapeTokenId+'_'+progr;
                                    this.slideToObjectAndDestroyWithCallback( grapeElement.id, elementId, 1500, 0 , function(){dojo.style(elementId, 'visibility','inherit');});
                                } else {
                                    //Change id and move it to wine and destroy it
                                    grapeElement.id = 'grapeslideanddestroy_'+grapeTokenId+'_'+progr;
                                    this.slideToObjectAndDestroy( grapeElement.id, elementId, 1500, 0 );
                                }
                            }
                        }
                    }
                } else {
                    wineElement.setAttribute('data-arg',winesPlayer[i].v);
                    wineElement.alt = this.getTokenDescription(winesPlayer[i].t+winesPlayer[i].v);
                    wineElement.title = this.getTokenDescription(winesPlayer[i].t+winesPlayer[i].v);
                }
                this.setElementZIndex(elementId, 15+Number(winesPlayer[i].v));
                this.moveObject(elementId, playerBoardId, pos.x, pos.y, animate, 1500, true);
            }

            //remove wines
            var wineElements = dojo.query('#'+playerBoardId+' .wine');
            for (var i=0;i<wineElements.length;i++){
                if (wineElements[i].id.indexOf('destroy')==-1){
                    var id = wineElements[i].id.split('_')[2];
                    var wine = this.arrayFindByProperty(winesPlayer,'i',id);
                    if (!wine){
                        if (animate && destination){
                            wineElements[i].id += 'destroy';
                            var destinationId = 'side_player_handCards_'+playerData.player_color;
                            if ($('playerboard_hand_zone_'+playerId)){
                                destinationId = 'playerboard_hand_zone_'+playerId;
                                if ($('playerboard_hand_zone_'+playerId+'_item_'+destination)){
                                    destinationId = 'playerboard_hand_zone_'+playerId+'_item_'+destination;
                                }
                            }
                            this.slideToObjectAndDestroy( wineElements[i].id, destinationId, 1500, 0 );
                        } else {
                            dojo.destroy(wineElements[i].id);
                        }
                    }
                }
            }
        },

        updateMamasPapas: function(animate){
            for (var playerId in this.gamedatas_local.players){
                var playerData = this.gamedatas_local.players[playerId];
                if (playerData.mama>0){
                    var mamaElement = $('player_mamaName_'+playerId);
                    if (mamaElement.getAttribute('data-id')==''){
                        mamaElement.innerHTML = this.gamedatas_local.mamas[playerData.mama].name;
                        mamaElement.setAttribute('data-id',playerData.mama);
                        this.addTooltipHtml(mamaElement.id, this.getTooltipHtmlMamaPapaCard(playerData.mama));
                    }
                }
                if (playerData.papa>0){
                    var papaElement = $('player_papaName_'+playerId);
                    if (papaElement.getAttribute('data-id')==''){
                        papaElement.innerHTML = this.gamedatas_local.papas[playerData.papa].name;
                        papaElement.setAttribute('data-id',playerData.papa);
                        this.addTooltipHtml(papaElement.id, this.getTooltipHtmlMamaPapaCard(playerData.papa));
                    }
                }
            }
        },

        updateScoreAndResidualPayment: function(animate){
            var progr = 0;
            var scoringCount=[];
            var scoringProgr=[];
            var residualPaymentCount=[];
            var residualPaymentProgr=[];
            for (var playerId in this.gamedatas_local.players){
                if (playerId!=this.SOLO_PLAYER_ID){
                    var playerData = this.getPlayerData(playerId);
                    if (playerData.playorder==1){
                        dojo.addClass('cc_player_board_'+playerId,'vit_first_player');
                        dojo.addClass('playerboard_row_'+playerId,'vit_first_player');
                    } else {
                        dojo.removeClass('cc_player_board_'+playerId,'vit_first_player');
                        dojo.removeClass('playerboard_row_'+playerId,'vit_first_player');
                    }
                }
            }
            for (var playerId in this.gamedatas_local.players){
                var score=this.gamedatas_local.players[playerId].score;
                //max displayed token pos is 26, more will exit board
                if (score>26){
                    score=26;
                }
                if (scoringCount[score]==null){
                    scoringCount[score]=0;
                }
                scoringCount[score]++;
                scoringProgr[score]=0;
                var residual_payment=this.gamedatas_local.players[playerId].residual_payment;
                if (residualPaymentCount[residual_payment]==null){
                    residualPaymentCount[residual_payment]=0;
                }
                residualPaymentCount[residual_payment]++;
                residualPaymentProgr[residual_payment]=0;
            }
            for (var playerId in this.gamedatas_local.players){
                var score=this.gamedatas_local.players[playerId].score;
                var originalScore=this.gamedatas_local.players[playerId].score;
                //max displayed token pos is 26, more will exit board
                if (score>26){
                    score=26;
                }
                var pos = this.scoringTrack[this.gamedatas_local.set].offsetPlayer[scoringCount[score]-1][scoringProgr[score]];
                scoringProgr[score]++;
                var scoringX = this.scoringTrack[this.gamedatas_local.set].zeroX + score*this.scoringTrack[this.gamedatas_local.set].dx+this.scoringTrack[this.gamedatas_local.set].ddx+pos.x*this.scoringTrack[this.gamedatas_local.set].ddx;
                var scoringY = pos.y*this.scoringTrack[this.gamedatas_local.set].ddy;
                this.moveObject( 'scoringToken_'+playerId, 'scoringTrack_slot', scoringX, scoringY, animate, 1500, 0, true);

                var residualPayment = this.residualPayment[this.gamedatas_local.set];
                var residualPaymentPlayer = this.gamedatas_local.players[playerId].residual_payment;
                pos = this.residualPayment[this.gamedatas_local.set].offsetPlayer[residualPaymentCount[residualPaymentPlayer]-1][residualPaymentProgr[residualPaymentPlayer]];
                residualPaymentProgr[residualPaymentPlayer]++;
                var residualPaymentX = pos.x*residualPayment.ddx;
                var residualPaymentY = pos.y*residualPayment.ddy;
                if (residualPaymentPlayer>0){
                    residualPaymentX +=  Math.cos(residualPaymentPlayer*Math.PI/2.5-Math.PI-0.3) * residualPayment.offset;
                    residualPaymentY +=  Math.sin(residualPaymentPlayer*Math.PI/2.5-Math.PI-0.3) * residualPayment.offset;
                }
                this.moveObject( 'residualPaymentToken_'+playerId, 'residualPayment_slot', residualPaymentX, residualPaymentY, animate, 1500, 0, true);

                progr++;
            }
        },

        updateHand: function(animate, origin, target){
            var mustUpdate=false;
            // Private hand cards by player
            for( var i=0;i<this.gamedatas_local.hand.length;i++)
            {
                var card = this.gamedatas_local.hand[i];
                var cardType = this.getCardType(card.k);

                //add if not present
                if (! this.handZone.getItemById(card.i)){
                    var originElementId;
                    if (animate && origin==null){
                        var deck = this.cardTypes[cardType].deck;
                        originElementId = 'deck_count_'+deck;
                    } else  if (origin=='discard'){
                        var discardDeck = this.cardTypes[cardType].discard;
                        originElementId = 'discardCard_slot_'+discardDeck;
                    }
                    if (originElementId){
                        this.handZone.addToStockWithId(card.k, card.i, originElementId);
                    } else {
                        this.handZone.addToStockWithId(card.k, card.i);
                    }
                }
            }

            // remove cards not present now
            var items = this.handZone.getAllItems();
            for (var i = 0;i<items.length;i++){
                var id = Number(items[i].id);
                var found = false;
                for( var j=0;j<this.gamedatas_local.hand.length;j++){
                    if (this.gamedatas_local.hand[j].i == id){
                        found = true;
                    }
                }
                if (!found){

                    var key = Number(items[i].type);
                    var cardType = this.getCardType(key);
                    var targetElementId;
                    if (animate && (target==null || target=='discard')){
                        var discardDeck = this.cardTypes[cardType].discard;
                        targetElementId = 'discardCard_slot_'+discardDeck;
                    }
                    //check if vine
                    var playerData = this.getPlayerData(this.getThisPlayerId());
                    if (this.arrayFindByProperty(playerData.vine1, 'i',id)||
                        this.arrayFindByProperty(playerData.vine2, 'i',id)||
                        this.arrayFindByProperty(playerData.vine3, 'i',id)){
                        targetElementId=null;
                    }
                    if (targetElementId){
                        this.handZone.removeFromStockById(id, targetElementId, true);
                        mustUpdate=true;
                    } else {
                        this.handZone.removeFromStockById(id);
                    }
                }
            }
            if (mustUpdate){
                this.handZone.updateDisplay();
            }
        },

        updateHistory: function(){
            var historyStock = this.historyStock;

            historyStock.removeAll();

            var history=this.gamedatas_local.history;
            for (var i=0;i<history.length;i++){
                var cardParts = history[i].split('_');
                this.historyStock.addToStockWithId(cardParts[1], history[i]+'_'+i);
            }
            if (historyStock.count()>0){
                $('history_exandable_count').innerHTML = '('+historyStock.count()+')';
            }
        },
        
        updateAutomaCards: function(){
            var automaCardsStock = this.automaCardsStock;

            if (this.automaCardsStock){
                automaCardsStock.removeAll();

                var acs=this.gamedatas_local.acs;
                for (var i=0;i<acs.length;i++){
                    this.automaCardsStock.addToStockWithId(acs[i], acs[i]);
                }
            }


        },

        //decompress map properties
        decompressMap: function(map){
            return map;
        },

        extend: function(obj, src) {
            for (var key in src) {
                if (src.hasOwnProperty(key)) obj[key] = src[key];
            }
            return obj;
        },

        isEmpty: function(obj) {
            for(var prop in obj) {
                if(obj.hasOwnProperty(prop))
                    return false;
            }
            return true;
        },

        /** Override this function to inject html for log items  */

        /* @Override */
        format_string_recursive : function(log, args) {
            try {
                if (log && args && !args.processed) {
                    args.processed = true;

                    if (!this.isSpectator)
                        args.You = this.divYou(); // will replace ${You} with colored version

                    for ( var key in args) {
                        if (args[key] && typeof args[key] == 'string' && key.indexOf('token_')==0){
                           args[key] = this.getTokenDiv(key, args);
                        }
                        if (args[key] && typeof args[key] == 'string' && args[key].indexOf('${token_')>=0){
                            args[key] = this.getDescriptionWithTokens(args[key]);
                        }
                    }
                }
            } catch (e) {
                console.error(log,args,"Exception thrown", e.stack);
            }
            return this.inherited(arguments);
        },

        getTokenDiv : function(key, args) {
            var token_id = args[key];
            var tokenDiv = this.getTokenSymbol(token_id, true);
            return tokenDiv;
        },

        formatNumberWithSign: function(numberValue){
            if (numberValue >= 0) return "+" + numberValue;
            return "" + numberValue;
        },

        getTokenPlayerSymbol: function(playerId, type, smaller){
            var player = this.gamedatas_local.players[playerId];
            var tokenColor = '';
            if (player){
                tokenColor = ' '+type+'_'+player.player_color;
            }
            var tooltip = this.getTokenDescription(type);
            return this.getTokenSymbol(type+tokenColor, smaller, tooltip);
        },

        getGrapeSymbol: function(type, value, tooltip, cssClass){
            var tooltipToken = tooltip;
            var tokenType = type;

            if (value == 0){
                value ='';
            }
            if (type=='red'||type=='grapeRed'){
                tokenType = 'grapeRed';
            }
            if (type=='white'||type=='grapeWhite'){
                tokenType = 'grapeWhite';
            }
            cssClass = cssClass||'';

            if (tooltipToken==null){
                tooltipToken = this.getTokenDescription(tokenType+value);
            }
            tooltipToken = this.escapeHtml(tooltipToken);

            return '<span class="token grape tokentext '+tokenType+' '+ tokenType+value+' '+cssClass+'" alt="' + tooltipToken+ '" title="' + tooltipToken + '"> </span>';
        },

        getWineSymbol: function(type, value, tooltip, cssClass){
            var tooltipToken = tooltip;
            var tokenType = type;

            if (value == 0){
                value ='';
            }
            var tokenType= '';
            if (type=='red'||type=='wineRed'){
                tokenType = 'wineRed';
            }
            if (type=='white'||type=='wineWhite'){
                tokenType = 'wineWhite';
            }
            if (type=='blush'||type=='wineBlush'){
                tokenType = 'wineBlush';
            }
            if (type=='sparkling'||type=='wineSparkling'){
                tokenType = 'wineSparkling';
            }
            cssClass = cssClass||'';

            if (tooltipToken==null){
                tooltipToken = this.getTokenDescription(tokenType+value);
            }
            tooltipToken = this.escapeHtml(tooltipToken);

            return '<span class="token wine tokentext '+tokenType+' '+tokenType+value+' '+cssClass+'" alt="' + tooltipToken+ '" title="' + tooltipToken + '"> </span>';
        },

        getPreviewLira: function(playerId, increment){
            var initial_lira = Number(this.getPlayerData(playerId).lira);
            var preview = initial_lira+this.formatNumberWithSign(increment)+'='+(initial_lira+increment)+this.getTokenSymbol('lira');
            return preview;
        },

        getTokenSymbol: function(type, smaller, tooltip){
            var tokenParts = (type+'|').split('|');
            var tooltipToken = tooltip;
            if (tooltipToken==null){
                tooltipToken = this.getTokenDescription(tokenParts[0]);
            }
            tooltipToken = this.escapeHtml(tooltipToken);
            if (smaller){
                return '<span class="token small tokentext '+tokenParts[0]+' smaller" alt="' + tooltipToken + '" title="' + tooltipToken + '"> </span>'+this.getTokenTextDescription(type, smaller, tooltip);
            }
            return '<span class="token small tokentext '+tokenParts[0]+'" alt="' + tooltipToken+ '" title="' + tooltipToken + '"> </span>'+this.getTokenTextDescription(type, smaller, tooltip);
        },

        getRotationStyle: function(rotation){
            if (rotation==null){
                return '';
            }
            return this.rotationTransformCssStyle+':rotate(' + rotation + 'deg);';
        },

        /* Implementation of proper colored You with background in case of white or light colors  */

        divYou : function() {
            var color_bg = "";
            if (this.player_color_back) {
                color_bg = "background-color:#" + this.player_color_back + ";";
            }
            var you = "<span style=\"font-weight:bold;color:#" + this.player_color + ";" + color_bg + "\">" + __("lang_mainsite", "You") + "</span>";
            return you;
        },

        escapeHtml: function (unsafe) {
            if (unsafe==null||unsafe==''){
                return unsafe;
            }
            return unsafe
                 .replace(/&/g, "&amp;")
                 .replace(/</g, "&lt;")
                 .replace(/>/g, "&gt;")
                 .replace(/"/g, "&quot;")
                 .replace(/'/g, "&#039;");
        },

        /**
         * replaces strings (no regex)
         * @param s
         * @param t
         * @param u
         * @returns {*}
         */
        replaceAll: function(s, t, u) {
            var i = s.indexOf(t);
            var r = "";
            if (i == -1) return s;
            r += s.substring(0,i) + u;
            if ( i + t.length < s.length)
                r += this.replaceAll(s.substring(i + t.length, s.length), t, u);
            return r;
        },

        getUrlParams: function() {
            var paramMap = {};
            try{
                if (location.search.length == 0) {
                  return paramMap;
                }
                var parts = location.search.substring(1).split("&");

                for (var i = 0; i < parts.length; i ++) {
                  var component = parts[i].split("=");
                  paramMap [decodeURIComponent(component[0])] = decodeURIComponent(component[1]);
                }
            } catch (error){
                //do nothing
            }
            return paramMap;
        },

        scrollTo: function(pElementId){
            var element = $(pElementId);
            if (element){
                element.scrollIntoView(true);
                window.scrollBy(0, -100);
            }
        },

        /**
         * moves object with or without animation
         * returns true after attaching to new parent
         */
        moveObject: function(mobileObj, targetObj, targetX, targetY, animate, duration, delay, attachToTargetObj) {
            var obj = $(mobileObj);
            var targetElement = $(targetObj);
            var destPositionString=targetObj+"|"+targetX+"|"+targetY;
            var newParent = false;
            if (attachToTargetObj){
                if (obj.parentElement.id != targetElement.id){
                    this.attachToNewParent(mobileObj, targetObj);
                    newParent = true;
                }
            }
            if (obj.getAttribute('data-private-destposition')!=destPositionString){
                obj.setAttribute('data-private-destposition',destPositionString);
                if (animate){
                    this.slideToObjectPosCentered( mobileObj, targetObj, targetX, targetY, duration, delay||0).play();
                } else {
                    this.placeOnObjectPos( mobileObj, targetObj, targetX, targetY);
                }
            }
            return newParent;
        },

        /**
         * slideToObjectPos with centered coordinates lik other slide/pos
         */
        slideToObjectPosCentered : function(mobileObj, targetObj, targetX, targetY, duration, delay) {
            if (mobileObj === null) {
                console.error("slideToObjectPosCentered: mobile obj is null");
            }
            if (targetObj === null) {
                console.error("slideToObjectPosCentered: target obj is null");
            }
            if (targetX === null) {
                console.error("slideToObjectPosCentered: target x is null");
            }
            if (targetY === null) {
                console.error("slideToObjectPosCentered: target y is null");
            }
            var tgt = dojo.position(targetObj);
            var src = dojo.position(mobileObj);
            if (typeof duration == "undefined") {
                duration = 500;
            }
            if (typeof delay == "undefined") {
                delay = 0;
            }
            if (this.instantaneousMode) {
                delay = Math.min(1, delay);
                duration = Math.min(1, duration);
            }
            var left = dojo.style(mobileObj, "left");
            var top = dojo.style(mobileObj, "top");
            left = left + tgt.x-src.x + (tgt.w-src.w)/2 + targetX;
            top = top + tgt.y - src.y + (tgt.h-src.h)/2 + targetY;

            return dojo.fx.slideTo({
                node : mobileObj,
                top : top,
                left : left,
                delay : delay,
                duration : duration,
                unit : "px"
            });
        },

        slideToObjectAndDestroyWithCallback: function(element, target, time, delay, callback) {
            dojo.style(element, "zIndex", 100);
            var anim = this.slideToObject(element, target, time, delay);
            dojo.connect(anim, "onEnd", function(node) {
                dojo.destroy(node);
                if (callback){
                    callback();
                }
            });
            anim.play();
        },
        
        disableStockSelectionWithouthEvents: function(pStock, pMantainSelection){
            var selection = [];
            
            for (var i in pStock.items) {
                if (pStock.isSelected(pStock.items[i].id)) {
                    if (pMantainSelection){
                        selection.push(pStock.items[i].id);
                    }
                    pStock.unselectItem(pStock.items[i].id);
                }
            }
            pStock.setSelectionMode(0);

            if (pMantainSelection){
                for (var i=0;i<selection.length;i++){
                    pStock.selectItem(selection[i]);
                }
            }
        },

        openDialog: function(id, title, text){
            // Create the new dialog over the play zone. You should store the handler in a member variable to access it later
            this.myDlg = new ebg.popindialog();
            this.myDlg.create( id );
            this.myDlg.setTitle( title );
            this.myDlg.setMaxWidth( 500 ); // Optional


            // Show the dialog
            this.myDlg.setContent( text ); // Must be set before calling show() so that the size of the content is defined before positioning the dialog
            this.myDlg.show();

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

        /* Example:

        onMyMethodToCall1: function( evt )
        {
            console.log( 'onMyMethodToCall1' );

            // Preventing default browser reaction
            dojo.stopEvent( evt );

            // Check that this action is possible (see "possibleactions" in states.inc.php)
            if( ! this.checkAction( 'myAction' ) )
            {   return; }

            this.ajaxcall( "/viticulture/viticulture/myAction.html", {
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
                  your viticulture.game.php file.

        */
        setupNotifications: function()
        {
            console.log( 'notifications subscriptions setup' );

            dojo.subscribe('harvestField',this, 'notif_harvestField');
            dojo.subscribe('makeWine',this, 'notif_makeWine');
            dojo.subscribe('uprootVine', this, 'notif_uprootVine');
            dojo.subscribe('plant', this, 'notif_plant');
            dojo.subscribe('fillOrder', this, 'notif_fillOrder');
            dojo.subscribe('playYellowCardNewGrapes', this, 'notif_playYellowCardNewGrapes');
            dojo.subscribe('playBlueCardNewGrapes', this, 'notif_playBlueCardNewGrapes');
            dojo.subscribe('addCardPlayedToHistory', this, 'notif_addCardPlayedToHistory');
            dojo.subscribe('removeLastCardPlayedToHistory', this, 'notif_removeLastCardPlayedToHistory');
            dojo.subscribe('updateDeck', this, 'notif_updateDeck');
            dojo.subscribe('discardCardsUpdateHand', this, 'notif_discardCardsUpdateHand' );
            dojo.subscribe('updateAll', this, 'notif_updateAll' );
            //dojo.subscribe('ageGrapesWinesAndGetResidualPayments', this, 'notif_ageGrapesWinesAndGetResidualPayments' ); //not needed
            dojo.subscribe('soloEnd', this, 'notif_soloEnd' );

            //TODO: notify discard card animation

            // Load production bug report handler
            dojo.subscribe("loadBug", this, function loadBug(n) {
                function fetchNextUrl() {
                var url = n.args.urls.shift();
                console.log("Fetching URL", url);
                dojo.xhrGet({
                    url: url,
                    load: function (success) {
                    console.log("Success for URL", url, success);
                    if (n.args.urls.length > 0) {
                        fetchNextUrl();
                    } else {
                        console.log("Done, reloading page");
                        window.location.reload();
                    }
                    },
                });
                }
                console.log("Notif: load bug", n.args);
                fetchNextUrl();
            });

        },


        /*
        Example:

        notif_cardPlayed: function( notif )
        {
            console.log( 'notif_cardPlayed' );
            console.log( notif );

            // Note: notif.args contains the arguments specified during you "notifyAllPlayers" / "notifyPlayer" PHP call

        },

        */
        notif_harvestField: function( notif )
        {
            console.log( 'notif_cardPlayed' );
            //console.log( notif );

            this.updateGrapesPlayer(notif.args.player_id, notif.args.newGrapes, notif.args.field, true);
        },

        notif_makeWine: function( notif )
        {
            console.log( 'notif_makeWine' );
            //console.log( notif );

            if (notif.args.makeWines){
                this.updateWinesPlayer(notif.args.player_id, notif.args.makeWines, notif.args.grapesUsed, true);
            }

        },

        notif_uprootVine: function( notif)
        {
            console.log( 'notif_uprootVine' );
            //console.log( notif );

            this.gamedatas_local.players[notif.args.player_id].vine1 = notif.args.vines.vine1;
            this.gamedatas_local.players[notif.args.player_id].vine2 = notif.args.vines.vine2;
            this.gamedatas_local.players[notif.args.player_id].vine3 = notif.args.vines.vine3;

            this.updateVinesPlayer(notif.args.player_id, true, null, 'hand');
        },

        notif_plant: function( notif)
        {
            console.log( 'notif_plant' );
            //console.log( notif );

            this.gamedatas_local.players[notif.args.player_id].vine1 = notif.args.vines.vine1;
            this.gamedatas_local.players[notif.args.player_id].vine2 = notif.args.vines.vine2;
            this.gamedatas_local.players[notif.args.player_id].vine3 = notif.args.vines.vine3;

            this.updateVinesPlayer(notif.args.player_id, true, 'hand', null);
        },

        notif_fillOrder: function( notif)
        {
            console.log( 'notif_fillOrder' );
            //console.log( notif );

            this.gamedatas_local.players[notif.args.player_id].wines = notif.args.wines;

            this.updateWinesPlayer(notif.args.player_id, notif.args.wines, null, true, notif.args.cardId);
        },

        notif_playYellowCardNewGrapes: function( notif )
        {
            console.log( 'notif_playYellowCardNewGrapes' );
            //console.log( notif );

            this.updateGrapesPlayer(notif.args.player_id, notif.args.newGrapes, notif.args.visitorCardId, true);
        },

        notif_playBlueCardNewGrapes: function( notif )
        {
            console.log( 'notif_playBlueCardNewGrapes' );
            //console.log( notif );

            this.updateGrapesPlayer(notif.args.player_id, notif.args.newGrapes, notif.args.visitorCardId, true);
        },

        notif_addCardPlayedToHistory: function( notif )
        {
            console.log( 'notif_addCardPlayedToHistory' );
            //console.log( notif );

            this.addCardPlayedToHistory(notif.args.card);
        },

        notif_removeLastCardPlayedToHistory: function( notif )
        {
            console.log( 'notif_removeLastCardPlayedToHistory' );
            //console.log( notif );

            this.removeLastCardPlayedToHistory(notif.args.card);
        },

        notif_updateDeck: function( notif )
        {
            console.log( 'notif_updateDeck' );
            //console.log( notif );
            this.gamedatas_local.hand = notif.args.hand;

            this.updateHand(true, notif.args.origin, notif.args.target);
        },

        notif_discardCardsUpdateHand: function( notif )
        {
            console.log( 'notif_discardCardsUpdateHand' );
            //console.log( notif );

            this.gamedatas_local.hand = notif.args.hand;

            //disable selection
            this.disableStockSelectionWithouthEvents(this.handZone, false);
            this.queryAndRemoveClass('.playerboard_hand_zone.stock_confirm_selection','stock_confirm_selection');

            this.updateHand(true, notif.args.origin, notif.args.target);

        },

        notif_updateAll: function( notif )
        {
            console.log( 'notif_updateAll' );
            //console.log( notif );

            try{
                this.gamedatas_local.players = notif.args.players;
                this.gamedatas_local.tokens = notif.args.tokens;
                this.gamedatas_local.cdc = notif.args.cdc;
                this.gamedatas_local.tdd = notif.args.tdd;
                this.gamedatas_local.actionProgress = notif.args.actionProgress;
                this.gamedatas_local.pceg = notif.args.pceg;
            } catch (e){
                alert('Error during read datas, track it in bugs please');
                console.log(e);
            }

            try{
                this.gamedatas_local.hand = notif.args.privateData[this.getThisPlayerId()].hand;
            } catch (e){
                alert('Error during read privateData hand, track it in bugs please');
                console.log(e);
            }
            try{
                this.updateScoreAndResidualPayment(true);
            } catch (e){
                alert('Error during updateScoreAndResidualPayment, track it in bugs please');
                console.log(e);
            }
            try{
                this.updateTokens(true);
            } catch (e){
                alert('Error during updateTokens, track it in bugs please');
                console.log(e);
            }
            try{
                this.updateDecks(true);
            } catch (e){
                alert('Error during updateDecks, track it in bugs please');
                console.log(e);
            }
            try{
                this.updatePlayerFlags(true);
            } catch (e){
                alert('Error during updatePlayerFlags, track it in bugs please');
                console.log(e);
            }
            try{
                this.updateVines(true);
            } catch (e){
                alert('Error during updateVines, track it in bugs please');
                console.log(e);
            }
            try{
                this.updateGrapesWines(true);
            } catch (e){
                alert('Error during updateGrapesWines, track it in bugs please');
                console.log(e);
            }
            try{
                this.updateHand(true);
            } catch (e){
                alert('Error during updateHand, track it in bugs please');
                console.log(e);
            }
            try{
                this.updateHistory(true);
            } catch (e){
                alert('Error during updateHistory, track it in bugs please');
                console.log(e);
            }
            try{
                this.updatePlayerEndGame();
            } catch (e){
                alert('Error during updatePlayerEndGame, track it in bugs please');
                console.log(e);
            }

        },

        notif_ageGrapesWinesAndGetResidualPayments: function( notif )
        {
            console.log( 'notif_ageGrapesWinesAndGetResidualPayments' );
            //console.log( notif );

            try{
                this.gamedatas_local.players = notif.args.players;
            } catch (e){
                alert('Error during read datas, track it in bugs please');
                console.log(e);
            }

            try{
                this.updateScoreAndResidualPayment(true);
            } catch (e){
                alert('Error during updateScoreAndResidualPayment, track it in bugs please');
                console.log(e);
            }
            try{
                this.updateTokens(true);
            } catch (e){
                alert('Error during updateTokens, track it in bugs please');
                console.log(e);
            }
            try{
                this.updatePlayerFlags(true);
            } catch (e){
                alert('Error during updatePlayerFlags, track it in bugs please');
                console.log(e);
            }
            try{
                this.updateVines(true);
            } catch (e){
                alert('Error during updateVines, track it in bugs please');
                console.log(e);
            }
            try{
                this.updateGrapesWines(true);
            } catch (e){
                alert('Error during updateGrapesWines, track it in bugs please');
                console.log(e);
            }
        },

        notif_soloEnd: function( notif )
        {
            console.log( 'notif_soloEnd' );
            //console.log( notif );
            var data=notif.args;
            var title;

            var msg;
            if (data.win==1){
                title = _('You win!');
                msg = dojo.string.substitute(_('Your final score is: ${score}${token_vp}'),{score:data.score, token_vp:this.getTokenSymbol('vp')});
            } else {
                title = _('You lose!');
                msg = dojo.string.substitute(_('Your final score is: ${score}${token_vp}<br/>Automa score is: ${automaScore}${token_vp}'),{score:data.score, token_vp:this.getTokenSymbol('vp'), automaScore:data.automaScore});
            }

            this.openDialog( 'soloModeEndingDlg',title, msg );

        }

   });
});
