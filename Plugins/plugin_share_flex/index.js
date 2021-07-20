$(document).ready(function(){
	var app = queryString("app");
	var fun = queryString("fun");
	var code = queryString("code");
	var user = queryString("user");
	var param = {
		list:[],
		now:{}
	};
	getList();
	function getList(){
		$.post("php/getModel.php",{app:app},res => {
			param.group = res.group;
			$(".main").empty();
			res.model.forEach(ele => {
				param.list[ele.F_id] = ele;
				if(ele.F_type == "temphum"){
					$(".main").append(`
						<div class="x-block temphum" id=${ele.F_id}>
							<h2>温湿度模型
								<ol>
									<li style="background-image: url(images/edit.png);" class="edit"></li>
									<li style="background-image: url(images/rel.png);" class="rel"></li>
								</ol>
							</h2>
							<ul>
								<li>开机温度范围</li>
								<li style="color:#00B9B4;">${ele.F_start}</li>
								<li>温度调节范围</li>
								<li style="color:#00B9B4;">${ele.F_end}</li>
								<li>关联分组：${ele.F_name == "" ? 0 : ele.F_name.split(",").length}个</li>
							</ul>
						</div>
					`.trim());
				}
			});
			// 编辑
			$(".temphum .edit").off("click").on("click",function(){
				var that = $(this).parents(".x-block")[0].id;
				param.temphum = param.list[that];
				temphumOpen(that); 
			});
			// 关联
			$(".rel").off("click").on("click",function(){
				var that = $(this).parents(".x-block")[0].id;
				param.now = param.list[that];
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
		},"json");
	}
	function temphumOpen(id){
		layer.open({
			type:1,
			title:"温湿度模型",
			area:['440px','300px'],
			resize:false,
			id:'temphum_open',
			skin:'layui-form',
			btn:['取消','保存'],
			content: '',
			success:function(){
				layui.laytpl(temphum_tpl.innerHTML).render(param.temphum,function(html){
					document.getElementById('temphum_open').innerHTML = html;
					layui.form.render();
				});
			},
			btn2:function(){
				var open_layui = $("#temphum_open .layui-form-item");
				var data = {
					F_start:open_layui.eq(0).find(".layui-input").eq(0).val()+"-"+open_layui.eq(0).find(".layui-input").eq(1).val(),
					F_end:open_layui.eq(1).find(".layui-input").eq(0).val()+"-"+open_layui.eq(1).find(".layui-input").eq(1).val(),
					F_name:open_layui.eq(2).find(".layui-input").val()
				}
				var msg = '';
				if(data.F_start == '' && data.F_end == '')msg = '请设置温度范围';
				if(msg != ''){
					layer.msg(msg);
					return false;
				}else{
					$.post("php/setTempHum.php",{data:data,app:app,id:id},function(){
						getList();
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