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

use smalex86\logger\Route;

/**
 * ConsoleRoute
 *
 * @author Alexandr Smirnov <mail_er@mail.ru>
 */
class ConsoleRoute extends Route {
    
  public $template = '{date} :: {level} :: {file}=>{line} :: {message} '
          . '{context}';
    
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
    
    // pull file and line
    $fileLine = $this->getFileLine();
    
    // put message to console
    echo strtr($this->template, [
              '{date}' => $this->getDate(),
              '{level}' => $msgStatusWord,
              '{file}' => $fileLine['file'],
              '{line}' => $fileLine['line'],
              '{message}' => $message,
              '{context}' => $this->contextStringify($context),
            ]) . PHP_EOL;
    return true;    
  }
    
}
