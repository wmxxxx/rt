$(document).ready(function(){
	var data = {
		color:["#159316","#fa5d3a","#faaf3a","#00b9b4","#ccc"],
		template:[],
		command:[],
		config:{}
	};
	var access = {
		setting:{
			check:{
				enable:true,
				chkStyle:"checkbox",
				chkboxType:{"Y":"","N":""}
			},
			data:{
				simpleData:{
					enable:true,
					idKey:'F_EntityID',
					pIdKey:'F_ParentID',
					rootPId:0
				}
			},
			view:{showIcon:false}
		},
		active:0,
		gsel:0,
		gtree:'',
		dsel:0,
		dtree:''
	}
	// 获取设备模板
	$.get("/API/Dictionary/DeviceTpl/?tpl_type=2",function(result){
		result.forEach(tpl => {
			$.post("/Php/project/getValueList.php/",{code:tpl.F_TemplateCode},function(res){
				var val = [{
					F_ValueLabel: "illegal",
					F_ValueName: "违规状态",
					F_KV: ""
				},{
					F_ValueLabel: "online",
					F_ValueName: "通讯状态",
					F_KV: "0:离线-1:在线"
				}];
				res.forEach(tpl_val => {
					if(tpl_val.F_IsDisplay)val.push(tpl_val);
				});
				tpl.value = val;
				data.template[tpl.F_TemplateLabel] = tpl;
			},"json");
		});
	},"json");
	// 加载系统列表
	$.get("../php/monitor/getSystemList.php",function(result){
		var system = '';
		result.system.forEach(sys => {
			system += '<li app='+sys.F_ProjectNo+'>'+sys.F_ProjectName+'</li>';
		});
		data.command = result.command;
		// 点击获取该系统配置
		$(".sidebar ul").html(system).find("li").off("click").on("click",function(){
			$(".sidebar ul li").addClass("active").not($(this)).removeClass("active");
			$.post("/Php/lib/plugin/share/getConfig.php",{app:$(this).attr("app")},function(res){
				if(res){
					data.config = res;
				}else{
					data.config = {
						group:[],
						node:[],
						device:[]
					}
				}
				loadConfig();
			},"json");
		});
	},"json");
	// 加载系统配置
	function loadConfig(){
		// 分组视图配置
		layui.laytpl(group_tpl.innerHTML).render(data,function(html){
			document.getElementById('group_main').innerHTML = html;
			$(".group_pop").off("click").on("click",function(){
				GroupPopup();
			});
			$(".access_pop").off("click").on("click",function(){
				AccessPopup();
			});
			$(".config_pop").off("click").on("click",function(){
				$.get("../php/monitor/delConfig.php?app="+$(".sidebar ul .active").attr("app"),function(res){
					data.config = {
						group:[],
						node:[],
						device:[]
					};
					layer.msg("配置已清除");
					loadConfig();
				},"json");
			});
		});
		// 节点视图配置
		layui.laytpl(node_tpl.innerHTML).render(data,function(html){
			document.getElementById('node_main').innerHTML = html;
			$(".node_pop").off("click").on("click",function(){
				NodePopup();
			});
		});
		// 设备视图配置
		layui.laytpl(device_tpl.innerHTML).render(data,function(html){
			document.getElementById('device_main').innerHTML = html;
			$(".device_pop").off("click").on("click",function(){
				DevicePopup();
			});
		});
	}
	// 外接设备弹出框
	function AccessPopup(){
		layui.layer.open({
			type:1,
			title:'外接设备关联',
			area:['auto','500px'],
			resize:false,
			id:'access_open',
			btn:['关闭','保存'],
			content:'<div style="width:550px;"></div>',
			success:function(){
				$.get("../php/monitor/getModelList.php",function(res){
					layui.laytpl(access_open_tpl.innerHTML).render(res,function(html){
						document.getElementById('access_open').innerHTML = html;
						layui.form.render('select');
						layui.form.on('select(groupSel)', function(data){
							access.gsel = data.value;
							access.active = 0;
							var tree = getTree();
							var setting = {
								data:{
									simpleData:{
										enable:true,
										idKey:'F_EntityID',
										pIdKey:'F_ParentID',
										rootPId:0
									}
								},
								view:{showIcon: false},
								callback:{onClick:onClick}
							};
							access.gtree = $.fn.zTree.init($("#groupTree"),setting,tree);
						});
						layui.form.on('select(deviceSel)', function(data){
							access.dsel = data.value;
							var tree = getTree();
							access.dtree = $.fn.zTree.init($("#deviceTree"),access.setting,tree);
						});
					});
				},"json");
			},
			btn2:function(){
				if(access.dtree != '' && access.gtree != ''){
					var node = access.dtree.getCheckedNodes(true);
					var nodes = [];
					node.forEach(ele => {
						nodes.push(ele.F_EntityID);
					});
					if(access.active == 0){
						layer.msg("请选择管理节点");
					}else{
						$.post("../php/monitor/accessRel.php",{
							app:$(".sidebar ul .active").attr("app"),
							gtree:access.gsel,
							gid:access.active,
							dtree:access.dsel,
							nodes:nodes
						},function(res){
							layer.msg("已保存关联");
						},"json");
					}
				}else{
					layer.msg("请选择管理树或设备树");
				}
				return false;
			}
		});
	}
	function getTree(){
		var result;
		$.ajax({
			type: 'post',
			url: "../php/monitor/getTree.php",
			data:{
				app:$(".sidebar ul .active").attr("app"),
				gtree:access.gsel,
				gid:access.active,
				dtree:access.dsel
			},
			dataType: "json",
			async: false,
			success: function (res) {
				res.forEach(ele => {
					ele.open = true;
				});
				result = res;
			}
		});
		return result;
	}
	function onClick(event,treeId,treeNode,clickFlag){
		access.active = treeNode.F_EntityID;
		var tree = getTree();
		access.dtree = $.fn.zTree.init($("#deviceTree"),access.setting,tree);
	}
	// 分组视图弹出框
	function GroupPopup(){
		layui.layer.open({
			type:1,
			title:'分组视图配置',
			area:['auto','500px'],
			resize:false,
			id:'group_open',
			btn:['添加','保存'],
			content:'<div style="width:400px;"></div>',
			success:function(){
				layui.laytpl(group_open_tpl.innerHTML).render(data,function(html){
					document.getElementById('group_open').innerHTML = html;
					layui.form.render('select');
					GroupOper();
				});
			},
			yes:function(){
				layui.laytpl(group_add.innerHTML).render(data,function(html){
					$("#group_open").append(html);
					layui.form.render('select');
					GroupOper();
				});
			},
			btn2:function(){
				var result = [];
				$.each($("#group_open .layui-form"),function(inx,key){
					var check = {};
					$.each($(key).children(".layui-form-item").not(".check"),function(i,k){
						var tplabel = $(k).find(".oper_temp .layui-this").attr("lay-value");
						if(tplabel){
							if(!check[tplabel])check[tplabel] = [];
							check[tplabel].push({
								attr:$(k).find(".oper_key .layui-this").attr("lay-value"),
								check:$(k).find(".oper_term .layui-this").attr("lay-value"),
								judge:$(k).find(".oper_val .layui-this").attr("lay-value") ? $(k).find(".oper_val .layui-this").attr("lay-value") : $(k).find(".oper_val .layui-input").val(),
								pos:$(k).find(".oper_pos .layui-this").attr("lay-value")
							});
						}
					});
					result.push({
						text:$(key).find(".check .layui-input:eq(0)").val(),
						unit:$(key).find(".check .layui-input:eq(1)").val(),
						color:$(key).find(".check .layui-input:eq(2)").val(),
						tpl:check
					});
				});
				data.config.group = result;
				save();
			}
		});
	}
	// 分组视图操作
	function GroupOper(){
		$.each($(".colorpicker"),function(i,k){
			$(k).attr("id","colorPic"+i);
			layui.colorpicker.render({
				elem:"#colorPic"+i,
				color:$(k).parent().prev().find("input").val(),
				predefine:true,
				colors:data.color,
				done:function(color){
				   $(k).parent().prev().find("input").val(color);
				}
			});
		});
		layui.laytpl(group_oper.innerHTML).render(data,function(html){
			$(".layui-icon-add-circle").off("click").on("click",function(){
				$(this).parents(".add_tpl").append(html);
				layui.form.render('select');
				change();
			});
		});
		change();
		function change(){
			layui.form.on('select(tempSel)',function(d){
				var kopt = '<option value="">模板参数</option>';
				data.template[d.value].value.forEach(tpl_value => {
					kopt += '<option value='+tpl_value.F_ValueLabel+' kv='+tpl_value.F_KV+'>'+tpl_value.F_ValueName+'</option>';
				});
				$(this).parents(".layui-form-item").find(".oper_key select").html(kopt);
				$(this).parents(".layui-form-item").find(".oper_val").html('<input type="text" placeholder="判定值" autocomplete="off" class="layui-input">');
				layui.form.render('select');
			});
			layui.form.on('select(keySel)',function(d){
				var kv;
				$.each($(".oper_key select option"),function(i,k){
					if($(k).attr("kv") && $(k).attr("value") == d.value){
						kv = $(k).attr("kv");
						return false;
					}
				});
				if(kv){
					var vopt = '';
					kv.split("-").forEach(ele => {
						vopt += '<option value='+ele.split(':')[0]+'>'+ele.split(':')[1]+'</option>'
					});
					$(this).parents(".layui-form-item").find(".oper_val").html('<select>'+vopt+'</select>');
					layui.form.render('select');
				}else{
					$(this).parents(".layui-form-item").find(".oper_val").html('<input type="text" placeholder="判定值" autocomplete="off" class="layui-input">');
				}
			});
		}
	}
	// 节点视图弹出框
	function NodePopup(){
		layui.layer.open({
			type:1,
			title:'运行状态设置',
			area:['auto','500px'],
			resize:false,
			id:'node_open',
			btn:['添加','保存'],
			content:'<div style="width:550px;"></div>',
			success:function(){
				layui.laytpl(node_open_tpl.innerHTML).render(data,function(html){
					document.getElementById('node_open').innerHTML = html;
					layui.form.render();
					NodeOper();
				});
			},
			yes:function(){
				layui.laytpl(node_add.innerHTML).render(data,function(html){
					$("#node_open").append(html);
					layui.form.render();
					NodeOper();
				});
			},
			btn2:function(){
				var result = [];
				$.each($("#node_open .layui-form"),function(index,node){
					var res = {
						name:$(node).find(".tab_name").val(),
						tips:[],
						tpl:{},
						batch:[],
						screen:$(node).find(".layui-form-checkbox:eq(0)").hasClass("layui-form-checked") ? 1 : 0,
						control:$(node).find(".layui-form-checkbox:eq(1)").hasClass("layui-form-checked") ? 1 : 0,
						hide:$(node).find(".layui-form-checkbox:eq(2)").hasClass("layui-form-checked") ? 1 : 0,
						group:$(node).find(".layui-form-checkbox:eq(3)").hasClass("layui-form-checked") ? 1 : 0
					}
					$.each($(node).find(".toState .add_tpl"),function(inx,key){
						var check = {};
						$.each($(key).children(".layui-form-item").not(".check"),function(i,k){
							var tplabel = $(k).find(".oper_temp .layui-this").attr("lay-value");
							if(tplabel){
								if(!check[tplabel])check[tplabel] = [];
								check[tplabel].push({
									attr:$(k).find(".oper_key .layui-this").attr("lay-value"),
									check:$(k).find(".oper_term .layui-this").attr("lay-value"),
									judge:$(k).find(".oper_val .layui-this").attr("lay-value") ? $(k).find(".oper_val .layui-this").attr("lay-value") : $(k).find(".oper_val .layui-input").val(),
									pos:$(k).find(".oper_pos .layui-this").attr("lay-value")
								});
							}
						});
						res.tips.push({
							text:$(key).find(".check .layui-input:eq(0)").val(),
							color:$(key).find(".check .layui-input:eq(1)").val(),
							tpl:check
						});
					});
					$.each($(node).find(".toDevice .layui-form-item"),function(inx,key){
						var tplabel = $(key).find(".oper_temp .layui-this").attr("lay-value");
						if(tplabel){
							res.tpl[tplabel] = {
								attr1:$(key).find(".oper_key:eq(0) .layui-this").attr("lay-value"),
								attr2:$(key).find(".oper_key:eq(1) .layui-this").attr("lay-value"),
								attr3:$(key).find(".oper_key:eq(2) .layui-this").attr("lay-value"),
								icon:$(key).find(".oper_icon .layui-input").val()
							}
						}
					});
					$.each($(node).find(".toBatch .layui-form-checked"),function(inx,key){
						res.batch.push($(key).prev().val());
					});
					result.push(res);
				});
				data.config.node = result;
				save();
			}
		});
	}
	// 节点视图操作
	function NodeOper(){
		$("#node_open .addState").off("click").on("click",function(){
			var that = $(this);
			layui.laytpl(node_add_state.innerHTML).render(data,function(htm){
				that.parents(".layui-form").find(".toState").append(htm);
				layui.form.render();
				GroupOper();
			});
		});
		GroupOper();
		$("#node_open .addDevice").off("click").on("click",function(){
			var that = $(this);
			layui.laytpl(node_add_device.innerHTML).render(data,function(htm){
				that.parents(".layui-form").find(".toDevice").append(htm);
				layui.form.render();
				change();
			});
		});
		change();
		function change(){
			layui.form.on('select(tplSel)',function(d){
				var kopt = '';
				data.template[d.value].value.forEach(ele => {
					kopt += '<option value='+ele.F_ValueLabel+' kv='+ele.F_KV+'>'+ele.F_ValueName+'</option>';
				});
				d.othis.parents(".layui-form-item").find(".oper_key select").html(kopt);
				layui.form.render('select');
			});
		}
	}
	// 设备视图弹出框
	function DevicePopup(){
		layui.layer.open({
			type:1,
			title:'设备面板设置',
			area:['auto','500px'],
			resize:false,
			id:'device_open',
			btn:['添加','保存'],
			content:'<div style="width:600px;"></div>',
			success:function(){
				layui.laytpl(device_open_tpl.innerHTML).render(data,function(html){
					document.getElementById('device_open').innerHTML = html;
					layui.form.render();
					DeviceOper();
				});
			},
			yes:function(){
				layui.laytpl(device_add.innerHTML).render(data,function(html){
					$("#device_open").append(html);
					layui.form.render();
					DeviceOper();
				});
			},
			btn2:function(){
				var result = {};
				$.each($("#device_open .layui-form"),function(inx,key){
					var that = $(key).children(".layui-form-item");
					var tplabel = that.eq(1).find(".oper_temp .layui-this").attr("lay-value");
					if(tplabel){
						var comList = [];
						$.each($(key).children(".layui-form-item.com"),function(i,k){
							var param = {
								icon:$(k).find(".oper_icon input").val(),
								com:[]
							}
							$.each($(k).find(".oper_temp"),function(i,t){
								if($(t).find(".layui-this").attr("lay-value")){
									param.com.push($(t).find(".layui-this").attr("lay-value"));
									if(that.eq(1).find(".oper_class .layui-this").attr("lay-value") == "p3" && i == 0)param.icon = $(t).find(".layui-this").html();
								}
							});
							comList.push(param);
						});
						result[tplabel] = {
							show:that.eq(0).find(".layui-form-checkbox:eq(0)").hasClass("layui-form-checked") ? 1 : 0,
							chart:that.eq(0).find(".layui-form-checkbox:eq(1)").hasClass("layui-form-checked") ? 1 : 0,
							calendar:that.eq(0).find(".layui-form-checkbox:eq(2)").hasClass("layui-form-checked") ? 1 : 0,
							strategy:that.eq(0).find(".layui-form-checkbox:eq(3)").hasClass("layui-form-checked") ? 1 : 0,
							class:that.eq(1).find(".oper_class .layui-this").attr("lay-value"),
							ext:that.eq(1).find(".oper_ext input").val(),
							attr1:that.eq(2).find(".oper_key:eq(0) .layui-this").attr("lay-value"),
							attr2:that.eq(2).find(".oper_key:eq(1) .layui-this").attr("lay-value"),
							attr3:that.eq(2).find(".oper_key:eq(2) .layui-this").attr("lay-value"),
							attr4:that.eq(2).find(".oper_key:eq(3) .layui-this").attr("lay-value"),
							attr5:that.eq(2).find(".oper_key:eq(4) .layui-this").attr("lay-value"),
							btn:comList
						}
					}
				});
				data.config.device = result;
				save();
			}
		});
	}
	// 设备视图操作
	function DeviceOper(){
		var porary = data;
		layui.form.on('select(tempSel)',function(d){
			kopt = '';
			data.template[d.value].value.forEach(ele => {
				kopt += '<option value='+ele.F_ValueLabel+' kv='+ele.F_KV+'>'+ele.F_ValueName+'</option>';
			});
			porary.opt = kopt;
			$(this).parents(".layui-form").find(".oper_key select").html('<option value="0">不显示</option>'+kopt);
			$(this).parents(".layui-form").find(".oper_rel select").html('<option value="">关联参数</option>'+kopt);
			layui.form.render('select');
		});
		layui.form.on('select(panelSel)',function(d){
			if(d.value == "p3"){
				$(this).parents(".layui-form").find(".attr").hide();
			}else{
				$(this).parents(".layui-form").find(".attr").show();
			}
		});
		$("#device_open .add_btn .layui-icon-add-circle").off("click").on("click",function(){
			var that = $(this);
			layui.laytpl(device_oper.innerHTML).render(porary,function(html){
				that.parents(".layui-form").append(html);
				layui.form.render('select');
				change();
			});
		});
		change();
		function change(){
			$("#device_open .add_com .layui-icon-add-circle").off("click").on("click",function(){
				var that = $(this);
				layui.laytpl(device_com.innerHTML).render(porary,function(htm){
					that.parents(".com").find(".comlist").append(htm);
					layui.form.render('select');
				});
			});
		}
	}
	// 保存配置
	function save(){
		$.post("../php/monitor/setConfig.php",{app:$(".sidebar ul .active").attr("app"),data:JSON.stringify(data.config)},function(result){
			layer.msg(result.errCode ? "保存成功" : "保存失败");
			loadConfig();
		},"json");
	}
});