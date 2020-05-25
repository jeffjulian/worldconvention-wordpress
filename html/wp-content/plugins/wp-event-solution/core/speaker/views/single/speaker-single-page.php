<?php get_header(); ?>

<?php
$options = get_option('etn_event_general_options');
$container_cls = isset($options['single_post_container_width_cls'])
	? $options['single_post_container_width_cls']
	: '';
?>
<div class="etn-speaker-page-container etn-container <?php echo esc_attr($container_cls); ?>">
	<?php
	while (have_posts()) :
		the_post();
		require ETN_DIR . '/core/speaker/views/parts/speaker.php';
	endwhile;
	?>
</div>

<?php get_footer(); ?>