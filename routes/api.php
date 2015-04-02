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

// GET::dataset (all)
Flight::route('GET /api/dataset', function(){
	Flight::json(Flight::get('database')->filterResponse(Flight::get('database')->find('datasets', Flight::get('params'))));
});

// GET::dataset (all by provider)
Flight::route('GET /api/dataset/provider/@provider_name', function($provider_name){

	$provider = current(Flight::get('database')->find('providers', array('filter'=>array("name"=>new MongoRegex("/$provider_name/i")),'sortby'=>'name','sort'=>'asc', 'limit'=>1)));
	$datasets = Flight::get('database')->find('datasets', array_merge(Flight::get('params'), array('filter'=>array('provider_uuid'=>$provider['uuid']))));

	Flight::json(Flight::get('database')->filterResponse($datasets));
});

// GET::dataset (one by provider/accession)
Flight::route('GET /api/dataset/provider/@provider_name/accession/@accession', function($provider_name, $accession){

	$dataset = array();

	$provider = current(Flight::get('database')->find('providers', array('filter'=>array("name"=>new MongoRegex("/$provider_name/i")),'sortby'=>'name','sort'=>'asc', 'limit'=>1)));
	if (isset($provider['uuid'])){
		$dataset = current(Flight::get('database')->find('datasets', array('filter'=>array('provider_uuid'=>$provider['uuid'], 'accession'=>$accession))));
	} 

	Flight::json(Flight::get('database')->filterResponse($dataset));
});

// GET::dataset (search)
Flight::route('GET /api/dataset/search/@in/@for', function($in, $for){

	// look for $for (case insensitive) in the field $in
	$params = Flight::get('params');
	$params['filter'][$in] = new MongoRegex("/$for/i");

	Flight::json(Flight::get('database')->filterResponse(Flight::get('database')->find('datasets', $params)));
});

// GET::provider (all)
Flight::route('GET /api/provider', function(){
	Flight::json(Flight::get('database')->filterResponse(Flight::get('database')->find('providers', Flight::get('params'))));
});

// GET::provider (one)
Flight::route('GET /api/provider/@provider_name', function($provider_name){
	Flight::json(current(Flight::get('database')->filterResponse(Flight::get('database')->find('providers', array('filter'=>array("name"=>new MongoRegex("/$provider_name/i")),'sortby'=>'name','sort'=>'asc', 'limit'=>1)))));
});

?>