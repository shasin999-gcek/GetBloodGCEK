<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Donar extends Model
{
    protected $fillable = [
        'name', 'age', 'blood_group', 'weight', 
        'contact_number', 'home_town', 'district'
    ];
}
