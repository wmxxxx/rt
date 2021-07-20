$(document).ready(function(){
	var app = queryString("app");
	var fun = queryString("fun");
	var code = queryString("code");
	var user = queryString("user");
	var param = {
		list:[],
		cycle:{},
		action:{},
		device:[],
		common:[]
	};
	// 获取设备模板
	$.get("/API/Context/FunToTemplate/?fun_code="+fun,function(res){
		res.forEach(ele => param.device.push(ele.F_TemplateCode));
		// 获取命令列表
		$.post("php/getCommonList.php",{device:param.device},function(result){
			result.forEach(ele => param.common[ele.F_code] = ele);
			getList();
			$(".cycle .header .layui-btn").off("click").on("click",function(){
				param.cycle = {
					F_name:'',
					F_type_val:'0',
					F_day:0
				}
				cycleOpen();
			});
			$(".action .header .layui-btn").off("click").on("click",function(){
				param.action = {
					F_name:'',
					sub:[]
				}
				actionOpen();
			});
		},"json");
	},"json");
	function getList(){
		// 获取模型列表
		$.post("php/getList.php",{app:app},function(res){
			$(".main").empty().removeClass("x-nodata");
			res.forEach(ele => {
				param.list[ele.F_id] = ele;
				if(ele.F_type == "cycle"){// 添加周期模型
					$(".cycle .main").append(
						'<div class="x-block" id='+ele.F_id+'>'
						+'	<h2>'+ele.F_name
						+'		<ol>'
						+'			<li style="background-image: url(images/edit.png);" class="edit"></li>'
						+'			<li style="background-image: url(images/del.png);" class="del"></li>'
						+'		</ol>'
						+'	</h2>'
						+'	<ul>'
						+'		<li>'+ele.F_start+' ~ '+ele.F_end+'</li>'
						+'		<li style="color:#00B9B4;">'+(ele.F_type_val == 0 ? "日循环模式" : "自定义模式")+'</li>'
						+'		<li>'+changeWeek(ele.F_type_val)+'</li>'
						+'		<li>'+(ele.F_day ? (ele.F_day == 1 ? "工作日" : "节假日") : "默认")+'</li>'
						+'	</ul>'
						+'</div>'
					);
				}else if(ele.F_type == "action"){// 添加动作模型
					var li = "";
					ele.sub.forEach((sele,i) => {
						if(i == 2)li += '<li style="line-height:0px;">. . .</li>';
						if(param.common[sele.F_command_id]){
							li += '<li style="font-size:16px;color:#00B9B4;">'+sele.F_time+' '+param.common[sele.F_command_id].F_name+'</li>';
							li += '<li>轮询周期'+sele.F_poll_time+'分钟 轮询次数'+sele.F_poll_num+'次</li>';
						}
					});
					$(".action .main").append(
						'<div class="x-block" id='+ele.F_id+'>'
						+'	<h2>'+ele.F_name
						+'		<ol>'
						+'			<li style="background-image: url(images/edit.png);" class="edit"></li>'
						+'			<li style="background-image: url(images/del.png);" class="del"></li>'
						+'		</ol>'
						+'	</h2>'
						+'	<ul>'+li+'</ul>'
						+'</div>'
					);
				}
			});
			if($(".cycle .main .x-block").length == 0)$(".cycle .main").addClass("x-nodata");
			if($(".action .main .x-block").length == 0)$(".action .main").addClass("x-nodata");
			// 编辑
			$(".cycle .edit").off("click").on("click",function(){
				var that = $(this).parents(".x-block")[0].id;
				param.cycle = param.list[that];
				cycleOpen(that); 
			});
			$(".action .edit").off("click").on("click",function(){
				var that = $(this).parents(".x-block")[0].id;
				param.action = param.list[that];
				actionOpen(that); 
			});
			// 删除
			$(".del").off("click").on("click",function(){
				var that = $(this).parents(".x-block")[0].id;
				layer.confirm('模型所关联的策略也将删除，<br>是否确认删除？',{
					btn: ['取消','确定'] 
				},function(index){
					layer.close(index);
				},function(index){
					$.post("php/delModel.php",{id:that},function(){
						getList();
					},"json");
				});
			});
		},"json");
	}
	function cycleOpen(id){
		layer.open({
			type:1,
			title:"周期模型",
			area:['600px','460px'],
			resize:false,
			id:'cycle_open',
			skin:'layui-form',
			btn:['取消','保存'],
			content: '',
			success:function(){
				layui.laytpl(cycle_tpl.innerHTML).render(param.cycle,function(html){
					document.getElementById('cycle_open').innerHTML = html;
					layui.form.render();
					layui.laydate.render({
						elem:'#start'
					});
					layui.laydate.render({
						elem:'#end'
					});
					layui.form.on('select(type)',function(ck){
						ck.value * 1 ? $("#cycle_open .day").show() : $("#cycle_open .day").hide();
					});
				});
			},
			btn2:function(){
				var open_layui = $("#cycle_open .layui-form-item");
				var data = {
					name:open_layui.eq(0).find(".layui-input").val(),
					start:open_layui.eq(1).find(".layui-input").val(),
					end:open_layui.eq(2).find(".layui-input").val(),
					type:open_layui.eq(3).find(".layui-this").attr("lay-value"),
					type_val:[],
					day:open_layui.eq(5).find(".layui-form-radioed").prev().val()
				}
				$.each(open_layui.eq(4).find(".layui-form-checked"),function(i,k){
					data.type_val.push($(k).prev().val());
				});
				var msg = '';
				if(data.name == '')msg = '模型名称不能为空';
				else if(data.start == '')msg = '开始日期不能为空';
				else if(data.end == '')msg = '结束日期不能为空';
				else if(data.type * 1 && data.type_val.length == 0)msg = '请选择自定义日期';
				if(msg != ''){
					layer.msg(msg);
					return false;
				}else{
					$.post("php/setCycle.php",{data:data,app:app,id:id},function(){
						getList();
					},"json");
				}
			}
		});
	}
	function actionOpen(id){
		layer.open({
			type:1,
			title:"动作模型",
			area:['700px','460px'],
			resize:false,
			id:'action_open',
			skin:'layui-form',
			btn:['取消','保存'],
			content: '',
			success:function(){
				layui.laytpl(action_tpl.innerHTML).render(param,function(html){
					document.getElementById('action_open').innerHTML = html;
					layui.form.render();
					comEve();
					$("#action_open .layui-btn-xs").off("click").on("click",function(){
						layui.laytpl(control_tpl.innerHTML).render(param,function(html){
							$("#action_open tbody").append(html);
							layui.form.render();
							comEve();
						});
					});
				});
			},
			btn2:function(){
				var data = {
					name:$("#action_open .layui-form-item .layui-input").val(),
					action:[]
				};
				$.each($("#table tbody tr"),function(i,k){
					data.action.push({
						time:$(k).find("td").eq(0).find(".layui-input").eq(0).val()+":"+$(k).find("td").eq(0).find(".layui-input").eq(1).val(),
						action:$(k).find("td").eq(1).find(".layui-this").attr("lay-value"),
						value:$(k).find("td").eq(2).find(".layui-input").val(),
						poll:$(k).find("td").eq(3).find(".layui-input").val(),
						num:$(k).find("td").eq(4).find(".layui-input").val()
					});
				});
				var msg = '';
				if(data.name == '')msg = '模型名称不能为空';
				else if(data.action.length == 0)msg = '请添加动作';
				if(msg != ''){
					layer.msg(msg);
					return false;
				}else{
					$.post("php/setAction.php",{data:data,app:app,id:id},function(){
						getList();
					},"json");
				}
			}
		});
	}
	function comEve(){
		layui.form.on('select(control)',function(ck){
			ck.othis.parents("tr").find(".setAttr").val("");
		});   
		$(".setAttr").off("click").on("click",function(){
			var that = $(this);
			var code = that.parents("tr").find(".layui-form-select .layui-this").attr("lay-value");
			var com = param.common[code];
			layer.open({
				type:1,
				title:false,
				closeBtn:false,
				area:['350px','360px'],
				resize:false,
				btnAlign:'c',
				id:'control_open',
				skin:'layui-form',
				btn:['取消','确定'],
				content: '',
				success:function(){
					var tval = [];
					if(that.val() != "")tval = JSON.parse(that.val());
					if(com.sub.length){
						com.sub.forEach(ele => {
							if(ele.F_type == "input"){
								$("#control_open").append(
									'<div class="layui-form-item x-input">'
									+'	<label class="layui-form-label">'+ele.F_name+'</label>'
									+'	<div class="layui-input-block">'
									+'		<input type="text" class="layui-input attr" name='+ele.F_code+' autocomplete="off" placeholder="'+ele.F_typecontent+'" value="'+(tval[ele.F_code] ? tval[ele.F_code] : "")+'">'
									+'	</div>'
									+'</div>'
								);
							}else if(ele.F_type == "num"){
								$("#control_open").append(
									'<div class="layui-form-item x-input">'
									+'	<label class="layui-form-label">'+ele.F_name+'</label>'
									+'	<div class="layui-input-block">'
									+'		<input type="number" class="layui-input attr" name='+ele.F_code+' placeholder="'+ele.F_typecontent+'" min="'+ele.F_typecontent.split("-")[0]+'" max="'+ele.F_typecontent.split("-")[1]+'" value="'+tval[ele.F_code]+'">'
									+'	</div>'
									+'</div>'
								);
							}else if(ele.F_type == "switch"){
								$("#control_open").append(
									'<div class="layui-form-item x-switch">'
									+'	<label class="layui-form-label">'+ele.F_name+'</label>'
									+'	<div class="layui-input-block">'
									+'		<input type="checkbox" class="attr" name='+ele.F_code+' lay-skin="switch" lay-text='+ele.F_typecontent+' '+(tval[ele.F_code] ? "checked" : "")+'>'
									+'	</div>'
									+'</div>'
								);
							}else if(ele.F_type == "select"){
								var opt = '';
								ele.F_typecontent.split("-").forEach(tele => opt += '<option value='+tele.split(":")[0]+' '+(tval[ele.F_code] == tele.split(":")[0] ? "selected" : "")+'>'+tele.split(":")[1]+'</option>');
								$("#control_open").append(
									'<div class="layui-form-item x-select">'
									+'	<label class="layui-form-label">'+ele.F_name+'</label>'
									+'	<div class="layui-input-block">'
									+'		<select class="attr" name='+ele.F_code+'>'+opt+'</select>'
									+'	</div>'
									+'</div>'
								);
							}else if(ele.F_type == "date"){
								$("#control_open").append(
									'<div class="layui-form-item x-input">'
									+'	<label class="layui-form-label">'+ele.F_name+'</label>'
									+'	<div class="layui-input-block">'
									+'		<input class="layui-input layui-form-date attr" name='+ele.F_code+' type="text" id='+ele.F_code+' value='+tval[ele.F_code]+' readOnly>'
									+'	</div>'
									+'</div>'
								);
								layui.laydate.render({
									elem:'#'+ele.F_code,
									format: ele.F_typecontent
								});
							}
						});
						layui.form.render();
					}else{
						$("#control_open").html('<div style="text-align:center;">无需设置参数</div>');
					}
				},
				btn2:function(){
					var arg = {};
					$.each($("#control_open .layui-form-item"),function(i,k){
						if($(k).hasClass("x-input"))arg[$(k).find(".attr").attr("name")] = $(k).find(".attr").val();
						if($(k).hasClass("x-switch"))arg[$(k).find(".attr").attr("name")] = $(k).find(".layui-form-switch").prev().prop("checked") ? 1 : 0;
						if($(k).hasClass("x-select"))arg[$(k).find(".attr").attr("name")] = $(k).find(".layui-this").attr("lay-value");
					});
					that.val(JSON.stringify(arg) == "{}" ? null : JSON.stringify(arg));
				}
			});
		});
	}
	function changeWeek(week){
		var res = week.replace("0", "每天").replace("1", "周一").replace("2", "周二").replace("3", "周三").replace("4", "周四").replace("5", "周五").replace("6", "周六").replace("7", "周日");
		return res;
	}
	// 获取url参数
	function queryString(key){
		var reg = new RegExp("(^|&)"+key+"=([^&]*)(&|$)","i");
		var r = window.location.search.substr(1).match(reg);
		if(r!=null)return unescape(r[2]);return null;
	}
});