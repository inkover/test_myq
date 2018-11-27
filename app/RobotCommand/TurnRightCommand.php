<?php

namespace App\RobotCommand;


class TurnRightCommand extends TurnCommand {


    public static function getAlias()
    {
        return 'TR';
    }

    protected function getDirections()
    {
        return ['N' => 'E', 'E' => 'S', 'S' => 'W', 'W' => 'N'];
    }


}