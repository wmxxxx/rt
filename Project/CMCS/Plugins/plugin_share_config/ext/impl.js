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
				'<div class="form-group" style="float: left;"><button id="addbtn" class="layui-btn layui-btn-normal layui-btn-sm">新增设备模版</button>' +
				'</div>' +
				'<div class="form-group" style="float: left;"><button id="searchbtn" class="layui-btn layui-btn-normal layui-btn-sm">刷新列表</button>' +
				'</div>' +
				'</div>' +
				'<div id="listBtn1" style="display:none">' +
				'<div class="form-group" style="float: left;"><button id="addbtn1" class="layui-btn layui-btn-normal layui-btn-sm">新增服务</button>' +
				'</div>' +
				'<div class="form-group" style="float: left;"><button id="backbtn" class="layui-btn layui-btn-normal layui-btn-sm">返回</button>' +
				'</div>' +
				'</div>' +
				'</div>' +
				'<div style="background-color: #f2f2f2;height: 12px"></div>' +
				'<div class="tb tb1"><table id="relation-table" lay-filter="demo"></table></div>' +
				'<div class="tb tb2" style="display:none"><table id="relation-table1" lay-filter="demo1"></table></div>' +
				'</div>');

			// 全局变量
			this.selectedId = {};
			this.mblist = [];

			$('.rt-content-box').find("#searchbtn").bind("click", {handle: this, cfg: null}, function (e) {
				TUI.env.currPlugin.searchAll();
			});

			$('.rt-content-box').find("#addbtn").bind("click", {handle: this, cfg: null}, function (e) {
				TUI.env.currPlugin.addOne();
			});

			$('.rt-content-box').find("#backbtn").bind("click", {handle: this, cfg: null}, function (e) {
				TUI.env.currPlugin.back();
			});

			$('.rt-content-box').find("#addbtn1").bind("click", {handle: this, cfg: null}, function (e) {
				TUI.env.currPlugin.addFWOne();
			});

			TUI.env.currPlugin.initView();
			TUI.env.currPlugin.searchAll();
		},
		back: function () {
			$('#listBtn1').hide();
			$('#listBtn').show();
			$('.tb2').hide();
			$('.tb1').show();
		},
		gochild: function (infoobj) {
			$('#listBtn').hide();
			$('#listBtn1').show();
			$('.tb1').hide();
			$('.tb2').show();
			TUI.env.currPlugin.sid = infoobj.F_id;
			TUI.env.currPlugin.mblist = [];
			$.ajax({
				type: 'post',
				url:"../php/impl/getSelectList.php?infoid="+TUI.env.currPlugin.sid,
				dataType: "json",
				async: false,
				context: this,
				success: function (result) {
					TUI.env.currPlugin.mblist = result;
				}
			});
			TUI.env.currPlugin.searchChildAll(infoobj.F_id);
		},
		searchAll: function () {
			layui.use('table', function () {
				TUI.env.currPlugin.table = layui.table;
				TUI.env.currPlugin.table.render({
					id: 'testReload',
					elem: '#relation-table',
					height: 'full-83',
					url:"../php/impl/getList.php",
					page: false,
					cols: [[ //表头
						{field: 'F_id', title: '主键标识', align: 'center'}
						, {field: 'F_template_id', title: '设备模版标识', align: 'center'}
						, {field: 'F_template_name', title: '设备模版名称', align: 'center'}
						, {
							field: 'F_id',
							title: '操作',
							align: 'center',
							width: 160,
							toolbar: '<div><a class="btn-a btn-a-normal" lay-event="info" style="margin-top: 2px;">修改</a><a class="btn-a btn-a-normal" lay-event="detail" style="margin-top: 2px;">业务实现</a><a class="btn-a btn-a-normal" lay-event="del" style="margin-top: 2px;">删除</a></div>'
						}
					]]
				});

				TUI.env.currPlugin.table.on('tool(demo)', function (obj) {
					var data = obj.data;
					if (obj.event === 'detail') {
						TUI.env.currPlugin.gochild(data);
					} else if (obj.event === 'del') {
						TUI.env.currPlugin.delOne(data.F_id);
					} else if (obj.event === 'info') {
						TUI.env.currPlugin.xgOne(data);
					}
				});
			});
		},
		searchChildAll: function (key) {
			layui.use('table', function () {
				TUI.env.currPlugin.table = layui.table;
				TUI.env.currPlugin.table.render({
					id: 'testReload1',
					elem: '#relation-table1',
					height: 'full-83',
					url:"../php/impl/getFWList.php?infoid=" + key,
					page: false,
					cols: [[ //表头
						{field: 'face_id', title: '服务标识', align: 'center'}
						, {field: 'face_name', title: '服务名称', align: 'center'}
						, {field: 'face_remark', title: '服务描述', align: 'center'}
						, {
							field: 'F_id',
							title: '操作',
							align: 'center',
							width: 180,
							toolbar: '<div><a class="btn-a btn-a-normal" lay-event="contion" style="margin-top: 2px;">前置条件</a><a class="btn-a btn-a-normal" lay-event="config" style="margin-top: 2px;">业务配置</a><a class="btn-a btn-a-normal" lay-event="delc" style="margin-top: 2px;">删除</a></div>'
						}
					]]
				});

				TUI.env.currPlugin.table.on('tool(demo1)', function (obj) {
					var data = obj.data;
					if (obj.event === 'config') {
						TUI.env.currPlugin.config(data.F_id, data.F_info_id, data.F_face_id);
					} else if (obj.event === 'delc') {
						TUI.env.currPlugin.delOne1(data.F_id);
					} else if (obj.event === 'contion') {
						TUI.env.currPlugin.contion(data.F_id, data.F_info_id);
					}
				});
			});
		},
		contion: function (key, infoid) {
			layer.open({
				type: 1,
				title: "前置条件",
				area: ['600px', '430px'],
				shadeClose: true,
				skin: 'reatgreen-layer',
				btn: ['取消', '保存'],
				btn1: function (index, layero) {
					layer.close(index);
				},
				btn2: function (index, layero) {
					var cando = true;
					var list = [];
					$("#plancontion").find("#tmptr").each(function () {
						var tds = $(this).children();
						var a1 = tds.eq(0).find("#con1").val();
						var a2 = tds.eq(1).find("#con2").val();
						var a3 = tds.eq(2).find("#con3").val();
						if (a3 != "") {

							list.push({
								code: a1,
								con: a2,
								value: a3
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
							url:"../php/impl/saveContion.php",
							data: {mxid: key, infoid: infoid, list: json},
							dataType: "json",
							context: this,
							success: function (result) {
								layer.close(index);
								TUI.env.currPlugin.searchChildAll(TUI.env.currPlugin.sid);
							}
						});
					}
					return false;
				},
				success: function (index, layero) {

					var rs = TUI.env.currPlugin.mblist;
					$('#contb #con1').append('<option value="' + 'online' + '">' + '通信状态' + '</option>');
					for (var i = 0; i < rs.length; i++) {
						if (rs[i].type != 'WriteOnly')
							$('#contb #con1').append('<option value="' + rs[i].code + '">' + rs[i].name + '</option>');
					}

					$('#plancontion').delegate('#con1', 'change', function () {
						TUI.env.currPlugin.contionchange($(this).parent().parent());
					});

					$.ajax({
						type: 'post',
						url:"../php/impl/getContionList.php?infoid=" + key,
						dataType: "json",
						context: this,
						success: function (result) {
							if (result && result.length > 0) {
								for (var i = 0; i < result.length; i++) {
									var tmp = $("#tmptr").clone();
									$(tmp).find("#con1").val(result[i].F_code);
									$(tmp).find("#con2").val(result[i].F_con);
									TUI.env.currPlugin.contionchange(tmp);
									$(tmp).find("#con3").val(result[i].F_value);
									$("#plancontion").append(tmp);
								}
							} else {
							}
						}
					});
				},
				content: '<div><table style="display:none" id="contb">' +
					'<tr id="tmptr" style="height: 40px;">' +
					'<td><select class="rt-select layui-select" id="con1" style="width: 144px;"></select></td>' +
					'<td><select class="rt-select layui-select" id="con2" style="width: 164px;">' +
					'<option value="等于">等于</option>' +
					'<option value="大于">大于</option>' +
					'<option value="小于">小于</option>' +
					'<option value="不等于">不等于</option>' +
					'</select></td>' +
					'<td id="con3div"><input class="rt-input layui-input" placeholder="" style="width: 164px;display: initial;text-align: center;padding-left: 10;" value="" id="con3"></td>' +
					'<td><div class="layui-input-inline" style="width: auto;" onclick="TUI.env.currPlugin.delcontion(this)"><i class="layui-icon layui-icon-delete" style="font-size: 25px;"></i></div></td>' +
					'</tr>' +
					'</table>' +
					'<table style="width: calc(100% - 40px);margin: 0 20px;" id="plancontion">' +
					'<tr id="tmptrhead" style="height: 40px;font-size: 13px;">' +
					'<td><span>参数名称</span></td>' +
					'<td><span>比较条件</span></td>' +
					'<td><span>比较值</span></td>' +
					'<td style="width:50px"><div class="layui-input-inline" style="width: auto;" onclick="TUI.env.currPlugin.addcontion()"> <i class="layui-icon layui-icon-add-circle" style="font-size: 25px;"></i></div></td>' +
					'</tr>' +
					'</table>' +
					'</div>'
			});
		},
		delcontion: function (obj) {
			$(obj).parent().parent().remove();
		},
		addcontion: function () {
			var copy = $("#tmptr").clone();
			TUI.env.currPlugin.contionchange(copy);
			$("#plancontion").append(copy);
		},
		contionchange: function (copy) {
			$(copy).find("#con3div").empty();
			var code = $(copy).find("#con1").val();
			var fkv = "";
			var mb = TUI.env.currPlugin.mblist;
			for (var i = 0; i < mb.length; i++) {
				if (mb[i].code == code && mb[i].F_KV) {
					fkv = mb[i].F_KV;
				}
			}
			if (code == "online") {
				$(copy).find("#con3div").append('<select class="rt-select layui-select" id="con3" style="width: 164px;"><option value="0">离线</option><option value="1">在线</option></select>')
			} else if (fkv) {
				var selectStr = '<select class="rt-select layui-select" id="con3" style="width: 164px;">';
				var keys = fkv.split("-");
				for (var i = 0; i < keys.length; i++) {
					if (keys[i]) {
						var key = keys[i].split(":");
						selectStr += '<option value="' + key[0] + '">' + key[1] + '</option>';
					}
				}
				selectStr += '</select>';
				$(copy).find("#con3div").append(selectStr);
			} else {
				$(copy).find("#con3div").append('<input class="rt-input layui-input" placeholder="" style="width: 164px;display: initial;text-align: left;padding-left: 10;" value="" id="con3">');
			}
		},
		config: function (key, infoid, faceid) {
			$.ajax({
				type: 'post',
				url:"../php/impl/getSelectArgList.php?infoid=" + faceid,
				dataType: "json",
				async: false,
				context: this,
				success: function (result) {
					TUI.env.currPlugin.arglist = result;
				}
			});
			layer.open({
				type: 1,
				title: "业务配置",
				area: ['1000px', '585px'],
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
						var a2 = tds.eq(0).find("#tmp2").val();
						var a3 = tds.eq(1).find("#tmp3").val();
						var a4 = tds.eq(2).find("#tmp4").val();
						var a5 = tds.eq(3).find("#tmp5").val();
						var a6 = tds.eq(4).find("#tmp6").val();
						var a7 = tds.eq(5).find("#tmp7").val();

						list.push({
							code: a2,
							value: a3,
							type: a4,
							typecontent: a5,
							commandcode: a6,
							valuecode: a7
						});
					})
					if (list.length == 0) {
						cando = false;
						layer.msg('最少添加1个', {
							time: 1000
						});
					}
					var json = JSON.stringify(list);
					if (cando) {
						$.ajax({
							type: 'post',
							url:"../php/impl/saveConfig.php",
							data: {mxid: key, infoid: infoid, list: json},
							dataType: "json",
							context: this,
							success: function (result) {
								layer.close(index);
								TUI.env.currPlugin.searchChildAll(TUI.env.currPlugin.sid);
							}
						});
					}
					return false;
				},
				success: function (index, layero) {

					var rs = TUI.env.currPlugin.mblist;
					$('#tmptb #tmp2').append('<option value="' + "" + '">' + '默认(虚拟变量)' + '</option>');
					for (var i = 0; i < rs.length; i++) {
						if (rs[i].type != 'WriteOnly')
							$('#tmptb #tmp2').append('<option value="mb-' + rs[i].code + '">' + rs[i].name + '</option>');
					}
					var rs = TUI.env.currPlugin.arglist;
					for (var i = 0; i < rs.length; i++) {
						$('#tmptb #tmp2').append('<option value="arg-' + rs[i].code + '">' + rs[i].name + '</option>');
					}

					$('#planmx').delegate('#tmp2', 'change', function () {
						if ($(this).val() && $(this).val().indexOf("mb") != -1) {
							$(this).parent().parent().find("#tmp7").val($(this).val().substr($(this).val().lastIndexOf("-") + 1));
						} else if ($(this).val() && $(this).val().indexOf("arg") != -1) {
							$(this).parent().parent().find("#tmp7").val($(this).val().substr($(this).val().lastIndexOf("-") + 1));
						}
					});

					$.ajax({
						type: 'post',
						url:"../php/impl/getConfigList.php?infoid=" + key,
						dataType: "json",
						context: this,
						success: function (result) {
							if (result && result.length > 0) {
								for (var i = 0; i < result.length; i++) {
									var tmp = $("#tmptr").clone();
									$(tmp).find("#tmp2").val(result[i].F_code);
									$(tmp).find("#tmp3").val(result[i].F_value);
									$(tmp).find("#tmp4").val(result[i].F_type);
									$(tmp).find("#tmp5").val(result[i].F_typecontent);
									$(tmp).find("#tmp6").val(result[i].F_command_code);
									$(tmp).find("#tmp7").val(result[i].F_value_code);
									$("#planmx").append(tmp);
								}
							} else {
								TUI.env.currPlugin.addconfig();
								TUI.env.currPlugin.addconfig();
								TUI.env.currPlugin.addconfig();
							}
						}
					});
				},
				content: '<div><table style="display:none" id="tmptb">' +
					'<tr id="tmptr" style="height: 40px;">' +
					'<td><select class="rt-select layui-select" id="tmp2" style="width: 144px;"></select></td>' +
					'<td><input class="rt-input layui-input" placeholder="" style="width: 144px;display: initial;text-align: center;padding-left: 0;" value="" id="tmp3"></td>' +
					'<td><select class="rt-select layui-select" id="tmp4" style="width: 144px;">' +
					'<option value="no">无操作</option>' +
					'<option value="exp">表达式处理</option>' +
					//'<option value="array">转换数组</option>' +
					'<option value="time">系统时间</option>' +
					//'<option value="sxtime">生效时长</option>' +
					'</select></td>' +
					'<td><input class="rt-input layui-input" placeholder="" style="width: 144px;display: initial;text-align: center;padding-left: 0;" value="" id="tmp5"></td>' +
					'<td><input class="rt-input layui-input" placeholder="" style="width: 144px;display: initial;text-align: center;padding-left: 0;" value="" id="tmp6"></td>' +
					'<td><input class="rt-input layui-input" placeholder="" style="width: 144px;display: initial;text-align: center;padding-left: 0;" value="" id="tmp7"></td>' +
					'<td><div class="layui-input-inline" style="width: auto;" onclick="TUI.env.currPlugin.delconfig(this)"><i class="layui-icon layui-icon-delete" style="font-size: 25px;"></i></div></td>' +
					'</tr>' +
					'</table>' +
					'<table style="width: calc(100% - 40px);margin: 0 20px;" id="planmx">' +
					'<tr id="tmptrhead" style="height: 40px;font-size: 13px;">' +
					'<td><span>变量名称</span></td>' +
					'<td><span>默认值</span></td>' +
					'<td><span>处理类型</span></td>' +
					'<td><span>处理类型扩展</span></td>' +
					'<td><span>输出命令标识</span></td>' +
					'<td><span>输出变量标识</span></td>' +
					'<td style="width:50px"><div class="layui-input-inline" style="width: auto;" onclick="TUI.env.currPlugin.addconfig()"> <i class="layui-icon layui-icon-add-circle" style="font-size: 25px;"></i></div></td>' +
					'</tr>' +
					'</table>' +
					'</div>'
			});
		},
		delconfig: function (obj) {
			$(obj).parent().parent().remove();
		},
		addconfig: function () {
			var copy = $("#tmptr").clone();
			$("#planmx").append(copy);
		},
		addOne: function () {
			layer.open({
				type: 1,
				title: "添加",
				area: ['300px', '450px'],
				shadeClose: true,
				skin: 'reatgreen-layer',
				btn: ['取消', '保存'],
				btn1: function (index, layero) {
					layer.close(index);
				},
				btn2: function (index, layero) {
					var cando = true;
					var name = $('#templateid').find("option:selected").text();
					var tid = $('#templateid').val();
					if (tid == '') {
						cando = false;
						layer.msg('请选择一个设备模版', {
							time: 1000
						});
					}
					if (cando) {
						$.ajax({
							type: 'post',
							url:"../php/impl/save.php",
							data: {name: name, code: tid, id: ""},
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
					var rs = TUI.env.currPlugin.tmplist;
					for (var i = 0; i < rs.length; i++) {
						$('#templateid').append('<option value="' + rs[i].F_TemplateCode + '">' + rs[i].F_TemplateName + '</option>');
					}
					layui.use('form', function () {
						var form = layui.form;
						form.render();
					});
				},
				content: '<div><form class="layui-form" style="padding: 0 20px">' +
					'<div class="layui-form-item"><select id="templateid" type="text" style="" class="form-control"></select></div>' +
					'</form>' +
					'</div>'
			});
		},
		addFWOne: function () {
			layer.open({
				type: 1,
				title: "添加",
				area: ['300px', '450px'],
				shadeClose: true,
				skin: 'reatgreen-layer',
				btn: ['取消', '保存'],
				btn1: function (index, layero) {
					layer.close(index);
				},
				btn2: function (index, layero) {
					var cando = true;
					var ck3s = "";
					$("[name='ck3']:checked").each(function (index, element) {
						ck3s += $(this).val() + ",";
					});
					if (ck3s == "") {
						layer.msg('请选择至少一个', {
							time: 1000
						});
						return false;
					}
					if (cando) {
						$.ajax({
							type: 'post',
							url:"../php/impl/saveFW.php",
							data: {infoid: TUI.env.currPlugin.sid, faceids: ck3s},
							dataType: "json",
							context: this,
							success: function (result) {
								layer.close(index);
								TUI.env.currPlugin.searchChildAll(TUI.env.currPlugin.sid);
							}
						});
					}
					return false;
				},
				success: function (index, layero) {
					$.ajax({
						type: 'post',
						url:"../php/impl/getnoimpl.php?infoid=" + TUI.env.currPlugin.sid,
						dataType: "json",
						async: false,
						context: this,
						success: function (result) {
							for (var i = 0; i < result.length; i++) {
								$("#l1").append('<tr><td class="check"><div class="layui-input-myblock"><input type="checkbox" name="ck3" value="' + result[i].F_id + '" lay-skin="primary"></div></td><td>' + result[i].F_name + '</td></tr>');
							}
						}
					});
					layui.use('form', function () {
						var form = layui.form;
						form.render();
					});
				},
				content: '<div><form class="layui-form" style="padding: 0 20px">' +
					'<div class="layui-form-item"><div class="table-box"><table id="l1"></table></div></div>' +
					'</form>' +
					'</div>'
			});
		},
		xgOne: function (infoobj) {
			layer.open({
				type: 1,
				title: "添加",
				area: ['300px', '450px'],
				shadeClose: true,
				skin: 'reatgreen-layer',
				btn: ['取消', '保存'],
				btn1: function (index, layero) {
					layer.close(index);
				},
				btn2: function (index, layero) {
					var cando = true;
					var name = $('#templateid').find("option:selected").text();
					var tid = $('#templateid').val();
					if (tid == '') {
						cando = false;
						layer.msg('请选择一个设备模版', {
							time: 1000
						});
					}
					if (cando) {
						$.ajax({
							type: 'post',
							url:"../php/impl/save.php",
							data: {name: name, code: tid, id: infoobj.F_id},
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
					var rs = TUI.env.currPlugin.tmplist;
					for (var i = 0; i < rs.length; i++) {
						$('#templateid').append('<option value="' + rs[i].F_TemplateCode + '">' + rs[i].F_TemplateName + '</option>');
					}
					$('#templateid').val(infoobj.F_template_id);
					layui.use('form', function () {
						var form = layui.form;
						form.render();
					});
				},
				content: '<div><form class="layui-form" style="padding:0 20px">' +
					'<div class="layui-form-item"><select id="templateid" type="text" style="" class="form-control"></select></div>' +
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
					url:"../php/impl/del.php?id=" + key,
					dataType: "json",
					context: this,
					success: function (result) {
						layer.close(index);
						TUI.env.currPlugin.searchAll();
					}
				});
			});
		},
		delOne1: function (key) {
			layer.confirm('是否确认删除？', {
				btn: ['取消', '确定'] //按钮
			}, function (index) {
				layer.close(index);
			}, function (index) {
				$.ajax({
					type: 'post',
					url:"../php/impl/delChild.php?id=" + key,
					dataType: "json",
					context: this,
					success: function (result) {
						layer.close(index);
						TUI.env.currPlugin.searchChildAll(TUI.env.currPlugin.sid);
					}
				});
			});
		},
		initView: function () {

			$.ajax({
				type: 'get',
				url: "/API/Context/FunToTemplate/?fun_code="+this.fun,
				dataType: "json",
				success: function (result) {
					TUI.env.currPlugin.tmplist = result;
				}
			});

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