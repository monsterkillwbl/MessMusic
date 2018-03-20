<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:60:"E:\DongApi\public/../application/index\view\index\index.html";i:1521079159;}*/ ?>
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
		.center-block {
		  display: block;
		  margin-left: auto;
		  margin-right: auto;
		}
		.element {
		  .center-block();
		}
	</style>
</head>
<body>
<div class="container">
	<div class="center-block">
		<div id="aplayer" class="aplayer"></div>
	</div>
</div>

<script src="https://cdn.bootcss.com/aplayer/1.6.0/APlayer.min.js"></script>
<script type="text/javascript">
	var music = new Array();
	$.ajax({
	    type: "POST",
	    url: 'https://api.hibai.cn/api/index/index',
	    dataType: 'json',
	    data: {"TransCode":"020112","OpenId":"123456789","Body":{"SongListId":"141998290"}},
	    success: function(result){
			var ap = new APlayer({
			    element: document.getElementById('aplayer'),
			    narrow: false,
			    autoplay: true,
			    showlrc: 3,
			    mutex: true,
			    theme: '#e6d0b2',
			    mode: 'random',
			    preload: 'metadata',
			    listmaxheight: '800px',
			    music: result.Body
			});
	    }
	});
</script>
</body>
</html>