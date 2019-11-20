<span class="alert-bar__countdown" data-timer-date="<?php echo $alert_bar->countdown_end; ?>">
	<span>
		<span class="days"></span>
		<small>Days</small>
	</span>
	<span>
		<span class="hours"></span>
		<small>Hours</small>
	</span>
	<span>
		<span class="minutes"></span>
		<small>Minutes</small>
	</span>
	<span>
		<span class="seconds"></span>
		<small>Seconds</small>
	</span>
</span>

<?php if ( $alert_bar->countdown_bg_color || $alert_bar->countdown_text_color ) : ?>
<style type="text/css">
	<?php if ( $alert_bar->countdown_bg_color ) : ?>
	.alert-bar .alert-bar__countdown {
		background: <?php echo $alert_bar->countdown_bg_color; ?>!important;
	}
	<?php endif; ?>
	<?php if ( $alert_bar->countdown_text_color ) : ?>
	.alert-bar .alert-bar__countdown span {
		color: <?php echo $alert_bar->countdown_text_color; ?>!important;
	}
	<?php endif; ?>
</style>
<?php endif; ?>
