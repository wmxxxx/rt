/**
 * 依赖插件 jQuery hightcharts wdatepicker
 * @authors Your Name (you@example.org)
 * @date    2019-02-22 15:48:15
 * @version $Id$
 */
(function() {
	var _defaultOpt = {
		// 插件默认参数 
		minHeight: '240',

		// 请求接口参数
		reqUrl: '/API/RData/GetDeviceVariantData/',
		reqType: 'GET',
	}
	var extend = function(target, source) {
		for (var obj in source) {
			target[obj] = source[obj];
		}
		return target;
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
	// 前缀补0
	var pad = function(num, n) {
		num = num + '';
		num = num.replace(/,/g, "");
		if ((num - 0) <= 0) {
			n = n - 1;
			num = (0 - num) + '';
			var len = num.toString().length;
			while (len < n) {
				num = "0" + num;
				len++;
			}
			num = '-' + num;
		} else {
			var len = num.toString().length;
			while (len < n) {
				num = "0" + num;
				len++;
			}
		}
		return num;
	}

	// 错误信息格式化
	var formatError = function(status, data) {
		alert(status);
	}

	//生成HTML模板
	var initHtml = function(opt) {
		var rootElem = $('#' + opt.id);
		//检测是否已生成面板
		if (rootElem.attr('class') == 'water-m-panel') {
			return rootElem;
		}
		rootElem.empty();
		var _height = opt.minHeight ? opt.minHeight : rootElem.height();
		if (_height < opt.minHeight) {
			rootElem.height(opt.minHeight + 'px');
			_height = opt.minHeight;
		}
		rootElem.width(_height - 0 + 60 + 'px');
		rootElem.attr('class', 'water-m-panel');
		rootElem.data('data-node', opt.node);
		var fontSize = Math.floor(_height * 0.05);
		rootElem.css('font-size', fontSize + 'px');
		var tmpl = '<div class="m-wrap" style="height:' + _height + 'px;width:' + _height +
			'px;"><div class="m-hd m-name" style="width:' + (_height - 80) + 'px;">水表</div>' +
			'<div class="m-bd" style="width:' + (_height - 60) + 'px;">' +
			'<div class="m-time" style="text-align: right;">--</div>' +
			'<div class="m-data TotalFlux"><span class="d-val" style="font-size:' + Math.floor(fontSize * 2.5) +
			'px;">00000.0000</span><span class="d-suffix" style="margin-left:5px;">--</span></div>' +
			'<div class="m-data InstFlux"><span class="d-name">瞬时流量</span><span class="d-val" style="font-size:' + Math.floor(
				fontSize * 2) + 'px;">0.00</span><span class="d-suffix" style="font-size: 12px;">--</span></div>' +
			'<div class="m-data Pressure"><span class="d-name">瞬时压力</span><span class="d-val" style="font-size:' + Math.floor(
				fontSize * 2) + 'px;">0.00</span><span class="d-suffix" style="font-size: 12px;">--</span></div>' +
			'</div>' +
			'<div class="m-ft">' +
			'<span class="m-online m-status"><img src="" alt="离线" style="width: ' + Math.floor(fontSize * 1.6) +
			'px;"><br>通讯</span>' +
			'<span class="m-leak m-status"><img src="" alt="未知" style="width: ' + Math.floor(fontSize * 1.6) +
			'px;"><br>漏水</span>' +
			'</div></div>';
		rootElem.append(tmpl);
	}

	// 数据获取
	var getData = function(reqPram) {
		var resSet;
		$.ajax({
			url: reqPram.reqUrl,
			async: false, //同步请求
			type: reqPram.reqType,
			dataType: 'JSON',
			data: reqPram.data,
			success: function(res) {
				if (!!res) {
					resSet = {}
					resSet.name = res.name;
					resSet.online = res.online;
					resSet.status = res.status;
					resSet.time=res.time;
					resSet.TotalFlux = {
						value: '00000.0000',
						suffix: '--'
					};
					resSet.InstFlux = {
						value: '0.00',
						suffix: '--'
					};
					resSet.Pressure = {
						value: '0.00',
						suffix: '--'
					};

					for (var i = 0; i < res.variantDatas.length; i++) {
						if (res.variantDatas[i].label == 'TotalFlux') {
							resSet.TotalFlux = {
								value: pad((res.variantDatas[i].value - 0).toFixed(2), 10),
								suffix: res.variantDatas[i].unit
							};
						}
						if (res.variantDatas[i].label == 'InstFlux') {
							if((res.variantDatas[i].value - 0)>99){
								resSet.InstFlux = {
									value: pad((res.variantDatas[i].value - 0).toFixed(0), 3),
									suffix: res.variantDatas[i].unit
								};
							}else if((res.variantDatas[i].value - 0)>9){
								resSet.InstFlux = {
									value: pad((res.variantDatas[i].value - 0).toFixed(1), 4),
									suffix: res.variantDatas[i].unit
								};
							}else{
								resSet.InstFlux = {
									value: pad((res.variantDatas[i].value - 0).toFixed(2), 4),
									suffix: res.variantDatas[i].unit
								};
							}
							
						}
						if (res.variantDatas[i].label == 'Pressure') {
							resSet.Pressure = {
								value: pad((res.variantDatas[i].value - 0).toFixed(2), 4),
								suffix: res.variantDatas[i].unit
							};
						}
					}
				}
				$.ajax({
					url: '/Lib/component/WaterPanel/php/getWleak.php',
					async: false, //同步请求
					type: reqPram.reqType,
					dataType: 'JSON',
					data: {
						'device_code': reqPram.data.device_code
					},
					success: function(res) {
						if (res.length) {
							// 存在漏水
							if (!res[0].F_RecoverTime) {
								resSet.isleak = true;
							} else {
								resSet.isleak = false;
							}
						} else {
							resSet.isleak = false;
						}
					},
					error: function(data, status) {
						// formatError(status);	
					},
				})
			},
			error: function(data, status) {
				// formatError(status);
			},
		});
		console.log(resSet);
		return resSet;
	}
	var renderPanel = function(rootElem, mData) {
		// 绘制数据
		if (mData) {
			rootElem.find('.m-name').html(mData.name);
			rootElem.find('.m-time').html(mData.time);
			rootElem.find('.TotalFlux .d-val').html(mData.TotalFlux.value);
			rootElem.find('.TotalFlux .d-suffix').html(mData.TotalFlux.suffix);
			rootElem.find('.InstFlux .d-val').html(mData.InstFlux.value);
			rootElem.find('.InstFlux .d-suffix').html(mData.InstFlux.suffix);
			rootElem.find('.Pressure .d-val').html(mData.Pressure.value);
			rootElem.find('.Pressure .d-suffix').html(mData.Pressure.suffix);
			if (mData.online) {
				rootElem.find('.m-online img').attr('src', '/Resources/images/component/WaterPanel/on.png').attr('alt', '在线');
			} else {
				rootElem.find('.m-online img').attr('src', '/Resources/images/component/WaterPanel/off.png').attr('alt', '离线');
			}
			if (mData.isleak) {
				rootElem.find('.m-leak img').attr('src', '/Resources/images/component/WaterPanel/off.png').attr('alt', '漏水');
			} else {
				rootElem.find('.m-leak img').attr('src', '/Resources/images/component/WaterPanel/on.png').attr('alt', '正常');
			}
		}

	}

	var api = {
		config: function(opts) {
			console.log(_defaultOpt);
			if (!opts) return _defaultOpt;
			for (var key in opts) {
				_defaultOpt[key] = opts[key];
			}

			return this;
		},
		// 初始化
		init: function(opts) {
			var opt = extend(opts, _defaultOpt);
			if(opt.oTag && opt.oTag.TotalFlux && opt.oTag.InstFlux && opt.oTag.Pressure){
				opt.filter_str=opt.oTag.TotalFlux+','+opt.oTag.InstFlux+','+opt.oTag.Pressure;
				console.log('filter_str',opt.filter_str);
			}else{
				alert("未配置环境变量：{'TotalFlux':累计流量参数,'InstFlux':瞬时流量参数,'Pressure':瞬时压力参数}");
			}
			// 生成html
			initHtml(opt);
			var rootElem = $('#' + opt.id + '.water-m-panel');
			// 获取数据
			var reqPram = {
				reqUrl: opt.reqUrl,
				reqType: opt.reqType,
				data: {
					device_code: opt.node,
					filter_str: opt.filter_str
				}
			};
			var mData = getData(reqPram);
			renderPanel(rootElem, mData);
			return this;
		},
		// 更新
		update: function(opt) {
			var rootElem = $('#' + opt.id + '.water-m-panel');
			// 获取数据
			var reqPram = {
				reqUrl: _defaultOpt.reqUrl,
				reqType: _defaultOpt.reqType,
				data: {
					device_code: opt.node,
					filter_str: _defaultOpt.filter_str
				}
			};
			var mData = getData(reqPram);
			renderPanel(rootElem, mData);
			return this;
		}
	}
	this.WaterPanel = api;
})();
