<?
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

header("Access-Control-Allow-Origin: *"); // required for all clients to connect

// include config
require_once 'classes/Config.php';

// add some logic
require_once 'classes/flight/Flight.php';
require_once 'classes/Auth.php';
require_once 'classes/Database.php';

// init database connection
Flight::set('database', new Database($config));

// prep $providers variable as it is used on every page
Flight::set('providers', Flight::get('database')->jumpstartProviders());

// filter paramaters
Flight::set('params', Flight::get('database')->filterParameters($_REQUEST));

// define routes
require_once('routes/pages.php');
require_once('routes/rss.php');
require_once('routes/admin.php');
require_once('routes/api.php');
require_once('routes/errors.php');

// lift off
Flight::start();
?>
