<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Packages extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'packages';
    
    protected $fillable = [
        'recipient_name','recipient_address','recipient_phone','resi_number','created_by'
    ];
    
    
}
