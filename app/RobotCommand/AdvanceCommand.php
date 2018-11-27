<?php

namespace App\RobotCommand;

class AdvanceCommand extends MoveCommand
{

    public static function getAlias()
    {
        return 'A';
    }

    public function getBatteryUsage()
    {
        return 2;
    }

    protected function getMovementStep()
    {
        return 1;
    }

}