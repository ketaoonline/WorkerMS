<?php
namespace Home\Controller;
use Think\Controller;
header("Content-type: text/html;charset=utf-8");
class CommonController extends Controller {
    public function _initialize(){
        if (!isset($_SESSION['member'])){
            $this->redirect('Login/login', '', 1, '正在跳转！');
        }
    }
}