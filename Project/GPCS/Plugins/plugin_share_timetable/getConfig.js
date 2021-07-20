importScripts("jquery-nodom.js");
$.post("/Plugins/plugin_timetable/php/table.php",function(res){
    postMessage(res);
    self.close();
},"json");