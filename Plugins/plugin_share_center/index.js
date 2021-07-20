$(document).ready(function(){
	var app = queryString("app");
	var fun = queryString("fun");
	var code = queryString("code");
	var user = queryString("user");
	var param;
	init();
	function init(){
		param = {
			device:[],
			qz:[],
			tx:[],
			rx:[],
			ws:[],
			start:'00:00:00',
			end:'00:00:00',
			lx:'00:00:00',
			ratio:1
		};
		// 获取设备模板
		$.get("/API/Context/FunToDevice/?fun_code="+fun,function(result){
			result.forEach(ele => param.device.push(ele.F_DeviceTypeID));
			$.post("/Php/plugin/getTreePanelData.php",{plugin:code,fun:fun,app:app,energy_node:false,energy_id:'',device_node:true,device_id:param.device.join(","),filter:'',online:true},function(res){
				var nodes = [];
				res.forEach(ele => {
					if(ele.nTplId != null)nodes.push(ele.id);
				});
				$.post("php/init.php",{app:app},function(r){
					r.qz.forEach(ele => {
						if(nodes.includes(ele.F_node_id))param.qz.push(ele.F_node_id);
					});
					r.tx.forEach(ele => {
						if(nodes.includes(ele.F_node_id))param.tx.push(ele.F_node_id);
					});
					r.rx.forEach(ele => {
						if(nodes.includes(ele.F_node_id))param.rx.push(ele.F_node_id);
					});
					param.start = r.time.F_start;
					param.end = r.time.F_end;
					param.lx = r.time.F_type_val;
					var ws = param.qz.concat(param.tx,param.rx);
					ws = new Set(ws);
					ws = [...ws];
					ws.forEach(ele => {
						var index = nodes.indexOf(ele);
						if(index > -1)nodes.splice(index,1);
					});
					param.ws = nodes;
					var pWidth = $(".warpper").width() / 4 / 350;
					var pHeight = $(".warpper").height() / 580;
					var ratio = pWidth < pHeight ? pWidth : pHeight;
					param.ratio = ratio;
					layui.laytpl(content_tpl.innerHTML).render(param,function(html){
						document.getElementById('warp').innerHTML = html;
						oper();
					});
				},"json");
			},"json");
		},"json");
	}
	function oper(){
		$(".timeSet").on("click",function(){
			layui.layer.open({
				type:1,
				title:'时段设置',
				area:['350px','350px'],
				resize:false,
				id:'timeSet_open',
				btn:['取消','保存'],
				content:'',
				success:function(){
					layui.laytpl(timeSet_tpl.innerHTML).render(param,function(html){
						document.getElementById('timeSet_open').innerHTML = html;
					});
					layui.laydate.render({
						elem: '#test4',
						type: 'time',
						done: function(value){
							param.start = value;
						}
					});
					layui.laydate.render({
						elem: '#test5',
						type: 'time',
						done: function(value){
							param.end = value;
						}
					});
					layui.laydate.render({
						elem: '#test6',
						type: 'time',
						done: function(value){
							param.lx = value;
						}
					});
				},
				btn2:function(){
					$.post("php/setTime.php",{start:param.start,end:param.end,lx:param.lx,app:app},function(res){
						init();
					},"json");
				}
			});
		});
		$(".nodeRel").on("click",function(){
			var zTree;
			layui.layer.open({
				type:1,
				title:'关联设备',
				area:['350px','600px'],
				resize:false,
				id:'nodeRel_open',
				btn:['取消','保存'],
				content:'',
				success:function(){
					var on = 0;
					zTree = new TUI.TreePanel("nodeRel_open",{
						plugin:code,
						fun:fun,
						app:app,
						expand:true,
						selected:true,
						checkbox:true,
						expand_type:"all",
						device_node:true,
						device_id:param.device.join(","),
						cascaded:{ "Y": "s", "N": "ps" },
						online:true,
						screen:1,
						callback:function(){
							if(on == 0)checkTreeNodes("nodeRel_open",param.qz);
							on++;
						}
					});
				},
				btn2:function(){
					var ids = [];
					$.each(zTree.getCheckedNodes(),function(i,k){
						if(k.nTplId != null)ids.push(k.id);
					});
					$.post("php/forceRel.php",{node:ids.join(","),app:app},function(res){
						init();
					},"json");
				}
			});
		});
		$(".lookInfo").on("click",function(){
			var zTree;
			layui.layer.open({
				type:1,
				title:'查看详情',
				area:['350px','600px'],
				resize:false,
				id:'wsRel_open',
				content:'',
				success:function(){
					var on = 0;
					zTree = new TUI.TreePanel("wsRel_open",{
						plugin:code,
						fun:fun,
						app:app,
						expand:true,
						selected:true,
						checkbox:true,
						expand_type:"all",
						device_node:true,
						device_id:param.device.join(","),
						online:true,
						callback:function(){
							if(on == 0)checkTreeNodes("wsRel_open",param.ws);
							on++;
						}
					});
				}
			});
		});
		$(".str").off("click").on("click",function(){
			parent.TUI.env.main.activeProAndFun("GPCS",app,"fun_gpcs_elastic");
		});
		$(".str1").off("click").on("click",function(){
			parent.TUI.env.main.activeProAndFun("GPCS",app,"fun_gpcs_flex");
		});
		$(".gro").off("click").on("click",function(){
			parent.TUI.env.main.activeProAndFun("GPCS",app,"fun_gpcs_group");
		});
	}
	function checkTreeNodes(tree,id){
		var treeObj = $.fn.zTree.getZTreeObj(tree+'_tree');
		id.forEach(ele => {
			var node = treeObj.getNodeByParam("id",ele);
			treeObj.checkNode(node);
		});
	}
	// 获取url参数
	function queryString(key){
		var reg = new RegExp("(^|&)"+key+"=([^&]*)(&|$)","i");
		var r = window.location.search.substr(1).match(reg);
		if(r!=null)return unescape(r[2]);return null;
	}
});