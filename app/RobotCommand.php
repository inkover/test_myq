<?php

namespace App;

abstract class RobotCommand {

    /**
     * @var array
     */
    protected $map;

    /**
     * @var int
     */
    protected $startX;

    /**
     * @var int
     */
    protected $startY;

    /**
     * @var string
     */
    protected $startFacing;

    /**
     * @var int
     */
    protected $startBattery;

    /**
     * @var int
     */
    protected $finishX;

    /**
     * @var int
     */
    protected $finishY;

    /**
     * @var string
     */
    protected $finishFacing;

    /**
     * @var int
     */
    protected $finishBattery;

    /**
     * @return string
     */
    abstract public static function getAlias();

    /**
     * @return int
     */
    abstract public function getBatteryUsage();

    /**
     * @throws \Exception
     */
    public function execute() {
        $this->finishBattery -= $this->getBatteryUsage();
    }

    /**
     * @return int
     */
    public function getStartX() {
        return $this->startX;
    }

    /**
     * @return int
     */
    public function getStartY(){
        return $this->startY;
    }

    /**
     * @return string
     */
    public function getStartFacing() {
        return $this->startFacing;
    }

    /**
     * @return int
     */
    public function getStartBattery() {
        return $this->startBattery;
    }

    /**
     * @return int
     */
    public function getFinishX() {
        return $this->finishX;
    }

    /**
     * @return int
     */
    public function getFinishY(){
        return $this->finishY;
    }

    /**
     * @return string
     */
    public function getFinishFacing() {
        return $this->finishFacing;
    }

    /**
     * @return int
     */
    public function getFinishBattery() {
        return $this->finishBattery;
    }

    /**
     * @param array $map
     * @param int $x
     * @param int $y
     * @param string $facing
     * @param int $battery
     * @return $this
     */
    public function setStartPosition(array $map, int $x, int $y, string $facing, int $battery)
    {
        $this->map = $map;

        $this->startX = $x;
        $this->startY = $y;
        $this->startFacing = $facing;
        $this->startBattery = $battery;

        $this->finishX = $x;
        $this->finishY = $y;
        $this->finishFacing = $facing;
        $this->finishBattery = $battery;

        return $this;
    }

    /**
     * @return bool
     */
    public function isBatteryEnough()
    {
        return $this->startBattery >= $this->getBatteryUsage();
    }

    /**
     * @return bool
     */
    public function isPossible() {
        return true;
    }

}