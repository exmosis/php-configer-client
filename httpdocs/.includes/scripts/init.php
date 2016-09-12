<?php

/*
 * Main init script for configer client site as a whole.
 */

// Make sure we have this directory in our include path
set_include_path(get_include_path() . PATH_SEPARATOR . dirname(dirname(__FILE__)));
 
/** Class autoloading setup **/
function autoLoadClassFile($class_name) {
    if (file_exists('.includes/library/local/classes/' . $class_name . '.php')) {
        require_once('library/local/classes/' . $class_name . '.php');
    } else if (file_exists('.includes/library/local/interfaces/' . $class_name . '.php')) {
        require_once('library/local/interfaces/' . $class_name . '.php');
    }
}
spl_autoload_register('autoLoadClassFile');

 