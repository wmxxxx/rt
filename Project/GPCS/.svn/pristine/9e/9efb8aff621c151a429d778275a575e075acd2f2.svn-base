plugin_share_map = function(container, param) {    
	return {
		init: function() {
			this.container = container;
			this.code = param.code; //插件编号
			this.fun = param.fun; //功能编号
			this.app = param.app; //系统编号
			this.user = param.user; //用户编号
			this.refreshFlag = null;

			this.globalVar=param.env;


			$('#'+this.container).empty().html(
				'<div id="plugin_share_map" class="plugin_share_map">'
					+'<div id="tool-bar">'
						+'<div id="map-switch" style="display:inline-block;"></div>'
						+'<button id="close-config" class="set-btn" style="display:none;"></button>'
						+'<button id="open-config" class="set-btn"></button>'
					+'</div>'
					+'<div id="config-panel">'
						+'<div id="config-bar">'
							+'<label style="margin-right:20px;">1、选择文件导入地图(.jpg)；2、勾选节点；3、保存配置；</label>'
							+'<input type="file" id="upload-map" accept="image/jpg">'
							+'<label id="map-url"></label>'
						+'</div>'
						+'<div id="tree-panel">'
							+'<div class="panel-bd" id="map-nodes"></div>'
							+'<div class="panel-ft">'
								+'<button value="cancel" type="button" class="btn-tab">取消</button>'
								+'<button id="save-config" value="save" type="button" class="btn-tab">保存</button>'
							+'</div>'
						+'</div>'
					+'</div>'
					+'<div id="map-panel"></div>'
					// 自定义页面
					+'<div id="show-panel"></div>'
				+'</div>');

			// 加载树
			this.loadTree();
			
			this.initEvent();

			this._energy={};
			this._currNode={};
		},
		// 加载树
		loadTree:function(code,fun,app,user){
			// 获取关联的树
			new TUI.TreePanel('map-nodes',{
				plugin:TUI.env.currPlugin.code,
				fun:TUI.env.currPlugin.fun,
				app:TUI.env.currPlugin.app,
				async:false,
				expand:false,
				checkbox:true
			});
			var treeObj = $.fn.zTree.getZTreeObj("map-nodes_tree");
			TUI.env.currPlugin._root = treeObj.getNodesByFilter(function (node) { return node.level == 0 }, true);
			// 加载配置信息
			this.getConfig();
		},
		getEnergyParam:function(energyID,energyType){
			$.ajax({
				url:'/API/Dictionary/EnergyParam/',
				type:'GET',
				data:{energy_id:energyID},
				dataType:'JSON',
				success:function(res){
					TUI.env.currPlugin._energy[energyID].param=res;
				},
				error:function(data,status){
					console.log('获取能源参数error '+status);
				}
			});
		},
		initEvent:function(){
			// 关闭配置
			$('#close-config').on('click',function(){
				$('#config-panel').hide();
				$('#tree-panel').hide();
				$('#map-panel').css({top:0,right:0});
				$('#tool-bar').css({left:'200px'});
				$('#monitor-panel').show();
				$(this).hide();
				$('#open-config').show();
				TUI.env.currPlugin.onSetting=false;

				$('.map-item.active').click();
			});

			// 打开配置
			$('#open-config').on('click',function(){
				$('#monitor-panel').hide();
				$('#map-panel').css({top:'100px',right:'200px'});
				$('#tool-bar').css({left:'20px'});
				$('#config-panel').show();
				$('#tree-panel').show();
				$(this).hide();
				$('#close-config').show();
				TUI.env.currPlugin.onSetting=true;
				// 获取当前地图的配置信息
				var groupIndex=$('#map-switch .tab-btn.active').val();
				var mapIndex=$('.map-item.active').data('id');
				var mapData=TUI.env.currPlugin._oMap[groupIndex].maps[mapIndex];
				// 勾选对应的配置节点
				var zTree = $.fn.zTree.getZTreeObj("map-nodes_tree");
				zTree.checkAllNodes(false);
				if(mapData.nodes && mapData.nodes.length>0){
					var mapNodes=mapData.nodes;
					for(let i=0;i<mapNodes.length;i++){
						let node = zTree.getNodeByParam("id",mapNodes[i]);
						zTree.checkNode(node, true, true);
					}
				}
			});

			 $('#map-switch').on('click',function(event){
				var target=event.target;
				if(target.tagName=='BUTTON'){
					// 切换地图分组
					console.log('切换地图分组');
					$(target).addClass('active').siblings('.tab-btn').removeClass('active');
					var group=$(target).val();
					$('.map-list.active').removeClass('active');
					// 默认选中第一个地图
					$('#map-switch-'+group).addClass('active').find('.map-item:first-child').click();
				}else if(target.tagName=='LI'){
					// 切换地图
					console.log('切换地图');
					TUI.env.currPlugin._coordinate=[];
					$(target).addClass('active').siblings('.map-item').removeClass('active');
					var groupIndex=$('#map-switch .tab-btn.active').val();
					var mapIndex=$(target).data('id');
					// 获取当前地图的配置信息
					var mapData=TUI.env.currPlugin._oMap[groupIndex].maps[mapIndex];
					if(TUI.env.currPlugin.onSetting){
						// 如果已经配了节点，则勾选对应的节点
						var zTree = $.fn.zTree.getZTreeObj("map-nodes_tree");
						zTree.checkAllNodes(false);
						if(mapData.nodes && mapData.nodes.length>0){
							var mapNodes=mapData.nodes;
							for(let i=0;i<mapNodes.length;i++){
								let node = zTree.getNodeByParam("id",mapNodes[i]);
								zTree.checkNode(node, true, true);
							}
						}
					}
					// 如果已经配置了地图，则loadMap 
					if(mapData.bg){
						TUI.env.currPlugin.loadMap('map-panel',mapData);
					}else{
						$('#map-panel').empty();
					}
				}
			})

			// 上传地图
			$('#upload-map').on('change',function(){
				var fid=(new Date()).getTime();
				var file=event.target.files[0];
				if(file.size<=5*1024*1024){
					let mapData=new FormData();
					mapData.append('file',file);
					TUI.env.currPlugin.uploadMap(mapData);
				}else{
					// 文件大小超限制
					alert('文件大小超限制!');
				}
			});
			// 保存配置信息
			$('#save-config').on('click',function(){
				TUI.env.currPlugin.setConfig();
			});
		},
		// 获取配置
		getConfig:function(){
			$.post('/Plugins/plugin_share_map/php/getConfig.php',{app:TUI.env.currPlugin.app},function(res){
				var groupMap=[];
				if(res){
					groupMap=res.data;
				}else{
					let oTag=[];
					let globalVar=TUI.env.currPlugin.globalVar;
					if( globalVar.oTag){
						oTag=globalVar.oTag;
					}else{
						alert("未配置环境变量,请前往微应用中进行配置（配置格式：[{name:'分组1',maps:['地图1','地图2']},{name:'分组2',maps:['地图3','地图4' ]}])");
						oTag=[];
					} 
					for(let i=0;i<oTag.length;i++){
						groupMap.push({name:oTag[i].name,maps:[]})
						for(let j=0;j<oTag[i].maps.length;j++){
							groupMap[i].maps[j]={name:oTag[i].maps[j],bg:'',nodes:[]};
						}
					}
				}
				TUI.env.currPlugin._oMap=groupMap;
				for(let i=0;i<groupMap.length;i++){
					let ele='<button value="'+i+'" type="button" class="tab-btn">'+groupMap[i].name+'</button><ul id="map-switch-'+i+'" class="map-list">';
					for(let j=0;j<groupMap[i].maps.length;j++){
						if(groupMap[i].maps.length > 1){
							ele+='<li data-id="'+j+'" class="map-item">'+groupMap[i].maps[j].name+'</li>';
						}else{
							ele+='<li style="display:none;" data-id="'+j+'" class="map-item">'+groupMap[i].maps[j].name+'</li>';
						}
					}
					ele+='</ul>';                           
					$('#map-switch').append(ele);
				}
				$('#map-switch .tab-btn:first-child').click();
			},"json");
		},
		// 设置配置信息
		setConfig:function(){
			var checkNodes=[];
			var zTree = $.fn.zTree.getZTreeObj("map-nodes_tree");
			var nodes=zTree.getCheckedNodes(true);
			for(let i=0;i<nodes.length;i++){
				checkNodes.push(nodes[i].id); //获取选中节点的值
			}
			var groupIndex=$('#map-switch .tab-btn.active').val();
			var mapIndex=$('.map-list.active .map-item.active').data('id');
			var configData=TUI.env.currPlugin._oMap;
			configData[groupIndex].maps[mapIndex].nodes=checkNodes;
			configData[groupIndex].maps[mapIndex].bg=$('#map-url').text();

			$.post('/Plugins/plugin_share_map/php/setConfig.php',{app:TUI.env.currPlugin.app,dataItem:{funCode:TUI.env.currPlugin.fun,data:configData}},function(res){
				TUI.env.currPlugin._oMap=res.data;
				alert('保存成功！');
			},"json");
		},
		// 加载地图
		loadMap:function(container,mapData){
			$('#'+container).empty();
			// 初始化地图
			this.currMap = new TUI.Map(container, {
				plugin: 'plugin_share_map',
				tag: 'maps/'+mapData.bg,
				width: 1500,
				height: 1000
			});
			console.log($('#'+container).width(),$('#'+container).height())
			this.currMap.init();
			$(".map img").css("height","100%");
			if(!TUI.env.currPlugin.onSetting){
				// 不在在配置中标注锚点
				TUI.env.currPlugin.getAnchor(mapData.nodes);
			}
			$(".mapcontent").on("click",function(){
				if(!TUI.env.currPlugin.iShow)$("#show-panel").removeClass("w350");
				TUI.env.currPlugin.iShow = 0;
			});
		},
		// 上传图片
		uploadMap:function(mapData){
			$.ajax({
				url:'/Plugins/plugin_share_map/php/uploadFile.php',
				data:mapData,
				type: 'POST',
				dataType:'json',
				cache: false,
				processData:false,
				contentType:false,
				success:function (res) {
					if(res.error==0){
						// 地图导入完成后 loadMap 
						var groupIndex=$('#map-switch .tab-btn.active').val();
						var mapIndex=$('.map-list.active .map-item.active').data('id');
						// 更新新当前地图的配置信息
						$('#map-url').text(res.index);
						$('#map-panel').empty().append('<img style="width:100%;height:100%" src="'+res.url+'">');
					}else{
						alert(res.msg);
					}
				},
				error : function (data,status) {
					alert('文件上传发生错误：'+data);
				}
			});
		},
		getAnchor:function(nodes){
			$.ajax({
				url:'/API/Model/Property/',
				type:'GET',
				dataType:'JSON',
				data:{tree_node:nodes.join(','),filter_tag:'P_Xaxis,P_Yaxis'},
				success:function(res){
					for(var n=0;n<res.length;n++){
						var node=res[n].data,x='none',y='none';
						for(var i=0;i<node.length;i++){
							if(node[i].F_PropertyIdentifier=='P_Xaxis' && node[i].F_PropertyValue != ""){
								x=node[i].F_PropertyValue;
							}
							if(node[i].F_PropertyIdentifier=='P_Yaxis' && node[i].F_PropertyValue != ""){
								y=node[i].F_PropertyValue;
							}
						}
						TUI.env.currPlugin.setAnchor(res[n].F_EntityID,res[n].F_EntityName,x,y);
					}
				},
				error:function(status){
					alert('获取节点属性error '+status);
				}
			});
		},
		setAnchor: function(nodeID,nodeName,x,y){
			this.currMap.addPoint({
				id: nodeID,
				x: x,
				y: y,
				iconCls: 'heart_red',
				tipCls: 'tip-skygray',
				content:'<div>'+nodeName+'</div>',
				context:this,
				callback:function(id){
					TUI.env.currPlugin.iShow = 1;
					TUI.env.currPlugin.showInfo(id);
				}
			});
		},
		showInfo:function(id){
			$("#show-panel").addClass("w350");
			$("#show-panel").html(
				'<div class="binfo">'
				+'    <h2>建筑信息</h2>'
				+'    <ul>'
				+'        <li>建筑名称：<span>--</span></li>'
				+'        <li>建筑面积：<span>--</span></li>'
				+'        <li>用能人数：<span>--</span></li>'
				+'        <li>设备总数：<span>--</span></li>'
				+'    </ul>'
				+'</div>'
				+'<div class="rinfo">'
				+'    <h2>运行信息</h2>'
				+'    <ul>'
				+'        <li><div><span>0</span>/<small>0</small></div>在线数量 <i></i></li>'
				+'        <li><div><span>0</span>/<small>0</small></div>开机数量 <i></i></li>'
				+'        <li><div><span>0</span></div>违规数量 <i></i></li>'
				+'        <li><div><span>0</span></div>关联策略 <i></i></li>'
				+'    </ul>'
				+'</div>'
				+'<div class="einfo">'
				+'    <h2>能耗信息(<span style="color:#333;">总计</span><span style="color:#00B9B4;margin:0 10px;">工作时间</span><span style="color:#FAAF3B;">非工作时间</span>)</h2>'
				+'    <ul>'
				+'        <li value="'+id+'-1"><p>年</p><div><span style="color:#333;">--</span><span style="color:#00B9B4;">--</span><span style="color:#FAAF3B;">--</span></div></li>'
				+'        <li value="'+id+'-2"><p>月</p><div><span style="color:#333;">--</span><span style="color:#00B9B4;">--</span><span style="color:#FAAF3B;">--</span></div></li>'
				+'        <li value="'+id+'-3"><p>日</p><div><span style="color:#333;">--</span><span style="color:#00B9B4;">--</span><span style="color:#FAAF3B;">--</span></div></li>'
				+'    </ul>'
				+'</div>'
			);
			$.get("/API/Model/Property/?tree_node="+id,function(res){
				$(".binfo li:eq(0) span").html(res[0].F_EntityName);
				$.each(res[0].data,(i,item) => {
					if(item.F_PropertyName == "建筑面积")$(".binfo li:eq(1) span").html(item.F_PropertyValue+item.F_PropertyUnit);
					if(item.F_PropertyName == "用能人数")$(".binfo li:eq(2) span").html(item.F_PropertyValue+item.F_PropertyUnit);
				});
			},"json");
			var device = [];
			$.get("/API/Context/FunToDevice/?fun_code="+TUI.env.currPlugin.fun,function(result){
				result.forEach(ele =>  device.push(ele.F_DeviceTypeID));
				$.post("/Plugins/plugin_share_monitor/php/getChildInfo.php",{
					device:device,
					entity:id,
					app:TUI.env.currPlugin.app,
					type:0
				},function(res){
					$(".binfo li:eq(3) span").html(res.node.length);
					$(".rinfo li:eq(0) small").html(res.node.length);
					res.node.forEach(ele => {
						if(ele.online){
							$(".rinfo li:eq(0) span").html($(".rinfo li:eq(0) span").html()*1+1);
							if(ele.data["StartStopStatus"] && ele.data["StartStopStatus"].value == 1)$(".rinfo li:eq(1) span").html($(".rinfo li:eq(1) span").html()*1+1);
							if(ele.data["illegal"] && ele.data["illegal"].value == 1)$(".rinfo li:eq(2) span").html($(".rinfo li:eq(2) span").html()*1+1);
							if(ele.str)$(".rinfo li:eq(3) span").html($(".rinfo li:eq(3) span").html()*1+1)
						}
						$(".rinfo li:eq(0) i").off("click").on("click",function(){
							TUI.env.main.activeProAndFun("GPCS",TUI.env.currPlugin.app,"fun_gpcs_monitor",{
								deviceID:id,
								type: "online"
							});
						});
						$(".rinfo li:eq(1) i").off("click").on("click",function(){
							TUI.env.main.activeProAndFun("GPCS",TUI.env.currPlugin.app,"fun_gpcs_monitor",{
								deviceID:id,
								type: "StartStopStatus"
							});
						});
						$(".rinfo li:eq(2) i").off("click").on("click",function(){
							TUI.env.main.activeProAndFun("GPCS",TUI.env.currPlugin.app,"fun_gpcs_monitor",{
								deviceID:id,
								type: "illeagl"
							});
						});
						$(".rinfo li:eq(3) i").off("click").on("click",function(){
							TUI.env.main.activeProAndFun("GPCS",TUI.env.currPlugin.app,"fun_gpcs_relation",1);
						});
						$(".einfo li").on("click",function(){
							TUI.env.main.activeProAndFun("GPCS",TUI.env.currPlugin.app,"fun_air_energy_chart",$(this).attr("value"));
						});
					});
				},"json");
			},"json");
			var energy = [];
			$.get("/API/Context/FunToEnergy/?fun_code="+TUI.env.currPlugin.fun,function(result){
				result.forEach(ele =>  energy.push(ele.F_EntityID));
				$.get("/API/Context/FunToDevice/?fun_code="+TUI.env.currPlugin.fun,function(res){
					$.get("/API/Dictionary/DeviceParam/?type_id="+res[0].F_DeviceTypeID+"&param_type=1",function(r){
						var year = TUI.env.currPlugin.formatDate(new Date(),"yyyy");
						$.get("/API/SData/GetEntityDeviceSumData/?entity_str="+id+"&type_id="+res[0].F_DeviceTypeID+"&value_str="+r[0].F_ValueLabel+"&data_type=1&start_date="+year+"-01-01&end_date="+year+"-12-31&group_by=1&haschild=1",function(y){
							$(".einfo li:eq(0) span:eq(0)").html(y[0].F_EnergyData*1+r[0].F_Unit);
							$(".einfo li:eq(0) span:eq(1)").html(y[0].F_WorkingData*1+r[0].F_Unit);
							$(".einfo li:eq(0) span:eq(2)").html(y[0].F_UnWorkingData*1+r[0].F_Unit);
						},"json");
						var month = TUI.env.currPlugin.formatDate(new Date(),"yyyy-MM-01");
						var nextMonth = TUI.env.currPlugin.formatDate(new Date((new Date).getFullYear(), (new Date).getMonth() + 1, 1, 0),"yyyy-MM-dd");
						$.get("/API/SData/GetEntityDeviceSumData/?entity_str="+id+"&type_id="+res[0].F_DeviceTypeID+"&value_str="+r[0].F_ValueLabel+"&data_type=2&start_date="+month+"&end_date="+nextMonth+"&group_by=1&haschild=1",function(y){
							$(".einfo li:eq(1) span:eq(0)").html(y[0].F_EnergyData*1+r[0].F_Unit);
							$(".einfo li:eq(1) span:eq(1)").html(y[0].F_WorkingData*1+r[0].F_Unit);
							$(".einfo li:eq(1) span:eq(2)").html(y[0].F_UnWorkingData*1+r[0].F_Unit);
						},"json");
						var day = TUI.env.currPlugin.formatDate(new Date(),"yyyy-MM-dd");
						$.get("/API/SData/GetEntityDeviceSumData/?entity_str="+id+"&type_id="+res[0].F_DeviceTypeID+"&value_str="+r[0].F_ValueLabel+"&data_type=3&start_date="+day+"&end_date="+day+"&group_by=1&haschild=1",function(y){
							$(".einfo li:eq(2) span:eq(0)").html(y[0].F_EnergyData*1+r[0].F_Unit);
							$(".einfo li:eq(2) span:eq(1)").html(y[0].F_WorkingData*1+r[0].F_Unit);
							$(".einfo li:eq(2) span:eq(2)").html(y[0].F_UnWorkingData*1+r[0].F_Unit);
						},"json");
					},"json");
				},"json");
			},"json");
		},
		formatDate:function(date, fmt) {
			var o = {
				"M+": date.getMonth() + 1, //月份   
				"d+": date.getDate(), //日   
				"h+": date.getHours(), //小时   
				"m+": date.getMinutes(), //分   
				"s+": date.getSeconds(), //秒   
				"q+": Math.floor((date.getMonth() + 3) / 3), //季度   
				"S": date.getMilliseconds() //毫秒   
			};
			if (/(y+)/.test(fmt))
				fmt = fmt.replace(RegExp.$1, (date.getFullYear() + "").substr(4 - RegExp.$1.length));
			for (var k in o)
				if (new RegExp("(" + k + ")").test(fmt))
					fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
			return fmt;
		}
	}
}