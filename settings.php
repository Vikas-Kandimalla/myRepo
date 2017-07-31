<?php 
session_start();
include_once "create_db.php";
$pwd = 'http://'.$_SERVER['SERVER_NAME'].'/p';
if ( !isset($_SESSION['uid']) || !isset($_SESSION['user']) || !isset($_SESSION['email']) || !isset($_SESSION['dbstatus'])) {
	echo "Session not set";
	header("Location: ".$pwd."index.php");
}
if ( $_SESSION['dbstatus'] == 0) {
	createdatabase($_SESSION['uid']);
}
$database = $_SESSION['uid'];
try {
$con1 = mysqli_connect("127.0.0.1","root","",$database) or die("710 Error : Cannot connect to database");
}
catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}


//get data of username
//And the data of working hours




//Working hours


?>
<!DOCTYPE html>
<html>
<head>
<style>
.button {
  display: inline-block;
  border-radius: 4px;
  background-color: #f4511e;
  border: none;
  color: #FFFFFF;
  text-align: center;
  font-size: 18px;
  padding: 7px;
  width: 80px;
  transition: all 0.5s;
  cursor: pointer;
  margin: 5px;
  display: inline-block;
}

.button span {
  cursor: pointer;
  display: inline-block;
  position: relative;
  transition: 0.5s;
}

.button span:after {
  content: '>>';
  position: absolute;
  opacity: 0;
  top: 0;
  right: -20px;
  transition: 0.5s;
}

.button:hover span {
  padding-right: 25px;
}

.button:hover span:after {
  opacity: 1;
  right: 0;
}
a.headerlink1 {
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
		a.headerlink1 > span {
			align-content : center;
			vertical-align : middle;
			padding-top : 15px;
		}
		a.headerlink1:hover {
			background-color : orange;
		}
#settings {
	width:100%;
}
</style>
<script src='jquery/jquery.js'></script>
<script src='jquery/jquery-ui.js'></script>
 <link href="jquery/jquery-ui.theme.css" rel="stylesheet">
    <link href="jquery/jquery-ui.css" rel="stylesheet">
   
<title>
Settings
</title>
<script>
	
	var days = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
