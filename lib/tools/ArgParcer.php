<?php

/**
 * Created by PhpStorm.
 * User: bensoer
 * Date: 22/02/16
 * Time: 4:11 PM
 */

final class ArgParcer
{

    //private static $unformattedArguments;
    private static $formattedArguments;

    private static $instance = null;


    /**
     * ArgParcer constructor. Made private so that it can not be initialized. This enforces a singleton
     */
    private function __construct(){}

    /**
     * formatArguments is a static helper method for taking the $argv array and parsing it into an associative array
     * to be used by the ArgPArcer instance
     * @param array $argv - The argv array passed by php containing the initialization parameters passed to the program
     * @return array - An associative array with the passed in parameters sorted with the key being the flag and the value
     * being the value for that flag when it was passed as an initializer parameter
     */
    public static function formatArguments(Array $argv){
        //ArgParcer::$unformattedArguments = $argv;
        $formattedArguments = Array();

        // format argv into associative array with keys being the passed flag and the value being the value
        // passed for the flag
        for($i = 1; $i < count($argv); $i++){
            $element = $argv[$i];
            $firstTwo = substr($element, 0, 2);
            $firstOne = substr($element, 0, 1);
            if(strcmp($firstTwo,'--')==0){
                // also look for toggle flags and set them as appropriate
                //this is a standalone flag?
                $formattedArguments[$element] = true;

            }else if(strcmp($firstOne, "-")==0){
                //this is a normal flag
                $formattedArguments[$element] = $argv[$i + 1];
            }else{
                continue;
            }
        }

        return $formattedArguments;
    }

    /**
     * getInstance returns a singleton instance of the ArgParcer object. Note passed in arguments will not
     * be used if an instance has already been created
     * @param array $formattedArguments - the formatted arguments of the argv parameters into an associative array
     * @return ArgParcer - the singleton instance of the ArgParcer
     */
    public static function getInstance(Array $formattedArguments = null){
        if(self::$instance == null){
            self::$formattedArguments = $formattedArguments;
            self::$instance = new ArgParcer();
        }
        return self::$instance;
    }

    /**
     * getValue is the main use method of the ArgPArcer in getting a value passed in as an argument. Getting a value
     * first checks if the value isset. Since isset returns false if a variable has been set to NULL, array_key_exist
     * is also runby it to check if the value exists. The reasoning for the double function calls is the isset is
     * substantialy faster then array_key_exists and therefor is preferrable. Use of php's short-circuited if statements
     * is used to get the best of both worlds
     * @param $key String - the flag that the value we are looking for belongs to
     * @return null OR String - the value belonging to the key, or null if the key does not exist
     */
    public function getValue($key){
        //check if key is set
        if (isset(self::$formattedArguments[$key]) || array_key_exists($key, self::$formattedArguments)) {
            return self::$formattedArguments[$key];
        }else{
            return null;
        }
    }
}