================================================
LEFT 4 DEAD AND LEFT 4 DEAD 2 PLAYER RANK
Copyright (c) 2010 Mikko Andersson
================================================
Initial code written by msleeper
================================================

INSTALLATION INSTRUCTIONS:
Extract the ZIP into the directory on your webserver. Edit the config.php with your MySQL settings, and any
altered base settins as you desire. Refer to config.php for specific information on these settings.

After setting up config.php, run createtable.php from a web browser. This will create the tables and input
initial map data into them. After this file is ran successfully, remove it. You MUST delete this file
before running webstats!

You will need to chmod 777 the file templates/awards_cache.html for the Awards to cache properly.
With IIS (Microsoft) you have to grant the anonymous user (IUSR_<computername>) full access to the file.

UPDATE/UPGRADE INSTRUCTIONS:
Backup your existing files, espcially any templates that you may have modified, as well as your config.php.

Extract the latest release of webstats into your existing installation. Unless you have made custom
modifications, you can let it overwrite any files. Check the changelog for any changes to config.php or
any templates, and apply them as necessary to your custom changes.

After restoring any necessary files, run updatetable.php from a web browser. This will update your table
layout to any changes made. If there are no changes, or you have already made the changes, this update
will be harmless. You MUST delete this file before running webstats!

You will need to chmod 777 the file templates/awards_cache.html for the Awards to cache properly.
With IIS (Microsoft) you have to grant the anonymous user (IUSR_<computername>) full access to the file.

Thanks and enjoy!
