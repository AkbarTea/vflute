<div class="track_wrapper">
	<div class="track_title">
	 	<?php echo($c['name'] . ' - ' . $c['trackname']); ?>
	</div>
	
	<div class="track_player">
		<?php if (!isset($c['type'])) { ?>
			<form style="font-size: 0" method="POST">
				<input class="button" id="trackinfo" type="hidden" value="<?php echo $c['trackname']; ?>" name="trackname">

		<div class="player_rating">
				<?php if (isset($c['current_user_vote']) && $c['current_user_vote'] == 'down'): ?>
					<input class="rating_button" type="submit" name="track_rating_up" value="+">
					<input class="rating_button <?php echo "disabled_button";?>" <?php echo "disabled";?> type="submit" name="track_rating_down" value="-">
				<?php endif ?>

				<?php if (isset($c['current_user_vote']) && $c['current_user_vote'] == 'up'): ?>
					<input class="rating_button <?php echo "disabled_button";?>"" <?php echo "disabled";?> type="submit" name="track_rating_up" value="+">
					<input class="rating_button" type="submit" name="track_rating_down" value="-">
				<?php endif ?>

				<?php if (isset($c['current_user_vote']) && $c['current_user_vote'] == 'empty'): ?>
					<input class="rating_button" type="submit" name="track_rating_up" value="+">
					<input class="rating_button" type="submit" name="track_rating_down" value="-">
				<?php endif ?>
		</div>

			</form>
		<?php } ?>
		<div class="player_audio">
 			<audio controls="" src="<?php echo $c['url'] ?>"></audio>
		</div>
		<button class="button ctrlbutton download_btn" id="download_btn">Download
		</button>
		<?php if (isset($c['type'])) { ?>
		<form style="font-size: 0;" method="POST">
			<input class="button" id="trackinfo" type="hidden" value="<?php echo $c['trackname']; ?>" name="trackname">
			<input class="button" type="submit" name="track_delete" value="Delete">
	
			<?php if (isset($c['shared']) && $c['shared'] == 1): ?>
				<input class="<?php echo "shared_" ?>button" <?php echo "disabled"; ?> type="submit" name="track_share" value="Share<?php echo "d";  ?>">
			<?php endif ?>
			<?php if (isset($c['shared']) && $c['shared'] == 0): ?>
				<input class="button" type="submit" name="track_share" value="Share">
			<?php endif ?>
		
		</form>
		<?php
		} 
		?>
	</div>

	<div class="track_info">
		<!-- <div class="track_views">Views <?php echo $c['views']?></div> -->
	    <div class="track_rating">Rating <?php echo $c['rating'] ?></div>
	</div>
</div>