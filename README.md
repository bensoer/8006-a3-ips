# 8005-a3-ips

This is an implementation of a basic password guessing ips. Call the ips.php file
from a cron or automated service to check the log files for password attempts.

The ips comes with additional features to also read, write, update, and delete
records stored in the state information the ips keeps. This allows manual alterations of
rules and records through the ips