MainPanel = function (container) {
	return {
		init: function () {
			this.container = container;
			this.timer = null;
			this.isActive = false;
			$('#' + this.container).html(
				'<div class="topbar">'
					+ '<div class="home"></div>'
					+ '<div class="sys_name" style="width: 400px;">' + TUI.env.sys.name + '<span style="font-size: 20px;">《集成配置说明》</span></div>'
					+ '<div class="head">'
						+ '<div class="uname">' + TUI.env.us.id + '</div>'
						+ '<div class="utype">' + TUI.env.us.type_name + '</div>'
						+ '<div class="bubble"><img src="/Php/user/userHead.php?userId=' + TUI.env.us.id + '"/></div>'
					+ '</div>'
					+ '<div class="close"><div class="btn">退出</div></div>'
				+ '</div>'
				+ '<div class="leftbar3">'
					+ '<div class="colbar" title="收缩导航"></div>'
					+ '<ul class="navPanel"></ul>'
				+ '</div>'
				+ '<div id="centerPanel" style="left:160px;top:50px"></div>'
			);
			$('#' + this.container).find('.topbar .home').bind(TUI.env.ua.clickEventDown, function (e) {
				if (top.location == self.location) {
					window.close();
				}
			});
			$('#' + this.container).find('.topbar .close').bind(TUI.env.ua.clickEventDown, function (e) {
				if (top.location == self.location) {
					window.close();
				}
			});
			$('#' + this.container).find('.colbar').bind(TUI.env.ua.clickEventDown, function (e) {
				if ($(this).is('.exbar')) {
					$('#' + TUI.env.main.container).find('.leftbar3').animate({ width: 160 }, 300);
					$('#centerPanel').animate({ left: 160 }, 300);
					$(this).animate({ left: 130 }, 300, function () {
						var menus = $('#' + TUI.env.main.container).find('.leftbar3 .navPanel .menu');
						$.each(menus, function (n, menu) {
							$(menu).find('.arrow').show();
							if ($(menu).is('.active')) {
								$(menu).next('ul').show();
								$(menu).find('.arrow').addClass("rotate");
								$(menu).next('ul').animate({
									height: TUI.env.main.menus[$(menu).next().attr('id')]
								}, 300, function () {
								});
							}
						});
						$(this).toggleClass("exbar");
						$(this).attr("title", "收缩导航");
						$('#' + TUI.env.main.container).find('.leftbar3').unbind();
					});
					TUI.env.main.nav_switch = 'ex';
				} else {
					$('#' + TUI.env.main.container).find('.leftbar3').animate({ width: 44 }, 300);
					$('#centerPanel').animate({ left: 44 }, 300);
					var menus = $('#' + TUI.env.main.container).find('.leftbar3 .navPanel .menu');
					$.each(menus, function (n, menu) {
						$(menu).find('.arrow').hide();
						if ($(menu).is('.active')) {
							$(menu).next('ul').animate({
								height: 0
							}, 300, function () {
								$(this).prev('.menu').find('.arrow').removeClass("rotate");
								$(this).hide();
							});
						}
					});
					$(this).animate({ left: 12 }, 300, function () {
						$(this).toggleClass("exbar");
						$(this).attr("title", "展开导航");

						$('#' + TUI.env.main.container).find('.leftbar3').bind('mouseenter', function (e) {
							if (TUI.env.main.nav_switch == 'col') {
								$('#' + TUI.env.main.container).find('.leftbar3').animate({ width: 160 }, 300);
								$('#' + TUI.env.main.container).find('.colbar').animate({ left: 130 }, 300, function () {
									var menus = $('#' + TUI.env.main.container).find('.leftbar3 .navPanel .menu');
									$.each(menus, function (n, menu) {
										$(menu).find('.arrow').show();
										if ($(menu).is('.active')) {
											$(menu).next('ul').show();
											$(menu).find('.arrow').addClass("rotate");
											$(menu).next('ul').animate({
												height: TUI.env.main.menus[$(menu).next().attr('id')]
											}, 300, function () {
											});
										}
									});
								});
							}
						});
						$('#' + TUI.env.main.container).find('.leftbar3').bind('mouseleave', function (e) {
							if (TUI.env.main.nav_switch == 'col') {
								$('#' + TUI.env.main.container).find('.leftbar3').animate({ width: 44 }, 300);
								var menus = $('#' + TUI.env.main.container).find('.leftbar3 .navPanel .menu');
								$.each(menus, function (n, menu) {
									$(menu).find('.arrow').hide();
									if ($(menu).is('.active')) {
										$(menu).next('ul').animate({
											height: 0
										}, 300, function () {
											$(this).prev('.menu').find('.arrow').removeClass("rotate");
											$(this).hide();
										});
									}
								});
								$('#' + TUI.env.main.container).find('.colbar').animate({ left: 12 }, 300);
							}
						});
					});
					TUI.env.main.nav_switch = 'col';
				}
			});
			if (sys_type == "1") {
				$('#centerPanel').html('<iframe id="mdframe" width="100%" height="100%" marginwidth="0" marginheight="0" frameborder="0" src="/Lib/mdeditor/index.html?s_tag=' + TUI.env.sys.tag + '&f_type=config"></iframe>');
				$("#mdframe").on("load", function () {
					$.ajax({
						url: "/Php/build/loadProjectConfigFile.php",
						type: "post",
						async: true,
						dataType: "text",
						data: {
							s_tag: TUI.env.sys.tag
						},
						success: function (result) {
							$("#mdframe")[0].contentWindow.$('#input').val(result);
							$("#mdframe")[0].contentWindow.$('#output').html(Mdjs.md2html(result));
						}
					});
				});
				this.initWin();
			} else {
				$('#centerPanel').html('<iframe id="mdframe" width="100%" height="100%" marginwidth="0" marginheight="0" frameborder="0" src="/Lib/mdviewer/index.html"></iframe>');
				$("#mdframe").on("load", function () {
					$.ajax({
						url: "/Php/build/loadProjectConfigFile.php",
						type: "post",
						async: true,
						dataType: "text",
						data: {
							s_tag: TUI.env.sys.tag
						},
						success: function (result) {
							$("#mdframe")[0].contentWindow.$('#output').html(Mdjs.md2html(result));
						}
					});
				});
			}
			this.loadMenu();
		},
		initWin: function () {
			$('#' + this.container).append(
				'<div class="win" style="height: 210px;margin-left: -110px;">'
					+ '<div class="title"><span class="title_name">目录配置</span><div class="close" title="关闭"><img src="/Resources/images/close.png"/></div></div>'
					+ '<div class="form">'
						+ '<div class="item">目录标识：<input id="b_menu_tag" type="text"/></div>'
						+ '<div class="item">目录名称：<input id="b_menu_name" type="text"/></div>'
						+ '<div class="item">显示序号：<input id="b_menu_index" type="text"/></div>'
					+ '</div>'
					+ '<div class="okbtn"></div>'
					+ '<div class="delbtn">删&nbsp;除</div>'
				+ '</div>'
			);

			$('#' + this.container).find('.win .title .close').bind(TUI.env.ua.clickEventDown, { handler: this }, function (e) {
				$('#' + e.data.handler.container).find('.win').fadeOut(500);
			});
			$('#' + this.container).find('.win .cancelbtn').bind(TUI.env.ua.clickEventDown, { handler: this }, function (e) {
				$('#' + e.data.handler.container).find('.win').fadeOut(500);
			});
			$('#' + this.container).find('.win .okbtn').bind(TUI.env.ua.clickEventDown, function (e) {
				if ($('#b_menu_tag').val() == "") {
					alert('请输入目录标识！'); return;
				}
				if ($('#b_menu_name').val() == "") {
					alert('请输入目录名称！'); return;
				}
				if ($('#b_menu_index').val() == "") {
					alert('请输入显示序号！'); return;
				}

				var pcode = 0;
				if (TUI.env.main.typeFlg == '2') {
					var menus = $('#' + TUI.env.main.container).find('.leftbar3 .navPanel .menu');
					$.each(menus, function (n, menu) {
						if ($(menu).is('.active')) {
							pcode = $(menu).attr('id').split('_')[0];
						}
					});
				}

				$.ajax({
					url: "/Php/build/helpOperate.php",
					type: "post",
					async: true,
					dataType: "json",
					context: this,
					data: {
						oper: TUI.env.main.operFlg,
						type: TUI.env.main.typeFlg,
						prono: TUI.env.sys.no,
						protag: TUI.env.sys.tag,
						code: TUI.env.main.item.code,
						tag: $('#b_menu_tag').val(),
						name: $('#b_menu_name').val(),
						index: $('#b_menu_index').val(),
						parent: pcode
					},
					success: function (resObj) {
						$(this).parent().fadeOut(500);
						if (TUI.env.main.typeFlg == '1') {
							TUI.env.main.loadMenu();
						} else if (TUI.env.main.typeFlg == '2') {
							var menus = $('#' + TUI.env.main.container).find('.leftbar3 .navPanel .menu');
							$.each(menus, function (n, menu) {
								if ($(menu).is('.active')) {
									TUI.env.main.loadMenuByParent($(menu).attr('id').split('_')[0]);
								}
							});
						}
					}
				});
			});
			$('#' + this.container).find('.win .delbtn').bind(TUI.env.ua.clickEventDown, function (e) {
				if (TUI.env.main.typeFlg == '1' && !TUI.env.main.item.leaf) {
					alert('请先删除子目录！'); return;
				}
				if (confirm('确认删除目录配置？')) {
					$.ajax({
						url: "/Php/build/helpOperate.php",
						type: "post",
						async: true,
						dataType: "json",
						context: this,
						data: {
							oper: '3',
							type: TUI.env.main.typeFlg,
							prono: TUI.env.sys.no,
							protag: TUI.env.sys.tag,
							code: TUI.env.main.item.code,
							tag: $('#b_menu_tag').val()
						},
						success: function (resObj) {
							$(this).parent().fadeOut(500);
							if (TUI.env.main.typeFlg == '1') {
								TUI.env.main.loadMenu();
							} else if (TUI.env.main.typeFlg == '2') {
								var menus = $('#' + TUI.env.main.container).find('.leftbar3 .navPanel .menu');
								$.each(menus, function (n, menu) {
									if ($(menu).is('.active')) {
										TUI.env.main.loadMenuByParent($(menu).attr('id').split('_')[0]);
									}
								});
							}
						}
					});
				}
			});
		},
		loadMenu: function () {
			$.ajax({
				url: "/Php/build/getProjectHelp.php",
				type: "post",
				async: true,
				context: this,
				dataType: "json",
				data: {
					project: sys_no
				},
				success: function (resSet) {
					var menuPanel = $('#' + this.container).find('.leftbar3 .navPanel');
					menuPanel.empty();
					for (var i = 0; i < resSet.length; i++) {
						menuPanel.append(
							'<li id="' + resSet[i].F_MenuCode + '-' + resSet[i].F_MenuTag + '" class="menu" title="' + resSet[i].F_MenuName + '">'
								+ '<div class="text" style="padding-left: 30px;">' + resSet[i].F_MenuName + '</div>'
								+ '<div class="side"></div>'
								+ (resSet[i].F_IsHasChild == 1 || sys_type == "1" ? '<div class="arrow"></div>' : '')
							+ '</li>'
							+ (resSet[i].F_IsHasChild == 1 || sys_type == "1" ? '<ul id="' + resSet[i].F_MenuCode + '_ul" class="item"></ul>' : '')
						);

						$('#' + resSet[i].F_MenuCode + '-' + resSet[i].F_MenuTag).bind(TUI.env.ua.clickEventDown, { handler: this, record: resSet[i] }, function (e) {
							var id = $(this).attr('id');
							var menus = $('#' + e.data.handler.container).find('.leftbar3 .navPanel .menu');
							$.each(menus, function (n, menu) {
								if (id != $(menu).attr('id') && $(menu).is('.active')) {
									$(menu).removeClass("active");
									$(menu).find('.side').hide();

									$(menu).next('ul').animate({
										height: 0
									}, 300, function () {
										$(this).prev('.menu').find('.arrow').removeClass("rotate");
										$(this).hide();
									});
								}
							});
							$(this).addClass('active');
							$(this).find('.side').css({ 'display': 'block' });
							if (sys_type == "1") {
								TUI.env.main.timer = setTimeout(function () {
									$("#b_menu_tag").val(e.data.record.F_MenuTag);
									$("#b_menu_name").val(e.data.record.F_MenuName);
									$("#b_menu_index").val(e.data.record.F_MenuIndex);

									$('#' + TUI.env.main.container).find('.win .okbtn').html('修&nbsp;改');
									$('#' + TUI.env.main.container).find('.win .delbtn').show();
									$('#' + TUI.env.main.container).find('.win').fadeIn(500);
									TUI.env.main.operFlg = '2';
									TUI.env.main.typeFlg = '1';
									TUI.env.main.item = {
										code: e.data.record.F_MenuCode,
										leaf: !e.data.record.F_IsHasChild
									};
									TUI.env.main.isActive = true;
								}, 1500);
							}
						});
						$('#' + resSet[i].F_MenuCode + '-' + resSet[i].F_MenuTag).bind(TUI.env.ua.clickEventUp, { handler: this, record: resSet[i] }, function (e) {
							if (!TUI.env.main.isActive) {
								if (e.data.record.F_IsHasChild == 1 || sys_type == "1") {
									if ($(this).next('ul').is(":hidden")) {
										$(this).next('ul').show();
										$(this).next('ul').animate({
											height: TUI.env.main.menus[$(this).next().attr('id')]
										}, 300, function () {
											$(this).prev('.menu').find('.arrow').addClass("rotate");
										});
									} else {
										$(this).next('ul').animate({
											height: 0
										}, 300, function () {
											$(this).prev('.menu').find('.arrow').removeClass("rotate");
											$(this).hide();
										});
									}
								}
								var tag = $(this).attr('id').split('-')[1];
								$("#mdframe")[0].src = $("#mdframe")[0].src.split('#')[0] + '#' + tag;
								clearTimeout(TUI.env.main.timer);
							} else {
								clearTimeout(TUI.env.main.timer);
								TUI.env.main.isActive = false;
							}
						});
						$('#' + resSet[i].F_MenuCode + '-' + resSet[i].F_MenuTag).bind('mouseout', function (e) {
							clearTimeout(TUI.env.main.timer);
							TUI.env.main.isActive = false;
						});
					}
					if (sys_type == "1") {
						menuPanel.append(
							'<li id="menu_plus" class="menu">'
								+ '<div class="text"><div class="plus" title="添加一级菜单">+</div></div>'
							+ '</li>'
						);
						$('#menu_plus').bind(TUI.env.ua.clickEventDown, { handler: this }, function (e) {
							var menus = $('#' + e.data.handler.container).find('.leftbar3 .navPanel .menu');
							$.each(menus, function (n, menu) {
								$(menu).removeClass("active");
							});
							$(this).addClass('active');
							$("#b_menu_tag").val('');
							$("#b_menu_name").val('');
							$("#b_menu_index").val('');
							$('#' + TUI.env.main.container).find('.win .okbtn').html('添&nbsp;加');
							$('#' + TUI.env.main.container).find('.win .delbtn').hide();
							$('#' + TUI.env.main.container).find('.win').fadeIn(500);
							TUI.env.main.operFlg = '1';
							TUI.env.main.typeFlg = '1';
							TUI.env.main.item = {
								code: null,
								leaf: true
							};
							TUI.env.main.isActive = true;
						});
					}
					this.loadMenuByParent();
				}
			});
		},
		loadMenuByParent: function (parent) {
			$.ajax({
				url: "/Php/build/getProjectHelpByPId.php",
				type: "post",
				async: true,
				context: this,
				dataType: "json",
				data: {
					project: sys_no,
					parent: parent ? parent : ''
				},
				success: function (resSet) {
					for (var i = 0; i < resSet.length; i++) {
						$('#' + resSet[i].F_ParentCode + '_ul').empty();
					}
					for (var i = 0; i < resSet.length; i++) {
						$('#' + resSet[i].F_ParentCode + '_ul').append(
							'<li id="' + resSet[i].F_MenuCode + '-' + resSet[i].F_MenuTag + '" class="submenu">' + resSet[i].F_MenuName + '</li>'
						);
						$('#' + resSet[i].F_MenuCode + '-' + resSet[i].F_MenuTag).bind(TUI.env.ua.clickEventDown, { handler: this, record: resSet[i] }, function (e) {
							if (sys_type == "1") {
								TUI.env.main.timer = setTimeout(function () {
									$("#b_menu_tag").val(e.data.record.F_MenuTag);
									$("#b_menu_name").val(e.data.record.F_MenuName);
									$("#b_menu_index").val(e.data.record.F_MenuIndex);
									$('#' + TUI.env.main.container).find('.win .okbtn').html('修&nbsp;改');
									$('#' + TUI.env.main.container).find('.win .delbtn').show();
									$('#' + TUI.env.main.container).find('.win').fadeIn(500);
									TUI.env.main.operFlg = '2';
									TUI.env.main.typeFlg = '2';
									TUI.env.main.item = {
										code: e.data.record.F_MenuCode,
										leaf: true
									};
									TUI.env.main.isActive = true;
								}, 1500);
							}
						});
						$('#' + resSet[i].F_MenuCode + '-' + resSet[i].F_MenuTag).bind(TUI.env.ua.clickEventUp, { handler: this, record: resSet[i] }, function (e) {
							if (!TUI.env.main.isActive) {
								var menus = $('#' + e.data.handler.container).find('.leftbar3 .navPanel .item .submenu');
								$.each(menus, function (n, menu) {
									if ($(menu).is('.active')) {
										$(menu).removeClass("active");
									}
								});
								$(this).addClass("active");
								var tag = $(this).attr('id').split('-')[1];
								$("#mdframe")[0].src = $("#mdframe")[0].src.split('#')[0] + '#' + tag;
								clearTimeout(TUI.env.main.timer);
							} else {
								clearTimeout(TUI.env.main.timer);
								TUI.env.main.isActive = false;
							}
						});
						$('#' + resSet[i].F_MenuCode + '-' + resSet[i].F_MenuTag).bind('mouseout', function (e) {
							clearTimeout(TUI.env.main.timer);
							TUI.env.main.isActive = false;
						});
					}

					if (sys_type == "1") {
						var menus = $('#' + TUI.env.main.container).find('.leftbar3 .navPanel .item');
						$.each(menus, function (n, menu) {
							if ($('#' + $(menu).attr('id') + '_plus').length == 0) {
								$(menu).append(
									'<li id="' + $(menu).attr('id') + '_plus" class="submenu" style="font-size: 20px;" title="添加二级菜单">+</li>'
								);
								$('#' + $(menu).attr('id') + '_plus').bind(TUI.env.ua.clickEventDown, function (e) {
									$("#b_menu_tag").val('');
									$("#b_menu_name").val('');
									$("#b_menu_index").val('');
									$('#' + TUI.env.main.container).find('.win .okbtn').html('添&nbsp;加');
									$('#' + TUI.env.main.container).find('.win .delbtn').hide();
									$('#' + TUI.env.main.container).find('.win').fadeIn(500);
									TUI.env.main.operFlg = '1';
									TUI.env.main.typeFlg = '2';
									TUI.env.main.item = {
										code: null,
										leaf: true
									};
									TUI.env.main.isActive = true;
								});
							}
						});
					}
					var menus = $('#' + TUI.env.main.container).find('.leftbar3 .navPanel .item');
					$.each(menus, function (n, menu) {
						if (!TUI.env.main.menus) {
							TUI.env.main.menus = [];
						}
						$(menu).css({ 'height': 'auto' });
						TUI.env.main.menus[$(menu).attr('id')] = $(menu).height();
						if (!$(menu).prev().is('.active')) {
							$(menu).height(0);
							$(menu).hide();
						}
					});
				}
			});
		}
	};
}

$(document).ready(function () {
	var ajax1 = $.ajax({ url: "/Php/login/mysso.php?time=" + new Date().getTime(),type: 'post',dataType: "json" });
	var ajax2 = $.ajax({ url: "/Php/build/getProjectByNo.php",type: 'post',dataType: "json",data: { project: sys_no }});
	$.when(ajax1, ajax2).done(function (result1, result2) {
		if (result1[0].status) {
			TUI.env.us = result1[0]; 
			TUI.env.sys = {
				no: result2[0][0].F_ProjectNo,
				tag: result2[0][0].F_ProjectTag,
				name: result2[0][0].F_ProjectName,
				abbr: result2[0][0].F_ProjectAbbr,
				frame: result2[0][0].F_ProjectFrame
			}
			TUI.env.main = new MainPanel("mainPanel");
			TUI.env.main.init();
			document.title = result2[0][0].F_ProjectName + '《集成配置说明》';
		} else {
			top.location.href = "/";
		}
	});
	document.body.onselectstart = document.body.oncontextmenu = function () { return false; }
});