<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyVisitorCount extends Model
{
    protected $table = 'vw_DailyVisitorCount';
    protected $primaryKey = 'day_date';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
}
