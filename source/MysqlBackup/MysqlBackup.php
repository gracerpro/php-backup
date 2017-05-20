<?php
namespace MysqlBackup;

//use MysqlBackup\Config;

//include_once $projectDir . '/Config.php';
//include_once $projectDir . '/InputParameters.php';

class MysqlBackup
{

    /** @var Config */
    private $config;
    
    /** @var InputParameters */
    private $inputParameters;

    const VERSION = "1.0";

    public function __construct()
    {
        $this->config = new Config();
        $this->inputParameters = new InputParameters();
    }

    public function run()
    {
        echo "MySQL backup version ", MysqlBackup::VERSION, "\n";
        echo "Use --help for additional inforation.\n";

        try {
            $this->init();
            $this->readInputParameters();
            $this->runActions();
        } catch (\Exception $ex) {
            echo "Error: ", $ex->getMessage(), "\n";
        }
    }
    
    private function init()
    {
        
    }

    private function readInputParameters()
    {
        
    }
    
    private function runActions()
    {
        
    }
}
