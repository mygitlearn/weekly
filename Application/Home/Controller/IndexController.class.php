<?php
namespace Home\Controller;

use Think\Controller;
use Think\Log;

class IndexController extends BaseController
{

    public function _initialize()
    {
        parent::_initialize();
    }

    public function index()
    {
        $dict_qy = D()->query("select Id,name from data_dict where type=1");
        $this->assign("qy", $dict_qy);

        $dict_hy = D()->query("select Id,name from data_dict where type=2");
        $this->assign("hy", $dict_hy);

        $dict_cp = D()->query("select Id,name from data_dict where type=3");
        $this->assign("cp", $dict_cp);

        $user1 = D()->query("select Id,name from user_info where job=2");
        $this->assign("jl", $user1);

        $user2 = D()->query("select Id,name from user_info where job=3");
        $this->assign("xs", $user2);

        $sj = date('Y/m/d', strtotime("-7 day", time())) . " - " . date('Y/m/d', time());
        $this->assign("times", "");
        $this->display();
    }

    public function  getData()
    {
        $dd = I("aoData");
        $map["status"] = 1;
        $sj = explode("-", $dd["sj"]);
        $qy = $dd["qy"];
        $hy = $dd["hy"];
        $jl = $dd["jl"];
        $xs = $dd["xs"];
        $cp = $dd["cp"];
        $ys = $dd["ys"];
        $power = $dd["power"];
        $bb = "0";
        $m_dict = M("data_dict");
        if ($power != "" && $power != null) {
            $dict1 = $m_dict->where("type=5 and replace(name,'%','')>=" . $power)->select();
            foreach ($dict1 as $item) {
                $bb = $bb . "," . $item["Id"];
            }
        }
        //var_dump(M()->getLastSql());
        //exit;
        if ($qy != "0" && $qy != null) {
            $map["dict_qy"] = $qy;
        }
        if ($hy != "0" && $hy != null) {
            $map["dict_hy"] = $hy;
        }
        if ($cp != "0" && $cp != null) {
            $map["dict_cp"] = $cp;
        }
        if ($xs != "0" && $xs != null) {
            $map["dict_xs"] = $xs;
        }
        if ($jl != "0" && $jl != null) {
            $map["create_id"] = $jl;
        }
        if ($dd["sj"] != "" && $sj != null) {
            $map['create_time'] = array(array('egt', strtotime($sj[0])), array('lt', strtotime($sj[1] . " +1 days")));
        } else {
            //$map['create_time'] = array(array('egt', strtotime(date("Y-m-d", strtotime("-7 day")))), array('lt', strtotime(date('Y-m-d', time()))));
        }
        switch ($ys) {
            case "1":
                $map['_string'] = 'budget>0 AND budget<=100 and power in (' . $bb . ')';
                break;
            case "2":
                $map['_string'] = 'budget>100 AND budget<=300 and power in (' . $bb . ')';
                break;
            case "3":
                $map['_string'] = 'budget>300 AND budget<=500 and power in (' . $bb . ')';
                break;
            case "4":
                $map['_string'] = 'budget>500 and power in (' . $bb . ')';
                break;
            default:
                $map['_string'] = 'power in (' . $bb . ')';
                break;
        }
        $data = array();
        $dict = $m_dict->select();
        foreach ($dict as $item) {
            $data[$item["Id"]] = $item["name"];
        }
        $m_user = M("user_info");
        $user = $m_user->select();
        $user_arr = array();

        foreach ($user as $item) {
            $user_arr[$item["Id"]] = $item["name"];
        }
        $projects = M("project_info")->field('Id,create_time,name,dict_qy,dict_hy,create_id,dict_xs,channel,dict_cp,budget,tender_time,project_status,power,project_rate,is_state,is_promise,is_change,is_alone,win_channel,vender ,demo')->where($map)->select();

        if ($projects == null) {
            $projects = array();
        }
        for ($i = 0; $i < count($projects); $i++) {
            $projects[$i]["create_id"] = $user_arr[intval($projects[$i]["create_id"])];
            $projects[$i]["dict_xs"] = $user_arr[intval($projects[$i]["dict_xs"])];
            $projects[$i]["dict_qy"] = $data[intval($projects[$i]["dict_qy"])];
            $projects[$i]["dict_hy"] = $data[intval($projects[$i]["dict_hy"])];
            $projects[$i]["dict_cp"] = $data[intval($projects[$i]["dict_cp"])];
            $projects[$i]["project_status"] = $data[intval($projects[$i]["project_status"])];
            $projects[$i]["power"] = $data[intval($projects[$i]["power"])];
            $projects[$i]["project_rate"] = $data[intval($projects[$i]["project_rate"])];
            $projects[$i]["tender_time"] = $projects[$i]["tender_time"];
            $projects[$i]["create_time"] = date("Y-m-d", $projects[$i]["create_time"]);
            if ($projects[$i]["is_state"] == "1") {
                $projects[$i]["is_state"] = "是";
            } else {
                $projects[$i]["is_state"] = "否";
            }
            if ($projects[$i]["is_alone"] == "1") {
                $projects[$i]["is_alone"] = "是";
            } else {
                $projects[$i]["is_alone"] = "否";
            }
            if ($projects[$i]["is_promise"] == "1") {
                $projects[$i]["is_promise"] = "是";
            } else {
                $projects[$i]["is_promise"] = "否";
            }
            if ($projects[$i]["is_change"] == "1") {
                $projects[$i]["is_change"] = "是";
            } else {
                $projects[$i]["is_change"] = "否";
            }
        }
        session('my_project_list', $projects);
        //var_dump($projects);
        //exit;
        $this->ajaxReturn(array("data" => $projects));
    }

