/* from https://wordpress.org/support/topic/show-all-posts-of-a-custom-post-type */
echo do_shortcode( '[leaflet-map height=300 lat=18.55 lng=73.86 zoom=11]');
$type = 'stop';
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
	<th>StopCode</th><th>Stop Name (English)</th><th>Lat-long</th><th></th>
	</tr>
	<?php
	/* table header done */

  while ($my_query->have_posts()) : $my_query->the_post(); ?>
	<tr>
	<td><a href="<?php the_permalink() ?>"><?php the_field('stopcode') ?></a></td>
	<td><?php the_title() ?></td>
	<td><small><?php echo get_field('stop-lat-long')['lat'] . ', ' . get_field('stop-lat-long')['lng'] ?></small></td>
	<td><?php edit_post_link('edit', '<small>', '</small>'); ?></td>
	</tr>
    <?php
	echo do_shortcode( '[leaflet-marker lat=' . get_field('stop-lat-long')['lat'] . ' lng=' . get_field('stop-lat-long')['lng'] . ' ]<a href="' . get_permalink() . '">'. get_the_title() . ' (' . get_field('stopcode') . ')</a>[/leaflet-marker]'); 
  endwhile;
  
  echo '</table>';
}
wp_reset_query();  // Restore global post data stomped by the_post().
