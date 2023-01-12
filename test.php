<?php
require 'start.php';
//Dummy data to be fed into script
$first_name_m = array("Jakub", "Jan", "Adam", "Tomas", "Matyas", "Filip", "Ondrej", "Vojtech", "Matej", "David", "Lukas", "Dominik", "Martin", "Daniel", "Simon", "Petr", "Stepan", "Antonin", "Jiri", "Marek");
$second_name_m = array("Novak", "Svoboda", "Novotny", "Dvorak", "Cerny", "Prochazka", "Kucera", "Vesely", "Horak", "Nemec", "Marek", "Pospisil", "Pokorny", "Hajek", "Kral", "Jelinek", "Ruzicka", "Benes", "Fiala", "Sedlacek");
$first_name_f = array("Eliska", "Tereza", "Anna", "Adela", "Natalie", "Ema", "Viktorie", "Sofie", "Karolina", "Kristyna", "Barbora", "Veronika", "Nela", "Lucie", "Julie", "Laura", "Katerina", "Marie", "Emma", "Klara");
$second_name_f = array("Novakova", "Svobodova", "Novotna", "Dvorakova", "Cerna", "Prochazkova", "Kucerova", "Vesela", "Horakova", "Nemcova", "Markova", "Pokorna", "Pospisilova", "Hajkova", "Kralova", "Jelinkova", "Ruzickova", "Benesova", "Fialova", "Sedlackova");
$emails = array("centrum.cz", "seznam.cz", "email.cz", "gmail.com", "hotmail.com", "pokec.sk");
$rights = array("1", "0");
//-
$cups_names = array("O pohar Starosty", "Vyrocni turnaj", "Pravidelny turnaj", "Pohar mesta", "Mokry Eman", "Kondicni turnaj", "Oslava zalozeni mesta", "Vyroci rekonstrukce radnice", "Ostavy zalozeni klubu", "Vyroci postaveni divadla", "Vyroci zalozeni kostela", "O pohar mistostarosty", "O pohar predsedy sportovni komise", "O pohar hejtmana", "K vyroci zalozeni krajske tradice", "K zalozeni plavecke tradice v kraji");
$content = array("Pellentesque lacinia mollis pharetra. Praesent sit amet ligula vehicula, faucibus ante non, posuere lorem. Vestibulum vitae purus imperdiet, scelerisque arcu.", "Vestibulum semper dui quis libero pellentesque cursus. Donec orci ex, vulputate eu pretium eu, elementum at lacus. Aenean auctor hendrerit.", "Curabitur lacus sapien, porttitor in dictum at, consectetur et odio. Fusce maximus, tellus vel egestas gravida, elit nisi elementum ligula.", "Nulla vel diam elit. Curabitur consectetur tempor nunc. Maecenas pretium mattis vestibulum. Nunc finibus vehicula semper. Suspendisse pellentesque bibendum lorem.", "Aliquam euismod mollis sagittis. Nulla facilisi. Morbi nec sapien arcu. Donec hendrerit velit at turpis suscipit vestibulum. Fusce non congue.", "Duis euismod auctor ipsum. Integer sed facilisis odio, at scelerisque urna. Ut sed aliquam turpis. Pellentesque eget luctus velit, non.", "Suspendisse malesuada dui sit amet sapien tristique, sed tincidunt quam posuere. Mauris rhoncus placerat magna, id rhoncus libero euismod id.", "In sagittis elementum lorem, in convallis odio pretium ac. Fusce et nunc nisi. Mauris vestibulum erat ante, vel dictum lacus.", "Integer varius magna nec orci efficitur vehicula. Vivamus consequat lectus sed pharetra semper. Quisque placerat rutrum blandit. Etiam et magna.", "Sed lacus neque, viverra sed metus vitae, malesuada aliquam est. Aenean est dui, consequat non rutrum in, volutpat vel lacus.");
//$y = $usersManager->FindAllActiveUsersOrderByLastNameAsc();
//print_r($y)

//Start script
$start = microtime(true);
//Retrieve existing info
$ranks = $usersManager->FindAllRefereeRanks();
$clubs = $clubsManager->FindAllClubs();
//create 98 users - random affil to 1-15
//https://www.proose.com/tools/php-fiddle
echo ("Users #3-#100<br/>\r\n");
for($i=3;$i<=100;++$i)
{
    echo ($i."\r\n");
    $first_name;
    $last_name;
    if (rand(0, 1))
    {
        $first_name = $first_name_m[array_rand($first_name_m, 1)];
        $last_name = $second_name_m[array_rand($second_name_m, 1)];
    }
    else
    {
        $first_name = $first_name_f[array_rand($first_name_f, 1)];
        $last_name = $second_name_f[array_rand($second_name_f, 1)];
    }
    $email_idx = array_rand($emails, 1);
    $email = strtolower($first_name).strtolower($last_name)."@".$emails[$email_idx];
    //$password = "...";
    $rights_idx = array_rand($rights, 1);
    $rrid_idx = array_rand($ranks, 1);
    $club_idx = array_rand($clubs, 1);
    echo "\$usersManager->CreateUser(".$first_name.",".$last_name.",".$email.",12345,".$rights[$rights_idx].",".$ranks[$rrid_idx]->id.",".$clubs[$club_idx]->id.");<br/>\r\n";
}
echo ("Clubs #1-#12<br/>\r\n");
for($j=1;$j<=12;++$j)
{
    echo ($j."\r\n");
    $cup_name_idx = array_rand($cups_names, 1);
    $club_idx = array_rand($clubs, 1);
    $content_idx = array_rand($content, 1);
    echo "\$clubsManager->InsertNewCup(".$cups_names[$cup_name_idx]." ".rand(1, 8).".,2023-".str_pad($j, 2, '0', STR_PAD_LEFT)."-26, 2023-".str_pad($j, 2, '0', STR_PAD_LEFT)."-28,".$clubs[$club_idx]->id.",".$content[$content_idx].");<br/>\r\n";
}
echo ("Availability XX<br/>\r\n");
for($k=1;$k<=5;++$k)
{
    echo ("avail $k<br/>\r\n");
}
echo ("Pairing YY<br/>\r\n");
for($l=1;$l<=5;++$l)
{
    echo ("pairing $l<br/>\r\n");
}
//10 random queries Club(1q)/User(2qs) stats (uid, year)
for($m=1;$m<=10;++$m)
{
    echo ("Q #$l<br/>\r\n");
}
//availability for each cup 300

//foreach Cups as cup
//for 1 to 30 random user
//available(cup, person, 1)

//foreach Cups as cup
//getAvailableUsers(cup)
//foreach availableUser as avlusr
//pairing(cip, avlusr-id, positions[position-idx])

//GetAvailabilityForCup(1-10) then foreach u, (u x 1-19)
//random UserStatsQuery
//random ClubStatQuery
$total = microtime(true) - $start;
echo $total;
echo "<br/>\r\n";

//$club_idx = array_rand($clubs, 1);
//print_r($clubs[$club_idx]->id);
//echo "<br/>\r\n";
//print_r($clubs[$club_idx]);
//echo "<br/>\r\n";
?>