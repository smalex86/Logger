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

use Psr\Log\LogLevel;
use Psr\Log\LoggerInterface;
use smalex86\logger\Route;

/**
 * Description of Logger
 *
 * @author Alexandr Smirnov <mail_er@mail.ru>
 */
class Logger implements LoggerInterface {
  
  /**
   * Route list
   * @var SplObjectStorage
   */
  public $routeList;

  /**
   * Constructor
   */
  public function __construct()
  {
    $this->routeList = new \SplObjectStorage();
  }

  /**
   * System is unusable.
   *
   * @param string $message
   * @param array  $context
   *
   * @return void
   */
  public function emergency($message, array $context = array()) {
    $this->innerLog(LogLevel::EMERGENCY, $message, $context);
  }

  /**
   * Action must be taken immediately.
   *
   * Example: Entire website down, database unavailable, etc. This should
   * trigger the SMS alerts and wake you up.
   *
   * @param string $message
   * @param array  $context
   *
   * @return void
   */
  public function alert($message, array $context = array()) {
    $this->innerLog(LogLevel::ALERT, $message, $context);
  }

  /**
   * Critical conditions.
   *
   * Example: Application component unavailable, unexpected exception.
   *
   * @param string $message
   * @param array  $context
   *
   * @return void
   */
  public function critical($message, array $context = array()) {
    $this->innerLog(LogLevel::CRITICAL, $message, $context);
  }

  /**
   * Runtime errors that do not require immediate action but should typically
   * be logged and monitored.
   *
   * @param string $message
   * @param array  $context
   *
   * @return void
   */
  public function error($message, array $context = array()) {
    $this->innerLog(LogLevel::ERROR, $message, $context);
  }

  /**
   * Exceptional occurrences that are not errors.
   *
   * Example: Use of deprecated APIs, poor use of an API, undesirable things
   * that are not necessarily wrong.
   *
   * @param string $message
   * @param array  $context
   *
   * @return void
   */
  public function warning($message, array $context = array()) {
    $this->innerLog(LogLevel::WARNING, $message, $context);
  }

  /**
   * Normal but significant events.
   *
   * @param string $message
   * @param array  $context
   *
   * @return void
   */
  public function notice($message, array $context = array()) {
    $this->innerLog(LogLevel::NOTICE, $message, $context);
  }

  /**
   * Interesting events.
   *
   * Example: User logs in, SQL logs.
   *
   * @param string $message
   * @param array  $context
   *
   * @return void
   */
  public function info($message, array $context = array()) {
    $this->innerLog(LogLevel::INFO, $message, $context);
  }

  /**
   * Detailed debug information.
   *
   * @param string $message
   * @param array  $context
   *
   * @return void
   */
  public function debug($message, array $context = array()) {
    $this->innerLog(LogLevel::DEBUG, $message, $context);
  }
  
  /**
   * @inheritdoc
   */
  public function log($level, $message, array $context = [])
  {
    $this->innerLog($level, $message, $context);
  }
  
  /**
   * Inner function log
   * @param type $level
   * @param type $message
   * @param array $context
   */
  protected function innerLog($level, $message, array $context = [])
  {
    foreach ($this->routeList as $route) {
      if (!$route instanceof Route) {
        continue;
      }
      if (!$route->isEnabled) {
        continue;
      }
      $route->log($level, $message, $context);
    }
  }
  
}
