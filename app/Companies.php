<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Companies extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'companies';
    
    protected $fillable = [
        'name'
    ];
    
}
