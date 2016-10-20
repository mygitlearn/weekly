<?php
namespace Home\Controller;

use Think\Controller;
use Think\Log;

class StatisticsController extends BaseController
{

    public function _initialize()
    {
        parent::_initialize();
    }

    public function week()
    {
        $sj = date('Y/m/d', strtotime("-6 day", time())) . " - " . date('Y/m/d', time());
        $this->assign("times", $sj);

        $this->display();
    }

    public function getWeek()
    {
        $dd = I("aoData");
        $sj = explode("-", $dd["sj"]);
        $map_str = "where i.status=1 ";
        if ($dd != "" && $sj != null) {
            $map_str = $map_str . " and h.u_type=2 and h.update_time>=" . (strtotime($sj[0])) . " and h.update_time<" . (strtotime($sj[1] . "+1 days"));
        }
        $week = D()->query("SELECT i.dict_qy,i.create_id,i.dict_cp,from_unixtime(h.update_time,'%Y-%m-%d') u_time,count(*) count, 0 budget FROM project_info i right join  project_history h  on i.id=h.project_id " . $map_str . " group by u_time ,dict_cp,dict_qy,create_id");

        $budget = D()->query("select distinct concat(i.dict_qy,i.create_id,i.dict_cp) Id,i.budget FROM project_info i right join project_history h  on i.id=h.project_id " . $map_str . " group by dict_cp,dict_qy,create_id");
//        var_dump(M()->getLastSql());
//       exit;
        $sum = array();
        foreach ($budget as $item) {
            $sum[$item["Id"]] = $item["budget"];
        }

        $data = array();
        $dict = M("data_dict")->where("type=3 or type=1")->select();
        foreach ($dict as $item) {
            $data[$item["Id"]] = $item["name"];
        }

        $m_user = M("user_info");
        $user = $m_user->where("job=2 or job=1")->select();
        $user_arr = array();

        foreach ($user as $item) {
            $user_arr[$item["Id"]] = $item["name"];
        }
        for ($i = 0; $i < count($week); $i++) {
            $id = $week[$i]["dict_qy"] . $week[$i]["create_id"] . $week[$i]["dict_cp"];
            $week[$i]["dict_cp"] = $data[intval($week[$i]["dict_cp"])];
            $week[$i]["dict_qy"] = $data[intval($week[$i]["dict_qy"])] . "-" . $user_arr[intval($week[$i]["create_id"])];
            $week[$i]["create_id"] = $user_arr[intval($week[$i]["create_id"])];
            $week[$i]["sum"] = intval($week[$i]["sum"] + intval($sum[$id]));
            $week[$i]["u_time"] = $dd["sj"];
        }
        session('my_week_list', $week);
        $this->ajaxReturn(array("data" => $week));
    }

    public function excelWeek()
    {
        $data = array();
        $session_data = session('my_week_list');
        for ($i = 0; $i < count($session_data); $i++) {
            $data[$i]["u_time"] = $session_data[$i]["u_time"];
            $data[$i]["dict_cp"] = $session_data[$i]["dict_cp"];
            $data[$i]["dict_qy"] = $session_data[$i]["dict_qy"];
            $data[$i]["count"] = $session_data[$i]["count"];
            $data[$i]["sum"] = $session_data[$i]["sum"];

        }
        $header = ["周报日期", "产品线", "区域", "支持次数", "支持金额"];
        To_Exel("周报", $header, $data);
    }

    public function month()
    {
        $this->display();
    }

    public function quarter()
    {
        $this->display();
    }

