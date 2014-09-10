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

	// cache dir
	$cacheDir = 'cache';
	if (!file_exists($cacheDir)){ mkdir($cacheDir, 0755, true);	}

	// cache file
	$cachedVersion = $cacheDir . '/stats.' . date("Ymd") . '.cache'; // refresh ones a day

	// read or produce cached version of the documentation
	if (!is_readable($cachedVersion)){

		$providers = Flight::get('providers');

		//build stats
		$minYear = date("Y");
		$maxYear = date("Y");	
		$providerDatasetsByYearMonth = array();

		foreach (Flight::get('database')->find('datasets') as $dIdx => $dataset){

			$provider = $providers[$dataset['provider_uuid']];
			$month = date("m", $dataset['date']);
			$year = date("Y", $dataset['date']);
			$ym = $year . '-' . $month;

			// record oldest date (Y)
			if ((int)$minYear > (int)$year) { $minYear = $year; }

			if (!isset($providerDatasetsByYearMonth[$provider['name']])){
				$providerDatasetsByYearMonth[$provider['name']] = array();
			}

			if (!isset($providerDatasetsByYearMonth[$provider['name']][$ym])){
				$providerDatasetsByYearMonth[$provider['name']][$ym] = 0;
			}		

			$providerDatasetsByYearMonth[$provider['name']][$ym]++;

		}
		$strYears = '';
		$startYear = $minYear;
		while($startYear < $maxYear){
			if ($startYear > 2009){ // skipp the oldies
				$strYears .= '"'.$startYear.'",';
			}
			$startYear++;
		}
		$strYears .= '"'.$maxYear.'"';

		// prep tsv
		$tsvText = "provider\tyear/month\tmonth\tyear\tdatasets\tincrease\n";
		foreach ($providers as $pIdx => $provider){

			$providerTotal = 0;

			$y = $minYear;
			while ((int)$y <= (int)$maxYear){
				$m = 1;
				while($m <= 12){
					$ym =  $y . '-' . (($m < 10) ? "0$m" : "$m");
					$increase = 0;
					if(isset($providerDatasetsByYearMonth[$provider['name']][$ym])){
						$increase = $providerDatasetsByYearMonth[$provider['name']][$ym];
						$providerTotal += $increase;
					}
					if (((int)$y <= (int)$maxYear)){						
                    //if (((int)$y < (int)$maxYear) || ($m <= ((int)date("m")+1))){						
						$tsvText .= $provider['name'] . "\t" . $ym . "\t" . $m . "\t". $y . "\t" . $providerTotal . "\t". $increase ."\n";
					}
					$m++;
				}
				$y++;
			}
		}

		// cache documentation!
		try {
			file_put_contents($cachedVersion, $tsvText);
		} catch (Exception $e) {
			// was unable to cache this documents
		}
	}		


	require_once('header.php');
