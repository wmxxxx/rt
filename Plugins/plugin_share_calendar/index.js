$(document).ready(function(){
	var app = queryString("app");
	var fun = queryString("fun");
	var code = queryString("code");
	var user = queryString("user");
	var device = [];
	var id = 1;
	$.get("/API/Context/FunToDevice/?fun_code="+fun,function(res){
		res.forEach(ele => device.push(ele.F_DeviceTypeID));
		zTree = new TUI.TreePanel("ztree",{
			plugin:code,
			fun:fun,
			app:app,
			expand:true,
			device_node:true,
			device_id:device.join(","),
			callback:function(node){
				id = node.nTplId ? node.id : "";
			}
		});
		layui.laydate.render({
			elem:'#month',
			value:new Date(),
			type:'month'
		});
		$(".header .layui-btn-normal").off("click").on("click",function(){
			loadCalendar();
			if(id == 1)id = "";
		}).click();
	},"json");
	function loadCalendar(){
		if(id == "")layer.msg("请选择设备");   
		if(id != "")$(".main").html('<iframe src="calendar.html?id='+id+'&month='+$("#month").val()+'" width="100%" height="100%" frameborder="0" scrolling="no"></iframe>');
	}
	// 获取url参数
	function queryString(key){
		var reg = new RegExp("(^|&)"+key+"=([^&]*)(&|$)","i");
		var r = window.location.search.substr(1).match(reg);
		if(r!=null)return unescape(r[2]);return null;
	}
});