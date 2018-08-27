<?php
/**
 * Created by PhpStorm.
 * User: wuaduo
 * Date: 2018/8/22
 * Time: 11:26
 */

namespace app\admin\model;

use think\Exception;
use think\Model;
use think\db;

class HomePageModel extends Model{


    //获取全部客户信息
    public function getcustomers(){

        try{
            $customerinfo  = Db::query('select * from customer ');

            if($customerinfo){
                return $customerinfo;
            }else{
            }
        }catch (Exception $e){
            print $e;
            $this->error("获取平台客户信息失败，请联系您的客户经理",url("index/person_info"));
        }

    }

    //通过积分购买客户
    public function deductscore(){

        $cusids = input('post.');

    }




}