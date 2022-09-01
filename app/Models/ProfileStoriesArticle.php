<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfileStoriesArticle extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'profile_id', 'image', 'title', 'text', 'status' ,'position', 'is_save'
    ];

    /**
     * Get profile of article
     */
    public function profile()
    {
        return $this->belongsTo('App\Models\Profile', 'profile_id', 'id');
    }
}
