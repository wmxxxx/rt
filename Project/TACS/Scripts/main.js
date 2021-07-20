MainPanel = function (container) {
    return {
        init: function () {
            this.container = container;
            this.timer = null;
            this.isActive = false;
            if (window.parent != window) $('#mainFrame', parent.document).find('.toolbar .title').text(parent.TUI.env.sys.name);
            if (TUI.env.sys.frame == 1) {
                $('#' + this.container).html(
                    '<div class="leftbar">'
                        + '<div class="nav_1"></div>'
                        + '<div class="nav_2"></div>'
                    + '</div>'
                    + '<div id="centerPanel"></div>'
                );
            } else if (TUI.env.sys.frame == 2) {
                $('#' + this.container).html(
                    '<div class="leftbar2">'
                        + '<div class="colbar" title="收缩导航"></div>'
                        + '<div class="logo"></div>'
                        + '<div class="navPanel"></div>'
                    + '</div>'
                    + '<div id="centerPanel" style="left:160px"></div>'
                );
                $('#' + this.container).find('.colbar').bind(TUI.env.ua.clickEventDown, function (e) {
                    if ($(this).is('.exbar')) {
                        $('#' + TUI.env.main.container).find('.leftbar2').animate({ width: 160 }, 300);
                        $('#centerPanel').animate({ left: 160 }, 300);
                        $(this).animate({ left: 126 }, 300, function () {
                            var menus = $('#' + TUI.env.main.container).find('.leftbar2 .navPanel .menu');
                            $.each(menus, function (n, menu) {
                                $(menu).find('.arrow').show();
                                if ($(menu).is('.active')) {
                                    $(menu).next().show();
                                    $(menu).find('.arrow').addClass("rotate");
                                    $(menu).next().animate({
                                        height: TUI.env.main.menus[$(menu).next().attr('id')]
                                    }, 300, function () {
                                    });
                                }
                            });
                            $(this).toggleClass("exbar");
                            $(this).attr("title", "收缩导航");
                        });
                    } else {
                        $('#' + TUI.env.main.container).find('.leftbar2').animate({ width: 40 }, 300);
                        $('#centerPanel').animate({ left: 40 }, 300);
                        $(this).animate({ left: 6 }, 300, function () {
                            var menus = $('#' + TUI.env.main.container).find('.leftbar2 .navPanel .menu');
                            $.each(menus, function (n, menu) {
                                $(menu).find('.arrow').hide();
                                if ($(menu).is('.active')) {
                                    $(menu).next().animate({
                                        height: 0
                                    }, 300, function () {
                                        $(this).prev('.menu').find('.arrow').removeClass("rotate");
                                        $(this).hide();
                                    });
                                }
                            });
                            $(this).toggleClass("exbar");
                            $(this).attr("title", "展开导航");
                        });
                    }
                });
            } else if (TUI.env.sys.frame == 3) {
                $('#' + this.container).html(
                    '<div class="topbar">'
                        + '<div class="sys_name">' + TUI.env.sys.name + '</div>'
                        + '<ul class="navPanel"></ul>'
                        + '<div class="config" title="集成配置"></div>'
                        + '<div class="help" title="功能使用"></div>'
                        + '<div class="alaram">'
                            + '<div class="sum">0</div>'
                            + '<div class="tip">'
                                + '<ul>'
                                    + '<li><div class="num" style="background: #FF4931;" tag="1">0</div><a class="rank">紧急</a></li>'
                                    + '<li><div class="num" style="background: #FF8833;" tag="2">0</div><a class="rank">重要</a></li>'
                                    + '<li><div class="num" style="background: #FFD939;" tag="3">0</div><a class="rank">次要</a></li>'
                                    + '<li><div class="num" style="background: #60B0FF;" tag="4">0</div><a class="rank">提示</a></li>'
                                + '</ul>'
                            + '</div>'
                        + '</div>'
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
                this.loadAlarmSum();
                $('#' + this.container).find('.topbar .alaram li').bind(TUI.env.ua.clickEventDown, function (e) {
                    var rank = $(this).find('.num').attr('tag');
                    var num = parseInt($(this).find('.num').text());
                    if (num > 0) {
                        window.open('http://' + location.host + '/Project/' + TUI.env.sys.tag + '/?appid=' + TUI.env.sys.no + '&tag=fun_alarm_record&data=' + JSON.stringify({ 'alarmType': rank, 'alarmStatus': 0 }));
                    }
                });
                $('#' + this.container).find('.topbar .home').bind(TUI.env.ua.clickEventDown, function (e) {
                    if (top.location == self.location) {
                        window.location.href = "about:blank";
                        window.close();
                    } else {
                        if (parent.TUI.env.main && parent.TUI.env.main.open_type == '1') {
                            parent.TUI.env.main.closeSystem();
                        } else if (parent.parent.TUI.env.main && parent.parent.TUI.env.main.open_type == '2') {
                            parent.TUI.env.build.closeSystem();
                        }
                    }
                });
                $('#' + this.container).find('.topbar .config').bind(TUI.env.ua.clickEventDown, function (e) {
                    window.open('http://' + location.host + '/Project/' + TUI.env.sys.tag + '/Config/?type=' + sys_type + '&appid=' + TUI.env.sys.no);
                });
                $('#' + this.container).find('.topbar .help').bind(TUI.env.ua.clickEventDown, function (e) {
                    window.open('http://' + location.host + '/Project/' + TUI.env.sys.tag + '/Help/?type=' + sys_type + '&appid=' + TUI.env.sys.no);
                });
                $('#' + this.container).find('.topbar .alaram').bind(TUI.env.ua.clickEventDown, function (e) {
                    $(this).find('.tip').toggleClass("active");
                });
                $('#' + this.container).find('.topbar .close').bind(TUI.env.ua.clickEventDown, function (e) {
                    if (top.location == self.location) {
                        window.close();
                    } else {
                        if (parent.TUI.env.main && parent.TUI.env.main.open_type == '1') {
                            parent.TUI.env.main.closeSystem();
                        } else if (parent.parent.TUI.env.main && parent.parent.TUI.env.main.open_type == '2') {
                            parent.TUI.env.build.closeSystem();
                        }
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
            }
            $('#centerPanel').bind(TUI.env.ua.clickEventDown, { handler: this }, function (e) {
                if (TUI.env.sys.frame == 1) {
                    $('#' + e.data.handler.container).find('.leftbar').animate({ width: 44 }, 300);
                    $(this).animate({ left: 44, right: 0 }, 300);
                } else if (TUI.env.sys.frame == 3) {
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
                    if ($('#' + e.data.handler.container).find('.topbar .alaram .tip').is('.active')) {
                        $('#' + e.data.handler.container).find('.topbar .alaram .tip').toggleClass("active");
                    }
                }
            });
            if (sys_type == "1") this.initWin();
            this.loadMenu();
        },
        initWin: function () {
            $('#' + this.container).append(
                '<div class="win">'
                    + '<div class="title"><span class="title_name">菜单配置</span><div class="close" title="关闭"><img src="/Resources/images/close.png"/></div></div>'
                    + '<div class="form">'
                        + '<div class="item">菜单标识：<input id="b_menu_tag" type="text" readonly/></div>'
                        + '<div class="item">菜单名称：<input id="b_menu_name" type="text"/></div>'
                        + '<div class="item">菜单简称：<input id="b_menu_abbr" type="text"/></div>'
                        + '<div class="item">菜单徽标：<input id="b_menu_logo" name="b_menu_logo" type="file" accept="image/*" style="padding-top:2px"></div>'
                        + '<div class="item">显示序号：<input id="b_menu_index" type="text"/></div>'
                        + '<div class="item">功能分组: '
                            + '<select id="b_fun_type" class="ddl" style="color:#A9A9A9">'
                                + '<option value="" style="color:#A9A9A9" selected>功能分组...</option>'
                            + '</select>'
                        + '</div>'
                        + '<div class="item">功能名称: '
                            + '<select id="b_fun_name" class="ddl" style="color:#A9A9A9">'
                                + '<option value="" style="color:#A9A9A9" selected>功能名称...</option>'
                            + '</select>'
                        + '</div>'
                    + '</div>'
                    + '<div class="okbtn"></div>'
                    + '<div class="delbtn">删&nbsp;除</div>'
                + '</div>'
            );
            $.ajax({
                url: "/Php/build/getFunctionTypeList.php",
                type: "post",
                async: true,
                context: this,
                dataType: "json",
                success: function (resObj) {
                    for (var i = 0; i < resObj.length; i++) {
                        $('#b_fun_type').append('<option value ="' + resObj[i].F_FunctionTypeNo + '" style="color:#000000">' + resObj[i].F_FunctionTypeName + '</option>');
                    }
                }
            });
            $('#b_fun_type').bind('change', function (e) {
                if ($(this).val() == "") {
                    $(this).css({ color: '#A9A9A9' });
                    $('#b_fun_name').html('');
                    $('#b_fun_name').css({ color: '#A9A9A9' });
                    $('#b_fun_name').append('<option value="" style="color:#A9A9A9" selected>功能名称...</option>');
                } else {
                    $(this).css({ color: '#000000' });
                    TUI.env.main.loadFunction($(this).val(), '');
                }
            });
            $('#b_fun_name').bind('change', function (e) {
                if ($(this).val() == "") {
                    $(this).css({ color: '#A9A9A9' });
                } else {
                    $(this).css({ color: '#000000' });
                }
            });
            $('#' + this.container).find('.win .title .close').bind(TUI.env.ua.clickEventDown, { handler: this }, function (e) {
                $('#' + e.data.handler.container).find('.win').fadeOut(500);
            });
            $('#' + this.container).find('.win .cancelbtn').bind(TUI.env.ua.clickEventDown, { handler: this }, function (e) {
                $('#' + e.data.handler.container).find('.win').fadeOut(500);
            });
            $('#' + this.container).find('.win .okbtn').bind(TUI.env.ua.clickEventDown, function (e) {
                if ($('#b_menu_tag').val() == "") {
                    alert('请输入菜单标识！'); return;
                }
                if ($('#b_menu_name').val() == "") {
                    alert('请输入菜单名称！'); return;
                }
                if ($('#b_menu_abbr').val() == "") {
                    alert('请输入菜单简称！'); return;
                }
                if (TUI.env.main.operFlg == '1' && TUI.env.main.typeFlg == '1') {
                    if ($('#b_menu_logo').val() == "") {
                        alert('请选择菜单徽标！'); return;
                    }
                }
                if ($('#b_menu_index').val() == "") {
                    alert('请输入显示序号！'); return;
                }
                if (TUI.env.main.typeFlg == '2') {
                    if ($('#b_fun_name').val() == "") {
                        alert('请选择关联功能！'); return;
                    }
                }
                if ($('#b_menu_logo').val() != '') {
                    $.ajaxFileUpload({
                        url: "/Php/build/uploadLogo.php?frame=" + TUI.env.sys.frame + "&protag=" + TUI.env.sys.tag + "&tag=" + $('#b_menu_tag').val(),
                        secureuri: false,
                        fileElementId: 'b_menu_logo',
                        dataType: 'xml',
                        success: function (data, status) {

                        }
                    });
                }
                var pcode = 0;
                if (TUI.env.main.typeFlg == '2') {
                    if (TUI.env.sys.frame == 1) {
                        var menus = $('#' + TUI.env.main.container).find('.leftbar .nav_1 .menu');
                        $.each(menus, function (n, menu) {
                            if ($(menu).is('.active')) {
                                pcode = $(menu).attr('id').split('_')[0];
                            }
                        });
                    } else if (TUI.env.sys.frame == 2) {
                        var menus = $('#' + TUI.env.main.container).find('.leftbar2 .navPanel .menu');
                        $.each(menus, function (n, menu) {
                            if ($(menu).is('.active')) {
                                pcode = $(menu).attr('id').split('_')[0];
                            }
                        });
                    } else if (TUI.env.sys.frame == 3) {
                        var menus = $('#' + TUI.env.main.container).find('.leftbar3 .navPanel .menu');
                        $.each(menus, function (n, menu) {
                            if ($(menu).is('.active')) {
                                pcode = $(menu).attr('id').split('_')[0];
                            }
                        });
                        menus = $('#' + TUI.env.main.container).find('.topbar .navPanel .menu');
                        $.each(menus, function (n, menu) {
                            if ($(menu).is('.active')) {
                                pcode = $(menu).attr('id').split('_')[0];
                            }
                        });
                    }
                }

                $.ajax({
                    url: "/Php/build/menuOperate.php",
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
                        abbr: $('#b_menu_abbr').val(),
                        index: $('#b_menu_index').val(),
                        position: TUI.env.main.posFlg,
                        parent: pcode,
                        fun: $('#b_fun_name').val()
                    },
                    success: function (resObj) {
                        $(this).parent().fadeOut(500);
                        if (TUI.env.main.typeFlg == '1') {
                            $('#centerPanel').trigger(TUI.env.ua.clickEventDown);
                            TUI.env.main.loadMenu();
                        } else if (TUI.env.main.typeFlg == '2') {
                            if (TUI.env.sys.frame == 1) {
                                var menus = $('#' + TUI.env.main.container).find('.leftbar .nav_1 .menu');
                                $.each(menus, function (n, menu) {
                                    if ($(menu).is('.active')) {
                                        TUI.env.main.loadSubMenu($(menu).attr('id').split('_')[0]);
                                    }
                                });
                            } else if (TUI.env.sys.frame == 2) {
                                var menus = $('#' + TUI.env.main.container).find('.leftbar2 .navPanel .menu');
                                $.each(menus, function (n, menu) {
                                    if ($(menu).is('.active')) {
                                        TUI.env.main.loadMenuByParent($(menu).attr('id').split('_')[0]);
                                    }
                                });
                            } else if (TUI.env.sys.frame == 3) {
                                var menus = $('#' + TUI.env.main.container).find('.leftbar3 .navPanel .menu');
                                $.each(menus, function (n, menu) {
                                    if ($(menu).is('.active')) {
                                        TUI.env.main.loadMenuByParent($(menu).attr('id').split('_')[0]);
                                    }
                                });
                                menus = $('#' + TUI.env.main.container).find('.topbar .navPanel .menu');
                                $.each(menus, function (n, menu) {
                                    if ($(menu).is('.active')) {
                                        TUI.env.main.loadMenuByParent($(menu).attr('id').split('_')[0]);
                                    }
                                });
                            }
                        }
                    }
                });
            });
            $('#' + this.container).find('.win .delbtn').bind(TUI.env.ua.clickEventDown, function (e) {
                if (TUI.env.main.typeFlg == '1' && !TUI.env.main.item.leaf) {
                    alert('请先删除子菜单！'); return;
                }
                if (confirm('确认删除菜单配置？')) {
                    $.ajax({
                        url: "/Php/build/menuOperate.php",
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
                            tag: $('#b_menu_tag').val(),
                            fun: $('#b_fun_name').val(),
                            frame: TUI.env.sys.frame
                        },
                        success: function (resObj) {
                            $(this).parent().fadeOut(500);
                            if (TUI.env.main.typeFlg == '1') {
                                $('#centerPanel').trigger(TUI.env.ua.clickEventDown);
                                TUI.env.main.loadMenu();
                            } else if (TUI.env.main.typeFlg == '2') {
                                if (TUI.env.sys.frame == 1) {
                                    var menus = $('#' + TUI.env.main.container).find('.leftbar .nav_1 .menu');
                                    $.each(menus, function (n, menu) {
                                        if ($(menu).is('.active')) {
                                            TUI.env.main.loadSubMenu($(menu).attr('id').split('_')[0]);
                                        }
                                    });
                                } else if (TUI.env.sys.frame == 2) {
                                    var menus = $('#' + TUI.env.main.container).find('.leftbar2 .navPanel .menu');
                                    $.each(menus, function (n, menu) {
                                        if ($(menu).is('.active')) {
                                            TUI.env.main.loadMenuByParent($(menu).attr('id').split('_')[0]);
                                        }
                                    });
                                } else if (TUI.env.sys.frame == 3) {
                                    var menus = $('#' + TUI.env.main.container).find('.leftbar3 .navPanel .menu');
                                    $.each(menus, function (n, menu) {
                                        if ($(menu).is('.active')) {
                                            TUI.env.main.loadMenuByParent($(menu).attr('id').split('_')[0]);
                                        }
                                    });
                                    menus = $('#' + TUI.env.main.container).find('.topbar .navPanel .menu');
                                    $.each(menus, function (n, menu) {
                                        if ($(menu).is('.active')) {
                                            TUI.env.main.loadMenuByParent($(menu).attr('id').split('_')[0]);
                                        }
                                    });
                                }
                            }
                        }
                    });
                }
            });
        },
        load: function () {
            var ajax1 = $.ajax({ url: "/Php/build/getSystemPlugin.php", type: "post", async: true, context: this, dataType: "json", data: { project: sys_no} });
            var ajax2 = $.ajax({ url: "/Php/build/getProjectPlugins.php", type: "post", async: true, context: this, dataType: "json", data: { project: sys_no} });
            $.when(ajax1, ajax2).done(function (resSet1, resSet2) {
                if (resSet1[0].length > 0) {
                    if (resSet1[0][0].F_GuideMode == 'html') {
                        $('#' + container).html('<iframe width="100%" height="100%" marginwidth="0" marginheight="0" frameborder="0" src="/Plugins/' + resSet1[0][0].F_PluginTag + '/?code=' + resSet1[0][0].F_PluginCode + '&fun=' + resSet1[0][0].F_FunctionCode + '&app=' + sys_no + '&user=' + TUI.env.us.code + '&env=' + resSet1[0][0].F_EnvVar + '"></iframe>');
                    } else if (resSet1[0][0].F_GuideMode == 'js') {
                        try {
                            $.include('/Plugins/' + resSet1[0][0].F_PluginTag + '/index.js');
                            $.include('/Plugins/' + resSet1[0][0].F_PluginTag + '/index.css');
                        } catch (e) {

                        }
                        eval("TUI.env.currPlugin = new " + resSet1[0][0].F_PluginTag + "('" + container + "',{ code:" + resSet1[0][0].F_PluginCode + ",fun:" + resSet1[0][0].F_FunctionCode + ",app:" + sys_no + ", user:" + TUI.env.us.code + ",env:" + (resSet1[0][0].F_EnvVar == "" ? "''" : unescape(resSet1[0][0].F_EnvVar)) + "})");
                        TUI.env.currPlugin.init();
                    }
                } else {
                    TUI.env.main.init();
                    for (var i = 0; i < resSet2[0].length; i++) {
                        try {
                            $.include('/Plugins/' + resSet2[0][i].F_PluginTag + '/index.js');
                            $.include('/Plugins/' + resSet2[0][i].F_PluginTag + '/index.css');
                        } catch (e) {
                            continue;
                        }
                    }
                    if (active_tag == '') {
                        $.ajax({
                            url: "/Php/build/getGuidePlugin.php",
                            type: "post",
                            async: true,
                            context: this,
                            dataType: "json",
                            data: {
                                project: sys_no
                            },
                            success: function (result) {
                                if (result.length > 0) {
                                    TUI.env.main.loadViewer(result[0].F_PluginCode, result[0].F_FunctionCode, result[0]);
                                }
                            }
                        });
                    } else {
                        $.ajax({
                            url: "/Php/build/getActivePlugin.php",
                            type: "post",
                            async: true,
                            context: this,
                            dataType: "json",
                            data: {
                                project: sys_no,
                                tag: active_tag
                            },
                            success: function (result) {
                                if (result.length > 0) {
                                    if (result[0].F_GuideMode == 'js') {
                                        eval("TUI.env.currPlugin = new " + result[0].F_PluginTag + "('centerPanel',{ code:" + result[0].F_PluginCode + ",fun:" + result[0].F_FunctionCode + ",param:" + JSON.stringify(param_data) + ",app:" + sys_no + ",tree:" + (result[0].F_TreeNo == "" ? "''" : result[0].F_TreeNo) + ", user:" + TUI.env.us.code + ",env:" + (result[0].F_EnvVar == "" ? "''" : unescape(result[0].F_EnvVar)) + "})");
                                        TUI.env.currPlugin.init();
                                    } else if (result[0].F_GuideMode == 'html') {
                                        $('#centerPanel').html('<iframe width="100%" height="100%" marginwidth="0" marginheight="0" frameborder="0" src="/Plugins/' + result[0].F_PluginTag + '/?code=' + result[0].F_PluginCode + '&fun=' + result[0].F_FunctionCode + '&app=' + sys_no + '&tree=' + result[0].F_TreeNo + '&data=' + escape(param_data) + '&user=' + TUI.env.us.code + '&env=' + result[0].F_EnvVar + '"></iframe>');
                                    }
                                }
                            }
                        });
                    }
                }
            });
        },
        loadFunction: function (type, value) {
            $.ajax({
                url: "/Php/build/getFunctionList.php",
                type: "post",
                async: true,
                dataType: "json",
                data: {
                    type: type
                },
                success: function (resObj) {
                    $('#b_fun_name').empty();
                    $('#b_fun_name').append('<option value="" style="color:#A9A9A9" selected>功能名称...</option>');
                    for (var i = 0; i < resObj.length; i++) {
                        $('#b_fun_name').append('<option value ="' + resObj[i].F_FunctionCode + '" style="color:#000000">' + resObj[i].F_FunctionName + '</option>');
                    }
                    if (value != 'null-null' && value != '') {
                        $('#b_fun_name').css({ color: '#000000' });
                        $('#b_fun_name').val(value);
                    } else {
                        $('#b_fun_name').css({ color: '#A9A9A9' });
                    }
                }
            });
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
                    if (TUI.env.sys.frame == 1) {
                        var menuPanel = $('#' + this.container).find('.leftbar .nav_1');
                        menuPanel.empty();
                        for (var i = 0; i < resSet.length; i++) {
                            menuPanel.append(
                                '<div id="' + resSet[i].F_MenuCode + '_' + resSet[i].F_MenuTag + '" class="menu" title="' + resSet[i].F_MenuName + '">'
                                    + '<div class="logo" style="background:url(Resources/images/nav/' + TUI.env.sys.frame + '/' + resSet[i].F_MenuTag + '.png);background-position:0px 0px"></div>'
                                    + '<div class="name">' + resSet[i].F_MenuAbbr + '</div>'
                                    + '<div class="arrow"></div>'
                                + '</div>'
                            );
                            $('#' + resSet[i].F_MenuCode + '_' + resSet[i].F_MenuTag).bind(TUI.env.ua.clickEventDown, { handler: this, record: resSet[i] }, function (e) {
                                var id = $(this).attr('id');
                                var menus = $('#' + e.data.handler.container).find('.leftbar .nav_1 .menu');
                                $.each(menus, function (n, menu) {
                                    if (id != $(menu).attr('id')) {
                                        $(menu).removeClass("active");
                                        $(menu).find('.arrow').removeClass("active");
                                        $(menu).find('.logo').css('background-position', '0px 0px');
                                        $(menu).find('.name').css('color', '#FFFFFF');
                                    }
                                });
                                $(this).addClass('active');
                                $(this).find('.logo').css('background-position', '28px 0px');
                                $(this).find('.name').css('color', '#CBD8E5');
                                if (sys_type == "1") {
                                    TUI.env.main.timer = setTimeout(function () {
                                        $("#b_menu_tag").val(e.data.record.F_MenuTag);
                                        $("#b_menu_name").val(e.data.record.F_MenuName);
                                        $("#b_menu_abbr").val(e.data.record.F_MenuAbbr);
                                        $("#b_menu_logo").val('');
                                        $("#b_menu_index").val(e.data.record.F_MenuIndex);
                                        $("#b_fun_type").val(e.data.record.F_FunctionTypeNo);
                                        TUI.env.main.loadFunction(e.data.record.F_FunctionTypeNo, e.data.record.F_FunctionCode + '-' + e.data.record.F_PluginTag);
                                        if (e.data.record.F_FunctionTypeNo != null && e.data.record.F_FunctionTypeNo != '') {
                                            $('#b_fun_type').css({ color: '#000000' });
                                        } else {
                                            $('#b_fun_type').css({ color: '#A9A9A9' });
                                        }

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
                            $('#' + resSet[i].F_MenuCode + '_' + resSet[i].F_MenuTag).bind(TUI.env.ua.clickEventUp, { handler: this, record: resSet[i] }, function (e) {
                                if (!TUI.env.main.isActive) {
                                    if (sys_type == "1") {
                                        TUI.env.main.loadSubMenu(e.data.record.F_MenuCode);
                                        $(this).parent().parent().animate({ width: 88 }, 300);
                                        $('#centerPanel').animate({ left: 88, right: -44 }, 300);
                                    } else {
                                        if (e.data.record.F_IsHasChild) {
                                            TUI.env.main.loadSubMenu(e.data.record.F_MenuCode);
                                            $(this).parent().parent().animate({ width: 88 }, 300);
                                            $('#centerPanel').animate({ left: 88, right: -44 }, 300);
                                        } else {
                                            $('#centerPanel').trigger(TUI.env.ua.clickEventDown);
                                        }
                                    }
                                    if (e.data.record.F_PluginTag != null && e.data.record.F_PluginTag != '') {
                                        TUI.env.main.loadViewer(e.data.record.F_PluginCode, e.data.record.F_FunctionCode, e.data.record);
                                        $(this).find('.arrow').addClass("active");
                                    }
                                    clearTimeout(TUI.env.main.timer);
                                } else {
                                    clearTimeout(TUI.env.main.timer);
                                    TUI.env.main.isActive = false;
                                }
                            });
                            $('#' + resSet[i].F_MenuCode + '_' + resSet[i].F_MenuTag).bind('mouseout', function (e) {
                                clearTimeout(TUI.env.main.timer);
                                TUI.env.main.isActive = false;
                            });
                            $('#' + resSet[i].F_MenuCode + '_' + resSet[i].F_MenuTag).bind('mouseover', { handler: this }, function (e) {
                                var menus = $('#' + e.data.handler.container).find('.leftbar .nav_1 .menu');
                                $.each(menus, function (n, menu) {
                                    if (!$(menu).is('.active')) {
                                        $(menu).find('.logo').css('background-position', '0px 0px');
                                        $(menu).find('.name').css('color', '#FFFFFF');
                                    }
                                });
                                $(this).find('.logo').css('background-position', '28px 0px');
                                $(this).find('.name').css('color', '#CBD8E5');
                            });
                            $('#' + resSet[i].F_MenuCode + '_' + resSet[i].F_MenuTag).bind('mouseleave', { handler: this }, function (e) {
                                var menus = $('#' + e.data.handler.container).find('.leftbar .nav_1 .menu');
                                $.each(menus, function (n, menu) {
                                    if (!$(menu).is('.active')) {
                                        $(menu).find('.logo').css('background-position', '0px 0px');
                                        $(menu).find('.name').css('color', '#FFFFFF');
                                    }
                                });
                            });
                        }
                        if (sys_type == "1") {
                            menuPanel.append('<div id="menu_plus" class="menu" title="添加一级菜单">' + '<div class="plus">+</div>' + '</div>');
                            $('#menu_plus').bind(TUI.env.ua.clickEventDown, { handler: this }, function (e) {
                                $.ajax({
                                    url: "/Php/build/getNewMenuTag.php",
                                    type: "post",
                                    async: true,
                                    context: this,
                                    dataType: "json",
                                    success: function (result) {
                                        if (result.length > 0) {
                                            $("#b_menu_tag").val('menu_' + result[0].F_MenuTag);
                                        } else {
                                            $("#b_menu_tag").val('');
                                        }
                                    }
                                });
                                var menus = $('#' + e.data.handler.container).find('.leftbar .nav_1 .menu');
                                $.each(menus, function (n, menu) {
                                    $(menu).removeClass("active");
                                    $(menu).find('.arrow').removeClass("active");
                                });
                                $(this).addClass('active');
                                $('#centerPanel').trigger(TUI.env.ua.clickEventDown);
                                $("#b_menu_name").val('');
                                $("#b_menu_abbr").val('');
                                $("#b_menu_logo").val('');
                                $("#b_menu_index").val('');
                                $("#b_fun_type").val('');
                                $("#b_fun_name").val('');
                                $('#b_fun_type').css({ color: '#A9A9A9' });
                                $('#b_fun_name').css({ color: '#A9A9A9' });
                                $('#' + TUI.env.main.container).find('.win .okbtn').html('添&nbsp;加');
                                $('#' + TUI.env.main.container).find('.win .delbtn').hide();
                                $('#' + TUI.env.main.container).find('.win').fadeIn(500);
                                TUI.env.main.operFlg = '1';
                                TUI.env.main.typeFlg = '1';
                                TUI.env.main.posFlg = 'v';
                                TUI.env.main.item = {
                                    code: null,
                                    leaf: true
                                };
                                TUI.env.main.isActive = true;
                            });
                        }
                    } else if (TUI.env.sys.frame == 2) {
                        var menuPanel = $('#' + this.container).find('.leftbar2 .navPanel');
                        menuPanel.empty();
                        for (var i = 0; i < resSet.length; i++) {
                            menuPanel.append(
                                '<div id="' + resSet[i].F_MenuCode + '_' + resSet[i].F_MenuTag + '" class="menu" title="' + resSet[i].F_MenuName + '">'
                                    + '<div class="ul">'
                                        + '<div class="icon" style="background:url(Resources/images/nav/' + TUI.env.sys.frame + '/' + resSet[i].F_MenuTag + '.png);background-position:0px 0px"></div>'
                                        + '<div class="name">' + resSet[i].F_MenuName + '</div>'
                                    + '</div>'
                                    + (resSet[i].F_IsHasChild == 1 || sys_type == "1" ? '<div class="arrow"></div>' : '')
                                + '</div>'
                                + (resSet[i].F_IsHasChild == 1 || sys_type == "1" ? '<div id="' + resSet[i].F_MenuCode + '_ul" class="itemul"></div>' : '')
                            );
                            $('#' + resSet[i].F_MenuCode + '_' + resSet[i].F_MenuTag).bind(TUI.env.ua.clickEventDown, { handler: this, record: resSet[i] }, function (e) {
                                var id = $(this).attr('id');
                                var menus = $('#' + e.data.handler.container).find('.leftbar2 .navPanel .menu');
                                $.each(menus, function (n, menu) {
                                    if (id != $(menu).attr('id') && $(menu).is('.active')) {
                                        $(menu).removeClass("active");
                                        $(menu).next('.itemul').animate({
                                            height: 0
                                        }, 300, function () {
                                            $(this).prev('.menu').find('.arrow').removeClass("rotate");
                                            $(this).hide();
                                        });
                                    }
                                });
                                $(this).addClass('active');
                                if (sys_type == "1") {
                                    TUI.env.main.timer = setTimeout(function () {
                                        $("#b_menu_tag").val(e.data.record.F_MenuTag);
                                        $("#b_menu_name").val(e.data.record.F_MenuName);
                                        $("#b_menu_abbr").val(e.data.record.F_MenuAbbr);
                                        $("#b_menu_logo").val('');
                                        $("#b_menu_index").val(e.data.record.F_MenuIndex);
                                        $("#b_fun_type").val(e.data.record.F_FunctionTypeNo);
                                        TUI.env.main.loadFunction(e.data.record.F_FunctionTypeNo, e.data.record.F_FunctionCode + '-' + e.data.record.F_PluginTag);
                                        if (e.data.record.F_FunctionTypeNo != null && e.data.record.F_FunctionTypeNo != '') {
                                            $('#b_fun_type').css({ color: '#000000' });
                                        } else {
                                            $('#b_fun_type').css({ color: '#A9A9A9' });
                                        }

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
                            $('#' + resSet[i].F_MenuCode + '_' + resSet[i].F_MenuTag).bind(TUI.env.ua.clickEventUp, { handler: this, record: resSet[i] }, function (e) {
                                if (!TUI.env.main.isActive) {
                                    if (e.data.record.F_IsHasChild == 1 || sys_type == "1") {
                                        if ($(this).next('.itemul').is(":hidden")) {
                                            $(this).next('.itemul').show();
                                            $(this).next('.itemul').animate({
                                                height: TUI.env.main.menus[$(this).next().attr('id')]
                                            }, 300, function () {
                                                $(this).prev('.menu').find('.arrow').addClass("rotate");
                                            });
                                        } else {
                                            $(this).next('.itemul').animate({
                                                height: 0
                                            }, 300, function () {
                                                $(this).prev('.menu').find('.arrow').removeClass("rotate");
                                                $(this).hide();
                                            });
                                        }
                                    }
                                    if (e.data.record.F_PluginTag != null && e.data.record.F_PluginTag != '') {
                                        TUI.env.main.loadViewer(e.data.record.F_PluginCode, e.data.record.F_FunctionCode, e.data.record);
                                    }
                                    clearTimeout(TUI.env.main.timer);
                                } else {
                                    clearTimeout(TUI.env.main.timer);
                                    TUI.env.main.isActive = false;
                                }
                            });
                            $('#' + resSet[i].F_MenuCode + '_' + resSet[i].F_MenuTag).bind('mouseout', function (e) {
                                clearTimeout(TUI.env.main.timer);
                                TUI.env.main.isActive = false;
                            });
                        }
                        if (sys_type == "1") {
                            menuPanel.append(
                                '<div id="menu_plus" class="menu">'
                                    + '<div class="ul"><div class="plus" title="添加一级菜单">+</div></div>'
                                + '</div>'
                            );
                            $('#menu_plus').bind(TUI.env.ua.clickEventDown, { handler: this }, function (e) {
                                $.ajax({
                                    url: "/Php/build/getNewMenuTag.php",
                                    type: "post",
                                    async: true,
                                    context: this,
                                    dataType: "json",
                                    success: function (result) {
                                        if (result.length > 0) {
                                            $("#b_menu_tag").val('menu_' + result[0].F_MenuTag);
                                        } else {
                                            $("#b_menu_tag").val('');
                                        }
                                    }
                                });
                                var menus = $('#' + e.data.handler.container).find('.leftbar2 .navPanel .menu');
                                $.each(menus, function (n, menu) {
                                    $(menu).removeClass("active");
                                });
                                $(this).addClass('active');
                                $("#b_menu_name").val('');
                                $("#b_menu_abbr").val('');
                                $("#b_menu_logo").val('');
                                $("#b_menu_index").val('');
                                $("#b_fun_type").val('');
                                $("#b_fun_name").val('');
                                $('#b_fun_type').css({ color: '#A9A9A9' });
                                $('#b_fun_name').css({ color: '#A9A9A9' });
                                $('#' + TUI.env.main.container).find('.win .okbtn').html('添&nbsp;加');
                                $('#' + TUI.env.main.container).find('.win .delbtn').hide();
                                $('#' + TUI.env.main.container).find('.win').fadeIn(500);
                                TUI.env.main.operFlg = '1';
                                TUI.env.main.typeFlg = '1';
                                TUI.env.main.posFlg = 'v';
                                TUI.env.main.item = {
                                    code: null,
                                    leaf: true
                                };
                                TUI.env.main.isActive = true;
                            });
                        }
                        this.loadMenuByParent();
                    } else if (TUI.env.sys.frame == 3) {
                        var menuPanel = $('#' + this.container).find('.leftbar3 .navPanel');
                        var topPanel = $('#' + this.container).find('.topbar .navPanel');
                        menuPanel.empty();
                        topPanel.empty();
                        for (var i = 0; i < resSet.length; i++) {
                            if (resSet[i].F_MenuPosition == 'v') {
                                menuPanel.append(
                                    '<li id="' + resSet[i].F_MenuCode + '_' + resSet[i].F_MenuTag + '" class="menu" title="' + resSet[i].F_MenuName + '">'
                                        + '<div class="text" style="background:url(Resources/images/nav/' + TUI.env.sys.frame + '/' + resSet[i].F_MenuTag + '.png) no-repeat;background-position: center left 6px;">' + resSet[i].F_MenuName + '</div>'
                                        + '<div class="side"></div>'
                                        + (resSet[i].F_IsHasChild == 1 || sys_type == "1" ? '<div class="arrow"></div>' : '')
                                    + '</li>'
                                    + (resSet[i].F_IsHasChild == 1 || sys_type == "1" ? '<ul id="' + resSet[i].F_MenuCode + '_ul" class="item"></ul>' : '')
                                );
                            } else if (resSet[i].F_MenuPosition == 'h') {
                                topPanel.append(
                                    '<li id="' + resSet[i].F_MenuCode + '_' + resSet[i].F_MenuTag + '" class="menu" title="' + resSet[i].F_MenuName + '">'
                                //+ '<div class="text" style="background:url(Resources/images/nav/' + TUI.env.sys.frame + '/' + resSet[i].F_MenuTag + '.png) no-repeat;background-position: center left 6px;">' + resSet[i].F_MenuName + '</div>'
                                        + '<div class="text">' + resSet[i].F_MenuName + '</div>'
                                        + '<div class="side"></div>'
                                        + (resSet[i].F_IsHasChild == 1 || sys_type == "1" ? '<div class="arrow"></div>' : '')
                                        + (resSet[i].F_IsHasChild == 1 || sys_type == "1" ? '<ul id="' + resSet[i].F_MenuCode + '_ul" class="item"></ul>' : '')
                                    + '</li>'
                                );
                            }

                            $('#' + resSet[i].F_MenuCode + '_' + resSet[i].F_MenuTag).bind(TUI.env.ua.clickEventDown, { handler: this, record: resSet[i] }, function (e) {
                                if (sys_type == "1") {
                                    TUI.env.main.timer = setTimeout(function () {
                                        $("#b_menu_tag").val(e.data.record.F_MenuTag);
                                        $("#b_menu_name").val(e.data.record.F_MenuName);
                                        $("#b_menu_abbr").val(e.data.record.F_MenuAbbr);
                                        $("#b_menu_logo").val('');
                                        $("#b_menu_index").val(e.data.record.F_MenuIndex);
                                        $("#b_fun_type").val(e.data.record.F_FunctionTypeNo);
                                        TUI.env.main.loadFunction(e.data.record.F_FunctionTypeNo, e.data.record.F_FunctionCode + '-' + e.data.record.F_PluginTag);
                                        if (e.data.record.F_FunctionTypeNo != null && e.data.record.F_FunctionTypeNo != '') {
                                            $('#b_fun_type').css({ color: '#000000' });
                                        } else {
                                            $('#b_fun_type').css({ color: '#A9A9A9' });
                                        }

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
                            $('#' + resSet[i].F_MenuCode + '_' + resSet[i].F_MenuTag).bind(TUI.env.ua.clickEventUp, { handler: this, record: resSet[i] }, function (e) {
                                var id = $(this).attr('id');
                                var menus = $('#' + e.data.handler.container).find('.leftbar3 .navPanel .menu');
                                $.each(menus, function (n, menu) {
                                    if (id != $(menu).attr('id') && $(menu).is('.active')) {
                                        $(menu).removeClass("active");
                                        $(menu).find('.side').hide();

                                        if ($(menu).next().length > 0 && $(menu).next()[0].tagName == 'UL') {
                                            $(menu).next().animate({
                                                height: 0
                                            }, 300, function () {
                                                $(this).prev('.menu').find('.arrow').removeClass("rotate");
                                                $(this).hide();
                                            });
                                        }
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
                                if (!TUI.env.main.isActive) {
                                    if (e.data.record.F_IsHasChild == 1 || sys_type == "1") {
                                        if (e.data.record.F_MenuPosition == 'v') {
                                            if ($(this).next().length > 0 && $(this).next()[0].tagName == 'UL') {
                                                if ($(this).next().is(":hidden")) {
                                                    $(this).next().show();
                                                    $(this).next().animate({
                                                        height: TUI.env.main.menus[$(this).next().attr('id')]
                                                    }, 300, function () {
                                                        $(this).prev('.menu').find('.arrow').addClass("rotate");
                                                    });
                                                } else {
                                                    $(this).next().animate({
                                                        height: 0
                                                    }, 300, function () {
                                                        $(this).prev('.menu').find('.arrow').removeClass("rotate");
                                                        $(this).hide();
                                                    });
                                                }
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
                                    if (e.data.record.F_PluginTag != null && e.data.record.F_PluginTag != '') {
                                        TUI.env.main.loadViewer(e.data.record.F_PluginCode, e.data.record.F_FunctionCode, e.data.record);
                                    }
                                    clearTimeout(TUI.env.main.timer);
                                } else {
                                    clearTimeout(TUI.env.main.timer);
                                    TUI.env.main.isActive = false;
                                }
                            });
                            $('#' + resSet[i].F_MenuCode + '_' + resSet[i].F_MenuTag).bind('mouseout', function (e) {
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
                            topPanel.append(
                                '<li id="top_menu_plus" class="menu">'
                                    + '<div class="text"><div class="plus" title="添加一级菜单">+</div></div>'
                                + '</li>'
                            );
                            $('#menu_plus').bind(TUI.env.ua.clickEventDown, { handler: this }, function (e) {
                                $.ajax({
                                    url: "/Php/build/getNewMenuTag.php",
                                    type: "post",
                                    async: true,
                                    context: this,
                                    dataType: "json",
                                    success: function (result) {
                                        if (result.length > 0) {
                                            $("#b_menu_tag").val('menu_' + result[0].F_MenuTag);
                                        } else {
                                            $("#b_menu_tag").val('');
                                        }
                                    }
                                });
                                var menus = $('#' + e.data.handler.container).find('.leftbar3 .navPanel .menu');
                                $.each(menus, function (n, menu) {
                                    $(menu).removeClass("active");
                                });
                                $(this).addClass('active');
                                $("#b_menu_name").val('');
                                $("#b_menu_abbr").val('');
                                $("#b_menu_logo").val('');
                                $("#b_menu_index").val('');
                                $("#b_fun_type").val('');
                                $("#b_fun_name").val('');
                                $('#b_fun_type').css({ color: '#A9A9A9' });
                                $('#b_fun_name').css({ color: '#A9A9A9' });
                                $('#' + TUI.env.main.container).find('.win .okbtn').html('添&nbsp;加');
                                $('#' + TUI.env.main.container).find('.win .delbtn').hide();
                                $('#' + TUI.env.main.container).find('.win').fadeIn(500);
                                TUI.env.main.operFlg = '1';
                                TUI.env.main.typeFlg = '1';
                                TUI.env.main.posFlg = 'v';
                                TUI.env.main.item = {
                                    code: null,
                                    leaf: true
                                };
                                TUI.env.main.isActive = true;
                            });
                            $('#top_menu_plus').bind(TUI.env.ua.clickEventDown, { handler: this }, function (e) {
                                $.ajax({
                                    url: "/Php/build/getNewMenuTag.php",
                                    type: "post",
                                    async: true,
                                    context: this,
                                    dataType: "json",
                                    success: function (result) {
                                        if (result.length > 0) {
                                            $("#b_menu_tag").val('menu_' + result[0].F_MenuTag);
                                        } else {
                                            $("#b_menu_tag").val('');
                                        }
                                    }
                                });
                                var menus = $('#' + e.data.handler.container).find('.topbar .navPanel .menu');
                                $.each(menus, function (n, menu) {
                                    $(menu).removeClass("active");
                                });
                                $(this).addClass('active');
                                $("#b_menu_name").val('');
                                $("#b_menu_abbr").val('');
                                $("#b_menu_logo").val('');
                                $("#b_menu_index").val('');
                                $("#b_fun_type").val('');
                                $("#b_fun_name").val('');
                                $('#b_fun_type').css({ color: '#A9A9A9' });
                                $('#b_fun_name').css({ color: '#A9A9A9' });
                                $('#' + TUI.env.main.container).find('.win .okbtn').html('添&nbsp;加');
                                $('#' + TUI.env.main.container).find('.win .delbtn').hide();
                                $('#' + TUI.env.main.container).find('.win').fadeIn(500);
                                TUI.env.main.operFlg = '1';
                                TUI.env.main.typeFlg = '1';
                                TUI.env.main.posFlg = 'h';
                                TUI.env.main.item = {
                                    code: null,
                                    leaf: true
                                };
                                TUI.env.main.isActive = true;
                            });
                        }
                        this.loadMenuByParent();
                    }
                }
            });
        },
        loadSubMenu: function (menu) {
            $.ajax({
                url: "/Php/build/getProjectSubMenu.php",
                type: "post",
                async: true,
                context: this,
                dataType: "json",
                data: {
                    project: TUI.env.sys.no,
                    menu: menu
                },
                success: function (resSet) {
                    var menuPanel = $('#' + this.container).find('.leftbar .nav_2');
                    menuPanel.empty();
                    for (var i = 0; i < resSet.length; i++) {
                        menuPanel.append(
                            '<div id="' + resSet[i].F_MenuCode + '_' + resSet[i].F_MenuTag + '" class="menu" title="' + resSet[i].F_MenuName + '">'
                                + '<div class="logo" style="background:url(Resources/images/nav/' + TUI.env.sys.frame + '/' + resSet[i].F_MenuTag + '.png);background-position:0px 0px"></div>'
                                + '<div class="name">' + resSet[i].F_MenuAbbr + '</div>'
                            + '</div>'
                        );
                        $('#' + resSet[i].F_MenuCode + '_' + resSet[i].F_MenuTag).bind(TUI.env.ua.clickEventDown, { handler: this, record: resSet[i] }, function (e) {
                            if (sys_type == "1") {
                                TUI.env.main.timer = setTimeout(function () {
                                    $("#b_menu_tag").val(e.data.record.F_MenuTag);
                                    $("#b_menu_name").val(e.data.record.F_MenuName);
                                    $("#b_menu_abbr").val(e.data.record.F_MenuAbbr);
                                    $("#b_menu_logo").val('');
                                    $("#b_menu_index").val(e.data.record.F_MenuIndex);
                                    $("#b_fun_type").val(e.data.record.F_FunctionTypeNo);
                                    TUI.env.main.loadFunction(e.data.record.F_FunctionTypeNo, e.data.record.F_FunctionCode + '-' + e.data.record.F_PluginTag);
                                    if (e.data.record.F_FunctionTypeNo != null && e.data.record.F_FunctionTypeNo != '') {
                                        $('#b_fun_type').css({ color: '#000000' });
                                    } else {
                                        $('#b_fun_type').css({ color: '#A9A9A9' });
                                    }
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
                        $('#' + resSet[i].F_MenuCode + '_' + resSet[i].F_MenuTag).bind(TUI.env.ua.clickEventUp, { handler: this, record: resSet[i] }, function (e) {
                            if (!TUI.env.main.isActive) {
                                var menus = $('#' + e.data.handler.container).find('.leftbar .nav_1 .menu');
                                $.each(menus, function (n, menu) {
                                    if ($(menu).is('.active')) {
                                        $(menu).find('.arrow').addClass("active");
                                    } else {
                                        $(menu).find('.arrow').removeClass("active");
                                    }
                                });
                                if (e.data.record.F_PluginTag != null && e.data.record.F_PluginTag != '') {
                                    TUI.env.main.loadViewer(e.data.record.F_PluginCode, e.data.record.F_FunctionCode, e.data.record);
                                }
                                $('#centerPanel').trigger(TUI.env.ua.clickEventDown);
                                clearTimeout(TUI.env.main.timer);
                            } else {
                                clearTimeout(TUI.env.main.timer);
                                TUI.env.main.isActive = false;
                            }
                        });
                        $('#' + resSet[i].F_MenuCode + '_' + resSet[i].F_MenuTag).bind('mouseout', function (e) {
                            clearTimeout(TUI.env.main.timer);
                            TUI.env.main.isActive = false;
                        });
                        $('#' + resSet[i].F_MenuCode + '_' + resSet[i].F_MenuTag).bind('mouseover', { handler: this }, function (e) {
                            var menus = $('#' + e.data.handler.container).find('.leftbar .nav_2 .menu');
                            $.each(menus, function (n, menu) {
                                $(menu).find('.logo').css('background-position', '0px 0px');
                                $(menu).find('.name').css('color', '#A9B0BD');
                            });
                            $(this).find('.logo').css('background-position', '28px 0px');
                            $(this).find('.name').css('color', '#017BC2');
                        });
                    }
                    if (sys_type == "1") {
                        menuPanel.append('<div id="submenu_plus" class="menu" title="添加二级菜单">' + '<div class="plus">+</div>' + '</div>');
                        $('#submenu_plus').bind(TUI.env.ua.clickEventDown, { handler: this }, function (e) {
                            $.ajax({
                                url: "/Php/build/getNewMenuTag.php",
                                type: "post",
                                async: true,
                                context: this,
                                dataType: "json",
                                success: function (result) {
                                    if (result.length > 0) {
                                        $("#b_menu_tag").val('submenu_' + result[0].F_MenuTag);
                                    } else {
                                        $("#b_menu_tag").val('');
                                    }
                                }
                            });
                            $("#b_menu_name").val('');
                            $("#b_menu_abbr").val('');
                            $("#b_menu_logo").val('');
                            $("#b_menu_index").val('');
                            $("#b_fun_type").val('');
                            $("#b_fun_name").val('');
                            $('#b_fun_type').css({ color: '#A9A9A9' });
                            $('#b_fun_name').css({ color: '#A9A9A9' });
                            $('#' + TUI.env.main.container).find('.win .okbtn').html('添&nbsp;加');
                            $('#' + TUI.env.main.container).find('.win .delbtn').hide();
                            $('#' + TUI.env.main.container).find('.win').fadeIn(500);
                            TUI.env.main.operFlg = '1';
                            TUI.env.main.typeFlg = '2';
                            TUI.env.main.posFlg = 'v';
                            TUI.env.main.item = {
                                code: null,
                                leaf: true
                            };
                            TUI.env.main.isActive = true;
                        });
                    }
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
                    if (TUI.env.sys.frame == 2) {
                        for (var i = 0; i < resSet.length; i++) {
                            $('#' + resSet[i].F_ParentCode + '_ul').append(
                                '<div id="' + resSet[i].F_MenuCode + '_' + resSet[i].F_MenuTag + '" class="item" title="' + resSet[i].F_MenuName + '">'
                                    + '<div class="v1"></div>'
                                    + '<div class="h1"></div>'
                                    + '<div class="name">' + resSet[i].F_MenuName + '</div>'
                                    + '<div class="v2"></div>'
                                + '</div>'
                            );
                            $('#' + resSet[i].F_MenuCode + '_' + resSet[i].F_MenuTag).bind(TUI.env.ua.clickEventDown, { handler: this, record: resSet[i] }, function (e) {
                                if (sys_type == "1") {
                                    TUI.env.main.timer = setTimeout(function () {
                                        $("#b_menu_tag").val(e.data.record.F_MenuTag);
                                        $("#b_menu_name").val(e.data.record.F_MenuName);
                                        $("#b_menu_abbr").val(e.data.record.F_MenuAbbr);
                                        $("#b_menu_logo").val('');
                                        $("#b_menu_index").val(e.data.record.F_MenuIndex);
                                        $("#b_fun_type").val(e.data.record.F_FunctionTypeNo);
                                        TUI.env.main.loadFunction(e.data.record.F_FunctionTypeNo, e.data.record.F_FunctionCode + '-' + e.data.record.F_PluginTag);
                                        if (e.data.record.F_FunctionTypeNo != null && e.data.record.F_FunctionTypeNo != '') {
                                            $('#b_fun_type').css({ color: '#000000' });
                                        } else {
                                            $('#b_fun_type').css({ color: '#A9A9A9' });
                                        }
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
                            $('#' + resSet[i].F_MenuCode + '_' + resSet[i].F_MenuTag).bind(TUI.env.ua.clickEventUp, { handler: this, record: resSet[i] }, function (e) {
                                if (!TUI.env.main.isActive) {
                                    var menus = $('#' + e.data.handler.container).find('.leftbar2 .navPanel .itemul .item .name');
                                    $.each(menus, function (n, menu) {
                                        if ($(menu).is('.active')) {
                                            $(menu).removeClass("active");
                                        }
                                    });
                                    $(this).find('.name').addClass("active");
                                    if (e.data.record.F_PluginTag != null && e.data.record.F_PluginTag != '') {
                                        TUI.env.main.loadViewer(e.data.record.F_PluginCode, e.data.record.F_FunctionCode, e.data.record);
                                    }
                                    clearTimeout(TUI.env.main.timer);
                                } else {
                                    clearTimeout(TUI.env.main.timer);
                                    TUI.env.main.isActive = false;
                                }
                            });
                            $('#' + resSet[i].F_MenuCode + '_' + resSet[i].F_MenuTag).bind('mouseout', function (e) {
                                clearTimeout(TUI.env.main.timer);
                                TUI.env.main.isActive = false;
                            });
                        }
                        if (sys_type == "1") {
                            var menus = $('#' + TUI.env.main.container).find('.leftbar2 .navPanel .itemul');
                            $.each(menus, function (n, menu) {
                                if ($('#' + $(menu).attr('id') + '_plus').length == 0) {
                                    $(menu).append(
                                        '<div id="' + $(menu).attr('id') + '_plus" class="item" title="添加二级菜单">'
                                            + '<div class="v1"></div>'
                                            + '<div class="h1"></div>'
                                            + '<div class="name" style="font-size: 20px;">+</div>'
                                        + '</div>'
                                    );
                                    $('#' + $(menu).attr('id') + '_plus').bind(TUI.env.ua.clickEventDown, function (e) {
                                        $.ajax({
                                            url: "/Php/build/getNewMenuTag.php",
                                            type: "post",
                                            async: true,
                                            context: this,
                                            dataType: "json",
                                            success: function (result) {
                                                if (result.length > 0) {
                                                    $("#b_menu_tag").val('submenu_' + result[0].F_MenuTag);
                                                } else {
                                                    $("#b_menu_tag").val('');
                                                }
                                            }
                                        });
                                        $("#b_menu_name").val('');
                                        $("#b_menu_abbr").val('');
                                        $("#b_menu_logo").val('');
                                        $("#b_menu_index").val('');
                                        $("#b_fun_type").val('');
                                        $("#b_fun_name").val('');
                                        $('#b_fun_type').css({ color: '#A9A9A9' });
                                        $('#b_fun_name').css({ color: '#A9A9A9' });
                                        $('#' + TUI.env.main.container).find('.win .okbtn').html('添&nbsp;加');
                                        $('#' + TUI.env.main.container).find('.win .delbtn').hide();
                                        $('#' + TUI.env.main.container).find('.win').fadeIn(500);
                                        TUI.env.main.operFlg = '1';
                                        TUI.env.main.typeFlg = '2';
                                        TUI.env.main.posFlg = 'v';
                                        TUI.env.main.item = {
                                            code: null,
                                            leaf: true
                                        };
                                        TUI.env.main.isActive = true;
                                    });
                                }
                            });
                        } else {
                            var menus = $('#' + TUI.env.main.container).find('.leftbar2 .navPanel .itemul');
                            $.each(menus, function (n, menu) {
                                $(menu).find('.item:last').children().last().remove();
                            });
                        }
                        var menus = $('#' + TUI.env.main.container).find('.leftbar2 .navPanel .itemul');
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
                    } else if (TUI.env.sys.frame == 3) {
                        for (var i = 0; i < resSet.length; i++) {
                            $('#' + resSet[i].F_ParentCode + '_ul').append(
                                '<li id="' + resSet[i].F_MenuCode + '_' + resSet[i].F_MenuTag + '" class="submenu">' + resSet[i].F_MenuName + '</li>'
                            );
                            $('#' + resSet[i].F_MenuCode + '_' + resSet[i].F_MenuTag).bind(TUI.env.ua.clickEventDown, { handler: this, record: resSet[i] }, function (e) {
                                if (sys_type == "1") {
                                    TUI.env.main.timer = setTimeout(function () {
                                        $("#b_menu_tag").val(e.data.record.F_MenuTag);
                                        $("#b_menu_name").val(e.data.record.F_MenuName);
                                        $("#b_menu_abbr").val(e.data.record.F_MenuAbbr);
                                        $("#b_menu_logo").val('');
                                        $("#b_menu_index").val(e.data.record.F_MenuIndex);
                                        $("#b_fun_type").val(e.data.record.F_FunctionTypeNo);
                                        TUI.env.main.loadFunction(e.data.record.F_FunctionTypeNo, e.data.record.F_FunctionCode + '-' + e.data.record.F_PluginTag);
                                        if (e.data.record.F_FunctionTypeNo != null && e.data.record.F_FunctionTypeNo != '') {
                                            $('#b_fun_type').css({ color: '#000000' });
                                        } else {
                                            $('#b_fun_type').css({ color: '#A9A9A9' });
                                        }
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
                            $('#' + resSet[i].F_MenuCode + '_' + resSet[i].F_MenuTag).bind(TUI.env.ua.clickEventUp, { handler: this, record: resSet[i] }, function (e) {
                                if (!TUI.env.main.isActive) {
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
                                    if (e.data.record.F_PluginTag != null && e.data.record.F_PluginTag != '') {
                                        TUI.env.main.loadViewer(e.data.record.F_PluginCode, e.data.record.F_FunctionCode, e.data.record);
                                    }
                                    clearTimeout(TUI.env.main.timer);
                                } else {
                                    clearTimeout(TUI.env.main.timer);
                                    TUI.env.main.isActive = false;
                                }
                            });
                            $('#' + resSet[i].F_MenuCode + '_' + resSet[i].F_MenuTag).bind('mouseout', function (e) {
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
                                        $.ajax({
                                            url: "/Php/build/getNewMenuTag.php",
                                            type: "post",
                                            async: true,
                                            context: this,
                                            dataType: "json",
                                            success: function (result) {
                                                if (result.length > 0) {
                                                    $("#b_menu_tag").val('submenu_' + result[0].F_MenuTag);
                                                } else {
                                                    $("#b_menu_tag").val('');
                                                }
                                            }
                                        });
                                        $("#b_menu_name").val('');
                                        $("#b_menu_abbr").val('');
                                        $("#b_menu_logo").val('');
                                        $("#b_menu_index").val('');
                                        $("#b_fun_type").val('');
                                        $("#b_fun_name").val('');
                                        $('#b_fun_type').css({ color: '#A9A9A9' });
                                        $('#b_fun_name').css({ color: '#A9A9A9' });
                                        $('#' + TUI.env.main.container).find('.win .okbtn').html('添&nbsp;加');
                                        $('#' + TUI.env.main.container).find('.win .delbtn').hide();
                                        $('#' + TUI.env.main.container).find('.win').fadeIn(500);
                                        TUI.env.main.operFlg = '1';
                                        TUI.env.main.typeFlg = '2';
                                        TUI.env.main.posFlg = 'v';
                                        TUI.env.main.item = {
                                            code: null,
                                            leaf: true
                                        };
                                        TUI.env.main.isActive = true;
                                    });
                                }
                            });
                            menus = $('#' + TUI.env.main.container).find('.topbar .navPanel .item');
                            $.each(menus, function (n, menu) {
                                if ($('#' + $(menu).attr('id') + '_plus').length == 0) {
                                    $(menu).append(
                                        '<li id="' + $(menu).attr('id') + '_plus" class="submenu" style="font-size: 20px;" title="添加二级菜单">+</li>'
                                    );
                                    $('#' + $(menu).attr('id') + '_plus').bind(TUI.env.ua.clickEventDown, function (e) {
                                        $.ajax({
                                            url: "/Php/build/getNewMenuTag.php",
                                            type: "post",
                                            async: true,
                                            context: this,
                                            dataType: "json",
                                            success: function (result) {
                                                if (result.length > 0) {
                                                    $("#b_menu_tag").val('submenu_' + result[0].F_MenuTag);
                                                } else {
                                                    $("#b_menu_tag").val('');
                                                }
                                            }
                                        });
                                        $("#b_menu_name").val('');
                                        $("#b_menu_abbr").val('');
                                        $("#b_menu_logo").val('');
                                        $("#b_menu_index").val('');
                                        $("#b_fun_type").val('');
                                        $("#b_fun_name").val('');
                                        $('#b_fun_type').css({ color: '#A9A9A9' });
                                        $('#b_fun_name').css({ color: '#A9A9A9' });
                                        $('#' + TUI.env.main.container).find('.win .okbtn').html('添&nbsp;加');
                                        $('#' + TUI.env.main.container).find('.win .delbtn').hide();
                                        $('#' + TUI.env.main.container).find('.win').fadeIn(500);
                                        TUI.env.main.operFlg = '1';
                                        TUI.env.main.typeFlg = '2';
                                        TUI.env.main.posFlg = 'h';
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

                        if (active_tag == '') {
                            $.ajax({
                                url: "/Php/build/getGuidePlugin.php",
                                type: "post",
                                async: true,
                                context: this,
                                dataType: "json",
                                data: {
                                    project: sys_no
                                },
                                success: function (result) {
                                    if (result.length > 0) {
                                        if (result[0].F_MenuType == '1') {
                                            var menu = $('#' + result[0].F_MenuCode + '_' + result[0].F_MenuTag);
                                            menu.addClass('active');
                                            menu.find('.side').css({ 'display': 'block' });

                                            if (result[0].F_IsHasChild == 1) {
                                                if (result[0].F_MenuPosition == 'v') {
                                                    if (menu.next('ul').is(":hidden")) {
                                                        menu.next('ul').show();
                                                        menu.next('ul').animate({
                                                            height: TUI.env.main.menus[menu.next().attr('id')]
                                                        }, 300, function () {
                                                            menu.prev('.menu').find('.arrow').addClass("rotate");
                                                        });
                                                    } else {
                                                        menu.next('ul').animate({
                                                            height: 0
                                                        }, 300, function () {
                                                            menu.prev('.menu').find('.arrow').removeClass("rotate");
                                                            menu.hide();
                                                        });
                                                    }
                                                } else if (result[0].F_MenuPosition == 'h') {
                                                    if (menu.find('.item').is(":hidden")) {
                                                        menu.find('.item').show();
                                                        menu.find('.item').animate({
                                                            height: TUI.env.main.menus[menu.find('.item').attr('id')]
                                                        }, 300, function () {
                                                            menu.parent().find('.arrow').addClass("rotate");
                                                        });
                                                    } else {
                                                        menu.find('.item').animate({
                                                            height: 0
                                                        }, 300, function () {
                                                            menu.parent().find('.arrow').removeClass("rotate");
                                                            menu.hide();
                                                        });
                                                    }
                                                }
                                            }
                                        } else {
                                            var submenu = $('#' + result[0].F_MenuCode + '_' + result[0].F_MenuTag);
                                            var menu = submenu.parent().prev('.menu');
                                            menu.addClass('active');
                                            menu.find('.side').css({ 'display': 'block' });

                                            if (result[0].F_MenuPosition == 'v') {
                                                if (menu.next('ul').is(":hidden")) {
                                                    menu.next('ul').show();
                                                    menu.next('ul').animate({
                                                        height: TUI.env.main.menus[menu.next().attr('id')]
                                                    }, 300, function () {
                                                        menu.prev('.menu').find('.arrow').addClass("rotate");
                                                    });
                                                } else {
                                                    menu.next('ul').animate({
                                                        height: 0
                                                    }, 300, function () {
                                                        menu.prev('.menu').find('.arrow').removeClass("rotate");
                                                        menu.hide();
                                                    });
                                                }
                                            } else if (result[0].F_MenuPosition == 'h') {
                                                if (menu.find('.item').is(":hidden")) {
                                                    menu.find('.item').show();
                                                    menu.find('.item').animate({
                                                        height: TUI.env.main.menus[menu.find('.item').attr('id')]
                                                    }, 300, function () {
                                                        menu.parent().find('.arrow').addClass("rotate");
                                                    });
                                                } else {
                                                    menu.find('.item').animate({
                                                        height: 0
                                                    }, 300, function () {
                                                        menu.parent().find('.arrow').removeClass("rotate");
                                                        menu.hide();
                                                    });
                                                }
                                            }
                                            submenu.addClass('active');
                                        }
                                    }
                                }
                            });
                        } else {
                            $.ajax({
                                url: "/Php/build/getActivePlugin.php",
                                type: "post",
                                async: true,
                                context: this,
                                dataType: "json",
                                data: {
                                    project: sys_no,
                                    tag: active_tag
                                },
                                success: function (result) {
                                    if (result.length > 0) {
                                        if (result[0].F_MenuType == '1') {
                                            var menu = $('#' + result[0].F_MenuCode + '_' + result[0].F_MenuTag);
                                            menu.addClass('active');
                                            menu.find('.side').css({ 'display': 'block' });

                                            if (result[0].F_IsHasChild == 1) {
                                                if (result[0].F_MenuPosition == 'v') {
                                                    if (menu.next('ul').is(":hidden")) {
                                                        menu.next('ul').show();
                                                        menu.next('ul').animate({
                                                            height: TUI.env.main.menus[menu.next().attr('id')]
                                                        }, 300, function () {
                                                            menu.prev('.menu').find('.arrow').addClass("rotate");
                                                        });
                                                    } else {
                                                        menu.next('ul').animate({
                                                            height: 0
                                                        }, 300, function () {
                                                            menu.prev('.menu').find('.arrow').removeClass("rotate");
                                                            menu.hide();
                                                        });
                                                    }
                                                } else if (result[0].F_MenuPosition == 'h') {
                                                    if (menu.find('.item').is(":hidden")) {
                                                        menu.find('.item').show();
                                                        menu.find('.item').animate({
                                                            height: TUI.env.main.menus[menu.find('.item').attr('id')]
                                                        }, 300, function () {
                                                            menu.parent().find('.arrow').addClass("rotate");
                                                        });
                                                    } else {
                                                        menu.find('.item').animate({
                                                            height: 0
                                                        }, 300, function () {
                                                            menu.parent().find('.arrow').removeClass("rotate");
                                                            menu.hide();
                                                        });
                                                    }
                                                }
                                            }
                                        } else {
                                            var submenu = $('#' + result[0].F_MenuCode + '_' + result[0].F_MenuTag);
                                            var menu = submenu.parent().prev('.menu');
                                            menu.addClass('active');
                                            menu.find('.side').css({ 'display': 'block' });

                                            if (result[0].F_MenuPosition == 'v') {
                                                if (menu.next('ul').is(":hidden")) {
                                                    menu.next('ul').show();
                                                    menu.next('ul').animate({
                                                        height: TUI.env.main.menus[menu.next().attr('id')]
                                                    }, 300, function () {
                                                        menu.prev('.menu').find('.arrow').addClass("rotate");
                                                    });
                                                } else {
                                                    menu.next('ul').animate({
                                                        height: 0
                                                    }, 300, function () {
                                                        menu.prev('.menu').find('.arrow').removeClass("rotate");
                                                        menu.hide();
                                                    });
                                                }
                                            } else if (result[0].F_MenuPosition == 'h') {
                                                if (menu.find('.item').is(":hidden")) {
                                                    menu.find('.item').show();
                                                    menu.find('.item').animate({
                                                        height: TUI.env.main.menus[menu.find('.item').attr('id')]
                                                    }, 300, function () {
                                                        menu.parent().find('.arrow').addClass("rotate");
                                                    });
                                                } else {
                                                    menu.find('.item').animate({
                                                        height: 0
                                                    }, 300, function () {
                                                        menu.parent().find('.arrow').removeClass("rotate");
                                                        menu.hide();
                                                    });
                                                }
                                            }
                                            submenu.addClass('active');
                                        }
                                    }
                                }
                            });
                        }
                    }
                }
            });
        },
        loadViewer: function (currPId, currFId, fun) {
            try {
                if (sys_type == "1" && (fun.F_IsConfig || fun.F_EnergyConfig || fun.F_TemplateConfig || fun.F_DeviceConfig)) {
                    $('#centerPanel').html('<div class="nav"><ul></ul></div><div id="configPanel" class="config"></div><div class="switch switch_l">\<</div><div id="middlePanel" class="p_panel" style="left:242px;top:0px;border:0px"></div>');
                    if (fun.F_IsConfig) {
                        if (fun.F_ConfType == '1') {
                            $('#centerPanel').find('.nav ul').append('<li class="model_cnf">自定义对象</li>');
                        } else if (fun.F_ConfType == '2') {
                            $('#centerPanel').find('.nav ul').append('<li class="model_cnf">单原生模型</li>');
                        } else if (fun.F_ConfType == '3') {
                            $('#centerPanel').find('.nav ul').append('<li class="model_cnf">多原生模型</li>');
                        } else if (fun.F_ConfType == '4') {
                            $('#centerPanel').find('.nav ul').append('<li class="model_cnf">自定义模型</li>');
                        }
                    }
                    if (fun.F_EnergyConfig) {
                        $('#centerPanel').find('.nav ul').append('<li class="energy_cnf">能源字典</li>');
                    }
                    if (fun.F_DeviceConfig) {
                        $('#centerPanel').find('.nav ul').append('<li class="device_cnf">设备字典</li>');
                    }
                    if (fun.F_TemplateConfig) {
                        $('#centerPanel').find('.nav ul').append('<li class="tpl_cnf">设备模板</li>');
                    }
                    $('#centerPanel').find('.switch').bind(TUI.env.ua.clickEventDown, { fun: fun }, function (e) {
                        if ($(this).is('.switch_l')) {
                            $(this).animate({
                                left: 31
                            }, 300, function () {
                                $(this).removeClass("switch_l");
                                $(this).addClass("switch_r");
                                $(this).html('\>');
                            });
                            $('#configPanel').animate({ width: 0 }, 300);
                            $('#middlePanel').animate({ left: 30 }, 300);
                        } else if ($(this).is('.switch_r')) {
                            $(this).animate({
                                left: 228
                            }, 300, function () {
                                $(this).removeClass("switch_r");
                                $(this).addClass("switch_l");
                                $(this).html('\<');
                            });
                            $('#configPanel').animate({ width: 210 }, 300);
                            $('#middlePanel').animate({ left: 242 }, 300);
                        }
                    });
                    $('#centerPanel').find('.nav .model_cnf').bind(TUI.env.ua.clickEventDown, { fun: fun }, function (e) {
                        var items = $('#centerPanel').find('.nav ul li');
                        $.each(items, function (n, item) {
                            if ($(item).is('.active')) {
                                $(item).removeClass("active");
                            }
                        });
                        $(this).addClass("active");
                        $('#configPanel').empty();
                        $("#configPanel").removeClass("ztree");
                        if (e.data.fun.F_ConfType == '1') {
                            TUI.env.main.loadCustomEntity(currPId, currFId);
                        } else if (e.data.fun.F_ConfType == '2') {
                            $('#configPanel').addClass('ztree');
                            $.ajax({
                                url: "/Php/plugin/getPluginToTree.php",
                                type: "post",
                                async: true,
                                dataType: "json",
                                context: this,
                                data: {
                                    fun: currFId
                                },
                                success: function (resObj) {
                                    var setting = {
                                        check: {
                                            enable: true,
                                            chkStyle: "radio",
                                            radioType: "level"
                                        },
                                        data: {
                                            simpleData: {
                                                enable: true
                                            }
                                        },
                                        callback: {
                                            onCheck: function (e, treeId, treeNode) {
                                                $.ajax({
                                                    url: "/Php/plugin/pluginToTree.php",
                                                    type: "post",
                                                    async: true,
                                                    dataType: "json",
                                                    data: {
                                                        fun: currFId,
                                                        plugin: currPId,
                                                        tree: treeNode.id.split('-')[0],
                                                        t_type: '1',
                                                        o_type: 1
                                                    },
                                                    success: function (resObj) {

                                                    }
                                                });
                                            }
                                        }
                                    };
                                    var szNodes = [];
                                    for (var i = 0; i < resObj.length; i++) {
                                        szNodes.push({
                                            id: resObj[i].id,
                                            pId: resObj[i].pId,
                                            name: resObj[i].name,
                                            checked: resObj[i].checked == 1 ? true : false,
                                            icon: "/Resources/images/folder.svg"
                                        });
                                    }
                                    $.fn.zTree.init($("#configPanel"), setting, szNodes);
                                }
                            });
                        } else if (e.data.fun.F_ConfType == '3') {
                            $('#configPanel').addClass('ztree');
                            $.ajax({
                                url: "/Php/plugin/getPluginToTree.php",
                                type: "post",
                                async: true,
                                dataType: "json",
                                context: this,
                                data: {
                                    fun: currFId
                                },
                                success: function (resObj) {
                                    var setting = {
                                        check: {
                                            enable: true
                                        },
                                        data: {
                                            simpleData: {
                                                enable: true
                                            }
                                        },
                                        callback: {
                                            onCheck: function (e, treeId, treeNode) {
                                                $.ajax({
                                                    url: "/Php/plugin/pluginToTree.php",
                                                    type: "post",
                                                    async: true,
                                                    dataType: "json",
                                                    data: {
                                                        fun: currFId,
                                                        plugin: currPId,
                                                        tree: treeNode.id.split('-')[0],
                                                        t_type: 'N',
                                                        o_type: treeNode.checked == true ? 1 : 0
                                                    },
                                                    success: function (resObj) {

                                                    }
                                                });
                                            }
                                        }
                                    };
                                    var szNodes = [];
                                    for (var i = 0; i < resObj.length; i++) {
                                        szNodes.push({
                                            id: resObj[i].id,
                                            pId: resObj[i].pId,
                                            name: resObj[i].name,
                                            checked: resObj[i].checked == 1 ? true : false,
                                            icon: "/Resources/images/folder.svg"
                                        });
                                    }
                                    $.fn.zTree.init($("#configPanel"), setting, szNodes);
                                }
                            });
                        } else if (e.data.fun.F_ConfType == '4') {
                            $.fn.zTree.destroy("configPanel");
                            $('#configPanel').html('<div class="t_nav"><ul><li class="li_model_rule active">模型规则</li><li class="li_match_entity">匹配对象</li></ul></div><div class="t_panel"></div>');
                            $('#configPanel').find('.t_nav .li_model_rule').bind(TUI.env.ua.clickEventDown, { fun: e.data.fun }, function (e) {
                                var items = $('#configPanel').find('.t_nav ul li');
                                $.each(items, function (n, item) {
                                    if ($(item).is('.active')) {
                                        $(item).removeClass("active");
                                    }
                                });
                                $(this).addClass("active");
                                TUI.env.main.loadTplRule(currPId, currFId);
                            });
                            $('#configPanel').find('.t_nav .li_match_entity').bind(TUI.env.ua.clickEventDown, { fun: e.data.fun }, function (e) {
                                var items = $('#configPanel').find('.t_nav ul li');
                                $.each(items, function (n, item) {
                                    if ($(item).is('.active')) {
                                        $(item).removeClass("active");
                                    }
                                });
                                $(this).addClass("active");
                                TUI.env.main.loadCustomTree(currPId, currFId);
                            });
                            TUI.env.main.loadTplRule(currPId, currFId);
                        }
                    });
                    $('#centerPanel').find('.nav .energy_cnf').bind(TUI.env.ua.clickEventDown, { fun: fun }, function (e) {
                        var items = $('#centerPanel').find('.nav ul li');
                        $.each(items, function (n, item) {
                            if ($(item).is('.active')) {
                                $(item).removeClass("active");
                            }
                        });
                        $(this).addClass("active");
                        $('#configPanel').empty();
                        $('#configPanel').addClass('ztree');
                        $.ajax({
                            url: "/Php/plugin/getPluginToEnergy.php",
                            type: "post",
                            async: true,
                            dataType: "json",
                            context: this,
                            data: {
                                fun: currFId
                            },
                            success: function (resObj) {
                                var setting = {
                                    view: {
                                        showIcon: true,
                                        nameIsHTML: true
                                    },
                                    check: {
                                        enable: true
                                    },
                                    data: {
                                        simpleData: {
                                            enable: true
                                        }
                                    },
                                    callback: {
                                        onCheck: function (e, treeId, treeNode) {
                                            $.ajax({
                                                url: "/Php/plugin/pluginToEnergy.php",
                                                type: "post",
                                                async: true,
                                                dataType: "json",
                                                data: {
                                                    fun: currFId,
                                                    plugin: currPId,
                                                    tree: treeNode.id.split('-')[0],
                                                    o_type: treeNode.checked == true ? 1 : 0
                                                },
                                                success: function (resObj) {

                                                }
                                            });
                                        }
                                    }
                                };
                                var szNodes = [];
                                for (var i = 0; i < resObj.length; i++) {
                                    szNodes.push({
                                        id: resObj[i].id,
                                        pId: resObj[i].pId,
                                        name: resObj[i].pId == 0 ? ('<b>' + resObj[i].name + '</b>') : resObj[i].name,
                                        open: true,
                                        checked: resObj[i].pId == 0 ? false : (resObj[i].checked == 1 ? true : false),
                                        nocheck: resObj[i].pId == 0 ? true : false,
                                        icon: "/Resources/images/folder.svg",
                                        iconOpen: "/Resources/images/folder_open.svg",
                                        iconClose: "/Resources/images/folder.svg"
                                    });
                                }
                                $.fn.zTree.init($("#configPanel"), setting, szNodes);
                            }
                        });
                    });
                    $('#centerPanel').find('.nav .tpl_cnf').bind(TUI.env.ua.clickEventDown, { fun: fun }, function (e) {
                        var items = $('#centerPanel').find('.nav ul li');
                        $.each(items, function (n, item) {
                            if ($(item).is('.active')) {
                                $(item).removeClass("active");
                            }
                        });
                        $(this).addClass("active");
                        $('#configPanel').empty();
                        $('#configPanel').addClass('ztree');
                        $.ajax({
                            url: "/Php/plugin/getPluginToTemplate.php",
                            type: "post",
                            async: true,
                            dataType: "json",
                            context: this,
                            data: {
                                fun: currFId
                            },
                            success: function (resObj) {
                                var setting = {
                                    view: {
                                        showIcon: true,
                                        nameIsHTML: true
                                    },
                                    check: {
                                        enable: true
                                    },
                                    data: {
                                        simpleData: {
                                            enable: true
                                        }
                                    },
                                    callback: {
                                        onCheck: function (e, treeId, treeNode) {
                                            $.ajax({
                                                url: "/Php/plugin/pluginToTemplate.php",
                                                type: "post",
                                                async: true,
                                                dataType: "json",
                                                data: {
                                                    fun: currFId,
                                                    plugin: currPId,
                                                    tree: treeNode.id.split('-')[0],
                                                    o_type: treeNode.checked == true ? 1 : 0
                                                },
                                                success: function (resObj) {

                                                }
                                            });
                                        }
                                    }
                                };
                                var szNodes = [];
                                for (var i = 0; i < resObj.length; i++) {
                                    szNodes.push({
                                        id: resObj[i].id,
                                        pId: resObj[i].pId,
                                        name: resObj[i].name,
                                        open: true,
                                        checked: resObj[i].checked == 1 ? true : false,
                                        icon: "/Resources/images/folder.svg",
                                        iconOpen: "/Resources/images/folder_open.svg",
                                        iconClose: "/Resources/images/folder.svg"
                                    });
                                }
                                $.fn.zTree.init($("#configPanel"), setting, szNodes);
                            }
                        });
                    });
                    $('#centerPanel').find('.nav .device_cnf').bind(TUI.env.ua.clickEventDown, { fun: fun }, function (e) {
                        var items = $('#centerPanel').find('.nav ul li');
                        $.each(items, function (n, item) {
                            if ($(item).is('.active')) {
                                $(item).removeClass("active");
                            }
                        });
                        $(this).addClass("active");
                        $('#configPanel').empty();
                        $('#configPanel').addClass('ztree');
                        $.ajax({
                            url: "/Php/plugin/getPluginToDevice.php",
                            type: "post",
                            async: true,
                            dataType: "json",
                            context: this,
                            data: {
                                fun: currFId
                            },
                            success: function (resObj) {
                                var setting = {
                                    view: {
                                        showIcon: true,
                                        nameIsHTML: true
                                    },
                                    check: {
                                        enable: true
                                    },
                                    data: {
                                        simpleData: {
                                            enable: true
                                        }
                                    },
                                    callback: {
                                        onCheck: function (e, treeId, treeNode) {
                                            $.ajax({
                                                url: "/Php/plugin/pluginToDevice.php",
                                                type: "post",
                                                async: true,
                                                dataType: "json",
                                                data: {
                                                    fun: currFId,
                                                    plugin: currPId,
                                                    tree: treeNode.id.split('-')[0],
                                                    d_type: treeNode.checked == true ? 1 : 0
                                                },
                                                success: function (resObj) {

                                                }
                                            });
                                        }
                                    }
                                };
                                var szNodes = [];
                                for (var i = 0; i < resObj.length; i++) {
                                    szNodes.push({
                                        id: resObj[i].id,
                                        pId: resObj[i].pId,
                                        name: resObj[i].name,
                                        open: true,
                                        checked: resObj[i].checked == 1 ? true : false,
                                        icon: "/Resources/images/node.svg"
                                    });
                                }
                                $.fn.zTree.init($("#configPanel"), setting, szNodes);
                            }
                        });
                    });
                    $('#centerPanel').find('.nav ul li').first().trigger(TUI.env.ua.clickEventDown);
                    if (fun.F_GuideMode == 'js') {
                        eval("TUI.env.currPlugin = new " + fun.F_PluginTag + "('centerPanel',{ code:" + fun.F_PluginCode + ",fun:" + fun.F_FunctionCode + ",app:" + sys_no + ",tree:" + (fun.F_TreeNo == "" ? "''" : fun.F_TreeNo) + ", user:" + TUI.env.us.code + ",env:" + (fun.F_EnvVar == "" ? "''" : unescape(fun.F_EnvVar)) + "})");
                        TUI.env.currPlugin.init();
                    } else if (fun.F_GuideMode == 'html') {
                        $('#middlePanel').html('<iframe width="100%" height="100%" marginwidth="0" marginheight="0" frameborder="0" src="/Plugins/' + fun.F_PluginTag + '/?code=' + fun.F_PluginCode + '&fun=' + fun.F_FunctionCode + '&app=' + sys_no + '&tree=' + fun.F_TreeNo + '&user=' + TUI.env.us.code + '&env=' + fun.F_EnvVar + '"></iframe>');
                    }
                } else {
                    if (fun.F_GuideMode == 'js') {
                        eval("TUI.env.currPlugin = new " + fun.F_PluginTag + "('centerPanel',{ code:" + fun.F_PluginCode + ",fun:" + fun.F_FunctionCode + ",app:" + sys_no + ",tree:" + (fun.F_TreeNo == "" ? "''" : fun.F_TreeNo) + ", user:" + TUI.env.us.code + ",env:" + (fun.F_EnvVar == "" ? "''" : unescape(fun.F_EnvVar)) + "})");
                        TUI.env.currPlugin.init();
                    } else if (fun.F_GuideMode == 'html') {
                        $('#centerPanel').html('<iframe width="100%" height="100%" marginwidth="0" marginheight="0" frameborder="0" src="/Plugins/' + fun.F_PluginTag + '/?code=' + fun.F_PluginCode + '&fun=' + fun.F_FunctionCode + '&app=' + sys_no + '&tree=' + fun.F_TreeNo + '&user=' + TUI.env.us.code + '&env=' + fun.F_EnvVar + '"></iframe>');
                    }
                }
            } catch (e) {
                alert(e);
            }
        },
        loadTplRule: function (currPId, currFId) {
            $('#configPanel').find('.t_panel').html('<div id="conf_stree" class="t_conf ztree"></div><div class="b_conf"><table><thead><tr><th style="width:100px;text-align:center;">对象属性</th><th style="width:100px;text-align:center;">对象类型</th></tr></thead><tbody></tbody></table></div>');
            $.ajax({
                url: "/Php/plugin/getPluginToTree.php",
                type: "post",
                async: true,
                dataType: "json",
                context: this,
                data: {
                    fun: currFId
                },
                success: function (resObj) {
                    var setting = {
                        check: {
                            enable: true,
                            chkStyle: "radio",
                            radioType: "level"
                        },
                        data: {
                            simpleData: {
                                enable: true
                            }
                        },
                        callback: {
                            onCheck: function (e, treeId, treeNode) {
                                TUI.env.main.bindTree = treeNode.id;
                                $.ajax({
                                    url: "/Php/plugin/pluginToTree.php",
                                    type: "post",
                                    async: true,
                                    dataType: "json",
                                    data: {
                                        fun: currFId,
                                        plugin: currPId,
                                        tree: TUI.env.main.bindTree.split('-')[0],
                                        t_type: '1',
                                        o_type: 1
                                    },
                                    success: function (resObj) {

                                    }
                                });
                                TUI.env.main.loadTplProperty(currPId, currFId);
                            }
                        }
                    };
                    var szNodes = [];
                    for (var i = 0; i < resObj.length; i++) {
                        szNodes.push({
                            id: resObj[i].id,
                            pId: resObj[i].pId,
                            name: resObj[i].name,
                            checked: resObj[i].checked == 1 ? true : false,
                            icon: "/Resources/images/folder.svg"
                        });
                        if (resObj[i].checked == 1) {
                            TUI.env.main.bindTree = resObj[i].id;
                            TUI.env.main.loadTplProperty(currPId, currFId);
                        }
                    }
                    $.fn.zTree.init($("#conf_stree"), setting, szNodes);
                }
            });
        },
        loadTplProperty: function (currPId, currFId) {
            $.ajax({
                url: "/Php/plugin/getEntityTplTypeList.php",
                type: "post",
                async: true,
                dataType: "json",
                data: {
                    fun: currFId,
                    plugin: currPId,
                    tree: TUI.env.main.bindTree.split('-')[0]
                },
                success: function (result) {
                    $('#configPanel').find('.b_conf table tbody').empty();
                    for (var i = 0; i < result.length; i++) {
                        $('#configPanel').find('.b_conf table tbody').append(
                            '<tr>'
            				    + '<td style="vertical-align: middle;text-align:center;">'
                                    + '<select class="ddl" style="color:#A9A9A9" id="' + result[i].F_GroupID + '">'
                                        + '<option value="" style="color:#A9A9A9" selected>属性节点...</option>'
                                    + '</select>'
                                + '</td>'
            				    + '<td style="vertical-align: middle;">' + result[i].F_GroupName + '</td>'
            			    + '</tr>'
                        )
                        $.ajax({
                            url: "/Php/plugin/getTplTypePropertyList.php",
                            type: "post",
                            async: false,
                            dataType: "json",
                            data: {
                                group: result[i].F_GroupID
                            },
                            success: function (resArray) {
                                for (var j = 0; j < resArray.length; j++) {
                                    $('#' + result[i].F_GroupID).append('<option value="' + resArray[j].F_PropertyID + '" style="color:#000000">' + resArray[j].F_PropertyName + '</option>');
                                }
                                if (result[i].F_ParentProperty != null) {
                                    $('#' + result[i].F_GroupID).val(result[i].F_ParentProperty);
                                    $('#' + result[i].F_GroupID).css({ color: '#000000' });
                                }
                            }
                        });
                    }
                    $('#configPanel').find('.b_conf table tbody select').bind('change', function (e) {
                        if ($(this).val() == "") {
                            $(this).css({ color: '#A9A9A9' });
                        } else {
                            $(this).css({ color: '#000000' });
                        }
                        var tId = $(this).attr('id');
                        $.ajax({
                            url: "/Php/plugin/pluginToCustom.php",
                            type: "post",
                            async: false,
                            dataType: "json",
                            data: {
                                fun: currFId,
                                plugin: currPId,
                                tree: TUI.env.main.bindTree.split('-')[0],
                                tId: tId,
                                pId: $(this).val()
                            },
                            success: function (result) {

                            }
                        });
                    });
                }
            });
        },
        loadCustomEntity: function (currPId, currFId) {
            $('#configPanel').html('<div class="c_tbar"><select id="cmb_entityTreeGType" class="ddl" style="color:#A9A9A9"></select></div><div id="conf_tree_panel" class="c_panel ztree"></div><div class="c_fbar"><input id="conf_model_name" class="txt" type="text"/><div class="okbtn">保存</div></div>');

            $('#cmb_entityTreeGType').bind('change', function (e) {
                if ($(this).val() == "") {
                    $(this).css({ color: '#A9A9A9' });
                    $("#conf_tree_panel").html('<div class="msg">无对象实例信息</div>');
                    $("#conf_model_name").val("");
                } else {
                    $(this).css({ color: '#000000' });
                    var tree = $(this).val().split('-')[0];
                    $.ajax({
                        url: "/Php/plugin/getEntityTreeSynNode.php",
                        type: "post",
                        async: true,
                        dataType: "json",
                        data: {
                            tree: tree,
                            fun: currFId
                        },
                        success: function (result) {
                            var setting = {
                                check: {
                                    enable: true
                                },
                                data: {
                                    simpleData: {
                                        enable: true
                                    }
                                },
                                callback: {
                                    onCheck: function (e, treeId, treeNode) {
                                        $.ajax({
                                            url: "/Php/plugin/pluginToEntity.php",
                                            type: "post",
                                            async: true,
                                            dataType: "json",
                                            data: {
                                                type: treeNode.checked == true ? 1 : 0,
                                                plugin: currPId,
                                                fun: currFId,
                                                tree: tree,
                                                node: treeNode.id
                                            },
                                            success: function (resObj) {

                                            }
                                        });
                                    }
                                }
                            };
                            var szNodes = [];
                            for (var i = 0; i < result.length; i++) {
                                szNodes.push({
                                    id: result[i].id,
                                    pId: result[i].pId,
                                    name: result[i].name,
                                    open: true,
                                    checked: result[i].checked == 1 ? true : false,
                                    icon: result[i].otype == "1" || result[i].otype == "" ? "/Resources/images/folder.svg" : (result[i].otype == "2" ? "/Resources/images/node.svg" : "/Resources/images/facility.svg")
                                });
                            }
                            $.fn.zTree.init($("#conf_tree_panel"), setting, szNodes);
                        }
                    });
                    $.ajax({
                        url: "/Php/plugin/getPluginToEntityRename.php",
                        type: "post",
                        async: true,
                        dataType: "json",
                        data: {
                            tree: tree,
                            fun: currFId
                        },
                        success: function (result) {
                            if (result.length > 0) {
                                $("#conf_model_name").val(result[0].F_EntityTreeRename);
                            } else {
                                $("#conf_model_name").val("");
                            }
                        }
                    });
                }
            });
            $.ajax({
                url: "/Php/plugin/getEntityTreeTypeddl.php",
                type: "post",
                async: false,
                dataType: "json",
                success: function (result) {
                    $('#cmb_entityTreeGType').html('');
                    $('#cmb_entityTreeGType').append('<option value="" style="color:#A9A9A9" selected>对象实例树...</option>');
                    for (var j = 0; j < result.length; j++) {
                        $('#cmb_entityTreeGType').append('<option value="' + result[j].value + '" style="color:#000000">' + result[j].name + '</option>');
                    }
                    $.ajax({
                        url: "/Php/plugin/getPluginToEntity.php",
                        type: "post",
                        async: true,
                        dataType: "json",
                        data: {
                            fun: currFId
                        },
                        success: function (resObj) {
                            if (resObj.length > 0) {
                                $('#cmb_entityTreeGType').val(resObj[0].F_EntityTreeNo);
                                $('#cmb_entityTreeGType').css({ color: '#000000' });
                                $.ajax({
                                    url: "/Php/plugin/getEntityTreeSynNode.php",
                                    type: "post",
                                    async: true,
                                    dataType: "json",
                                    data: {
                                        tree: resObj[0].F_EntityTreeNo.split('-')[0],
                                        fun: currFId
                                    },
                                    success: function (result) {
                                        var setting = {
                                            check: {
                                                enable: true
                                            },
                                            data: {
                                                simpleData: {
                                                    enable: true
                                                }
                                            },
                                            callback: {
                                                onCheck: function (e, treeId, treeNode) {
                                                    $.ajax({
                                                        url: "/Php/plugin/pluginToEntity.php",
                                                        type: "post",
                                                        async: true,
                                                        dataType: "json",
                                                        data: {
                                                            type: treeNode.checked == true ? 1 : 0,
                                                            plugin: currPId,
                                                            fun: currFId,
                                                            tree: resObj[0].F_EntityTreeNo.split('-')[0],
                                                            node: treeNode.id
                                                        },
                                                        success: function (resObj) {

                                                        }
                                                    });
                                                }
                                            }
                                        };
                                        var szNodes = [];
                                        for (var i = 0; i < result.length; i++) {
                                            szNodes.push({
                                                id: result[i].id,
                                                pId: result[i].pId,
                                                name: result[i].name,
                                                open: true,
                                                checked: result[i].checked == 1 ? true : false,
                                                icon: result[i].otype == "1" || result[i].otype == "" ? "/Resources/images/folder.svg" : (result[i].otype == "2" ? "/Resources/images/node.svg" : "/Resources/images/facility.svg")
                                            });
                                        }
                                        $.fn.zTree.init($("#conf_tree_panel"), setting, szNodes);
                                    }
                                });
                                $.ajax({
                                    url: "/Php/plugin/getPluginToEntityRename.php",
                                    type: "post",
                                    async: true,
                                    dataType: "json",
                                    data: {
                                        tree: resObj[0].F_EntityTreeNo.split('-')[0],
                                        fun: currFId
                                    },
                                    success: function (result) {
                                        if (result.length > 0) {
                                            $("#conf_model_name").val(result[0].F_EntityTreeRename);
                                        } else {
                                            $("#conf_model_name").val("");
                                        }
                                    }
                                });
                            } else {
                                $("#conf_tree_panel").html('<div class="msg">无对象实例信息</div>');
                            }
                        }
                    });
                }
            });
            $('#configPanel').find('.c_fbar .okbtn').bind(TUI.env.ua.clickEventDown, function (e) {
                $.ajax({
                    url: "/Php/plugin/renamePluginToEntity.php",
                    type: "post",
                    async: true,
                    dataType: "json",
                    data: {
                        tree: $("#cmb_entityTreeGType").val().split('-')[0],
                        fun: currFId,
                        plugin: currPId,
                        name: $("#conf_model_name").val()
                    },
                    success: function (result) {

                    }
                });
            });
        },
        loadCustomTree: function (currPId, currFId) {
            $('#configPanel').find('.t_panel').html(
                '<div id="conf_custom_check" class="t_check">'
                    + '<div class="item">勾选时：'
                        + '<input type="checkbox" id="py" style="vertical-align: middle;width: 13px;height: 14px;margin-right: 5px;" checked="checked">关联父'
                        + '<input type="checkbox" id="sy" style="vertical-align: middle;width: 13px;height: 14px;margin-right: 5px;margin-left: 10px;" checked="checked">关联子'
                    + '</div>'
                    + '<div class="item">取选时：'
                        + '<input type="checkbox" id="pn" style="vertical-align: middle;width: 13px;height: 14px;margin-right: 5px;" checked="checked">关联父'
                        + '<input type="checkbox" id="sn" style="vertical-align: middle;width: 13px;height: 14px;margin-right: 5px;margin-left: 10px;" checked="checked">关联子'
                    + '</div>'
                + '</div>'
                + '<div id="conf_custom_tree" class="t_custom ztree"></div>'
            );
            $('#conf_custom_check').find("input[type='checkbox']").bind('change', function (e) {
                var treeObj = $.fn.zTree.getZTreeObj("conf_custom_tree");
                var py = $('#py').is(':checked') ? 'p' : '';
                var sy = $('#sy').is(':checked') ? 's' : '';
                var pn = $('#pn').is(':checked') ? 'p' : '';
                var sn = $('#sn').is(':checked') ? 's' : '';
                treeObj.setting.check.chkboxType = { "Y": py + sy, "N": pn + sn };
            });
            $.ajax({
                url: "/Php/plugin/getEntityCustomTree.php",
                type: "post",
                async: true,
                dataType: "json",
                context: this,
                data: {
                    fun: currFId,
                    plugin: currPId
                },
                success: function (resObj) {
                    if (resObj.length > 0) {
                        var setting = {
                            view: {
                                showIcon: true,
                                nameIsHTML: true
                            },
                            check: {
                                enable: true,
                                chkboxType: { "Y": "ps", "N": "ps" }
                            },
                            data: {
                                simpleData: {
                                    enable: true
                                }
                            },
                            callback: {
                                onCheck: function (event, treeId, treeNode) {
                                    var treeObj = $.fn.zTree.getZTreeObj("conf_custom_tree");
                                    var nodes = treeObj.getCheckedNodes(true);
                                    var nodeArray = [];
                                    for (var i = 0; i < nodes.length; i++) {
                                        nodeArray.push(nodes[i].id);
                                    }
                                    $.ajax({
                                        url: "/Php/plugin/customToTree.php",
                                        type: "post",
                                        async: true,
                                        dataType: "json",
                                        data: {
                                            fun: currFId,
                                            plugin: currPId,
                                            tree: nodeArray.join(',')
                                        },
                                        success: function (result) {

                                        }
                                    });
                                }
                            }
                        };
                        var szNodes = [];
                        for (var i = 0; i < resObj.length; i++) {
                            szNodes.push({
                                id: resObj[i].id,
                                pId: resObj[i].pId,
                                name: resObj[i].name,
                                open: true,
                                checked: resObj[i].checked == 1 ? true : false,
                                icon: "/Resources/images/tree/" + (resObj[i].type == '1' || resObj[i].type == null ? 'folder' : (resObj[i].av == 1 ? 'meter_green' : 'meter_virtual')) + ".svg",
                                iconOpen: "/Resources/images/tree/" + (resObj[i].type == '1' || resObj[i].type == null ? 'folder_open' : (resObj[i].av == 1 ? 'meter_green' : 'meter_virtual')) + ".svg",
                                iconClose: "/Resources/images/tree/" + (resObj[i].type == '1' || resObj[i].type == null ? 'folder' : (resObj[i].av == 1 ? 'meter_green' : 'meter_virtual')) + ".svg"
                            });
                        }
                        $.fn.zTree.init($("#conf_custom_tree"), setting, szNodes);
                    } else {
                        $("#conf_custom_tree").html('<div class="msg">无对象实例信息</div>');
                    }
                }
            });
        },
        loadAlarmSum: function () {
            $.ajax({
                url: "/Php/alarm/getProjectUnconfirmed.php",
                type: "post",
                async: true,
                context: this,
                dataType: "json",
                data: {
                    project: sys_no
                },
                success: function (result) {
                    var alarm = $('#' + this.container).find('.topbar .alaram');
                    var total = 0;
                    if (result.length > 0) {
                        for (var i = 0; i < result.length; i++) {
                            alarm.find('[tag="' + result[i].F_Rank + '"]').html(result[i].F_UnconfirmedNum);
                            total += result[i].F_UnconfirmedNum;
                        }
                        alarm.find('.sum').html(total);
                        alarm.find('.sum').show();
                    }
                }
            });
        },
        activeProject: function (pro_tag, pro_no) {
            window.open('http://' + location.host + '/Project/' + pro_tag + '/?appid=' + pro_no);
        },
        activeFunction: function (fun_tag, data) {
            if (TUI.env.sys.frame == 3) {
                $.ajax({
                    url: "/Php/build/getFunctionInfo.php",
                    type: "post",
                    async: true,
                    context: this,
                    dataType: "json",
                    data: {
                        pro: sys_no,
                        fun: fun_tag
                    },
                    success: function (result) {
                        if (result.length > 0) {
                            var id = result[0].F_MenuCode + '_' + result[0].F_MenuTag;
                            var handler = $('#' + id);
                            var menus = $('#' + TUI.env.main.container).find('.leftbar3 .navPanel .menu');
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
                            menus = $('#' + TUI.env.main.container).find('.topbar .navPanel .menu');
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
                            handler.addClass('active');
                            handler.find('.side').css({ 'display': 'block' });

                            if (result[0].F_IsHasChild == 1) {
                                if (result[0].F_MenuPosition == 'v') {
                                    if (handler.next('ul').is(":hidden")) {
                                        handler.next('ul').show();
                                        handler.next('ul').animate({
                                            height: TUI.env.main.menus[handler.next().attr('id')]
                                        }, 300, function () {
                                            $(this).prev('.menu').find('.arrow').addClass("rotate");
                                        });
                                    } else {
                                        handler.next('ul').animate({
                                            height: 0
                                        }, 300, function () {
                                            $(this).prev('.menu').find('.arrow').removeClass("rotate");
                                            $(this).hide();
                                        });
                                    }
                                } else if (result[0].F_MenuPosition == 'h') {
                                    if (handler.find('.item').is(":hidden")) {
                                        handler.find('.item').show();
                                        handler.find('.item').animate({
                                            height: TUI.env.main.menus[handler.find('.item').attr('id')]
                                        }, 300, function () {
                                            $(this).parent().find('.arrow').addClass("rotate");
                                        });
                                    } else {
                                        handler.find('.item').animate({
                                            height: 0
                                        }, 300, function () {
                                            $(this).parent().find('.arrow').removeClass("rotate");
                                            $(this).hide();
                                        });
                                    }
                                }
                            }
                            if (result[0].F_PluginTag != null && result[0].F_PluginTag != '') {
                                if (result[0].F_GuideMode == 'js') {
                                    eval("TUI.env.currPlugin = new " + result[0].F_PluginTag + "('centerPanel',{ code:" + result[0].F_PluginCode + ",fun:" + result[0].F_FunctionCode + ",data:" + JSON.stringify(data) + ",app:" + sys_no + ",tree:" + (result[0].F_TreeNo == "" ? "''" : result[0].F_TreeNo) + ", user:" + TUI.env.us.code + ",env:" + (result[0].F_EnvVar == "" ? "''" : unescape(result[0].F_EnvVar)) + "})");
                                    TUI.env.currPlugin.init();
                                } else if (result[0].F_GuideMode == 'html') {
                                    $('#centerPanel').html('<iframe width="100%" height="100%" marginwidth="0" marginheight="0" frameborder="0" src="/Plugins/' + result[0].F_PluginTag + '/?code=' + result[0].F_PluginCode + '&fun=' + result[0].F_FunctionCode + '&app=' + sys_no + '&tree=' + result[0].F_TreeNo + '&data=' + escape(JSON.stringify(data)) + '&user=' + TUI.env.us.code + '&env=' + result[0].F_EnvVar + '"></iframe>');
                                }
                            }
                        }
                    }
                });
            }
        },
        activeProAndFun: function (pro_tag, pro_no, fun_tag, data) {
            window.open('http://' + location.host + '/Project/' + pro_tag + '/?appid=' + pro_no + '&tag=' + fun_tag + '&data=' + JSON.stringify(data));
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
            TUI.env.main.load();
            if (window.parent != window) {
                parent.TUI.env.sys.parent.fadeIn(500);
            }
            document.title = result2[0][0].F_ProjectName;
        } else {
            top.location.href = "/";
        }
    });
    document.body.onselectstart = document.body.oncontextmenu = function () { return false; }
});