var udetails;

	function userdetails(username,email){
	this.username = username;
	this.email = email;
}
	function useredit(){
        var bru = document.getElementsByClassName("user-dis");
        var i=0;
        while(bru[i])
            {
            bru[i].disabled= false;    
            i++;    
            }
			
		udetails = new userdetails(document.getElementById("user-name").value,document.getElementById("user-email").value);
       // console.log(udetails);
		document.getElementById("user-edit").style.display = 'none';
        document.getElementById("user-save").style.display = 'inline';
        document.getElementById("user-cancel").style.display = 'inline';    
    }
    function usercancel(){
     var bru = document.getElementsByClassName("user-dis");
        var i=0;
        while(bru[i])
            {
            bru[i].disabled= true;    
            i++;    
            }
			document.getElementById("user-name").value = udetails.username;
			document.getElementById("user-email").value = udetails.email;
        document.getElementById("user-edit").style.display = 'inline';
        document.getElementById("user-save").style.display = 'none';
        document.getElementById("user-cancel").style.display = 'none';       
    }
	function usersave() {
		
		//Check the user details
		//after chefcking proceed for updating
		
		var postdata = new userdetails(document.getElementById("user-name").value,document.getElementById("user-email").value);
		//console.log(postdata);
		var xhttp = new XMLHttpRequest();
		 xhttp.onreadystatechange = function() {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                var data = xhttp.responseText;
				//console.log(data);
				if ( data.slice(0,3) == '800' ) {
				var bru = document.getElementsByClassName("user-dis");
				var i=0;
				while(bru[i])
					{
					bru[i].disabled= true;    
					i++;    
					}
				
				 document.getElementById("user-name").value = postdata.username;
				 document.getElementById("user-email").value = postdata.email;
				 document.getElementById("user-edit").style.display = 'inline';
				 document.getElementById("user-save").style.display = 'none';
				 document.getElementById("user-cancel").style.display = 'none';  
				 location.reload();
            }
			else {
				alert("Error settings not saved.please try again after reloading the page");
			}
        }
		 };
		xhttp.open("GET","php/updateUser.php?username="+document.getElementById("user-name").value+"&email="+document.getElementById("user-email").value,true);
		xhttp.send();
		
		
	}
	function  editdata() {
     var obj = document.getElementsByClassName("prov_dis");
         var i =0 ;
         while(obj[i]){
         obj[i].disabled = false;
         i++;
         }
    document.getElementById("prov_save").style.display = 'inline';
          document.getElementById("prov_cancel").style.display = 'inline';
         document.getElementById("prov_edit").style.display = 'none';
     }
    function canceldata() {
           var obj = document.getElementsByClassName("prov_dis");
         var i =0 ;
         while(obj[i]){
         obj[i].disabled = true;
         i++;
         } 
         document.getElementById("prov_save").style.display = 'none';
          document.getElementById("prov_cancel").style.display = 'none';
         document.getElementById("prov_edit").style.display = 'inline';   
        }
	function editofedit1(){
        var bru = document.getElementsByClassName("prac-dis");
        var i=0;
        while(bru[i])
            {
            bru[i].disabled= false;    
            i++;    
            }
        document.getElementById("prac-edit").style.display = 'none';
        document.getElementById("prac-save").style.display = 'inline';
        document.getElementById("prac-cancel").style.display = 'inline';    
    }
    function editcancel1(){
     var bru = document.getElementsByClassName("prac-dis");
        var i=0;
        while(bru[i])
            {
            bru[i].disabled= true;    
            i++;    
            }
        document.getElementById("prac-edit").style.display = 'inline';
        document.getElementById("prac-save").style.display = 'none';
        document.getElementById("prac-cancel").style.display = 'none';       
    }
	function editofedit2(){
        var bru = document.getElementsByClassName("pat-dis");
        var i=0;
        while(bru[i])
            {
            bru[i].disabled= false;    
            i++;    
            }
        document.getElementById("pat-edit").style.display = 'none';
        document.getElementById("pat-save").style.display = 'inline';
        document.getElementById("pat-cancel").style.display = 'inline';    
    }
    function editcancel2(){
     var bru = document.getElementsByClassName("pat-dis");
        var i=0;
        while(bru[i])
            {
            bru[i].disabled= true;    
            i++;    
            }
        document.getElementById("pat-edit").style.display = 'inline';
        document.getElementById("pat-save").style.display = 'none';
        document.getElementById("pat-cancel").style.display = 'none';       
    }
	function hoursDisplayEdit(){
        var bru = document.getElementsByClassName("hours-dis");
        var i=0;
        while(bru[i])
            {
		
            bru[i].style.display = 'none';    
            i++;    
            }
		var edittools = document.getElementsByClassName("edit-display");
		i=0;
		while(edittools[i]){
			edittools[i].style.display = "block";
			i++;
		}
	
        document.getElementById("hour-edit").style.display = 'none';
        document.getElementById("hour-save").style.display = 'inline';
        document.getElementById("hour-cancel").style.display = 'inline';    
		
		var holidays = document.getElementsByClassName("edit-holidays");
		i=0;
		while(holidays[i]){
			holidays[i].style.display = 'none';
			i++;
		}
		
		
		
    }
    function hoursDisplayCancel(){
     var bru = document.getElementsByClassName("hours-dis");
        var i=0;
        while(bru[i])
            {
            bru[i].style.display= 'block';    
            i++;    
            }
			var edittools = document.getElementsByClassName("edit-display");
				i=0;
				while(edittools[i]){
					edittools[i].style.display = "none";
					i++;
		}
        document.getElementById("hour-edit").style.display = 'inline';
        document.getElementById("hour-save").style.display = 'none';
        document.getElementById("hour-cancel").style.display = 'none'; 
		
		$(".addbreak_div").remove();
	
	/*	for (i = 0; i < 7; i++){
			
			var j =0;
			var rnode = document.getElementsByClassName("addbreak_div");
			while(rnode[i]){
				
				document.getElementById(days[i] + '_breakhours').removeChild(rnode[i]);
				i++;
		
			}
		}
*/

		
    }
	function hoursSave(){
		
        
        

        
        
		var stimedata='';
		var offdaydata = '';
		var etimedata = '';
		var i;
		for (  i= 0;i<7;i++){
			stimedata += '-' + document.getElementById(days[i]+'_stime').value;
			etimedata += '-' + document.getElementById(days[i]+'_etime').value;
			offdaydata += '-' + document.getElementById(i+'_offday').value;
		}
		var brk = document.getElementsByClassName("breakhours_id");
		var numofbreaks = 0;
		var jsoncontent = '{"breakhours" : [';
		while (brk[numofbreaks]){
			// using JSON for no.of breaks
			
			
			id = brk[numofbreaks].id.slice(5);
			
			 jsoncontent += '{"ID" : ' + '"' + brk[numofbreaks].id.slice(5) + '",' + '"starttime" : ' + '"' + document.getElementById(id+'_stime').value + ':00",' + '"endtime" : ' + '"' + document.getElementById(id+'_etime').value + ':00",' + '"name" : ' + '"' + document.getElementById("name_"+id ).value + '",' + '"day" : ' + '"' + document.getElementById(id+'_day').value + '"},';
			numofbreaks++;
		}
		
		jsoncontent = jsoncontent.slice(0,-1);
		jsoncontent += ']}';
		//jsoncontent += ',{"numofenties" : '+numofbreaks+'}';
		console.log ( jsoncontent);
		
		var  bhours = "bdata=" +  JSON.stringify(jsoncontent);
		console.log(bhours);
		

		
		
		var data = "starttime="+stimedata+"&endtime="+etimedata+"&offday="+offdaydata;
		//console.log(data);
	// sending data for working hours	
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function () {
			if ( xhttp.readyState == 4 && xhttp.status == 200){
				var retdata = xhttp.responseText;
				//console.log(retdata);
				if ( retdata.slice(0,3) == '800' ){
					var i =0;
					var stemp,etemp,otemp;
					for ( i = 0; i < 7;i++){
					stemp = document.getElementById(days[i]+'_stime').value;
					etemp = document.getElementById(days[i]+'_etime').value;
					otemp = document.getElementById(i+'_offday').value;
					if ( otemp == 1){
						
						document.getElementById(days[i]+'_hours').innerHTML = 'Holiday';
					}
					else{
						var time = (parseInt((stemp.slice(0,2))%12)?parseInt((stemp.slice(0,2))%12):'12')+':'+stemp.slice(3,5);
						time += ' '+(parseInt(stemp.slice(0,2)/12)?'PM':'AM');
						time += ' - ' + ((parseInt((etemp.slice(0,2))%12))?(parseInt((etemp.slice(0,2))%12)):'12')+':'+ etemp.slice(3,5);
						time += ' '+(parseInt(etemp.slice(0,2)/12)?'PM':'AM');
						//console.log(time);
						document.getElementById(days[i]+'_hours').innerHTML = time;
					} 
				
					
					}
				}
				else {
					alert('Error Data is not saved.Please try again after sometime');
				}
			}
		}
		xhttp.open("POST","php/updateHours.php",true);
		xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xhttp.send(data);
	
	//sending data for breakhours only editing ........
		var xhtml = new XMLHttpRequest();
		xhtml.onreadystatechange = function() {
			if ( xhtml.status == 200 && xhtml.readyState == 4) {
			var retdata = xhtml.responseText;
				console.log(retdata);
				if ( retdata.slice(0,3) == '800' ){
                    var domele = document.getElementsByClassName("breakhours_span");
                    var i = 0;
                    var jsondata = JSON.parse(jsoncontent);
                    while(domele[i]){
                        console.log(domele[i].id.slice(10));
                        
                        
                        var stime = jsondata.breakhours[i].starttime;
                        stime = ((parseInt(stime.slice(0,2)%12))?(parseInt(stime.slice(0,2)%12)):'12') + ':' + stime.slice(3,5) +  ((parseInt(stime.slice(0,2)%12))?' PM' : ' AM');
                        
                        var etime = jsondata.breakhours[i].endtime;
                        etime = ((parseInt(etime.slice(0,2)%12))?(parseInt(etime.slice(0,2)%12)):'12') + ':' + etime.slice(3,5) +  ((parseInt(etime.slice(0,2)%12))?' PM' : ' AM');
                        
                        
                        domele[i].getElementsByClassName("breakhours_stime")[0].innerHTML = stime; 
                        domele[i].getElementsByClassName("breakhours_etime")[0].innerHTML = etime; 
                        domele[i].getElementsByClassName("breakhours_name")[0].innerHTML = '&nbsp;(' + jsondata.breakhours[i].name + ')'; 
                        
                        
                        i++;
                    }
                    hoursDisplayCancel();
                }	
				else {
					alert(retdata);
				}
			}
		}
		xhtml.open("POST","php/updateBHours.php",true);
		xhtml.setRequestHeader("Content-type", "application/x-www-form-urlencoded;charset=UTF-8;");
		xhtml.send(('bdata='+ jsoncontent + '&numofentries=' + numofbreaks));
	
	// sending data for addbreakhours 
	   
        var addbdiv = document.getElementsByClassName("addbreak_data");
        var inc = 0;
        if ( (addbdiv[0]) ) {
            
         
            var addbdata = '{ "breakhours" : [ ';
            
            
            
            
        while(addbdiv[inc]){
       //     console.log(addbdiv[inc].getElementsByClassName('addbreak_stime')[0].value);
        //    console.log('name : ' + addbdiv[inc].getElementsByClassName('addbreak_name')[0].value + ' stime : ' + addbdiv[inc].getElementsByClassName('addbreak_stime')[0].value + ' etime : ' + addbdiv[inc].getElementsByClassName('addbreak_etime')[0].value + ' day : ' + addbdiv[inc].getElementsByClassName('addbreak_day')[0].value );
            
            
            
            
           addbdata += '{"day" : "'+addbdiv[inc].getElementsByClassName('addbreak_day')[0].value+'","starttime" : "'+addbdiv[inc].getElementsByClassName('addbreak_stime')[0].value+':00", "endtime" : "'+addbdiv[inc].getElementsByClassName('addbreak_etime')[0].value+':00","name" : "'+addbdiv[inc].getElementsByClassName('addbreak_name')[0].value+'"},';
            
            
            
            
            inc++;
        }
        
            addbdata = addbdata.slice(0,-1);
            addbdata += ']}';
   //     console.log(addbdata);
        
        
        
	   var  ajaxhtml = new XMLHttpRequest();
        ajaxhtml.onreadystatechange = function() {
            if ( ajaxhtml.readyState == 4 && ajaxhtml.status == 200){
                var retdata = ajaxhtml.responseText;
                console.log( retdata);
                if ( retdata.slice(0,3) == '800'){
                        location.reload();
                }
                else {
                    alert("There is an error while processing the request.Please try again.");
                }
            }
            
        }
        ajaxhtml.open("POST","php/updateBHours.php",true);
        ajaxhtml.setRequestHeader("Content-type","application/x-www-form-urlencoded;charset=UTF-8");
        ajaxhtml.send('addbreaks=' + addbdata + '&num=' + inc);
	
		
    }
	
	}
	 function editofedit4(){
        var bru = document.getElementsByClassName("cal-dis");
        var i=0;
        while(bru[i])
            {
            bru[i].disabled= false;    
            i++;    
            }
        document.getElementById("cal-edit").style.display = 'none';
        document.getElementById("cal-save").style.display = 'inline';
        document.getElementById("cal-cancel").style.display = 'inline';    
    }
    function editcancel4(){
     var bru = document.getElementsByClassName("cal-dis");
        var i=0;
        while(bru[i])
            {
            bru[i].disabled= true;    
            i++;    
            }
        document.getElementById("cal-edit").style.display = 'inline';
        document.getElementById("cal-save").style.display = 'none';
        document.getElementById("cal-cancel").style.display = 'none';       
    }
    
	
	function changeDisplay(inp){
		
		var id = days[inp.id[0]];
		
		if ( inp.value == 0 ){
			document.getElementById(id+'_stime').style.display = 'inline';
			document.getElementById(id+'_etime').style.display = 'inline';
		}
		else{
			
			document.getElementById(id+'_stime').style.display = 'none';
			document.getElementById(id+'_etime').style.display = 'none';
		}
	}
	function addbreak(day){
		hoursDisplayEdit();
		//document.getElementById(days[day] + '_breakhours').innerHTML;
		//console.log(document.getElementById(days[day] + '_breakhours').innerHTML);
		var data = '<br><div><div class="notyetthought addbreak_div"  style="text-align : center;"><span  class="hours-dis" style = " display :none"><span style="color : red; font-size : 23px;">stime</span>&nbsp;-&nbsp;<span style="color : red; font-size : 23px;">etime</span><span style="position : static">&nbsp;(name)&nbsp;</span></span></div><div class="edit-display addbreak_div addbreak_data" style="text-align : center"><input class="edit-workhours addbreak_name" type="text" value="" size="12" placeholder="name">&nbsp;&nbsp;-&nbsp;&nbsp;<input class="edit-workhours addbreak_stime" type="time" value="">&nbsp;-&nbsp;<input class="edit-workhours addbreak_etime" type="time" value=""><input class="edit-workhours addbreak_day" type="hidden" value="'+day+'"><button style = "color : blue" onclick = "delbreak(this)">remove</button></div></div>';
		try {
		document.getElementById(days[day] + '_breakhours').innerHTML = document.getElementById(days[day] + '_breakhours').innerHTML+ data;
		}
		catch(err){
			//alert(err.message);
			console.log(err  + days[day] + '_breakhours');
            var temp = '<div id="'+days[day]+'_breakhours" style="text-align : center"><span style="text-align : center">Break Timings&nbsp;:&nbsp;</span><div><div class="notyetthought addbreak_div"  style="text-align : center;"><span  class="hours-dis" style = " display :none"><span style="color : red; font-size : 23px;">stime</span>&nbsp;-&nbsp;<span style="color : red; font-size : 23px;">etime</span><span style="position : static">&nbsp;(name)&nbsp;</span></span></div><div class="edit-display addbreak_div addbreak_data" style="text-align : center"><input class="edit-workhours addbreak_name" type="text" value="" size="12" placeholder="name">&nbsp;&nbsp;-&nbsp;&nbsp;<input class="edit-workhours addbreak_stime" type="time" value="">&nbsp;-&nbsp;<input class="edit-workhours addbreak_etime" type="time" value=""><input class="edit-workhours addbreak_day" type="hidden" value="'+day+'"><button style = "color : blue" onclick = "delbreak(this)">remove</button></div></div></div><br>';
            
            document.getElementById(days[day] + '_maindiv').innerHTML = document.getElementById(days[day] + '_maindiv').innerHTML+ temp;
            
		}
		}
		
	function delbreak(ele){
		
			//$(id).html();
		console.log(ele.parentNode.parentNode.innerHTML);
		
		try {
			
			
			 var id = ele.id.slice(7);
			// alert(id);
			 
			 if (id == ''){
			//	 alert('I am runned');
				var  parent = ele.parentNode.parentNode;
				 parent.parentNode.removeChild(parent);
			 }
		else {	 
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
				if (xhttp.readyState == 4 && xhttp.status == 200){
					var ret = xhttp.responseText;
					if ( ret.slice(0,3) == '800' ){
						$( "." + id + '_del').remove();
					}
					else {
						alert("There is some error in deleting the breakhours. Errorcode : " + ret);
					}
				}
			}
			xhttp.open("POST","php/updateBHours.php",true);
			xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded;charset=UTF-8;");
			xhttp.send('bhid='+id);
		
		}
        }
		
		catch(err){
			console.log(err.message);
		}
		
		
		
		
		
		
		
		/*
		
		if ( id == null){
			// remove the empty element
		}
		else{
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
				if (xhttp.readyState == 4 && xhttp.status == 200){
					var ret = xhttp.responseText;
					if ( ret.slice(0,3) == '800' ){
						$( "." + id + '_del').remove();
					}
					else {
						alert("There is some error in deleting the breakhours. Errorcode : " + ret);
					}
				}
			}
			xhttp.open("POST","php/updateBHours.php",true);
			xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded;charset=UTF-8;");
			xhttp.send('bhid='+id);
		}
		*/
	}
	
