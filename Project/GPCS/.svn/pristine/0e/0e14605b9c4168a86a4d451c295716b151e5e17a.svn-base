$(document).ready(function(){
	var app = queryString("app");
	var fun = queryString("fun");
	var code = queryString("code");
	var user = queryString("user");
	var param = {
		list:[],
		time:{},
		temp:{},
		groun:[]
	};
	$.post("../plugin_share_group/php/getGroupList.php",{app:app},function(res){
		param.group = res;
		getList();
		$(".time .header .layui-btn").off("click").on("click",function(){
			param.time = {
				F_type_val:'0',
				F_start:'08:00:00',
				F_end:'17:00:00',
				F_name:''
			}
			timeOpen();
		});
		$(".temp .header .layui-btn").off("click").on("click",function(){
			param.temp = {
				F_start:'',
				F_end:'',
				F_name:''
			}
			tempOpen();
		});
	},"json");
	function getList(){
		// 获取模型列表
		$.post("php/getList.php",{app:app},function(res){
			$(".main").empty().removeClass("x-nodata");
			res.forEach(ele => {
				param.list[ele.F_id] = ele;
				if(ele.F_type == "time"){// 添加时控模型
					$(".time .main").append(
						'<div class="x-block" id='+ele.F_id+'>'
						+'	<h2>时控违规模型'
						+'		<ol>'
						+'			<li style="background-image: url(images/edit.png);" class="edit"></li>'
						+'			<li style="background-image: url(images/rel.png);" class="rel"></li>'
						+'			<li style="background-image: url(images/del.png);" class="del"></li>'
						+'		</ol>'
						+'	</h2>'
						+'	<ul>'
						+'		<li>循环日期</li>'
						+'		<li style="color:#00B9B4;">'+changeWeek(ele.F_type_val)+'</li>'
						+'		<li>时间范围</li>'
						+'		<li style="color:#00B9B4;">'+ele.F_start+' - '+ele.F_end+'</li>'
						+'		<li>关联分组：'+(ele.F_name == "" ? 0 : ele.F_name.split(",").length)+'个</li>'
						+'	</ul>'
						+'</div>'
					);
				}else if(ele.F_type == "temp"){// 添加温控模型
					$(".temp .main").append(
						'<div class="x-block" id='+ele.F_id+'>'
						+'	<h2>温控违规模型'
						+'		<ol>'
						+'			<li style="background-image: url(images/edit.png);" class="edit"></li>'
						+'			<li style="background-image: url(images/rel.png);" class="rel"></li>'
						+'			<li style="background-image: url(images/del.png);" class="del"></li>'
						+'		</ol>'
						+'	</h2>'
						+'	<ul>'
						+'		<li>制冷温度下限</li>'
						+'		<li style="color:#00B9B4;">'+ele.F_start+'℃</li>'
						+'		<li>制热温度上限</li>'
						+'		<li style="color:#00B9B4;">'+ele.F_end+'℃</li>'
						+'		<li>关联分组：'+(ele.F_name == "" ? 0 : ele.F_name.split(",").length)+'个</li>'
						+'	</ul>'
						+'</div>'
					);
				}
			});
			if($(".time .main .x-block").length == 0)$(".time .main").addClass("x-nodata");
			if($(".temp .main .x-block").length == 0)$(".temp .main").addClass("x-nodata");
			// 编辑
			$(".time .edit").off("click").on("click",function(){
				var that = $(this).parents(".x-block")[0].id;
				param.time = param.list[that];
				timeOpen(that); 
			});
			$(".temp .edit").off("click").on("click",function(){
				var that = $(this).parents(".x-block")[0].id;
				param.temp = param.list[that];
				tempOpen(that); 
			});
			// 关联
			$(".rel").off("click").on("click",function(){
				var that = $(this).parents(".x-block")[0].id;
				param.time = param.list[that];
				layer.open({
					type:1,
					title:"关联分组",
					area:['320px','460px'],
					resize:false,
					id:'group_open',
					skin:'layui-form',
					btn:['取消','确定'],
					content: '',
					success:function(){
						layui.laytpl(group_tpl.innerHTML).render(param,function(html){
							document.getElementById('group_open').innerHTML = html;
							layui.form.render();
						});
					},
					btn2:function(){
						var group = [];
						$.each($("#group_open ul .layui-form-checked"),function(i,k){
							group.push($(k).prev().val());
						});
						$.post("php/relGroup.php",{id:that,group:group.join(",")},function(){
							getList();
						},"json");
					}
				});
			});
			// 删除
			$(".del").off("click").on("click",function(){
				var that = $(this).parents(".x-block")[0].id;
				layer.confirm('是否删除该模型',{
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
	function timeOpen(id){
		layer.open({
			type:1,
			title:"时控违规模型",
			area:['440px','300px'],
			resize:false,
			id:'time_open',
			skin:'layui-form',
			btn:['取消','保存'],
			content: '',
			success:function(){
				layui.laytpl(time_tpl.innerHTML).render(param.time,function(html){
					document.getElementById('time_open').innerHTML = html;
					layui.form.render();
					layui.laydate.render({
						elem: '#time',
						type: 'time',
						range: true
					});
				});
			},
			btn2:function(){
				var open_layui = $("#time_open .layui-form-item");
				var data = {
					F_type_val:[],
					F_start:open_layui.eq(1).find(".layui-form-date").val().split(" - ")[0],
					F_end:open_layui.eq(1).find(".layui-form-date").val().split(" - ")[1],
					F_name:open_layui.eq(2).find(".layui-input").val()
				}
				$.each(open_layui.eq(0).find(".layui-form-checked"),function(i,k){
					data.F_type_val.push($(k).prev().val());
				});
				var msg = '';
				if(!data.F_type_val.length)msg = '请选择循环日期';
				else if(data.F_start == '' && data.F_end == '')msg = '请选择时间范围';
				if(msg != ''){
					layer.msg(msg);
					return false;
				}else{
					$.post("php/setTime.php",{data:data,app:app,id:id},function(){
						getList();
					},"json");
				}
			}
		});
	}
	function tempOpen(id){
		layer.open({
			type:1,
			title:"温控违规模型",
			area:['400px','250px'],
			resize:false,
			id:'temp_open',
			skin:'layui-form',
			btn:['取消','保存'],
			content: '',
			success:function(){
				layui.laytpl(temp_tpl.innerHTML).render(param.temp,function(html){
					document.getElementById('temp_open').innerHTML = html;
				});
			},
			btn2:function(){
				var open_layui = $("#temp_open .layui-form-item");
				var data = {
					F_start:open_layui.eq(0).find(".layui-input").val(),
					F_end:open_layui.eq(1).find(".layui-input").val(),
					F_name:open_layui.eq(2).find(".layui-input").val()
				}
				var msg = '';
				if(data.F_start == '')msg = '请输入制冷下限温度';
				else if(data.F_end == '')msg = '请输入制热上限温度';
				if(msg != ''){
					layer.msg(msg);
					return false;
				}else{
					$.post("php/setTemp.php",{data:data,app:app,id:id},function(){
						getList();
					},"json");
				}
			}
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