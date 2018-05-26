<?php

/*
 * This file is part of the smalex86\logger package.
 *
 * (c) Alexandr Smirnov <mail_er@mail.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace smalex86\logger\routes;

use smalex86\logger\Route;

/**
 * Class for file logging of information
 * @author Alexandr Smirnov <mail_er@mail.ru>
 */
class FileLogger extends Route {
  
  /**
   * Log file name
   * @var string
   */
  public $logFile = 'syslog.log';
  /**
   * Log folder
   * @var string
   */
  public $folder  = __DIR__ . '/logs/';
  
  /**
   * Constructor
   */
  public function __construct(array $attributes = []) {
    parent::__construct($attributes);
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
    $msgStatusWord = $this->getStatusWord($level);
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
