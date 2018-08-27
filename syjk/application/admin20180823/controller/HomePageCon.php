<?php
/**
 * Created by PhpStorm.
 * User: wuaduo
 * Date: 2018/8/6
 * Time: 16:28
 */

namespace app\admin\controller;


use think\Controller;
use app\admin\model\HomePageModel;
use think\Exception;

class HomePageCon extends Controller{

    //获取全部客户信息
    public function getallcustomers(){

        $customers = new HomePageModel();

        try{
            $resdata = $customers->getcustomers();
            foreach($resdata as &$value){
                $value['telphone'] = substr($value['telphone'] ,0,3).'********';
                if($value['buyer']== null){
                    $value['qiangdan'] = '我要抢单';
                }else{
                    $value['qiangdan'] = '已被抢单';
                }
            }
            unset($value);
            return $resdata;
        }catch (Exception $e){
            print $e;
            $this->error("获取平台客户信息失败，请联系您的客户经理",url("index/person_info"));
        }
    }

}