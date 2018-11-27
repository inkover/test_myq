<?php

namespace App\RobotCommand;

use App\RobotCommand;

abstract class TurnCommand extends RobotCommand {

    abstract protected function getDirections();

    public function getBatteryUsage()
    {
        return 1;
    }

    /**
     * @throws \Exception
     */
    public function execute()
    {
        $directions = $this->getDirections();
        if (!isset($directions[$this->startFacing])) {
            throw new \Exception('Direction "' . $this->startFacing . '" is not supported');
        }
        $this->finishFacing = $directions[$this->startFacing];

        parent::execute();
    }

}