</script>
<script>
$(document).ready(function() {
	$("#settings").accordion({
		header: "h3",
    collapsible: true,
    autoHeight: false,
    navigation: true,
	heightStyle: "content",
	widthStyle:"fill",
	});
});
</script>
</head>
<body>
<div style = "margin-top : 30px; border : 1px solid black; border-style : none none solid none">
         <h2 style = "font-family : 'Palatino Linotype', 'Book Antiqua', Palatino, serif; padding : 5px 2px 2px 3px; margin-left : 10px;font-size : 30px;">Welcome <?php echo $_SESSION['user']?></h2>
       
	   <a class= "headerlink1" href="logout.php"><img src="pics/logout.png" height="30px" width="30px"><span>Logout</span></a>
	   
	   
	   
	   <a class="headerlink1" href="settings.php"><img src="pics/settings.png" height="30px" width="30px"><span>Settings</span></a>
	   
	   <a class="headerlink1"href="home.php"><img src="pics/home.png" height="30px" width="30px"><span>Home</span></a>
	   
	   
	   </div>
<div id="settings" style="width : 80%; float : right; align-content : center; border : 1px solid black" >
<h3>User</h3>
<div style="width:70% ;border : 0px solid black">  <div  style="display : inline;">   
<div>
        <img src="pics/user.jpg" alt="user" style="width:40px;height:40px;"><span  style="vertical-align : top; margin-top :-20px; font-size : 30px;">User</span>
    
    </div>
      <br>
      <div> User name:</div>
      <div><input class="user-dis" id="user-name" type="text" height="30px" value="<?php echo $_SESSION['user'] ?>" disabled> </div>
      <br>
      
      <div>Email:</div>
      <div>  <input class="user-dis" id="user-email" type="email" value="<?php echo $_SESSION['email'] ?>" disabled></div>
      <br>    
      <div> 
      <button class="user-dis-edit" id="user-edit" type="button" onclick="useredit()">Edit</button>
      <button class="user-dis-save" style="display:none" id="user-save" type="button" onclick="usersave()" >Save</button>
      <button class="user-dis-cancel" style="display:none" id="user-cancel" type="button" onclick="usercancel()">cancel</button>
          
          <br><br>  
      <button class="user-dis" id="user-changepassword" type="button" onclick=""> Change Password</button>    
      <button class="user-dis" id="user-closeaccount" type="button" onclick="">Close Account</button>
      </div>
