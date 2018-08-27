<?php
/**
 * Created by PhpStorm.
 * User: wuaduo
 * Date: 2018/8/16
 * Time: 15:48
 */

namespace app\admin\controller;


use app\admin\model\CustomerManagerModel;
use think\Controller;
use think\Db;
use think\Exception;


class CustomerManagerCon extends Controller{

    //获取我购买的客户列表
    public function getcustomers(){
        $customer = new CustomerManagerModel();
        $resdata = $customer->getcustomerdata();
//        $has = db('customer')->where('cusid', "1")->find();
        return $resdata;

    }

    //获取我上传的客户列表
    public function getmyuploadcustomers(){

        $customer = new CustomerManagerModel();
        $resdata = $customer->getuploadercustomerdata();
        return $resdata;

    }

    //我上传的客户批量表格导入
    public function customerimport(){
        try{
            if(request()->isPost()){
                $file = request()->file('file');
                if($file){
                    // 移动到框架应用根目录/public/uploads/ 目录下
                    $info = $file->move(ROOT_PATH . 'public' .DS.'uploads'. DS . 'excel');
                    if($info){
                        //获取文件所在目录名
                        $path=ROOT_PATH . 'public' . DS.'uploads'.DS .'excel/'.$info->getSaveName();
                        //加载PHPExcel类
                        vendor("PHPExcel.Reader.Excel5");
                        //实例化PHPExcel类（注意：实例化的时候前面需要加'\'）
                        $objReader=new \PHPExcel_Reader_Excel5();
                        $objPHPExcel = $objReader->load($path,$encode='utf-8');//获取excel文件
                        $sheet = $objPHPExcel->getSheet(0); //激活当前的表
                        $highestRow = $sheet->getHighestRow(); // 取得总行数
                        $highestColumn = $sheet->getHighestColumn(); // 取得总列数
                        $a=0;
                        //将表格里面的数据循环到数组中
                        for($i=2;$i<=$highestRow;$i++)
                        {
                            //*为什么$i=2? (因为Excel表格第一行应该是姓名，手机号，微信号等，从第二行开始，才是我们要的数据。)
                            $data[$a]['name'] = $objPHPExcel->getActiveSheet()->getCell("A".$i)->getValue();//姓名
                            $data[$a]['telphone'] = $objPHPExcel->getActiveSheet()->getCell("B".$i)->getValue();//手机号
                            $data[$a]['wechat'] = $objPHPExcel->getActiveSheet()->getCell("C".$i)->getValue();//微信号
                            $data[$a]['age'] = $objPHPExcel->getActiveSheet()->getCell("D".$i)->getValue();//年龄
                            $data[$a]['sex'] = $objPHPExcel->getActiveSheet()->getCell("E".$i)->getValue();//性别
                            $data[$a]['zhimascore'] = $objPHPExcel->getActiveSheet()->getCell("F".$i)->getValue();//芝麻信用分
                            $data[$a]['hujiadress'] = $objPHPExcel->getActiveSheet()->getCell("G".$i)->getValue();//户籍所在地
                            $data[$a]['nowliveadress'] = $objPHPExcel->getActiveSheet()->getCell("H".$i)->getValue();//现住址
                            $data[$a]['isgongjijin'] = $objPHPExcel->getActiveSheet()->getCell("I".$i)->getValue();//是否有公积金
                            $data[$a]['iscreditcard'] = $objPHPExcel->getActiveSheet()->getCell("J".$i)->getValue();//是否有信用卡
                            $data[$a]['isoverdue'] = $objPHPExcel->getActiveSheet()->getCell("K".$i)->getValue();//是否逾期
                            $data[$a]['iscarloan'] = $objPHPExcel->getActiveSheet()->getCell("L".$i)->getValue();//是否有车贷
                            $data[$a]['ishouseloan'] = $objPHPExcel->getActiveSheet()->getCell("M".$i)->getValue();//是否有房贷
                            $data[$a]['owner'] = cookie('user_id');
                            $data[$a]['uploader'] = cookie('user_id');
                            $data[$a]['carloansum'] = $objPHPExcel->getActiveSheet()->getCell("N".$i)->getValue();//车贷金额
                            $data[$a]['houseloansum'] = $objPHPExcel->getActiveSheet()->getCell("O".$i)->getValue();//房贷金额
                            $data[$a]['fee'] = $objPHPExcel->getActiveSheet()->getCell("P".$i)->getValue();//贷款费用
                            $data[$a]['beizhu'] = $objPHPExcel->getActiveSheet()->getCell("Q".$i)->getValue();//备注
                            $a++;
                        }
                        //往数据库添加数据
                        $customer = new CustomerManagerModel();
                        $customer->uploadcustomer($data);

                        $this->success("您的客户上传成功",url("index/table_jqgrid2"));

                    }else{
                        // 上传失败获取错误信息
                        $this->error($file->getError(),url("index/table_jqgrid2"));
                    }
                }else{
                    $this->error("请选择上传文件",url("index/table_jqgrid2"));
                }
            }
        }catch (Exception $e){
            $this->error("上传失败，请联系您的客户经理",url("index/table_jqgrid2"));
        }

    }

