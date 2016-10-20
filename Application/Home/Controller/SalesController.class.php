<?php
namespace Home\Controller;

use Think\Controller;
use Think\Log;

class SalesController extends BaseController
{

    public function _initialize()
    {
        parent::_initialize();
    }

    public function sale()
    {
        $project_status = D()->query("select Id,name from data_dict where is_del=0 and type=4 ORDER by order_num desc,Id desc");
        $this->assign("project_status", $project_status);

        $power = D()->query("select Id,name from data_dict where is_del=0 and type=5 ORDER by order_num desc,Id desc");
        $this->assign("power", $power);

        $project_rate = D()->query("select Id,name from data_dict where is_del=0 and type=6 ORDER by order_num desc,Id desc");
        $this->assign("project_rate", $project_rate);

        $this->display();
    }

    public function getItem()
    {
        $map["Id"] = intval(I("id"));
        $data = array();
        $dict = M("data_dict")->select();
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
        //var_dump(M()->getLastSql());
        //exit;
        for ($i = 0; $i < count($projects); $i++) {
            $projects[$i]["create_id"] = $user_arr[intval($projects[$i]["create_id"])];
            $projects[$i]["dict_xs"] = $user_arr[intval($projects[$i]["dict_xs"])];
            $projects[$i]["dict_hy"] = $data[intval($projects[$i]["dict_hy"])];
            $projects[$i]["dict_cp"] = $data[intval($projects[$i]["dict_cp"])];
            $projects[$i]["dict_qy"] = $data[intval($projects[$i]["dict_qy"])];
            $projects[$i]["tender_time"] = $projects[$i]["tender_time"];
            $projects[$i]["create_time"] = date("Y-m-d", $projects[$i]["create_time"]);
            $projects[$i]["project_status"] = $data[intval($projects[$i]["project_status"])];
            $projects[$i]["power"] = $data[intval($projects[$i]["power"])];
            $projects[$i]["project_rate"] = $data[intval($projects[$i]["project_rate"])];

        }
        $this->ajaxReturn($projects[0]);
    }

