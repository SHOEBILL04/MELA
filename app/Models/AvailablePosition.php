<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AvailablePosition extends Model
{
    protected $table = 'vw_AvailablePositions';
    protected $primaryKey = 'position_id';
    public $incrementing = false;
    public $timestamps = false;
}
