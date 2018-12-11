<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE>
<html>
    <head>
        <style>
            *{margin: 0px 0px;}
            a{text-decoration: none;color: white;}
            td{color: white;}
            .a-nav{height: 30px;margin-top: 10px;margin-bottom: 10px;}
            .a-nav a{display: block;height: 30px;line-height: 30px;border-radius: 5px;;
                background-color: white;float: left;margin-left: 30px;}
            .a-nav p{padding-left: 10px;padding-right: 10px;color: black;}
            .width80{width: 80px;}
            .width120{width: 120px;}
            .width180{width:180px;}
            .ml60{margin-left: 60px;}
            .text-right{text-align: right;}
            .text-center{text-align: center}
            .color-white{color: white;}
            .color-whitesmoke{color:whitesmoke;}
            input{padding:4px 5px;border:#ABABAB 1px solid;box-shadow:2px 2px 3px #EDEDED inset;font-size:14px;font-weight:bold;border-radius:3px}
            input:hover{border-color:#7B7B7B}
            input:focus{border-color:#3061C6}
            select{padding:4px 5px;border:#ABABAB 1px solid;box-shadow:2px 2px 3px #EDEDED inset;font-size:14px;font-weight:bold;border-radius:3px}
            input-err{border-color:#C66161;background-color:#FBE2E2;color:#C00;box-shadow:2px 2px 3px #EDEDED inset}
            input-focus{background-color:#FFF;border-color:#36C}
            ul{margin-top: 30px;}
            li{list-style-type: none;text-align: left;line-height: 50px;height: 50px;font-size: 20px;}
            li a{padding-left: 20px;}
            .outside{width: 100%;}
            .header{width: 100%;height: 60px;
                background-color: #323232;}
            .title{width: 1078px;margin-left: auto;margin-right: auto;}
            .title p{font-size: 29px;line-height: 60px;color: white;display: block;float: left;}
            .title .user{margin-left: 650px;font-size: 24px;line-height: 60px;}
            .nav{width: 100%;height: 30px;background-color:#8a8a8a;display: none;}
            .container{width: 1123px;margin-left: auto;margin-right: auto;}
            .aside{width: 23%;min-height: 550px;float: left;
                border-bottom-left-radius: 30px;
                background:-webkit-gradient(linear, left 0, right 0, color-stop(0, #525252), color-stop(1,#8c8c8c));
            }
            /*.<?php echo (ACTION_NAME); ?>{*/
                /*border-top-left-radius: 25px;border-bottom-left-radius: 25px;*/
                /*background:-webkit-gradient(linear, left 0, right 0, color-stop(0, #414141), color-stop(1,#8c8c8c));}*/
            .content{width: 76%;min-height: 550px;float:left;background-color:#8c8c8c;
            border-bottom-right-radius: 30px;}
            /*.footer{width: 100%;height: 100px;clear:both;background-color:pink;}*/
            .Pagination a:hover,.current{background-color: deepskyblue;border: 1px solid;color: #ffffff; }
            .Pagination{float: right;height: auto;_height: 45px; line-height: 20px;margin-right: 15px;_margin-right: 5px; color:#565656;margin-top: 10px;_margin-top: 20px; clear:both;}
            .Pagination a,.Pagination span, a.num,.current,.next,.prev,.first,.end{ font-size: 14px;text-decoration: none;display: block;float: left;color: #565656;border: 1px solid #ccc;height: 34px;line-height: 34px;margin: 0 2px;width: 34px;text-align: center;}
        </style>
        <script src="/WorkerMS/Public/js/jquery-1.9.1.min.js"></script>
    </head>
    <body>
        <div class="outside">
            <div class="header">
                <div class="title">
                    <a href="<?php echo U('Index/index');?>"><p>员工效绩考核系统</p></a>
                    <p class="user">账号：<?php echo ($_SESSION['member']['name']); ?></p>
                </div>
            </div>
            <div class="nav"></div>
            <div class="container">
                <div class="aside">
                    <ul>
                        <li><a href="javascript:void(0)" onclick="javascript:window.history.go(-1);">返回</a></li>
                        <li><a href="<?php echo U('Exam/him', array('id' => $_SESSION['member']['id']));?>">自我评分</a></li>
                        <li><a href="<?php echo U('Exam/them');?>">给他打分</a></li>
                        <li><a href="<?php echo U('Index/department');?>">部门管理</a></li>
                        <li><a href="<?php echo U('Index/office');?>">职位管理</a></li>
                        <li><a href="<?php echo U('Exam/project');?>">考核项目</a></li>
                        <li><a href="<?php echo U('Member/member');?>">员工管理</a></li>
                        <li><a href="<?php echo U('Member/grade');?>">评分排行</a></li>
                        <li><a href="<?php echo U('Login/logout');?>">登出</a></li>
                    </ul>
                </div>
                <div class="content">
                    <div>
    <div class="a-nav">
        <?php if(is_array($departments)): $i = 0; $__LIST__ = $departments;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a href="<?php echo U('Member/grade', array('department' => $vo['name']));?>"><p><?php echo ($vo["name"]); ?></p></a><?php endforeach; endif; else: echo "" ;endif; ?>
    </div>
    <table style="padding-left: 30px;">
        <tr>
            <td class="width80">部门</td>
            <td class="width80">时间</td>
            <td class="width80">员工</td>
            <td style="min-width: 180px;">得分详情</td>
            <td class="width80">得分</td>
        </tr>
        <?php if(is_array($grades)): $i = 0; $__LIST__ = $grades;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                <td><?php echo ($vo["department"]); ?></td>
                <td><?php echo ($vo["time"]); ?></td>
                <td><?php echo ($vo["member"]); ?></td>
                <td><?php echo ($vo["msg"]); ?></td>
                <td><?php echo ($vo["grade"]); ?></td>
            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
    </table>
</div>
                </div>
            </div>
            <!--<div class="footer"></div>-->
        </div>
    </body>
</html>