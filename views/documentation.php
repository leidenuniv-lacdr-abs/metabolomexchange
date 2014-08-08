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

	<h2>API Documentation</h2>

<?php

	// cache dir
	$cacheDir = 'cache';
	if (!file_exists($cacheDir)){ mkdir($cacheDir, 0755, true);	}

	// cache file
	$cachedVersion = $cacheDir . '/documentation.' . date("Ymd") . '.cache'; // refresh ones a day

	// read or produce cached version of the documentation
	if (is_readable($cachedVersion)){
		echo file_get_contents($cachedVersion); // return cached version
	} else {

		$url = 'http://'.$_SERVER['HTTP_HOST'];

		$documentation = array(
			array('individuals'=>
				array(
					'description'=>'
						This api provides read-only access to all. Below you find a list with available calls to the API. In addition to the default 
						arguments there are additional arguments you can use to fine-tune the results:
						<br /><br />
						<b>limit</b>: limits the results accordingly. Please note! If you are combining it with sort/sortby it will first sort, then limit!<br />
						<b>sortby</b>: sorts the results by the field provided accordingly. This is limited to the top-level fields like title, description, created etc.<br />
						<b>sort</b>: by default sorting is done ascending, setting this to desc will reverse the sort order.<br />
					',
					'calls'=> array(
						array(
							'title'=>'GET :: /api/dataset',
							'description'=>'Retrieve a list of the available datasets',
							'examples'=> array(
								array(
									'title'=>'All datasets',
									'command' => '/api/dataset/?limit=3&sort=asc'
								)
							)
						),
						array(
							'title'=>'GET :: /api/dataset/provider/@provider_name',
							'description'=>'Retrieve a list of the available datasets by a single provider',
							'examples'=> array(
								array(
									'title'=>'All datasets of a provider',
									'command' => '/api/dataset/provider/Golm/?limit=4&sortby=created&sort=desc'
								)
							)
						),						
						array(
							'title'=>'GET :: /api/dataset/provider/@provider_name/accession/@accession',
							'description'=>'Retrieve a dataset with @accession: MTBLS3 by data provider with @provider_name: EBI - MetaboLights' ,
							'examples'=> array(
								array(
									'title'=>'All providers',
									'command'=> '/api/dataset/provider/MetaboLights/accession/MTBLS3/'
								)
							)
						),						
						array(
							'title'=>'GET :: /api/dataset/search/@in/@for',
							'description'=>'Find all datasets where @for is found in @in. The @in value is case insensitive.',
							'examples'=> array(
								array(
									'title'=>'All datasets submitted by Reza (max 2)',
									'command' => '/api/dataset/search/submitter/Reza/?limit=2'
								),
								array(
									'title'=>'All datasets with lipid in the description (max 2)',
									'command' => '/api/dataset/search/description/serum/?limit=4&sortby=created&sort=desc'
								)
							)
						),
						array(
							'title'=>'GET :: /api/provider',
							'description'=>'Retrieve a list of the available data providers',
							'examples'=> array(
								array(
									'title'=>'All providers',
									'command'=>'/api/provider/?limit=3&sort=asc'
								)
							)
						),
						array(
							'title'=>'GET :: /api/provider/@name',
							'description'=>'Retrieve a single data providers',
							'examples'=> array(
								array(
									'title'=>'All providers',
									'command'=>'/api/provider/MetaboLights'
								)
							)
						)
					)
				)
			)
		);

		$index = '';
		$chapters = '';
		foreach ($documentation as $documentationGroups){
			foreach ($documentationGroups as $documentationGroupName => $documentationGroup){

				$index .= '<h3>' . ucfirst($documentationGroupName) . '</h3>';
				
				$chapters .= '<h3>' . ucfirst($documentationGroupName) . '</h3>';
				$chapters .= '<p>' . $documentationGroup['description'] . '</p>';
				foreach ($documentationGroup['calls'] as $idx => $call){

					$index .= '<b><a class="index" href="#'.base64_encode($call['title']).'">' . $call['title'] . '</a></b><br />';

					$chapters .= '<hr>';
					$chapters .= '<a id="'.base64_encode($call['title']).'">';
					$chapters .= '<h4>' . ucfirst($call['title']) . '</h4>';
					$chapters .= '<p>' . $call['description'] . '</p>';

					$exampleIdx = 0;
					foreach ($call['examples'] as $example){

						$exampleIdx++;
						$preTagName = 'json_'.$documentationGroupName.'_'.$idx.'_'.$exampleIdx;

						// $index .= ' - <a class="index" href="#'.base64_encode($example['command']).'">' . $example['command'] . '</a><br />';

						$chapters .= '<a id="'.base64_encode($example['command']).'">';
						$chapters .= '<small>';
						$chapters .=	$example['title'] . '<br />';
						$chapters .= '	cURL: <em style="color:grey;" >curl -i <a target="_blank" href="' . $url . $example['command'] . '">' . $url . urldecode($example['command']) . '</a></em><br />';
						$chapters .= '	<pre id="'.$preTagName.'"></pre>';
						$chapters .= '	<script>document.getElementById("'.$preTagName.'").innerHTML = JSON.stringify('.file_get_contents($url . $example['command']).', undefined, 2);</script>';
						$chapters .= '</small>';					
					}
				}
			}
		}

		$pageHTML = "" . $index . "<br /><hr><br />" . $chapters;

		// cache documentation!
		try {
			file_put_contents($cachedVersion, $pageHTML);
		} catch (Exception $e) {
			// was unable to cache this documents
		}


		echo $pageHTML;
	}

?>

<?php

	require_once('footer.php');