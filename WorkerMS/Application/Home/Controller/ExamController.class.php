<?php
namespace Home\Controller;
use Think\Controller;
class ExamController extends Controller {
    public function project(){
        if($_SESSION['member']['office'] != '管理员'){
            $this->error('您没有此权限！', U('Index/index'), 2);
            die();
        }
        if(IS_POST){
            $res = M('exam')->data(array('project'   => I('project')))->add();
            if($res)
                $this->success('添加成功！', U('project'), 1);
            else
                $this->error('添加失败！', U('project'), 2);
        }else{
            $this->exams = M('exam')->select();
            layout('Layout/simple');
            $this->display();
        }
    }

    public function him(){
        if(IS_POST){
            $content = I('content');
            $grade = I('grade');
            $data = array(
                'examer' => $_SESSION['member']['name'],
                'member'  => I('name'),
                'project' => I('project'),
                'time'  => date('Y-m'),
            );
            $member = M('member');
            $msg_member = $member->where('name="'.$data['member'].'"')->find();
            $examer_time = $msg_member['examer_time'];
            //根据 考核人-考核项目 找到考核权重
            $exam = $msg_member['exam'];
            $exam_weight = $msg_member['exam_weight'];
            $exam = array_slice(explode('#', $exam), 1, -1);
            $weight = array_slice(explode('#', $exam_weight), 1, -1);
            $examer_time = array_slice(explode('#', $examer_time), 1, -1);
            $get_all_exam = 1;
            // echo '<meta charset="UTF-8">';
            // dump($data['project']);
            // dump($exam);dump($weight);dump($examer_time);die;
            $examer_time_save = '#';
            foreach($examer_time as $k=>$value){
                $tmp = explode('>', $value);
                $examer[] = $tmp[0];
                $time[] = $tmp[1];
                // examer == self
                if($examer[$k]=='self')
                    $examer[$k] = $msg_member['name'];
                if($exam[$k] == $data['project'] && $examer[$k] == $data['examer']){
                    $this_weight = $weight[$k];
                    $time[$k] = $data['time'];
                }
                if($time[$k] != $data['time'])
                    $get_all_exam = 0;
                $examer_time_save .= $examer[$k].'>'.$time[$k].'#';
            }
            //     dump($this_weight);
            // dump($examer_time_save);die;
            $exam_data = M('exam_data');
            // 将数据写入考核数据中
            foreach($content as $k => $c){
                $data['content'] = $c;
                $data['grade'] = ((float)$grade[$k])*((float)$this_weight);
                $re = $exam_data->data($data)->add();
                if(!$re)
                    $exam_data->data($data)->add();
            }
            $res = $member->where('id='.$msg_member['id'])->data(array(
                'examer_time' => $examer_time_save,
            ))->save();
            if($get_all_exam == 1){
                $res = $exam_data->where('member="'.$data['member'].'" AND time="'.$data['time'].'"')->select();
                $grade_count = 0;
                foreach($res as $k => $exam_info){
                    $grade_count += (float)$exam_info['grade'];
                }
                $re = M('grades')->data(array(
                    'time'  => $data['time'],
                    'member' => $msg_member['name'],
                    'grade' => number_format($grade_count, 1),
                    'department' => $msg_member['department'],
                ))->add();
            }
            if($re && $res){
                $con = '#'.$_SESSION['member']['name'].'>';
                $members = M('member')->where(array('examer_time' => array('like', '%'.$con.'%')))->select();
                foreach($members as $k => $member){
                    $examer_time = $member['examer_time'];
                    $groups = array_slice(explode('#', $examer_time), 1, -1);
                    foreach($groups as $k => $group){
                        if(strpos('#'.$group, $_SESSION['member']['name']) && $group != $_SESSION['member']['name'].'>'.date('Y-m')){
                            $this->success('评分成功，进入下一个...', U('him', array('id' => $member['id'], 'project' => $exam[$k])), 1);
                            break;
                        }
                    }
                }
                $this->success('评分成功！', U('Index/index'), 1);
            }
            else
                $this->error("评分失败！", U('them'), 1);
        }else{
            $id = I('id');
            if($id == $_SESSION['member']['id']){
                // if(!strpos($_SESSION['member']['examer_time'], 'self>')){
                //     $this->error('您不需要自我评分！', U('Index/index'), 1);
                //     die();
                // }
                $examer_times = explode('#', $_SESSION['member']['examer_time']);
                $exams = explode('#', $_SESSION['member']['exam']);
                for($i=0;$i<count($examer_times);$i++){
                    $tmp = '#'.$examer_times[$i];
                    // dump($tmp);
                    // dump('#'.$_SESSION['member']['name'].'>'.date("Y-m"));
                    if(strpos($tmp, 'self') || strpos($tmp, $_SESSION['member']['name'])){
                        if($tmp == '#'.$_SESSION['member']['name'].'>'.date("Y-m")){
                            $this->error('您不需要自我评分！', U('Index/index'), 1);
                            die();
                        }
                        $project = $exams[$i];
                    }
                }
            }
            $member = M('member');
            $res = $member->where('id='.I('id'))->find();
            if(!isset($project))
                $project = I('project');
            // dump($project);
            $exam_mod = M('exam_content');
            $exam_content = $exam_mod->where('project="'.$project.'"')->select();
            $this->exam_project = $project;
            $this->exam_content = $exam_content;
            $this->person_examed = $res['name'];
            layout('Layout/simple');
            $this->display();
        }
    }

