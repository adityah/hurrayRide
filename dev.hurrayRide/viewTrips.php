<html>
<head>
View Trips
<?php
session_start();
?>
<style type="text/css">

#auto_box{border:1px solid #ccc;padding:5px;}
#auto_box span b{float:right;margin:3px 0 0 3px;font-size:10px;color:red;cursor:pointer}
#auto_box span{font-weight:bold;display:inline-block;border:1px solid #eee;padding:0 4px;margin-right:5px;-moz-border-radius:5px;}
#auto_box input{border:0;}
.highlight{background:#DFF7FF}
#auto_box .list_name{overflow-y:scroll;height:200px}
.datepick-popup { z-index: 10; }
.datepick-popup img { border: 1px; }

.layer1 {
margin: 0;
padding: 0;
width: 500px;
}
 
.heading {
margin: 1px;
color: #fff;
padding: 3px 10px;
cursor: pointer;
position: relative;
background-color:#c30;
}
.content {
padding: 5px 10px;
background-color:#fafafa;
}
p { padding: 5px 0; }

</style>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery/jquery.autocomplete.js"></script>
<link href="js/datePicker/jquery.datepick.css" rel="stylesheet" type="text/css" />
<link href="js/jquery/styles.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/datePicker/jquery.datepick.js"></script>

<script type="text/javascript">
jQuery(document).ready(function() {
  jQuery(".content").hide();
  //toggle the componenet with class msg_body
  jQuery(".heading").click(function()
  {
    jQuery(this).next(".content").slideToggle(500);
  });
});
</script>


<script type="text/javascript">


function searchTrips(){
			$.ajax({
					url:"ac.php?action=tripsearch",
					data: {from_city:$("#view_from_city").val(), to_city:$("#view_to_city").val(), travel_date:$('#travel_date').val()},
					success: function(data){
						var obj = jQuery.parseJSON(data);
						//$("#content").html(data).show();
						if(obj.is_error==false)
						{
							if(obj.response==null || obj.response==0)
							{
								$("#content").html("No trips exists, Click here to create new trip now").show();
							}
							else
							{
								var i=1;
								//	$("#searchResult").html(data).show();
								jQuery.each(obj.response, function(key, val) {
									alert("#header"+i);
									$("<span />").text(val.title).appendTo("#heading"+i);
									$("<span />").text(val.description).appendTo("#content"+i);
									i++;
								    });
							}
						}
						else if (obj.is_error==true)
						{
							var errorMsg="Cannot be Empty";
							$("#content").html(errorMsg).show();
						}
						else
						{
							var errorMsg="Fatal Error ... Screw You ...";
							$("#content").html(errorMsg).show();
						}
						}
				});
				
}

function autoComplete(txtBox){
var options, a;
$(function(){
	  options = { 
	  serviceUrl:'ac.php?action=ac' };
	  a = $(txtBox).autocomplete(options);
	});
}

$(function(){
	$('#travel_date').datepick({dateFormat: 'yyyy-mm-dd'});

	$("#expressInterest").submit(function(event) {
		 /* stop form from submitting normally */
		event.preventDefault(); 
		var formData = $("#expressInterest").serialize();
		//$("content").html('http://cscie12.dce.harvard.edu/lecture_notes/2010/20100310/images/loading-gif-animation.gif').show();
		var url="ac.php?action=expressInterest";
		$.ajax({
		url:url,
		data:  {from:$("#view_from_city").val(), to:$("#view_to_city").val()}, //formData,
		success: function(data){
			var obj = jQuery.parseJSON(data);
			$("#content").html(obj.response).show();
			/*if(obj.is_error=false)
			{
				$("#content").html(obj.response).show();
			}
			else if (obj.is_error=true)
			{
					var errorMsg="Cannot be Empty";
				$("#content").html(errorMsg).show();
			}
			else
			{
				var errorMsg="Fatal Error ... Screw You ...";
				$("#content").html(errorMsg).show();
			}*/
			}
		});
		
		});
});

function fetchTripCounts(txt)
{
	//$("#"+action).focusout(function() {
		$.ajax({
		url:"ac.php?action=countTrips",
		data: {from:$("#view_from_city").val(), to:$("#view_to_city").val()},
		success: function(data){
			var obj = jQuery.parseJSON(data);
			if(obj.is_error==false)
			{
				if(obj.response==null || obj.response==0)
				{
					$("#content").html("No trips exists, Click here to create new trip now").show();
				}
				else
				{
					$("#content").html(obj.response.length + " Trips Available").show();
				}
				//$("#myViewDiv").html(obj.response).show();
			}
			else if (obj.is_error==true)
			{
				var errorMsg="Cannot be Empty";
				$("#content").html(errorMsg).show();
			}
			else
			{
				var errorMsg="Fatal Error ... Screw You ...";
				$("#content").html(errorMsg).show();
			}
			}
		});
	//});
	}


</script>

</head>
<body>
	<fieldset >
<form id='viewTripsCount'>

	<legend>View Trips Count</legend>
		<table>
			<tr width="100">
				<td width="80"><label for='view_from_city'>From*: </label></td>
				<td><input type='text' name='view_from_city' id='view_from_city' size="30" onkeydown="" onfocusout="javascript:fetchTripCounts('view_from_city');"/></td>
			</tr>
			<tr>
				<td><label for='view_to_city' >To*:</label></td>
				<td><input type='text' name='view_to_city' id='view_to_city' size="30" onkeydown="" onfocusout="javascript:fetchTripCounts('view_to_city');" />
				</td>
				
			</tr>
			<tr>
				<td><label for='travel_date' >Travel Date Time*:</label></td>
				<td><input type='text' name='travel_date' id='travel_date' size="30" />
				</td>
				
			</tr>
			<tr>
				<td><input type="checkbox" name="hasCar" value="Has Car">Has Car</td>
			</tr>
			<!--tr>
				<td><label for='RequestTitle' >Title*:</label></td>
				<td><input type='text' name='RequestTitle' id='RequestTitle' size="30"  autocomplete="off"  /></td>
			</tr>
			<tr>
				<td><label for='Description' >Description*:</label></td>
				<td><textarea row="50" cols="40" name='Description' id='Description' maxlength="1500"  autocomplete="off" ></textarea></td>
			</tr-->
		</table>		
			<input type='button' name='Search' id='Search' value='search' onclick="javascript:searchTrips();" /> <h4> OR </h4>
			<input type="hidden" name="action" value="tripsearch" />
	
	</form>
	<form id="expressInterest">
	<input type='submit' name='expressInterest' id='expressInterest' value='Express interest in Available trips' />
	<input type='hidden' name='action' value='expressInterest'>
	</form>
	
	</fieldset>
	
	
<div id="content">
</div>

<div id="searchResult">
<!-- div class="layer1" -->
<div class="heading" id="heading1"></div>
<div class="content" id="content1"></div>
<div class="heading" id="heading2"></div>
<div class="content" id="content2"></div>
<div class="heading" id="heading3"></div>
<div class="content" id="content3"></div>
<!-- /div-->

</div>
    
</body>
</html>
