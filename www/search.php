<?php
/*
================================================
LEFT 4 DEAD AND LEFT 4 DEAD 2 PLAYER RANK
Copyright (c) 2010 Mikko Andersson
================================================
Player Search page - "search.php"
================================================
*/

// Include the primary PHP functions file
include("./common.php");

// PHP 7.4 COUNTRY FIX BY PRIMEAS.DE
require_once("geoip2.phar");
use GeoIp2\Database\Reader;
$geoip = new Reader('GeoLite2-Country.mmdb');
error_reporting(0);

// Load outer template
$tpl = new Template("./templates/" . $templatefiles['layout.tpl']);

// Set Steam ID as var, and quit on hack attempt
$searchstring = mysql_real_escape_string($_POST['search']);
if ($searchstring."" == "") $searchstring = md5("nostring");

setcommontemplatevariables($tpl);

$tpl->set("title", "Player Search"); // Window title
$tpl->set("page_heading", "Player Search"); // Page header

$result = mysql_query("SELECT * FROM players WHERE name LIKE '%" . $searchstring . "%' OR steamid LIKE '%" . $searchstring . "%' ORDER BY points + points_survivors + points_infected DESC LIMIT 100");
if (mysql_error()) {
  $output = "<p><b>MySQL Error:</b> " . mysql_error() . "</p>\n";
} else {
  $arr_online = array();
  $stats = new Template("./templates/" . $templatefiles['search.tpl']);

  $i = 1;
  while ($row = mysql_fetch_array($result))
  {

    // PHP 7.4 COUNTRY FIX BY PRIMEAS.DE
    $country_record = $geoip->country($row['ip']);

    $line = createtablerowtooltip($row, $i);
    $line .= "<td><img src=\"images/flags/" . strtolower($country_record->country->isoCode) . ".gif\" alt=\"" . strtolower($country_record->country->isoCode) . "\"> <a href=\"player.php?steamid=" . $row['steamid']. "\">" . htmlentities($row['name'], ENT_COMPAT, "UTF-8") . "</a></td>";
    $line .= "<td>" . gettotalpoints($row) . "</td><td>" . gettotalplaytime($row) . "</td></tr>\n";

    $i++;
    $arr_online[] = $line;
  }

  if (mysql_num_rows($result) == 0) $arr_online[] = "<tr><td colspan=\"3\" align=\"center\">There are no players that matched!</td</tr>\n";
  $stats->set("online", $arr_online);
  $output = $stats->fetch("./templates/" . $templatefiles['search.tpl']);
}

$tpl->set('body', trim($output));

// Output the top10
$tpl->set("top10", $top10);

// Output the MOTD
$tpl->set("motd_message", $layout_motd);

// Print out the page!
echo $tpl->fetch("./templates/" . $templatefiles['layout.tpl']);
?>
