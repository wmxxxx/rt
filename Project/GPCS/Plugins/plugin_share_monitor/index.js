$(document).ready(function(){
	var app = queryString("app");
	var fun = queryString("fun");
	var code = queryString("code");
	var user = queryString("user");
	var data = {
		device:[],
		ajax:[],
		worker:"",
		config:[],
		com:[],
		screen:[],
		tplid:[],
		param:JSON.parse(queryString("data")),
		togo:0,
		inter:"",
		filter:[],
		err_cont:"",
		err_arr:[]
	}
	// 获取配置
	$.post("/Php/lib/plugin/share/getConfig.php",{app:app},function(result){
		data.config = result;
		// 获取配置中需要的参数
		data.config.group.forEach(group => {
			$.each(group.tpl,(i,tpl) => {
				tpl.forEach(ele => data.filter.push(ele.attr));
			});
		});
		data.config.node.forEach(node => {
			node.tips.forEach(tips => {
				$.each(tips.tpl,(i,tpl) => {
					tpl.forEach(ele => data.filter.push(ele.attr));
				});
			});
			$.each(node.tpl,(i,tpl) => data.filter.push(tpl.attr1,tpl.attr2,tpl.attr3));
		});
		data.filter = [...new Set(data.filter)];
		// 获取功能关联设备ID
		$.get("/API/Context/FunToDevice/?fun_code="+fun,function(res){
			res.forEach(ele => data.device.push(ele.F_DeviceTypeID));
			// 获取命令列表
			$.post("php/getCommand.php",function(r){
				r.com.forEach(ele => data.com[ele.F_id] = ele);
				data.tplid = r.tpl;
				// 加载树
				zTree = new TUI.TreePanel("ztree",{
					plugin:code,
					fun:fun,
					app:app,
					expand:true,
					selected:true,
					expand_type:"root",
					device_node:true,
					device_id:data.device.join(","),
					online:true,
					callback:function(node){
						clear();
						jumpPage(node);
					}
				});
			},"json");
		},"json");
	},"json");
	// 跳转画面
	function jumpPage(x){
		if(!data.togo && data.param){
			data.togo++;
			zTree.setSelectNode(data.param.deviceID);
		}else{
			if(x.nTplId == null){
				x.children != undefined && x.children[0].nTplId == null ? getData(x,0) : getData(x,1);
			}else{
				// 加载设备视图
				if(data.config.device[data.tplid[x.nTplId]].show){
					$("#device").html(
						'<iframe src="device/index.html?id='+x.id+'&app='+app+'&num='+$("#node .tab-pane.active").index()+'" width="100%" height="100%" frameborder="0" scrolling="no"></iframe>'
					).show().siblings(".warpper").hide();
				}
			}
		}
	}
	// 获取视图数据
	function getData(x,type){
		if(!type){
			// 分组视图画面
			$("#group").html(`
				<div class="header">
					<button class="btn btn-primary" type="button">设备视图</button>
					<button class="btn btn-primary" type="button" data-toggle="popover" style="display:none;">异常设备 <span class="label label-badge label-warning">0</span></button>
				</div>
				<div class="main"></div>
			`.trim()).show().removeClass("loading load-indicator").siblings(".warpper").hide();
			$("#group .header button:eq(0)").off("click").on("click",function(){
				clear();
				getData(x,2);
			});
			data.err_cont = '<table class="table table-bordered table-hover"><tbody>';
			data.err_arr = [];
			if(!data.togo && data.param)$("#group button").click();
			x.children.forEach(ele => {
				var bgNum = ~~(Math.random()*6);
				var g_main = '';
				data.config.group.forEach((group,i) => {
					g_main += `<div>
								<p style="color:${group.color};"><span class="b-attr${i}">0</span> ${group.unit}</p>
								<p>${group.text}</p>
							</div>`;
				});
				$("#group .main").append(`
					<div class="x-block show" id=${ele.id}>
						<h2>${ele.name}<ol></ol></h2>
						<div style="background-image:url(images/${bgNum}.png)">${g_main}</div>
						<ul>
							<li>在线数量：<span class="b-online">--</span>/<small class="b-all">--</small></li>
							<li>关联策略：<span class="b-rel">--</span></li>
						</ul>
					</div>
				`.trim());
			});
			$("#group .main .x-block").off("click").on("click",function(){
				zTree.setSelectNode(this.id);
			});
			clearInterval(data.inter);
			data.inter = setInterval(() => {
				$(".x-block h2 ol").animate({marginTop:'-35px'},500,function(){
					$(this).css({marginTop:"0px"}).find(":first").appendTo(this);
				});
			},3000);
		}else{
			// 节点视图画面
			$("#node").addClass("x-loading").html(
				'<ul class="nav nav-tabs"></ul>'
				+'<div class="tab-content"></div>'
			).show().siblings(".warpper").hide();
			data.config.node.forEach((tab,i) => {
				var tabTop = '<li class='+(i == 0 ? 'active' : "")+'><a href="javascript:;" data-target="#tabContent'+i+'" data-toggle="tab">'+tab.name+'</a></li>';
				var tabMain = '<div id="tabContent'+i+'" class="tab-pane '+(i == 0 ? 'active' : "")+'">'
							+'<div class="header">'
								+'<ul class="tips"></ul>'
								+'<div class="btns"></div>'
							+'</div>'
							+'<div class="access"></div>'
							+'<div class="main"></div>'
						+'</div>';
				$("#node .nav").append(tabTop).next().append(tabMain);
				var that = $("#tabContent"+i);
				data.screen[i] = [];
				// 状态
				that.find(".header .tips").append('<li>设备总数(<strong>0</strong>)</li>')
				tab.tips.forEach(tip => {
					that.find(".header .tips").append('<li><span style="background-color:'+tip.color+';"></span>'+tip.text+'<small></small></li>');
				});
			});
			// 框选
			$("#node .main").selectable({
				selector:'.x-block.small',
				clickBehavior:'multi'
			});
		}
		// 需要获取数据的节点
		var entity = [];
		if(type && x.children != undefined && x.children[0].nTplId != null){
			entity.push(x.id);
		}else{
			x.children.forEach(ele => entity.push(ele.id));
		}
		// 取数据
		entity.forEach(id => {
			var ajax_data = {
				device:data.device,
				entity:id,
				app:app,
				type:type
			}
			if(data.filter != "")ajax_data.filter_str = data.filter.join(",");
			$.ajax({
				url:"php/getChildInfo.php",
				type:"post",
				dataType:"json",
				context:this,
				data:ajax_data,
				beforeSend:function(xhr){data.ajax.push(xhr);},
				success:function(result){
					type ? NodePage(result,x) : GroupPage(result,id);
				}
			});
		});
	}
	// 分组视图
	function GroupPage(result,id){
		// 计算数量
		var gInfo = [];
		gInfo["all"] = result.node.length,gInfo["online"] = 0,gInfo["rel"] = 0;
		result.node.forEach(Rele => {
			// 异常信息
			if(Rele.err != ""){
				data.err_cont+= '<tr><td style="width:200px;">'+Rele.name+'</td><td>'+Rele.err+'</td></tr>';
				data.err_arr.push({
					name:Rele.name,
					msg:Rele.err
				});
				$("#group .header .label-warning").html($("#group .header .label-warning").html()*1+1);
			}
			if(Rele.online){
				gInfo["online"]++;
				data.config.group.forEach((group,i) => {
					if(!gInfo["attr"+i])gInfo["attr"+i] = 0;
					var judge = group.tpl[Rele.tpl_tag],cond = [];
					if(judge){
						judge.forEach(Jele => {
							var vData = Rele.data[Jele.attr];
							if(vData){
								cond.push(vData.value,Jele.check,Jele.judge);
								if(Jele.pos)cond.push(Jele.pos);
							}else{
								return cond = [];
							}
						});
						if(eval(cond.join("")))gInfo["attr"+i]++;
					}
				});
			}
			gInfo["rel"] += (Rele.str ? 1 : 0);
		});
		// 更新数据
		for(var k in gInfo){
			$("#"+id+" .b-"+k).html(gInfo[k]);
		}
		result.access.forEach(ele => {
			ele.variantDatas.forEach(vele => $("#"+id+" h2 ol").append("<li>"+vele.name+":"+vele.value+vele.unit+"</li>"));
		});
		// 请求全部结束判断是否展示异常设备
		var num = 0;
		data.ajax.forEach(ele => num += ele.status);
		if(num == data.ajax.length * 200){
			$('[data-toggle="popover"]').popover({
				html:true,
				placement:'bottom',
				title:'<table class="table"><tbody><tr><td>设备名称</td><td>异常描述</td><td style="width:50px;"><button class="btn btn-mini export" type="button">导出</button></td></tr></tbody></table>',
				content:data.err_cont
			});
			$('[data-toggle="popover"]').on("shown.zui.popover",function(){
				$("#group .popover-title button").off("click").on("click",function(){
					JSONToExcelConvertor(data.err_arr);
				});
				$("#group .main").off("click").on("click",function(){
					$('[data-toggle="popover"]').popover('hide');
				});
			});
			if($("#group .header .label-warning").html() != 0)$("#group .header button:eq(1)").show();
		}
	}
	// 节点视图
	function NodePage(result,x){
		data.config.node.forEach((tab,i) => {
			var that = $("#tabContent"+i),tab_tpl = [];
			$.each(tab.tpl,(ii,k) => tab_tpl.push(ii));
			// 设备
			if(result.node.length){
				var htm = "";
				result.node.forEach(node => {
					if(tab_tpl.includes(node.tpl_tag)){
						var dBlock = data.config.node[i].tpl[node.tpl_tag];
						var blockStatus = judgeStatus(node.data,node.tpl_tag,i);
						var blockDiv = "",blockLi = "",blockH2 = "";
						if(node.len > 2){
							blockDiv += node.data[dBlock.attr1] ? '<div><p><span style="font-size:22px;" class='+dBlock.attr1+'>'+node.data[dBlock.attr1].show+'</span>'+node.data[dBlock.attr1].unit+'</p><p>'+node.data[dBlock.attr1].name+'</p></div>' : '<div style="font-size:22px;color:#999;display:flex;align-items:center;justify-content:center;"><p><span>未知属性</span></p><p></p></div>';
							blockLi += node.data[dBlock.attr2] ? '<li>'+node.data[dBlock.attr2].name+': <span class='+dBlock.attr2+'>'+node.data[dBlock.attr2].show+'</span>'+node.data[dBlock.attr2].unit+'</li>' : '<li>未知属性</li>';
							blockLi += node.data[dBlock.attr3] ? '<li>'+node.data[dBlock.attr3].name+': <span class='+dBlock.attr3+'>'+node.data[dBlock.attr3].show+'</span>'+node.data[dBlock.attr3].unit+'</li>' : '<li>未知属性</li>';
							if(data.config.device[node.tpl_tag].show)blockH2 = '<a href="javascript:;" onClick=zTree.setSelectNode('+node.code+');>详情</a>';
						}else{
							blockDiv += '<div style="font-size:22px;color:#999;"><p><span>设备</span></p><p>未通讯</p></div>';
						}
						htm += '<div class="x-block small show tip'+blockStatus.state+'" id='+node.code+' group='+JSON.stringify(node.group)+'>'
								+'<h2>'+node.name+blockH2+'</h2>'
								+'<div style="background-image:url(images/'+dBlock.icon+'),linear-gradient('+blockStatus.color+','+blockStatus.color+')">'+blockDiv+'</div>'
								+'<ul>'+blockLi+'</ul>'
							+'</div>';
					}
				});
				that.find(".main").append(htm);
			}
			// 外接设备
			if(x.children != undefined && x.children[0].nTplId != null && result.access.length){
				that.find(".main").addClass("ext");
				result.access.forEach(ele => {
					var li = '' ;
					ele.variantDatas.forEach(vele => li += '<li><strong>'+vele.show+'</strong> '+vele.unit+'<p>'+vele.name+'</p></li>');
					that.find(".access").append(
						'<span class='+ele.tpl_tag+'>'
						+'	<div>'
						+'		<h5><span style="background-color:'+(ele.online ? "#159316" : "#ddd")+';"></span>'+ele.name+'</h5>'
						+'		<ul>'+li+'</ul>'
						+'	</div>'
						+'</span>'
					);
				});
				if(that.find(".access > span").length)that.find(".access").show();
			}
			// 请求全部结束
			var num = 0;
			data.ajax.forEach(ele => num += ele.status);
			if(num == data.ajax.length * 200){
				// 状态
				that.find(".header .tips li:eq(0) strong").html(that.find(".main .x-block.small").length);
				tab.tips.forEach((tip,i) => {
					var num = $(".x-block.tip"+i).length;
					if(num)that.find(".header .tips li:eq("+(i+1)+") small").html("("+num+")");
				});
				// 筛选
				if(tab.screen){
					that.find(".header .btns").append(
						'<div class="term"></div>'
						+'<button id="btnScreen'+i+'" type="button" class="btn btn-primary" data-toggle="tooltip" data-placement="left" title="数据正在加载">筛选</button>'
					);
					$('[data-toggle="tooltip"]').tooltip();
					$.post("php/screenInfo.php",{tpl:tab_tpl},function(res){
						data.worker = new Worker('getChildInfo.js');
						data.worker.postMessage({
							device:data.device,
							entity:x.id,
							app:app,
							type:0
						});
						data.worker.onmessage = function(event){
							event.data.node.forEach(node => {
								$.each(node.data,function(i,k){
									$("#"+node.code).attr(i,k.show);
								});
							});
							$('[data-toggle="tooltip"]').tooltip('destroy');
							$("#btnScreen"+i).off("click").on("click",function(){
								layer.open({
									type:1,
									title:"筛选",
									area:['350px','400px'],
									resize:false,
									id:'screen_open',
									skin:'layui-form',
									btn:['取消','确定'],
									content: '',
									success:function(){
										layui.laytpl(screen_tpl.innerHTML).render(res,function(html){
											document.getElementById('screen_open').innerHTML = html;
											layui.form.render();
											layui.form.on('select(attr)',function(data){
												var data = data.value.split("#");
												if(data[1] == ""){
													$("#screen_open .main").html(
														'<div class="layui-form-item s-cond">'
															+'<label class="layui-form-label">筛选条件</label>'
															+'<div class="layui-input-block">'
																+'<select class="form-control">'
																	+'<option value="==">等于</option>'
																	+'<option value=">">大于</option>'
																	+'<option value="<">小于</option>'
																	+'<option value="!=">不等于</option>'
																+'</select>'
															+'</div>'
														+'</div>'
														+'<div class="layui-form-item s-value">'
															+'<label class="layui-form-label">筛选内容</label>'
															+'<div class="layui-input-block">'
																+'<input type="text" class="layui-input" autocomplete="off">'
															+'</div>'
														+'</div>'
													);
												}else{
													var opt = "";
													data[1].split("-").forEach(ele => opt += '<option value='+ele.split(":")[1]+'>'+ele.split(":")[1]+'</option>');
													$("#screen_open .main").html(
														'<div class="layui-form-item s-value">'
															+'<label class="layui-form-label">筛选内容</label>'
															+'<div class="layui-input-block">'
																+'<select class="form-control">'+opt+'</select>'
															+'</div>'
														+'</div>'
													);
												}
												layui.form.render();
											});
										});
									},
									btn2:function(){
										data.screen[i].push({
											attr:$("#screen_open > .layui-form-item .layui-this").attr("lay-value").split("#")[0],
											iattr:$("#screen_open > .layui-form-item .layui-this").html(),
											cond:$("#screen_open .s-cond .layui-this").attr("lay-value"),
											icond:$("#screen_open .s-cond .layui-this").attr("lay-value") ? $("#screen_open .s-cond .layui-this").html() : 0,
											value:$("#screen_open .s-value .layui-this").attr("lay-value") == undefined ? $("#screen_open .s-value .layui-input").val() : $("#screen_open .s-value .layui-this").attr("lay-value")
										});
										checkByAttr(i);
									}
								});
							});
							// 外部跳转
							if(data.togo && data.param){
								if(data.param.type == "online"){
									data.screen[0].push({
										attr: "online",
										iattr: "通讯状态",
										icond: 0,
										value: "在线",
									});
								}else if(data.param.type == "StartStopStatus"){
									data.screen[0].push({
										attr: "StartStopStatus",
										iattr: "开关状态",
										icond: 0,
										value: "开机",
									});
								}else if(data.param.type == "illeagl"){
									data.screen[0].push({
										attr: "illegal",
										iattr: "是否违规",
										icond: 0,
										value: "违规",
									});
								}
								checkByAttr(i);
								data.param = 0;
							}
						}
					},"json");
				}else{
					// 外部跳转
					if(data.togo && data.param)data.param = 0;
				}
				// 批量控制
				if(tab.control){
					var bli = '';
					tab.batch.forEach(batch => {
						if(data.com[batch])bli += '<li value='+data.com[batch].F_id+'><a href="javascript:;">'+data.com[batch].F_name+'</a></li>';
					});
					that.find(".header .btns").append(
						'<div class="btn-group">'
							+'<button id="btnControl" type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">批量控制 <span class="caret"></span></button>'
							+'<ul class="dropdown-menu pull-right" role="menu" aria-labelledby="btnControl">'+bli+'</ul>'
						+'</div">'
					);
					that.find(".dropdown-menu li").off("click").on("click",function(){
						var nodes = that.find(".x-block.show.active");
						var com = data.com[$(this).attr("value")];
						if(nodes.length){
							var arg = {},nid = [];
							$.each(nodes,(i,k) => nid.push(k.id));
							if(com && com.sub.length){
								layer.open({
									type:1,
									title:false,
									closeBtn:false,
									area:['350px','350px'],
									resize:false,
									btnAlign:'c',
									id:'control_open',
									skin:'layui-form',
									btn:['取消','确定'],
									content: '',
									success:function(){
										com.sub.forEach(ele => {
											var form = "";
											if(ele.F_type == "input"){
												form = '<input type="text" class="layui-input attr x-input" name='+ele.F_code+' autocomplete="off" placeholder="'+ele.F_typecontent+'">';
											}else if(ele.F_type == "num"){
												form = '<input type="number" class="layui-input attr x-input" name='+ele.F_code+' placeholder="'+ele.F_typecontent+'" min="'+ele.F_typecontent.split("-")[0]+'" max="'+ele.F_typecontent.split("-")[1]+'">';
											}else if(ele.F_type == "switch"){
												form = '<input type="checkbox" class="attr x-switch" name='+ele.F_code+' lay-skin="switch" lay-text='+ele.F_typecontent+'>';
											}else if(ele.F_type == "select"){
												var opt = '';
												ele.F_typecontent.split("-").forEach(tele => opt += '<option value='+tele.split(":")[0]+'>'+tele.split(":")[1]+'</option>');
												form = '<select class="attr x-select" name='+ele.F_code+'>'+opt+'</select>';
											}else if(ele.F_type == "date"){
												$("#control_open").append(
													'<div class="layui-form-item x-input">'
														+'<label class="layui-form-label">'+ele.F_name+'</label>'
														+'<div class="layui-input-block">'
															+'<input class="layui-input layui-form-date attr x-input" name='+ele.F_code+' type="text" id='+ele.F_code+' readOnly>'
														+'</div>'
													+'</div>'
												);
												layui.laydate.render({
													elem:'#'+ele.F_code,
													format: ele.F_typecontent
												});
											}
											if(form != ""){
												$("#control_open").append(
													'<div class="layui-form-item x-input">'
														+'<label class="layui-form-label">'+ele.F_name+'</label>'
														+'<div class="layui-input-block">'+form+'</div>'
													+'</div>'
												);
											}
										});
										layui.form.render();
									},
									btn2:function(){
										$.each($("#control_open .layui-form-item"),function(i,k){
											if($(k).find(".attr").hasClass("x-input"))arg[$(k).find(".attr").attr("name")] = $(k).find(".attr").val();
											if($(k).find(".attr").hasClass("x-switch"))arg[$(k).find(".attr").attr("name")] = $(k).find(".layui-form-switch").prev().prop("checked") ? 1 : 0;
											if($(k).find(".attr").hasClass("x-select"))arg[$(k).find(".attr").attr("name")] = $(k).find(".layui-this").attr("lay-value");
										});
										send(nid,com,arg);
									}
								});
							}else{
								send(nid,com,arg);
							}
						}else{
							new $.zui.Messager("请选择设备",{
								type: 'warning',
								icon: 'warning-sign'
							}).show();
						}
					});
				}
				// 右击菜单
				var nlist = that.find(".x-block");
				that.contextmenu({
					items:[{
						label:'分组视图',
						onClick:function(){
							togroup(nlist,that);
						}
					},{
						label:'默认视图',
						onClick:function(){
							that.find(".main").empty();
							$.each(nlist,(i,item) => {
								that.find(".main").append(item);
							});
							checkByAttr(i);
						}
					},{
						label:'全选',
						onClick:function(){
							that.find(".x-block.show").addClass("active");
						}
					}],
					onShown:function(){
						$(".contextmenu-menu li").eq(($("#node .main .panel").length ? 0 : 1)).hide();
					}
				});
				// 无设备隐藏
				if(!that.find(".main .x-block.small").length){
					if(tab.hide){
						that.addClass("hide");
						$("#node .nav li").eq(i).addClass("hide");
					}else{
						that.find(".main").addClass("x-nodata");
					}
				}
				// 默认分组视图
				if(tab.group)togroup(nlist,that);
				$("#node").removeClass("x-loading");
				$("#node .nav li").removeClass("active").not(".hide").eq(0).addClass("active");
				$("#node .tab-content .tab-pane").removeClass("active").not(".hide").eq(0).addClass("active");
				$("#node .nav li").not(".hide").length == 1 ? $("#node").addClass("one") : $("#node").removeClass("one");
			}
		});
	}
	// 按分组展示
	function togroup(nlist,that){
		that.find(".main").empty();
		$.each(nlist,(i,item) => {
			var glist = JSON.parse($(item).attr("group"));
			if(glist.length){
				$.each(glist,(k,kle) => {
					if(!$("#node .group"+kle.F_code).length){
						that.find(".main").append(
							'<div class="panel group'+kle.F_code+'">'
								+'<div class="panel-heading" style="display:flex;">'
									+'<span class="panel-title" data-toggle="collapse" data-target="#D'+kle.F_code+'">'+kle.F_name+'(<span>0</span>)</span>'
									+'<button class="btn btn-primary btn-mini Gall" type="button" style="margin-left:20px;">全选</button>'
								+'</div>'
								+'<div id="D'+kle.F_code+'" class="panel-collapse collapse in">'
									+'<div class="panel-body"></div>'
								+'</div>'
							+'</div>'
						);
					}
					$(item).clone(true).appendTo("#node .group"+kle.F_code+" .panel-body");
					that.find(".group"+kle.F_code+" .panel-title span").html($("#node .group"+kle.F_code+" .panel-body .x-block").length);
				});
			}else{
				if(!$("#node .ungroup").length){
					that.find(".main").append(
						'<div class="panel ungroup">'
							+'<div class="panel-heading" style="display:flex;">'
								+'<span class="panel-title" data-toggle="collapse" data-target="#ungroup">未分组(<span>0</span>)</span>'
								+'<button class="btn btn-primary btn-mini Gall" type="button" style="margin-left:20px;">全选</button>'
							+'</div>'
							+'<div id="ungroup" class="panel-collapse collapse in">'
								+'<div class="panel-body"></div>'
							+'</div>'
						+'</div>'
					);
				}
				$(item).clone(true).appendTo("#node .ungroup .panel-body");
				that.find(".ungroup .panel-title span").html($("#node .ungroup .panel-body .x-block").length);
			}
		});
		$(".Gall").off("click").on("click",function(){
			$(this).parents(".panel").find(".panel-body .x-block").addClass('active');
		});
	}
	// 筛选设备
	function checkByAttr(num){
		$("#node #tabContent"+num+" .header .btns .term").empty();
		data.screen[num].forEach(ele => {
			$("#node #tabContent"+num+" .header .btns .term").append("<p class='x-tips'>"+(ele.cond ? ele.iattr+ele.icond+ele.value : ele.value)+"<span>x</span></p>");
			$(".x-tips span").off("click").on("click",function(){
				var num = $("#node .nav li.active").index();
				data.screen[num].splice($(this).parent("p").index(),1);
				checkByAttr(num);
			});
		});
		var nodes = $("#node #tabContent"+num+" .main .x-block");
		data.screen[num].length ? nodes.removeClass("show") : nodes.addClass("show");
		for(let i of nodes){
			var cond = [];
			for(let k of data.screen[num]){
				if($(i).attr(k.attr) != undefined){
					if(cond.length != 0)cond.push("&&");
					var cterm = isNaN($(i).attr(k.attr) * 1) ? "'"+$(i).attr(k.attr)+"'" : $(i).attr(k.attr);
					var val = isNaN(k.value * 1) ? "'"+k.value+"'" : k.value;
					if(cterm != "" && val != "")cond.push(cterm,(k.cond ? k.cond : "=="),val);
				}else{
					cond = [];
					break;
				}
			}
			if(cond.length && eval(cond.join("")))$(i).addClass("show");
		}
		$("#node #tabContent"+num+" .main .show").length ? $("#node #tabContent"+num+" .main").removeClass("x-nodata") : $("#node #tabContent"+num+" .main").addClass("x-nodata");
		// 状态
		$.each($("#node #tabContent"+num+" .header .tips li"),function(k,i){
			if(k == 0)$(i).html('设备总数('+$("#node #tabContent"+num+" .main .show").length+')');
			var node = $("#node #tabContent"+num+" .main .show.tip"+(k-1)).length;
			$(i).find("small").html(node ? "("+node+")" : "");
		});
	}
	// 判断设备状态
	function judgeStatus(vDatas,tplId,inx){
		var result={
			color:"#ccc",
			state:0
		};
		try{
			data.config.node[inx].tips.forEach((ele,i) => {
				var check = ele.tpl[tplId];
				var cond = [];
				check.forEach(cele => {
					var vData = vDatas[cele.attr];
					if(vData){
						cond.push(vData.value,cele.check,cele.judge);
						if(cele.pos)cond.push(cele.pos);
					}
				});
				if(eval(cond.join(""))){
					result.color = ele.color;
					result.state = i;
					throw new Error();
				}
			});
		}catch(err){
			return result;
		}
		return result;
	}
	function send(node,com,arg){
		var load = new $.zui.Messager(com.F_name+"命令下发中",{
			type: 'primary',
			icon: 'spinner-snake icon-spin',
			time: 0
		}).show();
		$.post("/Php/lib/plugin/share/commandSend.php",{
			code:com.F_id,
			pCode:app,
			name:com.F_name,
			type:1,
			arg:arg,
			nodelist:node
		},function(res){
			load.hide();
			new $.zui.Messager("命令已下发",{
				type: 'primary',
				icon: 'info-sign'
			}).show();
		},"json");
	}
	// 清除请求
	function clear(){
		data.ajax.forEach(ele => ele.abort());
		data.ajax = [];
		if(data.worker != "")data.worker.terminate();
		data.worker = "";
	}
	// 导出csv
	function JSONToExcelConvertor(data) {
		var CSV = '设备名称,异常描述\n';
		data.forEach(ele => CSV += ele.name+','+ele.msg+'\n');
		var fileName = "异常设备列表_"+new Date().toJSON().split(".")[0].replace(/:/g, '');
		var url = new Blob(['\ufeff'+CSV],{type:"text/csv"});
		if(window.navigator && window.navigator.msSaveOrOpenBlob){
			window.navigator.msSaveOrOpenBlob(CSV,fileName+".csv");
		}else{
			var link = document.createElement("a");
			link.href = URL.createObjectURL(url);
			link.style = "visibility:hidden";
			link.download = fileName + ".csv";
			document.body.appendChild(link);
			link.click();
			document.body.removeChild(link);
		}
	}
	// 获取url参数
	function queryString(key){
		var reg = new RegExp("(^|&)"+key+"=([^&]*)(&|$)","i");
		var r = window.location.search.substr(1).match(reg);
		if(r!=null)return unescape(r[2]);return null;
	}
	// 禁用右击菜单
	document.oncontextmenu = function(){event.returnValue = false;}
});