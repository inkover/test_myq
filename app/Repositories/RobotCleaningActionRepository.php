<?php

namespace App\Repositories;

use App\RobotCleaningAction;
use App\RobotCleaningSession;
use App\RobotCommand;

class RobotCleaningActionRepository {

    public function storeRobotAction(RobotCleaningSession $session, RobotCommand $command)
    {
        $action = new RobotCleaningAction();
        $action->command = $command::getAlias();
        $action->session_id = $session->id;
        $action->start_x = $command->getStartX();
        $action->start_y = $command->getStartY();
        $action->start_facing = $command->getStartFacing();
        $action->start_battery = $command->getStartBattery();
        $action->finish_x = $session->x;
        $action->finish_y = $session->y;
        $action->finish_facing = $session->facing;
        $action->finish_battery = $session->battery;
        $action->save();
    }
    
}