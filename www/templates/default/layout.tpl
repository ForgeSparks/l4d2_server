<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <?php echo $statspagemeta; ?>
    <title>Left 4 Dead Player Stats :: <?php echo $title; ?></title>
    <link href="./templates/<?php echo $stylesheet; ?>" rel="stylesheet" type="text/css" />
  </head>
  <body>
    <script type="text/javascript" src="./templates/<?php echo $statstooltip; ?>"></script>
    <script type="text/javascript" src="./templates/<?php echo $statscombobox; ?>"></script>
    <div id="header">
      <div id="logo">
        <h1>Left 4 Dead Player Stats</h1>
        <h2><?php echo $site_name; ?></h2>
      </div>
      <div id="players_served" align="right">
        <?php foreach ($header_extra as $title => $value): ?>
        <h2><?php echo $title; ?>: <?php echo number_format($value); ?></h2>
        <?php endforeach; ?>
      </div>
    </div>
    <div id="page">
      <div id="content">
        <div class="post">
          <h1 class="title" style="background: none; padding: 0; margin-top: -10px;"><?php echo $page_heading; ?></h1>
        </div>
        <?php echo $body; ?>
      </div>
      <div id="sidebar">
        <ul>
          <li>
            <h2>Left 4 Dead Stats</h2>
            <ul>
              <li><a href="index.php">Players Online</a></li>
              <li><a href="playerlist.php">Player Rankings</a></li>
              <li><a href="search.php">Player Search</a></li>
              <li><a href="awards.php">Rank Awards</a></li>
              <li><a href="javascript:void();" class="special" onmouseover="showcmb(this, '<a href=&quot;maps.php?type=coop&quot;>Coop Stats</a><br><?php echo $realismcmblink; ?><a href=&quot;maps.php?type=versus&quot;>Versus Stats</a><br><?php echo $scavengecmblink; ?><a href=&quot;maps.php?type=survival&quot;>Survival Stats</a><br><?php echo $realismversuscmblink; ?><?php echo $mutationscmblink; ?>');" onmouseout="hidecmb();">Gamemode Stats &raquo;</a></li>
              <?php echo $timedmapslink;?>
              <li><a href="server.php" class="special">Server Stats</a></li>
            </ul>
          </li>
          <li>
            <h2>Search For Player</h2>
            <ul>
              <li>Search for a <b>Player's Name</b> or <b>Steam ID</b></li>
              <li>
                <form method="post" action="search.php">
                  <input type="text" id="s" name="search" value="" />
                  <input type="submit" id="x" name="submit" value="Search" />
                </form>
              </li>
            </ul>
          </li>
          <li>
            <h2><b>Top 10 Players</b></h2>
            <table cellspacing="0" cellpadding="0" border="0" width="100%">
              <?php foreach ($top10 as $text): ?>
              <?php echo $text; ?>
              <?php endforeach; ?>
            </table>
          </li>
          <?php echo $motd_message; ?>
        </ul>
      </div>
      <div style="clear: both;">&nbsp;</div>
    </div>
  </body>
</html>
