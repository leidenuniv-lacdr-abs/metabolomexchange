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

	require_once('header.php');
?>
	<header>
		<h2>Login</h2>
	</header>
	<article>		
		<form action="/mxadmin" method="POST">
			<input style="max-width: 250px; width: 75%"; type="text" name="mxu" value=""><br />
			<input style="max-width: 250px; width: 75%"; type="password" name="mxp" value=""><br />
			<input type="submit" value="login" style="width: 60px;">
		</form>
	</article>
<?php 

	require_once('footer.php');