    public function  getQuarter()
    {
        $dd = I("aoData");
        switch ($dd["sj"]) {
            case "1":
                $start = strtotime(date('Y') . "-01-01");
                $end = strtotime(date('Y') . "-04-01");
                session("jd_data", "第一季度");
                break;
            case "2":
                $start = strtotime(date('Y') . "-04-01");
                $end = strtotime(date('Y') . "-07-01");
                session("jd_data", "第二季度");
                break;
            case "3":
                $start = strtotime(date('Y') . "-07-01");
                $end = strtotime(date('Y') . "-10-01");
                session("jd_data", "第三季度");
                break;
            case "4":
                $start = strtotime(date('Y') . "-10-01");
                $end = strtotime((intval(date('Y')) + 1) . "-01-01");
                session("jd_data", "第四季度");
                break;
            case "0":
                $start = "";
                $end = "";
                break;
        }
        if ($dd["sj"] != "0") {
            $where1 = " where status>0 and create_time>= " . $start . " and create_time<" . $end;
            $where2 = "  where status>0 and create_time>= " . $start . " and create_time<" . $end . " and is_alone=1 ";
        } else {
            $where1 = " where status>0 ";
            $where2 = " where status>0 and is_alone=1";
        }
        //$all = D()->query("SELECT i.dict_qy,i.create_id,i.dict_cp ,count(*) count,sum(budget) sum FROM project_info i right join  project_history h  on i.id=h.project_id " . $where1 . " group by dict_cp,dict_qy,create_id");
        //$alone = D()->query("SELECT concat(i.dict_qy,i.create_id,i.dict_cp) xyz,count(*) count,sum(budget) sum FROM project_info i right join  project_history h  on i.id=h.project_id " . $where2 . " group by dict_cp,dict_qy,create_id");

        $all = D()->query("SELECT i.dict_qy,i.create_id,i.dict_cp ,count(*) count,sum(budget) sum FROM project_info i " . $where1 . " group by dict_cp,dict_qy,create_id");
        $alone = D()->query("SELECT concat(i.dict_qy,i.create_id,i.dict_cp) xyz,count(*) count,sum(budget) sum FROM project_info i " . $where2 . " group by dict_cp,dict_qy,create_id");

        $temp_count = array();
        $temp_sum = array();
        foreach ($alone as $vo) {
            $temp_count[$vo["xyz"]] = $vo["count"];
            $temp_sum[$vo["xyz"]] = $vo["sum"];
        }
        $data = array();
        $dict = M("data_dict")->where("type=3 or type=1")->select();
        foreach ($dict as $item) {
            $data[$item["Id"]] = $item["name"];
        }
        $m_user = M("user_info");
        $user = $m_user->where("job=2")->select();
        $user_arr = array();

        foreach ($user as $item) {
            $user_arr[$item["Id"]] = $item["name"];
        }
        for ($i = 0; $i < count($all); $i++) {
            $var_str = $all[$i]["dict_qy"] . $all[$i]["create_id"] . $all[$i]["dict_cp"];
            $all[$i]["dict_cp"] = $data[intval($all[$i]["dict_cp"])];
            $all[$i]["dict_qy"] = $data[intval($all[$i]["dict_qy"])] . "-" . $user_arr[intval($all[$i]["create_id"])];
            $all[$i]["create_id"] = $user_arr[intval($all[$i]["create_id"])];

            $all[$i]["alone_count"] = intval($temp_count[$var_str]);
            $all[$i]["alone_count_per"] = floor((intval($temp_count[$var_str]) / intval($all[$i]["count"])) * 100) . "%";
            $all[$i]["alone_sum"] = intval($temp_sum[$var_str]);
            $all[$i]["alone_sum_per"] = floor((intval($temp_sum[$var_str]) / intval($all[$i]["sum"])) * 100) . "%";
        }
        //var_dump($all);
        //exit;
        session('my_quarter_list', $all);
        $this->ajaxReturn(array("data" => $all));
    }

    public function excelQuarter()
    {
        $data = array();
        $session_data = session('my_quarter_list');
        for ($i = 0; $i < count($session_data); $i++) {
            $data[$i]["dict_cp"] = $session_data[$i]["dict_cp"];
            $data[$i]["dict_qy"] = $session_data[$i]["dict_qy"];
            $data[$i]["count"] = $session_data[$i]["count"];
            $data[$i]["alone_count"] = $session_data[$i]["alone_count"];
            $data[$i]["alone_count_per"] = $session_data[$i]["alone_count_per"];
            $data[$i]["sum"] = $session_data[$i]["sum"];
            $data[$i]["alone_sum"] = $session_data[$i]["alone_sum"];
            $data[$i]["alone_sum_per"] = $session_data[$i]["alone_sum_per"];

        }
        $header = ["产品线", "区域", "支持项目数量", "落单数量", "落单数量占比", "支持项目金额", "落单项目金额", "落单金额占比"];
        To_Exel(session("jd_data") . "-季报", $header, $data);
    }
}