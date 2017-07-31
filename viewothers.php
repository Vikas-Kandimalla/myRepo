<?php 
session_start();
include_once "create_db.php";
if ( !isset($_SESSION['uid']) || !isset($_SESSION['user']) || !isset($_SESSION['email']) || !isset($_SESSION['dbstatus'])) {
	echo "Session not set";
	header("Location: http://localhost/p/index.php");
}
if ( $_SESSION['dbstatus'] == 0) {
	createdatabase($_SESSION['uid']);
}

 require "php/calendar.php";

 $cal = new calendar;
 function _curmonth() {
	 $content = '';
	 if(isset($_GET['month']))
		 $content .= sprintf("%02d",$_GET['month']);
	 else
		$content = null;
	if(isset($_GET['year']))	
		$content .= sprintf("%02d",$_GET['year']);
	else
		$content .= null;
    
return $content;
}

 //$cal->setdate();
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="icon" type="image/png" sizes="16x16" href="styles/favicon-16x16.png">
    <link href="jquery/jquery-ui.theme.css" rel="stylesheet">
    <link href="jquery/jquery-ui.css" rel="stylesheet">
    <link href="styles/week-view.css" rel="stylesheet">
    <link href="styles/day-view.css" rel="stylesheet">
	<link href="styles/mini_calendar.css" rel="stylesheet">
    
     <link href="styles/month-view.css" rel="stylesheet">
    <script src='jquery/jquery.js'></script>        <script src='jquery/jquery-ui.js'></script>
    
