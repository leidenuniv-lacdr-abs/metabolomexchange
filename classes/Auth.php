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

class Auth {

	/**
	 * Authenticate a user based on provided username and password
	 * @param  array $auth
	 * @return boolean
	 */
	public static function authenticate($auth) {

		if (!isset($auth['mxu']) || !isset($auth['mxp'])){
			return false;
		}
		
		$valid_passwords = array ("uname" => "upwd");
		$valid_users = array_keys($valid_passwords);

		$user = $auth['mxu'];
		$pass = $auth['mxp'];

		return (in_array($user, $valid_users)) && ($pass == $valid_passwords[$user]);
	}

}