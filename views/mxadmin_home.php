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

	require_once('header.php');
	
	if (!$loggedIn){
		echo 'Please login first!<br />';
		echo '<a href="/mxadmin">login</a>';
	} else {

			echo '<div style="float:right;"><a href="/mxadmin/logout">logout</a></div>';

			$providers = Flight::get('database')->find('providers');
			$datasets = Flight::get('database')->find('datasets');
		?>

			<br />Number of providers: <?=count($providers);?>
			<br />Number of databasets: <?=count($datasets);?>

			<h2>Admin</h2>
			<h3>Options</h3>
			<ul>
				<li><a href="/mxadmin/provider/update">Update provider feeds</a></li>
			</ul>

			<h3>Be careful!</h3>
			<ul>
				<li><a href="/mxadmin/provider/init">Init providers</a></li>				
				<li><a href="/mxadmin/database/init"><font color="red">Clear database</font></a></li>
			</ul>


		<?php

	}

	require_once('footer.php');
?>