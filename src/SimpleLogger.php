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

use Psr\Log\LoggerInterface;

/**
 * Class for simple logging of information
 * @author Alexandr Smirnov <mail_er@mail.ru>
 * @version 1.4
 */
class SimpleLogger implements LoggerInterface {

  private static $statusWord = array(
    0 => ['emergency', 'EMERGENCY'],
    1 => ['alert', 'ALERT    '],
    2 => ['critical', 'CRITICAL '],
    3 => ['error', 'ERROR    '],
    4 => ['warning', 'WARNING  '],
    5 => ['notice', 'NOTICE   '],
    6 => ['info', 'INFO     '],
    7 => ['debug', 'DEBUG    ']
  );
  
  private static $status = 7; 
  private static $logfileDefault = 'syslog.log';
  private static $folder = __DIR__ . '/logs/';
    
  /**
   * Common function for write logs
   * It writes log to file $logFileName
   * @param int $msgStatus - see above
   * @param string $msg string of message that will write to log
   * @param string $logFileName log file name without path, log files are kept in root folder 'logs'
   * @return boolean
   */
  public static function toLog($msgStatus, $msg, $context = array(), $logFileName = '') {
    // check for requirement of writing
    // it is determined by log status and msgStatus
    if (self::$status < $msgStatus) {
      return false;
    }
    // check empty of logFilename
    if (!$logFileName) {
      $logFileName = self::$logfileDefault;
    }
    // define msg status word
    if (array_key_exists($msgStatus, self::$statusWord)) {
      $msgStatusWord = self::$statusWord[$msgStatus][1];
    } else {
      $msgStatusWord = 'UNKNOWN  ';
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
    return file_put_contents(realpath(self::$folder).'/'.$logFileName, 
              sprintf('%s :: %s :: %s' . PHP_EOL, (new \DateTime())->format('Y-m-d H:i:s:v'), $msgStatusWord, 
                $preparedContext . str_replace("\t", "", str_replace("\n", "", $msg))), 
              FILE_APPEND);
  }
  
  public function __construct($status = 7, $logFileName = '', $folder = '') {
    self::$status = $status;
    if ($logFileName) {
      self::$logfileDefault = $logFileName;
    }
    if ($folder) {
      self::$folder = $folder;
    }
    return true;
  }
  
  /**
   * Dynamic method for writing message to logfile with name logFileName with level = status
   * @param int $status
   * @param string $msg
   * @param string $logFileName
   * @return string
   */
  public function toLogD($status, $msg, $context = array(), $logFileName = '') {
    return self::toLog($status, $msg, $context, $logFileName);
  }
    
  /**
   * Dynamic method for writing message to logfile with name logFileName (or default log filename) with level = error (1)
   * @param string $msg
   * @param string $logFileName
   * @return boolean
   */
  public function errorD($msg, $context = array(), $logFileName = '') {
    return self::toLog(3, $msg, $context, $logFileName);
  }
  
  /**
   * Dynamic method for writing message to logfile with name logFileName (or default log filename) with level = warning (2)
   * @param string $msg
   * @param string $logFileName
   * @return boolean
   */  
  public function warningD($msg, $context = array(), $logFileName = '') {
    return self::toLog(4, $msg, $context, $logFileName);
  }
  
  /**
   * Dynamic method for writing message to logfile with name logFileName (or default log filename) with level = important (0)
   * @param string $msg
   * @param string $logFileName
   * @return boolean
   */  
  public function importantD($msg, $context = array(), $logFileName = '') {
    return self::toLog(6, $msg, $context, $logFileName);
  }
  
  /**
   * Dynamic method for writing message to logfile with name logFileName (or default log filename) with level = debug (3)
   * @param string $msg
   * @param string $logFileName
   * @return boolean
   */  
  public function debugD($msg, $context = array(), $logFileName = '') {
    return self::toLog(7, $msg, $context, $logFileName);
  }
  
  /**
   * Method for define of logging default status
   * @param type $status
   * @return boolean
   */
  public static function setStatus($status) {
    self::$status = $status;
    return true;
  }
  
  /**
   * Method for setting of logfiles default folder
   * @param type $folder
   * @return boolean
   */
  public static function setLogFolder($folder) {
    self::$folder = realpath($folder);
    return true;
  }
  
  /**
   * Method for getting of logfiles default folder 
   * @return type
   */
  public static function getLogFolder() {
    return self::$folder;
  }

  /**
   * Method for suitable with PsrLogger.
   * System is unusable.
   * 
   * @param type $message
   * @param array $context
   * @return bool
   */
  public function emergency($message, array $context = array()): bool
  {
    return $this->log(0, $message, $context);
  }
  
  /**
   * Method for suitable with PsrLogger.
   * Action must be taken immediately.
   *
   * Example: Entire website down, database unavailable, etc. This should
   * trigger the SMS alerts and wake you up.
   * 
   * @param type $message
   * @param array $context
   * @return bool
   */
  public function alert($message, array $context = array()): bool
  {
    return $this->log(1, $message, $context);
  }
  
  /**
   * Method for suitable with PsrLogger.
   * Critical conditions.
   *
   * Example: Application component unavailable, unexpected exception.*
   * 
   * @param type $message
   * @param array $context
   * @return bool
   */
  public function critical($message, array $context = array()): bool
  {
    return $this->log(2, $message, $context);
  }
  
  /**
   * Method for suitable with PsrLogger.
   * Runtime errors that do not require immediate action but should typically
   * be logged and monitored.
   *
   * @param type $message
   * @param array $context
   * @return bool
   */
  public function error($message, array $context = array()): bool
  {
    return $this->log(3, $message, $context);
  }
  
  /**
   * Method for suitable with PsrLogger.
   * Exceptional occurrences that are not errors.
   *
   * Example: Use of deprecated APIs, poor use of an API, undesirable things
   * that are not necessarily wrong.
   * 
   * @param type $message
   * @param array $context
   * @return bool
   */
  public function warning($message, array $context = array()): bool
  {
    return $this->log(4, $message, $context);
  }
 
  /**
   * Method for suitable with PsrLogger.
   * Normal but significant events.
   * 
   * @param type $message
   * @param array $context
   * @return bool
   */
  public function notice($message, array $context = array()): bool
  {
    return $this->log(5, $message, $context);
  }
 
  /**
   * Method for suitable with PsrLogger.
   * Interesting events. Example: User logs in, SQL logs.
   * 
   * @param type $message
   * @param array $context
   * @return bool
   */
  public function info($message, array $context = array()): bool
  {
    return $this->log(6, $message, $context);
  }

  /**
   * Method for suitable with PsrLogger.
   * Detailed debug information.
   * 
   * @param type $message
   * @param array $context
   * @return bool
   */
  public function debug($message, array $context = array()): bool
  {
    return $this->log(7, $message, $context);
  }

  /**
   * Return loglevel index by psr log level word
   * 
   * @param string $psrLogLevel
   * @return int
   */
  protected function getLogLevelFromRsrLogLevel($psrLogLevel) {
    foreach (self::$statusWord as $key => $value) {
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
  public function log($level, $message, array $context = array()): bool
  {
    // if level set in psrloglevel string
    if (is_string($level)) {
      $level = $this->getLogLevelFromRsrLogLevel($level);
    }
    return self::toLog($level, $message, $context);
  }
  
  
}
