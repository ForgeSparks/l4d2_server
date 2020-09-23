<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta charset="utf-8">
    <?php echo (isset($statspagemeta) ? $statspagemeta : ''); ?>
    <title><?php echo $motd_header; ?> :: <?php echo $title; ?></title>
    <link href="templates/<?php echo $stylesheet; ?>" rel="stylesheet" type="text/css" />
    <style type="text/css">
      h1.header {
        margin-left: 10px;
        margin-top: -5px;
        letter-spacing: -1px;
        font-size: 36px;
        font-weight: bold;
        color: #FFFFFF;
      }

      h2.header_secondary {
        margin-left: 10px;
        margin-top: -10px;
        font-size: 14px;
        font-weight: bold;
        color: #DD2222;
      }

      h2.header_extra {
        margin-top: -3px;
        font-size: 12px;
        font-weight: bold;
        color: #888;
      }

      .player {
        font-size: 12px;
        font-weight: bold;
        color: #FFCC33;
      }

      h1.header_normal {
        margin-top: 15px;
        font-size: 24px;
        font-weight: bold;
        color: #FFFFFF;
        margin-bottom: 10px;
        border-bottom: 1px solid #DD2222;
      }

      .motd_message {
        font-size: 14px;
        font-weight: normal;
        color: #888;
      }
    </style>
  </head>
  <body>
    <div id="header">
      <table width="700" align="center" cellpaddin="0" cellspacing="0">
        <tr>
          <td align="center" valign="middle" width="70"><img src="./templates/<?php echo $site_logo; ?>"></td>
          <td align="left" valign="top">
            <h1 class="header"><?php echo $site_name;?></h1>
            <br>
            <h2 class="header_secondary"><?php echo $motd_header;?></h2>
          </td>
          <td align="right" valign="top">
            <?php foreach ($header_extra as $title => $value): ?>
            <br>
            <h2 class="header_extra"><?php echo $title; ?>: <?php echo number_format($value); ?></h2>
            <?php endforeach; ?>
          </td>
        </tr>
      </table>
    </div>
    <div id="page">
      <table cellspacing="0" cellpaddin="0" border="0" style="margin-left:auto;margin-right:auto;">
        <tr>
          <td valign="top"><?php echo $motd_top10; ?></td>
          <td width="20">&nbsp;</td>
          <td valign="top">
            <?php echo $motd_message; ?>
            <?php echo $motd_topppm; ?>
          </td>
        </tr>
      </table>
    </div>
    <br>
    <br>
  </body>
</html>
