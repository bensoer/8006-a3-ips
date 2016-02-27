# 8005-a3-ips

This is an implementation of a basic password guessing ips. Call the ips.php file
from a cron or automated service to check the log files for password attempts.

The ips comes with additional features to also read, write, update, and delete
records stored in the state information the ips keeps. This allows manual alterations of
rules and records through the ips

# Dependencies

For the ips to work you will need to have php installed on your system and available from
the console. Also your system will need to have iptables running as its firewall editor
and using the standard linux `/var/log` directories for storing system logs. `jounral` will
not work on this ips system (an issue with some versions of Fedora)

# Quick Setup

To setup the ips simply register the the `ips4cron.sh` shell script with cron to be checked
at your desired frequency. You will need to add your sudo password to the `SUDOPASS` variable
in the `ips4cron.sh` script

# First time Setup

## 1) Configure Settings
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

## 2) Configure With Cron
The next step is then to setup the `ips4cron.sh` with crontab.

First open the ips4cron.sh file and edit the `SUDOPASS` variable to the sudo password of the machine `ips.php` will be executing on. This
is needed so that `ips.php` can execute iptables commands and read the system log files.

Then just configure cron to run the file at a frequency of your preference. The ideal setup is usually every couple of minutes.

# Problems With Setup
If you run into issues `ips.php` can be reset back to defaults simply by deleting either the `records.ipsconf` file or the `settings.ipsconf` file.
Deleting the `records.ipsconf` file will remove all history and knowledge `ips.php` has gained by accessing and monitoring your log files and is generaly
not recommended unless wanting to do a complete restore. If you would like to reset `ips.php`'s settings back to default you can do this by deleting the
`settings.ipsconf`. The next time `ips.php` runs in either `check` or `settings` mode a new file will be regenerated.

# Run ips.php manualy
You can always run the `ips.php` check manually if you would like to test it out. Note that this will only execute it once and it will terminate
after scanning logs and recording records of login attempts. To continue scanning manualy would require rerunning the program and thus using crontab
is the recommended approach.

You can run `ips.php` manualy with the following command:
```
php ips.php -m check -p <sudopassword>
```