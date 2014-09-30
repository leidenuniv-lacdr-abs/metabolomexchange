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

class Database {


    /** @vars */
    private $db;
    private $dbAdmin;
    private $config;	


    /**
     * Inits connection to the database for both user and admin
     * @param array $config
     */
	function __construct($config) {

		$this->config = $config;

		// init connection, read-only
		$connectionUrl	= 'mongodb://';
		if (isset($this->config['mongodbUser']) && $this->config['mongodbUser'] != ''){ // add username and password when set
			$connectionUrl	.= $this->config['mongodbUser'].':'.$this->config['mongodbPassword'].'@';
		}
		$connectionUrl	.= $this->config['mongodbHost'].':'.$this->config['mongodbPort'].'/'.$this->config['mongodbName'];
		$connection 	= new MongoClient($connectionUrl);
		$dbName			= $this->config['mongodbName'];
		$this->db		= $connection->$dbName;

		// init connection, read-write (admin)
		$connectionAdminUrl	= 'mongodb://';
		if (isset($this->config['mongodbAdminUser']) && $this->config['mongodbAdminUser'] != ''){ // add username and password when set
			$connectionAdminUrl	.= $this->config['mongodbAdminUser'].':'.$this->config['mongodbAdminPassword'].'@';
		}
		$connectionAdminUrl	.= $this->config['mongodbHost'].':'.$this->config['mongodbPort'].'/'.$this->config['mongodbName'];
		$connectionAdmin 	= new MongoClient($connectionAdminUrl);
		$dbName				= $this->config['mongodbName'];		
		$this->dbAdmin		= $connectionAdmin->$dbName;			
    }


	/**
	 * retrieve all collection documents from the database
	 * @param  string $collection
	 * @return array
	 */
	public function getAll($collection) {

		$collection = strval($collection); // cast to string
		$response = array();

		// apply collection filter
		$cursor = $this->db->$collection->find();
		foreach ($cursor as $dIdx => $document){ $response[] = $document; }

		return $response;
	}


	/**
	 * retrieve collection documents based on parameters
	 * @param  string $collection
	 * @param  array  $params
	 * @return array
	 */
	public function find($collection, $params = array()) {

		$response = array();

		try {

			$params = $this->filterParameters($params); // make sure all is filtered correctly

			$collection = strval($collection); // cast to string

			// apply collection filter
			$cursor = $this->db->$collection->find($params['filter']);
			
			// sort results
			$sort = ($params['sort'] == 'asc') ? 1 : -1;
			$cursor->sort(array($params['sortby'] => $sort)); // sort by ? descending

			// limit results
			if ($params['limit'] != 0){ $cursor->limit( (int) $params['limit'] ); }

			foreach ($cursor as $dIdx => $document){ 
				$response[] = $document; 
			}
		} catch (Exception $e){
			// sometimes searches can produce errors, then we return an empty result set
		}

		return $response;
	}	

	/**
	 * save searches for popular searches
	 * @param  string $search
	 * @return boolean
	 */
	public function saveSearch($search){

		// keep track of the month
		$month = date("Y-m");

		$searchEntry = current($this->find('searches', array('filter'=>array("search"=>$search,"month"=>$month))));
		$searchCount = isset($searchEntry['count']) ? (int)$searchEntry['count'] : 0;
		if ($searchCount > 0){
			$searchCount++;
			$this->dbAdmin->searches->update(array("search"=>$search,"month"=>$month), array('$set' => array("count"=>$searchCount)));
		} else {
			$this->dbAdmin->searches->insert(array("search"=>$search,"month"=>$month, "count"=>1, "active"=>1));
		}

		return true;
	}

	/**
	 * popular search/keywords used
	 * @return array list of the ($limit) most popular words
	 */
	public function popularSearches($limit = 25){
		$popularSearches = array();

		foreach ($this->find('searches', array('filter'=>array("month"=>date("Y-m")),'sortby'=>'count','sort'=>'desc','limit'=>$limit)) as $psIdx => $popularSearch){
			$popularSearches[$popularSearch['search']] = $popularSearch['count'];
		}
		
		return $popularSearches;
	}


	/**
	 * returns dataset count by provider
	 * @return array
	 */
	public function providerDatasets(){

		$results = array();

		// TODO: Improve performance, this will become slow when the database grows
		foreach ($this->find('datasets') as $dIdx => $dataset){
			if (!isset($results[$dataset['provider_uuid']])) { $results[$dataset['provider_uuid']] = 0; }
			$results[$dataset['provider_uuid']]++;
		}

		arsort($results); // with most datasets on top
		return $results;
	}


