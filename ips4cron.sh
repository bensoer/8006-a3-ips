#!/usr/bin/env bash

#the cronjob will only execute checks so this mode can stay this way forever
MODE="check"
#add the sudo password here so that it will be passed to the ips.php as it is needed to execute iptables and read secure log files
SUDOPASS=""

#now lets execute the ips program
php ips.php -m $MODE -p $SUDOPASS