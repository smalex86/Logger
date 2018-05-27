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

use smalex86\logger\SimpleLoggerFactory;

SimpleLoggerFactory::init(7, 'factory.log', dirname(__DIR__, 2) . '/logs/');
$logger = SimpleLoggerFactory::getFileLogger();

$logger->emergency('emergency test', ['test'=>'value', '32']);
$logger->alert('alert test', ['test'=>'value', 1=>'33']);
$logger->critical('critical test');
$logger->error('error test', ['34', '35']);
$logger->warning('warning test', ['36', '37']);
$logger->notice('notice test');
$logger->info('info', ['class'=>'Logger', 'method'=>'getName', '38']);
$logger->debug('debug test', [__FILE__,__LINE__]);
$logger->log(1, 'log test', []);
$logger->log('error', 'log test 2', []);

echo $logger->folder . $logger->logFile . PHP_EOL;
echo 'ok';