</div>
      
      <br>
      <hr>
      <br>
      <div> ACCOUNT USERS</div>
      <table style="width:100%">
          <tr> <td>Select </td>
               <td>Name  </td>
               <td>Email </td>
          </tr>      
      </table>
      <div> <button type="button" onclick="">Add</button> </div>
      
      </div>
	  
	  <!-- provider -->
	  <h3>Provider</h3>
	  <div id="provider" style="width : 700px; height : 500px;">
  <div id=prov_img>  <P>
    <H2>
  <b>      HEALTH PROVIDER
</b>        </H2></P>
    <P style="font-size:15px;color:#A9A9A9;padding:0px;margin:0px">
    Profile picture</P>
    <img class="prov_img" src="pics/propic.png" style="height:120px;width:130px">
    </div>
&nbsp;&nbsp;
    <div class="disp_name"><b><span style="font-size:15px;color:#A9A9A9;padding:0px;margin:0px;">Display name: </span></b><br>
 <input id="profile_name" class="prov_dis" type="text" style="width:300px;height:20px" value="profile name" disabled name="profile_name"></div>
&nbsp;&nbsp;
    <div class="ext_web_add">
    <b><span style="font-size:15px;color:#A9A9A9;padding:0px;margin:0px;">Your external web address: </span></b><br>
    <input id="web_address" class="prov_dis" type="text" style="width:300px;height:20px" value="web address" disabled name="web_address">
    </div>
     &nbsp;&nbsp;<div class="gender"><b><span style="font-size:15px;color:#A9A9A9;padding:0px;margin:0px;">Gender: </span></b><br>
    <select id="gender" class="prov_dis" disabled style="width:100px">
  <option value="NONE">NONE</option>
  <option value="MALE">MALE</option>
  <option value="FEMALE">FEMALE</option>
