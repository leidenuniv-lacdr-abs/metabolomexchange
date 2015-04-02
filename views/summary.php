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
?>
	<header>
		<h2>Summary</h2>
	</header>
<?php
	$summary = array();
	$datasets = Flight::get('database')->filterResponse(Flight::get('database')->find('datasets', Flight::get('params')));
	//print '<pre>'; print_r($datasets); print '</pre>';	

	function getPropertyCount(&$propertyCount, $properties, $parent = ''){

		foreach (array_keys($properties) as $propertyKey){
			$propertyValue = $properties[$propertyKey];
			$newParent = $parent.'|'.$propertyKey;
			
			if (is_integer($propertyKey)){ $newParent = $parent; }

			if (!is_array($propertyValue)){
				$newParent = $newParent.'|'. $properties[$propertyKey];
				if (!isset($propertyCount[$newParent])){ $propertyCount[$newParent] = 0; }
				$propertyCount[$newParent] = $propertyCount[$newParent] + 1;
			} else {
				$propertyCount = getPropertyCount($propertyCount, $propertyValue, $newParent);
			}
		}

		return $propertyCount;
	}


	//getPropertyCount(array_values($datasets));
	foreach ($datasets as $idx => $dataset){
		$summary = getPropertyCount($summary, $dataset);
	}

	ksort($summary);
	$columns = 4;
	print '<table cellspacing="15px">';
	
	$columnCount = 1;
	print '<th>occurrence</th>';
	print '<th>label (path)</th>';	
	print '<th>value</th>';

	foreach ($summary as $path => $summaryCount){
		$pathParts = explode('|', $path);
		$pathValue = array_pop($pathParts);
		print '<tr style="border-bottom: thin solid #cdcdcd;">';
		print '	<td valign="top">'.$summaryCount.'</td>';
		print '	<td nowrap valign="top">dataset '.implode(' > ', $pathParts).'</td>';		
		print '	<td valign="top">'.$pathValue.'</td>';		
		print '</tr>';
	}
	print '</table>';

	require_once('footer.php');
?>