<?php
/**
 * Created by PhpStorm.
 * User: wuaduo
 * Date: 2018/8/22
 * Time: 11:26
 */

namespace app\admin\model;

use think\Model;
use think\db;

class HomePageModel extends Model{

    public function getcustomers(){

        $customerinfo  = Db::query('select * from customer ');

        if($customerinfo){
            return $customerinfo;
        }else{
        }


    }

}