<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


/**
 * Class RobotCleaningSession
 *
 * @package App
 * @property int $id
 * @property string $token
 * @property array $map
 * @property int $start_x
 * @property int $start_y
 * @property string $start_facing
 * @property int $start_battery
 * @property array $commands
 * @property int $battery
 * @property int $x
 * @property int $y
 * @property string $facing
 * @property string $created_at
 * @property string $updated_at
 *
 * @property RobotCleaningAction[] $actions
 */

class RobotCleaningSession extends Model
{

    protected $fillable = ['token', 'map', 'start_x', 'start_y', 'start_facing', 'start_battery', 'commands', 'battery', 'x', 'y', 'facing'];

    protected $casts = [
        'map' => 'array',
        'commands' => 'array',
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function actions()
    {
        return $this->hasMany('App\RobotCleaningAction', 'session_id')->orderBy('id', 'desc');
    }
}
