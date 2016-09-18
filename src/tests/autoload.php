<?php
function autoload($className)
{
    preg_match('/(GPRCielo)/',$className,$matches);
    if(count($matches) > 0){
        $className = str_replace("\\","/",$className);
        $base = dirname(dirname(__FILE__).DIRECTORY_SEPARATOR);
        require $base.DIRECTORY_SEPARATOR.$className.".php";
    }

}

spl_autoload_register('autoload');