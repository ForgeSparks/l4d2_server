<?php
/*
================================================
Common PHP functions and code - "common.php"
================================================
*/

require_once('config.php');
require_once('mysql.php');

require_once("geoip2.phar");
use GeoIp2\Database\Reader;
$geoip = new Reader('GeoLite2-Country.mmdb');

// Include Template engine class
include("./class_template.php");

function php_scandir($dir, $listDirectories=false, $skipDots=true) {
  $dirArray = array();
  if ($handle = opendir($dir)) {
    while (false !== ($file = readdir($handle))) {
      if ((($file == "." || $file == "..") && !$skipDots) || ($file != "." && $file != "..")) {
        if (!$listDirectories && is_dir($file))
          continue;

        array_push($dirArray, basename($file));
      }
    }
    closedir($handle);
  }

  return $dirArray;
}

function getfriendid($pszAuthID) {
  $iServer = "0";
  $iAuthID = "0";

  $szAuthID = $pszAuthID;

  $szTmp = strtok($szAuthID, ":");

  while(($szTmp = strtok(":")) !== false) {
    $szTmp2 = strtok(":");
    if ($szTmp2 !== false) {
      $iServer = $szTmp;
      $iAuthID = $szTmp2;
    }
  }

  if ($iAuthID == "0")
    return "0";

  $i64friendID = bcmul($iAuthID, "2");

  //Friend ID's with even numbers are the 0 auth server.
  //Friend ID's with odd numbers are the 1 auth server.
  $i64friendID = bcadd($i64friendID, bcadd("76561197960265728", $iServer));

  return $i64friendID;
}

function formatage($date) {
  $nametable = array(" seconds", " minutes", " hours", " days", " weeks", " months", " years");
  $agetable = array("60", "60", "24", "7", "4", "12", "10");
  $ndx = 0;

  while ($date > $agetable[$ndx]) {
    $date = $date / $agetable[$ndx];
    $ndx++;
    next($agetable);
  }

  return number_format($date, 2).$nametable[$ndx];
}

function getpopulation($population, $file) {
  $cityarr = array();
  $page = fopen($file, "r");
  while (($data = fgetcsv($page, 1000, ",")) !== FALSE) {
    $cityarr[$data[1]] = $data[2];
  }

  fclose($page);
  asort($cityarr, SORT_NUMERIC);

  $returncity = "None";
  $returncity2 = "The Earth";

  foreach ($cityarr as $city => $pop) {
    if ($population > $pop)
      $returncity = $city;
    else {
      $returncity2 = $city;
      break;
    }
  }

  $return = array($returncity, $cityarr[$returncity], $returncity2, $cityarr[$returncity2]);

  return $return;
}

function gettotalpointsraw($row)
{
  $totalpoints = 0;

  $totalpoints = $row['points'] + $row['points_realism'] + $row['points_survivors'] + $row['points_infected'] + $row['points_survival'] + $row['points_scavenge_survivors'] + $row['points_scavenge_infected'] + $row['points_realism_survivors'] + $row['points_realism_infected'] + $row['points_mutations'];

  return $totalpoints;
}

function gettotalpoints($row)
{
  return number_format(gettotalpointsraw($row));
}

function gettotalplaytimecalc($row)
{
  return $row['playtime'] + $row['playtime_realism'] + $row['playtime_versus'] + $row['playtime_survival'] + $row['playtime_scavenge'] + $row['playtime_realismversus'] + $row['playtime_mutations'];
}

function gettotalplaytime($row)
{
  return getplaytime(gettotalplaytimecalc($row));
}

function getplaytime($minutes)
{
  return formatage($minutes * 60) . " (" . number_format($minutes) . " min)";
}

function getppm($__points, $__playtime)
{
  //echo "\$__points=" . $__points . "<br>";
  //echo "\$__playtime=" . $__playtime . "<br>";
  if ($__points != 0 && $__playtime != 0)
    return $__points / $__playtime;

  return 0.0;
}

function getserversettingsvalue($name)
{
  $q = "SELECT svalue FROM server_settings WHERE sname = '" . mysql_real_escape_string($name) . "'";
  $res = mysql_query($q);

  if ($res && mysql_num_rows($res) == 1 && ($r = mysql_fetch_array($res)))
    return $r['svalue'];

  return "";
}