    public function them(){
        $con = '#'.$_SESSION['member']['name'].'>';
        $members = M('member')->where(array('examer_time' => array('like', '%'.$con.'%')))->select();
        $members_waiting = array();
        foreach($members as $k => $member){
            $examer_time = $member['examer_time'];
            $groups = array_slice(explode('#', $examer_time), 1, -1);
            $exams = $member['exam'];
            $exams = array_slice(explode('#', $exams), 1, -1);
            foreach($groups as $k => $group){
                if(strpos('#'.$group, $_SESSION['member']['name']) && $group != $_SESSION['member']['name'].'>'.date('Y-m')){
                    $member['project'] = $exams[$k];
                    $members_waiting[] = $member;
                    break;
                }
            }
        }
        $this->persons = $members_waiting;
        layout('Layout/simple');
        $this->display();
    }

    public function project_del(){
        if($_SESSION['member']['office'] != '管理员'){
            $this->error('您没有此权限！', U('Index/index'), 2);
            die();
        }
        $con = 'project="'.I('project').'"';
        $res = M('exam')->where($con)->delete();
        if($res){
            M('exam_content')->where($con)->delete();
            $this->success('删除成功！', U('project'), 1);}
        else
            $this->error('删除失败！', U('project'), 2);
    }

    public function detail(){
        if($_SESSION['member']['office'] != '管理员'){
            $this->error('您没有此权限！', U('Index/index'), 2);
            die();
        }
        $exam_content = M('exam_content')->where('project="'.I('project').'"')->select();
        $this->project_name = I('project');
        $this->exam_content = $exam_content;
        layout('Layout/simple');
        $this->display();
    }

    public function exam_info(){
        if($_SESSION['member']['office'] != '管理员'){
            $this->error('您没有此权限！', U('Index/index'), 2);
            die();
        }
        $project = I('project');
        $map['project'] = $project;
        M('exam_content')->where($map)->delete();
        $exams = I('exam');
        $grade = I('grade');
        for($i=0;$i<count($exams);$i++){
            $data = array(
                'project' => $project,
                'content' => $exams[$i],
                'grade'   => $grade[$i],
            );
            M('exam_content')->data($data)->add();
        }
        $this->success('操作成功！', U('detail', array('project' => $project)), 1);
    }
}