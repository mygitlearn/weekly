<?php
namespace Home\Controller;

use Think\Controller;


class ProductsController extends BaseController
{

    public function _initialize()
    {
        parent::_initialize();
    }

    //获取项目经理所有项目列表
    public function prod()
    {
        $data_dict = M('data_dict');
        $prod_model = M('Products');
        $user_tell = $_COOKIE['userId'];
        $user_info = M('user_info');
        $find_user = (int)$user_info->where("tel=$user_tell")->getField('Id');
        //$data_list = $data_dict->where("Id !=0")->order('Id asc')->select(); //获取基础数据的具体数据信息
        $data_list = $data_dict->where("is_del=0")->order(array('order_num'=>'desc','id'=>'desc'))->select(); //获取基础数据的具体数据信息
        $list_all_project = M('project_info');
        $pj_list = $list_all_project->where('Id != 0')->order(array('id'=>'asc'))->select(); //获取项目的具体信息
        $get_user_bug = $list_all_project->where("status != 2 and create_id=$find_user")->order(array('id'=>'asc'))->select();
        $uset_list = $user_info->where("Id !=0 ")->select(); //获取人员的信息
        /*获取所有状态为新建的项目并罗列出来*/
        /*$data = array();
        $user_data = array();
        $projects = D()->query("select * from project_info where status !=2 and create_id =$find_user order by Id desc ");
        $dict = D()->query("select Id,name from data_dict");
        $user = D()->query("select Id,name from user_info");

        foreach ($user as $i) {
            $user_data[$i["Id"]] = $i["name"];
        }

        foreach ($dict as $item) {
            $data[$item["Id"]] = $item["name"];
        }
        for ($i = 0; $i < count($projects); $i++) {
            $projects[$i]["dict_qy"] = $data[intval($projects[$i]["dict_qy"])];
            $projects[$i]["dict_hy"] = $data[intval($projects[$i]["dict_hy"])];
            $projects[$i]["dict_cp"] = $data[intval($projects[$i]["dict_cp"])];
            $projects[$i]["project_status"] = $data[intval($projects[$i]["project_status"])];
            $projects[$i]["power"] = $data[intval($projects[$i]["power"])];
            $projects[$i]["project_rate"] = $data[intval($projects[$i]["project_rate"])];
            $projects[$i]["create_id"] = $user_data[intval($projects[$i]["create_id"])];
            $projects[$i]["dict_xs"] = $user_data[intval($projects[$i]["dict_xs"])];
        }*/

        $this->assign("buglist", $get_user_bug);
        $this->assign("_list", $data_list);
        $this->assign("user_data", $uset_list);
        $this->assign("pj_list", $pj_list);
        //$this->assign("get_value", $projects); //输出模板文件
        $this->display();
    }

