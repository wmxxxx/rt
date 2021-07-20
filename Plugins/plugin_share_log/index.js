$(document).ready(function(){
	var app = queryString("app");
	var fun = queryString("fun");
	var code = queryString("code");
	var user = queryString("user");

	layui.laydate.render({
		elem:'#time',
		value:new Date()
	});
	layui.form.render();
	$("#header .layui-btn-normal").off("click").on("click",function(){
		getTaskList();
	}).click();
	function getTaskList(){
		layui.table.render({
			elem:'#table',
			height:'full-100',
			url:"php/getTaskList.php",
			method:"post",
			where:{time:$("#time").val(),app:app,name:$("#name").val(),res:$("#result .layui-this").attr("lay-value")},
			page:{
				limit:'50',
				limits:[50,200,500,99999]
			},
			toolbar:true,
			defaultToolbar: [{
				title:'删除',
				layEvent:'del',
				icon:'layui-icon-delete'
			}],
			cols:[[
				{field:'',type:'checkbox',unresize:true},
				{field:'F_EntityName',title:'设备名称',align:'center',unresize:true},
				{field:'F_type',title:'任务类型',align:'center',unresize:true,toolbar:'#type'},
				{field:'F_command_name',title:'执行命令',align:'center',unresize:true},
				{field:'',title:'执行时间',align:'center',unresize:true,toolbar:'#send'},
				{field:'',title:'执行结果',align:'center',unresize:true,toolbar:'#code'},
				{field:'F_code',title:'结果标识',align:'center',unresize:true},
				{field:'',title:'操作',align:'center',unresize:true,width:200,toolbar:'#sub_oper'}
			]]
		});
		layui.table.on('toolbar()',function(obj){
			if(obj.event === 'del'){
				var check = [];
				$.each(layui.table.checkStatus('table').data,(i,k) => check.push(k.F_id));
				if(check.length){
					layer.confirm('是否删除选中记录？',{
						btn: ['取消','确定'] 
					},function(index){
						layer.close(index);
					},function(index){
						$.post("php/delSubTask.php",{id:check},function(){
							getTaskList();
						},"json");
					});
				}else{
					layer.msg("请选择需要删除的记录");
				}
			}
		});
		layui.table.on('tool(main)',function(obj){
			if(obj.event === 'info'){
				layer.open({
					type:1,
					title:"命令信息",
					area:['500px','500px'],
					resize:false,
					id:'info_open',
					content: '',
					success:function(){
						layui.laytpl(info_tpl.innerHTML).render(obj.data,function(html){
							document.getElementById('info_open').innerHTML = html;
							layui.code({
								about:false,
								height:"168px"
							});
						});
					}
				});
			}else if(obj.event === 'node'){
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