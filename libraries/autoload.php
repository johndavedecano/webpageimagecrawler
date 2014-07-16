<?php
function my_autoloader($class) {
    include dirname(__FILE__).'/' . $class . '.php';
}

spl_autoload_register('my_autoloader');