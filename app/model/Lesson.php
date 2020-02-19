<?php
namespace app\model;

use think\Model;

class Lesson extends Model
{
    protected $table = 'lessons';
    protected $createTime = 'created_at';
    protected $updateTime = 'updated_at';
    protected $hidden = ['sign_password'];
}
