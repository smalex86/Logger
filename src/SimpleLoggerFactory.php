<?php

namespace smalex86\logger;

use smalex86\logger\Logger;
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
   * It determines to use FileRoute or FileRouteWithPid
   * @var bool
   */
  protected static $withPid;

  /**
   * Factory parameters init
   * 
   * @param int $maxLevel
   * @param string $logFilename
   * @param string $folder
   * @param string $dateFormat
   */
  public static function init($maxLevel, $logFilename, $folder, 
          $dateFormat = DateTime::W3C, $withPid = false) 
  {
    self::$maxLevel = $maxLevel;
    self::$logFilename = $logFilename;
    self::$folder = $folder;
    self::$dateFormat = $dateFormat;
    self::$withPid = $withPid;
  }

  /**
   * Method to get new FileLogger
   * 
   * @return LoggerInterface
   */
  public static function getLogger(): LoggerInterface 
  {
    $logger = new Logger();
    if (!self::$withPid) {
        $routeClass = 'smalex86\logger\route\FileRoute';
    } else {
        $routeClass = 'smalex86\logger\route\FileRouteWithPid';
    }
    $logger->routeList->attach(new $routeClass([
        'dateFormat' => self::$dateFormat,  
        'isEnabled' => true,
        'maxLevel' => self::$maxLevel,
        'logFile' => self::$logFilename,
        'folder' => self::$folder
    ]));
    return $logger;
  }

}
