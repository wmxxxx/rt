$(document).ready(function(){
	var app = queryString("app");
	var fun = queryString("fun");
	var code = queryString("code");
	var user = queryString("user");
	layui.laydate.render({
		elem: '#time',
		type: 'datetime',
		range: true,
		value: new Date().Format("yyyy-MM-dd")+' 00:00:00 - '+new Date().Format("yyyy-MM-dd")+' 23:59:59'
	});
	layui.form.render();
	$("#header .layui-btn-normal").off("click").on("click",function(){
		getInfo();
	}).click();
	function getInfo(){
		layui.table.render({
			elem:'#table',
			height:'full-100',
			url:"php/getInfo.php",
			method:"post",
			where:{time:$("#time").val(),app:app,name:$("#name").val(),res:$("#result .layui-this").attr("lay-value"),rep:$("#repeat .layui-this").attr("lay-value")},
			page:{
				limit:'50',
				limits:[50,200,500,99999]
			},
			toolbar:true,
			defaultToolbar:['exports','print'],
			title:'违规信息_'+new Date().toJSON(),
			cols:[[
				{field:'F_EntityName',title:'设备名称',align:'center',unresize:true},
				{field:'F_type',title:'违规类型',align:'center',unresize:true,toolbar:'#type'},
				{field:'F_msg',title:'违规内容',align:'center',unresize:true},
				{field:'F_time',title:'违规时间',align:'center',unresize:true},
				{field:'',title:'操作',align:'center',unresize:true,width:100,toolbar:'#sub_oper'}
			]]
		});
		layui.table.on('tool(main)',function(obj){
			if(obj.event === 'node'){
				layer.open({
					type:1,
					title:"设备信息",
					area:['calc(100% - 20px)','calc(100% - 20px)'],
					resize:false,
					move:false,
					id:'node_open',
					content: '',
					success:function(){
						$("#node_open").html('<iframe src="/Plugins/plugin_share_monitor/device/index.html?id='+obj.data.F_node_id+'&app='+app+'" width="100%" height="100%" frameborder="0" scrolling="no"></iframe>')
					}
				});
			}
		});
	}
	// 获取url参数
	function queryString(key){
		var reg = new RegExp("(^|&)"+key+"=([^&]*)(&|$)","i");
		var r = window.location.search.substr(1).match(reg);
		if(r!=null)return unescape(r[2]);return null;
	}
});