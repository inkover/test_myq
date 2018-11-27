<?php

namespace App\RobotCommand;

use App\RobotCleaningSession;
use App\RobotCommand;

class Factory {

    protected $commandAliases;

    protected $commandClasses;

    /**
     * @param RobotCleaningSession $session
     * @param string $commandAlias
     * @return RobotCommand
     * @throws \Exception
     */
    public function getCommand(RobotCleaningSession $session, string $commandAlias)
    {
        $aliases = $this->getCommandAliases();
        if (!isset($aliases[$commandAlias])) {
            throw new \Exception('Command with "' . $commandAlias . '" is not found');
        }
        $commandClass = $aliases[$commandAlias];

        /** @var RobotCommand $result */
        $result = new $commandClass();
        $result->setStartPosition($session->map, $session->x, $session->y, $session->facing, $session->battery);
        return $result;
    }

    /**
     * @return array
     * @throws \ReflectionException
     */
    protected function getCommandAliases()
    {
        if (is_null($this->commandAliases)) {
            $this->commandAliases = [];
            foreach ($this->getCommandClasses() as $commandClass) {
                $this->commandAliases[$commandClass::getAlias()] = $commandClass;
            }
        }
        return $this->commandAliases;
    }

    /**
     * @return array
     * @throws \ReflectionException
     */
    protected function getCommandClasses()
    {
        return [
            AdvanceCommand::class,
            BackCommand::class,
            CleanCommand::class,
            TurnLeftCommand::class,
            TurnRightCommand::class
        ];
    }

}