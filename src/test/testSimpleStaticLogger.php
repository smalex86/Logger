<?php

/*
 * This file is part of the smalex86\logger package.
 *
 * (c) Alexandr Smirnov <mail_er@mail.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

include_once dirname(dirname(__DIR__)) . '/vendor/autoload.php';

use smalex86\logger\SimpleStaticLogger;

//$logger = new SimpleLogger(7, '', dirname(__DIR__, 2) . '/logs/');
SimpleStaticLogger::init(7, 'static.log', dirname(__DIR__, 2) . '/logs/');

SimpleStaticLogger::emergency('emergency test', ['test'=>'value', '32']);
SimpleStaticLogger::alert('alert test', ['test'=>'value', 1=>'33']);
SimpleStaticLogger::critical('critical test');
SimpleStaticLogger::error('error test', ['34', '35']);
SimpleStaticLogger::warning('warning test', ['36', '37']);
SimpleStaticLogger::notice('notice test');
SimpleStaticLogger::info('info', ['class'=>'Logger', 'method'=>'getName', '38']);
SimpleStaticLogger::debug('debug test', [__LINE__]);
SimpleStaticLogger::log(1, 'log test', []);
SimpleStaticLogger::log('error', 'log test 2', []);

echo SimpleStaticLogger::getLogFolder() . PHP_EOL;
echo SimpleStaticLogger::getLogFile() . PHP_EOL;

echo 'ok';