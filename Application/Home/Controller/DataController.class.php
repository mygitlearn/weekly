<?php
namespace Home\Controller;

use Think\Controller;
use Think\Log;
use Think\Page;

class DataController extends BaseController
{

    private $count_for_page = 10;

    public function _initialize()
    {
        parent::_initialize();
    }

    //1.区域
    public function area()
    {
        $this->publicVal(1);
        $this->assign('type', '区域');
        $this->assign('type_id', '1');
        $this->display('data');
    }

    //2.行业
    public function industry()
    {
        $this->publicVal(2);
        $this->assign('type', '行业');
        $this->assign('type_id', '2');
        $this->display('data');
    }

    //3.产品线
    public function line()
    {
        $this->publicVal(3);
        $this->assign('type', '产品线');
        $this->assign('type_id', '3');
        $this->display('data');
    }

    //4.项目状态
    public function status()
    {
        $this->publicVal(4);
        $this->assign('type', '项目状态');
        $this->assign('type_id', '4');
        $this->display('data');
    }

    //5.把握度
    public function power()
    {
        $this->publicVal(5);
        $this->assign('type', '把握度');
        $this->assign('type_id', '5');
        $this->display('data');
    }

    //6.当前进度
    public function progress()
    {
        $this->publicVal(6);
        $this->assign('type', '当前进度');
        $this->assign('type_id', '6');
        $this->display('data');
    }

    //公共调用的方法
    private function publicVal($type)
    {
        $where['type'] = $type;
        $where['is_del'] = 0;
        $count_for_page = I('page_count', $this->count_for_page, 'int');
        $all_count = M("data_dict")->field("id,name")->where($where)->count();
        $this->assign('all_count', $all_count);
        $all_page = ceil($all_count / $this->count_for_page);
        $this->assign('all_page', $all_page);
        $this->assign('_type', $where['type']);
        $this->dataBase($all_count, $this->count_for_page, $where);
    }

    public function getData()
    {
        $p = I('p', 1, 'int');
        $this->count_for_page = I('count', $this->count_for_page, 'int');
        $val = I('where', null, 'trim');
        $type = I('type', 1, 'int');
        $order = I('order', 0, 'int');
        if (0 == $order) {
            $order = 'Id DESC';
        } else if (1 == $order) {
            $order = 'Id';
        } else {
            $order = 'Id DESC';
        }

        if (empty($val)) {
            $where['type'] = $type;
            $where['is_del'] = 0;
        } else {
            $condition['name'] = array('like', '%' . $val . '%');
            $condition['type'] = $type;
            $where = $condition;
            $data['where'] = $where;
        }
        $first_row = ($p - 1) * $this->count_for_page;
        $result = M("data_dict")->field("id,name,order_num")
            ->where($where)
            ->limit($first_row, $this->count_for_page)
            ->order($order)
            ->select();
        session('area_data', $result);
        $all_count = M("data_dict")
            ->field("id,name,order_num")
            ->where($where)
            ->count();
        $all_page = ceil($all_count / $this->count_for_page);
        $data['all_count'] = $all_count;
        $data['all_page'] = $all_page;
        $data['result'] = $result;
        $this->ajaxReturn($data);
    }

    //添加
    public function add()
    {
        return $this->add_data();
    }

    //删除
    public function delete()
    {
        return $this->delete_data();
    }

    //修改排序
    public function orderFix()
    {
        $ida['Id'] = I('id');
        $idb['order_num'] = I('order');
        $idc['Id'] = I('up_id');
        $idd['order_num'] = I('up_order');

        $data_dict = M("data_dict");

        $res = $data_dict
            ->where($ida)
            ->data($idd)
            ->save();

        $resl = $data_dict
            ->where($idc)
            ->data($idb)
            ->save();
//        file_put_contents('D:/bbb.txt', $data_dict->getLastSql());

        if ($res && $resl) {
            $re_data['status'] = 1;
            $this->ajaxReturn($re_data);
        } else {
            $re_data['status'] = 0;
            $this->ajaxReturn($re_data);
        }

    }

    //修改
    public function  save()
    {
        $data = I('post.');
        $data_dict = M("data_dict");
        $where['Id'] = $data['id'];
        $data_arr['name'] = $data['name'];
        $data_arr['type'] = $data['type'];
        $data_arr['update_time'] = time();
        $condition['name'] = $data['name'];
        $condition['is_del'] = 0;
        $condition["Id"] = array('neq', $data['id']);
        $repeat_name = $data_dict->where($condition)->find();

        if ($repeat_name) {
            $re_data['status'] = -1;
            $this->ajaxReturn($re_data);
        } else {
            $res = $data_dict
                ->where($where)
                ->save($data_arr);
            if ($res) {
                $re_data['status'] = 1;
                $this->ajaxReturn($re_data);
            } else {
                $re_data['status'] = 0;
                $this->ajaxReturn($re_data);
            }
        }
    }

    //得到基本数据
    public function dataBase($all_count, $count_for_page, $where)
    {
        return $this->getDataBase($all_count, $count_for_page, $where);
    }

    //添加数据
    public function add_data()
    {
        $data_arr['name'] = I('name');

        $data_arr['type'] = I('type');
        $data_dict = M("data_dict");

        $condition['name'] = I('name');
        $condition['is_del'] = 0;

        $max_ord['is_del'] = 0;
        $max_ord['type'] = I('type');

        $order_max = $data_dict->where($max_ord)->max('order_num');
        $data_arr['order_num'] = $order_max['order_num'] + 1;
        $data_arr['update_time'] = time();

        $repeat_name = $data_dict->where($condition)->find();
        if ($repeat_name) {
            $data['status'] = -1;
            $this->ajaxReturn($data);
        } else {
            $result = $data_dict->field("name", "type", "order_num", "update_time")->data($data_arr)->add();
            if ($result) {
                $data['status'] = 1;
                $this->ajaxReturn($data);
            } else {
                $data['status'] = 0;
                $this->ajaxReturn($data);
            }
        }
    }

    //删除数据
    public function delete_data()
    {
        $data_dict = M("data_dict");
        $where['Id'] = I('id');
        $status['is_del'] = 1;
        $result = $data_dict->where($where)->save($status);
//        file_put_contents('D:/bbb.txt', $data_dict->getLastSql());
        if ($result != false) {
            $data['status'] = 1;
            $data['data'] = $result;
            $this->ajaxReturn("123");
        } else {
            $data['status'] = 0;
            $data['data'] = $result;
            $this->ajaxReturn($data);
        }

    }

    public function getDataBase($all_count, $count_for_page, $where)
    {
        $data_dict = M("data_dict");
        $page = new Page($all_count, $count_for_page);
        $result = $data_dict->field("id,name,order_num")
            ->where($where)
            ->limit($page->firstRow, $page->listRows)
//            ->order("Id DESC")
            ->order(array('order_num' => 'desc', 'id' => 'desc'))
            ->select();
        session('area_data', $result);
//        var_dump($result);
//        exit();
        if ($result) {
            return $this->assign("basedata", $result);
        } else {
            return $this->assign("basedata", null);
        }
    }

    public function get_date_style()
    {
        $project_list = session('area_data');
        $list = array();

        foreach ($project_list as $k => $project_info) {
            $list[$k]["name"] = $project_info['name'];
        }

        $headArr = ['名称'];
        $filename = "神州数码基础数据报表";

        To_Exel($filename, $headArr, $list);
    }
}