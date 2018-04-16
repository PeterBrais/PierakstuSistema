<?php
	include_once "../includes/manager.class.php";

	$sizes = Manager::BeamSizes();
?>

	<select class="custom-select" name="sizes" id="beam_size_select">
		<option selected value="" style="font-weight:bold;">Izvēlieties kubatūras izmēru</option>

<?php
	foreach($sizes as $size)
	{
		echo '<option value="'.$size['id'].'">'.$size['size'].'</option>';
	}
?>

	</select>