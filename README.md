# Logger
Simple logging system

**For use write to composer.json:**

`{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/Smalex86/Logger"
        }
    ],
    "require": {
        "Smalex86/Logger": "1.4"
    }
}`

**And add next text to your project:**

`use Smalex86\Logger\SimpleLogger;` // use it with autoloader PSR-4

`$logger = new SimpleLogger(4, 'bot.log', __DIR__ . '/logs');` // if you want use dynamic object<br>
`$logger->debugD($data);` // write data to log with file 'bot.log'

`SimpleLogger::toLog($msgStatus, $msg);` // use it if you don't want use dynamic object
