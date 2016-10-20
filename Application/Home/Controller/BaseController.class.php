<?php
namespace Home\Controller;

use Think\Controller;

class BaseController extends Controller
{
    public function _initialize()
    {
        if (cookie("userId") == "" || cookie("userId") == null) {
            $this->redirect("login/login");
        }
        $action = strtolower(ACTION_NAME);
        $roleId = cookie("roleId");
        $permission = array("index", "prod", "user", "area", "industry", "line", "status", "power", "progress", "week", "quarter", "sale");
        if (in_array($action, $permission)) {
            $map["action"] = strtolower(ACTION_NAME);
            $map["roleid"] = $roleId;
            $rlt = M("node_info")->where($map)->select();
            if ($rlt == null) {
                //dump("You do not have permission or login again.");
                //exit;
                $this->redirect("login/login");
            }
        }

        if ($roleId == 1) {
            $this->assign("project_count", M("project_info")->where("status=1")->count("Id"));
        }
        if ($roleId == 2) {
            $this->assign("project_count", M("project_info")->where("(status=1 or status=0) and create_id =" . $this->getId())->count("Id"));
        }
        if ($roleId == 3) {
            $this->assign("project_count", M("project_info")->where("status=1 and dict_xs=" . $this->getId())->count("Id"));
        }

        $sql = "select * from node_info where pid=0 and roleid=" . $roleId . ' order by sort asc';
        $menus = D()->query($sql);

        for ($i = 0; $i < count($menus); $i++) {
            $sql = "select * from node_info where pid=" . $menus[$i]["Id"];
            $item = D()->query($sql);
            for ($j = 0; $j < count($item); $j++) {
                if ($action == $item[$j]["action"]) {
                    $item[$j]["active"] = 1;
                } else {
                    $item[$j]["active"] = 0;
                }
            }
            if ($action == $menus[$i]["action"]) {
                $menus[$i]["active"] = 1;
            } else {
                $menus[$i]["active"] = 0;
            }
            $menus[$i]["child"] = $item;
        }
        //var_dump($menus);
        //exit;

        $this->assign("menus", $menus);
        $this->assign("name", cookie("userName"));

    }

    function  getId()
    {
        $tel['tel'] = $_COOKIE['userId'];
        if ($tel == "") {
            return;
        }
        $user = M("user_info")->field("Id")->where($tel)->select(); //获取获取用户Id和工作类型
        return $user[0]['Id'];
    }
}