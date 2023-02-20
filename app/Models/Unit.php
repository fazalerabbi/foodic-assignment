<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'name',
        'abbreviation',
    ];

    /**
     * Get the ingredients that use this unit.
     */
    public function ingredients()
    {
        return $this->hasMany(Ingredient::class);
    }
}
