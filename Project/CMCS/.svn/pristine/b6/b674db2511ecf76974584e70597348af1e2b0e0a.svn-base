MainPanel = function (container, param) {
	return {
		init: function () {

			this.container = container;
			this.code = param.code; //插件编号
			this.fun = param.fun; //功能编号
			this.app = param.app; //系统编号
			this.user = param.user; //用户编号

			if (this.app == "") this.app = "9999";

			$('#' + this.container).empty().html('<div class="rt-content-box">' +
				'<div class="layui-form" style="display: inline-block;padding: 10px 20px;">' +
				'<div id="listBtn">' +
				'<div class="form-group" style="float: left;"><button id="addbtn" class="layui-btn layui-btn-normal layui-btn-sm">新增接口</button>' +
				'</div>' +
				'<div class="form-group" style="float: left;"><button id="searchbtn" class="layui-btn layui-btn-normal layui-btn-sm">刷新列表</button>' +
				'</div>' +
				'</div>' +
				'</div>' +
				'<div style="background-color: #f2f2f2;height: 12px"></div>' +
				'<div class="tb"><table id="relation-table" lay-filter="demo"></table></div>' +
				'</div>');

			// 全局变量
			this.selectedId = {};

			$('.rt-content-box').find("#searchbtn").bind("click", {handle: this, cfg: null}, function (e) {
				TUI.env.currPlugin.searchAll();
			});

			$('.rt-content-box').find("#addbtn").bind("click", {handle: this, cfg: null}, function (e) {
				TUI.env.currPlugin.addOne();
			});

			TUI.env.currPlugin.initView();
			TUI.env.currPlugin.searchAll();
		},
		searchAll: function () {
			layui.use('table', function () {
				TUI.env.currPlugin.table = layui.table;
				TUI.env.currPlugin.table.render({
					id: 'testReload',
					elem: '#relation-table',
					height: 'full-83',
					url:"../php/interface/getList.php",
					page: false,
					cols: [[ //表头
						{field: 'F_id', title: '接口标识', align: 'center'},
						{field: 'F_name', title: '接口名称', align: 'center'},
						{field: 'F_remark', title: '接口描述', align: 'center'},
						, {
							field: 'F_id',
							title: '操作',
							align: 'center',
							width: 155,
							toolbar: '<div><a class="btn-a btn-a-normal" lay-event="info" style="margin-top: 2px;">修改</a><a class="btn-a btn-a-normal" lay-event="detail" style="margin-top: 2px;">参数配置</a><a class="btn-a btn-a-normal" lay-event="del" style="margin-top: 2px;">删除</a></div>'
						}
					]]
				});

				TUI.env.currPlugin.table.on('tool(demo)', function (obj) {
					var data = obj.data;
					if (obj.event === 'detail') {
						TUI.env.currPlugin.addArg(data.F_id);
					} else if (obj.event === 'del') {
						TUI.env.currPlugin.delOne(data.F_id);
					} else if (obj.event === 'info') {
						TUI.env.currPlugin.xgOne(data);
					}
				});
			});
		},
		addOne: function () {
			layer.open({
				type: 1,
				title: "添加接口",
				area: ['700px', '250px'],
				shadeClose: true,
				skin: 'reatgreen-layer',
				btn: ['取消', '保存'],
				btn1: function (index, layero) {
					layer.close(index);
				},
				btn2: function (index, layero) {
					var cando = true;
					var name = $('#name').prop("value");
					var remark = $('#remark').prop("value");
					if (name == '') {
						cando = false;
						layer.msg('请输入接口名称', {
							time: 1000
						});
					}
					if (remark == '') {
						cando = false;
						layer.msg('请输入接口描述', {
							time: 1000
						});
					}
					if (cando) {
						$.ajax({
							type: 'post',
							url:"../php/interface/save.php",
							data: {name: name, remark: remark, id: ""},
							dataType: "json",
							context: this,
							success: function (result) {
								layer.close(index);
								TUI.env.currPlugin.searchAll();
							}
						});
					}
					return false;
				},
				success: function (index, layero) {
				},
				content: '<div><form class="layui-form" style="padding-right:40px">' +
					'<div class="layui-form-item"><label class="layui-form-label">接口名称:</label><div class="layui-input-block"><input id="name" class="layui-input" autocomplete="off"></div></div>' +
					'<div class="layui-form-item"><label class="layui-form-label">接口描述:</label><div class="layui-input-block"><input id="remark" class="layui-input" autocomplete="off"></div></div>' +
					'</form>' +
					'</div>'
			});
		},
		addArg: function (key) {
			layer.open({
				type: 1,
				title: "参数配置",
				area: ['700px', '430px'],
				shadeClose: true,
				skin: 'reatgreen-layer',
				btn: ['取消', '保存'],
				btn1: function (index, layero) {
					layer.close(index);
				},
				btn2: function (index, layero) {
					var cando = true;
					var list = [];
					$("#planmx").find("#tmptr").each(function () {
						var tds = $(this).children();
						var a1 = tds.eq(0).find("#tmp1").val();
						var a2 = tds.eq(1).find("#tmp2").val();
						var a3 = tds.eq(2).find("#tmp3").val();
						var a4 = tds.eq(3).find("#tmp4").val();
						if (a1 != "" && a2 != "") {

							list.push({
								name: a1,
								code: a2,
								type: a3,
								typecontent: a4
							});

						}
					})
					if (list.length == -1) {
						cando = false;
						layer.msg('最少添加1个', {
							time: 1000
						});
					}
					var json = JSON.stringify(list);
					if (cando) {
						$.ajax({
							type: 'post',
							url:"../php/interface/saveArg.php",
							data: {infoid: key, list: json},
							dataType: "json",
							context: this,
							success: function (result) {
								layer.close(index);
								TUI.env.currPlugin.searchAll();
							}
						});
					}
					return false;
				},
				success: function (index, layero) {
					$.ajax({
						type: 'post',
						url:"../php/interface/getArgList.php?infoid="+key,
						dataType: "json",
						context: this,
						success: function (result) {
							if (result && result.length > 0) {
								for (var i = 0; i < result.length; i++) {
									var tmp = $("#tmptr").clone();
									$(tmp).find("#tmp1").val(result[i].F_name);
									$(tmp).find("#tmp2").val(result[i].F_code);
									$(tmp).find("#tmp3").val(result[i].F_type);
									$(tmp).find("#tmp4").val(result[i].F_typecontent);
									$("#planmx").append(tmp);
								}
							} else {
								TUI.env.currPlugin.addmx();
								TUI.env.currPlugin.addmx();
								TUI.env.currPlugin.addmx();
								TUI.env.currPlugin.addmx();
								TUI.env.currPlugin.addmx();
								TUI.env.currPlugin.addmx();
							}
						}
					});
				},
				content: '<div><table style="display:none">' +
					'<tr id="tmptr" style="height: 40px;">' +
					'<td><input class="rt-input layui-input" placeholder="" style="width: 144px;display: initial;text-align: center;padding-left: 0;" value="" id="tmp1"></td>' +
					'<td><input class="rt-input layui-input" placeholder="" style="width: 144px;display: initial;text-align: center;padding-left: 0;" value="" id="tmp2"></td>' +
					'<td><select class="rt-select layui-select" id="tmp3" style="width: 144px;">' +
					'<option value="input">输入框</option>' +
					'<option value="num">数字</option>' +
					'<option value="switch">开关</option>' +
					'<option value="select">下拉框</option>' +
					'<option value="date">日期</option>' +
					'</select></td>' +
					'<td><input class="rt-input layui-input" placeholder="" style="width: 144px;display: initial;text-align: center;padding-left: 0;" value="" id="tmp4"></td>' +
					'<td><div class="layui-input-inline" style="width: auto;" onclick="TUI.env.currPlugin.delmx(this)"><i class="layui-icon layui-icon-delete" style="font-size: 25px;"></i></div></td>' +
					'</tr>' +
					'</table>' +
					'<table style="width: calc(100% - 40px);margin: 0 20px;" id="planmx">' +
					'<tr id="tmptrhead" style="height: 40px;font-size: 13px;">' +
					'<td><span>参数名称</span></td>' +
					'<td><span>参数标识</span></td>' +
					'<td><span>参数类型</span></td>' +
					'<td><span>类型扩展</span></td>' +
					'<td style="width:50px"><div class="layui-input-inline" style="width: auto;" onclick="TUI.env.currPlugin.addmx()"> <i class="layui-icon layui-icon-add-circle" style="font-size: 25px;"></i></div></td>' +
					'</tr>' +
					'</table>' +
					'</div>'
			});
		},
		delmx: function (obj) {
			$(obj).parent().parent().remove();
		},
		addmx: function () {
			var copy = $("#tmptr").clone();
			$("#planmx").append(copy);
		},
		xgOne: function (infoobj) {
			layer.open({
				type: 1,
				title: "添加接口",
				area: ['700px', '250px'],
				shadeClose: true,
				skin: 'reatgreen-layer',
				btn: ['取消', '保存'],
				btn1: function (index, layero) {
					layer.close(index);
				},
				btn2: function (index, layero) {
					var cando = true;
					var name = $('#name').prop("value");
					var remark = $('#remark').prop("value");
					if (name == '') {
						cando = false;
						layer.msg('请输入接口名称', {
							time: 1000
						});
					}
					if (remark == '') {
						cando = false;
						layer.msg('请输入接口描述', {
							time: 1000
						});
					}
					if (cando) {
						$.ajax({
							type: 'post',
							url:"../php/interface/save.php",
							data: {name: name, remark: remark, id: infoobj.F_id},
							dataType: "json",
							context: this,
							success: function (result) {
								layer.close(index);
								TUI.env.currPlugin.searchAll();
							}
						});
					}
					return false;
				},
				success: function (index, layero) {
					$('#name').val(infoobj.F_name);
					$('#remark').val(infoobj.F_remark);
				},
				content: '<div><form class="layui-form" style="padding-right:40px">' +
					'<div class="layui-form-item"><label class="layui-form-label">接口名称:</label><div class="layui-input-block"><input id="name" class="layui-input" autocomplete="off"></div></div>' +
					'<div class="layui-form-item"><label class="layui-form-label">接口描述:</label><div class="layui-input-block"><input id="remark" class="layui-input" autocomplete="off"></div></div>' +
					'</form>' +
					'</div>'
			});
		},
		delOne: function (key) {
			layer.confirm('是否确认删除？', {
				btn: ['取消', '确定'] //按钮
			}, function (index) {
				layer.close(index);
			}, function (index) {
				$.ajax({
					type: 'post',
					url:"../php/interface/del.php?id="+key,
					dataType: "json",
					context: this,
					success: function (result) {
						if (!result.rs) {
							layer.msg('存在接口实现不可删除!', {
								time: 1000
							});
						} else {
							layer.close(index);
							TUI.env.currPlugin.searchAll();
						}
					}
				});
			});
		},
		initView: function () {

			$("#search_time").val(new Date().Format("yyyy-MM-dd"));
			layui.use('laydate', function () {
				var laydate = layui.laydate;
				//执行一个laydate实例
				laydate.render({
					elem: '#search_time' //指定元素
					, btns: ['confirm']
				});
			});

			layui.use('form', function () {
				var form = layui.form;
				form.render();
			});
		}
	}
}
$(document).ready(function () {
	var arg = {"code": code, "fun": fun, "user": user, "app": app};
	TUI.env.currPlugin = new MainPanel("mainPanel", arg);
	TUI.env.currPlugin.init();
});