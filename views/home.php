<!DOCTYPE html>
<html ng-app="mx" lang="en">
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

    <script type="text/javascript">
      // detect IE 9 or higher
      var ie = (function(){ var undef, v = 3, div = document.createElement('div'), all = div.getElementsByTagName('i'); while ( div.innerHTML = '<!--[if gt IE ' + (++v) + ']><i></i><![endif]-->', all[0] ); return v > 4 ? v : undef; }());
      if (ie && ie <= 8){ window.location.replace("/static"); }
    </script>    

    <script src="/js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>    

    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.14/angular.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.14/angular-route.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.14/angular-cookies.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.14/angular-sanitize.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.14/angular-animate.min.js"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>    

    <script src="/app/app.js"></script>    

    <script src="/js/vendor/d3.v3.min.js"></script>
    <script src="/js/vendor/dimple.v2.0.0.min.js"></script>        
</head>
<body OnLoad="document.searchform.search.focus();">
    <div class="content">
      <div class="header">
        <div class="header_content">
            <div class="logo">
              <a href="/#/">metabolome<font style="font-size: 1.7em; color: #2a7640">X</font>change</a>
            </div>
            <div class="menu"> 
              <div class="menu_item"><a href="#/">Home</a></div>
              <div class="menu_item"><a href="#/search">Search</a></div>              
              <div class="menu_item"><a href="#/about">About</a></div>
            </div>
            <br class="clearBoth" />
        </div>
      </div>
      <div class="search">
        <div class="search_content">
          <form ng-controller="SearchCtrl" action="#/search" name="searchform" method="POST" class="form-wrapper cf">
            <input ng-focus="changeView('search')" ng-change="changeView('search')" type="text" name="search" value="" placeholder="Search here..." required>
            <button type="submit">Search</button>
          </form>
          <br class="clearBoth" />
        </div>
      </div>          
      <div class="content_main"> 
        <div ng-view></div>
      </div>
      <div class="mx_footer">
        <div class="mx_footer_content">
          <ul class="share-buttons">
            <li><a href="https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Fmetabolomexchange.org&t=MetabolomeXchange" target="_blank" onclick="window.open('https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(document.URL) + '&t=' + encodeURIComponent(document.URL)); return false;"><img src="/img/flat_web_icon_set/black/Facebook.png"></a></li>
            <li><a href="https://twitter.com/intent/tweet?source=http%3A%2F%2Fmetabolomexchange.org&text=MetabolomeXchange: http%3A%2F%2Fmetabolomexchange.org" target="_blank" title="Tweet" onclick="window.open('https://twitter.com/intent/tweet?text=' + encodeURIComponent(document.title) + ': ' + encodeURIComponent(document.URL) + ' %23metabolomics' + ' %40metabolomexchan'); return false;"><img src="/img/flat_web_icon_set/black/Twitter.png"></a></li>
            <li><a href="https://plus.google.com/share?url=http%3A%2F%2Fmetabolomexchange.org" target="_blank" title="Share on Google+" onclick="window.open('https://plus.google.com/share?url=' + encodeURIComponent(document.URL)); return false;"><img src="/img/flat_web_icon_set/black/Google+.png"></a></li>
            <li><a href="http://www.linkedin.com/shareArticle?mini=true&url=http%3A%2F%2Fmetabolomexchange.org&title=MetabolomeXchange,%20an%20international%20initiative%20to%20promote%20and%20facilitate%20sharing%20of%20Metabolomics%20data.&source=http%3A%2F%2Fmetabolomexchange.org" target="_blank" title="Share on LinkedIn" onclick="window.open('http://www.linkedin.com/shareArticle?mini=true&url=' + encodeURIComponent(document.URL) + '&title=' +  encodeURIComponent(document.title)'); return false;"><img src="/img/flat_web_icon_set/black/LinkedIn.png"></a></li>
            <li><a href="mailto:?subject=MetabolomeXchange&body=MetabolomeXchange,%20an%20international%20initiative%20to%20promote%20and%20facilitate%20sharing%20of%20Metabolomics%20data.: http%3A%2F%2Fmetabolomexchange.org" target="_blank" title="Email" onclick="window.open('mailto:?subject=' + encodeURIComponent(document.title) + '&body=' +  encodeURIComponent(document.URL)); return false;"><img src="/img/flat_web_icon_set/black/Email.png"></a></li>
          </ul>
          <a target="_blank" href="http://www.cosmos-fp7.eu/"><img height="40px" class="mx_logo" src="/img/cosmos/fp7.png"></a> This project is funded through European Commission COSMOS Grant EC312941
          <div>
            <a target="_blank" href="/ns/dcat">RDF</a>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
