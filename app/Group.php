<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $table = 'groups';
    public $timestamps = false;
    protected $primaryKey = 'id_group';
    protected $guarded = [''];
}
