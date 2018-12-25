<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Calendar extends Model
{
    protected $fillable = ['name', 'color', 'user_id', 't_start', 't_end', 'media', 'notify'];
}
