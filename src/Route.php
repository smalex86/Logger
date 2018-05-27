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

use DateTime;
use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

/**
 * Route
 *
 * @author Alexandr Smirnov <mail_er@mail.ru>
 */
abstract class Route extends AbstractLogger implements LoggerInterface {
  /**
   * Store level word settings
   * @var array
   */  
  private static $levelWord = array(
    0 => [LogLevel::EMERGENCY, 'EMERGENCY'],
    1 => [LogLevel::ALERT,     'ALERT    '],
    2 => [LogLevel::CRITICAL,  'CRITICAL '],
    3 => [LogLevel::ERROR,     'ERROR    '],
    4 => [LogLevel::WARNING,   'WARNING  '],
    5 => [LogLevel::NOTICE,    'NOTICE   '],
    6 => [LogLevel::INFO,      'INFO     '],
    7 => [LogLevel::DEBUG,     'DEBUG    ']
  );
  /**
   * Determines enabling of route
   * @var bool
   */
  public $isEnabled = true;
  /**
   * Foramt of log date
   * @var string
   */
  public $dateFormat = DateTime::W3C;
  /**
   * Max level of log, 
   * levels more then the maxlevel will not be write to log
   * @var int
   */
  public $maxLevel = 7;
  /**
   * Message template
   * @var string 
   */
  public $template = "{message} {context}";

  /**
   * Constructor
   *
   * @param array $attributes route attributes
   */
  public function __construct(array $attributes = [])
  {
    foreach ($attributes as $attribute=>$value)
    {
      if (property_exists($this, $attribute))
      {
        switch ($attribute) {
          case 'maxLevel':
            echo 'maxLevel = ' . $value . PHP_EOL;
            $this->maxLevel = $this->getLogLevelFromRsrLogLevel($value);
            echo 'this->maxLevel = ' . $this->maxLevel . PHP_EOL;
            break;
          default:
            $this->{$attribute} = $value;
            break;
        }
      }
    }
  }

  /**
   * Current date
   *
   * @return string
   */
  public function getDate()
  {
    return (new DateTime())->format($this->dateFormat);
  }

  /**
   * Transform $context to string
   *
   * @param array $context
   * @return string
   */
  public function contextStringify(array $context = [])
  {
    return !empty($context) ? json_encode($context) : '';
  }
  
  /**
   * Return loglevel index by psr log level word
   * 
   * @param string|int $psrLogLevel
   * @return int
   */
  protected function getLogLevelFromRsrLogLevel($psrLogLevel) 
  {
    if (is_numeric($psrLogLevel) && $psrLogLevel >= 0 && $psrLogLevel <= 7) {
      return $psrLogLevel;
    }
    foreach (self::$levelWord as $key => $value) {
      if ($value[0] == $psrLogLevel) {
        return $key;
      }
    }
    return -1;
  }
  
  /**
   * Get status word for level (int)
   * @param int $level
   * @return string
   */
  protected function getStatusWord(int $level): string
  {
    if (array_key_exists($level, self::$levelWord)) {
      return self::$levelWord[$level][1];
    } else {
      return 'UNKNOWN  ';
    }
  }
  
  /**
   * Get file and line from stacktrace in array view
   * @return array
   */
  protected function getFileLine(): array
  {
    $dbt = debug_backtrace();    
    $result['file'] = $dbt[count($dbt)-1]['file'];
    $result['line'] = $dbt[count($dbt)-1]['line'];
    return $result;
  }
  
}
