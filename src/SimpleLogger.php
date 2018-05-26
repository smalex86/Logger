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
use Psr\Log\AbstractLogger;

/**
 * Class for simple logging of information
 * @author Alexandr Smirnov <mail_er@mail.ru>
 */
class SimpleLogger extends AbstractLogger implements LoggerInterface {

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
  
  private $maxLevel  = 7; 
  private $logFile = 'syslog.log';
  private $folder  = __DIR__ . '/logs/';
  
  /**
   * Constructor
   * @param int $maxLevel
   * @param string $logFile
   * @param string $folder
   */
  public function __construct($maxLevel = 7, $logFile = '', $folder = '') {
    $this->maxLevel = $maxLevel;
    if ($logFile) {
      $this->logFile = $logFile;
    }
    if ($folder) {
      $this->folder = $folder;
    }
  }
  
  /**
   * Method for define of logging max level
   * @param int $maxLevel
   * @return boolean
   */
  public function setMaxLevel($maxLevel): void
  {
    $this->maxLevel= $maxLevel;
  }
  
  /**
   * Return max level of this logger
   * @return string
   */
  public function getMaxLevel(): string
  {
    return $this->maxLevel;
  }
  
  /**
   * Method for setting of log files default folder
   * @param string $folder
   * @return boolean
   */
  public function setLogFolder($folder): void
  {
    $this->folder = realpath($folder);
  }
  
  /**
   * Method for getting of log files default folder 
   * @return type
   */
  public function getLogFolder() {
    return $this->folder;
  }
  
  /**
   * Set log filename
   * @param string $logfile
   */
  public function setLogFile($logfile): void
  {
    $this->logFile = $logfile;
  }
  
  /**
   * Get log filename
   * @return string
   */
  public function getLogfile(): string 
  {
    return $this->logFile;
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
  public function log($level, $message, array $context = array()): bool
  {
    // if level set in psrloglevel string
    if (is_string($level)) {
      $level = $this->getLogLevelFromRsrLogLevel($level);
    }
    // check for requirement of writing
    // it is determined by log maxLevel and msg level
    if ($this->maxLevel < $level) {
      return false;
    }
    // define msg status word
    if (array_key_exists($level, self::$levelWord)) {
      $msgStatusWord = self::$levelWord[$level][1];
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
    if (!file_exists($this->folder)) {
      mkdir($this->folder);
    }
    // write message to log
    return file_put_contents(realpath($this->folder) . DIRECTORY_SEPARATOR . $this->logFile, 
              sprintf('%s :: %s :: %s' . PHP_EOL, 
                      (new \DateTime())->format('Y-m-d H:i:s:v'), 
                      $msgStatusWord, 
                      $preparedContext . $message),
              FILE_APPEND);
  }

}
