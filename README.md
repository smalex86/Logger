# Logger
Simple logging system

## For use it write to composer.json: ##

```
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/Smalex86/Logger"
        }
    ],
    "require": {
        "Smalex86/Logger": "1.5.1"
    }
}
```

## And add next text to your project: ##

Use it with autoloader PSR-4:
```
use Smalex86\Logger\SimpleLogger;
```
If you want use dynamic object suitable with Psr\Log\LoggerInterface:
```
$logger = new SimpleLogger(4, 'syslog.log', __DIR__ . '/logs');
$logger->info('info', ['class'=>'Logger', 'method'=>'getName', '38']); // PsrLog style
$logger->debugD($data, $context, $logFilename); // Simple logger style
```
And use it if you don't want use dynamic object:
```
SimpleLogger::toLog($msgStatus, $msg);
```

### Some examples ###
```
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
```
