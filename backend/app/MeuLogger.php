<?php

namespace App;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class MeuLogger {

    public static function getLogger(){
        static $logger = null;
        if($logger === null){
            $logger = new Logger('meuLogger');
            $logger->pushHandler(new StreamHandler('../storage/logs/meulog.log', Logger::DEBUG));
        }
        return $logger;
    } 

    public static function debug($conteudo){
        self::getLogger()->debug($conteudo);
    }

}
