<?php

namespace app\index\controller;
use think\Controller;

class Login extends Controller
{
    public function index()
    {
        return $this->fetch('login');
    }

    // 处理登录逻辑
    public function doLogin()
    {
        $param = input('post.');
//        print $param;
//        foreach ($param as $k=>$v){
//            echo $k.'--'.$v;
//        }
        if(empty($param['username'])){

            $this->error('用户名不能为空');
        }

        if(empty($param['password'])){

            $this->error('密码不能为空');
        }

        // 验证用户名
        $has = db('user')->where('username', $param['username'])->find();
        if(empty($has)){

            $this->error('用户名或密码错误');
        }

        // 验证密码
        if($has['password'] != $param['password']){

            $this->error('用户名密码错误');
        }

        // 记录用户登录信息
        cookie('user_id', $has['id'], 3600);  // 一个小时有效期
        cookie('user_name', $has['username'], 3600);

        $this->redirect(url('index/index'));
    }

    // 退出登录
    public function loginOut()
    {
        cookie('user_id', null);
        cookie('user_name', null);

        $this->redirect(url('login/index'));
    }


}
