<?php

/*
 * This file is part of the smalex86\logger package.
 *
 * (c) Alexandr Smirnov <mail_er@mail.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace smalex86\logger\route;

use smalex86\logger\route\FileRoute;

/**
 * Class for file logging of information with pid info into msg line
 * @author Alexandr Smirnov <mail_er@mail.ru>
 */
class FileRouteWithPid extends FileRoute {
  
  /**
   * Message template with pid 
   * @var string 
   */
  public $template = '{date} :: {pid} :: {level} :: {file}=>{line} :: {message} {context}';
    
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
    // define msg status word
    $msgStatusWord = $this->getStatusWord($level);
    
    // folder existing check and creating
    if (!file_exists($this->folder)) {
      mkdir($this->folder);
    }
    
    // pull file and line
    $fileLine = $this->getFileLine();
    
    // put message to log file
    return file_put_contents(
            realpath($this->folder) . DIRECTORY_SEPARATOR . $this->logFile, 
            trim(strtr($this->template, [
              '{date}' => $this->getDate(),
              '{pid}' => getmypid(),
              '{level}' => $msgStatusWord,
              '{file}' => $fileLine['file'],
              '{line}' => $fileLine['line'],
              '{message}' => $message,
              '{context}' => $this->contextStringify($context),
            ])) . PHP_EOL,
            FILE_APPEND);
  }
    
}
