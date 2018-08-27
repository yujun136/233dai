<?php
/**
 * Created by PhpStorm.
 * User: wuaduo
 * Date: 2018/8/16
 * Time: 15:47
 */

namespace app\admin\model;


use think\Model;
use think\db;

class CustomerManagerModel extends Model{

    public function getcustomerdata(){

        $resdata = Db::query('select * from customer where owner = ?',[cookie('user_id')]);

//        $resdata =  Db::table('customer')
//                    ->where('owner',cookie('user_id'))
//                    ->where('uploader',1)
//                    ->find();

        if(empty($resdata)){
        }else{
            return $resdata;
        }

    }

    public function getuploadercustomerdata(){

        $resdata = Db::query('select * from customer where uploader = ?',[cookie('user_id')]);

//        $resdata =  Db::table('customer')
//                    ->where('owner',cookie('user_id'))
//                    ->where('uploader',1)
//                    ->find();

        if(empty($resdata)){
        }else{
            return $resdata;
        }
    }


    public function uploadcustomer($data){

        $res = Db::name('customer')->insertAll($data);
        if($res){
            return $res;
        }else{
        }
    }



}