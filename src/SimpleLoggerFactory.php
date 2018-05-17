<?php

namespace smalex86\logger;

use smalex86\logger\SimpleLogger;

/**
 * Factory for SimpleLogger
 *
 * @author Alexandr Smirnov <mail_er@mail.ru>
 */
class SimpleLoggerFactory {
    
    /**
     * max logger level
     * @var int 
     */
    protected static $maxLevel;
    /**
     * Log filename
     * @var string
     */
    protected static $logFilename;
    /**
     * Folder contain log file
     * @var string
     */
    protected static $folder;
    
    /**
     * Factory parameters init
     * 
     * @param int $maxLevel
     * @param string $logFilename
     * @param string $folder
     */
    public static function init($maxLevel, $logFilename, $folder): void
    {
        self::$maxLevel = $maxLevel;
        self::$logFilename = $logFilename;
        self::$folder = $folder;
    }
    
    /**
     * Method to get new logger
     * 
     * @return SimpleLogger
     */
    public static function getLogger(): SimpleLogger
    {
        return new SimpleLogger(self::$maxLevel, self::$logFilename, self::$folder);
    }
    
}
