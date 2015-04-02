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
	require_once('ViewHelper.php');	

	// correct for when result count == 1
	if (isset($datasets['uuid'])){ $datasets = array($datasets); }

	// get the number of datasets found
	$intDatasets = count($datasets);

?>

	<?=ViewHelper::displayRSSFromSearchLink($search)?>
	<div style="margin-top:15px;">
		<?php echo '<h4>'.$intDatasets.' dataset(s) found!</h4>'; ?>
		<?=ViewHelper::displayDatasets($datasets, $providers, array('details'=>'list'))?>
	</div>

<?php
	require_once('footer.php');
?>