{OVERALL_GAME_HEADER}

<!-- 
--------
-- BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
-- viticulture implementation : © Leo Bartoloni bartololeo74@gmail.com
-- 
-- This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
-- See http://en.boardgamearena.com/#!doc/Studio for more information.
-------

    viticulture_viticulture.tpl
    
    This is the HTML template of your game.
    
    Everything you are writing in this file will be displayed in the HTML page of your game user interface,
    in the "main game zone" of the screen.
    
    You can use in this template:
    _ variables, with the format {MY_VARIABLE_ELEMENT}.
    _ HTML block, with the BEGIN/END format
    
    See your "view" PHP file to check how to set variables and control blocks
    
    Please REMOVE this comment before publishing your game on BGA
-->


<script type="text/javascript">

// Javascript HTML templates

//Board
var jstpl_player_side = '<div id="cc_player_board_${id}" class="cc_player_board">\
  <span class="vit_first_player_token"><span class="token firstPlayer" title="${first_player_tooltip}"></span></span>\
  <span class="cc_counter"><span id="side_player_lira_${player_color}" data-binding="b.players[${id}].lira">${lira}</span><span id="lira_${player_color}_side" class="token small tokentext lira tooltipenable"></span></span>\
  <span class="cc_counter"><span id="side_player_residual_payment_${player_color}" data-binding="b.players[${id}].residual_payment">${residual_payment}</span><span id="residual_payment_${player_color}_side" class="token small tokentext residualPayment tooltipenable"></span></span>\
  <span class="cc_counter vit_solo_element"><span id="side_bonuses_${player_color}" data-binding="b.players[${id}].bonuses">${bonuses}</span><span id="bonuses_${player_color}_side" class="token small tokentext marker tooltipenable"></span></span>\
  <span class="cc_counter handCardsSummary"><span id="side_player_handCardsSummary_${player_color}" data-binding="b.players[${id}].handCardsSummary">${handCardsSummary}</span></span>\
  <span class="cc_counter workersSummary"><span id="side_player_workersSummary_${player_color}" data-binding="b.players[${id}].workersSummary">${workersSummary}</span></span>\
</div>';

//Board
var jstpl_player_side_automa = '<div id="cc_player_board_${id}" class="cc_player_board cc_player_board_automa">${name}\
  <span class="cc_counter"><span id="score_automa_${player_color}" data-binding="b.players[${id}].score">${score}</span><span id="automa_score_${player_color}_side" class="token small tokentext vp tooltipenable"></span></span>\
  <span class="cc_counter workersSummary"><span id="side_player_workersSummary_${player_color}" data-binding="b.players[${id}].workersSummary">${workersSummary}</span></span>\
</div>';

//Board automa
/*var jstpl_player_side_automa = '<div id="overall_player_board_${id}" class="player-board current-player-board" style="width: 234px; height: auto;">\
                        <div class="player_board_inner" id="player_board_inner_${player_color}">\
                                <div class="emblemwrap" id="avatarwrap_${id}">\
                                </div>\
                                <div id="rtc_placeholder_${id}" class="rtc_placeholder"></div>\
                                <div class="emblemwrap" id="avatar_active_wrap_{id}}" style="display:none">\
                                </div>\
                                <div class="player-name" id="player_name_${id}">\
                                 	${name}\
                                </div>\
                                <div id="player_board_${id}" class="player_board_content">\
                                    <div class="player_score">\
                                        <span id="player_score_${id}" class="player_score_value">0</span> <i class="fa fa-star" id="icon_point_2304436"></i>\
                                        <span class="player_elo_wrap">• <div class="gamerank gamerank_average "><span class="icon20 icon20_rankw"></span> <span class="gamerank_value" id="player_elo_${id}" "=""></span></div></span>\
                                        <span id="timeToThink_${id}" class="timeToThink">--:--</span>\
                                    </div>\
                                    <div class="player_showcursor" id="player_showcursor__${id}"><input type="checkbox" checked="checked" class="player_hidecursor" id="player_hidecursor_2304436"> Show cursor <i class="fa fa-hand-pointer-o" style="color:#ff0000"></i></div>\
                                    <div class="player_table_status" id="player_table_status__${id}></div>\
                                <div id="current_player_board">\
                                </div>\
                                <div id="player_panel_content_${player_color}" class="player_panel_content">';*/


