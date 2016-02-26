<?php

/*
 * Main init script for configer client site as a whole.
 */

/** Class autoloading setup **/

function autoLoadClassFile($class_name) {
    if (file_exists('.includes/library/local/classes/' . $class_name . '.php')) {
        require_once('library/local/classes/' . $class_name . '.php');
    }
}
spl_autoload_register('autoLoadClassFile');

 