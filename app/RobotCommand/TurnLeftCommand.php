<?php

namespace App\RobotCommand;


class TurnLeftCommand extends TurnCommand {


    public static function getAlias()
    {
        return 'TL';
    }

    protected function getDirections()
    {
        return ['N' => 'W', 'W' => 'S', 'S' => 'E', 'E' => 'N'];
    }


}