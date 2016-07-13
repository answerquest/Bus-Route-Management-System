if ( get_post_type() == 'stop' ) {  /* MAIN STOP CHECK */

if( get_field('stopcode') ) echo '<h3>Stopcode: ' . get_field('stopcode') . '</h3>';
$maparray = get_field('stop-lat-long');

if( $maparray) {
echo 'Lat: ' . $maparray['lat'] . ' | Long: ' .  $maparray['lng'];
echo '<br>';
echo do_shortcode( '[leaflet-map height=300 lat=' . $maparray['lat'] . ' lng=' . $maparray['lng'] . ' zoom=14]');
echo do_shortcode( '[leaflet-marker]'. get_field('stopcode') .': ' . get_the_title() . '[/leaflet-marker]' );
 
}

?>
<?php 
/* From https://www.advancedcustomfields.com/resources/querying-relationship-fields/ */
/*
*  Query posts for a relationship value.
*  This method uses the meta_query LIKE to match the string "123" to the database value a:1:{i:0;s:3:"123";} (serialized array)
*/

$routesAll = get_posts(array(
	'post_type' => 'route',
	'meta_query' => array(
		'relation' => 'OR', /* <-- from http://wordpress.stackexchange.com/a/104062 */
		array(
			'key' => 'up', // name of custom field
			'value' => '"' . get_the_ID() . '"', // matches exaclty "123", not just 123. This prevents a match for "1234"
			'compare' => 'LIKE'
		),
		array(
			'key' => 'down', // name of custom field
			'value' => '"' . get_the_ID() . '"', // matches exaclty "123", not just 123. This prevents a match for "1234"
			'compare' => 'LIKE'
		),
	)
));

if( $routesAll ): ?>
	<h3>Routes that pass through this stop:</h3>
	<ul>
	<?php foreach( $routesAll as $route ): ?>
		<?php 
		$routenum = get_field('route', $route->ID);
		?>
		<li>
			<a href="<?php echo get_permalink( $route->ID ); ?>">
				<?php echo $routenum . ': ' . get_the_title( $route->ID ); ?>
			</a>
		</li>
	<?php endforeach; ?>
	</ul>
<?php endif; ?>

<?php
} /* END OF MAIN STOP CHECK */
