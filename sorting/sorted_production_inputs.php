<div class="form-group row">
	<label class="col-md-2 offset-md-1 col-form-label">
		Veids
		<span class="text-danger" title="Šis lauks ir obligāts">
			&#10033;
		</span>
	</label>
	<div class="col-md-5">
		<select class="custom-select" name="type[]">
			<option selected value="0">Izvēlieties šķirošanas veidu</option>
			<option value="1">Šķirots</option>
			<option value="2">Garināts</option>
		</select>
	</div>
</div>	
<div class="form-group row">
	<label class="col-md-2 offset-md-1 col-form-label">
		Skaits
		<span class="text-danger" title="Šis lauks ir obligāts">
			&#10033;
		</span>
	</label>
	<div class="col-md-5">
		<input class="form-control" type="number" min="0" name="sorted_count[]" aria-describedby="sawnCountArea" placeholder="Kopējais skaits">
		<small id="sawnCountArea" class="form-text text-muted">
			* Satur tikai ciparus, kopējo (gab) skaitu *
		</small>
	</div>
	<div class="col-md-4">
		<?php
			if(isset($_SESSION['sorted_count']))
			{
		?>
			<div class="alert alert-danger alert-size" role="alert">
				<?=$_SESSION['sorted_count']?>
			</div>
		<?php
				unset($_SESSION['sorted_count']);
			}
		?>
	</div>
</div>
<div class="form-group row">
	<label class="col-md-2 offset-md-1 col-form-label">
		Izmēri
		<span class="text-danger" title="Šie lauki ir obligāti">
			&#10033;
		</span>
	</label>
	<div class="col-md-5">
		<div class="row">
			<div class="col-md-4">
				<input class="form-control" type="number" min="0" name="sorted_thick[]" aria-describedby="timeFromArea" placeholder="Biezums" id="thickeness">
			</div>
			<div class="col-md-4">
				<input class="form-control" type="number" min="0" name="sorted_width[]" aria-describedby="timeFromArea" placeholder="Platums" id="width">
			</div>
			<div class="col-md-4">
				<input class="form-control" type="number" min="0" name="sorted_length[]" aria-describedby="timeFromArea" placeholder="Garums" id="length">
			</div>
		</div>
		<small id="timeFromArea" class="form-text text-muted">
			* Satur tikai ciparus *
		</small>
	</div>
	<div class="col-md-4">
		<?php
			if(isset($_SESSION['sorted_sizes']))
			{
		?>
			<div class="alert alert-danger alert-size" role="alert">
				<?=$_SESSION['sorted_sizes']?>
			</div>
		<?php
				unset($_SESSION['sorted_sizes']);
			}
		?>
	</div>
</div>
<div class="form-group row">
	<label class="col-md-2 offset-md-1 control-label">
		Tilpums
	</label>
	<div class="col-md-5">
		<p class="form-control-static" id="sorted_capacity"> m<sup>3</sup></p>
	</div>
</div>
<div class="form-group row">
	<label class="col-md-2 offset-md-1 control-label">
		Tilpums / gab
	</label>
	<div class="col-md-5">
		<p class="form-control-static" id="sorted_capacity_piece"> m<sup>3</sup></p>
	</div>
</div>
