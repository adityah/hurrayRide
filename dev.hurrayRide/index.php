<?php
?>
<html>
<head>

<style type="text/css">
.datepick-popup { z-index: 10; }
.datepick-popup img { border: 1px; }
</style>


<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
<link href="js/datePicker/jquery.datepick.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/datePicker/jquery.datepick.js"></script>


<script type="text/javascript">
	/*$(function(){
	$('#travel_date').datepick({dateFormat: 'yyyy-mm-dd'});
	
	
	$("#createNewTrip").submit(function(event){
    // setup some local variables
    var $form = $(this),
        // let's select and cache all the fields
        $inputs = $form.find("input, select, button, textarea"),
        // serialize the data in the form
        serializedData = $form.serialize();

    // let's disable the inputs for the duration of the ajax request
    $inputs.attr("disabled", "disabled");

	$.ajax({
        url: "ac.php?action=viewTrips",
        type: "post",
        data: serializedData,
        // callback handler that will be called on success
        success: function(response, textStatus, jqXHR){
            // log a message to the console
            console.log("Hooray, it worked!");
        },
        // callback handler that will be called on error
        error: function(jqXHR, textStatus, errorThrown){
            // log the error to the console
            console.log(
                "The following error occured: "+
                textStatus, errorThrown
            );
        },
        // callback handler that will be called on completion
        // which means, either on success or error
        complete: function(){
            // enable the inputs
            $inputs.removeAttr("disabled");
        }
    });
	});
	
	});*/
</script>

<script type="text/javascript">
	$(function(){
	$('#travel_date').datepick({dateFormat: 'yyyy-mm-dd'});
	
	$("#createNewTrip").submit(function(event) {
		 /* stop form from submitting normally */
		event.preventDefault(); 
		var formData = $("#createNewTrip").serialize();
		$("content").html('http://cscie12.dce.harvard.edu/lecture_notes/2010/20100310/images/loading-gif-animation.gif').show();
		var url="ac.php?action=createNewTrip";
		$.ajax({
		url:url,
		data: formData,
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
	
	function swapContent(cv){
		$("#createNewTrip").submit(function(event) {
		 /* stop form from submitting normally */
		event.preventDefault(); 
		var formData = $("#createNewTrip").serialize();
		$("mydiv").html("Put animated .gif here").show();
		var url="ac.php?action=createNewTrip";
		$.post(url,{contentVar:cv},function(data){
		$("#mydiv").html(data).show();
		});
		
		});
	
	}
	
	function tryFocusOut(action){
	//$("#"+action).focusout(function() {
		$.ajax({
		url:"ac.php?action=countTrips",
		data: {from:$("#view_from_city").val(), to:$("#view_to_city").val()},
		success: function(data){
			var obj = jQuery.parseJSON(data);
			if(obj.is_error==false)
			{
			$("#myViewDiv").html(obj.response.count).show();
				//$("#myViewDiv").html(obj.response).show();
			}
			else if (obj.is_error==true)
			{
					var errorMsg="Cannot be Empty";
				$("#myViewDiv").html(errorMsg).show();
			}
			else
			{
				var errorMsg="Fatal Error ... Screw You ...";
				$("#myViewDiv").html(errorMsg).show();
			}
			}
		});
	//});
	}
	
</script>


</head>


<body>


	<form id='createNewTrip'>
	<fieldset >
	<legend>Create New Trip</legend>
		<table>
			<tr width="100">
				<td width="80"><label for='from_city'>To*: </label></td>
				<td ><input type='text' name='from_city' id='from_city' size="30" /></td>
			</tr>
			<tr>
				<td><label for='to_city' >From*:</label></td>
				<td><input type='text' name='to_city' id='to_city' size="30" />
				</td>
				
			</tr>
			<tr>
				<td><label for='travel_date' >Travel Date Time*:</label></td>
				<td><input type='text' name='travel_date' id='travel_date' size="30" autocomplete="off" />
				</td>
				
			</tr>
			<tr>
				<td><label for='title' >Title*:</label></td>
				<td><input type='text' name='title' id='title' siz	e="30"  autocomplete="off" /></td>
			</tr>
			<tr>
				<td><label for='Description' >Description*:</label></td>
				<td><textarea row="50" cols="40" name='Description' id='Description' maxlength="1500"  autocomplete="off" ></textarea></td>
			</tr>
		</table>		
			<input type='submit' id='submit' name='Submit' value='Submit' />
			<input type="hidden" name="action" value="createNewTrip" />
			<input type='hidden' name='submitted' id='submitted' value='1'/>
	</fieldset>
	</form>
</div>
<div id="content">	
</div>
</div>

</body>

</html>