</select>
</div>   
&nbsp;&nbsp;
<div class="languages">
    <b><span style="font-size:15px;color:#A9A9A9;padding:0px;margin:0px;">Languages spoken:<i>(Example:English,Telugu)</i></span></b><br>
<input id="languages" class="prov_dis" style="width:300px;height:20px" type="text" value="Languages spoken" disabled name="languages">
    </div>&nbsp;&nbsp;
    <div class="specialization">
        <b><span style="font-size:15px;color:#A9A9A9;padding:0px;margin:0px;">SPECIALIZATION:</span></b><br>
        <select disabled id="specialization" class="prov_dis" style="width:150px">
  <option value="CARDIOLOGY">CARDIOLOGY</option>
  <option value="DENTISTRY">DENTISTRY</option>
  <option value="DERMATOLOGY">DERMATOLOGY</option>
</select></div>
    &nbsp;&nbsp;
    <div class="practising">
        <b><span style="font-size:15px;color:#A9A9A9;padding:0px;margin:0px;">Practising since:</span></b><br>
        <select disabled id="practising_since" class="prov_dis" style="width:100px">
<option value="1">1939</option><option value="2">1940</option><option value="3">1941</option><option value="4">1942</option><option value="5">1943</option><option value="6">1944</option><option value="7">1945</option><option value="8">1946</option><option value="9">1947</option><option value="10">1948</option><option value="11">1949</option><option value="12">1950</option><option value="13">1951</option><option value="14">1952</option><option value="15">1953</option><option value="16">1954</option><option value="17">1955</option><option value="18">1956</option><option value="19">1957</option><option value="20">1958</option><option value="21">1959</option><option value="22">1960</option><option value="23">1961</option><option value="24">1962</option><option value="25">1963</option><option value="26">1964</option><option value="27">1965</option><option value="28">1966</option><option value="29">1967</option><option value="30">1968</option><option value="31">1969</option><option value="32">1970</option><option value="33">1971</option><option value="34">1972</option><option value="35">1973</option><option value="36">1974</option><option value="37">1975</option><option value="38">1976</option><option value="39">1977</option><option value="40">1978</option><option value="41">1979</option><option value="42">1980</option><option value="43">1981</option><option value="44">1982</option><option value="45">1983</option><option value="46">1984</option><option value="47">1985</option><option value="48">1986</option><option value="49">1987</option><option value="50">1988</option><option value="51">1989</option><option value="52">1990</option><option value="53">1991</option><option value="54">1992</option><option value="55">1993</option><option value="56">1994</option><option value="57">1995</option><option value="58">1996</option><option value="59">1997</option><option value="60">1998</option><option value="61">1999</option><option value="62">2000</option><option value="63">2001</option><option value="64">2002</option><option value="65">2003</option><option value="66">2004</option><option value="67">2005</option><option value="68">2006</option><option value="69">2007</option><option value="70">2008</option><option value="71">2009</option><option value="72">2010</option><option value="73">2011</option><option value="74">2012</option><option value="75">2013</option><option value="76">2014</option><option value="77">2015<option value="78">2016</option></select>
	</div>&nbsp;&nbsp;
	<div class="Education">
        <b><span style="font-size:15px;color:#A9A9A9;padding:0px;margin:0px;">Educational background:</span></b><pre style="font-size:12px;color:#A9A9A9;padding:0px;margin:0px;">School:  <input id="school" class="prov_dis" style="width:300px;height:20px" type="text"disabled name="school">          Year: <select disabled id="year" class="prov_dis" style="width:80px">
