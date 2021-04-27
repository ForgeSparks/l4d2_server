<table class="stats">
  <tr>
    <th colspan="5">
      <a href="?">Overall</a> | <a href="?type=coop">Coop</a><?php echo $realism_link;?> | <a href="?type=survival">Survival</a><?php echo $teammode_separator;?><a href="?type=versus">Versus</a> (<a href="?type=versus&team=survivors">Survivors</a> / <a href="?type=versus&team=infected">Infected</a>)<?php echo $scavenge_link;?>
    </th>
  </tr>
  <tr>
    <th colspan="5">
      <a href="<?php echo $page_prev;?>">&lt;&lt; Prev 100</a> | Page <?php echo $page_current;?> / <?php echo $page_total;?> | <a href="<?php echo $page_next;?>">Next 100 &gt;&gt;</a>
    </th>
  </tr>
  <tr>
    <th>Rank</th>
    <th>Player</th>
    <th>Points</th>
    <th>Playtime</th>
    <th>Last Online</th>
  </tr>
  <?php foreach ($players as $player): ?><?php echo $player;?><?php endforeach; ?>
  <tr>
    <th colspan="5">
      <a href="<?php echo $page_prev;?>"><< Prev 100</a> | Page <?php echo $page_current;?> / <?php echo $page_total;?> | <a href="<?php echo $page_next;?>">Next 100 >></a>
    </th>
  </tr>
</table>
