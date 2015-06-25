<?php

// Take the forest watch csv, and generate a GeoJSON file that you can use in other places,
// including: githin.com which processes it automatically,
// or http://geojson.io/ where you can just copy/paste the GeoJSON text file and get them
// on a map.

$csv_url = "https://raw.githubusercontent.com/Sinar/sinar.myreps/master/docs/forest-watch.csv";

$csv = file_get_contents($csv_url);
$rows = explode("\n", $csv);

$featurecollection = array(
	 "type" => "FeatureCollection",
	 "features" => array(),
);

// http://geojson.io/ compatible styling
$style = array(
	'marker-color' => '#ff2615',
	'marker-symbol' => 'circle',
);

foreach ($rows as $row) {
	@list($latlng, $description, $url) = str_getcsv($row,",");
	if (empty($url) && empty($description)) {
		continue;
	}
	list($lat, $lng) = explode(",",$latlng);
	$feature = array(
		'type' => 'Feature',
		'geometry' => array(
			'type'        => 'Point',
			'coordinates' => array($lng*1.0, $lat*1.0)
		),
		'properties' => array(
			'description' => $description,
		),
	);
	$feature['properties'] = array_merge($feature['properties'], $style);

	$featurecollection['features'][] = $feature;
}
print json_encode($featurecollection);