    public function  getData()
    {
        $dd = I("aoData");
        $map["status"] = 1;
        $map["dict_xs"] = $this->getId();
        $status = $dd["status"];
        $channel = $dd["channel"];
        $name = $dd["name"];
        $budget = $dd["budget"];
        $budget2 = $dd["budget2"];
        $rate = $dd["rate"];
        $power = $dd["power"];
        $m_dict = M("data_dict");

        if ($status != "0" && $status != null) {
            $map["project_status"] = $status;
        }
        if ($rate != "0" && $rate != null) {
            $map["project_rate"] = $rate;
        }
        if ($power != "0" && $power != null) {
            $map["power"] = $power;
        }
        if ($budget != "" && $budget != null && $budget2 != "" && $budget2 != null) {
            $map["budget"] = array(array('egt', $budget), array('ELT', $budget2));
        }
        if ($budget != "" && $budget != null) {
            $map["budget"] = array('egt', $budget);
        }
        if ($budget2 != "" && $budget2 != null) {
            $map["budget"] = array('ELT', $budget2);
        }
        $where['name'] = array('like', '%' . $name . '%');
        $where['channel'] = array('like', '%' . $channel . '%');
        $where['_logic'] = 'and';
        $map['_complex'] = $where;

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

        $projects = M("project_info")->field('Id,create_time,name,dict_qy,dict_hy,create_id,dict_xs,channel,dict_cp,budget,tender_time,project_status,power,project_rate,is_state,is_promise,is_change,is_alone,win_channel,vender ,demo')->order("name desc")->where($map)->select();

        if ($projects == null) {
            $projects = array();
        }
        for ($i = 0; $i < count($projects); $i++) {
            $projects[$i]["create_id"] = $user_arr[intval($projects[$i]["create_id"])];
            $projects[$i]["dict_xs"] = $user_arr[intval($projects[$i]["dict_xs"])];
            $projects[$i]["dict_qy"] = $data[intval($projects[$i]["dict_qy"])];
            $projects[$i]["dict_hy"] = $data[intval($projects[$i]["dict_hy"])];
            $projects[$i]["dict_cp"] = $data[intval($projects[$i]["dict_cp"])];
            $projects[$i]["tender_time"] = $projects[$i]["tender_time"];
            $projects[$i]["create_time"] = date("Y-m-d", $projects[$i]["create_time"]);
            $projects[$i]["project_status"] = $data[intval($projects[$i]["project_status"])];
            $projects[$i]["power"] = $data[intval($projects[$i]["power"])];
            $projects[$i]["project_rate"] = $data[intval($projects[$i]["project_rate"])];


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

    //信息导出
    public function export()
    {
        $data = array();
        $session_data = session('my_project_list');
        for ($i = 0; $i < count($session_data); $i++) {
            $data[$i]["create_time"] = $session_data[$i]["create_time"];
            $data[$i]["name"] = $session_data[$i]["name"];
            $data[$i]["dict_qy"] = $session_data[$i]["dict_qy"];
            $data[$i]["dict_hy"] = $session_data[$i]["dict_hy"];
            $data[$i]["create_id"] = $session_data[$i]["create_id"];
            $data[$i]["dict_xs"] = $session_data[$i]["dict_xs"];
            $data[$i]["channel"] = $session_data[$i]["channel"];
            $data[$i]["dict_cp"] = $session_data[$i]["dict_cp"];
            $data[$i]["budget"] = $session_data[$i]["budget"];
            $data[$i]["tender_time"] = $session_data[$i]["tender_time"];
            $data[$i]["project_status"] = $session_data[$i]["project_status"];
            $data[$i]["power"] = $session_data[$i]["power"];
            $data[$i]["project_rate"] = $session_data[$i]["project_rate"];
            $data[$i]["is_state"] = $session_data[$i]["is_state"];
            $data[$i]["is_promise"] = $session_data[$i]["is_promise"];
            $data[$i]["is_alone"] = $session_data[$i]["is_alone"];
            $data[$i]["is_change"] = $session_data[$i]["is_change"];
            $data[$i]["win_channel"] = $session_data[$i]["win_channel"];
            $data[$i]["vender"] = $session_data[$i]["vender"];
            $data[$i]["demo"] = $session_data[$i]["demo"];
        }
        $header = ["记录时间", "项目名称", "区域", "行业", "产品经理", "销售", "渠道消息", "产品线", "预算（万）", "投标时间", "项目状态", "把握度", "当前进度", "了解项目", "用户承诺", "换单", "落单", "中标渠道", "厂商负责人", "备注"];
        // $header = ["项目名称", "渠道消息", "预算（万）", "项目状态", "把握度", "当前进度", "了解项目", "得到承诺","落单", "换单"];
        To_Exel("销售", $header, $data);
    }

    public function  getId()
    {
        $tel['tel'] = $_COOKIE['userId'];
        if ($tel == "") {
            return;
        }
        $user = M("user_info")->field("Id")->where($tel)->select(); //获取获取用户Id和工作类型
        return $user[0]['Id'];
    }

    public function modify()
    {
        $time = time();
        $data['is_state'] = I("state");
        $data['is_alone'] = I("alone");
        $data['is_promise'] = I("promise");
        $data['is_change'] = I("change");
        $data['channel'] = I("channel");
        $data['win_channel'] = I("win_channel");
        $data['vender'] = I("vender");
        $data['name'] = I("title");
        $data['update_time'] = $time;         //数据库无此字段
        $where['Id'] = I("id");
        $model = M("project_info");
        $yes_no = ['否', '是'];

        $diff = "";
        //从数据库中获取编辑襄，与编辑内容进行对比，如果有改动的存入一个数组中。
        $reference = $model->field("name,is_state,is_alone,is_promise,is_change,channel,win_channel,vender")->where($where)->select();

        foreach ($reference as $list) {
            if ($list['name'] != $data['name']) {
                $diff = "项目名称由 '" . $list['name'] . "' 变换到 '" . $data['name'] . "'；";
            }
            if ($list['is_state'] != $data['is_state']) {
                $diff = $diff . " 是否了解项目基本情况由'" . $yes_no[$list['is_state']] . "' 变换到 '" . $yes_no[$data['is_state']] . "'；";
            }
            if ($list['is_alone'] != $data['is_alone']) {
                $diff = $diff . "是否落单情况由 '" . $yes_no[$list['is_alone']] . "' 变换到 '" . $yes_no[$data['is_alone']] . "'；";
            }
            if ($list['is_promise'] != $data['is_promise']) {
                $diff = $diff . "客户承诺情况由 '" . $yes_no[$list['is_promise']] . "' 变换到 '" . $yes_no[$data['is_promise']] . "'；";
            }
            if ($list['is_change'] != $data['is_change']) {
                $diff = $diff . "换单情况由 '" . $yes_no[$list['is_change']] . "' 变换到 '" . $yes_no[$data['is_change']] . "'；";
            }
            if ($list['channel'] != $data['channel']) {
                $diff = $diff . "渠道消息由 '" . $list['channel'] . "' 变换到 '" . $data['channel'] . "'；";
            }
            if ($list['win_channel'] != $data['win_channel']) {
                $diff = $diff . "中标渠道由 '" . $list['win_channel'] . "' 变换到 '" . $data['win_channel'] . "'；";
            }
            if ($list['vender'] != $data['vender']) {
                $diff = $diff . "厂商负责人由 '" . $list['vender'] . "' 变换到 '" . $data['vender'] . "'；";
            }
        }

        if ($diff != "") {
            $content['project_id'] = I("id");
            $content['user_id'] = $this->getId();
            $content['update_time'] = $time;
            $content['content'] = $diff;
            $content['u_type'] = 3;
            $insertdiff = M("project_history")->data($content)->add();
        }

        $res = $model->where($where)->data($data)->save();
        if ($res) {
            $this->ajaxReturn($res);
        } else {
            $this->ajaxReturn(0);
        }
    }

}