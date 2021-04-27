<?php
/*
================================================
Rank Awards page - "awards.php"
================================================
*/

function addordinalnumbersuffix($num) {
  if (!in_array(($num % 100), array(11,12,13))) {
    switch ($num % 10) {
      // Handle 1st, 2nd, 3rd
      case 1: return $num.'st';
      case 2: return $num.'nd';
      case 3: return $num.'rd';
    }
  }

  return $num.'th';
}

// Include the primary PHP functions file
require_once('common.php');

// Load outer template
$tpl = new Template('templates/layout.tpl');

$award_ppm = "<a href=\"%s\">%s</a> is The Most Efficient Player with <b>%s Points Per Minute</b>.";
$award_time = "<a href=\"%s\">%s</a> has the most total playtime with <b>%s of Play</b>.";
$award_second = "<a href=\"%s\">%s</a> came in %s with <b>%s</b>.";

$award_kills = "<a href=\"%s\">%s</a> is The Real Chicago Ted with <b>%s Total Kills</b>.";
$award_headshots = "<a href=\"%s\">%s</a> can Aim For The Top with <b>%s Headshots</b>.";
$award_ratio = "<a href=\"%s\">%s</a> is The Headshot King with a <b>%s&#37; Headshot Ratio</b>.";
$award_melee_kills = "<a href=\"%s\">%s</a> is The Martial Artist with <b>%s Total Melee Kills</b>.";

$award_killsurvivor = "<a href=\"%s\">%s</a> Masters The Life Of The Undead with <b>%s Survivor</b> kills.";
$award_killinfected = "<a href=\"%s\">%s</a> can Kill Anyone He Wants with <b>%s Common Infected</b> kills.";
$award_killhunter = "<a href=\"%s\">%s</a> Moves Like They Do with <b>%s Hunter</b> kills.";
$award_killsmoker = "<a href=\"%s\">%s</a> is In The Non-Smoking Section with <b>%s Smoker</b> kills.";
$award_killboomer = "<a href=\"%s\">%s</a> is a Weight Loss Trainer with <b>%s Boomer</b> kills.";
$award_killspitter = "<a href=\"%s\">%s</a> Don't Like Zombies Without Manners with <b>%s Spitter</b> kills.";
$award_killjockey = "<a href=\"%s\">%s</a> Likes To Be On Top with <b>%s Jockey</b> kills.";
$award_killcharger = "<a href=\"%s\">%s</a> Don't Like To Be Pushed Around with <b>%s Charger</b> kills.";

$award_pills = "<a href=\"%s\">%s</a> says The First Hit Is Free with <b>%s Pain Pills Given</b>.";
$award_medkit = "<a href=\"%s\">%s</a> is Wishing He Had A Medigun with <b>%s Medkits Used on Teammates</b>.";
$award_adrenaline = "<a href=\"%s\">%s</a> Needs The Teammates To Stay In Top Speed with <b>%s Adrenalines Given</b>.";
$award_defib = "<a href=\"%s\">%s</a> is A Life Giver with <b>%s Defibrillators Used on Teammates</b>.";
$award_hunter = "<a href=\"%s\">%s</a> is Johnny On The Spot by <b>Saving %s Pounced Teammates From Hunters</b>.";
$award_smoker = "<a href=\"%s\">%s</a> is Into Anime, But Not Like That by <b>Saving %s Teammates From Smokers</b>.";
$award_jockey = "<a href=\"%s\">%s</a> is The Freedom Fighter by <b>Saving %s Teammates From Jockeys</b>.";
$award_charger = "<a href=\"%s\">%s</a> is Giving Hell To Bullies <b>Saving %s Teammates From Chargers</b>.";
$award_protect = "<a href=\"%s\">%s</a> is Saving Your Ass with <b>%s Teammates Protected</b>.";
$award_revive = "<a href=\"%s\">%s</a> is There When You Need Him by <b>Reviving %s Teammates</b>.";
$award_rescue = "<a href=\"%s\">%s</a> is Checking All The Closets with <b>%s Teammates Rescued</b>.";
$award_campaigns = "<a href=\"%s\">%s</a> is Getting Rescued... Again! with <b>%s Campaigns Completed</b>.";
$award_tankkill = "<a href=\"%s\">%s</a> is Bringing Down The House by <b>Team Assisting %s Tank Kills</b>.";
$award_tankkillnodeaths = "<a href=\"%s\">%s</a> is Bringing Superior Firepower by <b>Team Assisting %s Tank Kills, With No Deaths</b>.";
$award_allinsafehouse = "<a href=\"%s\">%s</a> is Leaving No Man Behind with <b>%s Safe Houses Reached With All Survivors</b>.";

