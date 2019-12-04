<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attach extends Model
{
    protected $table = 'attachments';
    public $timestamps = false;
    protected $primaryKey = 'id_attach';
    protected $guarded = [''];
}
