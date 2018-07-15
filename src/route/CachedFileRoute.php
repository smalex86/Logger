<?php

/*
 * This file is part of the Smalex86 package.
 *
 * (c) Alexandr Smirnov <mail_er@mail.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace smalex86\logger\route;

use smalex86\logger\Route;

/**
 * Description of CachedFileRoute
 *
 * @author Alexandr Smirnov <mail_er@mail.ru>
 */
class CachedFileRoute extends Route {
  
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
   * Message template
   * @var string 
   */
  public $template = '{date} :: {level} :: {file}=>{line} :: {message} {context}';
  
  /**
   * Cache size in line count
   * @var int
   */
  public $cacheSize = 50;
  
  /**
   * Cache string array
   * @var array
   */
  protected $cache = [];
  
  /**
   * Constructor
   */
  public function __construct(array $attributes = []) {
    parent::__construct($attributes);
  }
  
  /**
   * Destructor for flush remaining cache to log file
   */
  public function __destruct() {
    if (count($this->cache) > 0) {
      $this->flushCacheToFile();
    }
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
  public function log($level, $message, array $context = []): bool
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
    // write message to cache or flush cache to file
    if (count($this->cache) >= $this->cacheSize) {
      return $this->flushCacheToFile();
    } else {
      $this->cache[] = $this->getLogLine($level, $message, $context);
    }
    return true;
  }

  /**
   * Generate new log line
   * @param mixed $level
   * @param string $message
   * @param array $context
   * @return string
   */
  protected function getLogLine($level, $message, array $context = array()): string
  {
    // pull file and line
    $fileLine = $this->getFileLine();
    // generate log line
    return trim(strtr($this->template, [
              '{date}' => $this->getDate(),
              '{level}' => $this->getStatusWord($level),
              '{file}' => $fileLine['file'],
              '{line}' => $fileLine['line'],
              '{message}' => $message,
              '{context}' => $this->contextStringify($context),
            ]));
  }
  
  /**
   * Flush cache string to log file
   * @return bool
   */
  protected function flushCacheToFile(): bool
  {
    // folder existing check and creating
    if (!file_exists($this->folder)) {
      mkdir($this->folder);
    }
    // put message to log file
    $result = file_put_contents(
            realpath($this->folder) . DIRECTORY_SEPARATOR . $this->logFile,
            implode(PHP_EOL, $this->cache) . PHP_EOL,
            FILE_APPEND);
    // clear cache
    unset($this->cache);
    $this->cache = [];
    return $result;
  }
  
}
