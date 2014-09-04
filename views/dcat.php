<?php

/**
 * Copyright 2014 Michael van Vliet (Leiden University), Thomas Hankeijer 
 * (Leiden University)
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * 
 *          http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 **/

	header("Content-Type:text/plain; charset=utf-8");

	// some handy functions
	function datasetUrlFromDataset($dataset){
		return urlencode($dataset['provider']['abbreviation']).":".urlencode($dataset['accession']);
	}	

	$mxDCAT = '';

	// add prefixes
	$mxDCAT .= "@prefix dc: <http://purl.org/dc/terms/> .\n";
	$mxDCAT .= "@prefix foaf: <http://xmlns.com/foaf/0.1/> .\n";
	$mxDCAT .= "@prefix dcat: <http://www.w3.org/ns/dcat#> .\n";
	$mxDCAT .= "@prefix xsd: <http://www.w3.org/2001/XMLSchema#> .\n";

	// add provider prefixes
	foreach ($providers as $pIdx => $provider){
		$mxDCAT .= "@prefix ".$provider['abbreviation'].": <http://".$_SERVER['HTTP_HOST']."/dataset/provider/".urlencode($provider['name'])."/accession/> .\n";
	}

	$mxDCAT .= "\n<http://".$_SERVER['HTTP_HOST']."/ns/dcat>\n"; // add dcat url
	$mxDCAT .= "\tdc:modified \"".date("Y-m-d")."^^xsd:date\" ;\n"; // add date modified
	$mxDCAT .= "\tfoaf:homepage \"<http://".$_SERVER['HTTP_HOST'].">\" ;\n"; // add homepage metabolomexchange

	// add datasets as reference list
	$mxDCAT .= "\tdcat:dataset ";
	$mxDCATDatasets = array();
	foreach ($datasets as $idx => $dataset){
		$mxDCATDatasets[] = datasetUrlFromDataset($dataset);
	}
	$mxDCAT .= implode(', ', array_values($mxDCATDatasets)) . " .\n";

	// add the individual datasets
	foreach ($datasets as $idx => $dataset){
		$mxDCAT .= "\n".datasetUrlFromDataset($dataset)."
			a dcat:Dataset ;
			dc:description \"\"\"".stripslashes($dataset['description'])."\"\"\" ;
			dc:identifier \"".str_replace("http://".$_SERVER['HTTP_HOST']."/", '', datasetUrlFromDataset($dataset))."\" ;
			dc:issued \"".date("Y-m-d", $dataset['date'])."^^xsd:date\" ;
			dc:landingPage <".$dataset['url']."> ;
			dc:source \"".stripslashes($dataset['provider']['name'])."\" ;
			foaf:name \"".stripslashes($dataset['submitter'])."\" ;
			dc:title \"".stripslashes($dataset['title'])."\" .\n
		";
	}

	echo trim($mxDCAT);

 ?>