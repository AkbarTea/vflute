<link rel="stylesheet" type="text/css" href="../css/testpage.css">
<link rel="stylesheet" type="text/css" href="../css/flute.css">

<div class="container_flute">
	<div class="flute_img_container">
		<img class="flute_img" src="../static/images/flute.png" usemap="#image-map">
	</div>
	<div class="border_flute">
		<?php for ($i=0; $i < 8 + 1; $i++) { 
			?>
			<div class="hole" id="<?php echo $i; ?>"></div>
			<?php
		} ?>
	</div>
</div>

<div class="player">
	<div class="container_control">
		<div>
			<button class="button ctrlbutton" id="record_button" >Start recording</button>
		</div>

		<div class="audio">
			<audio controls></audio>
		</div>
		<div>
			<button class="button ctrlbutton" id="save_button">Save record</button>
		</div>
	</div>
	<div class="container_control_lower">
		<div>
			<button class="button ctrlbutton" id="download_button">Download</button>
		</div>
	</div>
</div>

<script src="../js/SoundTests.js" type="text/javascript"></script>