<option value="1">1939</option><option value="2">1940</option><option value="3">1941</option><option value="4">1942</option><option value="5">1943</option><option value="6">1944</option><option value="7">1945</option><option value="8">1946</option><option value="9">1947</option><option value="10">1948</option><option value="11">1949</option><option value="12">1950</option><option value="13">1951</option><option value="14">1952</option><option value="15">1953</option><option value="16">1954</option><option value="17">1955</option><option value="18">1956</option><option value="19">1957</option><option value="20">1958</option><option value="21">1959</option><option value="22">1960</option><option value="23">1961</option><option value="24">1962</option><option value="25">1963</option><option value="26">1964</option><option value="27">1965</option><option value="28">1966</option><option value="29">1967</option><option value="30">1968</option><option value="31">1969</option><option value="32">1970</option><option value="33">1971</option><option value="34">1972</option><option value="35">1973</option><option value="36">1974</option><option value="37">1975</option><option value="38">1976</option><option value="39">1977</option><option value="40">1978</option><option value="41">1979</option><option value="42">1980</option><option value="43">1981</option><option value="44">1982</option><option value="45">1983</option><option value="46">1984</option><option value="47">1985</option><option value="48">1986</option><option value="49">1987</option><option value="50">1988</option><option value="51">1989</option><option value="52">1990</option><option value="53">1991</option><option value="54">1992</option><option value="55">1993</option><option value="56">1994</option><option value="57">1995</option><option value="58">1996</option><option value="59">1997</option><option value="60">1998</option><option value="61">1999</option><option value="62">2000</option><option value="63">2001</option><option value="64">2002</option><option value="65">2003</option><option value="66">2004</option><option value="67">2005</option><option value="68">2006</option><option value="69">2007</option><option value="70">2008</option><option value="71">2009</option><option value="72">2010</option><option value="73">2011</option><option value="74">2012</option><option value="75">2013</option><option value="76">2014</option><option value="77">2015<option value="78">2016</option></select>
    </pre></div>&nbsp;&nbsp;
    
    <div class="professional_affiliations">
    <b><span style="font-size:15px;color:#A9A9A9;padding:0px;margin:0px;">Professional affiliations: </span></b><br>
    <input id="professional_affiliations" class="prov_dis" type="text" style="width:300px;height:20px" disabled name="professional_affiliations">
    </div>
     &nbsp;&nbsp;
     <div class="hospital_affiliations">
    <b><span style="font-size:15px;color:#A9A9A9;padding:0px;margin:0px;">Hospital affiliations: </span></b><br>
    <input id="hospital_affiliations" class="prov_dis" type="text" style="width:300px;height:20px" disabled name="hospital_affiliations">
    </div>
     &nbsp;&nbsp;
    <div class="brand_affiliations">
    <b><span style="font-size:15px;color:#A9A9A9;padding:0px;margin:0px;">Brand affiliations: </span></b><br>
    <input id="brand_affiliations" class="prov_dis" type="text" style="width:300px;height:20px" disabled name="brand_affiliations">
    </div>
     &nbsp;&nbsp;
    <div class="insurance_options">
    <b><span style="font-size:15px;color:#A9A9A9;padding:0px;margin:0px;">Insurance options: </span></b><br>
    <input id="Insurance_options" type="text" class="prov_dis" style="width:300px;height:20px" disabled name="insurance_options">
    </div>
     &nbsp;&nbsp;
    <div class="summary">
    <b><span style="font-size:15px;color:#A9A9A9;padding:0px;margin:0px;">Summary:<i>(1000 characters minimum)</i> </span></b><br>
    <input id="summary" class="prov_dis" type="text" style="width:450px;height:235px" disabled name="summary">
    </div>
     &nbsp;&nbsp;
<div >
   
    <button id="prov_edit" type="submit" class="button" style="vertical-align:middle" onclick="editdata()"><span>Edit </span></button>

    
        
        <button id="prov_save" type="submit" class="button" style="display:none;vertical-align:middle" ><span>Save </span></button>
          <button id="prov_cancel" type="submit" class="button" style="display:none;vertical-align:middle" onclick="canceldata()"><span>cancel </span></button>

    </div>

   
   
    
  </div>
		 
		 
		 
	<h3>Practice</h3>
<div style="width:70%; border:0px solid black;"> 
<div>        <img src="pics/building.png" alt="user" style="width:50px;height:50px;"><span  style="vertical-align : top;margin-top : 5px; ;font-size : 30px;">Practice</span>
</div>
   <div>Email:</div>
      <div>  <input type="text" class="prac-dis" value="harsha@gmail.com" disabled></div>   
       <br>
    <div> Phone number:</div>    
    <div> <input class = "prac-dis" type="number" value="9859649856" disabled></div>
    
    
      <br>
    <div> street</div>    
    <div> <input type="text" class="prac-dis" value="kapili 34" disabled></div>
    
       <br>
    <div>zip</div>    
    <div> <input type="number" class="prac-dis" value="49856" disabled></div>
    
       <br>
    <div>city</div>    
    <div> <input type="text" class="prac-dis" value="Guwahati" disabled></div>
    
       <br>
    <div> state</div>    
    <div> <input type="text" value="Assam" class="prac-dis" disabled></div>
    
       <br>
    <div> time zone</div>    
    <div> <input type="text" value="eastern zone" class="prac-dis" disabled></div>
    <br>
    <div>
        <button id="prac-edit" class="prac-dis-edit" type="button" onclick="editofedit1()">Edit</button> 
        <button style="display:none;" id="prac-save" class="prac-dis-edit" type="button" >Save</button>
        <button style="display:none;" id="prac-cancel" class="prac-dis-cancel" type="button" onclick="editcancel1()">cancel</button>
    </div>
    
    
