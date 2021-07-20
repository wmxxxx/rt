$(document).ready(function(){
	var app = queryString("app");
	var num = queryString("num") > 0 ? queryString("num") : 0;
	var device = [],config = [],command = [],labels = [],temp = [];
	init();
	// 初始化
	function init(){
		$.post("php/Init.php",{id:queryString("id"),app:app},function(result){
			device = result.device;
			config = result.config;
			result.command.forEach(ele => command[ele.F_id] = ele);
			var dPanel = config.device[device.F_TemplateLabel];
			if(dPanel.chart)$(".dnav li:eq(1)").show();
			if(dPanel.calendar)$(".dnav li:eq(2)").show();
			if(dPanel.strategy)$(".dnav li:eq(3)").show();
			$("#tabContent1").addClass(dPanel.class);
			if(dPanel.chart || dPanel.calendar){
				// 设备模板信息
				$.get("/API/Dictionary/DTplValue/?tpl_code="+device.F_TemplateCode,function(res){
					res.forEach(ele => {
						temp[ele.F_ValueLabel] = ele;
						if(ele.F_IsStorage){
							var opt = '<option value='+ele.F_ValueLabel+' unit='+ele.F_Unit+'>'+ele.F_ValueName+'</option>';
							ele.F_ValueType == 1 ? $("#tabContent3 select").append(opt) : $("#tabContent2 select.cond").append(opt);
						}
					});
					buildPage(dPanel);
				},"json");
			}else{
				buildPage(dPanel);
			}
		},"json");
	}
	// 构建画面
	function buildPage(dPanel){
		$(".loading").hide();
		// 设备信息
		dInfo = getDeviceRunningInfo();
		// 实时监测
		let today = new Date().Format("yyyy-MM-dd");
		$(".sTop .sDate").datetimepicker({
			language:  "zh-CN",
			endDate: today,
			weekStart: 1,
			todayBtn:  1,
			autoclose: 1,
			minView: 2,
			forceParse: 0,
			pickerPosition: 'bottom-left',
			format: "yyyy-mm-dd"
		});
		$(".sTop .sDate").val(today);
		// 空调面板
		if(dPanel.class == "p3"){
			var bli = '';
			dPanel.btn.forEach(batch => bli += '<li><a href="#" value='+batch.com[0]+' class="bBtn">'+batch.icon+'</a></li>');
			$("#tabContent1 .nav .licon").html(
				'<div class="btn-group">'
					+'<button id="btnControl" type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">控制命令 <span class="caret"></span></button>'
					+'<ul class="dropdown-menu pull-right" role="menu" aria-labelledby="btnControl">'+bli+'</ul>'
				+'</div>'
			).find(".bBtn").off("click").on("click",function(){
				sends(command[$(this).attr("value")]);
			});
		}else{
			var judge = judgeStatus(dInfo.data,device.F_TemplateLabel);
			var pWidth = $("#tabContent1 .layTop").width() / 450;
			var pHeight = $("#tabContent1 .layTop").height() / 310;
			var ratio = pWidth < pHeight ? pWidth : pHeight;
			var cont = '';
			if(dInfo.data[dPanel.attr1])cont += '<div class="key1"><p>'+dInfo.data[dPanel.attr1].name+'</p><span class="D'+dPanel.attr1+'" style="background-image:url(images/'+dInfo.data[dPanel.attr1].show+'.png)"></span></div>' ;
			if(dInfo.data[dPanel.attr2])cont += '<div class="key2"><p>'+dInfo.data[dPanel.attr2].name+'</p><span class="D'+dPanel.attr2+'" style="background-image:url(images/'+dInfo.data[dPanel.attr2].show+'.png)"></span></div>';
			if(dInfo.data[dPanel.attr3])cont += '<div class="key3"><strong class="V'+dPanel.attr3+'" style='+(isNaN(dInfo.data[dPanel.attr3].show * 1) ? "font-size:30px;top:-9px;" : "")+'>'+dInfo.data[dPanel.attr3].show+'</strong><div><p>'+dInfo.data[dPanel.attr3].name+'</p><span>'+dInfo.data[dPanel.attr3].unit+'</span></div></div>';
			if(dInfo.data[dPanel.attr4])cont += '<div class="key4"><strong class="V'+dPanel.attr4+'" style='+(isNaN(dInfo.data[dPanel.attr4].show * 1) ? "font-size:55px;top:-15px;" : "")+'>'+dInfo.data[dPanel.attr4].show+'</strong><div><p>'+dInfo.data[dPanel.attr4].name+'</p><span>'+dInfo.data[dPanel.attr4].unit+'</span></div></div>';
			if(dInfo.data[dPanel.attr5])cont += '<div class="key5"><p>'+dInfo.data[dPanel.attr5].name+'</p><div><strong class="V'+dPanel.attr5+'" style='+(isNaN(dInfo.data[dPanel.attr5].show) * 1 ? "font-size:30px;line-height:70px;" : "")+'>'+dInfo.data[dPanel.attr5].show+'</strong><span>'+dInfo.data[dPanel.attr5].unit+'</span></div></div>';
			if(dPanel.class == "p1"){
				$("#tabContent1 .layTop").html(
					'<div class="airPanel" style="transform:scale('+ratio+')">'
						+'<div class="aTop">'+cont+'</div>'
						+'<div class="aDown">'
							+'<span><div id="state" style="background-color:'+judge.color+'"></div>状态</span>'
							+'<ul></ul>'
						+'</div>'
						+'<div class="aLeft"></div>'
						+'<div class="aRight"><div><i class="icon icon-star"></i><p class="airtype">--</p></div><div><i class="icon icon-cog"></i><p class="airenergy">--</p></div><div><i class="icon icon-time"></i><p class="airtime">--</p></div></div>'
					+'</div>'
				);
			}else if(dPanel.class == "p2"){
				$("#tabContent1 .layTop").html(
					'<div class="airPanel" style="transform:scale('+ratio+')">'
						+'<div class="aTop">'+cont
							+'<div class="aDown">'
								+'<ul></ul>'
							+'</div>'
						+'</div>'
						+'<div class="aLeft"></div>'
						+'<div class="aRight"><div><i class="icon icon-star"></i><p class="airtype">--</p></div><div><i class="icon icon-cog"></i><p class="airenergy">--</p></div><div><i class="icon icon-time"></i><p class="airtime">--</p></div></div>'
					+'</div>'
				);
			}
			if($("#tabContent1 .layTop").width() < 730*ratio){
				$(".aLeft").hide();
				$(".aRight").hide();
			}
			if(dPanel.ext != "" && dInfo.online){
				var dclass = dPanel.ext.split(";");
				dclass.forEach(ele => {
					var atts = ele.split("&");
					if(dInfo.data[atts[1]].value == atts[2]){
						if(atts[3] == "add"){
							$(".D"+atts[0]).addClass(atts[4]);
						}else if(atts[3] == "remove"){
							$(".D"+atts[0]).removeClass(atts[4])
						}
					}
				});
			}
			// 添加按钮
			dPanel.btn.forEach(btn => {
				var bid = (Date.now() * Math.random()).toString(32).replace(".","");
				$("#tabContent1 .aDown ul").append('<li id='+bid+' style="background-image:url(images/'+dPanel.class+'-'+btn.icon+')"></li>');
				if(btn.com.length > 1){
					var kv = '';
					btn.com.forEach(ele => kv += '<button class="sBtn" code='+ele+'>'+command[ele].F_name+'</button>');
					$("#"+bid).attr({
						"data-placement":"top",
						"data-toggle":"popover"
					}).popover({
						html:true,
						content:kv,
						animation:false
					}).attr("value",btn.com.length);
				}else{
					$("#"+bid).addClass("bBtn").attr("code",btn.com[0]);
				}
			});
			$('[data-toggle="popover"]').on("show.zui.popover",function(){
				$('[data-toggle="popover"]').popover("hide");
				$(".airPanel").css("transform","scale(1)");
			});
			$('[data-toggle="popover"]').on("shown.zui.popover",function(){
				$(".airPanel").css("transform","scale("+ratio+")");
				$("#tabContent1 .sBtn").off("click").on("click",function(){
					$('[data-toggle="popover"]').popover("hide");
					sends(command[$(this).attr("code")]);
				});
			});
			$("#tabContent1 .aDown li").on("click",function(){
				$(".popover").css("width",$(this).attr("value") * 68 + 30);
				$(this).popover("hide").popover("show");
			});
			$("#tabContent1 .bBtn").off("click").on("click",function(){
				sends(command[$(this).attr("code")]);
			});
		}
		// 运行信息
		$("#tabContent4 ol").empty().contextmenu({
			items: [{
				label:'刷新',
				onClick:function(){
					$("#tabContent4").addClass("load-indicator loading");
					update();
				}
			}]
		});
		$("#tabContent4 ol").append('<li class="time" style="width:100%;"><div>更新时间</div><span>'+dInfo.time+'</span></li>');
		$.each(dInfo.data,function(i,k){
			$("#tabContent4 ol").append(
				'<li class='+i+'>'
					+'<div title='+k.name+'>'+(k.name.length > 8 ? k.name.substr(0,6)+"…" : k.name)+'</div>'
					+'<span>'+k.show+k.unit+'</span>'
				+'</li>'
			);
			labels.push(i);
			// if(i == "TotalEnergy" || i == "Ep")$("#tabContent1 .aRight .airenergy").html(k.show+k.unit);
			if(i == "TotalTime")$("#tabContent1 .aRight .airtime").html(k.show+"<br>"+k.unit);
		});
		// 外接设备
		if(dInfo.access.length){
			$("#tabContent8").empty();
			dInfo.access.forEach(ele => {
				var li = '';
				ele.variantDatas.forEach(vele => {
					li += '<li class='+vele.label+'>'
							+'<div>'+vele.name+'</div>'
							+'<span>'+vele.show+vele.unit+'</span>'
						+'</li>';
				});
				$("#tabContent8").append('<ol style="height:auto;"><li>'+ele.name+'</li>'+li+'</ol>');
			});
		}else{
			$("#tabContent1 .layDown .nav li:eq(1)").hide();
		}
		// 基本信息
		$.get("/API/Model/Property/?tree_node="+device.F_NodeCode,function(res){
			$("#tabContent5 ol").empty();
			$("#tabContent5 ol").append(
				'<li>'
				+'	<div>设备名称</div>'
				+'	<span>'+res[0].F_EntityName+'</span>'
				+'</li>'
			);
			res[0].data.forEach(ele => {
				if(ele.F_PropertyValue != ""){
					$("#tabContent5 ol").append(
						'<li>'
							+'<div title='+ele.F_PropertyName+'>'+(ele.F_PropertyName.length > 8 ? ele.F_PropertyName.substr(0,6)+"…" : ele.F_PropertyName)+'</div>'
							+'<span>'+(ele.F_PropertyText == "" ? ele.F_PropertyValue : ele.F_PropertyText)+'</span>'
						+'</li>'
					);
				}
				if(ele.F_PropertyIdentifier == 'P2_AirConType')$("#tabContent1 .aRight .airtype").html(ele.F_PropertyText);
			});
		},"json");
		// 管理信息
		nodeGroup(device.F_NodeCode);
		// 日志
		$("#tabContent1 .axis .sDate").off("change").on("change",function(){
			var dt = $(this).val();
			$.post("php/getLogList.php",{id:device.F_NodeCode,time:dt,app:app},function(res){
				$("#tabContent1 .axis ul").empty();
				if(res.length){
					res.forEach(ele => {
						$("#tabContent1 .axis ul").append(
							'<li>'
								+'<span style="background-color:'+ele.color+'"></span>'
								+'<div>'+ele.time+'</div>'
								+'<p>'+ele.title+'<br>'+ele.msg+'</p>'
							+'</li>'
						);
					});
				}else{
					$("#tabContent1 .axis ul").append(
						'<li>'
							+'<span style="background-color:#ccc;"></span>'
							+'<div>'+dt+'</div>'
							+'<p>暂无日志信息</p>'
						+'</li>'
					);
				}
			},"json");
		});
		$("#tabContent1 .axis .sDate").change();
		// 运行图表
		if(dPanel.chart){
			$("#tabContent2 .sDate").datetimepicker({
				language: "zh-CN",
				endDate: today,
				weekStart: 1,
				todayBtn:  1,
				autoclose: 1,
				minView: 2,
				forceParse: 0,
				pickerPosition: 'bottom-left',
				format: "yyyy-mm-dd"
			});
			$("#tabContent2 .sDate").val(today);
			$("#tabContent2 .histogram .sDate").eq(1).val(new Date(new Date().getTime() - 7 * 86400 * 1000).Format("yyyy-MM-dd"));
			Highcharts.setOptions({global:{timezoneOffset:-8*60}});
			$("#tabContent2 .histogram input").off("change").on("change",function(){
				histogramChart($("#tabContent2 .histogram input").eq(1).val(),$("#tabContent2 .histogram input").eq(0).val(),$("#tabContent2 .histogram > div"))
			});
			$("#tabContent2 .diagram .cond").off("change").on("change",function(){
				diagramChart($("#tabContent2 .diagram select option:selected").val(),$("#tabContent2 .diagram input").val(),$("#tabContent2 .diagram > div"))
			});
			$(".nav li").eq(1).off("click").on("click",function(){
				$("#tabContent2 .histogram input").eq(0).change();
				$("#tabContent2 .diagram select.cond").change();
			});
		}
		// 用电日历
		let today3 = new Date().Format("yyyy-MM");
		if(dPanel.calendar){
			$("#tabContent3 .sDate").datetimepicker({
				language: "zh-CN",
				endDate: today3,
				weekStart: 1,
				todayBtn: 0,
				autoclose: 1,
				startView: 3,
				minView: 3,
				forceParse: 0,
				pickerPosition: 'bottom-left',
				format: "yyyy-mm"
			});
			$("#tabContent3 .sDate").val(today3);
			$("#tabContent3 .term select").off("change").on("change",function(){
				getDeviceCalendar($("#tabContent3 .term select option:selected").val(),$("#tabContent3 .term input").val(),$("#tabContent3 .term select option:selected").attr("unit"));
			});
			$("#tabContent3 .term input").off("change").on("change",function(){
				getDeviceCalendar($("#tabContent3 .term select option:selected").val(),$("#tabContent3 .term input").val(),$("#tabContent3 .term select option:selected").attr("unit"));
			}).change();
		}
		// 策略明细
		if(dPanel.strategy){
			$("#tabContent7 .sDate").datetimepicker({
				language: "zh-CN",
				weekStart: 1,
				todayBtn: 0,
				autoclose: 1,
				startView: 3,
				minView: 3,
				forceParse: 0,
				pickerPosition: 'bottom-left',
				format: "yyyy-mm"
			});
			$("#tabContent7 .sDate").val(today3);
			$("#tabContent7 .term input").off("change").on("change",function(){
				$("#mode").html('<iframe src="../../plugin_share_calendar/calendar.html?id='+device.F_NodeCode+'&month='+$("#tabContent7 .term input").val()+'" width="100%" height="100%" frameborder="0" scrolling="no"></iframe>');
			}).change();
		}
	}
	// 设备分组
	function nodeGroup(){
		$("#tabContent6 ul").empty();
		$.post("php/getNodeGroup.php",{app:app,id:device.F_NodeCode},function(res){
			$("#tabContent6 ul").prepend(
				'<li class="rel">'
					+'<p>关联分组：</p>'
					+'<div></div>'
					+'<button class="btn btn-primary" type="button">添加</button>'
					+'<select class="form-control"><option value="">请选择分组</option></select>'
				+'</li>'
			);
			res.group.forEach(ele => {
				if(ele.ck){
					$("#tabContent6 ul .rel select").append('<option value='+ele.F_id+' disabled>'+ele.F_name+'</option>');
					$("#tabContent6 ul .rel div").append('<span value='+ele.F_id+'>'+ele.F_name+' <i class="icon icon-times"></i></span>');
				}else{
					$("#tabContent6 ul .rel select").append('<option value='+ele.F_id+'>'+ele.F_name+'</option>');
				}
			});
			$("#tabContent6 .rel .btn").off("click").on("click",function(){
				var value = $("#tabContent6 .rel select").val();
				if(value != ""){
					$("#tabContent6 ul .rel div").append('<span value='+value+'>'+$("#tabContent6 .rel select option:selected").text()+' <i class="icon icon-times"></i></span>');
					save();
				}
			});
			$("#tabContent6 ul .rel div span").off("click").on("click",function(){
				$(this).remove();
				save();
			});
			res.strategy.forEach(ele => {
				var ms = ele.name+"("+(ele.F_open ? '启用' : '停用')+")　策略周期："+ele.F_start+" ~ "+ele.F_end+"　循环周期："; 
				ms += ele.F_type_val == 0 ? "每天" : "每周"+ele.F_type_val;
				if(ele.F_day != 0){
					ms += "　节假日模式：";
					ms += ele.F_day == 1 ? "工作日" : "节假日";
				}
				ms += "　执行动作：";
				ele.sub.forEach(cele => {
					ms += cele.F_time+" "+cele.F_name;
					if(cele.F_poll_time && cele.F_poll_num)ms += "(轮询周期"+cele.F_poll_time+"分钟，轮询"+cele.F_poll_num+"次)";
					ms += "　";
				});
				$("#tabContent6 ul").append('<li>'+ms+'</li>');
			});
			res.model.forEach(ele => {
				if(ele.F_type == "temphum"){
					$("#tabContent6 ul").append('<li>温湿度模型　开机温度范围：'+ele.F_start+'　设定温度范围：'+ele.F_end+'</li>');
				}
			});
			$("#tabContent1 .aRight .airenergy").html("未受控制");
			if(res.force.length){
				$("#tabContent1 .aRight .airenergy").html("强控");
			}else{
				if(res.strategy.length)$("#tabContent1 .aRight .airenergy").html("弹性");
				if(res.model.length)$("#tabContent1 .aRight .airenergy").html("柔性");
				if(res.strategy.length && res.model.length)$("#tabContent1 .aRight .airenergy").html("弹性<br>柔性");
			}
		},"json");
	}
	function save(){
		var groups = [];
		$.each($("#tabContent6 ul .rel div span"),function(i,item){
			groups.push($(item).attr("value"));
		});
		$.post("php/setNodeGroup.php",{groups:groups,id:device.F_NodeCode,app:app},function(){
			init();
		},"json");
	}
	// 设备运行信息
	function getDeviceRunningInfo(){
		var nodes;
		$.ajax({
			url:"php/getDevice.php?id="+device.F_NodeCode+"&app="+app,
			type:"get",
			dataType:"json",
			context:this,
			async:false,
			success:function(result){
				nodes = result;
			}
		});
		return nodes;
	}
	// 运行状态图
	function histogramChart(start,end,lead){
		$.post("php/getRunning.php",{
			app:app,
			nodeid:device.F_NodeCode,
			start:start,
			end:end,
			tab:num
		},function(result){
			var cate = [],series = [];
			result.run.forEach(ele => {
				if($.inArray(ele.F_Date,cate) == -1)cate.push(ele.F_Date);
				if(series[ele.F_Date] == undefined)series[ele.F_Date] = [];
				series[ele.F_Date].push(ele);
			});
			var data = [];
			cate.forEach((Cele,Ci) => {
				series[Cele].forEach(Sele => {
					data.push({
						x:Sele.F_StartTime*1000,
						x2:Sele.F_EndTime*1000,
						y:Ci,
						name:Sele.F_Info,
						color:Sele.F_Color
					});
				});
			});
			lead.highcharts({
				chart:{type:'xrange'},
				title:{text:'设备运行状态图'},
				credits:{enabled:false},
				legend:{enabled:false},
				xAxis:{
					type:'datetime',
					tickInterval:2*3600*1000,
					dateTimeLabelFormats:{day:'%H:%M'},
					plotBands:[{
						color:"#00B9B480",
						from:new Date("2011-11-11 "+result.time.F_start).getTime(),
						to:new Date("2011-11-11 "+result.time.F_end).getTime(),
						zIndex:3,
						label:{
							text:"工作时间("+result.time.F_start+"~"+result.time.F_end+")",
							y:-5
						}
					}]
				},
				yAxis:{
					title:{text:''},
					categories:cate
				},
				tooltip:{
					useHTML:true,
					formatter:function(){
						return Highcharts.dateFormat('%H:%M:%S',this.x)+"~"+Highcharts.dateFormat('%H:%M:%S',this.x2)+"</br>运行状态："+this.key;
					}
				},
				series:[{
					maxPointWidth:20,
					data:data
				}]
			});
		},"json");
	}
	// 参数曲线图
	function diagramChart(param,date,lead){
		$.get("/API/SData/GetDeviceInstantData/?device_str="+device.F_NodeCode+"&value_str="+param+"&data_type=1&start_date="+date+"&end_date="+date,function(result){
			var series = {
				name:$("#tabContent2 .diagram select option:selected").text(),
				data:[]
			};
			if(result.length){
				result = result[0].data[0];
				result.data.forEach(ele => series.data.push([new Date(ele.F_ReadingDate).getTime(),ele.F_DataValue*1]));
			}
			lead.highcharts({
				chart:{
					type:'spline',
					resetZoomButton:{
						position:{
							align:'right',
							verticalAlign:'top',
							x:0,
							y:-10
						}
					}
				},
				legend:{enabled:false},
				credits:{enabled:false},
				title:{text:series.name+'曲线图'},
				xAxis:{type:'datetime'},
				yAxis:{
					title:{text:''},
					min:0
				},
				tooltip:{
					formatter:function(){
						var nData = temp[$("#tabContent2 .diagram select option:selected").val()];
						if(nData.F_KV == ""){
							nData.show = this.y+nData.F_Unit;
						}else{
							var kv = [];
							nData.F_KV.split("-").forEach(ele => {
								var expv = ele.split(":");
								kv[expv[0]] = expv[1]
							});
							nData.show = kv[this.y];
						}
						return Highcharts.dateFormat('%Y-%m-%d %H:%M:%S',this.x)+'<br><b>'+this.series.name+'</b><b>：'+nData.show+'</b>';
					}
				},
				plotOptions:{spline:{marker:{enabled:false}}},
				series:[series]
			});
		},"json");
	}
	// 设备用电日历
	function getDeviceCalendar(param,month,unit){
		var last = month+"-"+new Date(month.split("-")[0],month.split("-")[1],0).getDate();
		$.get("/API/SData/GetDeviceDayData/?device_code="+device.F_NodeCode+"&value_tag="+param+"&start_date="+month+"-01&end_date="+last,function(result){
			var data = [];
			var energy = 0,work = 0,unwork = 0,flex = 0;
			result.forEach(ele => {
				var date = ele.F_DayDate.split("-");
				energy += ele.F_EnergyData*1;
				work += ele.F_WorkingData*1;
				unwork += ele.F_UnWorkingData*1;
				flex += ele.F_FlexibleData*1;
				data.push({
					year:date[0],
					month:date[1],
					day:date[2],
					energy:ele.F_EnergyData*1,
					work:ele.F_WorkingData*1,
					unwork:ele.F_UnWorkingData*1,
					flex:ele.F_FlexibleData*1
				});
			});
			var tbody='';
			var dateDom='';
			var len=data.length;
			var startDay=new Date(data[0].year,data[0].month-1,data[0].day).getDay();
			var endDay=new Date(data[len-1].year,data[len-1].month-1,data[len-1].day).getDay();
			for(i=0;i<startDay;i++){
				dateDom+='<td></td>';
			}
			for(i=0;i<len;i++){
				day=(new Date(data[i].year,data[i].month-1,data[i].day)).getDay();
				if(day==6){
					dateDom+='<td class="sat"><span class="date">'+data[i].day*1+'</span><span class="val"><div style="color:#333;">'+data[i].energy+'</div><div style="color:#00B9B4;">'+data[i].work+'</div><div style="color:#FAAF3B;">'+data[i].unwork+'</div><div style="color:#798CA0;">'+data[i].flex+'</div></span></td>';
					tbody+='<tr>'+dateDom+'</tr>';
					dateDom='';
				}else if(day=='0'){
					dateDom+='<td class="sun"><span class="date">'+data[i].day*1+'</span><span class="val"><div style="color:#333;">'+data[i].energy+'</div><div style="color:#00B9B4;">'+data[i].work+'</div><div style="color:#FAAF3B;">'+data[i].unwork+'</div><div style="color:#798CA0;">'+data[i].flex+'</div></span></td>';
				}else{
					dateDom+='<td class="working-day"><span class="date">'+data[i].day*1+'</span><span class="val"><div style="color:#333;">'+data[i].energy+'</div><div style="color:#00B9B4;">'+data[i].work+'</div><div style="color:#FAAF3B;">'+data[i].unwork+'</div><div style="color:#798CA0;">'+data[i].flex+'</div></span></td>';
				}
			}
			for(i=endDay+1;i<7;i++){
				dateDom+='<td><span class="date"></span></td>';
			}
			tbody+=dateDom+'</tr>';
			$('#calendar tbody').empty().append(tbody);
			$('#tabContent3 .term p').html('<span style="color:#333;">总计用能：'+energy.toFixed(2)+'</span><span style="color:#00B9B4;">工作时间用能：'+work.toFixed(2)+'</span><span style="color:#FAAF3B;">非工作时间用能：'+unwork.toFixed(2)+'</span><span style="color:#798CA0;">过渡时间用能：'+flex.toFixed(2)+'</span><br><span style="color:#333;">单位：'+unit+'</span>');
		},"json");
	}
	// 下发命令
	function send(com,arg){
		var load = new $.zui.Messager(com.F_name+"命令下发中",{
			type: 'primary',
			icon: 'spinner-snake icon-spin',
			time: 0
		}).show();
		$.post("/Php/lib/plugin/share/commandSend.php",{
			code:com.F_id,
			pCode:app,
			name:com.F_name,
			type:0,
			arg:arg,
			nodelist:[device.F_NodeCode]
		},function(res){
			load.hide();
			var code = res.code;
			new $.zui.Messager("命令下发"+(code == "0" ? "成功" : "失败"),{
				type: code == "0" ? 'success' : 'danger',
				icon: code == "0" ? 'ok-sign' : 'info-sign'
			}).show();
			if(code == "0")update();
			$("#tabContent1 .axis .sDate").change();
		},"json");
	}
	function sends(com){
		var arg = {};
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
					send(com,arg);
				}
			});
		}else{
			send(com,arg);
		}
	}
	// 更新状态
	function update(param){
		param = param == 'StartStopCmd' ? 'StartStopStatus' : param;
		var lab = param == undefined ? labels : [param];
		$.post("../php/readDeviceParamValue.php",{node:device.F_NodeCode,label:lab},function(res){
			if(res.status){
				var dInfo = getDeviceRunningInfo();
				$.each(dInfo.data,function(i,k){
					$("."+i+" span").html(k.show+k.unit);
					$(".V"+i).html(k.value);
					$(".D"+i).css("background-image","url(images/"+k.show+".png)");
				});
				var judge = judgeStatus(dInfo.data,device.F_TemplateLabel);
				$(".airPanel #state").css("background-color",judge.color);
				var dPanel = config.device[device.F_TemplateLabel];
				if(dPanel.ext != "" && dInfo.online){
					var dclass = dPanel.ext.split(";");
					dclass.forEach(ele => {
						var atts = ele.split("&");
						if(dInfo.data[atts[1]].value == atts[2]){
							if(atts[3] == "add"){
								$(".D"+atts[0]).addClass(atts[4]);
							}else if(atts[3] == "remove"){
								$(".D"+atts[0]).removeClass(atts[4])
							}
						}
					});
				}
			}else{
				new $.zui.Messager(res.msg,{
					type: 'warning',
					icon: 'warning-sign'
				}).show();
			}
			$("#tabContent1 .axis .sDate").change();
			$("#tabContent4").removeClass("load-indicator loading");
		},"json");
	}
	// 判断设备状态
	function judgeStatus(vDatas,tplId){
		var result={
			color:"#ccc",
			state:0
		};
		try{
			if(num && num != -1){
				config.node[num].tips.forEach((ele,i) => {
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
			}else{
				config.node.forEach(node => {
					node.tips.forEach((ele,i) => {
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
				});
			}
		}catch(err){
			return result;
		}
		return result;
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