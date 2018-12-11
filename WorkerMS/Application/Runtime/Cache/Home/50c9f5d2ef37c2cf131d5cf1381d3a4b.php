<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<style>
		*{
			margin: 0;
			padding: 0;
			list-style: none;
			text-decoration: none;
			font-family:"pinghei","Helvetica Neue","Helvetica","STHeitiSC-Light","Arial","sans-serif","Microsoft Yahei","Tahoma";
			font-size:14px;
		}
		#main{
			background-color: #fff;
			opacity: 0.9;
			width: 500px;
			height: 320px;
			padding-top: 20px;
			position: absolute;
			top:0;right: 0;bottom: 0;left: 0;
			margin: auto;
			border:1px solid #dcdcdc;
			border-radius: 10px;
			box-shadow: 0 0 10px #DCDCDC;
		}
		body{
			background-image: url('/WorkerMS/Public/img/bgc.jpg');
		}
		#logo{
			display: block;
			width: 140px;
			height:60px;
			margin: 0 auto;
		}
		form{
			width: 80%;
			height: 70%;
			margin: 15px auto;
			position: relative;
		}
		.login_div{
			width: 100%;
			height: 34px;
			line-height: 34px;
			margin: 35px 0;
		}
		.login_div span{
			display: block;
			float: left;
			height: 34px;
			line-height:34px;
			width: 20%;
			text-align: right;
			margin:0 5px;
		}
		.login_div input{
			height: 34px;
			line-height:34px;
			width: 70%;
			border:1px solid #1d7ad9;
			border-radius: 4px;
		}
		#btn_submit{
			display: block;
			width: 140px;
			height: 40px;
			line-height: 40px;
			text-align: center;
			border-radius: 5px;
			background: #1d7ad9;
			color:#fff;
			margin:50px auto;
		}
		#tip{
			display: block;
			position: absolute;
			left:90px;
			bottom:75px;
			width: 280px;
			height: 30px;
			line-height: 30px;
			color:red;
		}
		#main p{width: 100%;text-align: center;font-size: 30px;}
	</style>
</head>
<body>
	<div id="main">
		<!--<img src="/WorkerMS/Public/img/logo.jpg" id="logo">-->
		<p>登陆</p>
		<form action="<?php echo U('Login/login');?>" method="POST">
			<div class="login_div">
				<span>账号  ：</span>
				<input type="text" name="username">
			</div>
			<div class="login_div">
				<span>密码 ：</span>
				<input type="password" name="password">
			</div>
			<span id="tip"></span>
			<input type="submit" value="提交" id="btn_submit">
		</form>
	</div>
</body>
</html>