$award_friendlyfire = "<a href=\"%s\">%s</a> is A Terrible Friend with <b>%s Friendly Fire Incidents</b>.";
$award_teamkill = "<a href=\"%s\">%s</a> is Going To Be Banned, BRB with <b>%s Team Kills</b>.";
$award_fincap = "<a href=\"%s\">%s</a> is Not Very Friendly with <b>%s Team Incapacitations</b>.";
$award_left4dead = "<a href=\"%s\">%s</a> will Leave You For Dead by <b>Allowing %s Teammates To Die In Sight</b>.";
$award_letinsafehouse = "<a href=\"%s\">%s</a> is Turning Into One Of Them with <b>%s Infected Let In The Safe Room</b>.";
$award_witchdisturb = "<a href=\"%s\">%s</a> is Not A Lady Pleaser by <b>Disturbing %s Witches</b>.";

$award_pounce_nice = "<a href=\"%s\">%s</a> is Pain From Above with <b>%s Hunter Nice Pounces</b>.";
$award_pounce_perfect = "<a href=\"%s\">%s</a> is Death From Above with <b>%s Hunter Perfect Pounces</b>.";
$award_perfect_blindness = "<a href=\"%s\">%s</a> is A Pain Painter causing <b>%s Times Perfect Blindness With A Boomer</b>.";
$award_infected_win = "<a href=\"%s\">%s</a> is Driving Survivors In To Extinction with <b>%s Infected Victories</b>.";
$award_bulldozer = "<a href=\"%s\">%s</a> is A Tank Bulldozer inflicting <b>Massive Damage %s Times To The Survivors</b>.";
$award_survivor_down = "<a href=\"%s\">%s</a> puts Survivors On Their Knees with <b>%s Incapacitations</b>.";
$award_ledgegrab = "<a href=\"%s\">%s</a> wants Survivors Of The Map causing <b>%s Survivors Grabbing On The Ledge</b>.";
$award_matador = "<a href=\"%s\">%s</a> is The Matador with <b>%s Leveled Charges</b>.";
$award_witchcrowned = "<a href=\"%s\">%s</a> Knows How To Handle Women with <b>%s Crowned Witches</b>.";
$award_scatteringram = "<a href=\"%s\">%s</a> is a Crowd Breaker with <b>%s Scattering Rams</b>.";

$infected_tanksniper = "<a href=\"%s\">%s</a> is A Tank Sniper hitting <b>%s Survivors With A Rock</b>.";