    public function  excel()
    {
        $projects = session('my_project_list');
        $data = array();
        for ($i = 0; $i < count($projects); $i++) {
            $data[$i]["create_time"] = $projects[$i]["create_time"];
            $data[$i]["name"] = $projects[$i]["name"];
            $data[$i]["dict_qy"] = $projects[$i]["dict_qy"];
            $data[$i]["dict_hy"] = $projects[$i]["dict_hy"];
            $data[$i]["create_id"] = $projects[$i]["create_id"];
            $data[$i]["dict_xs"] = $projects[$i]["dict_xs"];
            $data[$i]["channel"] = $projects[$i]["channel"];
            $data[$i]["dict_cp"] = $projects[$i]["dict_cp"];
            $data[$i]["budget"] = $projects[$i]["budget"];
            $data[$i]["tender_time"] = $projects[$i]["tender_time"];
            $data[$i]["project_status"] = $projects[$i]["project_status"];
            $data[$i]["power"] = $projects[$i]["power"];
            $data[$i]["project_rate"] = $projects[$i]["project_rate"];
            $data[$i]["is_state"] = $projects[$i]["is_state"];
            $data[$i]["is_promise"] = $projects[$i]["is_promise"];
            $data[$i]["is_change"] = $projects[$i]["is_change"];
            $data[$i]["is_alone"] = $projects[$i]["is_alone"];
            $data[$i]["win_channel"] = $projects[$i]["win_channel"];
            $data[$i]["vender"] = $projects[$i]["vender"];
            $data[$i]["demo"] = $projects[$i]["demo"];

        }
        $header = ["记录日期", "项目名称", "区域", "行业", "产品经理", "销售", "渠道信息", "产品线", "预算(万)", "投标时间", "项目状态", "把握度", "当前进度", "了解项目", "客户承诺", "换单", "落单", "中标渠道", "厂商负责人", "备注"];
        To_Exel("神州数码项目报表", $header, $data);
    }

    public function todo()
    {
        $id["Id"] = I("id");
        $data["status"] = 2;
        $rlt = M("project_info")->where($id)->save($data);
        //var_dump(M()->getLastSql());
        //exit;
        if ($rlt) {
            $json_str["code"] = 0;
            $json_str["msg"] = "更新成功";
        } else {
            $json_str["code"] = 1;
            $json_str["msg"] = "更新失败";
        }
        $this->ajaxReturn($json_str);
    }

