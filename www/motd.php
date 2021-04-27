<?php
/*
================================================
Game server "Message of the Day" page - "motd.php"
================================================
*/

$motd_page = 1;
$motd_body = "";

// Include the primary PHP functions file
include("./common.php");

// Load outer template
$tpl = new Template('./templates/motd.tpl');

$tpl->set("header_extra", $header_extra); // Players served
$tpl->set("title", "Message Of The Day"); // Window title

$motd_header = getserversettingsvalue("motdheader");
if (strlen($motd_header) > 0)
  $tpl->set("motd_header", $motd_header);
else
  $tpl->set("motd_header", "Left 4 Dead Player Stats");

if (strlen($motd_message) > 0) {
  $tpl_msg = new Template('./templates/motd_message.tpl');
  $tpl_msg->set("motd_message", $motd_message);
  $tpl->set("motd_message", $tpl_msg->fetch('./templates/motd_message.tpl'));
}

$result = mysql_query("SELECT * FROM players ORDER BY " . $TOTALPOINTS . " DESC LIMIT 10");
if ($result && mysql_num_rows($result) > 0) {
  $top10 = array();
  $i = 1;

  while ($row = mysql_fetch_array($result)) {
    // This character is A PAIN... Find out how to convert it in to a HTML entity!
    // http://www.fileformat.info/info/unicode/char/06d5/index.htm
    // Maybe it's the same with all Arabic characters???? From right to left type of writing.

    $name = htmlentities($row['name'], ENT_COMPAT, "UTF-8");
    //$name = str_replace("" , "&#1749;", $name);
    //$titlename = str_replace("\"" , "\\\"", $name);

    // PHP 7.4 COUNTRY FIX BY PRIMEAS.DE
    $country_record = $geoip->country($row['ip']);

    $top10[] = array("rank" => $i++,
                     "flag" => "<img src=\"images/flags/" . strtolower($country_record->country->isoCode) . ".gif\" alt=\"" . strtolower($country_record->country->isoCode) . "\"> ",
                     "name" => $name,
                     "score" => gettotalpointsraw($row));
  }

  $tpl_top10 = new Template('./templates/motd_top10.tpl');
  $tpl_top10->set("motd_top10", $top10);
  $tpl->set("motd_top10", $tpl_top10->fetch('./templates/motd_top10.tpl'));
}

$real_playtime_sql = $TOTALPLAYTIME;
$real_playtime = "real_playtime";
$real_points_sql = $TOTALPOINTS;
$real_points = "real_points";
$extrasql = ", " . $real_points_sql . " as " . $real_points . ", " . $real_playtime_sql . " as " . $real_playtime;

$query = "SELECT *" . $extrasql . " FROM players WHERE (" . $real_playtime_sql . ") >= " . $award_minplaytime . " ORDER BY (" . $real_points . " / " . $real_playtime . ") DESC LIMIT 5";
$result = mysql_query($query);

if ($result && mysql_num_rows($result) > 0) {
  $topppm = array();
  $i = 1;

  while ($row = mysql_fetch_array($result)) {
    // This character is A PAIN... Find out how to convert it in to a HTML entity!
    // http://www.fileformat.info/info/unicode/char/06d5/index.htm
    // Maybe it's the same with all Arabic characters???? From right to left type of writing.

    $name = htmlentities($row['name'], ENT_COMPAT, "UTF-8");
    //$name = str_replace("" , "&#1749;", $name);
    //$titlename = str_replace("\"" , "\\\"", $name);

    // PHP 7.4 COUNTRY FIX BY PRIMEAS.DE
    $country_record = $geoip->country($row['ip']);

    $topppm[] = array("rank" => $i++,
                     "flag" => "<img src=\"images/flags/" . strtolower($country_record->country->isoCode) . ".gif\" alt=\"" . strtolower($country_record->country->isoCode) . "\"> ",
                     "name" => $name,
                     "score" => $row[$real_points] / $row[$real_playtime]);
  }

  $tpl_topppm = new Template('./templates/motd_topppm.tpl');
  $tpl_topppm->set("motd_topppm", $topppm);
  $tpl->set("motd_topppm", $tpl_topppm->fetch('./templates/motd_topppm.tpl'));
}

// Print out the page!
echo $tpl->fetch('./templates/motd.tpl');
?>
