/* from https://wordpress.org/support/topic/show-all-posts-of-a-custom-post-type */
// echo do_shortcode( '[leaflet-map height=300 lat=18.55 lng=73.86 zoom=11]');
$type = 'route';
$args=array(
  'post_type' => $type,
  'post_status' => 'publish',
  'posts_per_page' => -1,
  'caller_get_posts'=> 1
);
$my_query = null;
$my_query = new WP_Query($args);
if( $my_query->have_posts() ) {

	/* make a table */
	?>
	<table><tr>
	<th>Type</th><th>Route Number</th><th>Route Name (English)</th><th></th>
	</tr>
	<?php
	/* table header done */

  while ($my_query->have_posts()) : $my_query->the_post(); ?>
	<tr>
	<td><?php 
	$routeType = get_field('route_type');
	if( $routeType == 'circular' ) echo '<img src="http://localhost/bus/wp-content/uploads/2016/07/route-circular.png" title="Circular">';
	else if( $routeType == 'single' ) echo '<img src="http://localhost/bus/wp-content/uploads/2016/07/route-single.png" title="Same route in up and down direction">';
	else if( $routeType == 'double' ) echo '<img src="http://localhost/bus/wp-content/uploads/2016/07/route-double.png" title="Different route in up and down direction">';
	else echo 'error!';
	?></td>
	<td><strong><a href="<?php the_permalink() ?>"><?php the_field('route') ?></a></strong></td>
	<td><?php the_title() ?></td>
	<td><?php edit_post_link('edit', '<small>', '</small>'); ?></td>
	</tr>
    <?php
	/*
	echo do_shortcode( '[leaflet-marker lat=' . get_field('stop-lat-long')['lat'] . ' lng=' . get_field('stop-lat-long')['lng'] . ' ]'. get_the_title() . ' <small>(' . get_field('stopcode') . ')</small>[/leaflet-marker]'); 
  */
  endwhile;
  echo '</table>';
}
wp_reset_query();  // Restore global post data stomped by the_post().
