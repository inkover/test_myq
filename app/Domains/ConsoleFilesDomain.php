<?php

namespace App\Domains;


class ConsoleFilesDomain {

    const DATA_FIELD_MAP = 'map';
    const DATA_FIELD_START = 'start';
    const DATA_FIELD_COMMANDS = 'commands';
    const DATA_FIELD_BATTERY = 'battery';
    const DATA_FIELD_START_X = 'X';
    const DATA_FIELD_START_Y = 'Y';
    const DATA_FIELD_START_FACING = 'facing';

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
     * Check source file exists and readable
     * @param string $argumentValue
     * @throws \Exception
     */
    public function checkSourceFile($argumentValue)
    {
        $filePath = $this->getFilePath($argumentValue);
        if (!is_readable($filePath)) {
            throw new \Exception('Source file is not readable');
        }
        $this->sourceFilePath = $filePath;
    }

    /**
     * Check result file does not exists or writable
     *
     * @param string $argumentValue
     * @throws \Exception
     */
    public function checkResultFile($argumentValue)
    {
        $filePath = $this->getFilePath($argumentValue);

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
        $this->resultFilePath = $filePath;
    }

    /**
     * Parse source file and set source data
     * @throws \Exception
     */
    public function parseSourceFile()
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
        $this->validateSourceData($data, self::DATA_FIELD_MAP);
        $this->validateSourceData($data, self::DATA_FIELD_START);
        $this->validateSourceData($data, self::DATA_FIELD_COMMANDS);
        $this->validateSourceData($data, self::DATA_FIELD_BATTERY, true);

        $this->sourceData = $data;
    }

    /**
     * Get field map
     *
     * @return array
     */
    public function getSourceMap()
    {
        return $this->getSourceData(self::DATA_FIELD_MAP);
    }

    /**
     * Get X of initial position of the Robot
     *
     * @return int
     */
    public function getStartX()
    {
        return $this->getStartSourceData(self::DATA_FIELD_START_X);
    }


    /**
     * Get Y of initial position of the Robot
     *
     * @return int
     */
    public function getStartY()
    {
        return $this->getStartSourceData(self::DATA_FIELD_START_Y);
    }

    /**
     * Get initial facing of the Robot
     *
     * @return string
     */
    public function getStartFacing()
    {
        return $this->getStartSourceData(self::DATA_FIELD_START_FACING);
    }

    /**
     * Get array of commands to do
     *
     * @return array
     */
    public function getSourceCommands()
    {
        return $this->getSourceData(self::DATA_FIELD_COMMANDS);
    }

    /**
     * Get initial battery level of the Robot
     *
     * @return int
     */
    public function getStartBattery()
    {
        return $this->getSourceData(self::DATA_FIELD_BATTERY);
    }

    /**
     * Get full file path from command options
     *
     * @param $optionValue
     * @return bool|string
     * @throws \Exception
     */
    protected function getFilePath($optionValue)
    {
        if (!empty($optionValue) && $optionValue[0] != '/') {
            $result = base_path($optionValue);
        }
        if (empty($result)) {
            throw new \Exception('File path "' . $optionValue . '" is not a valid path');
        }
        return $result;
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

    /**
     * Get data from source file
     *
     * @param $key
     * @param array $default
     * @return array|mixed
     */
    protected function getSourceData($key, $default = [])
    {
        if (!isset($this->sourceData[$key])) {
            return $default;
        }
        return $this->sourceData[$key];
    }

    /**
     * Get data from "start" field from source data
     *
     * @param $key
     * @return mixed|null
     */
    protected function getStartSourceData($key) {
        $startData = $this->getSourceData(self::DATA_FIELD_START);
        if (!isset($startData[$key])) {
            return null;
        }
        return $startData[$key];
    }

}