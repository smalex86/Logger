# Logger
Simple logging system suitable with PsrLog.
Package includes:
* SimpleLogger
* SimpleLoggerFactory
* SimpleStaticLogger - not suitable with PsrLog
* tests

## To use this logger write to composer.json: ##

```
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/smalex86/logger"
        }
    ],
    "require": {
        "smalex86/logger": "1.6.1"
    }
}
```

## And add text below to your project:

Use it with autoloader PSR-4:
```
use smalex86\logger\SimpleLogger;
```
If you want to use dynamic object suitable with Psr\Log\LoggerInterface write to project these commands:
```
$logger = new SimpleLogger(4, 'syslog.log', __DIR__ . '/logs');
$logger->info('info', ['class'=>'Logger', 'method'=>'getName', '38']); // PsrLog style
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
```
