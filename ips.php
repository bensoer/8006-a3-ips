<?php
require_once('./lib/tools/ArgParcer.php');
/**
 * Created by PhpStorm.
 * User: bensoer
 * Date: 24/02/16
 * Time: 5:33 PM
 */

/**
 * PARAMETERS:
 * -m <check|create|read|update|delete> - Specify mode/activity to run.
 * -i <ip> - The IP of the accessor in question
 * -s <block|unblock> - The state the record should be
 * -p <protocol> - Name of the protocol to be looking for counts of
 */


/**
 * @param $argc
 * @param $argv
 * @return int
 */
function main($argc, $argv){

    //get arguments
    $formattedArguments = ArgParcer::formatArguments($argv);
    $apInstance = ArgParcer::getInstance($formattedArguments);

    //deserialize record manager

    //based on mode decide what needs to be done next

        //if to check
            //get login attempts list from log file - NOTE must concider date range so that you don't overlap

            //check for matching known records
                //if new record add the record

                //if known increment its count

            //check for records which have made too many offences in given time span
                //foreach one, block them

            //check for records that have been blocked long enough
                //foreach one, unblock them



    //serialize record manager

    return 0;
}
exit(main($argc,$argv));