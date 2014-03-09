<?php
/**
 *
 * Silverclean Pro WordPress Theme by Iceable Themes | http://www.iceablethemes.com
 *
 * Copyright 2013 Mathieu Sarrasin - Iceable Media
 *
 * Function: Google Maps API v3 Integration (Shortcode)
 *
 */

/* ---------------- Maps Shortcode ---------------- */

function icefit_maps_shortcode($atts) {
	extract( shortcode_atts( array( 'title' => '', 'col' => 'full-width', 'height' => '200',
									'type' => 'ROADMAP', 'zoom' => '15', 'address' => '',
									'lat' => '', 'lng' => '', 'controls' => 'off' ), $atts ) );

	// Validate basic parameters or set default value
	$col_options = array('one-fourth', 'one-third', 'one-half', 'two-thirds', 'three-fourths', 'full-width');
	if (!in_array($col, $col_options)) $col = 'full-width';
	if (!is_numeric($height)) $height = 200;
	$type_options = array('ROADMAP', 'SATELLITE', 'HYBRID', 'TERRAIN');
	if (!in_array($type, $type_options)) $col = 'ROADMAP';
	if (!is_numeric($zoom)) $zoom = 15;
	if ($controls != 'on' && $controls != 'off') $controls = "true";
	if ($controls == 'on') $controls = "true";
	if ($controls == 'off') $controls = "false";
	
	if($controls == "true"):
		//Only show controls in one-half or wider columns
		$half_or_wider = array('one-half', 'two-thirds', 'three-fourths', 'full-width');
		$controls = (in_array($col, $half_or_wider)) ? "true" : "false";
	endif;

	//Only show pan control if height is 200 or higher
	$pan = ($height >= 200) ? $controls : "false"; 

	// Set an unique ID by counting how many maps are called on the same page
	$GLOBALS['map_id'] = (!isset($GLOBALS['map_id'])) ? 1 : $GLOBALS['map_id']+1;
	$map_id = $GLOBALS['map_id'];

	$output = '<div class="maps-wrap '.$col.'">';
	if ($title) $output .= '<h3>'.$title.'</h3>';
	$output .= '<div id="map-'.$map_id.'" style="height: '.$height.'px;"></div>';

	// Only include the API once
	if (1 == $map_id) $output .= '<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>';

	// GPS coordinates overrides the address parameter
	// If GPS coordinates are set, initialize the map
	if ('' != $lat && '' != $lng):
	
	$output .= '<script type="text/javascript">
initialize'.$map_id.'();
function initialize'.$map_id.'(){  
	var mapOptions={	zoom:'.$zoom.',
						mapTypeId:google.maps.MapTypeId.'.$type.',
					    center: new google.maps.LatLng('.$lat.', '.$lng.'),
						panControl:'.$pan.',
						zoomControl:'.$controls.',
						mapTypeControl:'.$controls.',
						scaleControl:'.$controls.',
						streetViewControl:'.$controls.',
						overviewMapControl:false};
	var map = new google.maps.Map(document.getElementById("map-'.$map_id.'"), mapOptions);
	setTimeout(function(){
						var marker=new google.maps.Marker({
							map:map,
							position:new google.maps.LatLng('.$lat.', '.$lng.'),
							title:"Home",
							animation:google.maps.Animation.DROP});
						},1000);
}
</script>';
	
	// Else (if GPS coordinates are not set), geocode the given address
	elseif ($address):
	// Initialize this maps with the given parameters
	$output .= '<script type="text/javascript">
initialize'.$map_id.'();
function initialize'.$map_id.'(){
	geocoder=new google.maps.Geocoder();
	var address="'.$address.'";
	geocoder.geocode(
			{\'address\':address},
			function(results,status){
				if(status==google.maps.GeocoderStatus.OK){
					var mapOptions={
						zoom:'.$zoom.',
						mapTypeId:google.maps.MapTypeId.'.$type.',
						panControl:'.$pan.',
						zoomControl:'.$controls.',
						mapTypeControl:'.$controls.',
						scaleControl:'.$controls.',
						streetViewControl:'.$controls.',
						overviewMapControl:false};
					var map=new google.maps.Map(document.getElementById("map-'.$map_id.'"),mapOptions);
					map.setCenter(results[0].geometry.location);
					setTimeout(function(){
						var marker=new google.maps.Marker({
							map:map,
							position:results[0].geometry.location,
							title:"Home",
							animation:google.maps.Animation.DROP});
						},1000);
				}else{
					var css=document.createElement("style");
					css.type="text/css";
					css.innerHTML="#map-'.$map_id.'{display:none;}";
					document.body.appendChild(css);
				}
			}
	);
}
</script>';
	endif;

		$output .=  '</div>';
	return $output;
}
add_shortcode('maps', 'icefit_maps_shortcode');



?>