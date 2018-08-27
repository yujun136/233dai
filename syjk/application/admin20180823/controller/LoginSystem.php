<?php

/**
 * Created by PhpStorm.
 * User: wuaduo
 * Date: 2018/8/1
 * Time: 9:47
 */
namespace app\admin\controller;
//namespace app\common;
use think\Controller;
use think\Db;
use think\Exception;


class LoginSystem extends Controller
{
    public function login(){
        return $this->fetch();
    }

    public function dologin(){
        $param = input('post.');

//        foreach ($param as $k=>$v){
//            echo $k.'--'.$v;
//        }
//        print $param["username"];
//        print $param["password"];
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
        }else{
            // 验证密码
                if($has['password'] != md5($param['password'])){
                    $this->error('用户名或密码错误');
            }
        }


        // 记录用户登录信息记录用户登录信息
        cookie('user_id', $has['userid'], 3600);  // 一个小时有效期
        cookie('user_name', $has['username'], 3600);

        $this->redirect(url('index/index'));
    }

    // 退出登录
    public function loginOut()
    {
        cookie('user_id', null);
        cookie('user_name', null);

        $this->redirect(url('index/login'));
    }

    //注册
    public function registe()
    {

        $param = input('post.');
        try{

        }catch (Exception $e){
            if($param){

                $param["vip"] = "1";
                $param["regip"] = $_SERVER["HTTP_REFERER"];
                $param["regdate"] = date('y-m-d h:i:s',time());
                $password = md5($param["password"]);

                $has = db('user')->where('username', $param['username'])->find();

                if($param["password"] == $param["sepassword"]){
                    if(empty($has)){

                        Db::table("user")
                            ->insert(['username' => $param["username"], 'password' => $password, 'nickname' =>  $param["nickname"], 'company' =>  $param["company"],
                                'regdate' =>  $param["regdate"], 'lastdate' => $param["regdate"], 'regip' => $param["regip"], 'lastip' =>  $param["regip"] ,
                                'wechat' =>  $param["wechat"], 'mobile' => $param["mobile"], 'vip' => "1"]);

                        Db::table("userdetail")
                            ->insert(['nowliveadress' => $param["nowliveadress"], 'business' => $param["business"]]);

                        // 记录用户登录信息记录用户登录信息

                        $this->success("恭喜，注册成功",url("index/login"));

                    }
                    else{
                        $this->error('用户名重复，请重新填写用户名');
                    }
                }else{
                    $this->error('两次密码填写不一致，请重新填写密码');
                }
            }
        }
    }


}




////获取当前的域名:
//echo $_SERVER['SERVER_NAME'];
////获取来源网址,即点击来到本页的上页网址
//echo $_SERVER["HTTP_REFERER"];
//$_SERVER['REQUEST_URI'];//获取当前域名的后缀
//$_SERVER['HTTP_HOST'];//获取当前域名
//dirname(__FILE__);//获取当前文件的物理路径
//dirname(__FILE__)."/../";//获取当前文件的上一级物理路径



//方法一date函数
//echo date(‘y-m-d h:i:s’,time());
////2010-08-29 11:25:26
//方法二 time函数
//$time = time();
//echo date("y-m-d",$time) //2010-08-29
//方法三 $_server['server_time']
//
//方法四 strftime
//echo strftime ("%hh%m %a %d %b" ,time());
//18h24 sunday 21 may