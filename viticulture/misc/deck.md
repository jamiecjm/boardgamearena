Deck
====

Locations
---------
- board: for workers
- choicePapas
- papa
- choiceMamas
- mama
- deckGreen
- deckYellow
- deckBlue
- deckPurple
- discardGreen
- discardYellow
- discardBlue
- discardPurple
- hand: for player cards, location_arg = playerId
- player: for tokens/workes/wines/grapes, location_arg = playerId
- playerOff: for tokens
- vine1: for green cards on vine 1
- vine2: for green cards on vine 2
- vine3: for green cards on vine 3

card types
----------
- worker: worker
- grande: grande worker
- trellis
- windmill
- irrigation
- yoke
- tastingRoom
- mediumCellar
- largeCellar
- cottage
- grapeRed: red grape: card progr arg: value
- grapeWhite: white grape: card progr arg: value
- wineRed: red wine: card progr arg: value
- wineWhite: red wine: card progr arg: value
- wineBlush: blush wine: card progr arg: value
- wineSparkling: sparkling wine: card progr arg: value
- greenCard: green cards
- yellowCard: yellow cards
- blueCard: blue cards
- purpleCard: purple cards

location_arg
------------

player board:
- 201: available grande worker
- 202: available worker1
- 203: available worker2
- 204: available worker3
- 205: available worker4
- 206: available worker5
- 301: built trellis
- 302: built windmill
- 303: built irrigation
- 304: built yoke
- 305: built tastingRoom
- 306: built mediumCellar
- 307: built largeCellar
- 308: built cottage
- 1: worker3
- 2: worker4
- 3: worker5
- 4: trellis
- 5: windmill
- 6: irrigation
- 7: yoke
- 8: tastingRoom
- 9: mediumCellar
- 10: largeCellar
- 11: cottage
- 401: yoke worker place (uprootVine_1 or harvestField_1)

board summer:
- 101: playYellowCard_1
- 102: playYellowCard_1 (+1 playYellowCard_1)
- 103: playYellowCard_1
- 111: drawGreenCard_1
- 111: drawGreenCard_1 (+1 drawGreenCard_1)
- 112: drawGreenCard_1
- 121: getLira_2
- 122: getLira_2 (+ getLira_1)
- 123: getLira_2
- 131: buildStructure_1
- 132: buildStructure_1 (+ getDiscountLira1)
- 133: buildStructure_1
- 141: sellGrapes_1 or buySellVine_1
- 142: sellGrapes_1 or buySellVine_1 (+ getVp_1)
- 143: sellGrapes_1 or buySellVine_1
- 151: plant_1
- 152: plant_1 (+1 plant_1)
- 153: plant_1

board winter:
- 301: drawPurpleCard_1
- 302: drawPurpleCard_1 (+ drawPurpleCard_1)
- 303: drawPurpleCard_1
- 311: harvestField_1
- 312: harvestField_1 (+ harvestField_1)
- 313: harvestField_1
- 321: trainWorker_1
- 322: trainWorker_1 (+ getDiscountLira1)
- 323: trainWorker_1
- 331: wineOrder_1
- 332: wineOrder_1 (+ getVp_1)
- 333: wineOrder_1
- 341: makeWine_2
- 342: makeWine_2 (+ makeWine_1)
- 343: makeWine_2
- 351: playBlueCard_1
- 352: playBlueCard_1 (+ playBlueCard_1)
- 353: playBlueCard_1

board all seasons:
- 901: getLira_1