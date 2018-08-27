<?php
/**
 * Created by PhpStorm.
 * User: wuaduo
 * Date: 2018/8/20
 * Time: 11:10
 */

namespace app\admin\controller;
use think\Controller;
use app\admin\model\PersonInfoModel;
use think\Exception;


class PersonalInfoCon extends Controller
{

    //获取信贷经理个人信息
    public function personinfomation(){

        $perinfo = new PersonInfoModel();
        $res = $perinfo->getpersoninfo();
        return $res;

    }

    //更新信贷经理个人信息
    public function updatepersoninfo(){

        $value = input('post.');
        if($value){
            try{
                $perinfo = new PersonInfoModel();
                $perinfo->updatepersoninfo($value);
                $this->success("您的个人信息修改成功",url("index/person_info"));
            }catch (Exception $e){
                $this->error('修改个人信息失败，请联系您的客户经理啊',url("index/person_info"));
            }
        }
    }




}
















