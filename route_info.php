if ( get_post_type() == 'route' ) {  /* MAIN ROUTE CHECK */

if (get_field('route')) 
  echo 'Route num/code: <b>'.get_field('route').'</b>';
if (get_field('route_type')) 
  echo '&nbsp; | &nbsp;Route type: <b>'. showRouteType( get_field('route_type') ) . '</b>';

/* DISPLAY UP STOPS */
$upstops = get_field('up');
if ( $upstops ): ?>
<h4>Up Route:</h4>
<?php /*Make the map, set center as first stop's co-ords */
echo do_shortcode( '[leaflet-map height=300 lat=' . get_field('stop-lat-long', $upstops[0]->ID)['lat'] .
	' lng=' . get_field('stop-lat-long', $upstops[0]->ID)['lng'] . ' zoom=12]'); 
echo 'Schedule: ' . showTiming('A');
?>

<small><ol>
<?php $upCounter = 1; $mapLine = ''; ?>
<?php foreach( $upstops as $p ): /* variable must NOT be called $post (IMPORTANT) */ ?>
	<li>
	<a href="<?php echo get_permalink( $p->ID ); ?>"><?php the_field('stopcode', $p->ID); ?> : 
		<?php echo get_the_title( $p->ID ); ?></a>
	</li>
	<?php /* Inserting marker of each stop into the map, with sequence number */
	echo do_shortcode( '[leaflet-marker lat=' . get_field('stop-lat-long', $p->ID)['lat'] . ' lng=' .
		get_field('stop-lat-long', $p->ID)['lng'] . ' ]'. $upCounter . '. ' .
		get_the_title($p->ID) . ' <small>(' . get_field('stopcode', $p->ID) .
		')</small>[/leaflet-marker]'); 
	$upCounter ++;
	/* Done inserting marker! */
	/*Adding co-ords to mapLine */
	$mapLine = $mapLine . get_field('stop-lat-long', $p->ID)['lat'] . ', ' . 
		get_field('stop-lat-long', $p->ID)['lng'] . '; ';
	?>

<?php endforeach; /*End looping though UP stops */ ?>
</ol></small>

<?php 
/* draw line on map */
echo do_shortcode('[leaflet-line latlngs="' . $mapLine . '"]');
?>
<hr>
<?php endif; /* END OF DISPLAY UP STOPS */

/* DISPLAY DOWN STOPS */ 
$downstops=get_field( 'down'); 
if ( $downstops ): ?>
<h4>Down Route:</h4>

<?php /*Make the map, set center as first stop's co-ords */
echo do_shortcode( '[leaflet-map height=300 lat=' . get_field('stop-lat-long', $downstops[0]->ID)['lat'] .
	' lng=' . get_field('stop-lat-long', $downstops[0]->ID)['lng'] . ' zoom=12]'); 
	
echo 'Schedule: ' . showTiming('B');
?>

<small><ol>
<?php $downCounter = 1; $mapLine = ''; ?>
<?php foreach( $downstops as $p ): // variable must NOT be called $post (IMPORTANT) ?>
	<li>
    	<a href="<?php echo get_permalink( $p->ID ); ?>">
      	<?php the_field( 'stopcode', $p->ID); ?> :
      	<?php echo get_the_title( $p->ID ); ?></a>
	</li>
	<?php /* Inserting marker of each stop into the map, with sequence number */
	echo do_shortcode( '[leaflet-marker lat=' . get_field('stop-lat-long', $p->ID)['lat'] .
		' lng=' . get_field('stop-lat-long', $p->ID)['lng'] . ' ]'. $downCounter . 
		'. ' . get_the_title($p->ID) . ' <small>(' . get_field('stopcode', $p->ID) . 
		')</small>[/leaflet-marker]'); 
	$downCounter ++;
	/* Done inserting marker! */ 
	/*Adding co-ords to mapLine */
	$mapLine = $mapLine . get_field('stop-lat-long', $p->ID)['lat'] . ', ' .
		get_field('stop-lat-long', $p->ID)['lng'] . '; ';
	?>
<?php endforeach; /*End looping though DOWN stops */ ?>
</ol></small>
<?php /* draw line on map */
echo do_shortcode('[leaflet-line latlngs="' . $mapLine . '"]');
?>
<hr>

<?php endif; /* END OF DISPLAY DOWN STOPS */

} /*END OF MAIN ROUTE CHECK */

function showRouteType($var) {
	if($var == 'double') return 'Different Up and Down route';
	else if($var == 'single') return 'Same Up and Down route';
	else if($var == 'circular') return 'Circular route';
	else return 'Not specified or error.';
}

function showTiming($Dir) {
	if( get_field('schedule_type') == 'frequency' ) {
		return 'First trip: <b>' . get_field($Dir.'_first_trip') . '</b>&nbsp;&nbsp;|&nbsp;&nbsp;' . 
			'Frequency: <b>' . get_field('frequency') . ' mins</b>&nbsp;&nbsp;|&nbsp;&nbsp;' . 
			'Last trip: <b>' . get_field($Dir.'_last_trip') .'</b>';
	}
	else if ( get_field('schedule_type') == 'timings' ) {
		return get_field($Dir.'_timings');
	}
	else return 'Error!';
}
