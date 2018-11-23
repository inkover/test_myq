<?php

namespace App\Domains;

use App\Repositories\RobotCleaningSessionRepository;
use App\RobotCleaningSession;

class RobotCommanderDomain {

    protected $sessionRepository;

    /**
     * @var RobotCleaningSession
     */
    protected $session;

    protected $backOffStrategyId = -1;

    protected $backOffStrategies = [
        ['TR', 'A'],
        ['TL', 'B', 'TR', 'A'],
        ['TL', 'TL', 'A'],
        ['TR', 'B', 'TR', 'A'],
        ['TL', 'TL', 'A']
    ];

    /**
     * RobotCommanderDomain constructor.
     */
    public function __construct(RobotCleaningSessionRepository $sessionRepository)
    {
        $this->sessionRepository = $sessionRepository;
    }

    public function setCleaningSession(RobotCleaningSession $session)
    {
        $this->session = $session;
    }

    public function startCommand($commandName)
    {
        $command = CommandFactory::getCommand($commandName);
        $this->checkBatteryEnoughForCommand($command);
        try {
            $this->checkCommandIsPossible($command);
        }
        catch (\Exception $e) {
            $this->tryBackoff();
        }
        $this->executeCommand($command);
    }

    protected function checkBatteryEnoughForCommand(Command $command)
    {
        if ($this->session->battery < $command->required_battery) {
            throw new \Exception('Low battery');
        }
    }

    protected function tryBackOff() {
        $backOffCommands = $this->getBackOffCommands();
        foreach ($backOffCommands as $backOffCommand) {
            $this->startCommand($backOffCommand);
        }
    }

    protected function getBackOffCommands()
    {
        $this->backOffStrategyId ++;
        if (!isset($this->backOffStrategies[$this->backOffStrategyId])) {
            throw new \Exception('No strategies left');
        }
        return $this->backOffStrategies[$this->backOffStrategyId];
    }

    protected function executeCommand(Command $command)
    {
        $this->storeAction(
            $this->session,
            $command->getNextX(),
            $command->getNextY(),
            $command->getNextFacing(),
            $command->getNextBannery()
        );
    }

    protected function checkCommandIsPossible($command)
    {
        if ($command->getName() != 'A' && $command->getName() != 'B') {
            return true;
        }
        throw new \Exception('Command is not possible. Need to back off');
    }
}