    //获取数据
    public function  GetData()
    {
        $data_deitl = I("aoData");
        $project_name = trim($data_deitl["search_project_name"]);
        $budge_small = trim($data_deitl["buget_small"]);
        $buget_big = trim($data_deitl["buget_big"]);
        $project_status = $data_deitl["get_object_status"];
        $power = $data_deitl["grasp_degree"];
        $project_rate = $data_deitl["get_current_progress"];
        if ($project_status != "0" && $project_status != null) {
            $map["project_status"] = $project_status;
        }
        if ($power != "0" && $power != null) {
            $map["power"] = $power;
        }
        if ($project_rate != "0" && $project_rate != null) {
            $map["project_rate"] = $project_rate;

        }
        if($budge_small !='' && $buget_big != ''){
           $map["budget"]= array(array('EGT',$budge_small),array('ELT', $buget_big));
        }
        if($budge_small !='' && $buget_big == ''){
            $map["budget"]= array(array('EGT', $budge_small));
        }
        if($budge_small =='' && $buget_big != ''){
            $map["budget"]= array(array('ELT',$buget_big));
        }

        if($project_name != ''){
           $map["name"]=array('like', '%' . $project_name .'%');
        }
        $map['status'] = array(array('LT',2));
        $data_dict = M('data_dict');
        $prod_model = M('Products');
        $user_tell = $_COOKIE['userId'];
        $user_info = M('user_info');
        $find_user = (int)$user_info->where("tel=$user_tell")->getField('Id');
        $map['create_id'] = $find_user;
        /*获取所有状态为新建的项目并罗列出来*/
        $data = array();
        $user_data = array();
        $projects = M("project_info")->field('create_time,name,dict_qy,dict_hy,create_id,dict_xs,channel,dict_cp,budget,tender_time,project_status,power,project_rate,is_state,is_promise,is_change,is_alone,win_channel,vender,demo,urgent,status,Id')->order("name desc")
            ->where($map)
            ->select();
        if ($projects == null) {
            $projects = array();
        }
        $dict = $data_dict->select();
        $user = $user_info->select();

        foreach ($user as $i) {
            $user_data[$i["Id"]] = $i["name"];
        }

        foreach ($dict as $item) {
            $data[$item["Id"]] = $item["name"];
        }
        for ($i = 0; $i < count($projects); $i++) {
            $projects[$i]["dict_qy"] = $data[intval($projects[$i]["dict_qy"])];
            $projects[$i]["dict_hy"] = $data[intval($projects[$i]["dict_hy"])];
            $projects[$i]["dict_cp"] = $data[intval($projects[$i]["dict_cp"])];
            $projects[$i]["project_status"] = $data[intval($projects[$i]["project_status"])];
            $projects[$i]["power"] = $data[intval($projects[$i]["power"])];
            $projects[$i]["project_rate"] = $data[intval($projects[$i]["project_rate"])];
            $projects[$i]["create_id"] = $user_data[intval($projects[$i]["create_id"])];
            $projects[$i]["dict_xs"] = $user_data[intval($projects[$i]["dict_xs"])];
            if ($projects[$i]['is_state'] == 0) {
                $projects[$i]['is_state'] = "否";
            } else {
                $projects[$i]['is_state'] = "是";
            }

            if ($projects[$i]['is_alone'] == 0) {
                $projects[$i]['is_alone'] = "否";
            } else {
                $projects[$i]['is_alone'] = "是";
            }

            if ($projects[$i]['is_promise'] == 0) {
                $projects[$i]['is_promise'] = "否";
            } else {
                $projects[$i]['is_promise'] = "是";
            }

            if ($projects[$i]['is_change'] == 0) {
                $projects[$i]['is_change'] = "否";
            } else {
                $projects[$i]['is_change'] = "是";
            }

            if ($projects[$i]['urgent'] == 1) {
                $projects[$i]['urgent'] = "是";
            }else{
                $projects[$i]['urgent'] = "否";
            }
            $projects[$i]["create_time"] = date("Y-m-d", $projects[$i]['create_time']);
            $projects[$i]["tender_time"] = $projects[$i]['tender_time'];
        }

        //关联表数据，获取所有的数据
        foreach ($projects as $i => $j) {
            $_list[] = $j;
        }
        session('project_list', $_list);
        $this->ajaxReturn(array("data" => $projects));
    }

    //添加项目页面
    public function add_programme()
    {
        $data_dict = M('data_dict');
        $user_info = M('user_info');
        $uset_list = $user_info->where("id !=0")->select(); //获取人员的信息
        $data_list = $data_dict->where("is_del=0")->order(array('order_num'=>'desc','id'=>'desc'))->select(); //获取具体数据信息
        $this->assign("_list", $data_list);
        $this->assign("user_data", $uset_list);
        $this->display();
    }

    /*
     * 项目经理添加项目
     * **/
    public function submit_programme()
    {
        $project_info = M('project_info');
        $add_to_history = M('project_history');
        $user_info = M('user_info');
        $user_tell = $_COOKIE['userId'];
        $user_info = M('user_info');
        $find_user = (int)$user_info->where("tel=$user_tell")->getField('Id');
        $data['create_id'] = $find_user;
        $data['dict_qy'] = I('post.object_aera');
        $data['dict_hy'] = I('post.object_hangye');
        $data['dict_xs'] = I('post.object_sealman');
        $where['name']= $data['name'] =  I('post.project_name');
        $data['channel'] = I('post.project_channel');
        $data['dict_cp'] = I('post.object_list');
        $data['budget'] = I('post.object_budget');
        $data['tender_time'] = I('post.get_date');
        $data['project_status'] = I('post.object_status');
        $data['power'] = I('post.grasp_degree');
        $data['project_rate'] = I('post.current_progress');
        $data['win_channel'] = I('post.winning_channel');
        $data['vender'] = I('post.account_manager');
        $data['urgent'] = (int)I('post.urgent');
        $data['demo'] = I('post.demo');
        $time = time();
        $data['create_time'] = $time;
        $data['update_time'] = $time;
        $checkname =$project_info ->field("Id")->where($where)->select();
        if($checkname){
            $this->ajaxReturn(2); //如果项目名存在提示错误
        }else{
            $result = $project_info->data($data)->add();
            if ($result) {
                if ($data['urgent'] == 1) {
                    //暂时不发邮件
                    /*$to = $data['dict_xs'];
                    $sent_email = $user_info->where("Id =$to")->getField('email');
                    $get_name = $user_info->where("Id =$to")->getField('name');
                    dump($sent_email);
                    $title = "项目跟踪提醒";
                    $cont = "项目：" . $data['name'] . ",需要紧急处理！";
                    SendMail($sent_email, $get_name, $title, $cont);*/
                    /*判断所提交的项目名是否重复*/
                }
                $arr['project_id'] = $result;
                $arr['user_id'] = $find_user;
                $arr['update_time'] = time();
                $arr['content'] =$where['name']."创建于". date("Y-m-d", $arr['update_time']);
                $arr['u_type'] = 2;
                $add_history = $add_to_history->data($arr)->add();//把变更记录信心添加到历史表中
                if($add_history){
                    $this->ajaxReturn(0); //如果成功的情况下返回0
                }else{
                    $this->ajaxReturn(1); //如果不成功的情况下返回0
                }
            } else {
                $this->ajaxReturn(1); //如果不成功的情况下返回0
            }
        }

    }

