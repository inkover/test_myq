<?php

namespace App\RobotCommand;

use App\RobotCommand;

class CleanCommand extends RobotCommand{

    public static function getAlias()
    {
        return 'C';
    }

    public function getBatteryUsage()
    {
        return 3;
    }


}