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
		<div style="float:right"><a target="_blank" href="/rss/by/provider_uuid/<?=$provider['uuid']?>/?limit=15"><img src="/img/feed-icon-28x28.png"></a></div>		
		<h2><?=$provider['name']?> dataset</h2>		
	</header>
	<article>
		<?=ViewHelper::displayDatasets($dataset, $providers, array('details'=>'full'))?>
	</article>
    <aside>
    <h3><?=$provider['name']?></h3>
        <p>
        	<?=$provider['about']?>
        	<br /><br /><a class="dark_green" target="_blank" href="<?=$provider['url']?>">read more...</a>
       	</p>
    </aside>
<?
	require_once('footer.php');
?>