    //导出我的客户到Excel表格
    public function customerexport(){

        $name = '我的客户'.date('ymd',time()).'.xls';
        $customer = new CustomerManagerModel();
        $list = $customer->getcustomerdata();
        vendor("PHPExcel.Reader.Excel5");
        $excel = new \PHPExcel(); //引用phpexcel
        iconv('UTF-8', 'gb2312', $name); //针对中文名转码
        $excel->setActiveSheetIndex(0);
        $excel->getActiveSheet()->setTitle($name); //设置表名
        $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(18);
        $excel->getActiveSheet()->getColumnDimension('G')->setWidth(50);
        $excel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
        $excel->getActiveSheet()->getColumnDimension('H')->setWidth(50);
        $excel->getActiveSheet()->getColumnDimension('Q')->setWidth(50);

        $letter = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q'];//列坐标

        $excel->setActiveSheetIndex(0)
            ->setCellValue('A1', '姓名')
            ->setCellValue('B1', '手机号')
            ->setCellValue('C1', '微信号')
            ->setCellValue('D1', '年龄')
            ->setCellValue('E1', '性别')
            ->setCellValue('F1', '芝麻信用分')
            ->setCellValue('G1', '户籍所在地')
            ->setCellValue('H1', '现住址')
            ->setCellValue('I1', '是否有公积金')
            ->setCellValue('J1', '是否有信用卡')
            ->setCellValue('K1', '是否逾期')
            ->setCellValue('L1', '是否有车贷')
            ->setCellValue('M1', '是否有房贷')
            ->setCellValue('N1', '车贷金额')
            ->setCellValue('O1', '房贷金额')
            ->setCellValue('P1', '贷款费用')
            ->setCellValue('Q1', '备注');

//        //生成表头
//        for($i=0;$i<count($header);$i++)
//        {
//            //设置表头值
//            $excel->getActiveSheet()->setCellValue("$letter[$i]1",$header[$i]);
//            //设置表头字体样式
//            $excel->getActiveSheet()->getStyle("$letter[$i]1")->getFont()->setName('宋体');
//            //设置表头字体大小
//            $excel->getActiveSheet()->getStyle("$letter[$i]1")->getFont()->setSize(14);
//            //设置表头字体是否加粗
//            $excel->getActiveSheet()->getStyle("$letter[$i]1")->getFont()->setBold(true);
//            //设置表头文字水平居中
//            $excel->getActiveSheet()->getStyle("$letter[$i]1")->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//            //设置文字上下居中
//            $excel->getActiveSheet()->getStyle($letter[$i])->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
//            //设置单元格背景色
//            $excel->getActiveSheet()->getStyle("$letter[$i]1")->getFill()->getStartColor()->setARGB('FFFFFFFF');
////            $excel->getActiveSheet()->getStyle("$letter[$i]1")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
//            $excel->getActiveSheet()->getStyle("$letter[$i]1")->getFill()->getStartColor()->setARGB('FF6DBA43');
//            //设置字体颜色
//            $excel->getActiveSheet()->getStyle("$letter[$i]1")->getFont()->getColor()->setARGB('FFFFFFFF');
//        }

        $j = 0;
        //写入数据
        foreach($list as $k=>$v)
        {
            //从第二行开始写入数据（第一行为表头）
            $excel->getActiveSheet()->setCellValue('A'.($j+2),$list[$j]['name']);//姓名
            $excel->getActiveSheet()->setCellValue('B'.($j+2),$list[$j]['telphone']);//手机号
            $excel->getActiveSheet()->setCellValue('C'.($j+2),$list[$j]['wechat']);//微信号
            $excel->getActiveSheet()->setCellValue('D'.($j+2),$list[$j]['age']);//年龄
            $excel->getActiveSheet()->setCellValue('E'.($j+2),$list[$j]['sex']);//性别
            $excel->getActiveSheet()->setCellValue('F'.($j+2),$list[$j]['zhimascore']);//芝麻信用分
            $excel->getActiveSheet()->setCellValue('G'.($j+2),$list[$j]['hujiadress']);//户籍所在地
            $excel->getActiveSheet()->setCellValue('H'.($j+2),$list[$j]['nowliveadress']);//现住址
            $excel->getActiveSheet()->setCellValue('I'.($j+2),$list[$j]['isgongjijin']);//是否有公积金
            $excel->getActiveSheet()->setCellValue('J'.($j+2),$list[$j]['iscreditcard']);//是否有信用卡
            $excel->getActiveSheet()->setCellValue('K'.($j+2),$list[$j]['isoverdue']);//是否逾期
            $excel->getActiveSheet()->setCellValue('L'.($j+2),$list[$j]['iscarloan']);//是否有车贷
            $excel->getActiveSheet()->setCellValue('M'.($j+2),$list[$j]['ishouseloan']);//是否有房贷
            $excel->getActiveSheet()->setCellValue('N'.($j+2),$list[$j]['carloansum']);//车贷金额
            $excel->getActiveSheet()->setCellValue('O'.($j+2),$list[$j]['houseloansum']);//房贷金额
            $excel->getActiveSheet()->setCellValue('P'.($j+2),$list[$j]['fee']);//贷款费用
            $excel->getActiveSheet()->setCellValue('Q'.($j+2),$list[$j]['beizhu']);//备注
            $j++;
        }

        //设置单元格边框
//        $excel->getActiveSheet()->getStyle("A1:E".(count($list)+1))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

        //清理缓冲区，避免中文乱码
        ob_end_clean();
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$name.'.xls"');
        header('Cache-Control: max-age=0');