    /*
     *
     * 导出报表的方式
     * **/
    public function get_date_style()
    {
        $project_list = session('project_list');
        $list = array();
        foreach ($project_list as $k => $project_info) {
            $list[$k][create_time] = $project_info['create_time'];
            $list[$k][name] = $project_info['name'];
            $list[$k][dict_qy] = $project_info['dict_qy'];
            $list[$k][dict_hy] = $project_info['dict_hy'];
            $list[$k][create_id] = $project_info['create_id'];
            $list[$k][dict_xs] = $project_info['dict_xs'];
            $list[$k][channel] = $project_info['channel'];
            $list[$k][dict_cp] = $project_info['dict_cp'];
            $list[$k][budget] = $project_info['budget'];
            $list[$k][tender_time] = $project_info['tender_time'];
            $list[$k][project_status] = $project_info['project_status'];
            $list[$k][power] = $project_info['power'];
            $list[$k][project_rate] = $project_info['project_rate'];
            $list[$k][is_state] = $project_info['is_state'];
            $list[$k][is_alone] = $project_info['is_alone'];
            $list[$k][is_change] = $project_info['is_change'];
            $list[$k][is_promise] = $project_info['is_promise'];
            $list[$k][urgent] = $project_info['urgent'];
            $list[$k][win_channel] = $project_info['win_channel'];
            $list[$k][vender] = $project_info['vender'];
            $list[$k][demo] = $project_info['demo'];

        }

        foreach ($list as $field => $v) {
            if ($field == 'create_time') {
                $headArr[] = '创建时间';
            }

            if ($field == 'name') {
                $headArr[] = '项目名称';
            }

            if ($field == 'dict_qy') {
                $headArr[] = '区域';
            }

            if ($field == 'dict_hy') {
                $headArr[] = '行业';
            }

            if ($field == 'create_id') {
                $headArr[] = '产品经理';
            }

            if ($field == 'dict_xs') {
                $headArr[] = '销售';
            }

            if ($field == 'channel') {
                $headArr[] = '渠道信息';
            }

            if ($field == 'dict_cp') {
                $headArr[] = '产品线';
            }

            if ($field == 'budget') {
                $headArr[] = '预算(万)';
            }
            if ($field == 'tender_time') {
                $headArr[] = '招标时间';
            }

            if ($field == 'project_status') {
                $headArr[] = '项目状态';
            }
            if ($field == 'power') {
                $headArr[] = '把握度';
            }

            if ($field == 'project_rate') {
                $headArr[] = '当前进展';
            }

            if ($field == 'is_state') {
                $headArr[] = '了解项目情况';
            }

            if ($field == 'is_alone') {
                $headArr[] = '是否落单';
            }

            if ($field == 'is_promise') {
                $headArr[] = '得到客户经理口头承诺';
            }
            if ($field == 'is_change') {
                $headArr[] = '是否换单';
            }
            if ($field == 'urgent') {
                $headArr[] = '是否加急';
            }
            if ($field == 'win_channel') {
                $headArr[] = '中标渠道';
            }
            if ($field == 'vender') {
                $headArr[] = '厂商负责人';
            }
            if ($field == 'demo') {
                $headArr[] = '备注';
            }

        }
        $filename = "神州数码项目报表";
        To_Exel($filename, $headArr, $list);
    }

