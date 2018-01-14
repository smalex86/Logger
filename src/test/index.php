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

require '../SimpleLogger.php';

use smalex86\logger\SimpleLogger;

$logger = new SimpleLogger;

$logger->emergency('emergency test', ['test'=>'value', '32']);
$logger->alert('alert test', ['test'=>'value', 1=>'33']);
$logger->critical('critical test');
$logger->error('error test', ['34', '35']);
$logger->warning('warning test', ['36', '37']);
$logger->notice('notice test');
$logger->info('info', ['class'=>'Logger', 'method'=>'getName', '38']);
$logger->debug('debug test', [__LINE__]);
$logger->debugD('debugD test', [__LINE__], 'D.log');
$logger->errorD('errorD test', ['class'=>'Class', __LINE__], 'D.log');
$logger->importantD('importantD test', [], 'D.log');
$logger->warningD('warningD test', [__LINE__], 'D.log');

$logger->toLogD(7, 'toLogD test', [__LINE__], 'D.log');
$logger->log(1, 'log test', []);
$logger->log('error', 'log test 2', []);

echo $logger->getLogFolder();

echo 'ok';