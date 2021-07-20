/**
 * 依赖插件 jQuery hightcharts wdatepicker
 * @authors Your Name (you@example.org)
 * @date    2019-02-22 15:48:15
 * @version $Id$
 */
(function() {
	// 插件默认参数 
	var _defaultOpt = {
		// 偏移时区
		offsetTime: -8 * 3600 * 1000,
		minHeight: '260',
		minWidth: '900',

		chartType: {
			'Y': {
				name: '年',
				clickEvent: true
			},
			'M': {
				name: '月',
				clickEvent: true
			},
			'D': {
				name: '日',
				clickEvent: false
			}
		},
	}
	var extend = function(target, source) {
		for (var obj in source) {
			if (!target[obj]) {
				target[obj] = source[obj];
			}
		}
		return target;
	}
	var requestAPI = {
		//一年每个月的累计值用能
		ySumDataAPI: {
			reqUrl: '/API/SData/GetDeviceSumData/',
			type: 'GET',
			filiter: function(originData) {
				var data = [];
				var timeStamp;
				for (var i = 0; i < originData.length; i++) {
					timeStamp = Date.UTC(originData[i].sYear, originData[i].sMonth - 1);
					data.push([timeStamp, Number(originData[i].F_EnergyData)]);
				}
				return data;
			}
		},
		// 一个月每天的累计值用能
		mSumDataAPI: {
			reqUrl: '/API/SData/GetDeviceSumData/',
			reqType: 'GET',
			filiter: function(originData) {
				var data = [];
				var timeStamp;
				for (var i = 0; i < originData.length; i++) {
					timeStamp = Date.UTC(originData[i].sYear, originData[i].sMonth - 1, originData[i].sDay);
					data.push([timeStamp, Number(originData[i].F_EnergyData)]);
				}
				return data;
			}
		},
		//起始范围内 每个小时的累计值用能
		dSumDataAPI: {
			reqUrl: '/API/SData/GetDeviceHourInstData/',
			reqType: 'GET',
			filiter: function(originData) {
				var data = [];
				if (originData.length > 0 && originData[0].data.length > 0) {
					originData = originData[0].data[0].data;
					var dateStr, timeStamp;
					for (var i = 0; i < originData.length; i++) {
						dateStr = originData[i].F_Day.split('-');
						timeStamp = Date.UTC(dateStr[0], dateStr[1] - 0 - 1, dateStr[2], originData[i].F_Hour);
						data.push([timeStamp, Number(originData[i].F_EnergyData)]);
					}
				}
				return data;
			}
		},
		//起始范围内 每个小时的瞬时值用能
		dInstDataAPI: {
			reqUrl: '/API/SData/GetDeviceHourInstData/',
			reqType: 'GET',
			filiter: function(originData) {
				var data = [];
				if (originData.length > 0 && originData[0].data.length > 0) {
					originData = originData[0].data[0].data;
					for (var i = 0; i < originData.length; i++) {
						// data.push([(Number(originData[i].F_TimeStamp) - 8 * 3600 * 1000), Number(originData[i].F_DataValue)]);
						data.push([Number(originData[i].F_TimeStamp), Number(originData[i].F_DataValue)]);
					}
				}
				return data;
			}
		},
	}

	// 格式化日期
	var formatDate = function(date, fmt) {
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
	// 计算日期
	var computeDate = function(date, increament) {
		// date:格式 yyyy-MM-dd 
		// increament：时间增量
		var oldDate = new Date(Date.parse(date.replace(/-/g, "/"))); //转换成Data();  
		var newTime = oldDate.getTime() + increament;
		return new Date(newTime);
	}

	// 错误信息格式化
	var formatError = function(status, data) {
		alert(status);
	}

	//生成HTML模板
	var initHtml = function(opt) {
		var rootElem = $('#' + opt.id);
		rootElem.empty();
		rootElem.attr('class', 'meterChart');
		rootElem.data('data-node', opt.node);

		if (opt.height && opt.height > opt.minHeight) {
			rootElem.height(opt.height + 'px');
			var chartH = opt.height - 45;
		} else {
			var _height = rootElem.height();
			if (_height && _height > opt.minHeight) {
				var chartH = _height - 45;
			} else {
				rootElem.height(opt.minHeight + 'px');
				var chartH = opt.minHeight - 45;
			}
		}
		if (opt.width && opt.width > opt.minHeight) {
			rootElem.width(opt.width + 'px');
		} else {
			var _width = rootElem.width();
			if (!_width) {
				rootElem.width(opt.minWidth + 'px');
			}
		}

		var dateItem = dateType = currDate = valueSelect = '';
		if (opt) {
			var now = new Date();
			if (opt.chartType.D) {
				// 日表
				dateType = '<input id="' + opt.id + '-type-D" class="date-type" type="radio" name="' + opt.id +
					'date-type" value="D"><label for="' + opt.id + '-type-D">' + opt.chartType.D.name + '</label>';
				currDate = '<div class="date-input"><button class="sub-btn"></button><input type="text" value="' + formatDate(now,
					'yyyy-MM-dd') + '"><button class="add-btn"></button></div>';
				dateItem += '<div class="curr-D curr-date">' + dateType + currDate + '</div>';
			}
			if (opt.chartType.M) {
				// 月表
				dateType = '<input id="' + opt.id + '-type-M" class="date-type" type="radio" name="' + opt.id +
					'date-type" value="M"><label for="' + opt.id + '-type-M">' + opt.chartType.M.name + '</label>';
				currDate = '<div class="date-input"><button class="sub-btn"></button><input type="text" value="' + formatDate(now,
					'yyyy-MM') + '"><button class="add-btn"></button></div>';
				dateItem += '<div class="curr-M curr-date">' + dateType + currDate + '</div>';
			}
			if (opt.chartType.Y) {
				// 年表
				dateType = '<input id="' + opt.id + '-type-Y" class="date-type" type="radio" name="' + opt.id +
					'date-type" value="Y"><label for="' + opt.id + '-type-Y">' + opt.chartType.Y.name + '</label>';
				currDate = '<div class="date-input"><button class="sub-btn"></button><input type="text" value="' + (now.getFullYear()) +
					'"><button class="add-btn"></button></div>';
				dateItem += '<div class="curr-Y curr-date">' + dateType + currDate + '</div>';
			}
			if (opt.valueLabel && opt.valueLabel.length > 0) {
				var valueLabel = opt.valueLabel;
			}else{
				var valueLabel=[];
			}
			var optionsTypeAll = '<option disabled="disabled">请选择参数</option>';
			var optionsType1 = '<option disabled="disabled">请选择参数</option>';
			// var optionsType2='<option disabled="disabled">请选择参数</option>';
			for (var i = 0; i < valueLabel.length; i++) {
				optionsTypeAll += '<option data-type="' + valueLabel[i].F_ValueType + '" data-suffix="' + valueLabel[i].F_Unit +
					'" value="' + valueLabel[i].F_ValueLabel + '">' + valueLabel[i].F_ValueName + '</option>';
				if (valueLabel[i].F_ValueType == 1) {
					optionsType1 += '<option data-type="1" data-suffix="' + valueLabel[i].F_Unit + '" value="' + valueLabel[i].F_ValueLabel +
						'">' + valueLabel[i].F_ValueName + '</option>';
				} else {
					// optionsType2+='<option data-type="2" data-suffix="'+valueLabel[i].F_Unit+'" value="'+valueLabel[i].F_ValueLabel+'">'+valueLabel[i].F_ValueName+'</option>'
				}
			}
			var valueSelectAll = '<select id="' + opt.id + '-value-type-all" class="opt-item value-type-all">' + optionsTypeAll +
				'</select>';
			var valueSelect1 = '<select id="' + opt.id + '-value-type-1" class="opt-item value-type-1" style="display:none;">' +
				optionsType1 + '</select>';
			// valueSelect2='<select id="'+opt.id+'-value-typea-2" class="opt-item value-type-2">'+optionsType2+'</select>';
			$('#' + opt.id).append('<div class="search-wrap"><div class="opt-item">' +dateItem + '</div>' + valueSelectAll + valueSelect1 +
			'<button class="query-btn">查询</button></div><div id="' + opt.id + '-chart" class="chart-wrap" style="height:' + chartH + 'px;"></div>');
		} else {
			return false;
		}
	}
	// 处理事件
	var initEvent = function(opt) {
		var rootID = '#' + opt.id;
		// 日期类型切换事件
		$(rootID + ' .date-type').change(function() {
			var rootElem = $(this).parents('.meterChart');
			// 取得未改变前的日期
			var preDate = rootElem.find('.curr-date.active input[type="text"]').val();
			preDate = preDate.split('-');
			rootElem.find('.curr-date.active').removeClass('active')
			if (this.value == 'Y') {
				var currDate = preDate[0];
				//隐藏瞬时变量
				rootElem.find('.value-type-all').hide();
				rootElem.find('.value-type-1').show();
			} else if (this.value == 'M') {
				var currDate = preDate[0] + '-' + (preDate[1] ? preDate[1] : '01');
				//隐藏瞬时变量
				rootElem.find('.value-type-all').hide();
				rootElem.find('.value-type-1').show();
			} else {
				var currDate = preDate[0] + '-' + (preDate[1] ? preDate[1] : '01') + '-01';
				//显示瞬时变量
				rootElem.find('.value-type-1').hide();
				rootElem.find('.value-type-all').show();
			}
			rootElem.find('.curr-' + this.value).addClass('active');
			rootElem.find('.curr-' + this.value + ' input[type="text"]').val(currDate);
		});

		// 日历事件
		if (opt.chartType.D) {
			$(rootID + ' .curr-D').on('click', 'input[type="text"]', function() {
				WdatePicker({
					el: this,
					dateFmt: "yyyy-MM-dd",
					maxDate: '%y-%M-%d',
					readOnly: true,
					onpicked: function(dp) {}
				});
			});
			$(rootID + ' .curr-D').on('click', 'button', function() {
				var inputElem = $(this).siblings('input');
				var currDate = $(this).siblings('input').val();

				if ($(this).attr('class') == 'add-btn') {
					var now = formatDate(new Date(), 'yyyy-MM-dd');
					if (currDate < now) {
						var newDate = computeDate(currDate, 24 * 3600 * 1000);
					} else {
						return false;
					}
				} else {
					var newDate = computeDate(currDate, -24 * 3600 * 1000);
				}
				newDate = formatDate(newDate, 'yyyy-MM-dd');
				inputElem.val(newDate);
			});
		}
		if (opt.chartType.M) {
			$(rootID + ' .curr-M').on('click', 'input[type="text"]', function() {
				WdatePicker({
					el: this,
					dateFmt: "yyyy-MM",
					maxDate: '%y-%M',
					readOnly: true,
					onpicked: function(dp) {}
				});
			});
			$(rootID + ' .curr-M').on('click', 'button', function() {
				var inputElem = $(this).siblings('input');
				var currDate = $(this).siblings('input').val().split('-');

				var currY = currDate[0] - 0;
				var currM = currDate[1] - 0;
				if ($(this).attr('class') == 'add-btn') {
					var curr=(new Date(currY,currM-1)).getTime();
					if(curr<=(+new Date())){
						if (currM == 12) {
							inputElem.val(currY + 1 + '-01');
						} else if (currM >= 9) {
							inputElem.val(currY + '-' + (currM + 1));
						} else {
							inputElem.val(currY + '-0' + (currM + 1));
						}
					} else {
						return false;
					}
				} else {
					if (currM == 1) {
						inputElem.val((currY - 1) + '-12');
					} else if (currM > 10) {
						inputElem.val(currY + '-' + (currM - 1));
					} else {
						inputElem.val(currY + '-0' + (currM - 1));
					}
				}
			});
		}
		if (opt.chartType.Y) {
			$(rootID + ' .curr-Y').on('click', 'input[type="text"]', function() {
				WdatePicker({
					el: this,
					dateFmt: "yyyy",
					maxDate: '%y',
					readOnly: true,
					onpicked: function(dp) {}
				});
			});
			$(rootID + ' .curr-Y').on('click', 'button', function() {
				var inputElem = $(this).siblings('input');
				var currY = $(this).siblings('input').val();
				if ($(this).attr('class') == 'add-btn') {
					if (currY < (new Date()).getFullYear()) {
						inputElem.val(currY - 0 + 1);
					} else {
						return false;
					}
				} else {
					inputElem.val(currY - 0 - 1);
				}
			});
		}
		if (opt.chartType.D) {
			$(rootID + '-type-D').attr("checked", "checked");
			$(rootID + ' .curr-D').addClass('active');
		} else {
			if (opt.chartType.M) {
				$(rootID + '-type-M').attr("checked", "checked");
				$(rootID + ' .curr-M').addClass('active');
			} else {
				$(rootID + '-type-Y').attr("checked", "checked");
				$(rootID + ' .curr-Y').addClass('active');
			}
		}

		// 查询数据事件
		$(rootID + ' .query-btn').bind('click', function() {
			var rootElem = $(this).parents('.meterChart');
			newChart(rootElem);
		});
	}
	// 数据获取
	var getData = function(reqUrl, reqType, data, callback) {
		var resSet;
		$.ajax({
			url: reqUrl,
			async: false, //同步请求
			type: reqType,
			dataType: 'JSON',
			data: data,
			success: function(res) {
				resSet = callback(res);
			},
			error: function(data, status) {
				formatError(status);
			}
		});
		return resSet;
	}
	// 获取季度标示区
	var getQuaterBands = function(data) {
		var offsetTime = -8 * 3600 * 1000;
		var offset = 15 * 24 * 3600 * 1000; //half month
		var color = '#A5FCFF'; //'#FCC4FC'
		if (data.length > 1) {
			var plotBands = [];
			var plotItem = {
				from: data[0][0] - offset,
				to: null,
				label: {
					text: data[0][1]
				},
				color: color
			};
			var date, sumData = 0;
			for (var i = 1; i < data.length; i++) {
				date = new Date(data[i][0] + offsetTime);
				if ((date.getMonth() + 1) % 3 == 1) {
					// 月份为 4，7 ，10 ， 1 为季度初
					plotItem.to = data[i][0] + offset;
					plotItem.label = {
						text: sumData
					};
					plotBands.push(plotItem);
					color = color == '#A5FCFF' ? '#FCC4FC' : '#A5FCFF';
					plotItem = {
						from: data[i][0] - offset,
						to: null,
						label: {
							text: data[i][1]
						},
						color: color
					};
				} else {
					sumData += data[i][1];
				}
			}
			plotItem.to = data[data.length - 1][0] + offset;
			plotItem.label = {
				text: sumData.toFixed(2)
			};
			plotBands.push(plotItem);

			return plotBands;
		} else {
			return false;
		}
	}
	// 获取周末标示区
	var getWeekendBands = function(data) {
		var color = '#A5FCFF'; //'#FCC4FC' working color
		var offsetTime = -8 * 3600 * 1000;
		var offset = 12 * 3600 * 1000; //half day
		if (data.length > 1) {
			var plotBands = [];
			var date, sumData = 0;
			var plotItem = {};
			var date = new Date(data[0][0] + offsetTime);
			var startWeek = date.getDay();
			if (startWeek == 0) {
				// plotBands.push({from:data[0][0]-offset,to:data[0][0]+offset,label:{text:(data[0][1]).toFixed(2)},color:'#FCC4FC'});
				plotBands.push({
					from: data[0][0] - offset,
					to: data[0][0] + offset,
					color: '#FCC4FC'
				});
				var sIndex = 1;
				var color = '#FCC4FC';
			} else if (startWeek == 6) {
				plotItem = {
					from: data[0][0] - offset,
					to: null,
					color: '#FCC4FC'
				};
				var color = '#FCC4FC';
				var sIndex = 1;
			} else {
				var color = '#A5FCFF';
				plotItem = {
					from: data[0][0] - offset,
					to: null,
					color: '#A5FCFF'
				};
				var sIndex = 0;
			}
			for (var i = sIndex; i < data.length; i++) {
				date = new Date(data[i][0] + offsetTime);
				if (date.getDay() == 1 || date.getDay() == 6) {
					// 星期一 工作时间
					plotItem.to = data[i][0] + offset;
					// plotItem.label={text:sumData.toFixed(2)};
					plotBands.push(plotItem);
					color = (date.getDay() == 1) ? '#A5FCFF' : '#FCC4FC';
					sumData += data[i][1];
					plotItem = {
						from: data[i][0] - offset,
						to: null,
						color: color
					};
				} else {
					sumData += data[i][1];
				}
			}
			plotItem.to = data[data.length - 1][0] + offset;
			// plotItem.label={text:sumData.toFixed(2)};
			plotBands.push(plotItem);
			return plotBands;
		} else {
			return false;
		}
	}

	//获取工作时间跟非工作时间标示区
	var getWorkingBands = function(data) {
		var workingTime = {
			start: 9,
			end: 18
		};
		var color = '#A5FCFF'; //'#FCC4FC'
		var offsetTime = -8 * 3600 * 1000;
		var offset = 0.5 * 3600 * 1000; //half hour
		if (data.length > 1) {
			var plotBands = [];
			var date, sumData = 0;
			var plotItem = {};
			// 东八区 时间
			var date = new Date(data[0][0] + offsetTime);
			var startHour = date.getHours();
			if (startHour == workingTime.start) {
				plotBands.push({
					from: data[0][0] - offset,
					to: data[0][0] + offset,
					label: {
						text: (data[0][1]).toFixed(2)
					},
					color: '#FCC4FC'
				});
				var sIndex = 1;
				var color = '#FCC4FC';
			} else if (startHour == workingTime.end) {
				plotItem = {
					from: data[0][0] - offset,
					to: null,
					color: '#FCC4FC'
				};
				var color = '#FCC4FC';
				var sIndex = 0;
			} else {
				var color = '#A5FCFF';
				plotItem = {
					from: data[0][0] - offset,
					to: null,
					color: '#A5FCFF'
				};
				var sIndex = 0;
			}
			for (var i = sIndex; i < data.length; i++) {
				date = new Date(data[i][0] + offsetTime);
				if (date.getHours() == workingTime.start || date.getHours() == workingTime.end) {
					plotItem.to = data[i][0] + offset;
					plotItem.label = {
						text: sumData.toFixed(2)
					};
					plotBands.push(plotItem);
					color = color == '#A5FCFF' ? '#FCC4FC' : '#A5FCFF';
					sumData = data[i][1];
					plotItem = {
						from: data[i][0] - offset,
						to: null,
						color: color
					};
				} else {
					sumData += data[i][1];
				}
			}
			plotItem.to = data[data.length - 1][0] + offset;
			plotItem.label = {
				text: sumData.toFixed(2)
			};
			plotBands.push(plotItem);
			return plotBands;
		} else {
			return false;
		}
	}
	var getDayConsumption = function(originData) {
		var offsetTime = -8 * 3600 * 1000;
		// 计算日用量
		var resData = [];
		var sumData=originData[0][1];
		for (var i = 1; i < originData.length; i++) {
			date = new Date(originData[i][0]+offsetTime);
			if (date.getHours() == 23) {
				date = formatDate(date, 'yyyy-MM-dd');
				resData.push({
					date: date,
					value: sumData.toFixed(2)
				});
				sumData = originData[i][1];
			} else {
				sumData += originData[i][1];
			}
		}
		return resData;
	}

	// 绘制图表
	var renderChart = function(chartOpt, xAxis, series, clickEvent) {
		var options = {
			chart: {
				// zoomType: 'xy'
			},
			credits: false,
			legend: {
				enabled: false
			},
			title: {
				text: chartOpt.title,
			},
			subtitle: {
				text: chartOpt.subtitle,
				useHTML:true,
			},
			tooltip: {
				valueSuffix: ' (' + chartOpt.valueSuffix + ')',
				dateTimeLabelFormats: {
					hour: '%Y-%m-%d %H 时',
					day: '%Y-%m-%d',
					month: '%Y-%m',
					year: '%Y'
				},
				shared: true
			},
			plotOptions: {
				series: {
					cursor: 'pointer',
					marker: {
						enabled: false
					},
					events: {
						click: clickEvent ? clickEvent : ''
					}
				}
			},
			xAxis: xAxis,
			yAxis: [{
				lineColor: '#E8E8E8',
				lineWidth: 1,
				labels: {
					style: {
						color: Highcharts.getOptions().colors[1]
					}
				},
				title: {
					text: '(' + chartOpt.valueSuffix + ')',
					style: {
						color: Highcharts.getOptions().colors[1]
					}
				},
			}],
			series: series
		};

		// $('#'+chartOpt.rootID+'-chart').highcharts(options);
		var chart = Highcharts.chart(chartOpt.rootID + '-chart', options);
	}

	var newChart = function(rootElem) {
		// 查询条件
		var node = rootElem.data('data-node');
		var dateType = rootElem.find('input[type="radio"]:checked').val();

		var seriesData, dateTimeLabelFormats, chartType, xAxis = [{
			type: 'datetime'
		}];
		//调用接口的参数
		switch (dateType) {
			case 'Y':
				{
					var currLabel = rootElem.find('.value-type-1 option:selected');
					var labelName = currLabel.text();
					var labelValue = currLabel.val();
					var labelType = currLabel.data('type');
					var valueSuffix = currLabel.data('suffix');
					var currY = rootElem.find('.curr-' + dateType + ' input[type="text"]').val();
					var title = currY + '年 （' + labelName + '） 用能图';
					var subtitle = '';
					var startDate = currY + '-01-01';
					var now = new Date();
					if (currY == now.getFullYear()) {
						var endDate = formatDate(now, 'yyyy-MM-dd');
					} else {
						var endDate = formatDate((new Date(currY, 12, 0)), 'yyyy-MM-dd');
					}
					var data = {
						data_type: 2,
						group_by: '1',
						device_str: node,
						value_str: labelValue,
						start_date: startDate,
						end_date: endDate
					};
					//请求数据
					var req = requestAPI.ySumDataAPI;
					seriesData = getData(req.reqUrl, req.reqType, data, req.filiter);
					// 获取	plotBands		
					xAxis[0].plotBands = getQuaterBands(seriesData);
					xAxis[0].dateTimeLabelFormats = {
						month: '%m'
					};
					chartType = 'column';

					var clickEvent = function(event) {
						var timeStamp = event.point.category;
						var date = new Date(timeStamp + 8 * 3600 * 1000);
						date = formatDate(date, 'yyyy-MM');
						var targetParents = this.options.id;
						$('#' + targetParents + '-type-M').prop('checked', true).trigger('change');
						$('#' + targetParents + ' .curr-M input[type="text"]').val(date);
						$('#' + targetParents + ' .query-btn').click();
					}
				};
				break;
			case 'M':
				{
					var currLabel = rootElem.find('.value-type-1 option:selected');
					var labelName = currLabel.text();
					var labelValue = currLabel.val();
					var labelType = currLabel.data('type');
					var valueSuffix = currLabel.data('suffix');
					var currYM = rootElem.find('.curr-' + dateType + ' input[type="text"]').val();
					var startDate = currYM + '-01';
					currYM = currYM.split('-');
					var title = currYM[0] + '年-' + currYM[1] + '月 （' + labelName + '） 用能图';
					var subtitle = '';
					var now = new Date();
					if (currYM[0] == now.getFullYear() && (currYM[1] - 1) == now.getMonth()) {
						var endDate = formatDate(now, 'yyyy-MM-dd');
					} else {
						var endDate = formatDate((new Date(Number(currYM[0]), Number(currYM[1]), 0)), 'yyyy-MM-dd');
					}
					var node = rootElem.data('data-node');
					var data = {
						data_type: 3,
						group_by: '1',
						device_str: node,
						value_str: labelValue,
						start_date: startDate,
						end_date: endDate
					};
					console.log(data)
					//请求数据
					var req = requestAPI.mSumDataAPI;
					seriesData = getData(req.reqUrl, req.reqType, data, req.filiter);
					// 获取	plotBands			
					xAxis[0].plotBands = getWeekendBands(seriesData);
					xAxis[0].dateTimeLabelFormats = {
						day: '%e'
					};
					chartType = 'column';

					var clickEvent = function(event) {
						var timeStamp = event.point.category;
						var date = new Date(timeStamp + 8 * 3600 * 1000);
						date = formatDate(date, 'yyyy-MM-dd');
						var targetParents = this.options.id;
						var labelValue = $('#' + targetParents + '-value-type-1').val();
						$('#' + targetParents + '-value-type-all').val(labelValue);
						$('#' + targetParents + '-type-D').prop('checked', true).trigger('change');
						$('#' + targetParents + ' .curr-D input[type="text"]').val(date);
						$('#' + targetParents + ' .query-btn').click();
					}
				};
				break;
			case 'D':
				{
					var currLabel = rootElem.find('.value-type-all option:selected');
					var labelName = currLabel.text();
					var labelValue = currLabel.val();
					var labelType = currLabel.data('type');
					var valueSuffix = currLabel.data('suffix');
					var currYMD = rootElem.find('.curr-' + dateType + ' input[type="text"]').val();
					var startDate = computeDate(currYMD, -2 * 24 * 3600 * 1000);
					startDate = formatDate(startDate, 'yyyy-MM-dd');
					var endDate = currYMD;

					var data = {
						device_str: node,
						value_label: labelValue,
						start_date: startDate,
						end_date: endDate
					};
					if (labelType == 1) {
						var title = ' 72小时（' + labelName + '）用能图';
						var subtitle = '<pre>';
						//请求数据 累计变量类型
						var req = requestAPI.dSumDataAPI;
						seriesData = getData(req.reqUrl, req.reqType, data, req.filiter);
						// 获取plotBands
						xAxis[0].plotBands = getWorkingBands(seriesData);
						// 获取日用量
						var dayConsumption = getDayConsumption(seriesData);
						for (var i = 0; i < dayConsumption.length; i++) {
							subtitle += '      ' + dayConsumption[i].date + ': ' + dayConsumption[i].value + valueSuffix;
						}
						subtitle+='</pre>'
						chartType = 'column';
					} else {
						data.time_filter = 1;
						var title = '72小时（' + labelName + '）用量图';
						var subtitle = startDate + ' 至 ' + endDate;
						//请求数据 瞬时变量类型
						var req = requestAPI.dInstDataAPI;
						data.time_filter = 1;
						seriesData = getData(req.reqUrl, req.reqType, data, req.filiter);
						chartType = 'spline';
					}
					xAxis[0].dateTimeLabelFormats = {
						hour: '%H'
					};
					xAxis[0].labels = {
						formatter: function() {
							return new Date(this.value - 8 * 3600 * 1000).getHours() + ' 时';
						}
					};
				};
				break;
			default:
				;
		}
		var series = [{
			id: rootElem.attr('id'),
			data: seriesData,
			name: labelName,
			type: chartType
		}];
		var chartOpt = {
			rootID: rootElem.attr('id'),
			title: title,
			subtitle: subtitle,
			labelName: labelName,
			valueSuffix: valueSuffix
		};
		if (clickEvent) {
			renderChart(chartOpt, xAxis, series, clickEvent);
		} else {
			renderChart(chartOpt, xAxis, series);
		}

	}

	var api = {
		config: function(opts) {
			if (!opts) return _defaultOpt;
			//有参数传入，通过key将_defaultOpt的值更新为用户的值
			for (var key in opts) {
				_defaultOpt[key] = opts[key];
			}
			return this;
		},
		// 初始化
		init: function(opts) {
			var opt = extend(opts, _defaultOpt);
			console.log(opt)
			// 生成html
			initHtml(opt);
			// 绑定事件
			initEvent(opt);
			// 初始化
			if (opt.valueLabel && opt.valueLabel.length > 0) {
				$('#' + opt.id + ' .query-btn').click();
			}
		},
	}
	this.MeterChart = api;
})();
