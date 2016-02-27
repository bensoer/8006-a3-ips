# 8005-a3-ips

This is an implementation of a basic password guessing ips. Call the ips.php file
from a cron or automated service to check the log files for password attempts.

The ips comes with additional features to also read, write, update, and delete
records stored in the state information the ips keeps. This allows manual alterations of
rules and records through the ips

# Quick Setup

To setup the ips simply register the the `ips4cron.sh` shell script with cron to be checked
at your desired frequency. You will need to add your sudo password to the `SUDOPASS` variable
in the `ips4cron.sh` script

# First time Setup

On first run, ips has been set by default to check the `var/log/secure` file for intrusions,
it will allow up to 3 attempts before blocking a user, and it does not unblock users. In order
to change these settings you will need to run `ips.php` in `settings` mode so as to generate
a `settings.ipsconf` file with your preferences. Everytime `ips.php` starts up in `check` mode
it will then read this file and adjust accordingly.

The following flags will allow you to change the desired settings:

Flag | Description
---- | -----------
-tl | Set the time limit that a blocked user will be blocked for. -1 means no time limit
-al | Set the maximum number of attempts that can occur before a user is blocked
-ld | Set the log directory to be reasing from. Current support is for the `/var/log/messages` and `/var/log/secure` files
 
An example of using the command is like this:
```
php ips.php -m settings -tl <timelimit> -al <attemptlimit> -ld <logdir>
```
Note that by not including the parameter, the setting will not be changed from whatever it was previously set to
