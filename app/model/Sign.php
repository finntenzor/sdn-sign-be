<?php
namespace app\model;

use think\Model;

class Sign extends Model
{
    protected $table = 'signs';
    protected $createTime = 'created_at';
    protected $updateTime = 'updated_at';
}
