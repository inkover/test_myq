<?php

namespace App\RobotCommand;

use App\RobotCommand;

abstract class MoveCommand extends RobotCommand
{

    /**
     * @return int
     */
    abstract protected function getMovementStep();

    public function execute()
    {
        $finishField = $this->getFinishField();
        $this->$finishField += $this->getValueDiff();
        parent::execute();
    }

    public function isPossible()
    {
        $x = $this->startX;
        $y = $this->startY;
        ${$this->getFinishCoordinate()} += $this->getValueDiff();
        return (isset($this->map[$y][$x]) && $this->map[$y][$x] == 'S');
    }

    protected function getValueDiff()
    {
        $factor = -1;
        if ($this->finishFacing == 'N' || $this->finishFacing == 'E') {
            $factor = 1;
        }
        return $this->getMovementStep() * $factor;
    }

    protected function getFinishCoordinate()
    {
        if ($this->finishFacing == 'N' || $this->finishFacing == 'S') {
            return 'y';
        }
        return 'x';
    }

    protected function getFinishField()
    {
        return 'finish' . strtoupper($this->getFinishCoordinate());
    }


}