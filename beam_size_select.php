<?php
	include_once "includes/manager.class.php";

	$sizes = Manager::BeamSizes();
?>

	<select class="custom-select" name="size" id="beam_size_select">
		<option selected value="0">Izvēlieties kubatūras izmēru</option>

<?php
	foreach ($sizes as $size)
	{
		echo '<option vlaue="'.$size['id'].'">'.$size['size'].'</option>';
	}
?>

	</select>