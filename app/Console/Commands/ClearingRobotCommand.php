<?php

namespace App\Console\Commands;

use App\Domains\RobotCommanderDomain;
use App\RobotCleaningSession;
use App\Domains\ConsoleFilesDomain;
use Illuminate\Console\Command;
use App\Repositories\RobotCleaningSessionRepository;

class ClearingRobotCommand extends Command
{

    const OPTION_SOURCE_FILE = 'sources_file';
    const OPTION_RESULT_FILE = 'result_file';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'robot:clean {' . self::OPTION_SOURCE_FILE . '} {' . self::OPTION_RESULT_FILE . '}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clearing robot console interface';

    /**
     * @var ConsoleFilesDomain
     */
    protected $filesDomain;

    /**
     * @var RobotCleaningSessionRepository
     */
    protected $sessionRepository;

    /**
     * @var RobotCommanderDomain
     */
    protected $commanderDomain;

    /**
     * Cleaning session instance
     *
     * @var RobotCleaningSession
     */
    protected $cleaningSession;

    /**
     * Create a new command instance.
     * @param ConsoleFilesDomain $filesDomain
     * @param RobotCleaningSessionRepository $sessionRepository
     * @param RobotCommanderDomain $commanderDomain
     * @return void
     */
    public function __construct(
        ConsoleFilesDomain $filesDomain,
        RobotCleaningSessionRepository $sessionRepository,
        RobotCommanderDomain $commanderDomain
    )
    {
        $this->filesDomain = $filesDomain;
        $this->sessionRepository = $sessionRepository;
        $this->commanderDomain = $commanderDomain;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        try {
            $this->checkParameters();
            $this->parseSourceFile();
            $this->initSession();
            $this->setMap();
            $this->setRobotStartCondition();
            $this->setCommands();
            $this->executeCommands();
//            $this->generateResultFile();
            echo 'OK!';
        }
        catch (\Exception $e) {
            dump($e);
            $this->error($e->getMessage());
        }
    }

    /**
     * Check Incoming parameters and files existence
     * @throws \Exception
     */
    protected function checkParameters()
    {
        $this->filesDomain->checkSourceFile($this->argument(self::OPTION_SOURCE_FILE));
        $this->filesDomain->checkResultFile($this->argument(self::OPTION_RESULT_FILE));
    }

    /**
     * Parse source file and set source data
     * @throws \Exception
     */
    protected function parseSourceFile()
    {
        $this->filesDomain->parseSourceFile();
    }

    /**
     * Initiate cleaning session
     */
    protected function initSession()
    {
        $this->cleaningSession = $this->sessionRepository->createSession();
    }

    /**
     * Set filed map
     */
    protected function setMap()
    {
        $this->sessionRepository->setMap($this->cleaningSession, $this->filesDomain->getSourceMap());
    }

    /**
     * Set initial robot condition
     */
    protected function setRobotStartCondition()
    {
        $this->sessionRepository->setRobotStartCondition($this->cleaningSession, $this->filesDomain->getStartX(), $this->filesDomain->getStartY(), $this->filesDomain->getStartFacing(), $this->filesDomain->getStartBattery());
    }

    /**
     * Set array of commands
     */
    protected function setCommands()
    {
        $this->sessionRepository->setCommands($this->cleaningSession, $this->filesDomain->getSourceCommands());
    }

    protected function executeCommands()
    {
        foreach ($this->cleaningSession->commands as $command) {
            $this->commanderDomain->startCommand($this->cleaningSession, $command);
        }
    }
}
