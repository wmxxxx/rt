$(document).ready(function(){
	var app = queryString("app");
	var fun = queryString("fun");
	var code = queryString("code");
	var user = queryString("user");
	var param = {
		group:{},
		cycle:{},
		action:{},
		user:{},
		list:[],
		edit:[]
	};
	var data = {
		group:'',
		cycle:'',
		action:''
	}
	$.post("php/init.php",{app:app},function(res){
		res.group.forEach(ele => param.group[ele.F_id] = ele);
		res.cycle.forEach(ele => param.cycle[ele.F_id] = ele);
		res.action.forEach(ele => param.action[ele.F_id] = ele);
		res.user.forEach(ele => param.user[ele.F_UserCode] = ele);
		layui.laytpl(header_tpl.innerHTML).render(param,function(html){
			document.getElementById('header').innerHTML = html;
			layui.form.render();
			layui.form.on('select(group)',ele => data.group = ele.value);
			layui.form.on('select(cycle)',ele => data.cycle = ele.value);
			layui.form.on('select(action)',ele => data.action = ele.value);
			$("#header .layui-btn-normal").off("click").on("click",function(){
				query();
			});
			$("#header .layui-btn-action").off("click").on("click",function(){
				param.edit = {};
				setPopup();
			});
			query();
		});
	},"json");
	function query(){
		$.post("php/getStrategyList.php",{data:data,app:app},function(res){
			res.forEach(ele => {
				ele.F_group_name = '';
				ele.F_group_id.split(",").forEach(gele => ele.F_group_name += param.group[gele].F_name+"<br>");
				ele.F_cycle_name = param.cycle[ele.F_cycle_id].F_name;
				ele.F_action_name = param.action[ele.F_action_id].F_name;
				ele.F_user_name = '';
				if(ele.F_user_id)ele.F_user_id.split(",").forEach(uele => ele.F_user_name += param.user[uele].F_UserName+"<br>");
			});
			param.list = res;
			layui.table.render({
				elem:'#table',
				height:'full-100',
				page:true,
				limit:'20',
				limits:[20,50,100,200],
				data:param.list,
				cols:[[
					{field:'F_name',title:'策略名称',align:'center',unresize:true},
					{field:'F_group_name',title:'分组名称',align:'center',unresize:true},
					{field:'num',title:'关联设备',align:'center',unresize:true},
					{field:'F_cycle_name',title:'周期模型',align:'center',unresize:true},
					{field:'F_action_name',title:'动作模型',align:'center',unresize:true},
					{field:'F_open',title:'是否启用',align:'center',unresize:true,toolbar:'#open'},
					{field:'F_push',title:'消息推送',align:'center',unresize:true,toolbar:'#push'},
					{field:'F_user_name',title:'推送人员',align:'center',unresize:true},
					{field:'',title:'操作',align:'center',unresize:true,width:120,toolbar:'#oper'}
				]]
			});
			layui.table.on('tool(main)',function(obj){
				if(obj.event === 'del'){
					layer.confirm('是否删除该策略',{
						btn:['取消','确定']
					},function(index){
						layer.close(index);
					},function(index){
						$.post("php/delStrategy.php",{id:obj.data.F_id},function(){
							layer.close(index);
							query();
						},"json");
					});
				}else if(obj.event === 'edit'){
					param.edit = obj.data;
					setPopup();
				}
			});
		},"json");
	}
	function setPopup(){
		layer.open({
			type:1,
			title:"添加策略",
			area:['700px','550px'],
			resize:false,
			id:'set_open',
			skin:'layui-form',
			btn:['取消','保存'],
			content:'',
			success:function(){
				layui.laytpl(set_tpl.innerHTML).render(param,function(html){
					document.getElementById('set_open').innerHTML = html;
					layui.form.render();
					layui.form.on('checkbox(push)',function(r){
						r.elem.checked ? $("#set_open .block").addClass("push") : $("#set_open .block").removeClass("push");
					});
				});
			},
			btn2:function(){
				var set = {
					name:$("#set_open .name").val(),
					open:$("#set_open .isopen .layui-form-checked").length,
					push:$("#set_open .ispush .layui-form-checked").length,
					user:[],
					group:[],
					cycle:$("#set_open .cycle .layui-form-radioed").prev().val(),
					action:$("#set_open .action .layui-form-radioed").prev().val()
				}
				$.each($("#set_open .user li"),function(i,k){
					var ck = $(k).find(".layui-form-checked");
					if(ck.length)set.user.push(ck.prev().attr("name"));
				});
				$.each($("#set_open .group li"),function(i,k){
					var ck = $(k).find(".layui-form-checked");
					if(ck.length)set.group.push(ck.prev().attr("name"));
				});
				var msg = '';
				if(set.name == "")msg = "策略名称不能为空";
				else if(!set.cycle)msg =  "请选择周期模型";
				else if(!set.action)msg = "请选择动作模型";
				else if(!set.group.length)msg = "请勾选管理分组";
				else if(set.push && !set.user.length)msg = "请勾选推送人员";
				if(msg != ''){
					layer.msg(msg);
					return false;
				}else{
					$.post("php/setStrategy.php",{data:set,app:app,id:param.edit.F_id},function(){
						query();
					},"json");
				}
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