    //编辑信息
    public function edit_project()
    {
        $data = array();
        $data = I('post.arr');
        foreach ($data as $url) {
            $a[] = $url;
        }
        $value = (int)$a[0];
        $this->ajaxReturn($value);
    }

    //显示编辑页面
    public function  show_edit()
    {
        $id = I("post.object_status_id");
        $project_info = M('project_info');
        $data_dict = M('data_dict');
        $user_info = M('user_info');
        $get_status = (int)$project_info->where("Id = $id")->getField('status');
        $uset_list = $user_info->where("id !=0")->select(); //获取人员的信息
        $data_list = $data_dict->where("id !=0")->order(array('order_num'=>'desc','id'=>'desc'))->select(); //获取具体数据信息
        $data = $project_info->where("Id=$id")->find();
        $data['tender_time'] = $data['tender_time'];
        if ($data) {
            $this->ajaxReturn($data);//如果找到数据则返回数据
        } else {
            $this->ajaxReturn(1);//如果找不到怎返回1
        }
    }

    //编辑 当点击表格时弹框包括（项目的状态，进度，和把握度）可根据提交的类型更新不同的参数(在修改的同时添加历史记录)
    public function edit()
    {
        $project_info = M('project_info');
        $add_to_history = M('project_history');
        $data_dict = M('data_dict');
        $data['Id'] = $id = (int)I('post.project_id');
        $user_tell = $_COOKIE['userId'];
        $user_info = M('user_info');
        $data_list = $project_info->where("Id =$id")->select();
        $find_user = (int)$user_info->where("tel=$user_tell")->getField('Id');
        $data['create_id'] = $find_user;
        $data['dict_qy'] = I('post.object_aera');
        $data['dict_hy'] = I('post.object_hangye');
        $data['dict_xs'] = I('post.object_sealman');
        $data['name'] = I('post.project_name');
        $data['channel'] = I('post.project_channel');
        $data['dict_cp'] = I('post.object_list');
        $data['budget'] = I('post.object_budget');
        $data['tender_time'] = I('post.get_date');
        $data['project_status'] = I('post.object_status');
        $data['power'] = I('post.grasp_degree');
        $data['project_rate'] = I('post.current_progress');
        $data['win_channel'] = I('post.winning_channel');
        $data['vender'] = I('post.account_manager');
        $data['urgent'] = I('post.urgent');
        $data['demo'] = I('post.demo');
        $time = time();
        $data['update_time'] = $time;
        $content = '';
        foreach ($data_list as $item) {
            if ($item['dict_qy'] != $data['dict_qy']) {
                $a = $data['dict_qy'];
                $get_name = $data_dict->where("Id= $a")->getField('name');
                $b = $item['dict_qy'];
                $get_old_name = $data_dict->where("Id= $b")->getField('name');
                $content .= "项目区域由" . $get_old_name . "变换为" . $get_name . ";";
            }
            if ($item['dict_hy'] != $data['dict_hy']) {
                $a = $data['dict_hy'];
                $get_name = $data_dict->where("Id=$a")->getField('name');
                $b = $item['dict_hy'];
                $get_old_name = $data_dict->where("Id= $b")->getField('name');
                $content .= "项目的行业由" . $get_old_name . "变换为：" . $get_name . ";";
            }
            if ($item['dict_xs'] != $data['dict_xs']) {
                $b = $data['dict_xs'];
                $user_name = $user_info->where("Id =$b")->getField('name');
                $c = $item['dict_xs'];
                $get_old_name = $user_info->where("Id= $c")->getField('name');

                $content .= "项目的销售由" . $get_old_name . "变换为：" . $user_name . ";";
            }
            if ($item['name'] != $data['name']) {
                $content .= "项目名称由" . $item['name'] . "变换为" . $data['name'] . ";";
            }
            if ($item['channel'] != $data['channel']) {
                $content .= "项目渠道信息由" . $item['channel'] . "变换为" . $data['name'] . ";";
            }
            if ($item['dict_cp'] != $data['dict_cp']) {
                $a = $data['dict_cp'];
                $get_name = $data_dict->where("Id=$a")->getField('name');
                $b = $item['dict_cp'];
                $get_old_name = $data_dict->where("Id= $b")->getField('name');
                $content .= "项目的产品信息由" . $get_old_name . "变换为：" . $get_name . ";";
            }
            if ($item['budget'] != $data['budget']) {
                $content .= "项目的预算由" . $item['budget'] . "变换为" . $data['budget'] . ";";
            }
            if ($item['tender_time'] != $data['tender_time']) {
                $content .= "项目的投标时间由" . $item['tender_time'] . "变换为" . $data['tender_time'] . ";";
            }
            if ($item['project_status'] != $data['project_status']) {
                $a = $data['project_status'];
                $get_name = $data_dict->where("Id=$a")->getField('name');
                $b = $item['project_status'];
                $get_old_name = $data_dict->where("Id= $b")->getField('name');
                $content .= "项目的状态由" . $get_old_name . "变换为：" . $get_name . ";";
            }

            if ($item['power'] != $data['power']) {
                $a = $data['power'];
                $get_name = $data_dict->where("Id=$a")->getField('name');
                $b = $item['power'];
                $get_old_name = $data_dict->where("Id= $b")->getField('name');
                $content .= "项目的把握度由" . $get_old_name . "变换为：" . $get_name . " ";
            }
            if ($item['project_rate'] != $data['project_rate']) {
                $a = $data['project_rate'];
                $get_name = $data_dict->where("Id=$a")->getField('name');
                $b = $item['project_rate'];
                $get_old_name = $data_dict->where("Id= $b")->getField('name');
                $content .= "项目的当前进度由" . $get_old_name . "变换为：" . $get_name . ";";
            }
            if ($item['win_channel'] != $data['win_channel']) {
                $content .= "项目的中标渠道由" . $item['win_channel'] . "变换为" . $data['win_channel'] . ";";
            }
            if ($item['vender'] != $data['vender']) {
                $content .= "项目的厂商负责人由" . $item['vender'] . "变换为" . $data['vender']. ";";
            }
            if ($item['demo'] != $data['demo']) {
                $content .= "项目的备注信息由" . $item['demo'] . "变换为" . $data['demo']. ";";
            }
            if($item['urgent'] != $data['urgent']){
                $a =(int)$data['urgent'];
                if($a == 0){
                    $a = "否";
                }else{
                    $a="是";
                }
                $b = (int)$item['urgent'];
                if($b == 0){
                    $b = "否";
                }else{
                    $b="是";
                }
                $content .= "项目的加急由" . $b . "变换为" . $a;
            }
            if($item['dict_qy'] == $data['dict_qy']&&$item['dict_hy'] == $data['dict_hy']&&$item['dict_xs'] == $data['dict_xs']&&$item['name'] == $data['name']&&$item['channel'] == $data['channel']&&$item['dict_cp'] == $data['dict_cp']&&$item['budget'] == $data['budget']&&$item['tender_time'] == $data['tender_time']&&$item['project_status'] == $data['project_status']&&$item['power'] == $data['power']&&$item['project_rate'] == $data['project_rate']&&$item['win_channel'] == $data['win_channel']&&$item['vender'] == $data['vender']&&$item['urgent'] == $data['urgent']){
                $obj = $project_info->where("Id=$id")->data($data)->save();
                if($obj){
                    $this->ajaxReturn(0); //如果成功的情况下返回0
                }else{
                    $this->ajaxReturn(1); //如果不成功的情况下返回1
                }
            }
        }

        $arr['project_id'] = $id;
        $arr['user_id'] = $data['create_id'];
        $arr['update_time'] = $time;
        $arr['content'] = $content;
        $arr['u_type'] = 2;
        $obj = $project_info->where("Id=$id")->data($data)->save();
        if ($obj) {
            $add_history = $add_to_history->data($arr)->add();//把变更记录信心添加到历史表中
            /*判断所提交的项目名是否重复*/
            //  $result = D('Home/Products')->edit($data);
            if ($add_history) {
                $this->ajaxReturn(0); //如果成功的情况下返回0
            } else {
                $this->ajaxReturn(1); //如果不成功的情况下返回1
            }
        } else {
            $this->ajaxReturn(1);
        }
    }


