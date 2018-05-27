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
 * SyslogRoute
 *
 * @author Alexandr Smirnov <mail_er@mail.ru>
 */
class SyslogRoute extends Route {
  
  /**
   * Template message
   * @var string 
   */
  public $template = "{message} {context}";

  /**
   * @inheritdoc
   */
  public function log($level, $message, array $context = [])
  {
    $level = $this->getLogLevelFromRsrLogLevel($level);
    if ($level == -1) {
      return;
    }
    // check for requirement of writing
    // it is determined by log maxLevel and msg level
    if ($this->maxLevel < $level) {
      return false;
    }
    syslog($level, trim(strtr($this->template, [
        '{message}' => $message,
        '{context}' => $this->contextStringify($context),
    ])));
  }
  
}
