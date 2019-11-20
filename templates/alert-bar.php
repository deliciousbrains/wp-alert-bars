<?php
$class = '';
if ( $alert_bar->is_footer ) {
	$class .= ' alert-bar-footer';
}
if ( $alert_bar->is_sticky ) {
	$class .= ' alert-bar-sticky';
}
if ( 'minimize' === $alert_bar->behaviour ) {
	$class .= ' alert-bar-narrow';
}
?>

<div id="alert-bar-<?php echo $alert_bar->id; ?>" class="alert-bar<?php echo $class; ?>" data-slug="<?php echo $alert_bar->key; ?>-alert-bar" style="display: none;">
	<?php if ( $alert_bar->countdown_end ) {
		include_once 'alert-bar-countdown.php';
	} ?>
	<p>
		<?php echo $alert_bar->message; ?>
	</p>
	<?php if ( $alert_bar->cta ) : ?>
		<a class="dbi-btn" target="<?php echo $alert_bar->cta['target']; ?>" href="<?php echo $alert_bar->cta['url']; ?>"><?php echo empty( $alert_bar->cta['title'] ) ? 'Click for Details' : $alert_bar->cta['title']; ?></a>
	<?php endif; ?>

	<?php if ( 'permanent' !== $alert_bar->behaviour ) : ?>
		<a href="#" class="dismiss"></a>
	<?php endif; ?>
</div>

<?php if ( $alert_bar->bg_color || $alert_bar->text_color ) : ?>
	<style type="text/css">
		<?php if ( $alert_bar->bg_color ) : ?>
		#alert-bar-<?php echo $alert_bar->id; ?> {
			background: <?php echo $alert_bar->bg_color; ?>
		}

		<?php endif; ?>
		<?php if ( $alert_bar->text_color ) : ?>
		#alert-bar-<?php echo $alert_bar->id; ?> {
			color: <?php echo $alert_bar->text_color; ?>;
		}
		<?php endif; ?>
	</style>
<?php endif; ?>

<?php if ( $alert_bar->cta && ( $alert_bar->cta_bg_color || $alert_bar->cta_text_color ) ) : ?>
	<style type="text/css">
		<?php if ( $alert_bar->cta_bg_color ) : ?>
		#alert-bar-<?php echo $alert_bar->id; ?>:not(.alert-bar--narrow) .dbi-btn {
			background: <?php echo $alert_bar->cta_bg_color; ?>
		}

		<?php endif; ?>
		<?php if ( $alert_bar->cta_text_color ) : ?>
		#alert-bar-<?php echo $alert_bar->id; ?>:not(.alert-bar--narrow) .dbi-btn {
			color: <?php echo $alert_bar->cta_text_color; ?>;
		}
		<?php endif; ?>
	</style>
<?php endif; ?>

<?php if ( $alert_bar->countdown_end && ( $alert_bar->countdown_bg_color || $alert_bar->countdown_text_color ) ) : ?>
	<style type="text/css">
		<?php if ( $alert_bar->countdown_bg_color ) : ?>
		#alert-bar-<?php echo $alert_bar->id; ?>:not(.alert-bar--narrow) .alert-bar__countdown {
			background: <?php echo $alert_bar->countdown_bg_color; ?>!important;
		}
		<?php endif; ?>
		<?php if ( $alert_bar->countdown_text_color ) : ?>
		#alert-bar-<?php echo $alert_bar->id; ?>:not(.alert-bar--narrow) .alert-bar__countdown span {
			color: <?php echo $alert_bar->countdown_text_color; ?>!important;
		}
		<?php endif; ?>
	</style>
<?php endif; ?>
