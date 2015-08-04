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

// GET::rss
Flight::route('GET /rss', function(){
	$providers = json_decode(file_get_contents('http://api.metabolomexchange.org/providers'), true);
	$datasets = json_decode(file_get_contents('http://api.metabolomexchange.org/datasets'), true);
	Flight::render('rss', array('providers' => $providers, 'datasets' => $datasets)); 
});

// GET::rss by provider
Flight::route('GET /rss/by/@in/@for', function($in, $for){
	
	// DISABLED, NOW RETURNS THE SAME AS A NORMAL SEARCH
	//Flight::render('rss', array('in'=>$in, 'for'=>$for, 'providers'=>array(), 'datasets'=>array()));

	// DO NOT REMOVE!!! REQUIRED TO KEEP OLD RSS FEED LINK WORKING
	if ($in == 'provider_uuid' && $for != ''){
		if (strpos($for, 'UJJIC0gTWV0Y') >= 1 ){ $for = 'mtbls';	}
		if (strpos($for, 'WV0YWJvbG9ta') >= 1 ){ $for = 'mwbs';		}
		if (strpos($for, '29sbSBNZXRhY') >= 1 ){ $for = 'golm';		}
		if (strpos($for, 'WV0YWJvbG9ta') >= 1 ){ $for = 'meryb';	}
	} // DO NOT REMOVE!!! END


	$providers = json_decode(file_get_contents('http://api.metabolomexchange.org/providers'), true);
	$datasets = json_decode(file_get_contents('http://api.metabolomexchange.org/datasets/' . $for), true);
	Flight::render('rss', array('for'=>$for, 'providers'=>$providers, 'datasets'=>$datasets) );
});

// GET::rss by search
Flight::route('GET /rss/search/@for', function($for){
	$providers = json_decode(file_get_contents('http://api.metabolomexchange.org/providers'), true);
	$datasets = json_decode(file_get_contents('http://api.metabolomexchange.org/datasets/' . $for), true);	
	Flight::render('rss', array('for'=>$for, 'providers'=>$providers, 'datasets'=>$datasets) );
});	

Flight::route('GET /ns/dcat', function(){
	$providers = json_decode(file_get_contents('http://api.metabolomexchange.org/providers'), true);
	$datasets = json_decode(file_get_contents('http://api.metabolomexchange.org/datasets'), true);	
	Flight::render('dcat', array('datasets'=>$datasets, 'providers' => $providers));
});

// unsupported browser
Flight::route('GET /static', function(){ Flight::render('static'); });

// GET::stats page
Flight::route('GET /stats', function(){ 
	Flight::redirect('/site/#/stats');
	exit();	
});

// catch the rest, send to homepage
Flight::route('*', function(){
	Flight::redirect('/site');
	exit();
});

// lift off
Flight::start();
?>