function setcommontemplatevariables($template)
{
  global $header_extra, $site_name, $playercount, $realismlink, $realismversuslink, $mutationslink, $scavengelink, $realismcmblink, $realismversuscmblink, $mutationscmblink, $scavengecmblink, $timedmapslink, $templatefiles;

  $template->set("header_extra", $header_extra); // Players served
  $template->set("site_name", $site_name); // Site name

  $template->set("realismlink", $realismlink); // Realism stats link
  $template->set("realismversuslink", $realismversuslink); // Realism Versus stats link
  $template->set("mutationslink", $mutationslink); // Mutations stats link
  $template->set("scavengelink", $scavengelink); // Scavenge stats link

  $template->set("realismcmblink", $realismcmblink); // Realism stats link
  $template->set("realismversuscmblink", $realismversuscmblink); // Realism Versus stats link
  $template->set("mutationscmblink", $mutationscmblink); // Mutations stats link
  $template->set("scavengecmblink", $scavengecmblink); // Scavenge stats link

  $template->set("stylesheet", $templatefiles['style.css']); // Stylesheet for the page
  $template->set("statstooltip", $templatefiles['statstooltip.js']); // Tooltip javascript file
  $template->set("statscombobox", $templatefiles['statscombobox.js']); // Combobox javascript file

  $template->set("timedmapslink", $timedmapslink); // Timed maps stats link
}

function createtablerowtooltip($row, $i)
{
  $points = $row['points'];
  $totalpoints = gettotalpoints($row);
  $points_coop = number_format($points);
  $points_realism = number_format($row['points_realism']);
  $points_versus = number_format($row['points_survivors'] + $row['points_infected']);
  $points_versus_sur = number_format($row['points_survivors']);
  $points_versus_inf = number_format($row['points_infected']);
  $points_survival = number_format($row['points_survival']);
  $points_scavenge = number_format($row['points_scavenge_survivors'] + $row['points_scavenge_infected']);
  $points_scavenge_sur = number_format($row['points_scavenge_survivors']);
  $points_scavenge_inf = number_format($row['points_scavenge_infected']);
  $points_realismversus = number_format($row['points_realism_survivors'] + $row['points_realism_infected']);
  $points_realismversus_sur = number_format($row['points_realism_survivors']);
  $points_realismversus_inf = number_format($row['points_realism_infected']);
  $points_mutations = number_format($row['points_mutations']);
  $totalplaytime = gettotalplaytime($row);
  $playtime_coop = getplaytime($row['playtime']);
  $playtime_realism = getplaytime($row['playtime_realism']);
  $playtime_versus = getplaytime($row['playtime_versus']);
  $playtime_survival = getplaytime($row['playtime_survival']);
  $playtime_scavenge = getplaytime($row['playtime_scavenge']);
  $playtime_realismversus = getplaytime($row['playtime_realismversus']);
  $playtime_mutations = getplaytime($row['playtime_mutations']);

  $ppm_coop = number_format(getppm($points, $row['playtime']), 2);
  $ppm_versus = number_format(getppm($row['points_survivors'] + $row['points_infected'], $row['playtime_versus']), 2);
  $ppm_survival = number_format(getppm($row['points_survival'], $row['playtime_survival']), 2);
  $ppm_realism = number_format(getppm($row['points_realism'], $row['playtime_realism']), 2);
  $ppm_scavenge = number_format(getppm($row['points_scavenge_survivors'] + $row['points_scavenge_infected'], $row['playtime_scavenge']), 2);
  $ppm_realismversus = number_format(getppm($row['points_realism_survivors'] + $row['points_realism_infected'], $row['playtime_realismversus']), 2);
  $ppm_mutations = number_format(getppm($row['points_mutations'], $row['playtime_mutations']), 2);

  return "<tr onmouseover=\"showtip('<b>Coop: " . $points_coop . " (PPM: " . $ppm_coop . ")<br>Realism: " . $points_realism . " (PPM: " . $ppm_realism . ")<br>Mutations: " . $points_mutations . " (PPM: " . $ppm_mutations . ")<br>Survival: " . $points_survival . " (PPM: " . $ppm_survival . ")<br>Versus: " . $points_versus . " (PPM: " . $ppm_versus . ")</b><br>&nbsp;&nbsp;Survivors: " . $points_versus_sur . "<br>&nbsp;&nbsp;Infected: " . $points_versus_inf . "<br><b>Scavenge: " . $points_scavenge . " (PPM: " . $ppm_scavenge . ")</b><br>&nbsp;&nbsp;Survivors: " . $points_scavenge_sur . "<br>&nbsp;&nbsp;Infected: " . $points_scavenge_inf . "<br><b>Realism&nbsp;Versus: " . $points_realismversus . " (PPM: " . $ppm_realismversus . ")</b><br>&nbsp;&nbsp;Survivors: " . $points_realismversus_sur . "<br>&nbsp;&nbsp;Infected: " . $points_realismversus_inf . "<br><b>Playtime: " . $totalplaytime . "</b><br>&nbsp;&nbsp;Coop: " . $playtime_coop . "<br>&nbsp;&nbsp;Realism: " . $playtime_realism . "<br>&nbsp;&nbsp;Survival: " . $playtime_survival . "<br>&nbsp;&nbsp;Versus: " . $playtime_versus . "<br>&nbsp;&nbsp;Scavenge: " . $playtime_scavenge . "<br>&nbsp;&nbsp;Realism&nbsp;Versus: " . $playtime_realismversus . "<br>&nbsp;&nbsp;Mutations: " . $playtime_mutations . "');\" onmouseout=\"hidetip();\"" . (($i & 1) ? ">" : " class=\"alt\">");
}