var jstpl_player_board = '<div id="playerboard_row_${id}" class="playerboard_row whiteblock ${playerboard_row_class}">\
  <h3 id="playerboard_row_header_${id}" class="playerboard_row_header">\
  <span class="vit_first_player_token"><span class="token firstPlayer" title="${first_player_tooltip}"></span></span>\
  <span style="color:#${player_color};">${name}</span>\
  <span class="cc_counter"><span id="player_lira_${player_color}" data-binding="b.players[${id}].lira">${lira}</span><span id="lira_${player_color}" class="token small tokentext lira tooltipenable"></span></span>\
  <span class="cc_counter"><span id="player_residual_payment_${player_color}" data-binding="b.players[${id}].residual_payment">${residual_payment}</span><span id="residual_payment_${player_color}" class="token small tokentext residualPayment tooltipenable"></span></span>\
  <span class="cc_counter vit_solo_element"><span id="player_bonuses_${player_color}" data-binding="b.players[${id}].bonuses">${bonuses}</span><span id="bonuses_${player_color}" class="token small tokentext marker tooltipenable"></span></span>\
  <span class="cc_counter mamaName"><span id="player_mamaName_${id}" data-id="">-</span></span>\
  <span class="cc_counter papaName"><span id="player_papaName_${id}" data-id="">-</span></span>\
  <span class="cc_counter handCardsSummary"><span id="player_handCardsSummary_${player_color}" data-binding="b.players[${id}].handCardsSummary">${handCardsSummary}</span></span>\
  <span class="cc_counter workersSummary"><span id="player_workersSummary_${player_color}" data-binding="b.players[${id}].workersSummary">${workersSummary}</span></span>\
  </h3>\
    <div class="playerboard">\
        <div id="playerboard_wrapper_${id}" class="playerboard_img_wrapper shadow"><div id="playerboard_${id}" class="playerboard_img"></div></div>\
        <div id="playerboard_hand_zone_${id}" class="playerboard_hand_zone"></div>\
        <div class="spectator_playerboard_hand_zone">${labelHandSpectator}</div>\
    </div>\
    ${preferencesHtml}\
  </div>\
</div>';

var jstpl_player_board_empty='<div class="playerboard_row playerboard_row_empty ${playerboard_row_class}">';

// Action Slot
var jstpl_action_slot ='<span id="${elementId}" class="${cssClass}" style="${position} ${style}" data-type="${type}" data-x="${x}" data-y="${y}" data-action="${action}" data-arg="${arg}" data-phase="${phase}">${label}</span>';

// Shared Location
var jstpl_shared_location ='<span id="${elementId}" class="${cssClass}" style="${position} ${style}" data-type="${type}" data-x="${x}" data-y="${y}">${label}</span>';

// Token
var jstpl_token ='<span id="${elementId}" class="token ${cssClass} ${type}" style="${position} ${style}" data-type="${type}" data-id="${id}" data-x="${x}" data-y="${y}" data-arg="${arg}" alt="${tooltip}" title="${tooltip}" > </span>';

// Wine/Grape marker
var jstpl_marker ='<span id="${elementId}" class="token ${cssClass} ${type}" style="${position} ${style}" data-type="${type}" data-id="${id}" data-x="${x}" data-y="${y}" data-arg="${arg}" alt="${tooltip}" title="${tooltip}" ><span class="marker shadow"> </span></span>';

// Card
var jstpl_card ='<div id="${elementId}" class="${topCssClass}" style="${position} ${style}" data-location="${location}" data-type="${type}" data-id="${id}" data-cardtype="${cardType}" data-x="${x}" data-y="${y}"><div class="inner_element"><span class="card ${cssClass} card_${type}" ><span class="name">${name}</span><span class="description">${description}</span></span></div></div>';
var jstpl_card_tooltip ='<div class="card_tooltip ${type} ${cardType}"><span class="type type_${cardType}">${cardTypeDescription}</span><h1>${tooltipName}</h1><div class="text">${text}</div><div class="card_container"><div class="inner_element"><span class="card ${cssClass} card_${type} shadow"><span class="name">${name}</span><span class="description">${description}</span></span></div></div></div>';

