<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryTypeVente extends Model
{
    use HasFactory;

    protected $table = 'category_type_ventes';

    protected $fillable = [
        'name',
        'description',
    ];
}
