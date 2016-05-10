var xmlHttp;
function srvTime(){
try {
    //FF, Opera, Safari, Chrome
    xmlHttp = new XMLHttpRequest();
}
catch (err1) {
    //IE
    try {
        xmlHttp = new ActiveXObject('Msxml2.XMLHTTP');
    }
    catch (err2) {
        try {
            xmlHttp = new ActiveXObject('Microsoft.XMLHTTP');
        }
        catch (eerr3) {
            //AJAX not supported, use CPU time.
            alert("AJAX not supported");
        }
    }
}
xmlHttp.open('HEAD',window.location.href.toString(),false);
xmlHttp.setRequestHeader("Content-Type", "text/html");
xmlHttp.send('');
return xmlHttp.getResponseHeader("Date");
}

var st = srvTime();

function date_time(id)
{
        date = new Date(srvTime());
        year = date.getFullYear();
        month = date.getMonth();
        months = new Array('January', 'February', 'March', 'April', 'May', 'June', 'Jully', 'August', 'September', 'October', 'November', 'December');
        d = date.getDate();
        day = date.getDay();
        days = new Array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
        h = date.getHours();
        if(h<10)
        {
                h = "0"+h;
        }
        m = date.getMinutes();
        if(m<10)
        {
                m = "0"+m;
        }
        s = date.getSeconds();
        if(s<10)
        {
                s = "0"+s;
        }
        result = ''+days[day]+' '+months[month]+' '+d+' '+year+' '+h+':'+m+':'+s;
        document.getElementById(id).innerHTML = result;
        setTimeout('date_time("'+id+'");','1000');
        return true;
}