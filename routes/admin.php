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
	ksort($summary);
	print '<pre>'; print_r($summary); print '</pre>';
});

?>