// Automa Card
var jstpl_card_automa ='<div id="${elementId}" class="${topCssClass}" style="${position} ${style}" data-location="${location}" data-type="${type}" data-id="${id}" data-cardtype="${cardType}" data-x="${x}" data-y="${y}"><div class="inner_element"><span class="card ${cssClass} card_${type}" ><span class="name">${name}</span>\
  <div class="${cls1}"><span class="automaCardBarText">${des1}</span></div>\
  <div class="${cls2}"><span class="automaCardBarText">${des2}</span></div>\
  <div class="${cls3}"><span class="automaCardBarText">${des3}</span></div>\
  <div class="${cls4}"><span class="automaCardBarText">${des4}</span></div>\
  </span></div></div>';
var jstpl_card_automa_tooltip ='<div class="card_tooltip ${type} ${cardType}"><span class="type type_${cardType}">${cardTypeDescription}</span><h1>${tooltipName}</h1><div class="text">${text}</div><div class="card_container"><div class="inner_element"><span class="card ${cssClass} card_${type} shadow"><span class="name">${name}</span>\
  <div class="${cls1}"><span class="automaCardBarText">${des1}</span></div>\
  <div class="${cls2}"><span class="automaCardBarText">${des2}</span></div>\
  <div class="${cls3}"><span class="automaCardBarText">${des3}</span></div>\
  <div class="${cls4}"><span class="automaCardBarText">${des4}</span></div>\
  </span></div></div></div>';

// History
var jstpl_card_history ='<div id="${elementId}" class="history ${topCssClass}" style="${position} ${style}" data-location="${location}" data-type="${type}" data-id="${id}" data-cardtype="${cardType}" data-x="${x}" data-y="${y}"><div class="inner_element"><span class="card ${cssClass} card_${type}" ><span class="name">${name}</span><span class="description">${description}</span></span></div><div class="history_player" style="color:#${player_color};">${player_name}<div class="history_moment">${historyMoment}</div></div>';
var jstpl_card_automa_history ='<div id="${elementId}" class="history ${topCssClass}" style="${position} ${style}" data-location="${location}" data-type="${type}" data-id="${id}" data-cardtype="${cardType}" data-x="${x}" data-y="${y}"><div class="inner_element"><span class="card ${cssClass} card_${type}" ><span class="name">${name}</span>\
  <div class="${cls1}"><span class="automaCardBarText">${des1}</span></div>\
  <div class="${cls2}"><span class="automaCardBarText">${des2}</span></div>\
  <div class="${cls3}"><span class="automaCardBarText">${des3}</span></div>\
  <div class="${cls4}"><span class="automaCardBarText">${des4}</span></div>\
  </span></div><div class="history_player" style="color:#${player_color};">${player_name}<div class="history_moment">${historyMoment}</div></div>';

// Vine Card
var jstpl_card_vine ='<div id="${elementId}" class="vine" style="${position} ${style}" data-location="${location}" data-type="${type}" data-id="${id}" data-cardtype="${cardType}" data-x="${x}" data-y="${y}"><div class="inner_element"><span class="vine_card ${cssClass} card_${type}" ><span class="name">${name}</span><span class="red">${red}</span><span class="white">${white}</span></span></div></div>';

// Mama & papa cards
var jstpl_card_mama_papa ='<div id="${elementId}" style="${position} ${style}" data-type="${type}" data-cardtype="${cardType}" data-id="${id}" data-x="${x}" data-y="${y}" class="card_wrapper"><div class="inner_element"><span class="mamaPapaCard ${cssClass} card_${type}" ><span class="title">${title}</span><span class="name">${name}</span></span></div></div>';
var jstpl_card_mama_papa_tooltip ='<div class="card_tooltip ${type}"><h1>${name}</h1><div class="text">${text}</div><div class="card_container"><div class="inner_element"><span class="mamaPapaCard ${cssClass} card_${type} shadow"><span class="title">${title}</span><span class="name">${name}</span></span></div></div></div>';
 
// Deck count cards
var jstpl_deck_count = '<div id="${elementId}" class="deck_count" style="${position}">${count}</div>';

// Board Header
var jstpl_turn_header ='<div><span class="board_header_turn">${turnLabel}: <span class="gr_value" data-binding="b.turn">${turn}</span></span> <span class="board_header_season">${seasonLabel}: <span class="gr_value" data-binding="b.seasonTr">${seasonTr}</span></span></div>';

// Choose papa option: view mama & papa
var jstpl_choose_papa_option_section = ' \
  <div id="choose_papa_option_section_wrapper">\
    ${mama}\
    ${papa}\
  </div>';