function parseplayersummary($profilexml)
{
  return parseplayerprofile($profilexml, "/profile/summary");
}

function parseplayerheadline($profilexml)
{
  return parseplayerprofile($profilexml, "/profile/headline");
}

function parseplayername($profilexml)
{
  return parseplayerprofile($profilexml, "/profile/steamID");
}

function parseplayerhoursplayed2wk($profilexml) {
  return parseplayerprofile($profilexml, '/profile/hoursPlayed2Wk');
}

function parseplayersteamrating($profilexml)
{
  return parseplayerprofile($profilexml, "/profile/steamRating");
}

function parseplayermembersince($profilexml)
{
  return parseplayerprofile($profilexml, "/profile/memberSince");
}

function parseplayerprivacystate($profilexml)
{
  return parseplayerprofile($profilexml, "/profile/privacyState");
}

/*
*	Parse player Steam profile
*	Parameters:
*		profilexml	- [SimpleXMLElement] Player Steam profile XML
*		xpathnode		- [String] XML path for the node
*	Returns:
*		[String] XML Node value
*/
function parseplayerprofile($profilexml, $xpathnode) {
  $arr = $profilexml->xpath($xpathnode);

  if (!$arr || count($arr) != 1)
    return '';

  return ''.$arr[0];
}

/*
*	Get player avatar
*	Parameters:
*		profilexml	- [SimpleXMLElement] Player Steam profile XML
*		avatarsize	- [String] icon / medium / full
*	Returns:
*		[String] Image URL
*/
function parseplayeravatar($profilexml, $avatarsize)
{
  if (!$profilexml || !$avatarsize)
  {
    return "";
  }

  $retval = "";

  switch (strtolower($avatarsize))
  {
    case "icon":
      if ($profilexml->avatarIcon)
      {
        $retval = $profilexml->avatarIcon;
      }
      break;

    case "medium":
      if ($profilexml->avatarMedium)
      {
        $retval = $profilexml->avatarMedium;
      }
      break;

    case "full":
      if ($profilexml->avatarFull)
      {
        $retval = $profilexml->avatarFull;
      }
      break;
  }

  return $retval;
}

/*
*	Get player avatar
*	Parameters:
*		steamid			- [String] Player Steam ID
*		avatarsize	- [String] icon / medium / full
*	Returns:
*		[String] Image URL
*/
function getplayeravatar($steamid, $avatarsize)
{
  if (!$steamid || !$avatarsize)
  {
    return "";
  }

  $xml = getplayersteamprofilexml($steamid);
  return parseplayeravatar($xml, $avatarsize);
}

/*
*	Get player Steam profile XML
*	Parameters:
*		steamid			- [String] Player Steam ID
*	Returns:
*		[SimpleXMLElement] Steam profile XML
*/
function getplayersteamprofilexml($steamid) {
  if (!$steamid)
    return;

  return simplexml_load_file('http://steamcommunity.com/profiles/'.getfriendid($steamid).'?xml=1');
}

