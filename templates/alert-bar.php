<?php
$class = '';
if ( $alert_bar->is_footer ) {
	$class .= ' alert-bar-footer';
}
if ( $alert_bar->is_sticky ) {
	$class .= ' alert-bar-sticky';
}
?>

<div class="alert-bar<?php echo $class; ?>" data-slug="<?php echo $alert_bar->key; ?>-alert-bar" style="display: none;">
	<p>
		<?php echo $alert_bar->message; ?>
	</p>
	<?php if ( $alert_bar->cta ) : ?>
		<a class="dbi-btn" target="<?php echo $alert_bar->cta['target']; ?>" href="<?php echo $alert_bar->cta['url']; ?>"><?php echo empty( $alert_bar->cta['title'] ) ? 'Click for Details' : $alert_bar->cta['title']; ?></a>
	<?php endif; ?>

	<a href="#" class="dismiss"></a>
</div>