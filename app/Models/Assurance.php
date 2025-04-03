<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assurance extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'coverage_percentage', 'description'];

    public function patients()
    {
        return $this->hasMany(Patient::class);
    }
}