// Play Card Section
var jstpl_play_card = ' \
<div id="${elementId}" class="play_card">\
  <h3 class="play_card_name">${labelPlay} ${name}</h3>\
  <div class="play_card_description">${description}</div>\
</div>';

// Action panel
var jstpl_action = '\
  <div class="action-panel">\
        ${rows}\
      <div style="clear:both"></div>\
      <div id="action-preview-resources"></div>\
  </div>';

// Labels/text on board
var jstpl_label = ' \
  <div id="${elementId}" class="${cssClass}" style="${style}">${label}</div>';

var jstpl_player_selection='\
  <div class="player_selection"><span class="active_slot"><input id="chk_${id}" type="checkbox" value="${id}"></input>\
  <label for="chk_${id}"><span style="color:#${player_color};">${player_name}</span>\
  <span class="cc_counter"><span>${score}</span><span class="token small tokentext vp tooltipenable"></span></span>\
  <span class="cc_counter"><span>${lira}</span><span class="token small tokentext lira tooltipenable"></span></span>\
  <span class="cc_counter"><span>${residual_payment}</span><span class="token small tokentext residualPayment tooltipenable"></span></span>\
  <span class="cc_counter handCardsSummary"><span>${handCardsSummary}</span></span>\
  <span class="cc_counter workersSummary"><span>${workersSummary}</span></span>\
  </label></span>\
  </div>';

var jstpl_last_turn='\
  <div class="last_turn"><span class="last_turn_label">${labelEndTurn}</span> <span class="last_turn_label_players">${labelPlayers}<span> ${playersHtml}</div>';

var jstpl_last_turn_player='\
  <span class="player_last_turn"><span style="color:#${player_color};">${player_name}</span>\
  <span class="cc_counter"><span>${score}</span><span class="token small tokentext vp tooltipenable"></span></span>\
  </span>';

var jstpl_expandablesection = '\
  <div id="${id}">\
      <a href="#" id="toggle_${id}" class="expandabletoggle expandablearrow">\
          ${label} <span id="${id}_count">${count}</span> <div class="icon20"></div>\
      </a>\
      <div id="content_${id}" class="expandablecontent">\
          ${content}\
      </div>\
  </div>';

var jstpl_player_name_with_color='<span style="color:#${player_color};background-color:${background_color};">${player_name}</span>';
var jstpl_preferences='<div id="vit_preferences" class="vit_preferences">\
  <input id="vit_preference_100" type="checkbox" class="vit_preference" data-preference="100" value="2" data-value-unchecked="1"><label for="vit_preference_100">${label_preference_100}</label> | \
  <input id="vit_preference_101" type="checkbox" class="vit_preference" data-preference="101" value="1" data-value-unchecked="2"><label for="vit_preference_101">${label_preference_101}</label>\
  </div>';

</script>  

<div id="vit_game">


  <!--<div id="sample_tokens"> </div>-->

  <!--<div id="sample_cards"> </div>-->

  <div id="action_section" class="whiteblock hidden">
    <div id="action_section_inner">
    </div>
  </div>

  <div id="last_turn_section" class="whiteblock hidden"></div>
  <div id="choose_mama_papa_section" class="whiteblock hidden"><div id="choose_mama_papa_stock_wrapper"><div id="choose_mama_papa_stock"></div></div></div>
  <div id="choose_papa_option_section" class="whiteblock hidden"></div>

  <div id="choose_cards_section" class="whiteblock hidden"><div id="choose_cards_stock_wrapper"><div id="choose_cards_stock"></div></div></div>

  <div id="play_card_section" class="whiteblock hidden"></div>

  <div id="choose_players_section" class="whiteblock hidden"></div>

  <div id="vit_boards_wrapper">
    <div id="board-row">
      <div id="board-row-container">
        <div class="whiteblock"><div id="board" class="board shadow" style="position:relative"><div id='turn_header'></div></div><div id="boardLegend"></div></div>
      </div>
    </div>

    <div id="history_section" class="whiteblock"></div>

  </div>

  <div id="automa_cards_section" class="whiteblock hidden"><div id="automa_cards_stock_wrapper"><div id="automa_cards_stock"></div></div></div>

  <div id="soloModeInstructions" class="whiteblock hidden"></div>

</div>

<!--<div id="_deck"></div> -->


{OVERALL_GAME_FOOTER}
