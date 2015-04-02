/**
 * ANGLULAR APP
 **/
var mx = angular.module('mx', ['ngRoute']);

/**
 * ANGLULAR CONFIG
 **/
mx.config(function($routeProvider) {
    $routeProvider.
    when('/', { templateUrl: '/app/views/home.html', controller: 'MxCtrl' }).
    when('/search', { templateUrl: '/app/views/search.html', controller: 'MxCtrl' }).    
    when('/providers', { templateUrl: '/app/views/providers.html', controller: 'MxCtrl' }).
    when('/provider/:provider', { templateUrl: '/app/views/provider.html', controller: 'MxCtrl' }).
    when('/about', { templateUrl: '/app/views/about.html', controller: 'MxCtrl' }).            
    otherwise({ redirectTo: '/' });
});
/* ----------------------------*/

/**
 * ANGLULAR CONTROLLERS
 **/
mx.controller('MxCtrl', function ($scope, $routeParams, $http, $location, $cacheFactory){

    var cache = $cacheFactory.get('mxCache');
        cache = (cache ? cache : $cacheFactory('mxCache'));        
    
    $scope.results = [];    
    $scope.providers = (cache.get('providers') ? cache.get('providers') : []);
    $scope.sortField = 'timestamp';  

    if (!cache.get('providers')){
        console.log("running loop!");
        $http({ method: 'GET', url: 'http://feeds.metabolomexchange.org/providers.php', cache: true }).then(function(urls) { 
            for (shortname in urls.data){
                (function(d) {
                    var providerURL = urls.data[shortname];
                    $http({ method: 'GET', url: providerURL, cache: true }).then(function(responseProvider) {
                        var provider = responseProvider.data;
                        $scope.providers.push(provider);
                    });
                })(urls.data[shortname]);                                                    
            }            
        });                    
    }
    // cache the providers data for future use
    cache.put('providers', $scope.providers);

    if ($routeParams.provider){
        $scope.provider = $routeParams.provider;
    }

    // handle the search
    if ($routeParams.query){
        var providers = cache.get('providers');
        for (pIdx in providers){
            var datasets = providers[pIdx].datasets;
            for (dIdx in datasets){
                var haystack = (JSON.stringify(datasets[dIdx])).toLowerCase();
                var needle = ($routeParams.query).toLowerCase();
                if ( (haystack.indexOf(needle) >= 0) && ($scope.results.indexOf(datasets[dIdx]) === -1) ){ 
                    $scope.results.push(datasets[dIdx]);
                }
            }
        }
    }
}); 

mx.controller('SearchCtrl', function ($scope, $location, $routeParams){
    $scope.changeView = function(view){
        $location.path(view);
        $location.search('query', $scope.query); // add query parameter to search scope through a GET param
    }
}); 

mx.filter('isArray', function() {
  return function (input) {
    return angular.isArray(input);
  };
}); 
   
/* ----------------------------*/
