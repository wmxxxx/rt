$(document).ready(function(){
	var app = queryString("app");
	var fun = queryString("fun");
	var code = queryString("code");
	var user = queryString("user");
	layui.laydate.render({
		elem:'#time',
		value:new Date()
	});
	$("#header .layui-btn-normal").off("click").on("click",function(){
		getTaskList();
	}).click();
	function getTaskList(){
		layui.table.render({
			elem:'#table',
			height:'full-100',
			url:"php/getTaskList.php",
			method:"post",
			where:{time:$("#time").val(),app:app},
			page:{
				limit:'20',
				limits:[20,100,300]
			},
			cols:[[
				{field:'F_name',title:'任务名称',align:'center',unresize:true},
				{field:'F_type',title:'任务类型',align:'center',unresize:true,toolbar:'#type'},
				{field:'F_time',title:'执行时间',align:'center',unresize:true,width:180},
				{field:'all',title:'子任务总数',align:'center',unresize:true},
				{field:'success',title:'成功',align:'center',unresize:true},
				{field:'fail',title:'失败',align:'center',unresize:true},
				{field:'abnormal',title:'异常',align:'center',unresize:true},
				{field:'ignore',title:'忽略',align:'center',unresize:true},
				{field:'',title:'操作',align:'center',unresize:true,width:160,toolbar:'#oper'}
			]]
		});
		layui.table.on('tool(main)',function(obj){
			if(obj.event === 'del'){
				layer.confirm('是否删除该日志',{
					btn:['取消','确定']
				},function(index){
					layer.close(index);
				},function(index){
					$.post("php/delTask.php",{id:obj.data.F_id},function(){
						layer.close(index);
						getTaskList();
					},"json");
				});
			}else if(obj.event === 'define'){
				layer.open({
					type:1,
					title:"任务明细",
					area:['calc(100% - 20px)','calc(100% - 20px)'],
					resize:false,
					move:false,
					id:'sub_open',
					content: '<table id="sub_table" lay-filter="sub_main"></table>',
					success:function(){
						layui.table.render({
							elem:'#sub_table',
							height:'full-100',
							url:"php/getSubTaskList.php",
							method:"post",
							where:{time:$("#time").val(),app:app,id:obj.data.F_id},
							page:{
								limit:'50',
								limits:[50,200,500]
							},
							cols:[[
								{field:'F_EntityName',title:'设备名称',align:'center'},
								{field:'F_type',title:'任务类型',align:'center',unresize:true,toolbar:'#type'},
								{field:'F_command_name',title:'执行命令',align:'center',unresize:true},
								{field:'',title:'执行时间',align:'center',unresize:true,width:180,toolbar:'#send'},
								{field:'',title:'执行结果',align:'center',unresize:true,toolbar:'#code'},
								{field:'F_code',title:'结果标识',align:'center',unresize:true},
								{field:'',title:'操作',align:'center',unresize:true,width:120,toolbar:'#sub_oper'}
							]]
						});
						layui.table.on('tool(sub_main)',function(obj){
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
										$("#node_open").html('<iframe src="/Plugins/plugin_share_monitor/device/index.html?id='+obj.data.F_node_id+'&app='+app+'" width="100%" height="100%" frameborder="0" scrolling="no"></iframe>');
									}
								});
							}
						});
					}
				});
			}else if(obj.event === 'repeat'){
				layer.confirm('是否对失败设备重新下发命令',{
					btn:['取消','确定']
				},function(index){
					layer.close(index);
				},function(index){
					$.post("php/sendAgain.php",{id:obj.data.F_id,name:obj.data.F_name,app:app},function(){
						layer.close(index);
						getTaskList();
					},"json");
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