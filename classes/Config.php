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

// errors
error_reporting(E_ALL);
ini_set("display_errors", 1);

/*********************
 *   CONFIGURATION   *
 *********************/
$config = array();

// mongodb
$config['mongodbHost'] = 'localhost'; 
$config['mongodbPort'] = 27017;
$config['mongodbName'] = 'metabolomexchange';
$config['mongodbUser'] = 'mongousername';
$config['mongodbPassword'] = 'mongopassword';
$config['mongodbAdminUser'] = 'mongoadminusername';
$config['mongodbAdminPassword'] = 'mongoadminpassword';

/**
 * When enabling Auth in Mongo, use the following commands in the Mongo shell to create the users:
 *
 * db.createUser({user: "mongousername",pwd: "mongopassword",roles: [{ role: "read", db: "metabolomexchange" }]})
 * db.createUser({user: "mongoadminusername",pwd: "mongoadminpassword",roles: [{ role: "readWrite", db: "metabolomexchange" }]})
 */

// providers
$config['providerJsonUrl'] = ''; // location where the providers are defined

?>