    //添加历史记录
    public function add_to_history()
    {
        $project_info = M('project_info');
        $data['Id']=$id= I("post.project_id");
        $data['project_status'] = I("post.object_status");
        $data['power'] = I("post.grasp_degree");
        $data['project_rate'] = I("post.current_progress");
        $user_tell = $_COOKIE['userId'];
        $user_info = M('user_info');
        $data_dict = M('data_dict');
        $project_history = M('project_history');
        $find_user = (int)$user_info->where("tel=$user_tell")->getField('Id');
        $get_project_status = $project_info->where("Id=$id")->getField('project_status');
        $get_project_power = $project_info->where("Id=$id")->getField('power');
        $get_project_rate = $project_info->where("Id=$id")->getField('project_rate');
        $content = " ";
        if ($get_project_status != $data['project_status']) {
            $a = $data['project_status'];
            $get_old_name = $data_dict->where("Id= $get_project_status")->getField('name');
            $get_type = $data_dict->where("Id= $a")->getField('name'); //根据要修改类型的id 获取类型
            $content .="项目状态由" . $get_old_name . "状态变换为" . $get_type .";";
        }
        if ($get_project_power !=  $data['power']) {
            $a =  $data['power'];
            $get_old_name = $data_dict->where("Id= $get_project_power")->getField('name');
            $get_type = $data_dict->where("Id= $a")->getField('name');
            $content .= "项目把握度由" . $get_old_name . "变换为" . $get_type.";";
        }
        if ($get_project_rate != $data['project_rate']) {
            $a=$data['project_rate'];
            $get_old_name = $data_dict->where("Id= $get_project_rate")->getField('name');
            $get_type = $data_dict->where("Id=$a")->getField('name');
            $content .= "项目进展由" . $get_old_name . "变换为" . $get_type.";";
        }
        $arr['project_id'] = $id;
        $arr['user_id'] = $find_user;
        $arr['update_time'] = time();
        $arr['content'] = $content;
        $arr['u_type'] = 2;
        $obj = $project_info->where("Id=$id")->data($data)->save();
        if ($obj) {
            $add_history = $project_history->data($arr)->add();//把变更记录信心添加到历史表中
            if ($add_history) {
                $this->ajaxReturn(0); //如果成功的情况下返回0
            } else {
                $this->ajaxReturn(1); //如果不成功的情况下返回1
            }
        } else {
            $this->ajaxReturn(1);
        }


    }

