<?php

/**
 * Created by PhpStorm.
 * User: wuaduo
 * Date: 2018/8/1
 * Time: 9:44
 */
class common
{
    /**
     * 打印log日志
     * @param 文件名 第几行 日志内容
     */
    function dl_log($basename=null,$num=null,$msg=null)
    {
        //$msg = [2018-04-11 09:22:56]文件名：wxpay，第29行，[info]：日志信息
        $msg = '['.date("Y-m-d H:i:s").']'.'文件名：'.$basename.'，第'.$num.'行，'.'[info]：'.$msg;

        // 日志文件名：日期.txt
        $path = ROOT_PATH.DS.'public'. DS .'logs'. DS .date("Ymd").'.txt';

        file_put_contents($path, $msg.PHP_EOL,FILE_APPEND);
    }

}