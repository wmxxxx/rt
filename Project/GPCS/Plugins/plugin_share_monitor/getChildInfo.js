importScripts("jquery-nodom.js");
self.addEventListener('message',function(e){
    $.post("php/getChildInfo.php",e.data,function(res){
        postMessage(res);
    },"json");
}, false);
