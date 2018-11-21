<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ClearingRobot extends Command
{

    const OPTION_SOURCE_FILE = 'sources_file';

    const OPTION_RESULT_FILE = 'result_file';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'robot:clean {' . self::OPTION_SOURCE_FILE . '} {' . self::OPTION_RESULT_FILE . '}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clearing robot console interface';

    /**
     * Full path of source file
     *
     * @var string
     */
    protected $sourceFilePath;

    /**
     * Full path of result file
     *
     * @var string
     */
    protected $resultFilePath;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        try {
            $this->checkParameters();
//            $this->parseSourceFile();
//            $this->initSession();
//            $this->setMap();
//            $this->setRobotStartPosition();
//            $this->setCommands();
//            $this->setBattery();
//            $this->executeCommands();
//            $this->generateResultFile();
            echo 'OK!';
        }
        catch (\Exception $e) {
            dump($e);
            $this->error($e->getMessage());
        }
    }

    /**
     * Check Incoming parameters and files existence
     * @throws \Exception
     */
    protected function checkParameters()
    {
        $this->sourceFilePath = $this->checkSourceFile();
        $this->resultFilePath = $this->checkResultFile();
    }

    /**
     * Check source file exists and readable
     *
     * @return string
     * @throws \Exception
     */
    protected function checkSourceFile()
    {
        $filePath = $this->getFilePath(self::OPTION_SOURCE_FILE);
        if (!is_readable($filePath)) {
            throw new \Exception('Source file is not readable');
        }
        return $filePath;
    }

    /**
     * Check result file does not exists or writable
     *
     * @throws \Exception
     */
    protected function checkResultFile()
    {
        $filePath = $this->getFilePath(self::OPTION_RESULT_FILE);

        if (file_exists($filePath)) {
            if (!is_file($filePath)) {
                throw new \Exception('Result file is not a regular file');
            }
            unlink($filePath);
            if (file_exists($filePath)) {
                throw new \Exception('Result file is not deletable');
            }
        }
        if (file_put_contents($filePath, '[]') === false) {
            throw new \Exception('Failed to write empty data to result file');
        }
        return $filePath;
    }

    /**
     * Get full file path from command options
     *
     * @param $optionName
     * @return bool|string
     * @throws \Exception
     */
    protected function getFilePath($optionName)
    {
        $optionValue = $this->argument($optionName);
        $result = realpath($optionValue);
        if (empty($result)) {
            throw new \Exception('Option "' . $optionValue . '" is not a valid path');
        }
        return $result;
    }
}
