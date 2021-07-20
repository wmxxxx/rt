MainPanel = function (container) {
	return {
		init: function () {
			this.container = container;
			$('#' + this.container).html(
				'<div class="topbar">'
					+ '<div class="home"></div>'
					+ '<div class="sys_name" style="width: 400px;">' + TUI.env.sys.name + '<span style="font-size: 20px;">《功能使用说明》</span></div>'
					+ '<ul class="navPanel" style="left: 450px;"></ul>'
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
				+ '<div id="centerPanel" style="left:160px;top:50px;"></div>'
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
			$('#centerPanel').bind(TUI.env.ua.clickEventDown, { handler: this }, function (e) {
				var menus = $('#' + e.data.handler.container).find('.topbar .navPanel .menu');
				$.each(menus, function (n, menu) {
					if ($(menu).is('.active')) {
						$(menu).find('.item').animate({
							height: 0
						}, 300, function () {
							$(this).parent().find('.arrow').removeClass("rotate");
							$(this).hide();
						});
					}
				});
			});
			if (sys_type != 1) {
				$('#centerPanel').html('<iframe id="mdframe" width="100%" height="100%" marginwidth="0" marginheight="0" frameborder="0" src="/Lib/mdviewer/index.html"></iframe>');
				$("#mdframe").on("load", function () {
					$.ajax({
						url: "/Php/build/loadProjectHelpFile.php",
						type: "post",
						async: true,
						dataType: "text",
						data: {
							s_no: TUI.env.sys.no,
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
		loadMenu: function () {
			$.ajax({
				url: "/Php/build/getProjectMenu.php",
				type: "post",
				async: true,
				context: this,
				dataType: "json",
				data: {
					project: sys_no
				},
				success: function (resSet) {
					var menuPanel = $('#' + this.container).find('.leftbar3 .navPanel');
					var topPanel = $('#' + this.container).find('.topbar .navPanel');
					menuPanel.empty();
					topPanel.empty();
					for (var i = 0; i < resSet.length; i++) {
						if (resSet[i].F_MenuPosition == 'v') {
							menuPanel.append(
								'<li id="' + resSet[i].F_MenuCode + '-' + resSet[i].F_MenuTag + '" class="menu" title="' + resSet[i].F_MenuName + '">'
									+ '<div class="text">' + resSet[i].F_MenuName + '</div>'
									+ '<div class="side"></div>'
									+ (resSet[i].F_IsHasChild == 1 ? '<div class="arrow"></div>' : '')
								+ '</li>'
								+ (resSet[i].F_IsHasChild == 1 ? '<ul id="' + resSet[i].F_MenuCode + '_ul" class="item"></ul>' : '')
							);
						} else if (resSet[i].F_MenuPosition == 'h') {
							topPanel.append(
								'<li id="' + resSet[i].F_MenuCode + '-' + resSet[i].F_MenuTag + '" class="menu" title="' + resSet[i].F_MenuName + '">'
									+ '<div class="text">' + resSet[i].F_MenuName + '</div>'
									+ '<div class="side"></div>'
									+ (resSet[i].F_IsHasChild == 1 ? '<div class="arrow"></div>' : '')
									+ (resSet[i].F_IsHasChild == 1 ? '<ul id="' + resSet[i].F_MenuCode + '_ul" class="item"></ul>' : '')
								+ '</li>'
							);
						}
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
							menus = $('#' + e.data.handler.container).find('.topbar .navPanel .menu');
							$.each(menus, function (n, menu) {
								if (id != $(menu).attr('id') && $(menu).is('.active')) {
									$(menu).removeClass("active");
									$(menu).find('.side').hide();

									$(menu).find('.item').animate({
										height: 0
									}, 300, function () {
										$(this).parent().find('.arrow').removeClass("rotate");
										$(this).hide();
									});
								}
							});
							$(this).addClass('active');
							$(this).find('.side').css({ 'display': 'block' });
						});
						$('#' + resSet[i].F_MenuCode + '-' + resSet[i].F_MenuTag).bind(TUI.env.ua.clickEventUp, { handler: this, record: resSet[i] }, function (e) {
							var tag = $(this).attr('id').split('-')[1];
							if (sys_type == 1) {
								$('#centerPanel').html('<iframe id="mdframe" width="100%" height="100%" marginwidth="0" marginheight="0" frameborder="0" src="/Lib/mdeditor/index.html?s_tag=' + TUI.env.sys.tag + '&m_tag=' + tag + '&f_type=help"></iframe>');
								$("#mdframe").on("load", function () {
									$.ajax({
										url: "/Php/build/loadMenuHelpFile.php",
										type: "post",
										async: true,
										dataType: "text",
										data: {
											s_tag: TUI.env.sys.tag,
											m_tag: tag
										},
										success: function (result) {
											$("#mdframe")[0].contentWindow.$('#input').val(result);
											$("#mdframe")[0].contentWindow.$('#output').html(Mdjs.md2html(result));
										}
									});
								});
							}else{
								$("#mdframe")[0].src = $("#mdframe")[0].src.split('#')[0] + '#' + tag;
							}
							if (e.data.record.F_IsHasChild == 1) {
								if (e.data.record.F_MenuPosition == 'v') {
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
								} else if (e.data.record.F_MenuPosition == 'h') {
									if ($(this).find('.item').is(":hidden")) {
										$(this).find('.item').show();
										$(this).find('.item').animate({
											height: TUI.env.main.menus[$(this).find('.item').attr('id')]
										}, 300, function () {
											$(this).parent().find('.arrow').addClass("rotate");
										});
									} else {
										$(this).find('.item').animate({
											height: 0
										}, 300, function () {
											$(this).parent().find('.arrow').removeClass("rotate");
											$(this).hide();
										});
									}
								}
							}
						});
					}
					this.loadMenuByParent();
				}
			});
		},
		loadMenuByParent: function (parent) {
			$.ajax({
				url: "/Php/build/getProjectMenuByPId.php",
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
						$('#' + resSet[i].F_MenuCode + '-' + resSet[i].F_MenuTag).bind(TUI.env.ua.clickEventUp, { handler: this, record: resSet[i] }, function (e) {
							var menus = $('#' + e.data.handler.container).find('.leftbar3 .navPanel .item .submenu');
							$.each(menus, function (n, menu) {
								if ($(menu).is('.active')) {
									$(menu).removeClass("active");
								}
							});
							menus = $('#' + e.data.handler.container).find('.topbar .navPanel .item .submenu');
							$.each(menus, function (n, menu) {
								if ($(menu).is('.active')) {
									$(menu).removeClass("active");
								}
							});
							$(this).addClass("active");
							if (e.data.record.F_MenuPosition == 'h') {
								$(this).parent().animate({
									height: 0
								}, 300, function () {
									$(this).parent().find('.arrow').removeClass("rotate");
									$(this).hide();
								});
							}
							var tag = $(this).attr('id').split('-')[1];
							if (sys_type == 1) {
								$('#centerPanel').html('<iframe id="mdframe" width="100%" height="100%" marginwidth="0" marginheight="0" frameborder="0" src="/Lib/mdeditor/index.html?s_tag=' + TUI.env.sys.tag + '&m_tag=' + tag + '&f_type=help"></iframe>');
								$("#mdframe").on("load", function () {
									$.ajax({
										url: "/Php/build/loadMenuHelpFile.php",
										type: "post",
										async: true,
										dataType: "text",
										data: {
											s_tag: TUI.env.sys.tag,
											m_tag: tag
										},
										success: function (result) {
											$("#mdframe")[0].contentWindow.$('#input').val(result);
											$("#mdframe")[0].contentWindow.$('#output').html(Mdjs.md2html(result));
										}
									});
								});
							}else{
								$("#mdframe")[0].src = $("#mdframe")[0].src.split('#')[0] + '#' + tag;
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
					menus = $('#' + TUI.env.main.container).find('.topbar .navPanel .item');
					$.each(menus, function (n, menu) {
						if (!TUI.env.main.menus) {
							TUI.env.main.menus = [];
						}
						$(menu).css({ 'height': 'auto' });
						TUI.env.main.menus[$(menu).attr('id')] = $(menu).height();
						if (!$(menu).parent().is('.active')) {
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
			document.title = result2[0][0].F_ProjectName + '《功能使用说明》';
		} else {
			top.location.href = "/";
		}
	});
	document.body.onselectstart = document.body.oncontextmenu = function () { return false; }
});