    /**
     * procedure to update provider datasets from feeds
     * @param  boolean $force
     * @return boolean
     */
    public function providerUpdateFeeds($force = false){

    	foreach ($this->getAll('providers') as $idx => $provider){

    		$pIdx = $provider['uuid'];

    		if (isset($provider['feed_url'])){
				
				if (!isset($provider['crc'])){ $provider['crc'] = 0; }
				if (!isset($provider['feed_update'])){ $provider['feed_update'] = 0; }

    			if ($force || ((time() - (int)$provider['feed_update']) > 15*60)){ // reload feed when older then 15 minutes    				
										
					try { // updated feed $provider['feed_url']
						$providerFeed = file_get_contents($provider['feed_url']);
						$providerCrc = crc32($providerFeed);

						if($provider['crc'] != $providerCrc){ // check for changes

							// parse feed
							$providerFeedData = json_decode($providerFeed, true);

							if (count($providerFeedData) >= 1){ // only run when at least 1 dataset is found

								$providerAccessions = array();

								// parse it into individual dataset documents
								foreach ($providerFeedData as $dIdx => $dataset){

									// keep track of all provider accessions
									$providerAccessions[] = $dataset['accession'];

									$date = ''; // convert date to timestamp
									if (isset($dataset['date']) && $dataset['date'] != ''){
										list($year, $month, $day) = explode('-', $dataset['date']);
										$date = mktime(0, 0, 0, $month, $day, $year);
									}

									// build dataset
									$dataset['active'] = 1;
									$dataset['provider_uuid'] = $pIdx;
									$dataset['uuid'] = base64_encode('ds'.$dataset['provider_uuid'].'_'.$dataset['accession']);																		
									$dataset['json'] = json_encode($dataset);
									$dataset['date'] = (string) $date;	// force to string, older versions of the php-mongo 

									// delete existing (if there)
									$this->dbAdmin->datasets->remove(array("accession" => $dataset['accession'],"provider_uuid" => $pIdx));

									// insert new (or updated version)
									$this->dbAdmin->datasets->insert($dataset);
								}

								// de-activate all datasets not present in feed
								foreach ($this->find('datasets', array('filter'=>array('provider_uuid'=>$pIdx))) as $ds){
									if (!in_array($ds['accession'], $providerAccessions)){
										$this->dbAdmin->datasets->update(array("uuid" => $ds['uuid'],"accession" => $ds['accession'],"provider_uuid" => $pIdx), array('$set' => array("active"=>0)));
									}
								}							
							}

					    	// mark feed as updated
					    	$this->dbAdmin->providers->update(array("uuid" => $provider['uuid']), array('$set' => array("feed_update" => time(), "crc"=>$providerCrc)));
					    }
					} catch (Exception $e){
						
						//TODO: add this $e->getMessage() to error_log
						//echo $e->getMessage();
						
						$this->dbAdmin->providers->update(array("uuid" => $provider['uuid']), array('$set' => array("feed_update" => time()))); // try again in x minutes
						return false;
					}
    			}
    		}
    	}

    	return true;
    }


    /**
     * filtering request parameters
     * @param  array $params request (POST/GET) parameters
     * @return array $filteredParameters filtered request (POST/GET) parameters
     */
    public function filterParameters($params){

		$acceptedParams = array('filter'=>array(), 'limit'=>0, 'sort'=>'asc', 'sortby'=>'date', 'uuid'=>null);	    	
    	
    	// init response
    	$filteredParameters = array();
    	
    	foreach ($acceptedParams as $acceptedParam => $defaultValue){
    		if (isset($params[$acceptedParam])){
    			$filteredParameters[$acceptedParam] = $params[$acceptedParam];
    		} else {
    			$filteredParameters[$acceptedParam] = $defaultValue;
    		}
    	}

        //active filter is required, if not set we only show the active datasets
        if (!isset($filteredParameters['filter']['active'])){
            $filteredParameters['filter']['active'] = 1;
        }

    	return $filteredParameters;
    }


