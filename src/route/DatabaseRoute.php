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

use PDO;
use smalex86\logger\Route;

/**
 * DatabaseRoute
 * Class for write logs to database
 *
 * This reruire table with fields structure:
 *  date datetime,
 *  level varchar,
 *  file varchar,
 *  line integer,
 *  message text,
 *  context text
 * 
 * MySql code example:
 * 
 * CREATE TABLE `project_log` ( 
 *   `id` INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT, 
 *   `date` DATETIME NULL, 
 *   `level` VARCHAR(20) NULL, 
 *   `file` VARCHAR(300) NULL, 
 *   `line` INT UNSIGNED NULL, 
 *   `message` TEXT NULL, 
 *   `context` TEXT NULL
 * );
 * 
 * @author Alexandr Smirnov <mail_er@mail.ru>
 */
class DatabaseRoute extends Route {
  
  /**
   * Data Source Name
   * @var string 
   * @see http://php.net/manual/en/pdo.construct.php
   */
  public $dsn;
  /**
   * Database user name
   * @var string 
   */
  public $username;
  /**
   * Database user password
   * @var string
   */
  public $password;
  /**
   * Database table name
   * @var string
   */
  public $table;

  /**
   * PDO connection
   * @var
   */
  private $connection;
 
  /**
   * @inheritdoc
   */
  public function __construct(array $attributes = [])
  {
    parent::__construct($attributes);
    $options = [
      \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
      \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
      \PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    $this->connection = new PDO($this->dsn, $this->username, $this->password, $options);
  }

  /**
   * @inheritdoc
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
    
    $statement = $this->connection->prepare(
      'INSERT INTO ' . $this->table . ' (date, level, file, line, message, context) ' .
      'VALUES (:date, :level, :file, :line, :message, :context)'
    );
    $date = $this->getDate();
    $statement->bindParam(':date', $date);
    $statement->bindParam(':level', $msgStatusWord);
    $statement->bindParam(':file', $fileLine['file']);
    $statement->bindParam(':line', $fileLine['line']);
    $statement->bindParam(':message', $message);
    $context = $this->contextStringify($context);
    $statement->bindParam(':context', $context);
    return $statement->execute();
  }
  
}
