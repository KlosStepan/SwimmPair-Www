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

//https://www.proose.com/tools/php-fiddle

//Retrieve existing info
$ranks = $usersManager->FindAllRefereeRanks();
$clubs = $clubsManager->FindAllClubs();

echo ("SWIMMPAIR DUMMY DATA & BENCHMARK<br/>\r\n");
//Start script
$start = microtime(true);
$rt_so_far = 0;

//1. Create 98 Users - random affil to 1-15
echo ("1. Register Users #3-#100<br/>\r\n");
for($i=3;$i<=100;++$i)
{
    //echo ($i."\r\n");
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
    //$password = 12345;
    $rights_idx = array_rand($rights, 1);
    $rrid_idx = array_rand($ranks, 1);
    $club_idx = array_rand($clubs, 1);
    //echo "\$usersManager->RegisterUser(".$first_name.",".$last_name.",".$email.",12345,".$rights[$rights_idx].",".$ranks[$rrid_idx]->id.",".$clubs[$club_idx]->id.");<br/>\r\n";
    $usersManager->RegisterUser($first_name, $last_name, $email, 12345, $rights[$rights_idx], $ranks[$rrid_idx]->id, $clubs[$club_idx]->id);
}
$stop1 = microtime(true) - $start;
$rt_so_far = $stop1;
echo "Stop 1: $stop1 sec.<br/>\r\n";

//2. Create 12 Cups
echo ("2. Insert Cups #1-#12<br/>\r\n");
for($j=1;$j<=12;++$j)
{
    //echo ($j."\r\n");
    $cup_name_idx = array_rand($cups_names, 1);
    $club_idx = array_rand($clubs, 1);
    $content_idx = array_rand($content, 1);
    //echo "\$cupsManager->InsertNewCup(".$cups_names[$cup_name_idx]." ".rand(1, 8).".,2023-".str_pad($j, 2, '0', STR_PAD_LEFT)."-26, 2023-".str_pad($j, 2, '0', STR_PAD_LEFT)."-28,".$clubs[$club_idx]->id.",".$content[$content_idx].");<br/>\r\n";
    $cupsManager->InsertNewCup($cups_names[$cup_name_idx]." ".rand(1, 8), "2023-".str_pad($j, 2, '0', STR_PAD_LEFT)."-26", "2023-".str_pad($j, 2, '0', STR_PAD_LEFT)."-28", $clubs[$club_idx]->id, $content[$content_idx]);
}
$stop2 = microtime(true) - $start - $rt_so_far;
$rt_so_far = $stop2;
echo "Stop 2: $stop2 sec.<br/>\r\n";

//3. Fetch new Users and Cups (&positions)
echo ("3. Fetch Users and Cups (&Positions)<br/>\r\n");
$users = $usersManager->FindAllActiveUsersOrderByLastNameAsc();
$cups = $cupsManager->FindAllUpcomingCupsEarliestFirst();
$positions = $positionsManager->FindAllPositions();

$stop3 = microtime(true) - $start - $rt_so_far;
$rt_so_far = $stop3;
echo "Stop 3: $stop3 sec.<br/>\r\n";

//4. Create Availabilities
echo ("4. Insert Availability (20 per Cup)<br/>\r\n");
for($k=0; $k<count($cups); ++$k)
{
    $kk_avails=20;
    $user_idx = array_rand($users, $kk_avails);
    for($kk=0; $kk<$kk_avails; ++$kk)
    {
        $cupsManager->InsertNewAvailability($cups[$k]->id, $users[$user_idx[$kk]]->id, 1);
    }
}
$stop4 = microtime(true) - $start - $rt_so_far;
$rt_so_far = $stop4;
echo "Stop 4: $stop4 sec.<br/>\r\n";

//5. Create Pairings
echo ("5. Insert Pairing (availabilities 1 random pos. for each)<br/>\r\n");
for($l=0; $l<count($cups); ++$l)
{
    $avails = $usersManager->FindAllRegisteredUsersForTheCup($l+1);
    for($ll=0; $ll<count($avails); ++$ll)
    {
        $position_idx = array_rand($positions, 1);
        $cupsManager->InsertNewPairing(($l+1), $positions[$position_idx]->id, $avails[$ll]->id);
    }
}
$stop5 = microtime(true) - $start - $rt_so_far;
$rt_so_far = $stop5;
echo "Stop 5: $stop5 sec.<br/>\r\n";

//6. Queries for Clubs / Users in random order
echo("6. Call stats queries (20 either Clubs/Users stat queryings)<br/>\r\n");
for($m=1;$m<=20;++$m)
{
    if (rand(0, 1))
    {
        //echo "-user query $m-<br/>\r\n";
        $year = $cupsManager->GetMaximumCupYear();
        $user_idx = array_rand($users, 1);
        $personCupsCount = $usersManager->CountCupsAttendanceOfUserGivenYear($users[$user_idx]->id, $year);
        $stats = $usersManager->CountOverallStatisticsOfUserGivenYear($users[$user_idx]->id, $year);
        //echo $personCupsCount."<br/>\r\n";
        //print_r($stats);
    }
    else
    {
        //echo "-club query $m-<br/>\r\n";
        $year = $cupsManager->GetMaximumCupYear();
        $club_idx = array_rand($clubs, 1);
        $stats = $usersManager->CountClubSeasonalStatistics($clubs[$club_idx]->id, $year);
        //print_r($stats);
    }
}
$stop6 = microtime(true) - $start - $rt_so_far;
$rt_so_far = $stop6;
echo "Stop 6: $stop6 sec.<br/>\r\n";
//
$total = microtime(true) - $start;
echo "TOTAL RUNTIME: $total sec.<br/>\r\n";

?>