<?php
/*
================================================
Configuration file - "config.php"
================================================
*/

// MySQL information for L4D Stats DB
$mysql_server = "localhost";
$mysql_db = "l4d2_stats";
$mysql_user = "root";
$mysql_password = "";

// Heading for the stats page.
$site_name = "ForgeSparks";

// Supported game versions
// 0 = Support both L4D1 and L4D2
// 1 = Left 4 Dead 1 (default)
// 2 = Left 4 Dead 2
$game_version = 2;

// Template for the stats page.
// Leave empty if the default template is used.
// Usage: "mytemplate" (requires directory ./templates/mytemplate existence)
$site_template = "";

// Minimum playtime and points required to be eligible for any awards, in minutes
$award_minplaytime = 60;
$award_minpointstotal = 0;

// Minimum kills, headshots and points to be eligible for "Headshot Ratio" award
$award_minkills = 100;
$award_minheadshots = 100;
$award_minpoints = 1000;

// How many top players would you like to show on the awards page on each award?
// Note! You should set this value to at least 1.
$award_display_players = 5;

// Amount of time in minutes between Awards page cache updates.
// 0 to disable cacheing
$award_cache_refresh = 60;

// Database time modifier (hours)
// 0 if the db time is the same as the websites
$dbtimemod = 0;

// Date format for player last online time
// http://www.php.net/manual/en/function.date.php
// Example: 24h - "M d, Y H:i";
$lastonlineformat = "M d, Y g:ia";

$population_file = "worldpop.csv";
$population_minkills = 1000;

// Show/hide link for the timed maps (also disables the page for parameterless use)
$timedmaps_show_all = False;

// Allow reading of player Steam profile (overrides all avatar related if set to False)
// Warning! Setting value to true can slow loading of some pages.
$steam_profile_read = True;

// Number of players to show additional info at Top 10 -players list (set to 0 to disable)
// Shows player avatar and some other information.
// Warning! Setting a number higher than 0 (zero) will slow every page load a little.
$top10players_additional_info = 0;
?>
