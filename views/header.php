<?php

/**
 * Copyright 2014 Michael van Vliet (Leiden University), Thomas Hankemeier 
 * (Leiden University)
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * 
 *      http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 **/

require_once('ViewHelper.php');

?>

<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>MetabolomeXchange</title>
        <meta name="description" content="metabolomics data sharing">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" href="/favicon.png">
        <link href='http://fonts.googleapis.com/css?family=Shadows+Into+Light' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="/css/normalize.min.css">
        <link rel="stylesheet" href="/css/main.css">

        <script src="/js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
        <script src="/js/vendor/d3.v3.min.js"></script>
        <script src="/js/vendor/dimple.v2.0.0.min.js"></script>        
    </head>
    <body OnLoad="document.searchform.search.focus();">
        <div class="content">
          <div class="header">
            <div class="header_content">
                <div class="logo">
                  <a href="/#/">metabolome<font style="font-size: 1.6em; color: #2a7640">X</font>change</a>
                </div>
                <div class="menu"> 
                  <div class="menu_item"><a href="/">Home</a></div>        
                  <div class="menu_item"><a href="/search">Search</a></div>
                  <div class="menu_item"><a href="/about">About</a></div>
                </div>
                <br class="clearBoth" />
            </div>
          </div>
          <div class="search">
            <div class="search_content">
                <?=ViewHelper::displaySearchBox(isset($search) ? $search : '')?>              
                <?=ViewHelper::displayRecentSearches(isset($recentSearches) ? $recentSearches : '')?> 
                <br class="clearBoth" />
            </div>
          </div>          
          <div class="content_main"> 