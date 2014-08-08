<?php

/**
 * Copyright 2014 Michael van Vliet (Leiden University), Thomas Hankeijer 
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
        <link rel="stylesheet" href="/css/normalize.min.css">
        <link rel="stylesheet" href="/css/main.css">

        <script src="/js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
        <script src="/js/vendor/d3.v3.min.js"></script>
        <script src="/js/vendor/dimple.v2.0.0.min.js"></script>        
    </head>
    <body OnLoad="document.searchform.search.focus();">
        <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
        <div class="header-container">
            <header class="wrapper clearfix">
                <a href="/"><img src="/img/metabolomeXchange.png"></a>
                <nav>
                    <ul>
                        <li><a href="/">Home</a></li>
                        <li><a href="/search">Search</a></li>
                        <li><a href="/documentation">API</a></li>
                    </ul>
                </nav>                
            </header>
        </div>

        <div class="main-container">            
            <div class="main wrapper clearfix">                 