<?php
namespace Home\Controller;

use Think\Controller;
use Think\Log;

class UsersController extends BaseController
{

    public function _initialize()
    {
        parent::_initialize();
    }

    public function user()
    {
        $this->display();
    }

    public function getdata()
    {
        $model = M("user_info");
        $jobtype = ["", "大区经理", "产品经理", "销售", "系统管理员"];
        $res = $model->field("Id, name, tel, job, email,account")->where("job=2 or job=3")->select();
        $count = count($res);
        for ($i = 0; $i < $count; $i++) {
            $set = $res[$i]["job"];
            $res[$i]['job'] = $jobtype[$set];
        }
        if ($res == null) {
            $res = array();
        }
        $this->ajaxReturn(array('data' => $res));
    }

    //编辑用户信息
    public function modify()
    {
        $where['Id'] = $_POST['id'];
        $data['name'] = $_POST['uname'];
        $data['account'] = $_POST['account'];
        $data['tel'] = $_POST['tel'];
        $data['job'] = $_POST['job'];
        $data['email'] = $_POST['email'];
        $data["update_time"] = time();
        $is_name = M("user_info")->where("Id!=" . $_POST['id'] . " and name='" . $_POST['uname'] . "'")->select();
        $is_tel = M("user_info")->where("Id!=" . $_POST['id'] . " and tel='" . $_POST['tel'] . "'")->select();
        $is_mail = M("user_info")->where("Id!=" . $_POST['id'] . " and email='" . $_POST['email'] . "'")->select();
        $is_account = M("user_info")->where("Id!=" . $_POST['id'] . " and account='" . $_POST['account'] . "'")->select();
        if ($is_account) {
            $this->ajaxReturn(-4);
        }
        if ($is_name) {
            $this->ajaxReturn(-1);
        }
        if ($is_tel) {
            $this->ajaxReturn(-2);
        }
        if ($is_mail) {
            $this->ajaxReturn(-3);
        }
        $res = M("user_info")->data($data)->where($where)->save();
        if ($res) {
            $this->ajaxReturn($res);
        } else {
            $this->ajaxReturn(0);
        }

    }

    //添加新增用户信息
    public function adduser()
    {
        $where['name'] = $data['name'] = $_POST['uname'];
        $data['tel'] = $phone["tel"] = $_POST['phone'];
        $data['job'] = $_POST['job'];
        $data['email'] = $mail["email"] = $_POST['email'];
        $data["account"] = $account["account"] = $_POST["account"];
        $data['pwd'] = md5("123456");
        $data["update_time"] = time();
        $sarchName = M("user_info")->field("Id")->where($where)->select();
        $checkphone = M("user_info")->field("Id")->where($phone)->select();
        $checkmail = M("user_info")->field("Id")->where($mail)->select();
        $checkaccount = M("user_info")->field("Id")->where($account)->select();
        if ($checkaccount) {
            $returnInfo[0] = 5;
            $returnInfo[1] = "此登录帐号已经存在！";
            $this->ajaxReturn($returnInfo);
        }
        if ($checkmail) {
            $returnInfo[0] = 4;
            $returnInfo[1] = "此邮箱已经存在！";
            $this->ajaxReturn($returnInfo);
        }
        if ($sarchName) {
            $returnInfo[0] = 1;
            $returnInfo[1] = "此用户名已存在！";
            $this->ajaxReturn($returnInfo);
        }
        if ($checkphone) {
            $returnInfo[0] = 3;
            $returnInfo[1] = "此手机号已存在！";
            $this->ajaxReturn($returnInfo);
        }
        $res = M("user_info")->data($data)->add();
        if ($res) {
            $returnInfo[0] = 2;
            $returnInfo[1] = "添加成功！";
            $this->ajaxReturn($returnInfo);
        } else {
            $this->ajaxReturn(0);
        }

    }

    //删除用户
    public function deldata()
    {
        $map['Id'] = $_POST['set'];
        $res = M("user_info")->where($map)->delete();
        if ($res) {
            $this->ajaxReturn($res);
        } else {
            $this->ajaxReturn(0);
        }
    }

//密码重置
    public function resetpwd()
    {
        $map['Id'] = $_POST['set'];
        $data['pwd'] = md5("123456");
        $res = M("user_info")->data($data)->where($map)->save();
        $ret_mes = "密码成功重置为:123456";
        $this->ajaxReturn($ret_mes);
    }

    public function export()
    {
        $jobtype = ["", "大区经理", "产品经理", "销售"];
        $datalist = M("user_info")->field(" account,name, tel, email, job")->where("job=3 or job=2")->select();
        $num = count($datalist);
        for ($i = 0; $i < $num; $i++) {
            $y = $datalist[$i]['job'];
            $datalist[$i]['job'] = $jobtype[$y];
        }
        foreach ($datalist as $field => $v) {
            if ($field == 'account') {
                $headArr[] = '登录帐号';
            }
            if ($field == 'uname') {
                $headArr[] = '用户名称';
            }
            if ($field == 'tel') {
                $headArr[] = '电话号码';
            }
            if ($field == 'email') {
                $headArr[] = '电子邮箱';
            }
            if ($field == 'job') {
                $headArr[] = '员工职位';
            }

        }
        $filename = "神州数码产品经理及销售人员名单";
        $this->get_exel($filename, $headArr, $datalist);
    }

    //导出表格
    public function get_exel($fileName, $headArr, $list)
    {
        //导入PHPExcel类库，因为PHPExcel没有用命名空间，只能import导入
        import("Org.Util.PHPExcel");
        import("Org.Util.PHPExcel.Writer.Excel5");
        import("Org.Util.PHPExcel.IOFactory.php");

        $date = date("Y_m_d", time());
        $fileName .= "_{$date}.xls";

        //创建PHPExcel对象，注意，不能少了\
        $objPHPExcel = new \PHPExcel();
        $objProps = $objPHPExcel->getProperties();

        //设置表头
        $key = ord("A");
        //print_r($headArr);exit;
        foreach ($headArr as $v) {
            $colum = chr($key);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum . '1', $v);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum . '1', $v);
            $key += 1;
        }
        $column = 2;
        $objActSheet = $objPHPExcel->getActiveSheet();
        foreach ($list as $key => $rows) { //行写入
            $span = ord("A");
            foreach ($rows as $keyName => $value) {// 列写入
                $j = chr($span);
                $objActSheet->setCellValue($j . $column, $value);
                $span++;
            }
            $column++;
        }
        $fileName = iconv("utf-8", "gb2312", $fileName);
        $objPHPExcel->setActiveSheetIndex(0);
        ob_end_clean();//清除缓冲区,避免乱码
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename=\"$fileName\"");
        header('Cache-Control: max-age=0');

        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output'); //文件通过浏览器下载
        exit;
    }

}