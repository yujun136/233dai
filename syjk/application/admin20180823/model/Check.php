<?php

/**
 * Created by PhpStorm.
 * User: wuaduo
 * Date: 2018/8/3
 * Time: 15:45
 */

namespace app\syjk\model;
use think\Model;

class Check extends Model
{
    public function islogin()
    {
        {
            //读取条件
            $map = [

                //过滤接收的username字段为全部小写
                'username' => Request::instance()->post('username', '', 'strtolower'),
                //过滤接收的password字段为md5加密后再加密一次
                'password' => md5(Request::instance()->post('password', '', 'md5')),
                //tmd5相当于加密两次，效果是同上的
                'password' => Request::instance()->post('password', '', 'tmd5'),
                'state' => 1
            ];

            //取出数据
            $user_id = self::where($map)->value('id');
            $user_name = self::where($map)->value('username');
            $nickname = self::where($map)->value('nickname');

            // 用户名&密码 验证成功
            if (!empty($user_id)) {

                //设置Session,如果取出Session中的admin_user_id的话直接调用Session对象，然后Session::get(admin_user_id')

                Session::set('admin_user_id', $user_id);
                Session::set('admin_user_name', $user_name);
                Session::set('nickname', $nickname);
                return true;
            } else {
                return false;
            }
        }

    }

}