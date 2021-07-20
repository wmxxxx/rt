MainPanel = function(){
    return {
        init:function(){
            _device=[],_unit=0;
            //页面布局
            $(".mainPanel").empty().append(
                '<div id="mTree"></div>'
                +'<div class="content">'
                +'  <div class="layTop">'
                +'    <input type="text" class="form-control inText mgr10" value='+new Date().Format("yyyy-MM-dd")+' onClick=WdatePicker({dateFmt:"yyyy-MM-dd"}) readonly/>'
                +'    <input type="text" class="form-control inText mgr10" value='+new Date().Format("yyyy-MM-dd")+' onClick=WdatePicker({dateFmt:"yyyy-MM-dd"}) readonly/>'
                +'    <button class="query btn btn-primary mgr10">查询</button>'
                +'    <button class="update btn btn-primary mgr10" data-position="10" data-toggle="modal" data-target="#update">设置课表</button>'
                +'    <div class="btn-group">'
                +'       <button id="btnGroupDrop1" type="button" class="btn dropdown-toggle" data-toggle="dropdown">课表导入 <span class="caret"></span></button>'
                +'       <ul class="dropdown-menu" role="menu" aria-labelledby="btnGroupDrop1">'
                +'           <li class="download"><a href="#">下载模板</a></li>'
                +'           <li class="upload" data-toggle="modal" data-target="#upload" slot="upload"><a href="#">更新课表</a></li>'
                +'           <li class="upload" data-toggle="modal" data-target="#upload" slot="update"><a href="#">添加课表</a></li>'
                +'       </ul>'
                +'    </div>'
                +'  </div>'
                +'  <div class="layCont" style="padding-top:0;"></div>'
                +'</div>'
                +'<div id="upload" class="modal fade">'
                +'    <div class="modal-dialog modal-sm">'
                +'        <div class="modal-content">'
                +'            <div class="modal-body">'
                +'                <div class="input-group">'
                +'                    <input type="file" class="form-control file" accept=".csv">'
                +'                </div>'
                +'            </div>'
                +'            <div class="modal-footer">'
                +'                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>'
                +'                <button type="button" class="btn btn-primary sure" onClick=TUI.env.currPlugin.upload($(this).val());>确定</button>'
                +'            </div>'
                +'        </div>'
                +'    </div>'
                +'</div>'
            );
            $(".download").click(function(){
                $.ajax({
                    url:"/API/Context/FunToTree/?fun_code="+fun+"&sys_code="+app+"&user_code="+user,
                    type:"get",
                    dataType:"json",
                    success:function(result){
						window.open("/Plugins/plugin_timetable/php/list.php?id="+result[0].F_EntityTreeNo); 
                    }
                });
            });
            $(".upload").off("click").on("click",function(){
                $(".sure").val($(this).prop("slot"));
            });
            // 加载树
            $.get("/API/Context/FunToDevice/?fun_code="+fun,function(res){
                res.forEach(ele =>  _device.push(ele.F_DeviceTypeID));
                zTree = new TUI.TreePanel("mTree",{
                    plugin:code,
                    fun:fun,
                    app:app,
                    cascaded:true,
                    expand:true,
                    expand_type:"all",
                    device_id:_device.join(","),
                    checkbox:true,
                    online:true,
                    selected:true
                });
            },"json");
            // 获取控制命令
            option = "<option hidden>执行命令</option>";
            $.ajax({
                url:"/Plugins/"+local+"/php/getStrategyInfo.php",
                type:"POST",
                dataType:"json",
				data:{
					app:app
				},
                success:function(result){
                    for(let i=0;i<result.length;i++){
                        option += "<option value="+result[i].F_keyid+">"+result[i].F_name+"</option>";
                    }
                }
            });
            TUI.env.currPlugin.modal();
        },
        modal:function(){
            $.ajax({
                url:"/Plugins/"+local+"/php/operation.php",
                type:"post",
                dataType:"json",
                data:{
                    type:"get"
                },
                success:function(result){
					_unit = result;
					debugger
                    $(".mainPanel").append(
                        '<div id="update" class="modal fade">'
                        +'    <div class="modal-dialog modal-lg">'
                        +'        <div class="modal-content">'
                        +'            <div class="modal-header">'
                        +'                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">关闭</span></button>'
                        +'                <h4 class="modal-title">课程设置</h4>'
                        +'            </div>'
                        +'            <div class="modal-body">'
                        +'                <div class="input-group">'
                        +'                    <span class="input-group-addon">学期开始</span>'
                        +'                    <input type="date" class="form-control start">'
                        +'                    <span class="input-group-addon">学期结束</span>'
                        +'                    <input type="date" class="form-control end">'
                        +'                </div>'
                        +'                <div class="input-group mgt15 add">'
                        +'                    <input type="text" class="form-control" placeholder="多少节课">'
                        +'                    <span class="input-group-btn">'
                        +'                        <button class="btn btn-primary" type="button">添加</button>'
                        +'                    </span>'
                        +'                </div>'
                        +'                <div class="input-group mgt15 class"></div>'
                        +'            </div>'
                        +'            <div class="modal-footer">'
                        +'                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>'
                        +'                <button type="button" class="btn btn-primary save">保存</button>'
                        +'            </div>'
                        +'        </div>'
                        +'    </div>'
                        +'</div>'
                    );
                    if(_unit != 0){
                        $(".start").val(result.termStart);
                        $(".end").val(result.termEnd);
						$(".class").empty();
                        _unit.class.forEach(ele => {
                            var mid = (Date.now() * Math.random()).toString(32).replace(".","");
                            $(".class").append(
                                '<div class="input-group">'
                                +'  <span class="input-group-addon">'
                                +'      <input type="text" class="form-control name" placeholder="名称" style="width:130px;" value='+ele.name+'>'
                                +'  </span>'
                                +'  <span class="input-group-addon" style="background:#fff;">'
                                +'      <select class="form-control type '+mid+'" style="width:80px;">'
                                +'          <option value="0">分类</option>'
                                +'          <option value="1">上午</option>'
                                +'          <option value="2">下午</option>'
                                +'          <option value="3">晚上</option>'
                                +'      </select>'
                                +'  </span>'
                                +'  <div class="input-group">'
                                +'      <span class="input-group-addon fix-border fix-padding"></span>'
                                +'      <span class="input-group-addon">上课时间</span>'
                                +'      <input type="time" class="form-control cStart" value='+ele.cStart+'>'
                                +'      <span class="input-group-addon fix-border fix-padding"></span>'
                                +'      <select class="form-control sControl" style="width:150px">'+option+'</select>'
                                +'      <span class="input-group-addon fix-border fix-padding"></span>'
                                +'      <input type="text" class="form-control sTime" placeholder="轮询周期(分钟)" value='+ele.sTime+'>'
                                +'      <span class="input-group-addon fix-border fix-padding"></span>'
                                +'      <input type="text" class="form-control sNum" placeholder="次数" value='+ele.sNum+'>'
                                +'  </div>'
                                +'  <div class="input-group">'
                                +'      <span class="input-group-addon fix-border fix-padding"></span>'
                                +'      <span class="input-group-addon">下课时间</span>'
                                +'      <input type="time" class="form-control cEnd" value='+ele.cEnd+'>'
                                +'      <span class="input-group-addon fix-border fix-padding"></span>'
                                +'      <select class="form-control eControl" style="width:150px">'+option+'</select>'
                                +'      <span class="input-group-addon fix-border fix-padding"></span>'
                                +'      <input type="text" class="form-control eTime" placeholder="轮询周期(分钟)" value='+ele.eTime+'>'
                                +'      <span class="input-group-addon fix-border fix-padding"></span>'
                                +'      <input type="text" class="form-control eNum" placeholder="次数" value='+ele.eNum+'>'
                                +'  </div>'
                                +'  <span class="input-group-addon" style="cursor:pointer;" onClick="$(this).parent().remove();">X</span>'
                                +'</div>'
                            );
                            $("."+mid).val(ele.type);
                            $(".sControl").val(ele.sControl);
                            $(".eControl").val(ele.eControl);
                        });
                    }
                    $(".add button").off("click").on("click",function(){
                        var num = $(".add input").val()*1;
                        for(let i=0;i<num;i++){
                            $(".class").append(
                                '<div class="input-group">'
                                +'  <span class="input-group-addon">'
                                +'      <input type="text" class="form-control name" placeholder="名称" style="width:130px;">'
                                +'  </span>'
                                +'  <span class="input-group-addon" style="background:#fff;">'
                                +'      <select class="form-control type" style="width:80px;">'
                                +'          <option value="0">分类</option>'
                                +'          <option value="1">上午</option>'
                                +'          <option value="2">下午</option>'
                                +'          <option value="3">晚上</option>'
                                +'      </select>'
                                +'  </span>'
                                +'  <div class="input-group">'
                                +'      <span class="input-group-addon fix-border fix-padding"></span>'
                                +'      <span class="input-group-addon">上课时间</span>'
                                +'      <input type="time" class="form-control cStart">'
                                +'      <span class="input-group-addon fix-border fix-padding"></span>'
                                +'      <select class="form-control sControl" style="width:150px">'+option+'</select>'
                                +'      <span class="input-group-addon fix-border fix-padding"></span>'
                                +'      <input type="text" class="form-control sTime" placeholder="轮询周期(分钟)">'
                                +'      <span class="input-group-addon fix-border fix-padding"></span>'
                                +'      <input type="text" class="form-control sNum" placeholder="次数">'
                                +'  </div>'
                                +'  <div class="input-group">'
                                +'      <span class="input-group-addon fix-border fix-padding"></span>'
                                +'      <span class="input-group-addon">下课时间</span>'
                                +'      <input type="time" class="form-control cEnd">'
                                +'      <span class="input-group-addon fix-border fix-padding"></span>'
                                +'      <select class="form-control eControl" style="width:150px">'+option+'</select>'
                                +'      <span class="input-group-addon fix-border fix-padding"></span>'
                                +'      <input type="text" class="form-control eTime" placeholder="轮询周期(分钟)">'
                                +'      <span class="input-group-addon fix-border fix-padding"></span>'
                                +'      <input type="text" class="form-control eNum" placeholder="次数">'
                                +'  </div>'
                                +'  <span class="input-group-addon" style="cursor:pointer;" onClick="$(this).parent().remove();">X</span>'
                                +'</div>'
                            );
                        }
                    });
                    $(".save").off("click").on("click",function(){
                        var config = {
                            "termStart":$(".start").val(),
                            "termEnd":$(".end").val(),
                            "classNum":$(".add input").val(),
                            "class":[]
                        };
                        var cls = $(".class > div");
                        for(let i=0;i<cls.length;i++){
                            config.class.push({
                                name:cls.eq(i).find(".name").val(),
                                type:cls.eq(i).find(".type option:selected").val(),
                                cStart:cls.eq(i).find(".cStart").val(),
                                sControl:cls.eq(i).find(".sControl option:selected").val(),
                                sTime:cls.eq(i).find(".sTime").val(),
                                sNum:cls.eq(i).find(".sNum").val(),
                                cEnd:cls.eq(i).find(".cEnd").val(),
                                eControl:cls.eq(i).find(".eControl option:selected").val(),
                                eTime:cls.eq(i).find(".eTime").val(),
                                eNum:cls.eq(i).find(".eNum").val()
                            });
                        }
                        $.ajax({
                            url:"/Plugins/"+local+"/php/operation.php",
                            type:"post",
                            dataType:"json",
                            data:{
                                type:"save",
                                param:JSON.stringify(config)
                            },
                            success:function(result){
                                $('#update').modal('hide');
                                var tips = result ? "保存成功" : "保存失败";
                                new $.zui.Messager(tips,{
                                    icon: 'bell'
                                }).show();
								TUI.env.currPlugin.modal();
                            }
                        });
                    });
                    $(".query").off("click").on("click",function(){
						$(".layCont").empty().append(
							'<table class="table table-bordered">'
							+'    <thead>'
							+'        <tr>'
							+'          <th>教室名称</th>'
							+'          <th>日期</th>'
							+'        </tr>'
							+'    </thead>'
							+'    <tbody></tbody>'
							+'</table>'
						);
						var ke = "";
						_unit.class.forEach(ele => {
							$(".table thead tr").append('<th>'+ele.name+'</th>');
							ke += "<td>无课</td>";
						});
                        var node = [];
                        var nodes = zTree.getCheckedNodes();
                        nodes.forEach(ele => node.push(ele.id));
						if(node.length){
							$.ajax({
								url:"/Plugins/"+local+"/php/getInfo.php",
								type:"POST",
								dataType:"json",
								context:this,
								data:{
									node:node.join(","),
									sdate:$(".inText").eq(0).val(),
									edate:$(".inText").eq(1).val()
								},
								success:function(res){
									res.forEach(ele => {
										var tid = (Date.now() * Math.random()).toString(32).replace(".","");
										$(".table tbody").append(
											'<tr id='+tid+'>'
											+'    <td>'+ele.F_name+'</td>'
											+'    <td>'+ele.F_date+'</td>'+ke
											+'</tr>'
										);
										ele.F_class.split(",").forEach(Cele => {
											$("#"+tid+" td").eq(Cele*1+1).html("有课");
										});
									});
								}
							});
						}
                    });
					$(".query").click();
                }
            });
        },
        upload:function(type){
            $(".file").click(function(){
                var formData = new FormData();
                formData.append("file",$(".file")[0].files[0]);
                formData.append("type",type);
                formData.append("device",_device.join(","));
                $.ajax({
                    url: '/Plugins/'+local+'/php/test.php', 
                    type:'POST',
                    cache: false, 
                    processData: false,
                    contentType: false,
                    data: formData,
                    dataType:"json",
                    success:function(result){
                        result.errCode == 1 ? $('#upload').modal('hide') : 0;
                        new $.zui.Messager(result.msg,{
                            icon: 'bell'
                        }).show();
                    }
                });
            }());
        }
    };
}
$(document).ready(() => {
    local = "plugin_timetable";
    TUI.env.currPlugin = new MainPanel();
    TUI.env.currPlugin.init();
});
