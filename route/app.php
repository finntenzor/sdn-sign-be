<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Route;

Route::post('api/admin/test', 'index/testAdmin');

Route::post('api/lesson/create', 'index/createLesson');
Route::post('api/lesson/query', 'index/queryLesson');

Route::post('api/sign/create', 'index/createSign');
Route::post('api/sign/confirm', 'index/confirmSign');
Route::post('api/sign/query', 'index/querySign');
Route::post('api/sign/is', 'index/isSigned');
