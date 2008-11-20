<?php
function displayErrorPage($request){
	?>
	<h1>Workflow engine error</h1>
	<?="It appears that you have entered an invalid value."?>
	<?php
}
displayErrorPage($_REQUEST);

?>