$awardarr = array(
  'kills' => $award_kills,
  'headshots' => $award_headshots,

  'versus_kills_survivors + scavenge_kills_survivors + realism_kills_survivors' => $award_killsurvivor,
  'kill_infected' => $award_killinfected,
  'melee_kills' => $award_melee_kills,
  'kill_hunter' => $award_killhunter,
  'kill_smoker' => $award_killsmoker,
  'kill_boomer' => $award_killboomer,
  'kill_spitter' => $award_killspitter,
  'kill_jockey' => $award_killjockey,
  'kill_charger' => $award_killcharger,

  'award_pills' => $award_pills,
  'award_medkit' => $award_medkit,
  'award_adrenaline' => $award_adrenaline,
  'award_defib' => $award_defib,
  'award_hunter' => $award_hunter,
  'award_smoker' => $award_smoker,
  'award_jockey' => $award_jockey,
  'award_charger' => $award_charger,
  'award_protect' => $award_protect,
  'award_revive' => $award_revive,
  'award_rescue' => $award_rescue,
  'award_campaigns' => $award_campaigns,
  'award_tankkill' => $award_tankkill,
  'award_tankkillnodeaths' => $award_tankkillnodeaths,
  'award_allinsafehouse' => $award_allinsafehouse,

  'award_friendlyfire' => $award_friendlyfire,
  'award_teamkill' => $award_teamkill,
  'award_fincap' => $award_fincap,
  'award_left4dead' => $award_left4dead,
  'award_letinsafehouse' => $award_letinsafehouse,
  'award_witchdisturb' => $award_witchdisturb,

  'award_pounce_nice' => $award_pounce_nice,
  'award_pounce_perfect' => $award_pounce_perfect,
  'award_perfect_blindness' => $award_perfect_blindness,
  'award_infected_win' => $award_infected_win,
  'award_bulldozer' => $award_bulldozer,
  'award_survivor_down' => $award_survivor_down,
  'award_ledgegrab' => $award_ledgegrab,
  'award_matador' => $award_matador,
  'award_witchcrowned' => $award_witchcrowned,
  'award_scatteringram' => $award_scatteringram,

  'infected_tanksniper' => $infected_tanksniper
);

