<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends CommonController {
    public function index(){
        $department = I('department');
        $grade = M('grades');
        $filter = 'time="'.date("Y-m").'"';
        if($department){
            $filter .= ' AND department="'.$department.'"';
            $count = $grade->where($filter)->count();
        }else{
            $count = $grade->where($filter)->count();
        }
        $Page       = new \Think\Page($count,20);
        $grades = $grade->where($filter)->order('grade desc')->page($_GET['p'].',20')->select();
        $exam_data = M('exam_data');
        foreach($grades as $k => $grade){
            $res = $exam_data->where('member="'.$grade['member'].'"')->select();
            $grades[$k]['msg'] = '';
            $tmp = array();
            foreach ($res as $key => $each_exam) {
                $each_exam['grade'] = (float)$each_exam['grade'];
                if(!isset($tmp[$each_exam['project']])){
                    $tmp[$each_exam['project']] = $each_exam['grade'];
                }else{
                    $tmp[$each_exam['project']] += $each_exam['grade'];
                }
            }
            // dump($tmp);
            foreach($tmp as $project=>$grade){
                $grades[$k]['msg'] .= $project.'='.$grade.'<br/>';
            }
            $grades[$k]['msg'] = substr($grades[$k]['msg'], 0, -5);
            // dump($grades[$k]);
        }
        $this->grades = $grades;
        $this->page = $Page->show();
        layout('Layout/simple');
        $this->departments = M('department')->select();
        $this->display();
    }

    public function department(){
        if($_SESSION['member']['office'] != '管理员'){
            $this->error('您没有此权限！', U('Index/index'), 2);
            die();
        }
        if(IS_POST){
            $res = M('department')->data(array('name'   => I('department')))->add();
            if($res)
                $this->success('添加成功！', U('department'), 1);
            else
                $this->error('添加失败！', U('department'), 2);
        }else{
            $this->departments = M('department')->select();
            layout('Layout/simple');
            $this->display();
        }
    }

    public function department_del(){
        if($_SESSION['member']['office'] != '管理员'){
            $this->error('您没有此权限！', U('Index/index'), 2);
            die();
        }
        $res = M('department')->where('id='.I('id'))->delete();
        if($res)
            $this->success('删除成功！', U('department'), 1);
        else
            $this->error('删除失败！', U('department'), 2);
    }

    public function office(){
        if($_SESSION['member']['office'] != '管理员'){
            $this->error('您没有此权限！', U('Index/index'), 2);
            die();
        }
        if(IS_POST){
            $res = M('office')->data(array('level'   => I('level'), 'sort' => I('sort')))->add();
            if($res)
                $this->success('添加成功！', U('office'), 1);
            else
                $this->error('添加失败！', U('office'), 2);
        }else{
            $this->offices = M('office')->select();
            layout('Layout/simple');
            $this->display();
        }
    }
    public function office_del(){
        if($_SESSION['member']['office'] != '管理员'){
            $this->error('您没有此权限！', U('Index/index'), 2);
            die();
        }
        $res = M('office')->where('id='.I('id'))->delete();
        if($res)
            $this->success('删除成功！', U('office'), 1);
        else
            $this->error('删除失败！', U('office'), 2);
    }

    public function office_modify(){
        if($_SESSION['member']['office'] != '管理员'){
            $this->error('您没有此权限！', U('Index/index'), 2);
            die();
        }
        $res = M('office')->where('id='.I('id'))->data(array('sort' => I('sort')))->save();
        if($res)
            $this->success('修改成功！', U('office'), 1);
        else
            $this->error('修改失败！', U('office'), 2);
    }
}