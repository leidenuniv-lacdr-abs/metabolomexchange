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

require_once 'src/flight/Flight.php';

Flight::route('GET /', function(){ 
	Flight::render('home', array('datasets' => array(),'providers' => array()));
});

// GET::rss
Flight::route('GET /rss', function(){ 
	Flight::render('rss', array('datasets' => array(),'providers' => array())); 
});

// GET::rss by provider
Flight::route('GET /rss/by/@in/@for', function($in, $for){
	Flight::render('rss', array('in'=>$in, 'for'=>$for, 'providers'=>array(), 'datasets'=>array()));
});

// GET::rss by search
Flight::route('GET /rss/search/@for', function($for){
	Flight::render('rss', array('for'=>$for, 'providers'=>array(), 'datasets'=>array()));
});	

// GET::stats page
Flight::route('GET /stats', function(){ 
	Flight::render('stats');
});

Flight::route('GET /ns/dcat', function(){
	Flight::render('dcat', array('datasets'=>array(), 'providers' => array()));
});

// unsupported browser
Flight::route('GET /static', function(){ Flight::render('static'); });

// catch the rest
Flight::route('*', function(){
	Flight::json(array('error'=>'page not found!'));	
	exit();
});

// lift off
Flight::start();
?>
