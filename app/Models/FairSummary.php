<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FairSummary extends Model
{
    protected $table = 'vw_FairSummary';
    protected $primaryKey = 'fair_id';
    public $incrementing = false;
    public $timestamps = false;
}
