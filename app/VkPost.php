<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VkPost extends Model
{
    protected $table = 'vk_posts';
    public $timestamps = false;
    protected $primaryKey = 'id_vk_post';
    protected $guarded = [''];
    public $incrementing = false;


    public function attachments()
    {
        return $this->hasMany(Attach::class,'id_post');
    }

}