/*
Database fields
*/

$TOTALPOINTS = "points + points_survivors + points_infected + points_survival + points_realism + points_scavenge_survivors + points_scavenge_infected + points_realism_survivors + points_realism_infected + points_mutations";
$TOTALPLAYTIME = "playtime + playtime_versus + playtime_survival + playtime_realism + playtime_scavenge + playtime_realismversus + playtime_mutations";

$templatesdir = "./templates";
$templatesdir_default = $templatesdir . "/default";
$imagesdir_default = $templatesdir_default . "/images";

if (!file_exists($imagesdir_default) || !is_dir($imagesdir_default))
{
  echo "Webstats installation is incomplete. Download and install all the files again.<br />\n";
  exit;
}

if ($site_template != "" && $site_template != "default" && (!file_exists($templatesdir . "/" . $site_template) || !is_dir($templatesdir . "/" . $site_template)))
{
  echo "Webstats \"" . $site_template . "\" template path not found. Reconfigurations required!<br />\n";
  exit;
}

if (!function_exists('file_put_contents')) {
  function file_put_contents($filename, $data) {
    $f = @fopen($filename, 'w');
    if (!$f) {
      return false;
    } else {
      $bytes = fwrite($f, $data);
      fclose($f);
      return $bytes;
    }
  }
}

if (basename($_SERVER['PHP_SELF']) !== "createtable.php" && basename($_SERVER['PHP_SELF']) !== "updatetable.php" && basename($_SERVER['PHP_SELF']) !== "install.php") {
  if (file_exists("./install.php")) {
    echo "Delete the file <b>install.php</b> before running webstats!<br />\n";
    exit;
  }
  if (file_exists("./createtable.php")) {
    echo "Delete the file <b>createtable.php</b> before running webstats!<br />\n";
    exit;
  }
  if (file_exists("./updatetable.php")) {
    echo "Delete the file <b>updatetable.php</b> before running webstats!<br />\n";
    exit;
  }
}

$con_main = mysql_connect($mysql_server, $mysql_user, $mysql_password);
mysql_select_db($mysql_db, $con_main);
mysql_query("SET NAMES 'utf8'", $con_main);
$coop_campaigns = array();
$versus_campaigns = array();
$realism_campaigns = array();
$survival_campaigns = array();
$scavenge_campaigns = array();
$realismversus_campaigns = array();
$mutations_campaigns = array();

$coop_campaigns = array("c1m" => "Dead Center",
           "c2m" => "Dark Carnival",
           "c3m" => "Swamp Fever",
           "c4m" => "Hard Rain",
           "c5m" => "The Parish",
           "c6m" => "The Passing",
           "c7m" => "The Sacrifice",
           "c8m" => "No Mercy",
           "c9m" => "Crash Course",
           "c10m" => "Death Toll",
           "c11m" => "Dead Air",
           "c12m" => "Blood Harvest",
           "c13m" => "Cold Stream",
           "c14m" => "The Last Stand",
           "" => "Custom Maps");

$versus_campaigns = array("c1m" => "Dead Center",
           "c2m" => "Dark Carnival",
           "c3m" => "Swamp Fever",
           "c4m" => "Hard Rain",
           "c5m" => "The Parish",
           "c6m" => "The Passing",
           "c7m" => "The Sacrifice",
           "c8m" => "No Mercy",
           "c9m" => "Crash Course",
           "c10m" => "Death Toll",
           "c11m" => "Dead Air",
           "c12m" => "Blood Harvest",
           "c13m" => "Cold Stream",
           "c14m" => "The Last Stand",
           "" => "Custom Maps");

$survival_campaigns = array("c1m" => "Dead Center",
           "c2m" => "Dark Carnival",
           "c3m" => "Swamp Fever",
           "c4m" => "Hard Rain",
           "c5m" => "The Parish",
           "c6m" => "The Passing",
           "c7m" => "The Sacrifice",
           "c8m" => "No Mercy",
           "c9m" => "Crash Course",
           "c10m" => "Death Toll",
           "c11m" => "Dead Air",
           "c12m" => "Blood Harvest",
           "c13m" => "Cold Stream",
           "c14m" => "The Last Stand",
           "" => "Custom Maps");

