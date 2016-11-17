<?php
$drinks = array(
    'KICKED UP BLOODY MARY',
'TROPICAL CRUSH',
'AUTUMN SUNSET',
'A PIECE OF PARADISE',
'WHITE PEACH SANGRIA',
'JACK’S LONG ISLAND ICED TEA',
'PURPLE RUSH MARGARITA',
'TEQUILA SOUR',
'THE RIDER',
'PINK SHANDY',
'THE ANGRY APPLE',
'BLUEBERRY COOLER',
);

foreach($drinks as $drink){
    print ucwords(strtolower($drink)) . ', ';
}
