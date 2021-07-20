$(document).ready(function(){
	var month = queryString("month")+"-01";
	var id = queryString("id");
	var date = new Date(month);
	var month_st = date.Format("yyyy-MM-dd");
	var month_ed = new Date(date.getFullYear(),date.getMonth()+1,0).Format("yyyy-MM-dd");
	var data = [];
	while(month_st <= month_ed){
		data.push(month_st);
		var stime_ts = new Date(month_st).getTime();
		var next_date = stime_ts + (24 * 3600 * 1000);
		month_st = new Date(next_date).Format("yyyy-MM-dd");
	}
	var tbody = '';
	var dateDom = '';
	var startDay = new Date(date.getFullYear(),date.getMonth(),1).getDay();
	var endDay = new Date(date.getFullYear(),date.getMonth()+1,0).getDay();
	for(var i=0;i<startDay;i++){
		dateDom += '<td></td>';
	}
	for(var i=0;i<data.length;i++) {
		var day = (new Date(data[i])).getDay();
		var key = new Date(data[i]).Format("yyyy-MM-dd");
		var keydt = new Date(data[i]).Format("dd");
		dateDom += '<td><span class="date" id="dc'+key+'">'+keydt+'</span><div class="valc" id="c'+key+'"></div><div class="show-tip">更多</div></td>';
		if(day == 6){
			tbody += '<tr>'+dateDom+'</tr>';
			dateDom = '';
		}
	}
	for(var i=endDay+1;i<7;i++) {
		dateDom += '<td><span class="date"></span></td>';
	}
	tbody += dateDom+'</tr>';
	$('#calendar tbody').html(tbody);
	$.post("php/getCalenar.php",{id:id,start:month,end:month_st},function(res){
		data.forEach(ele => {
			var action = [];
			var info = "";
			res.data.forEach(cycle => {
				if(cycle.F_open && ele >= cycle.F_start && ele <= cycle.F_end){
					var week = new Date(ele).getDay();
					var isToday = 0;
					if(week == 0)week = 7;
					if(cycle.F_type_val == "0" || cycle.F_type_val.indexOf(week) != -1)isToday = 1;
					// 工作日
					if(cycle.F_day == 1){
						if(res.work[0].includes(ele))isToday = 0;// 在系统节假日表中
						if("6,7".indexOf(week) != -1 && res.work[1] && !res.work[1].includes(ele))isToday = 0;// 为节假日 and 不在系统工作日表中
					}
					// 节假日
					if(cycle.F_day == 2){
						if(res.work[1].includes(ele))isToday = 0;// 在系统工作日表中
						if("1,2,3,4,5".indexOf(week) != -1 && res.work[0] && !res.work[0].includes(ele))isToday = 0;// 为工作日 and 不在系统节假日表中
					}
					if(isToday)action = [...action,...cycle.sub];
				}
			});
			// 排序
			action.sort(function(a,b){
				return a.F_time < b.F_time ? -1 : 1;
			});
			// 去重
			action = action.map(ele => ele.F_time+' ['+ele.F_name+']');
			action = [...new Set(action)];
			action.forEach(aele => info += '<div class="message-list-item">'+aele+'</div>');
			$("#c"+ele).html(info);
		});
		$(".valc").each(function(){
			var childs = $(this).children();
			if(childs.length >= 3)$(this).parent().find(".show-tip").css("opacity",1);
		});
		$(".show-tip").off("click").on("click",function(){
			showMore(this);
		});
		var today = new Date().Format("yyyy-MM-dd");
		$.each($("#c"+today+" div"),function(i,k){
			window.parent.$(".aLeft").append("<p>"+k.innerText+"</p>");
		});
	},"json");
	function showMore(obj){
		layer.open({
			type: 4,
			shadeClose: true,
			shade: 0.01,
			maxHeight: 130,
			content: [$(obj).prev().html(),"#d"+$(obj).prev().attr("id")]
		});
	}
	// 获取url参数
	function queryString(key){
		var reg = new RegExp("(^|&)"+key+"=([^&]*)(&|$)","i");
		var r = window.location.search.substr(1).match(reg);
		if(r!=null)return unescape(r[2]);return null;
	}
});