<title>calender</title>
      
    <script>
	var ofdate = [{ 'date' :"0000-00-00",'numofevesinthatdate' : 0}];
            var loaddata , ofdatedata;
	var tempeventid,tempname,tempdate,temptime,tempduration,tempstatus,temprectime;
      var eventstatus = ["docadd","patientadd","referadd","note","checkedin","checkedout","docconf","patientconf","requestconf"]; 
	   
        var tempevent;
        function eventstruct(ID,name,date,time,duration,status){
            this.ID = ID;
            this.name = name;
            this.time = time;
            this.duration = duration;
            this.date = date;
			this.status = status;
        }

          function  mininext(){
		  	var date =  document.getElementById("ndate").innerHTML;
					var month = date.slice(0,2);
					var year = date.slice(2);
					var xhttp = new XMLHttpRequest();
					xhttp.onreadystatechange = function() {
								if (xhttp.readyState == 4 && xhttp.status == 200) {
												
												document.getElementById("mini_cal").innerHTML = xhttp.responseText;
									}
					};
					xhttp.open("GET","php/mini_calendar.php?month="+month+"&year="+year,true);
					xhttp.send();
					
	  }
	   function  miniprev(){
		  	var date =  document.getElementById("pdate").innerHTML;
					var month = date.slice(0,2);
					var year = date.slice(2);
					var xhttp = new XMLHttpRequest();
					xhttp.onreadystatechange = function() {
								if (xhttp.readyState == 4 && xhttp.status == 200) {
												
												document.getElementById("mini_cal").innerHTML = xhttp.responseText;
									}
					};
					xhttp.open("GET","php/mini_calendar.php?month="+month+"&year="+year,true);
					xhttp.send();
					
	  }
       
		function addevent(_eventid,_name,_date,_time,_duration,_class=null,_status) {
          
		
		//  console.log(_name + '   ' + _status);
          var name = _name;
          var day = _date;
          var time = _time;
		  time = _time.slice(0,5);
		  var hours = parseInt(_time.slice(0,2));
		  
		  var min = parseInt(_time.slice(3,5));
		  var flag = 0,flaga = 0;
		  hoursa = parseInt(hours + _duration/60);
		  mina = parseInt(min + _duration%60);
		if ( mina > 59) {
			mina = mina%60;
			hoursa++;
		}
		if ( hoursa > 12) {
			  hoursa = hoursa%12;
			flag =1;  
		  } 
		  if ( hours > 12) {
			  hours = hours%12;
			  flaga = 1;
		  }
		  
		  if ( hoursa < 10) {
			  hoursa = '0' + hoursa;
		  }
		  
		  if ( mina < 10) {
			  mina = '0' + mina; 
		  }
		  if ( hours < 10) {
			  hours = '0' + hours;
		  }
		  
		  if ( min < 10) {
			  min = '0' + min; 
		  }
		  
		  var endtime = '' + hoursa + ':' + mina;
		 if( flag == 1) {
			 endtime = hoursa+':'+mina+'pm';
		 }
		 else { 
			 endtime = '' + hoursa + ':' + mina+'am';
			 }
			 if ( flaga == 1) {
				 time = hours +':'+ min + 'pm';
			 }
			 else {
				 time = hours +':'+ min + 'am';
			 }
			 
			 
        var eventid = _eventid;
        
        var event = '<div id="mw-eventid-'+eventid+'" class="mw-events '+_class+' eventsdis '+eventstatus[_status]+'" style = "z-index : 2"><a href="javascript:void(0)"><div>'+time+'  '+name+'</div></a></div>';
		$(event).appendTo("td#mw-day-"+day);
        
		var aw_event = '<div class="event-aw resizable eventsdis '+eventstatus[_status]+'" id="aw-eventid-'+eventid+'"  style="top : 0px; z-index : 11" ><div class="aw-drag">'+name+'</div><div class="aw-events" id ="some_id"><div><span id="aw-starttime-eventid-'+eventid+'">'+'From :&nbsp;'+time+'&nbsp;<br></span><span id="aw-endtime-eventid-'+eventid+'">To&nbsp;&nbsp;&nbsp;:&nbsp;'+endtime+'</span></div></div></div>'; 
        $(aw_event).appendTo("#aw-day-"+day);
         var ad_event = '<div class="event-ad resizable eventsdis '+eventstatus[_status]+'" id="ad-eventid-'+eventid+'"  style="top : 0px; z-index : 11" ><div class="ad-drag">'+name+'</div><div class="ad-events" id ="some_id"><div><span id="ad-starttime-eventid-'+eventid+'">'+'From :&nbsp;'+time+'&nbsp;<br></span><span id="ad-endtime-eventid-'+eventid+'">To&nbsp;&nbsp;&nbsp;:&nbsp;'+endtime+'</span></div></div></div>'; 
        $(ad_event).appendTo("#ad-day-"+day);
        
    
        
      }
	   function  events_reload() {
		  $(".eventsdis").remove();
		  $(".eventsdis").remove();
		  loadevents();
	  }
		function viewevents(_date){
		  var id = "#div-day-" + _date;
		  var tid = "#mw-day-" + _date + " > ";
		  $(tid + "div.hideevents").css({"display" : "block"});
		  $(id).css({"overflow" : "visible" , "border" : "0px solid black" , "z-index" : "100"});
		  $(tid + "div.view-more").css({"display" : "none"});
		  $(tid + " div").css({"z-index" : 100});
		 $("div.view-less").css({"display" : "block"});
		  var event = '<div class="view-less eventsdis"><a class="view-less" href="javascript:hideevents(\''+_date+'\')">View less</a></div>';
		  $(event).appendTo("td#mw-day-"+_date);
		 
		  
	  }
		function hideevents(_date){
		   var id = "#div-day-" + _date;
		     var tid = "#mw-day-" + _date + " > ";
		  $(tid + "div.hideevents").css({"display" : "none"});
		  $(tid + " div").css({"z-index" : 2});
		  $(id).css({"overflow" : "hidden" , "border" : "0px solid black" , "z-index" : "2"});
		  $(tid + "div.view-more").css({"display" : "block"});
		 $(tid + "div.view-less").remove();
		
		 
	  }
		function viewmore(_day) {
		  
		  var event = '<div class="view-more"><a class="view-more eventsdis" href="javascript:viewevents(\''+_day+'\')">View more</a></div>';
		  $(event).appendTo("td#mw-day-"+_day);
		   
	  }
		function event_form(_day = null) {
               
                
               document.getElementById("addevent-name").value = null;
               document.getElementById("addevent-time").value = null;
              
               
               document.getElementById("addevent-date-picker").value = _day; 
        $("#addevent-form").dialog("open");
           }
		function loadevents() {
        var load;
        
        var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
            
            if (xhttp.readyState == 4 && xhttp.status == 200) {
            load = xhttp.responseText;
                        loaddata = load;
                console.log(load);
                                
				var res = JSON.parse(load);
				    var flag = 1; var i = 0;
                    numofevents = res.events[0].numofevents;
                  
        
					
					var tempdate,inc = 0,incflag = 0;
		  var j=0;
			
                var p = 0;
                        for ( i =1; i <= numofevents ; i++) {
                                
                           
                    
                            
                           
        
                                
								if ( j == 0) {
						addevent(res.events[i].ID,res.events[i].name,res.events[i].eventdate,res.events[i].eventtime,res.events[i].eventduration);			
						tempdate = res.events[i].eventdate;
						j++;
				}
			  
			
			   else if ( j  != 0 ) {	
				  if ( tempdate == res.events[i].eventdate) {
					  if ( j > 0) {
						  incflag = 1;
						  ofdate[inc] = { 'id' : i,'date' : tempdate , 'numofevesinthatdate' : (j+1)};
					  }
					
					    if ( j > 2) {
							
						  addevent(res.events[i].ID,res.events[i].name,res.events[i].eventdate,res.events[i].eventtime,res.events[i].eventduration,'hideevents');
						  
						  if ( p == 0) {
							
							viewmore(res.events[i].eventdate);
							p = 1;
						}
						
						  
					  }
					  else {
						  addevent(res.events[i].ID,res.events[i].name,res.events[i].eventdate,res.events[i].eventtime,res.events[i].eventduration);
					  }
					  j++;
				  }
				  else {
					addevent(res.events[i].ID,res.events[i].name,res.events[i].eventdate,res.events[i].eventtime,res.events[i].eventduration);
					  tempdate = res.events[i].eventdate;
					  j = 1;
					
					 if ( incflag == 1) {
						 
					incflag = 0;
					  inc++;
					 }
					  p = 0;
				  }
			  }
			  
			
			  
		  }
		  
		  for ( i =1; i <= numofevents ; i++) {
			  positionevent(res.events[i].ID,res.events[i].eventtime,res.events[i].eventduration);
			} 
		 
// SETTING THE OVERLAPPING POSITIONS Algorithm -1 
/*
			var xa = 0;
			for(xa = 0 ; xa < ofdate.length ; xa++){
				console.log(ofdate[xa].date);
				var endid = ofdate[xa].id;
				
			var i=0,j=0,n=ofdate[xa].numofevesinthatdate;
			var id = endid - n + 1;
			while ( i <  n){
		
				for ( j = n-1 ; j > i ; j--) {
					
					console.log('endid  = ' + (id+j) + '  sid = ' + (id+i) );
				
				var tempd1,tempt1,tempt2,h1,h2,m1,m2;				
					
					
					tempd1 = parseInt(res.events[id + i].eventduration); 
				tempt1 = res.events[id + i].eventtime;
				tempt2 = res.events[(id + j)].eventtime;
			
				h1 = parseInt(tempt1.slice(0,2));
				m1 = parseInt(tempt1.slice(3,5));
				h2 = parseInt(tempt2.slice(0,2));
				m2 = parseInt(tempt2.slice(3,5));
			var diff = ((h2-h1)*60 + m2-m1) - tempd1;		
					
					
					
					if ( diff < 0) {
					console.log(tempt1 + '  ' + tempt2);
					var numofsets = j - i +1;
					console.log("Setting the things" + ' i=' + (i) + ' j = ' + j + 'num of position settings : ' + numofsets );
					var g = 0;
					width =  140/numofsets;
					for ( g = i ; g <= j ; g++) {
						positionevent(res.events[id+g].ID,res.events[id+g].eventtime,res.events[id+g].eventduration,width,((g-i)*width - 5*(g-i)),(10+g-i));
					}
					i = j;
				
					break;
					}
				}
				i++;
				
			}
			}
			
			// SETTING THE OVERLAPPING events II Algoritm -II
*/
var xb = 0;
for(xb = 0 ; xb < ofdate.length ; xb++){
	var endid = ofdate[xb].id;
				
			var i=1,j=1,n=ofdate[xb].numofevesinthatdate;
			var id = endid - n + 1;
			var tempid;
			
			var tempd1,tempt1,tempt2,tempd2,h1,h2,m1,m2,inc = 0,diff;				
					
					
				
				
			
			
			
			while ( i < n){
				
				tempd1 = parseInt(res.events[id+i-1].eventduration) + inc; 
				tempt1 = res.events[id+i-1].eventtime;
				h1 = parseInt(tempt1.slice(0,2));
				m1 = parseInt(tempt1.slice(3,5));
				
				tempd2 = parseInt(res.events[id+i].eventduration) ; 
				tempt2 = res.events[id+i].eventtime;
				h2 = parseInt(tempt2.slice(0,2));
				m2 = parseInt(tempt2.slice(3,5));
				
				var height = (h2-h1)*60 + (m2-m1);
				
				if (height < tempd1) {
					
					j = j + 1;
				
					
				}
				else {
					
					var k = 0;
				
				
					var width = 140/j;
				for ( k = i-j; k < i; k++) {
					
				positionevent(res.events[id+k].ID,res.events[id+k].eventtime,res.events[id+k].eventduration,(width),((k-i+j)*width),(10+k-i+j));
					}
					j = 1;
					
				}
				i++;
				
				
				
				
			}
			if ( j != 1 ) {
				var k = 0;
				
				var width = 140/j;
				for ( k = n-j; k < n; k++) {
				
			if ( k != 0)	{
				positionevent(res.events[id+k].ID,res.events[id+k].eventtime,res.events[id+k].eventduration,(width),((k -n + j)*width),(10+k));
			}
			else {
				positionevent(res.events[id+k].ID,res.events[id+k].eventtime,res.events[id+k].eventduration,(width),0,(10+k));
			}
		
					}
			}
			
}
			
			
			
			
			
	
			  
		
								
			}                  
        
  };
                xhttp.open("POST","php/othersevent.php", true);
				xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhttp.send("ouid=<?php if(isset($_POST['ouid'])) echo $_POST['ouid']?>");
                }
		function positionevent(_eventid , _eventtime ,_eventduration,x=0,y=0,z=0,_eventday = null) {
            var id = _eventid;
            var ht = _eventtime;
            var height = _eventduration;
            height = (height * 50)/30 ; 
            var minutes = ht.slice(3,5);
            var hours = ht.slice(0,2);
            var top = minutes*50/30 + hours*100;
		//	var width = parseInt$("#ad-eventid-" + id).width();
			
		//	tp = $("#ad-eventid-" + id).css("margin-left") || '00px';
		//	 var tp = parseInt((tp).slice(0,-2));
		//	console.log(tp);
		//	x = x + tp/2;
		//	tm = $("#ad-eventid-" + id).css("margin-right") || '00px';
			// var tm = parseInt((tm).slice(0,-2));
		//	 y =y + tm/2;
	 
	var width =  ($(".aw-set-width").width() - 8) || 190;
	 if ( x  > 0) {
		 width = x;
	 }
	 
	var	z = 2;
		if(z > 0) {
			z = z;
		}
		
			var mlft = y;
			
          //  var aw_week_head = parseInt(localStorage.getItem('aw_week_head'));
           // var ad_week_head = parseInt(localStorage.getItem('ad_week_head'));
            var aw_top  = top;
            
            var ad_top = top;
        
        
            $("#aw-eventid-"+id).css({
                
                    "top" : top+'px',
                    "height" : height+'px',
                    "width" : width+'px',
                    "margin-left" : mlft + 'px',
				    "z-index" : z,
                                     });
        
            $("#ad-eventid-"+id).css({
                
                    "top" : top +'px',
                    "height" : height+'px',
                    "width" :  ((width + 5)*7) + 'px',      
                    "margin-left" : (mlft*7) + 'px',
					"z-index" : z,
                                     
                                     });
            
            
        }
		
            
		
		function setstyle() {
            var d = new  Date();
            var height = d.getHours()*60 + d.getMinutes();
            height = height * 100/60;
        document.getElementById("aw-vertical-lines").scrollTop = height-400;
		document.getElementById("ad-vertical-lines").scrollTop = height-400;
        var aw_top = document.getElementById("aw-week-head").offsetHeight;
        var ad_top = document.getElementById("ad-week-head").offsetHeight;    
        $("#aw-tablewidth").css({'top' : top, 'height' : 2400 + aw_top});
        $("div.aw-events").css({'top' : aw_top});
        $("div.aw-time-grid").css({'top' : aw_top});
            $("div.ad-events").css({'top' : ad_top});
           $("div.ad-time-grid").css({'top' : ad_top});
           localStorage.setItem('aw_week_head', aw_top);
           localStorage.getItem('ad_week_head',ad_top);
           $("#ad-tablewidth").css({'top' : ad_top, 'height' : 2400 + ad_top});
        
            
        }
        function submit_form(path,params,method) {
                method = method || 'POST';
                var form = document.createElement("form");
                form.setAttribute("method",method);
                form.setAttribute("action",path);

            for (var key in params) {
                if(params.hasOwnProperty(key)) {
                    var hidden = document.createElement("input");
                    hidden.setAttribute("type","hidden");
                    hidden.setAttribute("name",key);
                    hidden.setAttribute("value",params[key]);
                    
                    
                    form.appendChild(hidden);
                
            }
            }
                    document.body.appendChild(form);
                    form.submit();
            }
        function next() {
     var current_view = null;
         var str = (window.location.href);
       
        if( str.search("month-view") > 0 ) {
            current_view = 'tab-month';
        }
        if( str.search("week-view") > 0 ) {
            current_view ='tab-week';
        }
        if( str.search("day-view") > 0 ) {
            current_view =  $(".agenda-view").find("li.current > a").attr("id") || 'tab-day';
        }
		current_view =  $(".agenda-view").find("li.current > a").attr("id") || current_view; 
        if(current_view == 'tab-day') {
            var date1 = '<?php echo $cal->_nextday(); ?>';
            var day = date1.slice(0,2);
            var month = date1.slice(2,4);
            var year = date1.slice(4);
            submit_form('viewothers.php#day-view',{'day' : day , 'month' : month , 'year' : year},'GET');   
    }
        else if(current_view == 'tab-week') {
               var date1 = '<?php echo $cal->_nextweek(); ?>';
               var day = date1.slice(0,2);
               var month = date1.slice(2,4);
               var year = date1.slice(4);
               submit_form('viewothers.php#week-view',{'day' : day , 'month' : month , 'year' : year},'GET');
        }
    
        else {
                var date1 = '<?php echo $cal->_nextmonth(); ?>';
                var day = date1.slice(0,2);
                var month = date1.slice(2,4);
                var year = date1.slice(4);
                submit_form('viewothers.php#month-view',{'day' : day , 'month' : month , 'year' : year},'GET');            }  
}
        function prev() {
         var current_view = null;
         var str = (window.location.href);
       
       if( str.search("month-view") > 0 ) {
            current_view = 'tab-month';
        }
        if( str.search("week-view") > 0 ) {
            current_view ='tab-week';
        }
        if( str.search("day-view") > 0 ) {
            current_view =  $(".agenda-view").find("li.current > a").attr("id") || 'tab-day';
        }
		current_view =  $(".agenda-view").find("li.current > a").attr("id") || current_view;        
        if(current_view == 'tab-week') {
            var date1 = '<?php echo $cal->_prevweek(); ?>';
            var day = date1.slice(0,2);
            var month = date1.slice(2,4);
            var year = date1.slice(4);
            
            submit_form('viewothers.php#week-view',{'day' : day , 'month' : month , 'year' : year},'GET');   
    }
        else if(current_view == 'tab-day') {
               var date1 = '<?php echo $cal->_prevday(); ?>';
               var day = date1.slice(0,2);
               var month = date1.slice(2,4);
               var year = date1.slice(4);
               submit_form('viewothers.php#day-view',{'day' : day , 'month' : month , 'year' : year},'GET');
        }
    
        else {
                var date1 = '<?php echo $cal->_prevmonth(); ?>';
                var day = date1.slice(0,2);
                var month = date1.slice(2,4);
                var year = date1.slice(4);
                submit_form('viewothers.php#month-view',{'day' : day , 'month' : month , 'year' : year},'GET');            }
    }
        function today() {
            
            var current_view = null;
         var str = (window.location.href);
       
        if( str.search("month-view") > 0 ) {
            current_view = $(".agenda-view").find("li.current > a").attr("id") || 'tab-month';
        }
        if( str.search("week-view") > 0 ) {
            current_view = $(".agenda-view").find("li.current > a").attr("id")  || 'tab-week';
        }
        if( str.search("day-view") > 0 ) {
            current_view =  $(".agenda-view").find("li.current > a").attr("id") || 'tab-day';
        }
         
            if(current_view == 'tab-week') {
            var d=new Date;
            submit_form("viewothers.php#week-view",{'day' :d.getDate() ,'month' : d.getMonth() + 1 ,'year' : d.getFullYear()},'GET');
            }
            else if(current_view == 'tab-day') {
            var d=new Date;
            submit_form("viewothers.php#day-view",{'day' :d.getDate() ,'month' : d.getMonth() + 1 ,'year' : d.getFullYear()},'GET');
            }
            else {
                 var d=new Date;
            submit_form("viewothers.php#month-view",{'day' :d.getDate() ,'month' : d.getMonth() + 1 ,'year' : d.getFullYear()},'GET');
            }
        }
        
        
        var co =1;
        
          function resize_window() {
            
                 var widthofmini =  ((window.innerWidth) * 15)/100;
                //  widthofmini = 300;
//            alert(widthofmini);
            var defaultwidth = 1920;
            var widthofagenda = ((window.innerWidth - widthofmini - 100 - ((defaultwidth - window.innerWidth)/10) ));
            try {  
                document.getElementById("agenda-view").style.width = widthofagenda;
                 document.getElementById("mini_cal").style.width = widthofmini;
                document.getElementById("mini_cal").style.height = widthofmini;
                document.getElementById("side_panel").style.width = widthofmini;
               
            }
              catch(err){
                  console.log(err.message);
              }
               //    document.getElementById("side_panel").style.height = window.innerHeight;
                var mw_events = (widthofagenda)/7;
                $("table.mw-event-table").width(mw_events);
                $("div.mw-events").width(mw_events - 5);
                $("tr.mw-event-container").width(mw_events);
                
            
            
                try {
                    $("table.nav-month").width(widthofmini);
                    $("table.nav-month").height(300);
                     console.log('set width = ' + widthofmini + ' setted width = ' +  $("#side_panel").width());  
                   
                }
                catch(err) {
                    console.log(err.message);
               
                }
                
            // setting the week width settings
                var aw_events = (widthofagenda  - 75)/7;
                
                $(".aw-axis").width(75);
                $(".ad-axis").width(75);
                $(".aw-set-width").width(aw_events);
                $(".ad-set-width").width(widthofagenda - 75);
                 //   console.log(loaddata);
                try{
                    resize_events(loaddata,aw_events-8);
                }
                
                
            
            
                catch(err){
                    console.log(err.message);
                }
            
            console.log( "Sno : "+ co +  ' Width : ' + window.innerWidth + ' Width of  view : ' +( widthofagenda + widthofmini));
                co++;
         		
        }
        
          function resize_events(load,_width) {
            
            
              $(".eventsdis").remove();
              $(".eventsdis").remove();
		
            var res = JSON.parse(load);
              var ofdate = [{ 'date' :"0000-00-00",'numofevesinthatdate' : 0}];       
            var flag = 1; var i = 0;
            var numofevents = res.events[0].numofevents;
            			
            var tempdate,inc = 0,incflag = 0;
            var j=0;
			
            var p = 0;
              for ( i =1; i <= numofevents ; i++) {
                                
                           
                    
                            
                           // This is loop is for month-view to hide the events
        
                                
								if ( j == 0) {   // if it is the first event in that day then add the event with null class
						addevent(res.events[i].ID,res.events[i].name,res.events[i].eventdate,res.events[i].eventtime,res.events[i].eventduration,null,res.events[i].eventstatus);			
						tempdate = res.events[i].eventdate;
						j++;
				                            }
                            
                            // now j is 1
			  
			
			   else if ( j  != 0 ) {	// The second event will always go through this loop 
				  if ( tempdate == res.events[i].eventdate) {     // if second event is on the same date.
					  if ( j > 0) {
						  incflag = 1;                     
						  //alert(i);
                          ofdate[inc] = { 'id' : i,'date' : tempdate , 'numofevesinthatdate' : (j+1)}; 
                    //      console.log(ofdate[inc]);
                    //      console.log(' INC : ' + inc);
                          //ofdate  store the last event in a given date with no.of events in that date
					  }
					
					    if ( j > 2) { // max limit is three j starts from 0. other than three make _class = hideevents and add view more option
							
						  addevent(res.events[i].ID,res.events[i].name,res.events[i].eventdate,res.events[i].eventtime,res.events[i].eventduration,'hideevents',res.events[i].eventstatus);
						  
						  if ( p == 0) {
							
							viewmore(res.events[i].eventdate);
							p = 1;
						}
						
						  
					  }
					  else { // for j < 2 just add the events.
						  addevent(res.events[i].ID,res.events[i].name,res.events[i].eventdate,res.events[i].eventtime,res.events[i].eventduration,null,res.events[i].eventstatus);
					  }
                                // increment the j as it is on the same date
					  j++;
				  }
				  else {  // if the next event is on another date . Then add the event.
					addevent(res.events[i].ID,res.events[i].name,res.events[i].eventdate,res.events[i].eventtime,res.events[i].eventduration,null,res.events[i].eventstatus);
					  tempdate = res.events[i].eventdate;
					  j = 1;  // j is 1 since j = 0 is already printed.
					
					 if ( incflag == 1) {
						 
					incflag = 0;
					  inc++;
					 }
					  p = 0;
				  }
			  }
			  
			
			  
		  }
              for ( i =1; i <= numofevents ; i++) {
			  positionevent(res.events[i].ID,res.events[i].eventtime,res.events[i].eventduration);
			} 
		      var xb = 0;
              for(xb = 0 ; xb < ofdate.length ; xb++){ // for every xb in ofdate
	       var endid = ofdate[xb].id;             // get the last id
	//		console.log(ofdate[xb]);	
			var i=1,j=1,n=ofdate[xb].numofevesinthatdate; 
			var id = endid - n + 1;              // get the first id
			var tempid;                           // temp to store the last id.
			
			var tempd1,tempt1,tempt2,tempd2,h1,h2,m1,m2,inc = 0,diff;				
					
					
				
				
			
			
			
			while ( i < n){                      // i = 1 to last event
                                                    
				
				tempd1 = parseInt(res.events[id+i-1].eventduration) + inc; 
				tempt1 = res.events[id+i-1].eventtime;
				h1 = parseInt(tempt1.slice(0,2));
				m1 = parseInt(tempt1.slice(3,5));
				
				tempd2 = parseInt(res.events[id+i].eventduration) ; 
				tempt2 = res.events[id+i].eventtime;
				h2 = parseInt(tempt2.slice(0,2));
				m2 = parseInt(tempt2.slice(3,5));
				
				var height = (h2-h1)*60 + (m2-m1);        // change time to height if event2 minus event1
				
				if (height < tempd1) {    // if height is less than duration of event1 then Then it is overlapping note the j
					
					j = j + 1;
				
					
				}
				else {      // If it is not overlapping 
					
					var k = 0;
				
				
					var width = _width/j;
				for ( k = i-j; k < i; k++) {
					
				positionevent(res.events[id+k].ID,res.events[id+k].eventtime,res.events[id+k].eventduration,(width),((k-i+j)*width),(10+k-i+j));
					}
					j = 1;
					
				}
				i++;
				
				
				
				
			}
			if ( j != 1 ) {
				var k = 0;
				
				var width = _width/j;
				for ( k = n-j; k < n; k++) {
                    
                   
			if ( k != 0)	{
				positionevent(res.events[id+k].ID,res.events[id+k].eventtime,res.events[id+k].eventduration,(width),((k -n + j)*width),(10+k));
			}
			else {
				positionevent(res.events[id+k].ID,res.events[id+k].eventtime,res.events[id+k].eventduration,(width),0,(10+k));  
			}
		
					}
			}
			
}

        }
      
        
        
        
        
                    
    </script>
    <script>
