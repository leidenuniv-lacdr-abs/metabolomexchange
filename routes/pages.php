<?php
/**
 * Copyright 2014 Michael van Vliet (Leiden University), Thomas Hankemeier 
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

// GET::homepage
Flight::route('GET /', function(){	
	Flight::render('home.php', array(
		'providerDatasets'=>Flight::get('database')->providerDatasets(),
		'popularSearches'=>Flight::get('database')->popularSearches(),		
		'datasets' => Flight::get('database')->find('datasets', array('sortby'=>'date','sort'=>'desc','limit'=>3)),
		'providers' => Flight::get('providers')
	));
});

// GET::datasets
Flight::route('GET /dataset/', function(){
	Flight::render('datasets.php', array(
		'providers' => Flight::get('providers'),	
		'datasets' => Flight::get('database')->find('datasets', array_merge(Flight::get('params'), array('sortby'=>'date','sort'=>'desc')))
	));
});

// GET::dataset (one by provider/accession)
Flight::route('GET /dataset/provider/@provider_name/accession/@accession', function($provider_name, $accession){

	$dataset = array();

	$provider = current(Flight::get('database')->find('providers', array('filter'=>array("name"=>new MongoRegex("/$provider_name/i")),'sortby'=>'name','sort'=>'asc', 'limit'=>1)));

	if (isset($provider['uuid'])){
		$dataset = Flight::get('database')->find('datasets', array_merge(Flight::get('params'), array('filter'=>array('provider_uuid'=>$provider['uuid'], 'accession'=>$accession))));
	}

	Flight::render('dataset.php', array(
		'providers' => Flight::get('providers'),			
		'provider' => $provider,
		'dataset' => $dataset
	));
});

// GET::provider page
Flight::route('GET /provider/@provider_name', function($provider_name){

	$provider = current(Flight::get('database')->find('providers', array('filter'=>array("name"=>new MongoRegex("/$provider_name/i")),'sortby'=>'name','sort'=>'asc', 'limit'=>1)));
	
	Flight::render('provider.php', array(
		'provider_uuid' => $provider['uuid'],
		'providers' => Flight::get('providers'),
		'datasets' => Flight::get('database')->find('datasets', array_merge(Flight::get('params'), array('filter'=>array('provider_uuid'=>$provider['uuid']),'sortby'=>'date','sort'=>'desc')))
	));
});

// GET::search page
Flight::route('GET|POST /search', function(){

	$datasets = array();	
	$search = '';

	if (isset($_REQUEST['search'])){

		// do some filtering
		$search = addcslashes($_REQUEST['search'], '()');
		$search = str_replace(array('{','}','[',']'), array('','','',''), $search);
		$search = trim($search);

	}

	$searchRegex = new MongoRegex("/$search/i");
	$datasets = Flight::get('database')->find('datasets', array_merge(Flight::get('params'), array(
		'sortby'=>'date',
		'sort'=>'desc',
		'filter'=>array('$or' => array(
			array('json'=> $searchRegex)
		)))
	));

	// store search in database
	if ($search != '' && count($datasets) != 0){
		session_start();
		if (!isset($_SESSION['savedSearches'])) { $_SESSION['savedSearches'] = array(); }

		if (!isset($_SESSION['savedSearches'][$search])){
			Flight::get('database')->saveSearch($search);
			$_SESSION['savedSearches'][$search] = true;
		}
	}

	Flight::render('search.php', array(
		'search'=>stripslashes($search), 
		'datasets'=>$datasets,
		'recentSearches'=>(isset($_SESSION['savedSearches']) ? array_keys($_SESSION['savedSearches']) : array()),
		'providers' => Flight::get('providers')
	));
});

// GET::about us page
Flight::route('GET /about', function(){
	Flight::render('about.php');
});

// GET::documentation page
Flight::route('GET /documentation', function(){
	Flight::render('documentation.php');
});

// GET::stats page
Flight::route('GET /stats', function(){
	Flight::render('stats.php');
});

?>