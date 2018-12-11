<?php
namespace Home\Controller;
use Think\Controller;
class MemberController extends CommonController{
    public function member(){
        if($_SESSION['member']['office'] != '管理员'){
            $this->error('您没有此权限！', U('Index/index'), 2);
            die();
        }
        if(IS_POST){
            $res = M('office')->where('level="'.I('office').'"')->find();
            $data_exam = '#'; $data_examer_time = '#'; $data_exam_weight = '#';
            $exam = I('exam'); $examer = I('examer'); $weight = I('weight');
            for($i=0;$i<count($exam);$i++){
                if($exam[$i] != '' && $examer[$i] != '' && $weight[$i] != '0.'){
                    $data_exam .= $exam[$i].'#';
                    $data_examer_time .= $examer[$i].'>#';
                    $data_exam_weight .= $weight[$i].'#';
                }
            }
            $data = array(
                'account'   => I('account'),
                'password'  => I('password'),
                'name'      => I('name'),
                'department'=> I('department'),
                'office'    => I('office'),
                'sort'      => $res['sort'],
                'exam'      => $data_exam,
                'examer_time'=> $data_examer_time,
                'exam_weight' => $data_exam_weight,
            );
            $re = M('member')->data($data)->add();
            if($re)
                $this->success('添加'.$data['office'].'成功', U('member', array('add' => 1)), 1);
            else
                $this->success('添加'.$data['office'].'失败', U('member', array('add' => 1)), 2);
        }else{
            $member = M('member');
            $this->levels = M('office')->order('sort desc')->select();
            $filter = 'office<>"管理员"';
            if(I('department')){
                $filter .= ' AND department="'.I('department').'"';
                $count = $member->where($filter)->count();
            }else
                $count = $member->count();
            $Page       = new \Think\Page($count,20);
            // $this->members = $member->where($filter)->page($_GET['p'].',20')->select();
            $members = $member->where($filter)->page($_GET['p'].',20')->select();
            $members_upd = array();
            foreach($members as $k=>$member){
                $member['examer_time'] = str_replace('#', '<br/>', $member['examer_time']);
                $member['examer_time']  = substr($member['examer_time'], 5, -5);
                $member['exam'] = str_replace('#', '<br/>', $member['exam']);
                $member['exam']  = substr($member['exam'], 5, -5);
                $member['exam_weight'] = str_replace('#', '<br/>', $member['exam_weight']);
                $member['exam_weight']  = substr($member['exam_weight'], 5, -5);
                $members_upd[] = $member;
            }
            $this->members = $members_upd;

            $this->page = $Page->show();
            $this->departments = M('department')->select();
            if (I('add') == 1)
                $this->add = 1;
            layout('Layout/simple');
            $this->display();
        }
    }

    public function grade(){
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

    public function detail(){
        if($_SESSION['member']['office'] != '管理员'){
            $this->error('您没有此权限！', U('Index/index'), 2);
            die();
        }
        $member = M('member');
        $this->levels = M('office')->order('sort desc')->select();
        $this->msg_member = $member->where('id='.I('id'))->find();
        $this->departments = M('department')->select();
        layout('Layout/simple');
        $this->display();
    }

    public function modify(){
        if($_SESSION['member']['office'] != '管理员'){
            $this->error('您没有此权限！', U('Index/index'), 2);
            die();
        }
        if(IS_POST){
            $res = M('office')->where('level="'.I('office').'"')->find();
            $data_exam = '#'; $data_examer_time = '#'; $data_exam_weight = '#';
            $exam = I('exam'); $examer = I('examer'); $weight = I('weight');
            for($i=0;$i<count($exam);$i++){
                if($exam[$i] != '' && $examer[$i] != '' && $weight[$i] != '0.'){
                    $data_exam .= $exam[$i].'#';
                    $data_examer_time .= $examer[$i].'>#';
                    $data_exam_weight .= $weight[$i].'#';
                }
            }
            $data = array(
                'account'   => I('account'),
                'password'  => I('password'),
                'name'      => I('name'),
                'department'=> I('department'),
                'office'    => I('office'),
                'sort'      => $res['sort'],
                'exam'      => $data_exam,
                'examer_time'=> $data_examer_time,
                'exam_weight' => $data_exam_weight,
            );
            $re = M('member')->where('id='.I('id'))->data($data)->save();
            if($re)
                $this->success('修改'.$data['office'].'成功', U('member', array('add' => 1)), 1);
            else
                $this->success('修改'.$data['office'].'失败', U('member', array('add' => 1)), 2);
        }
    }

    public function delete(){
        $id = I('id');
        $member = M('member');
        $person = $member->where('id='.$id)->find();
        $member->where('id='.$id)->delete();
        M('exam_data')->where('member="'.$person['name'].'"')->delete();
        M('grades')->where('member="'.$person['name'].'"')->delete();
        $this->success('删除成功！', U('Index/index'), 1);
    }
}