$(document).ready(function() {
       
             //   setstyle();            
                loadevents();
                
                window.addEventListener("resize",resize_window);
                  
                resize_window();
                
    
               var sdate = '<?php echo _curmonth(); ?>';
				
				var smonth = sdate.slice(0,2);;
				var syear = sdate.slice(2);
					var xhttp = new XMLHttpRequest();
					xhttp.onreadystatechange = function() {
								if (xhttp.readyState == 4 && xhttp.status == 200) {
												document.getElementById("mini_cal").innerHTML = xhttp.responseText;
									}
					};
					xhttp.open("GET","php/mini_calendar.php?month="+smonth+"&year="+syear,true);
					xhttp.send();
				document.getElementById("ouid").value = "<?php 
				if(isset($_POST['ouid']))
					echo $_POST['ouid'];
				else if(isset($_SESSION['ouid']))
					echo $_SESSION['ouid'];
				else
					echo "none";
					
				?>";
   
               $("#agenda-view").tabs({
                    heightStyle : "auto",
                });
               $( "#addevent-date-picker" ).datepicker({
                        appendText:"(yy-mm-dd)",
                        dateFormat:"yy-mm-dd",
                });
               $( "#addevent-form" ).dialog({
                    autoOpen: false, 
                    show : "highlight",
                    modal : true,
                    title : "Add an event"
                  });
				$("#error_form").dialog({
					autoOpen : false,
					modal : true,
					title : "Error",
					width : 750,
				});
               
               $("button.bform").click(function() {
                 
                    var name = document.getElementById("addevent-name").value;
                    var day = document.getElementById("addevent-date-picker").value;
                    var time = document.getElementById("addevent-time").value;
                   
                    var duration = document.getElementById("addevent-duration").value;
                    var date = new Date;
                    var recordtime = date.getTime();
                    
                 $("#addevent-form").dialog("close");
                $.post("php/requestevents.php",{
                    name : name,
                    date : day,
                    time : time,
                    recordtime : recordtime,
                    eventduration : duration,
					eventstatus : 2,
					
                },
                function(data , status){
                    if ( status == 'success') {
                       var errdata = data;
						 errdata = errdata.slice(0,3);
                        console.log(errdata);
                        if ( errdata == "700") {
							$("#error_form").dialog("open");
						}
                        events_reload();
                    }
                    
                });
                
                });
           
				$(".agenda-view > li > a.view").click(function(event) {
							event.preventDefault();
            
        
            
        $(this).parent().siblings().removeClass("current");
        $(this).parent().addClass("current");
            var current_view = ($(".agenda-view").find("li.current > a").attr("id"));
            
    });
          
			$("button.closeform").on("click",function() {
				$("#error_form").dialog("close");
			});
                
            
          if( document.getElementById("ouid").value == 'none') {
			  
			  $("#eventsviewer").css({"display" : "none"});
			  
		  }
		  else {
			  $("#eventhelper").css({"display" : "none"});
		  }
        });
		
		
		
		
    </script>
    
    <style>
        
       
        td {
            vertical-align : text-top;
        }
        ul#menu {
            padding: 0;
        }
     
        ul#menu li {
            display: inline;
            padding-top : 8px;
            position : static;

        }

        ul#menu li a {
            background-color: #1d80de;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px 4px 0 0;
        }


        ul#menu li a:hover {
            background-color: #0f4171;
            animation-name: padit;
            animation-duration: 0.3s; 
            border-radius : 5px;
            padding : 20px 25px;
            font-size: 125%;
        }
        @keyframes padit {
            from {background-color:#1d80de;padding : 10px 20px;}
            to {background-color: #0f4171; padding : 20px 25px;}
        }
        ul#menu li.a {
            
            position: relative;
            top : -2.55px;
            left : 200px;
            
            
        }
        ul#menu li.a a.heading {
            border-radius : 5px;
            font-size: 120%;
        }
        ul#menu li.a a.heading:hover {
            
        }
   
        .holiday {
            background-color: #f2f2f2;
        }
        .active {
            background-color: #e6eeff;
        }
		div.header {
			position : relative;
			width : 100%;
			display : block;
			
		}
		div.agenda-ov {
			float : left;
		}
		#side_panel{
			float : left;
			overflow : visible;
		}
		#agenda-view {
			float:left;
			
		}
		#minidata {
			display : none;
		}
		div#selectusers{
			width : 100%;
			margin-top : 30px;
			margin-left : 350px;
			align-content : center;
		}
		select#ouid{
			align-content : center;
			padding : 5px 50px 5px 50px;
			width : 700px;
			height : 40px;
			font-size : 120%;
			border-radius : 5px;
			
		}
		select#ouid:focus {
			box-shadow : 0px 0px 5px blue;
		}
		input.option {
			align-content : center;
			height : 40px;
			width : 75px;
			font-size : 20px;
			vertical-align : middle;
			margin-top : -5px;
			border-radius : 5px;
			border : 1px solid grey;
			
			
		}
		input.option:focus {
			box-shadow : 0px 0px 5px blue;
		}
		div.mw-events-vo {
			//    width : 160px; 
            white-space: nowrap; 
            overflow : hidden; 
            text-overflow : clip; 
            vertical-align : text-top; 
            background-color : #ff6666; 
            
			border-radius : 5px;
            text-align : left;
        
            border : 1px solid blue;
         
		}
		div.ad-events-vo {
			    position: absolute;
            border : 1px solid #006600;
			background-color: #99ff99;

			white-space: nowrap; 
            overflow : hidden; 
            text-overflow : clip;
            height : 70px;
            z-index : 2;
             width : 140px;
            margin-left: 5px;
			
           
           
            border-bottom-left-radius: 5px;
            border-bottom-right-radius: 5px;
            border-top-right-radius: 5px;
            border-top-left-radius: 5px;
        
		}
		div.aw-events-vo {
			     position: absolute;
           border : 1px solid #003cb3;
            background-color: #4d88ff;           
			white-space: nowrap; 
            overflow : hidden; 
            text-overflow : clip;
            height : 70px;
            z-index : 2;
             width : 150px;
            margin-left: 5px;
           
           
            border-bottom-left-radius: 5px;
            border-bottom-right-radius: 5px;
            border-top-right-radius: 5px;
            border-top-left-radius: 5px;
       
		}
		a.headerlink {
		margin : 2px;
			margin-top : -50px;
			padding : 5px;
			float : right;
			height : 40px;
			width : 120px;
			font-size : 20px;
			font-family : Arial, Helvetica, sans-serif;
			background-color : black;
			color : white;
			text-decoration : none;
			align-content : right;
			border-radius : 10px 10px 0px 0px;
			align-content : left;
			text-align : center;
			vertical-align : middle;
				
			
		}
		a.headerlink:hover {
			background-color : orange;
		}
    </style>
    
    </head>
    <body>
  
      <div style = "margin-top : 30px; border : 1px solid black; border-style : none none solid none">
         <h2 style = "font-family : 'Palatino Linotype', 'Book Antiqua', Palatino, serif; padding : 5px 2px 2px 3px; margin-left : 10px;font-size : 30px;">Welcome <?php echo $_SESSION['user']?></h2>
       
	   <a class= "headerlink" href="logout.php"><img src="pics/logout.png" height="30px" width="30px"><span>Logout</span></a>
	   
	   
	   
	   <a class="headerlink" href="settings.php"><img src="pics/settings.png" height="30px" width="30px"><span>Settings</span></a>
	   
	   <a class="headerlink"href="home.php"><img src="pics/home.png" height="30px" width="30px"><span>Home</span></a>
	   
	   
	   </div>
		
		<div id="selectusers" >
		<form action="viewothers.php" method="POST">
		<select name="ouid" id="ouid">
		<option value="none">Select a Doctor</option>
		<?php
		$con = mysqli_connect("127.0.0.1","root","","project");
	if (! $con) {
    die('Error : Could not connect database.');
}

		$qu = "SELECT `username` FROM `userlogin`";
		$retu = mysqli_query($con,$qu);
		$dat = mysqli_fetch_array($retu);
		$n = mysqli_num_rows($retu);
		while ($dat) {
			if($dat['username'] != $_SESSION['user'])
		echo '<option value="'.$dat["username"].'">'.$dat['username'].'</option>';
		$dat = mysqli_fetch_array($retu);
		}
		mysqli_close($con);
		?>
		</select>
		<input class="option"type = "submit" value="submit">
		</form>
		</div>
		<div id="eventsviewer">
            <div width="100%">
            <div id="side_panel">
			<div id="mini_cal">
			
			</div>
			</div>
			</div>
            <div id="agenda-view" class="agenda-ov">
			<div class="header">
         <ul class="agenda-view">
            <li ><a  class="view" id="tab-month" href = "#month-view">Month</a></li>
             <li><a  class="view" href = "#week-view" id="tab-week">Week</a></li>
            <li><a  class="view"  href = "#day-view" id = "tab-day">Day</a></li>
             
             <ul id="menu" style="float : left">
                 <li class="a"><a class="heading" href="javascript:void(0)"><?php echo $cal->_heading() ?></a></li>
                 <li align="center"><a href="javascript:event_form()">Request Event</a></li>   
    <li><a href="javascript:next()">&#10095;</a></li>
    <li><a href="javascript:today()">Today</a></li>
    <li><a href="javascript:prev()">&#10094;</a></li>
    
    </div>
    
   
        
        </ul>
         </ul>
        
        
        <div id="month-view">
        <?php echo $cal->month_view(); ?>
        </div>
        <div id="week-view">
        <?php echo $cal->week_view(); ?>
        </div>
        <div id="day-view">
        <?php echo $cal->day_view(); ?>
        </div>
                
            </div>
        
    </div>
		<div id="eventhelper"style = "margin-left : 400px; margin-top: 50px; font-size : 120%; font-family : Tahoma, Geneva, sans-serif">
		<h3>Select a Doctor in the above list to view the appoinments.</h3>
		</div>
         <div id="addevent-form" border = "1">
            
        
             <table class="form" border="0" width="auto" height="auto" style = "table-layout : fixed ">
                <caption><span>Please enter the details</span></caption>
                <tr>
            <td>Name&nbsp;:&nbsp;</td>
            <td colspan="3"><input type = "text" id="addevent-name" placeholder="Name" name="name"></td>
                </tr>
                <tr>          
                    <td>Date&nbsp;:&nbsp;</td>
                    <td colspan="3"><input type = "text" id="addevent-date-picker" placeholder="day" name="date"></td>
                </tr>
				<tr>
					<td>Time&nbsp;:&nbsp;</td>
                    <td><input type = "time" id="addevent-time" name="time" placeholder="time"></td>
				</tr>
				<tr>
                 <td>
                 Duration&nbsp;:
                 </td>
                 <td>
                 <select name="eventduration" id="addevent-duration">
						<option value="15">15 mins</option>
						<option value="30">30 mins</option>
						<option value="60">1 hour</option>
						<option value="120">2 hours</option>
  </select>
                 </td>
                 </tr>        
                </table>
                
                <button class="bform">Submit</button>
            
            
        </div>
        
        
        <div id="error_form">
	<div>Sorry!.This appointment is conflicting with other appointments.<br>This appoinment is cancelled.Please make appointment at another time.</div>
	<button align="right" class="closeform">Ok</button>
	</div>
         
        
        
    
    </body>

</html>
        
        
   
