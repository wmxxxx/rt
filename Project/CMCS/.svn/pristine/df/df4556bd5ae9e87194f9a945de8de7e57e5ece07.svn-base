$(document).ready(function(){
	var app = queryString("app");
	var fun = queryString("fun");
	var code = queryString("code");
	var user = queryString("user");

	$(".base").off("click").on("click",function(){
		layer.open({
			type:1,
			title:"基础配置",
			area:['calc(100% - 20px)','calc(100% - 20px)'],
			resize:false,
			move:false,
			id:'main',
			content: '',
			success:function(){
				$.post("php/getBaseConfig.php",function(res){
					layui.laytpl(base_tpl.innerHTML).render(res,function(html){
						document.getElementById('main').innerHTML = html;
						layui.form.render();
						layui.form.on('select(keySel)',function(d){
						   save();
						});
					});
				},"json");
			}
		});
	});
	$(".monitor").off("click").on("click",function(){
		layer.open({
			type:1,
			title:"基础配置",
			area:['calc(100% - 20px)','calc(100% - 20px)'],
			resize:false,
			move:false,
			id:'main',
			content: '',
			success:function(){
				$("#main").html(
					'<iframe src="ext/monitor.php" width="100%" height="100%" frameborder="0" scrolling="no"></iframe>'
				);
			}
		});
	});
	$(".apiReady").off("click").on("click",function(){
		layer.open({
			type:1,
			title:"基础配置",
			area:['calc(100% - 20px)','calc(100% - 20px)'],
			resize:false,
			move:false,
			id:'main',
			content: '',
			success:function(){
				$("#main").html(
					'<iframe src="ext/interface.php?app='+app+'&fun='+fun+'&code='+code+'&user='+user+'" width="100%" height="100%" frameborder="0" scrolling="no"></iframe>'
				);
			}
		});
	});
	$(".apiGo").off("click").on("click",function(){
		layer.open({
			type:1,
			title:"基础配置",
			area:['calc(100% - 20px)','calc(100% - 20px)'],
			resize:false,
			move:false,
			id:'main',
			content: '',
			success:function(){
				$("#main").html(
					'<iframe src="ext/impl.php?app='+app+'&fun='+fun+'&code='+code+'&user='+user+'" width="100%" height="100%" frameborder="0" scrolling="no"></iframe>'
				);
			}
		});
	});
	// 获取url参数
	function queryString(key){
		var reg = new RegExp("(^|&)"+key+"=([^&]*)(&|$)","i");
		var r = window.location.search.substr(1).match(reg);
		if(r!=null)return unescape(r[2]);return null;
	}
});
function save(){
	var data = {
		ip:$("#main .layui-input:eq(0)").val(),
		num:$("#main .layui-input:eq(1)").val(),
		clear:$("#main select").val()
	}
	$.post("php/getBaseConfig.php",{data:data},function(res){},"json");
}