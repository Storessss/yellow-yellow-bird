<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TSBMLevelModel extends Model
{
    protected $table = 'tsbm_game_levels';
    protected $fillable = [
        'level_name',
        'level_data',
        'level_code',
    ];
}
