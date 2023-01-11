<?php
require 'start.php';
$first_name_m = array("Jakub", "Jan", "Adam", "Tomas", "Matyas", "Filip", "Ondrej", "Vojtech", "Matej", "David", "Lukas", "Dominik", "Martin", "Daniel", "Simon", "Petr", "Stepan", "Antonin", "Jiri", "Marek");
$second_name_m = array("Novak", "Svoboda", "Novotny", "Dvorak", "Cerny", "Prochazka", "Kucera", "Vesely", "Horak", "Nemec", "Marek", "Pospisil", "Pokorny", "Hajek", "Kral", "Jelinek", "Ruzicka", "Benes", "Fiala", "Sedlacek");
$first_name_f = array("Eliska", "Tereza", "Anna", "Adela", "Natalie", "Ema", "Viktorie", "Sofie", "Karolina", "Kristyna", "Barbora", "Veronika", "Nela", "Lucie", "Julie", "Laura", "Katerina", "Marie", "Emma", "Klara");
$second_name_f = array("Novakova", "Svobodova", "Novotna", "Dvorakova", "Cerna", "Prochazkova", "Kucerova", "Vesela", "Horakova", "Nemcova", "Markova", "Pokorna", "Pospisilova", "Hajkova", "Kralova", "Jelinkova", "Ruzickova", "Benesova", "Fialova", "Sedlackova");
//$item = null;
$start = microtime(true);
//$y = $usersManager->FindAllActiveUsersOrderByLastNameAsc();
//print_r($y)
//create 98 users - random affil to 1-15
//create 10 cups - random affil to 1-15
//availability for each cup 300
//GetAvailabilityForCup(1-10) then foreach u, (u x 1-19)
//random UserStatsQuery
//random ClubStatQuery
$total = microtime(true) - $start;
echo $total;
?>