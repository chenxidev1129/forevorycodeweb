<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfileGraveSite extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'profile_id', 'image', 'address','country', 'state', 'city', 'zip_code', 'note', 'lat' ,'lang' ,'status'
    ];
}
