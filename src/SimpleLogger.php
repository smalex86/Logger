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
 * Class for simple logging of information
 * @author Alexandr Smirnov <mail_er@mail.ru>
 * @version 1.4
 */
class SimpleLogger {

  // the status determines what information will write to log file 
  // 4 - write all, 
  // 3 - debug messages, 
  // 2 - errors and warining messages, 
  // 1 - error messages, 
  // 0 - only important info which is defined by user
  private static $status = 4; 
  private static $logfileDefault = 'common.log';
  private static $folder = __DIR__ . '/logs/';

  /**
   * Common function for write logs
   * It writes log to file $logFileName
   * @param int $msgStatus - see above
   * @param string $msg string of message that will write to log
   * @param string $logFileName log file name without path, log files are kept in root folder 'logs'
   * @return boolean
   */
  public static function toLog($msgStatus, $msg, $logFileName = '') {
    // check for requirement of writing
    // it is determined by log status and msgStatus
    if (self::$status < $msgStatus) {
      return false;
    }
    // check empty of logFilename
    if (!$logFileName) {
      $logFileName = self::$logfileDefault;
    }
    switch ($msgStatus) {
      case 0: $msgStatusWord =  'IMPORTANT';
        break;
      case 1: $msgStatusWord =  'ERROR    ';
        break;
      case 2: $msgStatusWord =  'WARNING  ';
        break;
      case 3: $msgStatusWord =  'DEBUG    ';
        break;
      default: $msgStatusWord = 'UNKNOWN  ';
    }
    // write message to log
    return file_put_contents(realpath(self::$folder).'/'.$logFileName, 
              sprintf('%s :: %s :: %s' . PHP_EOL, date("Y-m-d H-i-s"), $msgStatusWord, 
                str_replace("\t", "", str_replace("\n", "", $msg))), 
            FILE_APPEND);
  }
  
  public function __construct($status = 4, $logFileName = '', $folder = '') {
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
  public function toLogD($status, $msg, $logFileName = '') {
    return self::toLog($status, $msg, $logFileName);
  }
  
  /**
   * Dynamic method for writing message to logfile with name logFileName (or default log filename) with level = important (0)
   * @param string $msg
   * @param string $logFileName
   * @return boolean
   */  
  public function importantD($msg, $logFileName = '') {
    return self::toLog(0, $msg, $logFileName);
  }
  
  /**
   * Dynamic method for writing message to logfile with name logFileName (or default log filename) with level = error (1)
   * @param string $msg
   * @param string $logFileName
   * @return boolean
   */
  public function errorD($msg, $logFileName = '') {
    return self::toLog(1, $msg, $logFileName);
  }
  
  /**
   * Dynamic method for writing message to logfile with name logFileName (or default log filename) with level = warning (2)
   * @param string $msg
   * @param string $logFileName
   * @return boolean
   */  
  public function warningD($msg, $logFileName = '') {
    return self::toLog(2, $msg, $logFileName);
  }
  
  /**
   * Dynamic method for writing message to logfile with name logFileName (or default log filename) with level = debug (3)
   * @param string $msg
   * @param string $logFileName
   * @return boolean
   */  
  public function debugD($msg, $logFileName = '') {
    return self::toLog(3, $msg, $logFileName);
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

}
