<?php

namespace smalex86\logger;

use smalex86\logger\SimpleLogger;

/**
 * Description of SimpleLoggerFactory
 *
 * @author smirnov
 */
class SimpleLoggerFactory {
    
    protected static $status;
    protected static $logFilename;
    protected static $folder;
    
    /**
     * Инициализация параметров
     * 
     * @param type $status
     * @param type $logFilename
     * @param type $folder
     */
    public static function init($status, $logFilename, $folder) {
        self::$status = $status;
        self::$logFilename = $logFilename;
        self::$folder = $folder;
    }
    
    /**
     * Создание Logger
     * 
     * @return SimpleLogger
     */
    public static function getLogger() {
        return new SimpleLogger(self::$status, self::$logFilename, self::$folder);
    }
    
}
