<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesTarget extends Model
{
    protected $tables = 'sales_targets';
    protected $guarded = ['id'];
    public function sales()
    {
        return $this->belongsTo(Sales::class);
    }

    public function branch()
    {
        return $this->sales->employee->branch ?? null; // jika `employee` punya relasi ke `branch`
    }
}
