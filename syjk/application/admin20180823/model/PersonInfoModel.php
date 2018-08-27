<?php
/**
 * Created by PhpStorm.
 * User: wuaduo
 * Date: 2018/8/20
 * Time: 12:10
 */

namespace app\admin\model;


use think\Model;
use think\db;

class PersonInfoModel extends Model
{
    //获取信贷经理个人信息
    public function getpersoninfo(){

//        $res = array();

        $personinfo = Db::query('select * from user where userid = ?',[cookie('user_id')]);
        $infodetail = Db::query('select * from userdetail where userid = ?',[cookie('user_id')]);

        $res['userid'] = $personinfo[0]['userid'];
        $res['nickname'] = $personinfo[0]['nickname'];
        $res['telphone'] = $personinfo[0]['mobile'];
        $res['wechat'] = $personinfo[0]['wechat'];
        $res['username'] = $personinfo[0]['username'];
        $res['nowliveadress'] = $infodetail[0]['nowliveadress'];
        $res['scores'] = $infodetail[0]['scores'];
        $res['business'] = $infodetail[0]['business'];
        return $res;

    }

    //修改信贷经理个人信息
    public function updatepersoninfo($value){

        if($value){
            try {
                Db::table('user')
                    ->where('userid', cookie('user_id'))
                    ->update(['nickname' => $value['nickname'],'mobile' => $value['telphone'],'wechat' => $value['wechat']]);
                Db::table('userdetail')
                    ->where('userid', cookie('user_id'))
                    ->update(['nowliveadress' => $value['nowliveadress'],'business' => $value['business']]);

            }catch(Exception $e) {
                $this->error('修改个人信息失败，请联系您的客户经理',url("index/person_info"));
            }
        }

    }


}