?>
	<header>
		<h2>Stats</h2>
	</header>
	<div id="charts">

		<h3>Number of datasets by year/month</h3>	
		<div id="chartContainer">
		  <script type="text/javascript">
		      var svg = dimple.newSvg("#chartContainer", document.getElementById('charts').offsetWidth, 450);
		      d3.tsv("<?=$cachedVersion?>", function (data) {

		          // Filter
		          data = dimple.filterData(data, "year", [
		              <?=$strYears?>
		          ]);

		          // Create the indicator chart on the right of the main chart
		          var indicator = new dimple.chart(svg, data);

		          // Pick blue as the default and orange for the selected month
		          var defaultColor = indicator.defaultColors[0];
		          var indicatorColor = indicator.defaultColors[2];

		          // The frame duration for the animation in milliseconds
		          var frame = 3000;

		          var firstTick = true;

		          // Place the indicator bar chart to the right
		          indicator.setBounds(document.getElementById('charts').offsetWidth-200, 80, 150, 300);

		          // Add dates along the y axis
		          var y = indicator.addCategoryAxis("y", "year");
		          y.addOrderRule("year", "Desc");

		          // Use sales for bar size and hide the axis
		          var x = indicator.addMeasureAxis("x", "increase");
		          x.hidden = true;

		          // Add the bars to the indicator and add event handlers
		          var s = indicator.addSeries(null, dimple.plot.bar);
		          s.addEventHandler("click", onClick);
		          // Draw the side chart
		          indicator.draw();

		          // Remove the title from the y axis
		          y.titleShape.remove();

		          // Remove the lines from the y axis
		          y.shapes.selectAll("line,path").remove();

		          // Move the y axis text inside the plot area
		          y.shapes.selectAll("text")
		                  .style("text-anchor", "start")
		                  .style("font-size", "11px")
		                  .attr("transform", "translate(18, 0.5)");

		          // This block simply adds the legend title. I put it into a d3 data
		          // object to split it onto 2 lines.  This technique works with any
		          // number of lines, it isn't dimple specific.
		          svg.selectAll("title_text")
		                  .data(["Click bar to select",
		                      "and pause. Click again",
		                      "to resume animation"])
		                  .enter()
		                  .append("text")
		                  .attr("x", document.getElementById('charts').offsetWidth-180)
		                  .attr("y", function (d, i) { return 15 + i * 12; })
		                  .style("font-family", "sans-serif")
		                  .style("font-size", "10px")
		                  .style("color", "Black")
		                  .text(function (d) { return d; });

		          // Manually set the bar colors
		          s.shapes
		                  .attr("rx", 10)
		                  .attr("ry", 10)
		                  .style("fill", function (d) { return (d.y === '2010' ? indicatorColor.fill : defaultColor.fill) })
		                  .style("stroke", function (d) { return (d.y === '2010' ? indicatorColor.stroke : defaultColor.stroke) })
		                  .style("opacity", 0.4);

		          // Draw the main chart
		          var c = new dimple.chart(svg, data);
		          c.setBounds(70, 80, document.getElementById('charts').offsetWidth-290, 300)
		          var cx = c.addMeasureAxis("x", "month");
		          cx.overrideMin = 1;
		          cx.addOrderRule("month");
		          var cy = c.addMeasureAxis("y", "datasets");
		          cy.addOrderRule("datasets");
		          c.addSeries(["month","provider"], dimple.plot.line)
		          c.addLegend(70, 10, document.getElementById('charts').offsetWidth-290, 60);

		          // Add a storyboard to the main chart and set the tick event
		          var story = c.setStoryboard("year", onTick);
		          // Change the frame duration
		          story.frameDuration = frame;
		          // Order the storyboard by date
		          story.addOrderRule("year");

		          // Draw the bubble chart
		          c.draw();

		          // Orphan the legends as they are consistent but by default they
		          // will refresh on tick
		          c.legends = [];
		          // Remove the storyboard label because the chart will indicate the
		          // current month instead of the label
		          story.storyLabel.remove();

		          // On click of the side chart
		          function onClick(e) {
		              // Pause the animation
		              story.pauseAnimation();
		              // If it is already selected resume the animation
		              // otherwise pause and move to the selected month
		              if (e.yValue === story.getFrameValue()) {
		                  story.startAnimation();
		              } else {
		                  story.goToFrame(e.yValue);
		                  story.pauseAnimation();
		              }
		          }

		          // On tick of the main charts storyboard
		          function onTick(e) {
		              if (!firstTick) {
		                  // Color all shapes the same
		                  s.shapes
		                          .transition()
		                          .duration(frame / 2)
		                          .style("fill", function (d) { return (d.y === e ? indicatorColor.fill : defaultColor.fill) })
		                          .style("stroke", function (d) { return (d.y === e ? indicatorColor.stroke : defaultColor.stroke) });
		              }
		              firstTick = false;
		          }
		      });
		  </script>
		</div>
	
		<h3>Number of datasets by month</h3>	
		<div id="chartContainer1">		
		  <script type="text/javascript">
		    var svg1 = dimple.newSvg("#chartContainer1", document.getElementById('charts').offsetWidth, 450);
		    d3.tsv("<?=$cachedVersion?>", function (data) {
				var myChart = new dimple.chart(svg1, data);
				myChart.setBounds(60, 60, document.getElementById('charts').offsetWidth-80, 305);
				var x = myChart.addCategoryAxis("x", "year/month");
				x.addOrderRule("year/month");
				myChart.addMeasureAxis("y", "datasets");
				var s = myChart.addSeries(["provider"], dimple.plot.line);
				s.addOrderRule("provider");
				s.interpolation = "step";
				var myLegend = myChart.addLegend(60, 5, document.getElementById('charts').offsetWidth-80, 20, "right");
				myChart.draw();

				myChart.legends = [];

			    var filterValues = dimple.getUniqueValues(data, "provider");
			    myLegend.shapes.selectAll("rect")
			      .on("click", function (e) {
			        var hide = false;
			        var newFilters = [];
			        filterValues.forEach(function (f) {
			          if (f === e.aggField.slice(-1)[0]) {
			            hide = true;
			          } else {
			            newFilters.push(f);
			          }
			        });
			        if (hide) {
			          d3.select(this).style("opacity", 0.2);
			        } else {
			          newFilters.push(e.aggField.slice(-1)[0]);
			          d3.select(this).style("opacity", 0.8);
			        }
			        filterValues = newFilters;
			        myChart.data = dimple.filterData(data, "provider", filterValues);

			      	myChart.draw();
			  });

		    });
		  </script>
		</div>

		<h3>Datasets added over time</h3>
		<div id="chartContainer0">
		  <script type="text/javascript">
		    var svg0 = dimple.newSvg("#chartContainer0", document.getElementById('charts').offsetWidth, 450);
		    d3.tsv("<?=$cachedVersion?>", function (data) {
				var myChart = new dimple.chart(svg0, data);
				myChart.setBounds(60, 60, document.getElementById('charts').offsetWidth-80, 305);
				var x = myChart.addCategoryAxis("x", "year/month");
				x.addOrderRule("year/month");
				myChart.addMeasureAxis("y", "datasets");
				myChart.addMeasureAxis("z", "increase");
				var s = myChart.addSeries("provider", dimple.plot.bubble);
				s.addOrderRule("provider");
				s.interpolation = "step";
				var myLegend = myChart.addLegend(60, 5, document.getElementById('charts').offsetWidth-80, 20, "right");
				myChart.draw();

				myChart.legends = [];

			    var filterValues = dimple.getUniqueValues(data, "provider");
			    myLegend.shapes.selectAll("rect")
			      .on("click", function (e) {
			        var hide = false;
			        var newFilters = [];
			        filterValues.forEach(function (f) {
			          if (f === e.aggField.slice(-1)[0]) {
			            hide = true;
			          } else {
			            newFilters.push(f);
			          }
			        });
			        if (hide) {
			          d3.select(this).style("opacity", 0.2);
			        } else {
			          newFilters.push(e.aggField.slice(-1)[0]);
			          d3.select(this).style("opacity", 0.8);
			        }
			        filterValues = newFilters;
			        myChart.data = dimple.filterData(data, "provider", filterValues);
			      	myChart.draw();
			  });

		    });
		  </script>
		</div>	

	</div>
<?
	require_once('footer.php');
?>