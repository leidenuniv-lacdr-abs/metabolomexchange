<?php
/**
 * Copyright 2014 Michael van Vliet (Leiden University), Thomas Hankeijer 
 * (Leiden University)
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * 
 * 		http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 **/

/**
 * Login/home page
 */
Flight::route('GET|POST /mxadmin', function(){

	session_start();

	if (Auth::authenticate($_SESSION) || Auth::authenticate($_REQUEST)){
		
		if (Auth::authenticate($_REQUEST)){
			// we have a logged in user :)
			$_SESSION['mxu'] = $_REQUEST['mxu'];
			$_SESSION['mxp'] = $_REQUEST['mxp'];
		}

		Flight::render('mxadmin_home.php', array('loggedIn'=>Auth::authenticate($_SESSION)));
	} else {
		Flight::render('mxadmin_login.php');
	}
});


/**
 * Logout by clearing session data
 */
Flight::route('GET /mxadmin/logout', function(){
	session_start();
	$_SESSION['mxu'] = '';
	$_SESSION['mxp'] = '';
	Flight::render('mxadmin_home.php', array('loggedIn'=>Auth::authenticate($_SESSION)));
});


/**
 * Empty whole database
 */
Flight::route('GET /mxadmin/database/init', function(){

	session_start();
	if (Auth::authenticate($_SESSION)){ 
		Flight::get('database')->clearDatabase();
	} 

	Flight::redirect('/mxadmin');
	exit();
});


/**
 * Read providers.json and parse it
 */
Flight::route('GET /mxadmin/provider/init', function(){

	session_start();
	if (Auth::authenticate($_SESSION)){ 
		Flight::get('database')->initProviders();
	} 

	Flight::redirect('/mxadmin');
	exit();
});	


/**
 * Update datasets by parsing the provider feeds (ADMIN::FORCE)
 */
Flight::route('GET /mxadmin/provider/update', function(){

	session_start();
	if (Auth::authenticate($_SESSION)){ 
		Flight::get('database')->providerUpdateFeeds(true); // force an update
	}
	
	Flight::redirect('/mxadmin');
	exit();
});


/**
 * Used by external triggers to update the provider feeds
 */
Flight::route('GET /update/feeds', function(){
	Flight::get('database')->providerUpdateFeeds(); // can only be called every 15 min
	echo 'done!';
	exit();
});

/**
 * Summary of datasets
 */
Flight::route('GET /summary', function(){
	$summary = array();
	$datasets = Flight::get('database')->filterResponse(Flight::get('database')->find('datasets', Flight::get('params')));
	//print '<pre>'; print_r($datasets); print '</pre>';	

	function getPropertyCount(&$propertyCount, $properties, $parent = ''){

		foreach (array_keys($properties) as $propertyKey){
			$propertyValue = $properties[$propertyKey];
			$newParent = $parent.'_'.$propertyKey;
			
			if (is_integer($propertyKey)){ $newParent = $parent; }

			if (!is_array($propertyValue)){
				$newParent = $newParent.'_'. $properties[$propertyKey];
				if (!isset($propertyCount[$newParent])){ $propertyCount[$newParent] = 0; }
				$propertyCount[$newParent] = $propertyCount[$newParent] + 1;
			} else {
				$propertyCount = getPropertyCount($propertyCount, $propertyValue, $newParent);
			}
		}

		return $propertyCount;
	}


	//getPropertyCount(array_values($datasets));
	foreach ($datasets as $idx => $dataset){
		$summary = getPropertyCount($summary, $dataset); print '</pre>';
	}
	arsort($summary);
	print '<pre>'; print_r($summary); print '</pre>';
	ksort($summary);
	print '<pre>'; print_r($summary); print '</pre>';	
});

/**
 * DCAT endpoint
 */

Flight::route('GET /ns/dcat', function(){

	header("Content-Type:text/plain; charset=utf-8");

	// some handy functions
	function datasetUrlFromDataset($dataset){
		return urlencode($dataset['provider']['abbreviation']).":".urlencode($dataset['accession']);
	}	

	$mxDCAT = '';

	// add prefixes
	$mxDCAT .= "@prefix dc: <http://purl.org/dc/terms/> .\n";
	$mxDCAT .= "@prefix foaf: <http://xmlns.com/foaf/0.1/> .\n";
	$mxDCAT .= "@prefix dcat: <http://www.w3.org/ns/dcat#> .\n";
	$mxDCAT .= "@prefix xsd: <http://www.w3.org/2001/XMLSchema#> .\n";

	// add provider prefixes
	$providers = Flight::get('providers');
	foreach ($providers as $pIdx => $provider){
		$mxDCAT .= "@prefix ".$provider['abbreviation'].": <http://metabolomexchange.org/dataset/provider/".urlencode($provider['name'])."/accession/> .\n";
	}

	$mxDCAT .= "\n<http://metabolomexchange.org/ns/dcat>\n"; // add dcat url
	$mxDCAT .= "\tdc:modified \"".date("Y-m-d")."^^xsd:date\" ;\n"; // add date modified
	$mxDCAT .= "\tfoaf:homepage \"<http://metabolomexchange.org>\" ;\n"; // add homepage metabolomexchange

	// add datasets as reference list
	$mxDCAT .= "\tdcat:dataset ";
	$datasets = Flight::get('database')->filterResponse(Flight::get('database')->find('datasets', Flight::get('params')));
	$mxDCATDatasets = array();
	foreach ($datasets as $idx => $dataset){
		$mxDCATDatasets[] = datasetUrlFromDataset($dataset);
	}
	$mxDCAT .= implode(', ', array_values($mxDCATDatasets)) . " .\n";

	// add the individual datasets
	foreach ($datasets as $idx => $dataset){
		$mxDCAT .= "\n".datasetUrlFromDataset($dataset)."
			a dcat:Dataset ;
			dc:description \"\"\"".stripslashes(htmlentities($dataset['description']))."\"\"\" ;
			dc:identifier \"".str_replace('http://metabolomexchange.org/', '', datasetUrlFromDataset($dataset))."\" ;
			dc:issued \"".date("Y-m-d", $dataset['date'])."^^xsd:date\" ;
			dc:source <".$dataset['url']."> ;
			dc:title \"".stripslashes(htmlentities($dataset['title']))."\" .\n
		";
	}

	echo trim($mxDCAT);

});

?>