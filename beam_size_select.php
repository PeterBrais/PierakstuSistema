<?php
	include_once "includes/manager.class.php";

	$sizes = Manager::BeamSizes();
?>

	<select class="custom-select" name="sizes" id="beam_size_select">
		<option selected value="0">Izvēlieties kubatūras izmēru</option>

<?php
	foreach($sizes as $size)
	{
		echo '<option value="'.$size['id'].'">'.$size['size'].'</option>';
	}
?>

	</select>