    //选择删除记录
    public function delete_projects()
    {
        $project_info = M('project_info');
        $data = (int)I('post.object_status_id');
        $find_type = (int)$project_info->where("Id= $data")->getField('status');
        if ($find_type == 1) {
            $this->ajaxReturn(2);//如果项目处于进行状态，则不能修改
        } else {
            $save_status = $project_info->where("Id=$data")->save(array('status' => 2));
            if ($save_status) {
                $this->ajaxReturn(1);
            } else {
                $this->ajaxReturn(0);
            }
        }

    }

    public function add_newproject()
    {
        $id = (int)I('post.object_status_id');
        $project_info = M('project_info');
        $save_status = $project_info->where("Id=$id")->save(array('status' => 1));
        if ($save_status) {
            $this->ajaxReturn(0);
        } else {
            $this->ajaxReturn(1);
        }

    }

    //查看历史记录
    public function look_history()
    {
        $id = (int)I('post.id');
        $data_name = array();
        $data_project = array();
        $data_user = array();
        $data_type = array();
        $project_history = M('project_history');
        $project_info = M('project_info');
        $data_dict = M('data_dict');
        $get_history_count = $project_history->where('project_id = $id')->count();
        $Page = new \Think\Page($get_history_count, 10);
        $show = $Page->show();// 分页显示输出
        $first = $Page->firstRow;
        $last = $Page->listRows;
        $dict = D()->query("select Id,name from data_dict");
        $user = D()->query("select Id,name from user_info");
        $project = D()->query("select Id,name from project_info");

        //基础数据名称
        foreach ($dict as $item) {
            $data_name[$item["Id"]] = $item["name"];
        }
        //用户数据名称
        foreach ($user as $item) {
            $data_user[$item["Id"]] = $item["name"];
        }
        //项目名称
        foreach ($project as $item) {
            $data_project[$item["Id"]] = $item["name"];
        }
        $projects = D()->query("select * from project_history where project_id =$id order by Id desc limit $first, $last");

        for ($i = 0; $i < count($projects); $i++) {
            $projects[$i]["project_id"] = $data_project[intval($projects[$i]["project_id"])];
            $projects[$i]["user_id"] = $data_user[intval($projects[$i]["user_id"])];
            //$projects[$i]["content"] = $data_name[intval($projects[$i]["from"])];
            $projects[$i]["update_time"] = date("Y-m-d", $projects[$i]["update_time"]);
        }
        $this->ajaxReturn($projects);
    }
}