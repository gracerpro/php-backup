<?php
namespace PhpBackup;

class ConsoleOutput
{

    /** @var ConsoleOutput */
    private static $instance;

    /**
     * @return ConsoleOutput
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new ConsoleOutput();
        }
        return self::$instance;
    }

    public function printMessage($message)
    {
        echo $message, "\n";
    }
}
