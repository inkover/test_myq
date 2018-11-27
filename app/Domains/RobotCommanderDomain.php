<?php

namespace App\Domains;

use App\Repositories\RobotCleaningActionRepository;
use App\Repositories\RobotCleaningSessionRepository;
use App\RobotCleaningSession;
use App\RobotCommand;
use App\RobotCommand\Factory as CommandFactory;

class RobotCommanderDomain {

    /**
     * @var RobotCleaningSessionRepository
     */
    protected $sessionRepository;

     /**
     * @var RobotCleaningActionRepository
     */
    protected $actionRepository;

    /**
     * @var CommandFactory
     */
    protected $commandFactory;

    /**
     * @var RobotCleaningSession
     */
    protected $session;

    protected $backOffStrategyId = 0;

    protected $backOffStrategies = [
        ['TR', 'A'],
        ['TL', 'B', 'TR', 'A'],
        ['TL', 'TL', 'A'],
        ['TR', 'B', 'TR', 'A'],
        ['TL', 'TL', 'A']
    ];

    /**
     * RobotCommanderDomain constructor.
     * @param RobotCleaningSessionRepository $sessionRepository
     * @param RobotCleaningActionRepository $actionRepository
     * @param CommandFactory $commandFactory
     */
    public function __construct(RobotCleaningSessionRepository $sessionRepository, RobotCleaningActionRepository $actionRepository, CommandFactory $commandFactory)
    {
        $this->sessionRepository = $sessionRepository;
        $this->actionRepository = $actionRepository;
        $this->commandFactory = $commandFactory;
    }

    /**
     * @param RobotCleaningSession $session
     * @throws \Exception
     */
    public function startSessionCommands(RobotCleaningSession $session)
    {
        $this->session = $session;
        $this->startCommandsSequence($this->session->commands);
    }

    /**
     * @param array $commands
     * @throws \Exception
     */
    protected function startCommandsSequence(array $commands)
    {
        foreach ($commands as $command) {
            $this->startCommand($command);
        }
    }

    /**
     * @param string $commandAlias
     * @throws \Exception
     */
    protected function startCommand(string $commandAlias)
    {
        $command = $this->commandFactory->getCommand($this->session, $commandAlias);
        if (!$command->isBatteryEnough()) {
            throw new \Exception('Low battery');
        }
        try {
            $this->checkCommandIsPossible($command);
            $this->executeCommand($command);
        }
        catch (\Exception $e) {
            dump($e->getMessage());
            $this->tryBackOff();
        }
    }

    /**
     * @throws \Exception
     */
    protected function tryBackOff() {
        $this->startCommandsSequence($this->getBackOffSequence());
    }

    /**
     * @return array
     * @throws \Exception
     */
    protected function getBackOffSequence()
    {
        if (!isset($this->backOffStrategies[$this->backOffStrategyId])) {
            throw new \Exception('No strategies left');
        }
        $result = $this->backOffStrategies[$this->backOffStrategyId];
        $this->backOffStrategyId ++;
        return $result;
    }

    /**
     * @param RobotCommand $command
     * @throws \Exception
     */
    protected function executeCommand(RobotCommand $command)
    {
        $command->execute();
        $this->storeAction($command);
    }

    /**
     * @param RobotCommand $command
     * @throws \Exception
     */
    protected function checkCommandIsPossible(RobotCommand $command)
    {
        if (!$command->isPossible()) {

            throw new \Exception('RobotCommand is not possible. Need to back off');
        }
    }

    /**
     * @param RobotCommand $command
     */
    protected function storeAction(RobotCommand $command)
    {
        $this->sessionRepository->updateRobotPosition($this->session, $command);
        $this->actionRepository->storeRobotAction($this->session, $command);
    }
}