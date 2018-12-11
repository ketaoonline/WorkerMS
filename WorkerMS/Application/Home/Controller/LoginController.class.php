<?php
namespace Home\Controller;
use Think\Controller;
class LoginController extends Controller {
    public function login(){
        if (IS_POST){
            $username = I('username');
            $password = I('password');
            $rs = M('member')->select();
            $member = M('member')->where(array('account' => $username))->find();
            if (!$member)
                $this->error('未找到用户！', U(''), 2);
            else{
                if($member['password'] != $password)
                    $this->error('密码错误！', U(''), 2);
                else{
                    $_SESSION['member'] = $member;
                    $this->success('登陆成功！', U('Index/index'), 1);
                }
            }
        }else{
            if($_SESSION['member'])
                $this->redirect('Index/index', '', 1, '<meta charset="utf-8">正在跳转！');
            $this->display();
        }
    }

    public function logout(){
        unset($_SESSION['member']);
        $this->redirect('Login/login', '', 1, '正在跳转！');
    }

    public function get_exam(){
        $member = M('member');
        $levels = M('office')->order('sort desc')->select();
        $lowest_level = $levels[0]['sort'];
        $leaders = $member->where('sort<>'.$lowest_level)->order('sort')->select();
        $data['exam'] = M('exam')->select();
        $data['leaders'] = $leaders;
        $this->ajaxReturn($data);
    }
}