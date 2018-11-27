<?php

namespace App\Repositories;

use App\RobotCleaningSession;
use App\RobotCommand;

class RobotCleaningSessionRepository {

    public function findByToken($token)
    {
        return RobotCleaningSession::where('token', $token)->first();
    }

    /**
     * Create new session
     *
     * @return RobotCleaningSession
     */
    public function createSession()
    {
        $session = new RobotCleaningSession();
        $session->token = md5(microtime(true));
        $session->save();

        return $session;
    }

    /**
     * Set map of the field
     *
     * @param RobotCleaningSession $session
     * @param array $map
     */
    public function setMap(RobotCleaningSession $session, array $map)
    {
        $session->map = $map;
        $session->save();
    }

    /**
     * Set start position of the robot
     *
     * @param RobotCleaningSession $session
     * @param int $x
     * @param int $y
     * @param string $facing
     * @param int $battery
     */
    public function setRobotStartCondition(RobotCleaningSession $session, int $x, int $y, string $facing, int $battery)
    {
        $session->start_x = $x;
        $session->x = $x;
        $session->start_y = $y;
        $session->y = $y;
        $session->start_facing = $facing;
        $session->facing = $facing;
        $session->start_battery = $battery;
        $session->battery = $battery;
        $session->save();
    }

    /**
     * @param RobotCleaningSession $session
     * @param array $commands
     */
    public function setCommands(RobotCleaningSession $session, array $commands)
    {
        $session->commands = $commands;
        $session->save();
    }

    /**
     * @param RobotCleaningSession $session
     * @param RobotCommand $command
     */
    public function updateRobotPosition(RobotCleaningSession $session, RobotCommand $command)
    {
        $session->x = $command->getFinishX();
        $session->y = $command->getFinishY();
        $session->facing = $command->getFinishFacing();
        $session->battery = $command->getFinishBattery();
        $session->save();
    }
}