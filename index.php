<?php
/**********************************************
V 0.1
Progress Bar with upload file with Jquery and PHP by Frederic Pioch

REQUIREMENT:
	# >= PHP 5.4 
	# Jquery
	# Jquery ui (optional)
	# session.upload_progress.enabled On. See your phpinfo(); 

See the whole code for explainations. 

I voluntary let the js script on the same page to allow an easy vew between the html and the js / jquery

	
**********************************************/
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_FILES["userfile"])) {

	$target_folder = "tmp/"; //where need to upload the file
	$file_name = $_FILES["userfile"]['name']; //real file name
	if(is_file($target_folder.$file_name)) { $file_name = time()."_".$file_name; } //if file already exist, change the name to not overwrite it
	move_uploaded_file($_FILES["userfile"]['tmp_name'], $target_folder.$file_name); //moving the file
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<meta name="description" content="Upload your files and see it going on on a smoothy progress bar with jquery !" />
	<meta name="author" content="Frederic Pioch" />
	<title>Progress bar upload with jquery</title>
	<script src="http://code.jquery.com/jquery-latest.min.js"></script>
	<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
	<link rel="stylesheet" href="http://jquery-ui.googlecode.com/svn/tags/1.8.2/themes/dot-luv/jquery-ui.css" />
	<link rel="stylesheet" href="progressbar.css" />
</head>
<body>

<div id="main_bloc">
	<h1 id="title">- Progress Bar with Jquery -</h1>
	<br />
	<br />

<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST" id="myForm" enctype="multipart/form-data" target="hidden_iframe">
   <input type="hidden" value="myForm"  name="<?=ini_get("session.upload_progress.name")?>" />
<label for="userfile">Choose a file:</label>  
   <input type="file" name="userfile" id="userfile" required />
   <br />
   <br />
   <input type="submit" value="Start Upload" class="ui-corner-all" />

</form>	
	
	
  
<div class="progress-bar blue stripes" id="bar_blank">	<span class="bar_content" style="width: 0%"></span> </div>
		<br />
		<br />
<div id="status"></div>
  <iframe id="hidden_iframe" name="hidden_iframe" src="about:blank"></iframe>

	
</div>
<div id="footer"> 

- <a href="http://www.orugari.fr/portfolio/" target="_blank">Home Page</a> - 
<a href="https://github.com/orugari" target="_blank">Github</a> - 
<a href="https://www.facebook.com/frederic.pioch" target="_blank">Facebook</a> - 
<br />
<br />
<a href="http://validator.w3.org/check?uri=<?=$_SERVER['HTTP_HOST'].$_SERVER["PHP_SELF"]?>&amp;charset=(detect+automatically)&amp;doctype=Inline&amp;group=0" target="_blank">
	<img src="http://www.w3.org/QA/Tools/I_heart_validator_lg" alt="validate me" />
</a>
</div>
<script>
$(document).ready(function(){
// The action before and after upload
function toggleBarVisibility() {
	$("#status").show("blind", 1000).html('Uploading...');
	$( "#bar_blank" ).css( "display", "block");
	$(".bar_content").css('width','0%');
}
// We get the current % on upload_frame.php
function sendRequest() {
	$.get('upload_frame.php', function(http) {	 handleResponse(http); 	});
}

//the function for update the progress bar and check if finish
function handleResponse(http) {
		//we set the width with jquery
		$(".bar_content").css('width',''+http+'%');
		// If still not finish, we refresh the request which get the content of upload_frame.php
		if (http < 100) { var t=setTimeout(sendRequest, 1000);	}
		// If finish, everything here will be done
		else 
		{
			//random effect, do what ever you want
			$(".bar_content").removeClass(".bar_content").css('width','100%');
			$( "#bar_blank" ).delay(2000).hide("highlight", 1000);
			$("#status").html('Upload finished !').delay(2000).hide("blind", 1000);
		}
}
// The function when you send the Form
function startUpload() {
	toggleBarVisibility();
	var t=setTimeout(sendRequest, 1000);
}
//everything start here
$("#myForm").submit(function() { if($('#userfile').val() != '') { startUpload();	} });

//jusst stylish button from jquery ui
$( "[type=submit]" ).button();

});
</script>
</body>
</html>