    public function upload()
    {
        $arr = From_Excel();
        $model = M("data_dict");
        $i = 0;
        $temp = "";
        foreach ($arr as $item) {
            $data = array();
            $data["create_time"] = strtotime($item[0]);

            $map1["job"] = 2;
            $map1["name"] = trim($item[2]);
            $data1["job"] = 2;
            $data1["name"] = trim($item[2]);
            $data1["pwd"] = md5("123456");
            $data1["area_id"] = 0;
            $data1["email"] = "";
            $data1["openid"] = "";
            $data1["tel"] = "";
            $rlt = M("user_info")->where($map1)->field("Id")->select();
            if ($rlt == null) {
                $data["create_id"] = M("user_info")->add($data1);
            } else {
                $data["create_id"] = $rlt[0]["Id"];
            }

            $map1["job"] = 3;
            $map1["name"] = trim($item[4]);
            $data1["job"] = 3;
            $data1["name"] = trim($item[4]);
            $data1["pwd"] = md5("123456");
            $data1["area_id"] = 0;
            $data1["email"] = "";
            $data1["openid"] = "";
            $data1["tel"] = "";
            $rlt = M("user_info")->where($map1)->field("Id")->select();
            if ($rlt == null) {
                $data["dict_xs"] = M("user_info")->add($data1);
            } else {
                $data["dict_xs"] = $rlt[0]["Id"];
            }

            $data["name"] = trim($item[5]);
            $data["channel"] = trim($item[6]);
            $data["budget"] = intval(trim($item[8]));
            $data["tender_time"] = trim($item[9]);

            $map["type"] = 1;
            $map["name"] = trim($item[1]) == "" ? "未选择" : trim($item[1]);
            $rlt = $model->where($map)->field("Id")->select();
            if ($rlt == null) {
                $data["dict_qy"] = $model->add($map);
            } else {
                $data["dict_qy"] = $rlt[0]["Id"];
            }

            $map["type"] = 2;
            $map["name"] = trim($item[3]) == "" ? "未选择" : trim($item[3]);
            $rlt = $model->where($map)->field("Id")->select();
            if ($rlt == null) {
                $data["dict_hy"] = $model->add($map);
            } else {
                $data["dict_hy"] = $rlt[0]["Id"];
            }

            $map["type"] = 3;
            $map["name"] = trim($item[7]) == "" ? "未选择" : trim($item[7]);
            $rlt = $model->where($map)->field("Id")->select();
            if ($rlt == null) {
                $data["dict_cp"] = $model->add($map);
            } else {
                $data["dict_cp"] = $rlt[0]["Id"];
            }

            $map["type"] = 4;
            $map["name"] = trim($item[10]) == "" ? "未选择" : trim($item[10]);
            $rlt = $model->where($map)->field("Id")->select();
            if ($rlt == null) {
                $data["project_status"] = $model->add($map);
            } else {
                $data["project_status"] = $rlt[0]["Id"];
            }

            $map["type"] = 5;
            $map["name"] = trim($item[11]) == "" ? "未选择" : trim($item[11]);
            $rlt = $model->where($map)->field("Id")->select();
            if ($rlt == null) {
                $data["power"] = $model->add($map);
            } else {
                $data["power"] = $rlt[0]["Id"];
            }

            $map["type"] = 6;
            $map["name"] = trim($item[12]) == "" ? "未选择" : trim($item[12]);
            $rlt = $model->where($map)->field("Id")->select();
            if ($rlt == null) {
                $data["project_rate"] = $model->add($map);
            } else {
                $data["project_rate"] = $rlt[0]["Id"];
            }

            if (trim($item[13]) == "是") {
                $data["is_state"] = 1;
            } else {
                $data["is_state"] = 0;
            }
            if (trim($item[14]) == "是") {
                $data["is_alone"] = 1;
            } else {
                $data["is_alone"] = 0;
            }
            if (trim($item[15]) == "是") {
                $data["is_promise"] = 1;
            } else {
                $data["is_promise"] = 0;
            }
            if (trim($item[16]) == "是") {
                $data["is_change"] = 1;
            } else {
                $data["is_change"] = 0;
            }

            $data["win_channel"] = trim($item[17]);
            $data["vender"] = trim($item[18]);
            $data["demo"] = trim($item[19]);
            $data["update_time"] = time();
            $data["urgent"] = 0;
            $data["area_id"] = 0;
            $data["status"] = 1;
            $rlt = M("project_info")->add($data);
            $i = $i + 1;
            if ($rlt > 0) {

            } else {
                $temp = $temp . $i . ",";
                $i = $i - 1;
            }

        }
        dump("Success" . $i . "Rows");
        dump("Failure:" . $temp);
    }
}
