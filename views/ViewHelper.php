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

class ViewHelper {

	public static function displayRSSFromSearchLink($search = ''){
		return $search != '' ? '<a target="_blank" href="/rss/search/'.$search.'"><img src="/img/feed-icon-28x28.png"></a>' : '';
	}

	public static function displaySearchBox($search = ''){

        $searchBoxHtml = '	<form action="/search" name="searchform" method="POST" class="form-wrapper cf">';
        $searchBoxHtml .= '		<input type="text" name="search" value="'.$search.'" placeholder="Search here..." required>';
        $searchBoxHtml .= '		<button type="submit">Search</button>';
        $searchBoxHtml .= '	</form>';
		return $searchBoxHtml;
	}

	public static function displayRecentSearches($resentSearches){

		$resentSearchesHtml = '';

		if (is_array($resentSearches) && count($resentSearches) >= 1){

			$arrRecentSearches = array();
			foreach ($resentSearches as $sIdx => $search){
				$arrRecentSearches[] = '<a href="/search/?search=' . stripcslashes($search) . '">' . stripcslashes($search) . '</a>';
			}

			if (!empty($arrRecentSearches)){
				$resentSearchesHtml .= '<div style="margin: 10px 0;" id="rs">';
				$resentSearchesHtml .= '	<b>recent searches</b>: ';
				$resentSearchesHtml .= 		implode(', ', $arrRecentSearches);
				$resentSearchesHtml .= '</div>';
			}
		}

		return $resentSearchesHtml;
	}	

	private static function displayMetaData($metadata = null){

		$metadataHtml = '<hr><small>';
		if ($metadata != null){
			foreach ($metadata as $meta_key => $meta_value){
				if ($meta_value){
					if (is_array($meta_value)){
						$metadataHtml .= '<strong>' . ucfirst(str_replace('_', ' ', $meta_key))  . '</strong>: ';
						foreach($meta_value as $value){
							 $metadataHtml .= '<a href="/search/?search=' . stripcslashes($value) . '">' . ucfirst(stripcslashes($value)) . '</a> ';
						}
						$metadataHtml .= '<br />';						
					} else {
						$metadataHtml .= '<strong>' . ucfirst(str_replace('_', ' ', $meta_key))  . '</strong>: <a href="/search/?search=' . $meta_value . '">' . ucfirst($meta_value) . '</a><br />';
					}
				}
			}
		}
		$metadataHtml .= '</small>';
		return $metadataHtml;

	}

	public static function displayDatasets($datasets, $providers, $options = array()){

		//init options
		if (!isset($options['details'])) { $options['details'] = 'full'; } // default to show full details

		$datasetsHtml = '';
		$datasetsHtml .= '<table>';

	    foreach( $datasets as $dataset ) {

	    	$provider = $providers[$dataset['provider_uuid']];

	    	if ($options['details'] == 'full'){
		    	$datasetsHtml .= '<tr><td>';	    		
		    	$datasetsHtml .= '<section>';
				$datasetsHtml .= '	<div style="width:250px; text-align:right; float: right;">';
				$datasetsHtml .= '		<a href="/provider/' . $provider['name'] . '"><b>' . $provider['name'] . '</b></a>';
				if (isset($dataset['date'])) {
					$datasetsHtml .= '		<br /><small><i>' . date("D, d M Y", $dataset['timestamp']) . '</i></small>';
				}
				$datasetsHtml .= '		<br /><small>' . $dataset['accession'] . '</small>';
				$datasetsHtml .= '	</div>';
				$datasetsHtml .= '	<h3 class="dark_green">';
				$datasetsHtml .=			$dataset['title'];
				$datasetsHtml .= '		<small><br />by <a href="/search/?search=' . $dataset['submitter'] . '">' . $dataset['submitter'] . '</a></small>';
				$datasetsHtml .= '	</h3>';
				$datasetsHtml .= '	<blockquote><i>&ldquo;' . $dataset['description'] . '&rdquo;</i></blockquote>';
				$datasetsHtml .= '	<small><a target="_blank" href="'. $dataset['url'] .'">'. $dataset['url'] .'</a></small>';

				$datasetsHtml .= self::displayMetaData($dataset['meta']);
				$datasetsHtml .= '	<hr>';
				$datasetsHtml .= '</section>';
		    	$datasetsHtml .= '</td></tr>';	    						
			} 

	    	if ($options['details'] == 'basic'){
		    	$datasetsHtml .= '<tr><td>';	    		
		    	$datasetsHtml .= '<section>';
				$datasetsHtml .= '	<div style="width:250px; text-align:right; float: right;">';
				$datasetsHtml .= '		<a href="/provider/' . $provider['name'] . '"><b>' . $provider['name'] . '</b></a><br />';
				if (isset($dataset['date'])) {
					$datasetsHtml .= '		<small><i>' . date("D, d M Y", $dataset['timestamp']) . '</i></small>';
				}
				$datasetsHtml .= '		<br /><small>' . $dataset['accession'] . '</small>';				
				$datasetsHtml .= '	</div>';
				$datasetsHtml .= '	<h3>';
				$datasetsHtml .= '		<a class="dark_green" href="/dataset/provider/' . $provider['name'] . '/accession/' . $dataset['accession'] . '">';				
				$datasetsHtml .=			$dataset['title'];
				$datasetsHtml .= '		</a> ';
				$datasetsHtml .= '		<small><br />by <a href="/search/?search=' . $dataset['submitter'] . '">' . $dataset['submitter'] . '</a></small>';
				$datasetsHtml .= '	</h3>';
				$datasetsHtml .= '	<p>';

				// limit the description
				$description = '';
				$linesToDisplay = 1;
				$descriptionLines = explode('.', $dataset['description']);
				$i = 0;
				while ($i < $linesToDisplay){
					if (isset($descriptionLines[$i]) && $descriptionLines[$i] != ''){
						$description .= $descriptionLines[$i] . '.';
					}
					$i++;
				}

				$datasetsHtml .= '	<blockquote><i>&ldquo;' . $description . '..&rdquo;</i></blockquote>';	

				$datasetsHtml .= '	<div style="width:100%; text-align: right;"><a href="/dataset/provider/' . $provider['name'] . '/accession/' . $dataset['accession'] . '"><small>...more</small></a></div>';				
				$datasetsHtml .= '	<hr>';
				$datasetsHtml .= '</section>';
		    	$datasetsHtml .= '</td></tr>';	    										
			}	

	    	if ($options['details'] == 'list'){
		    	$datasetsHtml .= '<tr>';	    							    		
		    	$datasetsHtml .= '	<td valign="top" width="100%"><small><a class="dark_green" href="/dataset/provider/' . $provider['name'] . '/accession/' . $dataset['accession'] . '">' . $dataset['title'] . '</a></small></td>';		    	
		    	$datasetsHtml .= '	<td valign="top" nowrap><small>' .$dataset['accession'] . '</small></td>';
		    	$datasetsHtml .= '	<td valign="top" nowrap><small><a href="/provider/' . $provider['name'] . '"><b>' . $provider['name'] . '</b></a></small></td>';		    	
		    	$datasetsHtml .= '</tr>';	    							    						
			}			
		}

		$datasetsHtml .= '</table>';				

		return $datasetsHtml;
	}
}
?>