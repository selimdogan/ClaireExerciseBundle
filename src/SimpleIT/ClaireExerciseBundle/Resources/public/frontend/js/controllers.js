var mainAppControllers = angular.module('mainAppControllers', ['ui.router']);

mainAppControllers.controller('mainController', ['$scope', '$routeParams', '$location', 'BASE_CONFIG',
    function($scope, $routeParams, $location, BASE_CONFIG) {

        $scope.BASE_CONFIG = BASE_CONFIG;

    }]);