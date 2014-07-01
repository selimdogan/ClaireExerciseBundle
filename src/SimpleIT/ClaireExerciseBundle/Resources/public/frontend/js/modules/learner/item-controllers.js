var itemControllers = angular.module('itemControllers', ['ui.router']);

itemControllers.controller('itemController', ['$scope', '$state', 'Item', 'Answer', '$routeParams', '$location', '$stateParams',
    function ($scope, $state, Item, Answer, $routeParams, $location, $stateParams) {

        $scope.section = 'item';
        $scope.imageUrl = BASE_CONFIG.urls.images.uploads;
        $scope.imageExoUrl = BASE_CONFIG.urls.images.exercise;

        console.log('item loading...');

        // retrieve item
        $scope.item = Item.get({itemId: $stateParams.itemId, attemptId: $stateParams.attemptId},
            function () {
                // when data loaded
                console.log('item loaded');
                if ($scope.item.type == 'pair-items') {
                    $state.go('attempt.item.pair-items');
                }
            });
    }]);

itemControllers.controller('pairItemsController', ['$scope', 'Item', 'Answer', '$routeParams', '$location', '$stateParams',
    function ($scope, Item, Answer, $routeParams, $location, $stateParams) {

        // post answer
        $scope.saveAnswer = function () {
            answer = new Answer;
            answer.content = [];

            for (i = 0; i < $scope.drop.length; ++i) {
                answer.content.push($scope.drop[i].id);
            }

            item = answer.$save({itemId: $stateParams.itemId, attemptId: $stateParams.attemptId},
                function (item) {
                    $scope.displayCorrection(item)
                });
        };

        // correction
        $scope.displayCorrection = function (item) {
            for (i = 0; i < $scope.drop.length; ++i) {
                $scope.solution[i] = item['content'].mobile_parts[
                    item['content'].solutions[i]
                    ];
                $scope.solution[i].right =
                    item['content'].answers[i] == item['content'].solutions[i];
            }
            $scope.item.corrected = true;
            $scope.item['content']['comment'] = item['content']['comment'];
            $scope.item['content']['mark'] = item['content']['mark'];
        };

        // display learner answers
        $scope.fillLearnerAnswers = function () {
            for (i = 0; i < $scope.drop.length; ++i) {
                $scope.drop[i] = $scope.item['content'].mobile_parts[
                    $scope.item['content'].answers[i]
                    ];
            }
        };

        // back button
        $scope.backToList = function () {
            $location.path("/learner");
        };

        // drag and drop
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

        // init answer array
        $scope.drop = [];
        $scope.solution = [];
        for (i = 0; i < $scope.item['content'].mobile_parts.length; ++i) {
            $scope.drop[i] = null;
            $scope.solution[i] = null;
            $scope.item['content'].mobile_parts[i].id = i;
        }
        if ($scope.item['corrected'] == true) {
            $scope.fillLearnerAnswers();
            $scope.displayCorrection($scope.item);
        }
    }]);
