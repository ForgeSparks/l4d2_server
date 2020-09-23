<?php
/*
================================================
Index / Players Online page - "index.php"
================================================
*/

// Include the primary PHP functions file
include('common.php');

// Load outer template
$tpl = new Template('/templates/'.$templatefiles['layout.tpl']);

$result = mysql_query('SELECT * FROM players WHERE lastontime >= \''.intval(time() - 300).'\' ORDER BY '.$TOTALPOINTS.' DESC');
$playercount = number_format(mysql_num_rows($result));

setcommontemplatevariables($tpl);

$tpl->set('title', 'Players Online');
$tpl->set('page_heading', 'Players Online - '.$playercount);
$tpl->set('statspagemeta', '<meta http-equiv="refresh" content="30">');

if (mysql_error())
  $output = '<p><b>MySQL Error:</b> '.mysql_error().'</p>';
else {
  $arr_online = array();
  $stats = new Template('templates/'.$templatefiles['online.tpl']);

  $i = 1;
  while ($row = mysql_fetch_array($result)) {
    if ($row['lastontime'] > time())
      $row['lastontime'] = time();

    $lastgamemode = 'Unknown';
    switch ($row['lastgamemode']) {
      case 0:
        $lastgamemode = 'Coop';
        break;
      case 1:
        $lastgamemode = 'Versus';
        break;
      case 2:
        $lastgamemode = 'Realism';
        break;
      case 3:
        $lastgamemode = 'Survival';
        break;
      case 4:
        $lastgamemode = 'Scavenge';
        break;
      case 5:
        $lastgamemode = 'Realism&nbsp;Versus';
        break;
      case 6:
        $lastgamemode = 'Mutation';
        break;
    }

    $player_ip = $row['ip'];

    $avatarimg = '';

    if ($players_online_avatars_show) {
      $avatarimgurl = getplayeravatar($row['steamid'], 'icon');

      if($avatarimgurl)
        $avatarimg = "<img src=\"" . $avatarimgurl . "\" border=\"0\">";
    }

    $country_code = strtolower($geoip->country($row['ip'])->country->isoCode);
    $playername = '<img src="images/flags/'.$country_code.'.gif" alt="'.$country_code.'"> <a href="player.php?steamid='.$row['steamid'].'">'.htmlentities($row['name'], ENT_COMPAT, 'UTF-8').'</a>';

    if ($avatarimg)
      $playername = '<table border=0 cellspacing=0 cellpadding=0><tr><td>'.$avatarimg.'</td><td>&nbsp;'.$playername.'</td></tr></table>';

    $line = createtablerowtooltip($row, $i);
    $line .= '<td>'.$playername.'</td>';
    $line .= '<td>'.gettotalpoints($row).'</td><td>'.$lastgamemode.'</td><td>'.gettotalplaytime($row).'</td></tr>';

    $arr_online[] = $line;

    $i++;
  }

  if (count($arr_online) == 0)
    $arr_online[] = '<tr><td colspan="4" align="center">There are no players online</td</tr>';

  $stats->set('online', $arr_online);
  $output = $stats->fetch('templates/'.$templatefiles['online.tpl']);
}

$tpl->set('body', trim($output));

// Output the top 10
$tpl->set('top10', $top10);

// Output the MOTD
$tpl->set('motd_message', $layout_motd);

// Print out the page!
echo $tpl->fetch('templates/' . $templatefiles['layout.tpl']);
?>
