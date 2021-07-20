$(document).ready(function(){
	var app = queryString("app");
	var fun = queryString("fun");
	var code = queryString("code");
	var user = queryString("user");

	$(".layui-tab-content div").eq(0).html('<iframe src="plugin_share_model/index.html?fun='+fun+'&app='+app+'&code='+code+'&user='+user+'" width="100%" height="100%" frameborder="0" scrolling="no"></iframe>');
	$(".layui-tab-content div").eq(1).html('<iframe src="plugin_share_relation/index.html?fun='+fun+'&app='+app+'&code='+code+'&user='+user+'" width="100%" height="100%" frameborder="0" scrolling="no"></iframe>');
	$(".layui-tab-content div").eq(2).html('<iframe src="plugin_share_calendar/index.html?fun='+fun+'&app='+app+'&code='+code+'&user='+user+'" width="100%" height="100%" frameborder="0" scrolling="no"></iframe>');
	// 获取url参数
	function queryString(key){
		var reg = new RegExp("(^|&)"+key+"=([^&]*)(&|$)","i");
		var r = window.location.search.substr(1).match(reg);
		if(r!=null)return unescape(r[2]);return null;
	}
});