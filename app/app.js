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
    when('/dataset/:provider/:accession', { templateUrl: '/app/views/dataset.html', controller: 'MxCtrl' }).
    when('/about', { templateUrl: '/app/views/about.html', controller: 'MxCtrl' }).            
    otherwise({ redirectTo: '/' });
});
/* ----------------------------*/

/**
 * ANGLULAR CONTROLLERS
 **/
mx.controller('MxCtrl', function ($scope, $routeParams, $http){
    
    //getProviders($scope, $http);        
    
    if ($routeParams.query){
        findDatasets($scope, $http, $routeParams.query);
        console.log($routeParams.query);
    } else if ($routeParams.provider && $routeParams.accession) {
        getDataset($scope, $http, $routeParams.provider, $routeParams.accession);
    } else {
        getDatasets($scope, $http);
    }

    function getProviders($scope, $http) {
    $http.get('http://api.metabolomexchange.org/providers', { cache: true }).
        success(function(data) {
            $scope.providers = data;
        });
    }

    function getDataset($scope, $http, provider, accession) {
    $http.get('http://api.metabolomexchange.org/provider/' + provider + '/' + accession, { cache: true }).
        success(function(data) {
            $scope.dataset = data;
        });
    }

    function getDatasets($scope, $http) {
    $http.get('http://api.metabolomexchange.org/datasets', { cache: true }).
        success(function(data) {
            $scope.datasets = data;
        });
    }

    function findDatasets($scope, $http, query) {
    $http.get('http://api.metabolomexchange.org/datasets/' + query, { cache: true }).
        success(function(data) {
            $scope.datasets = data;
        });
    }
}); 

mx.controller('SearchCtrl', function ($scope, $location, $routeParams){
    $scope.changeView = function(view){
        $location.path(view);
        $location.search('query', $scope.query); // add query parameter to search scope through a GET param
    }
}); 
   
/* ----------------------------*/
