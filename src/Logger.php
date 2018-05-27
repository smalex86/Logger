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

use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface;
use smalex86\logger\Route;

/**
 * Description of Logger
 *
 * @author Alexandr Smirnov <mail_er@mail.ru>
 */
class Logger extends AbstractLogger implements LoggerInterface {
  
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
   * @inheritdoc
   */
  public function log($level, $message, array $context = [])
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
