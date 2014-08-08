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
	require_once('ViewHelper.php');
?>
	<header>
		<div style="float:right"><a target="_blank" href="/rss/?limit=15"><img src="/img/feed-icon-28x28.png"></a></div>		
		<h2>Latest datasets</h2>
	</header>
	<article>		
		<?=ViewHelper::displayDatasets($datasets, $providers, array('details'=>'basic'))?>
	</article>
    <aside>
    	<?php
    		$rows = '';
    		$intTotalNumberOfDatasets = 0;

    		foreach($providerDatasets as $provider_uuid => $intDatasets){
    			try {
    				$intTotalNumberOfDatasets += $intDatasets;
					$rows .= '<tr><td>';
					$rows .= '	<a class="white" href="/provider/'.$providers[$provider_uuid]['name'].'">'.$providers[$provider_uuid]['name'].'</a>';
					$rows .= '</td><td>&nbsp;</td></td><td>'.$intDatasets.'</td></tr>';
				} catch (Exception $e) {
					echo $e->getMessage();
				}
    		}
    	?>

    	<h3><?=$intTotalNumberOfDatasets?> datasets <a href="/stats"><img src="/img/chart_line.png"></a></h3>
    	<table>
			<?=$rows?>
    	</table>
    </aside>
    <bside>
		<?=ViewHelper::displaySearchBox()?>				    	
		<?php if (count($popularSearches)) { ?>		
			<?=ViewHelper::displayPopularSearches($popularSearches)?>	
		<?php } ?>
    </bside>
<?php 

	require_once('footer.php');
