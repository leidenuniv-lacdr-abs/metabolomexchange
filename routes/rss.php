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

// GET::rss
Flight::route('GET /rss', function(){
	Flight::render('rss.php', array(
		'datasets' => Flight::get('database')->find('datasets', array_merge(Flight::get('params'), array('sortby'=>'date','sort'=>'desc'))),
		'providers' => Flight::get('providers')
	));
});

// GET::rss by provider
Flight::route('GET /rss/by/@in/@for', function($in, $for){
	Flight::render('rss.php', array(
		'in' => $in,
		'for' => $for,
		'providers' => Flight::get('providers'),		
		'datasets' => Flight::get('database')->find('datasets', array_merge(Flight::get('params'), array('filter'=>array("$in"=>new MongoRegex("/$for/i")),'sortby'=>'date','sort'=>'desc')))
	));
});

// GET::rss by search
Flight::route('GET /rss/search/@for', function($for){

	$searchRegex = new MongoRegex("/$for/i");
	$datasets = Flight::get('database')->find('datasets', array_merge(Flight::get('params'), array('sortby'=>'date','sort'=>'desc','filter'=>array('$or' => array(array('json'=> $searchRegex))))));		

	Flight::render('rss.php', array(
		'for' => $for,
		'providers' => Flight::get('providers'),		
		'datasets' => $datasets
	));
});	
?>