</div>
  <h3>Patients</h3>
  <div style="width:70%; border:0px solid black;"> 
<div>        <img src="" alt="user" style="width:50px;height:50px;"><span  style="vertical-align : top;margin-top : 5px; ;font-size : 30px;">Patients</span>
</div>
   <div>Default recall interval:</div>
      <div>  <input class="pat-dis" id="pat_defaultrecallinterval" type="text" value="Eveyery 12 Months" disabled></div>   
       <br>
    <div> Default contact method:</div>    
    <div> <input  class="pat-dis" id="pat_defaultcontactmethod" type="text" value="Email" disabled></div>
    
    
      <br>
    <div>Default birthday greeting:</div>    
    <div> <input class="pat-dis" id="pat_defaultbirthdaygreeting" type="text" value="No" disabled></div>
    
       <br>
    <div>Default recall method:</div>   
    <div> <input class="pat-dis" id="pat_defaultrecallmethod" type="text" value="Email" disabled></div>
    
       <br>
    <div>Default language:</div>    
    <div> <input class="pat-dis" id="pat_defaultlanguage" type="text" value="English" disabled></div>
    
  
    <br>
    <div> 
        <button class="pat-dis-edit" id="pat-edit" type="button" onclick="editofedit1()">Edit</button>
        <button class="pat-dis-save" id="pat-save" style="display:none" type="button">Save</button>
        <button class="pat-dis-cancel" id="pat-cancel" style="display:none" type="button" onclick="editcancel2()">cancel</button>
    
    </div>
    
    
</div>

