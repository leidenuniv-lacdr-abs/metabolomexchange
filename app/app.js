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
    when('/search/:query', { templateUrl: '/app/views/search.html', controller: 'MxCtrl' }).
    when('/provider/:provider', { templateUrl: '/app/views/provider.html', controller: 'MxCtrl' }).
    otherwise({ redirectTo: '/' });
});
/* ----------------------------*/

/**
 * ANGLULAR CONTROLLERS
 **/
mx.controller('MxCtrl', ['$scope', '$routeParams', '$http', '$location', '$anchorScroll', 
    function ($scope, $routeParams, $http, $location, $anchorScroll){

        // default path to take
        if (!$location.path){
            $location.path('/');
        }
            
        // load providers into scope            
        getProviders($scope, $http);

        if ($routeParams.query){
            $scope.query = $routeParams.query;
            findDatasets($scope, $http, $routeParams.query);
        } else if ($routeParams.provider && $routeParams.accession) {
            getDataset($scope, $http, $routeParams.provider, $routeParams.accession);
        } else if ($routeParams.provider){
            getProvider($scope, $http, $routeParams.provider);
        } 

        function getProviders($scope, $http) {
        $http.get('http://api.metabolomexchange.org/providers', { cache: true }).
            success(function(data) {
                $scope.providers = data;
                console.log("... new call to fetch all providers");
            });
        }

        function getProvider($scope, $http, provider) {
        $http.get('http://api.metabolomexchange.org/provider/' + provider, { cache: true }).
            success(function(data) {
                $scope.provider = data;
                $scope.datasets = data.datasets;                
                console.log("... new call to fetch a single provider " + provider);
            });
        } 

        function getDataset($scope, $http, provider, accession) {
        $http.get('http://api.metabolomexchange.org/provider/' + provider + '/' + accession, { cache: true }).
            success(function(data) {
                $scope.dataset = data;
                console.log("... new call to fetch dataset " + provider + '/' + accession);
            });
        }

        function getDatasets($scope, $http) {
        $http.get('http://api.metabolomexchange.org/datasets', { cache: true }).
            success(function(data) {
                $scope.datasets = data;
                console.log("... new call to fetch all datasets");
            });
        }

        function findDatasets($scope, $http, query) {
        var andMatch = query.replace(new RegExp(' ', 'g'), '&'); // re-format to call the API with a AND match result
        $http.get('http://api.metabolomexchange.org/datasets/' + andMatch, { cache: true }).
            success(function(data) {
                $scope.datasets = data;
                console.log("... new call to fetch all (" + data.length + ") datasets that match " + andMatch);
            });
        }

        $scope.updateResults = function(){
            console.log('updateResults on path', $location.path());
            if (!$scope.query && $location.path() != '/'){
                $location.path('/');
                $location.hash('top');
                $anchorScroll();                 
            }                        
            if ($scope.query && $location.path() == '/'){
                $location.path('search');
                $location.hash('top');
                $anchorScroll();                 
            }                   

            findDatasets($scope, $http, $scope.query);
            $location.search($scope.query); // update url
            $location.hash('top'); // scroll to top where the results start
            $anchorScroll();            
        } 

        /**
         * ANCHORS
         **/
        $scope.scrollTo = function(id) {
            $location.hash(id);
            $anchorScroll();
        }

        /**
         * VIEW Switcher
         **/
        $scope.changeView = function(view){
            if ($location.path != view){
                $location.path(view);
                $location.hash('top');
                $anchorScroll();            
            }
        }
    }
]); 

mx.directive('focus', function($timeout) {
    return {
        scope : {
            trigger : '@focus'
        },
        link : function(scope, element) {
            scope.$watch('trigger', function(value) {
                if (value === "true") {
                    $timeout(function() {
                        element[0].focus();
                    });
                }
            });
        }
    };

});

/* ----------------------------*/
