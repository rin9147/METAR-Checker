var week = new Array("Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat");

function set2fig(num) {
    var ret;
    if( num < 10 ) {
        ret = "0" + num;
    } else { 
        ret = num;
    }
    return ret;
}

function showUTC() {
    var nowUTC = new Date();

    var yearUTC     = nowUTC.getUTCFullYear();
    var monthUTC    = set2fig(nowUTC.getUTCMonth() + 1);
    var dayUTC      = nowUTC.getUTCDay();
    var dateUTC     = set2fig(nowUTC.getUTCDate());
    var hourUTC     = set2fig(nowUTC.getUTCHours());
    var minUTC      = set2fig(nowUTC.getUTCMinutes());
    var secUTC      = set2fig(nowUTC.getUTCSeconds());

    var strDateUTC = yearUTC + "-" + monthUTC + "-" + dateUTC + " " + week[dayUTC] + ". " + hourUTC + ":" + minUTC + ":" + secUTC;
    var strTimeUTC = hourUTC + ":" + minUTC + ":" + secUTC;

    document.getElementById("showDateUTC").innerHTML = strDateUTC;
    //document.getElementById("showTimeUTC").innerHTML = strTimeUTC;
}

function showLOCAL() {
    var nowLOCAL = new Date();

    var yearLOCAL     = nowLOCAL.getFullYear();
    var monthLOCAL    = set2fig(nowLOCAL.getMonth() + 1);
    var dayLOCAL      = nowLOCAL.getDay();
    var dateLOCAL     = set2fig(nowLOCAL.getDate());
    var hourLOCAL     = set2fig(nowLOCAL.getHours());
    var minLOCAL      = set2fig(nowLOCAL.getMinutes());
    var secLOCAL      = set2fig(nowLOCAL.getSeconds());

    var strDateLOCAL = yearLOCAL + "-" + monthLOCAL + "-" + dateLOCAL + " " + week[dayLOCAL] + ". " + hourLOCAL + ":" + minLOCAL + ":" + secLOCAL;
    var strTimeLOCAL = hourLOCAL + ":" + minLOCAL + ":" + secLOCAL;

    document.getElementById("showDateLOCAL").innerHTML = strDateLOCAL;
    //document.getElementById("showTimeLOCAL").innerHTML = strTimeLOCAL;
    
}

setInterval('showUTC()', 1000);
setInterval('showLOCAL()', 1000);

/*
const timer = 600000;
window.addEventListener('load',function(){
    setInterval('location.reload()',timer);
});
*/