<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


/**
 * Class RobotCleaningAction
 *
 * @package App
 * @property int $id
 * @property string $command
 * @property int $start_x
 * @property int $start_y
 * @property string $start_facing
 * @property int $start_battery
 * @property int $finish_x
 * @property int $finish_y
 * @property string $finish_facing
 * @property int $finish_battery
 */
class RobotCleaningAction extends Model
{

    protected $fillable = ['session_id', 'command', 'start_x', 'start_y', 'start_facing', 'start_battery', 'finish_x', 'finish_y', 'finish_facing', 'finish_battery'];

    public function session()
    {
        return $this->belongsTo('App\RobotCleaningSession', 'session_id');
    }

}