        //导出数据
        $res_excel = \PHPExcel_IOFactory::createWriter($excel, 'Excel5');
        $res_excel->save('php://output');

    }

    //导出模板
    public function mubanexport(){

        $name = '上传客户模板.xls';
        $customer = new CustomerManagerModel();
        $list = $customer->getcustomerdata();
        vendor("PHPExcel.Reader.Excel5");
        $excel = new \PHPExcel(); //引用phpexcel
        iconv('UTF-8', 'gb2312', $name); //针对中文名转码
        $excel->setActiveSheetIndex(0);
        $excel->getActiveSheet()->setTitle($name); //设置表名
        $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(18);
        $excel->getActiveSheet()->getColumnDimension('G')->setWidth(50);
        $excel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
        $excel->getActiveSheet()->getColumnDimension('H')->setWidth(50);
        $excel->getActiveSheet()->getColumnDimension('Q')->setWidth(50);

        $letter = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q'];//列坐标

        $excel->setActiveSheetIndex(0)
            ->setCellValue('A1', '姓名')
            ->setCellValue('B1', '手机号')
            ->setCellValue('C1', '微信号')
            ->setCellValue('D1', '年龄')
            ->setCellValue('E1', '性别')
            ->setCellValue('F1', '芝麻信用分')
            ->setCellValue('G1', '户籍所在地')
            ->setCellValue('H1', '现住址')
            ->setCellValue('I1', '是否有公积金')
            ->setCellValue('J1', '是否有信用卡')
            ->setCellValue('K1', '是否逾期')
            ->setCellValue('L1', '是否有车贷')
            ->setCellValue('M1', '是否有房贷')
            ->setCellValue('N1', '车贷金额')
            ->setCellValue('O1', '房贷金额')
            ->setCellValue('P1', '贷款费用')
            ->setCellValue('Q1', '备注');

        //清理缓冲区，避免中文乱码
        ob_end_clean();
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$name.'.xls"');
        header('Cache-Control: max-age=0');

        //导出数据
        $res_excel = \PHPExcel_IOFactory::createWriter($excel, 'Excel5');
        $res_excel->save('php://output');
    }




}