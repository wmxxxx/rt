$(document).ready(function(){
	var id = queryString("id");
	$.post("php/wxTaskInfo.php",{id:id},function(res){
		$(".main").empty();
		res.forEach(ele => {
			var code = ele.code == "0" ? '<span style="color:#00B9B4;">成功</span>' : '<span style="color:#FF5722;">失败</span>';
			var back = JSON.parse(ele.F_back);
			var msg = back.errMsg;
			if(back.cmdReturnData.Code)msg = back.cmdReturnData.Message;
			$(".main").append(
				'<div class="mui-card">'
				+'	<div class="mui-card-header">'+ele.F_EntityName+code+'</div>'
				+'	<div class="mui-card-content">'
				+'		<p>执行命令：'+ele.F_command_name+'</p>'
				+'		<p>执行时间：'+ele.F_time+'</p>'
				+'		<p>结果标识：'+ele.F_code+'</p>'
				+'		<p>标识说明：'+msg+'</p>'
				+'	</div>'
				+'</div>'
			);
		});
	},"json");
	// 获取url参数
	function queryString(key){
		var reg = new RegExp("(^|&)"+key+"=([^&]*)(&|$)","i");
		var r = window.location.search.substr(1).match(reg);
		if(r!=null)return unescape(r[2]);return null;
	}
});