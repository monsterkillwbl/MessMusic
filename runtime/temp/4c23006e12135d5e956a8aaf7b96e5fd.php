<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:57:"E:\DongApi\public/../application/api\view\demo\index.html";i:1521084826;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>API接口在线DEMO</title>
    <meta name="robots" content="noindex,nofollow" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
	<!-- 新 Bootstrap 核心 CSS 文件 -->
	<link href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
	<!-- jQuery文件。务必在bootstrap.min.js 之前引入 -->
	<script src="https://cdn.bootcss.com/jquery/2.1.1/jquery.min.js"></script>
	 
	<!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
	<script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<style>
		.container{margin-top:10px; }
		.btn{margin-right:15px;}
		textarea{min-height:150px; }
	</style>
</head>
<body>
<div class="container">
	<div class="row clearfix">
		<div class="col-md-12 column">
			<div class="jumbotron">
				<h3 style="color:green; ">
					API接口在线DEMO
				</h3>
				<p>
					注意:这里为了可视化，所有参数都是经过转字符串后的值，并不是实际上的JSON对象。<br/>
					如需使用返回结果，请自行使用JSON.parse()转换为对象后再使用。
				</p>
			</div>
			<form role="form">
				<div class="form-group">
					 <label for="exampleInputEmail1">提交地址</label><input name="url" type="text" value="https://<?php echo $_SERVER['HTTP_HOST']; ?>/api/index/index" id="url" class="form-control"/>
				</div>
				<div class="form-group">
					 <label for="exampleInputEmail1">提交参数(填写转字符串后的JSON对象)</label><textarea name="param" id="param" class="form-control">{"TransCode":"020111","OpenId":"Test","Body":{"SongListId":"141998290"}}</textarea>
				</div>
				<div class="form-group">
					<button type="button" class="btn btn-lg btn-success" id="submit">发射</button>
				</div>
				<div class="form-group">
					 <label for="exampleInputPassword1">结果(返回转字符串后的JSON对象)</label><textarea name="result" id="result" class="form-control"></textarea>
				</div>
			</form>
		</div>
	</div>
</div>
<script type="text/javascript">
$("#submit").click(function(){
	var param = $("#param").val();
	var url = $("#url").val();
	if(!param){
		alert('提交参数未填写');
	}else{
		$.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: JSON.parse(param),
            success: function(result){
				$("#result").val(JSON.stringify(result));
                return false;
            },
            error: function(request) {
                alert("Connection error");
                return false;
            }
        });
	}
});
</script>
</body>
</html>