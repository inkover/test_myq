<?php

namespace App\RobotCommand;

class BackCommand extends MoveCommand {

    public static function getAlias()
    {
        return 'B';
    }

    public function getBatteryUsage()
    {
        return 3;
    }

    protected function getMovementStep()
    {
        return -1;
    }

}