<h3>Hours</h3>
<div style="width:70%; border:0px solid black;"> 
<div>        <img src="/home/harsha/Documents/man.jpg" alt="user" style="width:50px;height:50px;"><span  style="vertical-align : top;margin-top : 5px; ;font-size : 30px;">Provider</span>
</div>
<br>

		<?php 
		
		//database connection start for working hours and break hours
						
				$workhoursquery = 'SELECT * FROM `workinghours` ORDER BY `day` ASC;';
				if ( !($retval = mysqli_query($con1,$workhoursquery))){
					die("711 Error : COuldn't select the table");
				}
				$workhours = mysqli_fetch_array($retval,MYSQLI_BOTH);
				$breakquery = 'SELECT * FROM `breakhours` ORDER BY `day` ASC,`starttime` ASC,`endtime` DESC;';
				if ( !($breakqueryret = mysqli_query($con1,$breakquery))){
					die("711 Error : COuldn't select the table");
				}
				$breakhours = mysqli_fetch_array($breakqueryret,MYSQLI_BOTH);
		//database connection end
		
		
		
		// defining 0 => 'Sunday' so on ........
		$days = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
		// looping start .
		$i=0;
		$content = '';
		while ( $workhours) {
			$content .= '<div  id="'.$days[$workhours['day']].'_maindiv" style="border : 1px solid black; text-align : left;"><div><span style="font-size : 28px; font-color : grey">&nbsp;&nbsp;'.$days[$workhours['day']].' : <br></span> <span style="float : right"><a href="javascript:addbreak('.$workhours['day'].')">add break</a></span></div>';
			
			if ( $workhours['offday'] == 1){
				$content .= '<div style="text-align : center"><span style="color : blue; font-size : 28px" class="hours-dis" id="'.$days[$workhours['day']].'_hours">&nbsp;&nbsp;Holiday</span>
				<div class="edit-display" style="display : none"><select id="'.$workhours['day'].'_offday" onchange="changeDisplay(this)" name="offday">
				<option value="1">Holiday</option>
				<option value="0">Working Day</option></select>
				&nbsp;&nbsp;&nbsp;<input class="edit-holidays" id="'.$days[$workhours['day']].'_stime" type="time" value="'.substr($workhours['starttime'],0,5).'">
				&nbsp;-&nbsp;
				<input class="edit-holidays" id="'.$days[$workhours['day']].'_etime" type="time" value="'.substr($workhours['endtime'],0,5).'"></div></div><br>';
				
			}
			else {
				
				$stime =substr($workhours['starttime'],0,5);
				$stime = (sprintf("%02d",((int)(($stime[0].$stime[1])%12))?(($stime[0].$stime[1])%12):12)).$stime[2].$stime[3].$stime[4].' '.(((int)(($stime[0].$stime[1])/12))?'PM':'AM');
				
				$etime =substr($workhours['endtime'],0,5);
				$etime = (sprintf("%02d",(int)(($etime[0].$etime[1])%12)?(($etime[0].$etime[1])%12):12)).$etime[2].$etime[3].$etime[4].' '.(((int)(($etime[0].$etime[1])/12))?'PM':'AM');
				if ( $etime == '00:00 PM')
					$etime = '12:00 PM';
				
				$content .= '<div style="text-align : center"><span>Working Hours : <br></span><span style="color : green; font-size : 24px;" class="hours-dis" id="'.$days[$workhours['day']].'_hours">'.$stime.' - '.$etime.'</span>';
				$content .= '<div class="edit-display" style="display : none"><select id="'.$workhours['day'].'_offday" onchange="changeDisplay(this)" name="offday">
				<option value="0">Working Day</option>
				<option value="1">Holiday</option></select>
				&nbsp;&nbsp;&nbsp;<input class="edit-workhours" id="'.$days[$workhours['day']].'_stime" type="time" value="'.substr($workhours['starttime'],0,5).'">&nbsp;-&nbsp;
				<input class="edit-workhours" id="'.$days[$workhours['day']].'_etime" type="time" value="'.substr($workhours['endtime'],0,5).'"></div></div><br>';
				
					
					
				}
				$flag = 0;
				
			
			while ($breakhours){
					if ( $breakhours['day'] == $workhours['day']){
						
							if ( $flag == 0){
							$content .= '<div id="'.$days[$workhours['day']].'_breakhours" style="text-align : center"><div><span style="text-align : center">Break Timings&nbsp;:&nbsp;</span></div>';
							$flag = 1;
							}
						
						$stime_b =substr($breakhours['starttime'],0,5);
						$stime_b = (sprintf("%02d",((int)(($stime_b[0].$stime_b[1])%12))?(($stime_b[0].$stime_b[1])%12):12)).$stime_b[2].$stime_b[3].$stime_b[4].' '.(((int)(($stime_b[0].$stime_b[1])/12))?'PM':'AM');
						if ($stime_b == '00:00 PM'){
							$stime_b = '12:00 PM';
						}
						$etime_b =substr($breakhours['endtime'],0,5);
						$etime_b = (sprintf("%02d",((int)(($etime_b[0].$etime_b[1])%12))?(($etime_b[0].$etime_b[1])%12):12)).$etime_b[2].$etime_b[3].$etime_b[4].' '.(((int)(($etime_b[0].$etime_b[1])/12))?'PM':'AM');
						if ($stime_b == '00:00 PM'){
							$stime_b = '12:00 PM';
						}
						
						//echo $breakhours['day'];
						
						$content .= '<div><div class="'.$breakhours['ID'].'_del" style="text-align : center"><span  class="hours-dis breakhours_span" id="breakhour_'.$breakhours['ID'].'"><span class="breakhours_stime" style="color : red; font-size : 23px;">'.$stime_b.'</span>&nbsp;-&nbsp;<span class="breakhours_etime" style="color : red; font-size : 23px;">'.$etime_b.'</span><span style="position : static" class="breakhours_name">&nbsp;&nbsp;('.$breakhours['name'].')</span></span></div>';
						$content .= '<div class="edit-display '.$breakhours['ID'].'_del" id="'.$breakhours['ID'].'_div"  style="display : none; text-align : center"><input class="edit-workhours breakhours_id" id="name_'.$breakhours['ID'].'" type="text" value="'.$breakhours['name'].'" size="12" placeholder="name">&nbsp;&nbsp;-&nbsp;&nbsp;<input class="edit-workhours" id="'.$breakhours['ID'].'_stime" type="time" value="'.substr($breakhours['starttime'],0,5).'">&nbsp;-&nbsp;
				<input class="edit-workhours" id="'.$breakhours['ID'].'_etime" type="time" value="'.substr($breakhours['endtime'],0,5).'">
				<input class="edit-workhours" id="'.$breakhours['ID'].'_day" type="hidden" value="'.$breakhours['day'].'">
				<button id="remove_'.$breakhours['ID'].'" onclick="javascript:delbreak(this)" style="color : blue ; border = 0px solid grey;">remove</button>
				</div></div>';
				
						
						$breakhours = mysqli_fetch_array($breakqueryret,MYSQLI_BOTH);
						
					}
					else {
						break;
					}
			}
			if ( $flag == 1){
							$content .= '</div>';
							$flag= 0;
						}
			$content .= '</div>';
		$workhours = mysqli_fetch_array($retval,MYSQLI_BOTH);
		}
		
		echo $content;
		
		?>



	<div>
	
        <button class="hour-dis-edit" id="hour-edit" type="button" onclick="hoursDisplayEdit()">Edit</button>
    <button class="hour-dis-save" id="hour-save" style="display:none" type="button" onclick="hoursSave()">Save</button>
        <button class="hour-dis-cancel" id="hour-cancel" style="display:none" type="button" onclick="hoursDisplayCancel()">cancel</button>
    </div>
    
    
</div>
	<h3>Calendar</h3>
	<div style="width:70%; border:0px solid black;"> 
<div>        <img src="/home/harsha/Documents/man.jpg" alt="user" style="width:50px;height:50px;"><span  style="vertical-align : top;margin-top : 5px; ;font-size : 30px;">Calendar</span>
</div>
   <div>Display Hours:</div>
      <div>  <input class="cal-dis" id="displayhours-cal" type="text" value="9am-6pm" disabled></div>   
       <br>
    <div> Scheduling increment:</div>    
    <div> <input class="cal-dis" id="schedulingincrement-cal" type="text" value="15min" disabled></div>
    
    
      <br>
    <div> Default appointment duration:</div>    
    <div> <input  class="cal-dis" id="defaultappointmentduration-cal"type="text" value="1 Hour" disabled></div>
    
       <br>
    <div>Default to check for scheduling conflicts</div>    
    <div> <input class="cal-dis" id="defaulttocheckforscheduling-cal" type="text" value="Yes" disabled></div>
    
       <br>
    <div>Synchronize with external calendar:</div>    
    <div> <input class="cal-dis" id="synchronize-cal" type="text" value="No" disabled></div>
    
    <br>
    <div>
        <button class="cal-dis-edit" id="cal-edit"  type="button" onclick="editofedit4()">Edit</button> 
          <button class="cal-dis-save" id="cal-save" style="display:none" type="button">Save</button> 
          <button class="cal-dis-cancel" id="cal-cancel" style="display:none" type="button" onclick="editcancel4()">cancel</button> 
    </div>
    
    
</div>
	  </div>
	  
</body>

</html>