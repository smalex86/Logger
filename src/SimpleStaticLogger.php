<?php

/*
 * This file is part of the smalex86\logger package.
 *
 * (c) Alexandr Smirnov <mail_er@mail.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace smalex86\logger;

/**
 * Description of SimpleStaticLogger
 *
 * @author Alexandr Smirnov
 */
class SimpleStaticLogger {
  
  private static $levelWord = array(
    0 => ['emergency', 'EMERGENCY'],
    1 => ['alert',     'ALERT    '],
    2 => ['critical',  'CRITICAL '],
    3 => ['error',     'ERROR    '],
    4 => ['warning',   'WARNING  '],
    5 => ['notice',    'NOTICE   '],
    6 => ['info',      'INFO     '],
    7 => ['debug',     'DEBUG    ']
  );
  
  private static $maxLevel = 7; 
  private static $logfileDefault = 'syslog.log';
  private static $folder = __DIR__ . '/logs/';
  
  public static function init($level = 7, $logFileName = '', $folder = ''): void
  {
    self::$maxLevel = $level;
    self::$logfileDefault = $logFileName;
    self::$folder = $folder;
  }
  
  /**
   * Method for define of logging max level
   * @param int $level
   * @return boolean
   */
  public static function setMaxLevel($level): void
  {
    self::$maxLevel = $level;
  }
  
  /**
   * Method for setting of logfiles default folder
   * @param string $folder
   * @return boolean
   */
  public static function setLogFolder($folder): void
  {
    self::$folder = realpath($folder);
  }
  
  /**
   * Method for getting of logfiles default folder 
   * @return type
   */
  public static function getLogFolder(): string
  {
    return self::$folder;
  }
  
  /**
   * Gets max log level
   * @return int
   */
  public static function getMaxLevel(): int
  {
    return self::$maxLevel;
  }
  
  /**
   * Get log filename 
   * @return string
   */
  public static function getLogFile(): string
  {
    return self::$logfileDefault;
  }

  /**
   * System is unusable.
   * 
   * @param string $message
   * @param array $context
   * @return bool
   */
  public static function emergency($message, array $context = array()): bool
  {
    return self::log(0, $message, $context);
  }
  
  /**
   * Action must be taken immediately.
   *
   * Example: Entire website down, database unavailable, etc. This should
   * trigger the SMS alerts and wake you up.
   * 
   * @param type $message
   * @param array $context
   * @return bool
   */
  public static function alert($message, array $context = array()): bool
  {
    return self::log(1, $message, $context);
  }
  
  /**
   * Critical conditions.
   *
   * Example: Application component unavailable, unexpected exception.*
   * 
   * @param type $message
   * @param array $context
   * @return bool
   */
  public static function critical($message, array $context = array()): bool
  {
    return self::log(2, $message, $context);
  }
  
  /**
   * Runtime errors that do not require immediate action but should typically
   * be logged and monitored.
   *
   * @param type $message
   * @param array $context
   * @return bool
   */
  public static function error($message, array $context = array()): bool
  {
    return self::log(3, $message, $context);
  }
  
  /**
   * Exceptional occurrences that are not errors.
   *
   * Example: Use of deprecated APIs, poor use of an API, undesirable things
   * that are not necessarily wrong.
   * 
   * @param type $message
   * @param array $context
   * @return bool
   */
  public static function warning($message, array $context = array()): bool
  {
    return self::log(4, $message, $context);
  }
 
  /**
   * Normal but significant events.
   * 
   * @param type $message
   * @param array $context
   * @return bool
   */
  public static function notice($message, array $context = array()): bool
  {
    return self::log(5, $message, $context);
  }
 
  /**
   * Interesting events. Example: User logs in, SQL logs.
   * 
   * @param type $message
   * @param array $context
   * @return bool
   */
  public static function info($message, array $context = array()): bool
  {
    return self::log(6, $message, $context);
  }

  /**
   * Detailed debug information.
   * 
   * @param type $message
   * @param array $context
   * @return bool
   */
  public static function debug($message, array $context = array()): bool
  {
    return self::log(7, $message, $context);
  }

  /**
   * Return loglevel index by psr log level word
   * 
   * @param string $psrLogLevel
   * @return int
   */
  protected static function getLogLevelFromRsrLogLevel($psrLogLevel) {
    foreach (self::$levelWord as $key => $value) {
      if ($value[0] == $psrLogLevel) {
        return $key;
      }
    }
    return -1;
  }

  /**
   * Method for suitable with PsrLogger.
   * Logs with an arbitrary level.
   * 
   * @param mixed $level
   * @param string $message
   * @param array $context
   * @return bool
   */
  public static function log($level, $message, array $context = array()): bool
  {
    // if level set in psrloglevel string
    if (is_string($level)) {
      $level = self::getLogLevelFromRsrLogLevel($level);
    }
    // check for requirement of writing
    // it is determined by log level and msgStatus
    if (self::$maxLevel < $level) {
      return false;
    }
    // define msg status level word
    if (array_key_exists($level, self::$levelWord)) {
      $levelWord = self::$levelWord[$level][1];
    } else {
      $levelWord = 'UNKNOWN  ';
    }
    // prepare context
    $preparedContext = '';
    if (!empty($context)) {
      foreach ($context as $key=>$value) {     
        if (is_numeric($key)) {
          $preparedContext .= $value . '=>';
        } else {
          $preparedContext .= sprintf('(%s)%s=>', $key, $value);
        }
      }
      $preparedContext = substr($preparedContext, 0, -2) . ' :: ';
    }   
    // folder existing check and creating
    if (!file_exists(self::$folder)) {
      mkdir(self::$folder);
    }
    // write message to log
    return file_put_contents(realpath(self::$folder) . DIRECTORY_SEPARATOR . self::$logfileDefault, 
              sprintf('%s :: %s :: %s' . PHP_EOL, 
                      (new \DateTime())->format('Y-m-d H:i:s:v'), 
                      $level, 
                      $preparedContext . $message), 
              FILE_APPEND);
  }
  
}