$scavenge_campaigns = array("c1m" => "Dead Center",
           "c2m" => "Dark Carnival",
           "c3m" => "Swamp Fever",
           "c4m" => "Hard Rain",
           "c5m" => "The Parish",
           "c6m" => "The Passing",
           "c7m" => "The Sacrifice",
           "c8m" => "No Mercy",
           "c9m" => "Crash Course",
           "c10m" => "Death Toll",
           "c11m" => "Dead Air",
           "c12m" => "Blood Harvest",
           "c13m" => "Cold Stream",
           "c14m" => "The Last Stand",
           "" => "Custom Maps");

$realism_campaigns = array("c1m" => "Dead Center",
           "c2m" => "Dark Carnival",
           "c3m" => "Swamp Fever",
           "c4m" => "Hard Rain",
           "c5m" => "The Parish",
           "c6m" => "The Passing",
           "c7m" => "The Sacrifice",
           "c8m" => "No Mercy",
           "c9m" => "Crash Course",
           "c10m" => "Death Toll",
           "c11m" => "Dead Air",
           "c12m" => "Blood Harvest",
           "c13m" => "Cold Stream",
           "c14m" => "The Last Stand",
           "" => "Custom Maps");

$realismversus_campaigns = array("c1m" => "Dead Center",
           "c2m" => "Dark Carnival",
           "c3m" => "Swamp Fever",
           "c4m" => "Hard Rain",
           "c5m" => "The Parish",
           "c6m" => "The Passing",
           "c7m" => "The Sacrifice",
           "c8m" => "No Mercy",
           "c9m" => "Crash Course",
           "c10m" => "Death Toll",
           "c11m" => "Dead Air",
           "c12m" => "Blood Harvest",
           "c13m" => "Cold Stream",
           "c14m" => "The Last Stand",
           "" => "Custom Maps");

$mutations_campaigns = array("c1m" => "Dead Center",
           "c2m" => "Dark Carnival",
           "c3m" => "Swamp Fever",
           "c4m" => "Hard Rain",
           "c5m" => "The Parish",
           "c6m" => "The Passing",
           "c7m" => "The Sacrifice",
           "c8m" => "No Mercy",
           "c9m" => "Crash Course",
           "c10m" => "Death Toll",
           "c11m" => "Dead Air",
           "c12m" => "Blood Harvest",
           "c13m" => "Cold Stream",
           "c14m" => "The Last Stand",
           "" => "Custom Maps");

$realismlink = "";
$scavengelink = "";
$realismversuslink = "";
$mutationslink = "";
$realismcmblink = "";
$scavengecmblink = "";
$realismversuscmblink = "";
$mutationscmblink = "";

$realismlink = "<a href=\"maps.php?type=realism\">Realism Stats</a>";
$scavengelink = "<a href=\"maps.php?type=scavenge\">Scavenge Stats</a>";
$realismversuslink = "<a href=\"maps.php?type=realismversus\">Realism&nbsp;Versus Stats</a>";
$mutationslink = "<a href=\"maps.php?type=mutations\">Mutations</a>";

$realismcmblink = str_replace("\"", "&quot;", $realismlink) . "<br>";
$scavengecmblink = str_replace("\"", "&quot;", $scavengelink) . "<br>";
$realismversuscmblink = str_replace("\"", "&quot;", $realismversuslink) . "<br>";
$mutationscmblink = str_replace("\"", "&quot;", $mutationslink) . "<br>";

$realismlink = "<li>" . $realismlink . "</li>";
$scavengelink = "<li>" . $scavengelink . "</li>";
$scavengelink = "<li>" . $scavengelink . "</li>";
$mutationslink = "<li>" . $mutationslink . "</li>";

$timedmapslink = "";

if ($timedmaps_show_all)
{
  $timedmapslink = "<li><a href=\"timedmaps.php\">Timed Maps</a></li>";
}

$header_extra = array();
$header_extra['Zombies Killed'] = 0;
$header_extra['Players Served'] = 0;
$result = mysql_query("SELECT COUNT(*) AS players_served, sum(kills) AS total_kills FROM players");
if ($result && $row = mysql_fetch_array($result))
{
  $header_extra['Zombies Killed'] = $row['total_kills'];
  $header_extra['Players Served'] = $row['players_served'];
}

