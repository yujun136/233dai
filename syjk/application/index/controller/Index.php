<?php
namespace app\index\controller;

use think\Controller;
use think\Db;

//class Index extends Controller
//{
//    public function hello($name = 'thinkphp')
//    {
//        $this->assign('name', $name);
//        return $this->fetch();
//    }
//}

//class Index extends Controller
//{
//    public function hello()
//    {
//        $data = Db::name('user')->find();
//        $this->assign('result', $data);
//        return $this->fetch();
//
//    }
//}

class Index
{
    public function index()
    {
        echo "您好： " . cookie('user_name') . ', <a href="' . url('login/loginout') . '">退出</a>';
    }
}
