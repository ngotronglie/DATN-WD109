<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RevenueStatistic extends Model
{
    protected $table = 'revenue_statistics';
    public $timestamps = false; // view không có created_at / updated_at
}