$i = 1;
$top10 = array();

$result = mysql_query('SELECT * FROM players ORDER BY '.$TOTALPOINTS.' DESC LIMIT 10');
if ($result && mysql_num_rows($result) > 0) {
  while ($row = mysql_fetch_array($result)) {
    $name = htmlentities($row['name'], ENT_COMPAT, 'UTF-8');
    $avatarimg = '';
    $playerheadline = '';

    if ($steam_profile_read && $i <= $top10players_additional_info) {
      $playersteamprofile = getplayersteamprofilexml($row['steamid']);

      if ($playersteamprofile) {
        $avatarimgurl = parseplayeravatar($playersteamprofile, 'icon');

        if ($avatarimgurl)
          $avatarimg = '<img src="'.$avatarimgurl.'" border="0">';
        $playerheadline = htmlentities(parseplayerheadline($playersteamprofile), ENT_COMPAT, 'UTF-8');
      }
    }
    $country_code = strtolower($geoip->country($row['ip'])->country->isoCode);
    $playername = '<img src="images/flags/'.$country_code.'.gif" alt="'.$country_code.'"> <a href="player.php?steamid='.$row['steamid'].'">'.$name.'</a>';

    if ($playerheadline)
      $playername = '<table border=0 cellspacing=0 cellpadding=0 class="top10"><tr><td rowspan="2">&nbsp;</td><td>'.$playername.'</td></tr><tr><td class="summary">'.$playerheadline.'</td></tr></table>';

    if ($avatarimg)
    {
      $playername = "<table border=0 cellspacing=0 cellpadding=0 class=\"top10\"><tr><td>&nbsp;</td><td>" . $avatarimg . "</td>" . ($playerheadline ? "" : "<td>&nbsp;</td>") . "<td>" . $playername . "</td></tr></table>";
    }

    if (!$playerheadline && !$avatarimg)
    {
      if ($i <= $top10players_additional_info)
      {
        $playername = "<table border=0 cellspacing=0 cellpadding=0 class=\"top10\"><tr><td>&nbsp;</td><td>" . $playername . "</td></tr></table>";
      }
      else
      {
        $playername = "&nbsp;" . $playername;
      }
    }

    $top10[] = createtablerowtooltip($row, $i) . "<td><b>" . $i . ".</b></td><td><div style=\"position:relative;width:150px;overflow:hidden;white-space:nowrap;\">" . $playername . "</div></td><td align=\"right\">&nbsp;&nbsp;" . gettotalpoints($row) . "&nbsp;Points</td></tr>";

    if ($top10players_additional_info && $i == $top10players_additional_info)
    {
      $top10[] = "<tr><td colspan=\"3\">&nbsp;</td></tr>";
    }

    $i++;
  }
}

$arr_templatefiles = php_scandir($templatesdir_default);
$templatefiles = array();
foreach ($arr_templatefiles as $file) {
  $templatefiles[$file] = "default/" . $file;
}

$arr_templatefiles = php_scandir($imagesdir_default);
$imagefiles = array();
foreach ($arr_templatefiles as $file) {
  $imagefiles[$file] = "default/images/" . $file;
}

if ($site_template != "" && $site_template != "default") {
  $arr_templatefiles = php_scandir($templatesdir . "/" . $site_template);

  foreach ($arr_templatefiles as $file) {
    if (!is_dir($file))
      $templatefiles[$file] = $site_template . "/" . $file;
  }

  $imagespath = $templatesdir . "/" . $site_template . "/images";

  if (file_exists($imagespath) && is_dir($imagespath)) {
    $arr_templatefiles = php_scandir($imagespath);

    foreach ($arr_templatefiles as $file) {
      if (!is_dir($file))
        $imagefiles[$file] = $site_template . "/images/" . $file;
    }
  }
}

$motd_message = htmlentities(getserversettingsvalue('motdmessage'), ENT_COMPAT, 'UTF-8');
$layout_motd = '';
if (strlen($motd_message) > 0) {
  $tpl_msg = new Template('templates/'.$templatefiles['layout_motd.tpl']);
  $tpl_msg->set('motd_message', $motd_message);
  $layout_motd = $tpl_msg->fetch('templates/'.$templatefiles['layout_motd.tpl']);
}
?>