    /**
     * filtering output from database. Not all document data should be returned to the user
     * @param  array $response document data returned from database
     * @return array $filteredResponse document data returned from database without hidden fields
     */
    public function filterResponse($response){

		$privateFields = array('_id', 'active', 'feed_update', 'crc', 'json', 'uuid');    	

    	// init response
    	$filteredResponse = array();

    	foreach ($response as $respKey => $respVal){

    		if (!is_object($respVal) && !in_array(strval($respKey), $privateFields)){ 

    			if (is_array($respVal)){
					$filteredSubResponse = array();
    				foreach ($respVal as $respSubKey => $respSubVal){
						if (!is_object($respSubVal) && !in_array(strval($respSubKey), $privateFields)){     					
                            $filteredSubResponse = array_merge($filteredSubResponse, $this->enhanceKeyValue($respSubKey, $respSubVal));
						}
    				}
	    			if (!empty($filteredSubResponse)){
    					$filteredResponse["$respKey"] = $filteredSubResponse;	
    				}
    			} else {
                    $filteredResponse = array_merge($filteredResponse, $this->enhanceKeyValue($respKey, $respVal));
    			}
    		}
    	}

    	return $filteredResponse;
    }


    /**
     * enhances the database documents with additional or more user friendly data
     * @param  string $respKey label of variable
     * @param  string/array $respVal variable data, can be a string or an array
     * @return array $respArray enhanced variable
     */
    private function enhanceKeyValue($respKey, $respVal){

        $respArray = array();

        switch($respKey){
            
            case 'provider_uuid':   $respArray = array("provider" => current($this->filterResponse($this->find('providers', array('filter'=>array('uuid'=>$respVal))))));
                                    break;
            
            default:                $respArray = array("$respKey" => $respVal);
        }

        return $respArray;
    }    


    /**
     * parse provider.json and update them accordingly
     * @return boolean
     */
	public function initProviders(){

		// read the provider json
		$jsonUrl = null;
		if (!isset($this->config['providerJsonUrl']) || $this->config['providerJsonUrl'] != ''){ // see if a specific url is provided to the provider json
			$jsonUrl = $this->config['providerJsonUrl'];
		} else { // if not, we use the default
			$jsonUrl = 'providers.json';
		}
		$providers = json_decode(file_get_contents($jsonUrl));

		if (count($providers)){
			foreach($providers as $pIdx => $provider){

				$providerArray = array('active'=>1,'abbreviation'=>$provider->abbreviation ,'url'=>$provider->url,'name'=>$provider->name,'about'=>$provider->about,'feed_url'=>$provider->feed_url);

				// see if it is already in there!
				$existingProvider = $this->find('providers', array('filter'=>array("name"=>$provider->name)));

				if (isset($existingProvider['uuid'])){ // update
					$this->dbAdmin->providers->update(array("uuid"=>$existingProvider['uuid']),array('$set'=>$providerArray));
				} else { // create
					$this->createProvider($providerArray);
				}
			}
		}

		return true;
	}


	/**
	 * creates a new provider
	 * @param  array $args provider info
	 * @return boolean       returns true on succes and false when the provider couldn't be created
	 */
    public function createProvider($args){

    	// get collection
		$providers = $this->db->providers;    	
		
		$provider_name	= strval($args['name']);

		// generate uuid
		$uuid = base64_encode($provider_name);

    	if (	$providers->find( array('uuid'=> $uuid) )->count() > 0 ||
				$providers->find( array('name'=> $provider_name) )->count() > 0
			) 
    	{
    		return false;
		}

		// save user
		$provider = $args;
		$provider['uuid'] = $uuid;
		$provider['name'] = $provider_name;

		return ($this->dbAdmin->providers->insert($provider)) ? true : false;
    }	


    /**
     * setup initial providers
     * @return array array with providers
     */
	public function jumpstartProviders(){

		$providers = array();

		// see if we have any providers in the database
		$providersFromDatabase = $this->find('providers');
		if (!$providersFromDatabase || count($providersFromDatabase) <= 0){
			$this->initProviders(); // try to load the providers
			$this->providerUpdateFeeds(); // try to populater the datasets from the providers
		}

		foreach($this->find('providers') as $pIdx => $p){ $providers[$p['uuid']] = $p; }

		return $providers;
	}


	/**
	 * clears db
	 * @return boolean true/false based on success
	 */
	public function clearDatabase(){
		// clear old data
		$this->dbAdmin->providers->drop();
		$this->dbAdmin->datasets->drop();

		return true;
	}

}