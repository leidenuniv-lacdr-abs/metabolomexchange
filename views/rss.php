<?php

/**
 * Copyright 2014 Michael van Vliet (Leiden University), Thomas Hankemeier 
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

	/** 
	 * show latest datasets as RSS
	 */
	header("Content-Type: application/rss+xml; charset=utf-8");

    $providerDetails = array();
    foreach ($providers as $pIdx => $provider){
        $providerDetails[$provider['shortname']] = $provider;
    }

    // DATASETS ARE NOW ALWAYS SORTED BY TIMESTAMP, NO NEED TO RE_SORT
    // $dates = array();
    // foreach ($datasets as $key => $row) {
    //     $dates[$key]  = $row['timestamp'];
    // }

    // array_multisort($dates, SORT_DESC, $datasets);    

	$rss = "<?xml version=\"1.0\" encoding=\"utf-8\"?>";
    $rss .= "\n<rss version=\"2.0\" xmlns:atom=\"http://www.w3.org/2005/Atom\">";
    $rss .= "\n\t<channel>";
    $rss .= "\n\t\t<ttl>60</ttl>"; // refresh feed each 60min
    $rss .= "\n\t\t<image>";
    $rss .= "\n\t\t\t<url>http://".$_SERVER['HTTP_HOST']."/site/img/metabolomeXchange.png</url>";
    $rss .= "\n\t\t\t<title>MetabolomeXchange</title>";
    $rss .= "\n\t\t\t<link>http://".$_SERVER['HTTP_HOST']."</link>";
    $rss .= "\n\t\t</image>";  

    $atomLinkUrl = 'http://'.$_SERVER['HTTP_HOST'].'/rss';  
    
    if (isset($for)) {
        // search enabled rss title and atom:link
        $rss .= "\n\t\t<title>metabolomeXchange RSS feed matching ".$for."</title>";    
        $rss .= "\n\t\t<atom:link href=\"".$atomLinkUrl."/search/".$for."\" rel=\"self\" type=\"application/rss+xml\" />";        
    } else {
        // default title and atom:link
        $rss .= "\n\t\t<title>metabolomeXchange RSS feed</title>";
        $rss .= "\n\t\t<atom:link href=\"".$atomLinkUrl."\" rel=\"self\" type=\"application/rss+xml\" />";
    }
    
    $rss .= "\n\t\t<link>http://www.metabolomeXchange.org</link>";
    $rss .= "\n\t\t<description>metabolomeXchange RSS feed</description>";
    $rss .= "\n\t\t<language>en-us</language>";
    $rss .= "\n\t\t<copyright>Copyright (C) " . date("Y") . " metabolomeXchange.org</copyright>";

    $rssFeedLimit = 15; // display only the 15 most recent entries
    foreach( $datasets as $dataset ) {

        if ($rssFeedLimit > 0){

            $provider = $providerDetails[$dataset['provider']];            
        	$guidLink = "http://" . htmlspecialchars($_SERVER['HTTP_HOST'] . "/dataset/provider/" . urlencode($provider['name']) . '/accession/' . urlencode($dataset['accession']));

            $rss .= "\n\t\t<item>";
            $rss .= "\n\t\t\t<guid>".$guidLink."</guid>";        
            $rss .= "\n\t\t\t<link>".$guidLink."</link>";        
            $rss .= "\n\t\t\t<title>" . htmlspecialchars($dataset['title']) . "</title>";
            $rss .= "\n\t\t\t<description>&lt;a target=\"_blank\" href=\"" . htmlspecialchars($dataset['url']) . '"&gt;' . htmlspecialchars($provider['name']) . ' entry by ' . htmlspecialchars(join(", ", $dataset['submitter'])) . '&lt;/a&gt;: ' . htmlspecialchars($dataset['description']) . "</description>";
            $rss .= "\n\t\t\t<pubDate>" . date("D, d M Y H:i:s O", $dataset['timestamp']) . "</pubDate>";
            $rss .= "\n\t\t</item>";

            $rssFeedLimit--;
        }
    }

    $rss .= "\n\t</channel>";
    $rss .= "\n</rss>"; 

    echo $rss;