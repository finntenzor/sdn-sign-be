<?php
namespace app\controller;

use app\ApiException;
use app\BaseController;
use app\model\Lesson;
use app\model\Sign;

class Index extends BaseController
{
    public function testAdmin()
    {
        try {
            $this->mustAdmin();
            return $this->data([
                'pass' => true,
            ]);
        } catch (ApiException $e) {
            return $this->data([
                'pass' => false,
            ]);
        }
    }

    public function createLesson()
    {
        $this->mustAdmin();

        $lesson = new Lesson();
        $lesson->lesson_section = input('lesson_section');
        $lesson->start_time = date("Y-m-d H:i:s", input('start_time'));
        $lesson->end_time = date("Y-m-d H:i:s", input('end_time'));
        $lesson->sign_type = input('sign_type');
        $lesson->sign_password = input('sign_password');
        $lesson->sign_question = input('sign_question');
        $lesson->save();
    
        return $this->data($lesson);
    }

    public function queryLesson()
    {
        $order = input('order') === 'asc' ? 'asc' : 'desc';
        $count = input('count');
        return $this->data(
            Lesson::order('created_at', $order)->paginate($count)
        );
    }

    public function getLesson()
    {
        return $this->data(
            Lesson::where('id', input('id'))->find()
        );
    }

    public function isSigned()
    {
        $lesson_id = input('lesson_id');
        $class_name = input('class_name');
        $real_name = input('real_name');

        if (!is_array($lesson_id)) {
            $lesson_id = [$lesson_id];
        }

        $result = [];

        foreach ($lesson_id as $id) {
            $extSign = Sign::where([
                'lesson_id' => $id,
                'class_name' => $class_name,
                'real_name' => $real_name,
            ])->find();
            $status = $extSign ? $extSign->status : -1;
            $result[] = [
                'id' => $id,
                'status' => $status
            ];
        }

        return $this->data($result);
    }

    public function createSign()
    {
        $lesson_id = input('lesson_id');
        $lesson = Lesson::where('id', $lesson_id)->find();

        if (!$lesson) {
            $this->abort(2000, '没有找到该签到');
        }

        $start_time = strtotime($lesson->start_time);
        $end_time = strtotime($lesson->end_time);
        $now_time = time();

        if (!($start_time <= $now_time && $now_time <= $end_time)) {
            $this->abort(2001, '不在签到的时间内');
        }

        if ($lesson->sign_type === 1) {
            if ($lesson->sign_password !== input('password')) {
                $this->abort(2002, '密码错误');
            }
        }

        $class_name = input('class_name');
        $real_name = input('real_name');

        $extSign = Sign::where([
            'lesson_id' => $lesson_id,
            'class_name' => $class_name,
            'real_name' => $real_name,
        ])->find();
        if ($extSign) {
            $this->abort(2003, '您已签到过');
        }

        $answer = $lesson->sign_type === 2 ? input('answer') : '';
        $status = $lesson->sign_type === 2 ? 0 : 1;

        $sign = new Sign();
        $sign->lesson_id = $lesson_id;
        $sign->class_name = input('class_name');
        $sign->real_name = input('real_name');
        $sign->user_ip = $this->request->ip();
        $sign->answer = $answer;
        $sign->status = $status;
        $sign->save();

        return $this->data($sign);
    }

    public function confirmSign()
    {
        $this->mustAdmin();

        $sign_id = input('sign_id');
        $sign = Sign::where('id', $sign_id)->find();

        if (!$sign) {
            $this->abort(2004, '没有找到该签到');
        }
        
        if ($sign->status === 1) {
            $this->abort(2005, '已经确认签到');
        }

        $sign->status = 1;
        $sign->save();

        return $this->data($sign);
    }

    public function querySign()
    {
        $lesson_id = input('lesson_id');

        return $this->data(
            Sign::where('lesson_id', $lesson_id)->select()
        );
    }
}
