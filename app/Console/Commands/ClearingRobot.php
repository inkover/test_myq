<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ClearingRobot extends Command
{

    const OPTION_SOURCE_FILE = 'sources_file';
    const OPTION_RESULT_FILE = 'result_file';

    const DATA_FIELD_MAP = 'map';
    const DATA_FIELD_START = 'start';
    const DATA_FIELD_COMMANDS = 'commands';
    const DATA_FIELD_BATTERY = 'battery';

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
     * Source data
     *
     * @var array
     */
    protected $sourceData;

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
            $this->parseSourceFile();
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
        if (!empty($optionValue) && $optionValue[0] != '/') {
            $result = base_path($optionValue);
        }
        if (empty($result)) {
            throw new \Exception('Option ' . $optionName . ' "' . $optionValue . '" is not a valid path');
        }
        return $result;
    }

    /**
     * Parse source file and set source data
     * @throws \Exception
     */
    protected function parseSourceFile()
    {
        $json = file_get_contents($this->sourceFilePath);
        if (empty($json)) {
            throw new \Exception('Source file is empty or not accessible');
        }

        $data = \json_decode($json, true);
        if (json_last_error()) {
            throw new \Exception('Source data JSON parse error: ' . json_last_error_msg());
        }

        if (!is_array($data)) {
            throw new \Exception('Source data is not a valid array');
        }
        $this->validateSourceData($data, 'map');
        $this->validateSourceData($data, 'start');
        $this->validateSourceData($data, 'commands');
        $this->validateSourceData($data, 'battery', true);

        $this->sourceData = $data;
        dump($this->sourceData);
    }

    /**
     * Check source data fields are valid
     *
     * @param array $data
     * @param string $field
     * @param bool $isScalar
     * @throws \Exception
     */
    protected function validateSourceData($data, $field, $isScalar = false)
    {
        $exceptionPrefix = 'Source data field "' . $field . '" ';
        if (!isset($data[$field])) {
            throw new \Exception($exceptionPrefix . 'is not found');
        }
        $value = $data[$field];
        if (empty($value)) {
            throw new \Exception($exceptionPrefix . 'is empty');
        }
        if ($isScalar) {
            if (!is_scalar($value)) {
                throw new \Exception($exceptionPrefix . 'expected to be scalar value');
            }
            return;
        }
        if (!(is_array($value) && count($value))) {
            throw new \Exception($exceptionPrefix . 'expected to be not empty array');
        }
    }
}
