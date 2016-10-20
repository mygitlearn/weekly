<?php
namespace Home\Controller;

use Think\Controller;
use Think\Log;

class LoginController extends Controller
{

    public function _initialize()
    {

    }

    public function login()
    {

        $this->display();
    }

    public function logout()
    {
        cookie("SM-Id", null);
        cookie("roleId", null);
        cookie("userName", null);
        redirect("login");
    }

    public function dologin()
    {
        $account = trim(I("t"));
        $pwd = md5(trim(I("p")));
        $model = M("user_info");
        $condition["account"] = $account;
        $user = $model->where($condition)->select();
        if (count($user) > 0) {
            cookie("userAccount", $account);
            cookie("userId", $user[0]["tel"]);
            cookie("userName", $user[0]['name']);
            if ($pwd == $user[0]['pwd']) {
                cookie("roleId", $user[0]['job']);
                $json_str["code"] = 0;
                $json_str["msg"] = $user[0]['job'];
            } else {
                $json_str["code"] = 1;
                $json_str["msg"] = "输入的登录密码不正确";
            }
        } else {
            $json_str["code"] = 2;
            $json_str["msg"] = "输入的登录帐号不正确";
        }
        $this->ajaxReturn($json_str);

    }

    public function modify()
    {
        $oldPwd = trim(I("oldPwd"));
        $newPwd = trim(I("newPwd"));
        $model = M("user_info");
        $condition["tel"] = cookie("userId");
        $user = $model->where($condition)->select();

        if ($user[0]["pwd"] != md5($oldPwd)) {
            $json_str["code"] = 1;
            $json_str["msg"] = "原密码输入错误";
        } else {
            $data["pwd"] = md5($newPwd);
            $rlt = $model->where($condition)->save($data);
            if ($rlt) {
                $json_str["code"] = 0;
                $json_str["msg"] = "修改成功";
            } else {
                $json_str["code"] = 2;
                $json_str["msg"] = "修改错误，请联系管理员";
            }
        }
        $this->ajaxReturn($json_str);
    }
}