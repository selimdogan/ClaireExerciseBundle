/**
 * Created by bryan on 25/06/14.
 */

var itemControllers = angular.module('itemControllers', ['ui.router']);

itemControllers.controller('itemController', ['$scope', 'Item', '$routeParams', '$location', '$stateParams',
    function ($scope, Item, $routeParams, $location, $stateParams) {

        $scope.section = 'item';

        if ($scope.$parent.section === undefined) {
            $scope.parentSection = '';
        } else {
            $scope.parentSection = $scope.$parent.section;
        }

        // retrieve item
        $scope.item = Item.get({itemId: $stateParams.itemId, attemptId: $stateParams.attemptId});

        console.log($scope.item);

        $scope.drop = [];
        $scope.drop[0] = null;
        $scope.drop[1] = null;
        $scope.drop[2] = null;
        $scope.drop[3] = null;

        $scope.saveAnswer = function () {
        };

        $scope.onDropList = function ($event, $data, array) {
            array.push($data);
        };

        $scope.onDropField = function ($event, $data, fieldNumber) {
            $scope.drop[fieldNumber] = $data;
        };

        $scope.dropSuccessHandler = function ($event, index, array) {
            array.splice(index, 1);
        };

        $scope.dropSuccessHandlerField = function ($event, fieldNumber) {
            $scope.drop[fieldNumber] = null;
        };
    }]);