$cachedate = filemtime('./awards_cache.html');
if ($cachedate < time() - 3600) {
  $real_playtime_sql = $TOTALPLAYTIME;
  $real_playtime = 'real_playtime';
  $real_points_sql = $TOTALPOINTS;
  $real_points = 'real_points';
  $extrasql = ', '.$real_points_sql.' as '.$real_points.', '.$real_playtime_sql.' as '.$real_playtime;

  if ((int)$award_display_players <= 0)
    $award_display_players = 1;

  $query = 'SELECT *'.$extrasql.' FROM players WHERE ('.$real_playtime_sql.') >= '.$award_minplaytime.' ORDER BY ('.$real_points.' / '.$real_playtime.') DESC LIMIT '.$award_display_players;
  $result = mysql_query($query);

  if ($result && mysql_num_rows($result) > 0) {
    $i = 0;

    while ($row = mysql_fetch_array($result)) {
      $country_code = strtolower($geoip->country($row['ip'])->country->isoCode);
      if ($i++ == 0)
        $table_body .= '<tr><td><img src="images/flags/'.$country_code.'.gif" alt="'.$country_code.'"> '.sprintf($award_ppm, 'player.php?steamid='.$row['steamid'], htmlentities($row['name'], ENT_COMPAT, 'UTF-8'), number_format($row[$real_points] / $row[$real_playtime], 2));
      else
        $table_body .= '<br><i style="font-size: 12px;"><img src="images/flags/'.$country_code.'.gif" alt="'.$country_code.'"> '.sprintf($award_second, 'player.php?steamid='.$row['steamid'], htmlentities($row['name'], ENT_COMPAT, 'UTF-8'), addordinalnumbersuffix($i), number_format($row[$real_points] / $row[$real_playtime], 2)).'</i>';
    }

    $table_body .= '</td></tr>';
  }

  $query = 'SELECT *'.$extrasql.' FROM players WHERE ('.$real_playtime_sql.') >= '.$award_minplaytime.' ORDER BY '.$real_playtime.' DESC LIMIT '.$award_display_players;
  $result = mysql_query($query);

  if ($result && mysql_num_rows($result) > 0) {
    $i = 0;

    while ($row = mysql_fetch_array($result)) {
      $country_code = strtolower($geoip->country($row['ip'])->country->isoCode);
      if ($i++ == 0)
        $table_body .= '<tr><td><img src="images/flags/'.$country_code.'.gif" alt="'.$country_code.'"> '.sprintf($award_time, 'player.php?steamid='.$row['steamid'], htmlentities($row['name'], ENT_COMPAT, 'UTF-8'), formatage($row[$real_playtime] * 60));
      else
        $table_body .= '<br><i style="font-size: 12px;"><img src="images/flags/'.$country_code.'.gif" alt="'.$country_code.'"> '.sprintf($award_second, 'player.php?steamid='.$row['steamid'], htmlentities($row['name'], ENT_COMPAT, 'UTF-8'), addordinalnumbersuffix($i), formatage($row[$real_playtime] * 60)).'</i>';
    }

    $table_body .= '</td></tr>';
  }

  $headshotratiosql = $real_playtime_sql.' >= '.$award_minplaytime.' AND '.$real_points_sql.' >= '.$award_minpoints.' AND kills >= '.$award_minkills.' AND headshots >= '.$award_minheadshots;

  $query = 'SELECT *'.$extrasql.' FROM players WHERE '.$headshotratiosql.' ORDER BY (headshots/kills) DESC LIMIT '.$award_display_players;
  $result = mysql_query($query);

  if ($result && mysql_num_rows($result) > 0) {
    $i = 0;

    while ($row = mysql_fetch_array($result)) {
      if (!($row['headshots'] && $row['kills']))
        break;

      $country_code = strtolower($geoip->country($row['ip'])->country->isoCode);
      if ($i++ == 0)
        $table_body .= '<tr><td><img src="images/flags/'.$country_code.'.gif" alt="'.$country_code.'"> '.sprintf($award_ratio, 'player.php?steamid='.$row['steamid'], htmlentities($row['name'], ENT_COMPAT, 'UTF-8'), number_format($row['headshots'] / $row['kills'], 4) * 100);
      else
        $table_body .= '<br><i style="font-size: 12px;"><img src="images/flags/'.$country_code.'.gif" alt="'.$country_code.'"> '.sprintf($award_second, 'player.php?steamid='.$row['steamid'], htmlentities($row['name'], ENT_COMPAT, 'UTF-8'), addordinalnumbersuffix($i), (number_format($row['headshots'] / $row['kills'], 4) * 100).'&#37;').'</i>';
    }

    $table_body .= '</td></tr>';
  }

  foreach ($awardarr as $award => $awardstring) {
    $queryresult = array();

    $awardsql = ($award !== 'award_teamkill' || $award !== 'award_friendlyfire') ? ' WHERE '.$real_playtime_sql.' >= '.$award_minplaytime.' AND '.$real_points_sql.' >= '.$award_minpointstotal : '';

    $query = 'SELECT name, steamid, ip, '.$award.' AS queryvalue'.$extrasql.' FROM players '.$awardsql.' ORDER BY '.$award.' DESC LIMIT '.$award_display_players;
    $result = mysql_query($query);

    if ($result && mysql_num_rows($result) > 0) {
      $i = 0;

      while ($row = mysql_fetch_array($result)) {
        $country_code = strtolower($geoip->country($row['ip'])->country->isoCode);
        if ($i++ == 0)
          $table_body .= '<tr><td><img src="images/flags/'.$country_code.'.gif" alt="'.$country_code.'"> '.sprintf($awardstring, 'player.php?steamid='.$row['steamid'], htmlentities($row['name'], ENT_COMPAT, 'UTF-8'), number_format($row['queryvalue']));
        else
          $table_body .= '<br><i style="font-size: 12px;"><img src="images/flags/'.$country_code.'.gif" alt="'.$country_code.'"> '.sprintf($award_second, 'player.php?steamid='.$row['steamid'], htmlentities($row['name'], ENT_COMPAT, 'UTF-8'), addordinalnumbersuffix($i), number_format($row['queryvalue'])).'</i>';
      }

      $table_body .= '</td></tr>';
    }
  }

  $stats = new Template('templates/awards.tpl');
  $stats->set('awards_date', date("M d, Y g:ia", time()));
  $stats->set('awards_body', $table_body);
  $award_output = $stats->fetch('templates/awards.tpl');
  file_put_contents('./awards_cache.html', trim($award_output));
}

setcommontemplatevariables($tpl);

$tpl->set('title', 'Rank Awards');
$tpl->set('page_heading', 'Rank Awards');

$output = file_get_contents('./awards_cache.html');

$tpl->set('body', trim($output));

// Output the top10
$tpl->set('top10', $top10);

// Output the MOTD
$tpl->set('motd_message', $layout_motd);

// Print out the page!
echo $tpl->fetch('templates/layout.tpl');
?>
