# Logger
Simple logging system suitable with PsrLog.
* Logger - base logger class including and managed routes
* Route - base class for routes
* SimpleLoggerFactory - factory simple (file) logger
* SimpleStaticLogger - not suitable with PsrLog
* route:
    * DatabaseRoute - route for database logging
    * FileRoute - ex SimpleLogger, route for file logging
    * FileRouteWithPid - route for file logging with print of process id into log message
    * CachedFileRoute - route for file logging with cache using
    * SyslogRoute - syslog route
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
        "smalex86/logger": "1.7.5"
    }
}
```

## And add text below to your project:

Use it with autoloader PSR-4:
```
use smalex86\logger\Logger;
```
If you want to use dynamic object suitable with Psr\Log\LoggerInterface write to project these commands:
```
$logger = new Logger();
$logger->routeList->attach(new smalex86\logger\route\FileRoute([
    'isEnabled' => true,
    'maxLevel' => 7,
    'logFile' => 'test.log',
    'folder' => dirname(__DIR__, 2) . '/logs/'
]));
$logger->routeList->attach(new smalex86\logger\route\CachedFileRoute([
    'isEnabled' => true,
    'maxLevel' => 7,
    'logFile' => 'cacheTest.log',
    'folder' => dirname(__DIR__, 2) . '/logs/',
    'cacheSize' => 50
]));
$logger->routeList->attach(new smalex86\logger\route\DatabaseRoute([
    'isEnabled' => true,
    'maxLevel' => 6,
    'dsn' => 'mysql:host=localhost;port=3306;dbname=test',
    'username' => 'root',
    'password' => '',
    'table' => 'project_log'    
]));
$logger->routeList->attach(new smalex86\logger\route\SyslogRoute([
    'isEnabled' => true,
    'maxLevel' => 5
]));
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
