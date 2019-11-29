//基础公共处理类
var bodyW = $("body").width();
var bodyH = $(window).height();
//$("img.data-lazy").lazyload({/*effect: "fadeIn",*/threshold : 200,});
//取得get值
var $_get = (function(){
    var url = window.document.location.href.toString();
    var u = url.split("?");
    if(typeof(u[1]) == "string"){
        u = u[1].split("&");
        var get = {};
        for(var i in u){
            var j = u[i].split("=");
            get[j[0]] = j[1];
        }
        return get;
    } else {
        return {};
    }
})();
//基础签名算法
function sign(options){
	var newOption = Object.assign({}, options);
	delete newOption.signkey;
	$.each(options,function(k,v){
		
	});
	var nopt = objKeySort(newOption);
	var str = '';
	$.each(nopt,function(k,v){
		str += k+'='+v+'&';
	});
	str = str.substring(0,str.length-1)+options.signkey;
	options.sign =  md5(str);
	return options;
}
//对象根据键值字典排序
function objKeySort(obj) {
    var newkey = Object.keys(obj).sort();
    var newObj = {};
    for (var i = 0; i < newkey.length; i++) {
        newObj[newkey[i]] = obj[newkey[i]];
    }
    return newObj;
}
//请求接口分发处理
$.fn.Method = function(m,options, sucess){
	var defaults = {
        'method'	: m,
		'user_id'	: 1,
		'signkey'	: 'hsktz5jkuxcq',
		'timestamp'	: Date.parse(new Date()),
	}
	var options = $.extend(defaults, options);
	options = sign(options);
	var tis = this;
	//TODO 验证签名
	var layLoad = layer.load(2);
	$.post(Config.apiUrl,options,function(data){
		layer.close(layLoad);
		if(data.errCode == 0){
			sucess(data);
			/*Do.ready(m,function () {
				window[m](tis,data,options);
			})*/
		}else{
			layer.msg(data.errMsg);
		}
	})
}