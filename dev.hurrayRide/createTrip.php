<?php


?>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery/jquery.autocomplete.js"></script>
<link href="js/datePicker/jquery.datepick.css" rel="stylesheet" type="text/css" />
<link href="js/jquery/styles.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/datePicker/jquery.datepick.js"></script>


<script type="text/javascript">

$(function(){
	$('#travel_date').datepick({dateFormat: 'yyyy-mm-dd'});
});
	
function createNewTrip()
{

	$.ajax({
	url:"ac.php?action=createNewTrip",
	data: {from:$("#from_city").val(), to:$("#to_city").val(), travel_date:$("#travel_date").val(), title:$("#title").val(), Description:$("#Description").val()},
	success: function(data){
		var obj = jQuery.parseJSON(data);
		if(obj.is_error==false)
		{
			if(obj.response==null || obj.response==0)
			{
				$("#content").html("Trip was added successfully ....").show();
			}
			else
			{
				$("#content").html("Trip Added Successfully !!").show();
			}
			//$("#myViewDiv").html(obj.response).show();

			$("#from_city").val('');
			$("#to_city").val('');
			$("#travel_date").val();
			$("#title").val('');
			$("#Description").val('');


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

<form id='createTrip' action='ac.php'>

<legend>Create A Trip</legend>
<table>
<tr width="100">
<td width="80"><label for='from_city'>From*: </label></td>
<td><input type='text' name='from_city' id='from_city' size="30" onkeydown="" onfocusout=""/></td>
</tr>
<tr>
<td><label for='to_city' >To*:</label></td>
<td><input type='text' name='to_city' id='to_city' size="30" onkeydown="" onfocusout="" />
</td>

</tr>
<tr>
<td><label for='travel_date' >Travel Date Time*:</label></td>
<td><input type='text' name='travel_date' id='travel_date' size="30" />
</td>

</tr>
<tr>
<td><label for='title' >Title*:</label></td>
<td><input type='text' name='title' id='title' size="30"  autocomplete="off"  /></td>
</tr>
<tr>
<td><label for='Description' >Description*:</label></td>
<td><textarea row="50" cols="40" name='Description' id='Description' maxlength="1500"  autocomplete="off" ></textarea></td>
</tr>
</table>
<input type='button' name='Create' id='createTrip' value='createTrip' onclick="javascript:createNewTrip();" value='Create Trip'/>
<input type='hidden' name='action' value='createTrip'/>

	</form>

<div id="content">
</div>
