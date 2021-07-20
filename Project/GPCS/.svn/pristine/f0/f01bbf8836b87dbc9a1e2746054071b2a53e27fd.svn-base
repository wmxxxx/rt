$(document).ready(function(){
	var app = queryString("app");
	var fun = queryString("fun");
	var code = queryString("code");
	var user = queryString("user");
	var param = {
		F_name:'',
		F_code:'',
		device:[],
		group:{},
		ztree:''
	}
	// 获取设备类型
	$.get("/API/Context/FunToDevice/?fun_code="+fun,function(res){
		res.forEach(ele => param.device.push(ele.F_DeviceTypeID));
	},"json");
	getGroup();
	// 添加分组
	$(".header .layui-btn").off("click").on("click",function(){
		layer.open({
			type:1,
			title:"添加分组",
			area:['400px','250px'],
			resize:false,
			id:'set_open',
			btn:['取消','保存'],
			content: '',
			success:function(){
				layui.laytpl(set_tpl.innerHTML).render(param,function(html){
					document.getElementById('set_open').innerHTML = html;
				});
			},
			btn2:function(){
				$.post("php/setGroup.php",{app:app,name:$("#set_open #name").val(),num:$("#set_open #num").val()},function(res){
					if(!res)layer.msg("创建分组失败");
					getGroup();
				},"json");
			}
		})
	});
	function getGroup(){
		// 获取分组列表
		$.post("php/getGroupList.php",{app:app},function(res){
			res.length ? $("#main").removeClass("x-nodata") : $("#main").addClass("x-nodata");
			res.forEach(ele => param.group[ele.F_id] = ele);
			layui.laytpl(group_tpl.innerHTML).render(res,function(html){
				document.getElementById('main').innerHTML = html;
			});
			// 修改
			$("#main .edit").off("click").on("click",function(){
				var that = $(this).parents(".group")[0].id;
				layer.open({
					type:1,
					title:"添加分组",
					area:['400px','250px'],
					resize:false,
					id:'set_open',
					btn:['取消','保存'],
					content:'',
					success:function(){
						layui.laytpl(set_tpl.innerHTML).render(param.group[that],function(html){
							document.getElementById('set_open').innerHTML = html;
						});
					},
					btn2:function(){
						$.post("php/setGroup.php",{app:app,name:$("#set_open #name").val(),num:$("#set_open #num").val(),id:that},function(){
							getGroup();
						},"json");
					}
				});
			});
			// 关联
			$("#main .rel").off("click").on("click",function(){
				var that = $(this).parents(".group")[0].id;
				layer.open({
					type:1,
					title:"添加分组",
					area:['400px','560px'],
					resize:false,
					id:'rel_open',
					btn:['取消','保存'],
					content:'',
					success:function(){
						$("#rel_open").css("padding",0);
						var on = 0;
						param.ztree = new TUI.TreePanel("rel_open",{
							plugin:code,
							fun:fun,
							app:app,
							expand:true,
							selected:true,
							checkbox:true,
							cascaded:true,
							device_node:true,
							device_id:param.device.join(","),
							callback:function(){
								if(on == 0){
									$.post("php/getDevice.php",{id:that},function(result){
										var treeObj = $.fn.zTree.getZTreeObj('rel_open_tree');
										result.forEach(ele => {
											var node = treeObj.getNodeByParam("id",ele.F_node_id);
											treeObj.checkNode(node);
										});
									},"json");
								}
								on++;
							}
						});
					},
					btn2:function(){
						var ids = [];
						$.each(param.ztree.getCheckedNodes(),function(i,k){
							if(k.nTplId)ids.push(k.id);
						});
						$.post("php/setNode.php",{id:that,node:ids.join(",")},function(){
							getGroup();
						},"json");
					}
				})
			});
			// 删除
			$("#main .del").off("click").on("click",function(){
				var that = $(this).parents(".group")[0].id;
				layer.confirm('分组所关联的策略也将删除，<br>是否确认删除？',{
					btn: ['取消','确定'] 
				},function(index){
					layer.close(index);
				},function(index){
					$.post("php/delGroup.php",{id:that},function(){
						getGroup();
					},"json");
				});
			});
		},"json");
	}
	// 获取url参数
	function queryString(key){
		var reg = new RegExp("(^|&)"+key+"=([^&]*)(&|$)","i");
		var r = window.location.search.substr(1).match(reg);
		if(r!=null)return unescape(r[2]);return null;
	}
});