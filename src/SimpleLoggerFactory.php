<?php

namespace smalex86\logger;

use smalex86\logger\Logger;
use smalex86\logger\route\FileRoute;
use Psr\Log\LoggerInterface;
use DateTime;

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
   * Date format for string log file
   * @var string
   */
  protected static $dateFormat;

  /**
   * Factory parameters init
   * 
   * @param int $maxLevel
   * @param string $logFilename
   * @param string $folder
   * @param string $dateFormat
   */
  public static function init($maxLevel, $logFilename, $folder, 
          $dateFormat = DateTime::W3C) 
  {
    self::$maxLevel = $maxLevel;
    self::$logFilename = $logFilename;
    self::$folder = $folder;
  }

  /**
   * Method to get new FileLogger
   * 
   * @return LoggerInterface
   */
  public static function getLogger(): LoggerInterface 
  {
    $logger = new Logger();
    $logger->routeList->attach(new FileRoute([
        'dateFormat' => self::$dateFormat,  
        'isEnabled' => true,
        'maxLevel' => self::$maxLevel,
        'logFile' => self::$logFilename,
        'folder' => self::$folder
    ]));
    return $logger;
  }

}
