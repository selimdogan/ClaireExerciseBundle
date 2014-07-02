/**
 * Created by bryan on 25/06/14.
 */

var itemControllers = angular.module('itemControllers', ['ui.router']);

itemControllers.controller('itemController', ['$scope', 'Item', 'Answer', '$routeParams', '$location', '$stateParams',
    function ($scope, Item, Answer, $routeParams, $location, $stateParams) {

        $scope.section = 'item';
        $scope.imageUrl = BASE_CONFIG.urls.images.uploads;
        $scope.imageExoUrl = BASE_CONFIG.urls.images.exercise;

        console.log('item loading...');

        // retrieve item
        $scope.item = Item.get({itemId: $stateParams.itemId, attemptId: $stateParams.attemptId},
            function () {
                // when data loaded
                console.log('item loaded');
                for (i = 0; i < $scope.item['content'].mobile_parts.length; ++i) {
                    $scope.drop[i] = null;
                    $scope.solution[i] = null;
                    $scope.item['content'].mobile_parts[i].id = i;
                }
                if ($scope.item['corrected'] == true) {
                    $scope.fillLearnerAnswers();
                    $scope.displayCorrection($scope.item);
                }
            });

        // init answer array
        $scope.drop = [];
        $scope.solution = [];

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
        };

        // display learner answers
        $scope.fillLearnerAnswers = function () {
            for (i = 0; i < $scope.drop.length; ++i) {
                $scope.drop[i] = $scope.item['content'].mobile_parts[
                    $scope.item['content'].answers[i]
                    ];
            }
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
    }]);

var exerciseControllers = angular.module('exerciseControllers', ['ui.router']);

exerciseControllers.controller('exerciseController', ['$scope', '$state', 'Exercise', 'Attempt', 'Item', '$routeParams', '$location', '$stateParams',
    function ($scope, $state, Exercise, Attempt, Item, $routeParams, $location, $stateParams) {

        $scope.section = 'exercise';

        // retrieve attempt
        attempt = Attempt.get({attemptId: $stateParams.attemptId},
            function (attempt) {
                // when data loaded
                console.log('loading exercise...');
                $scope.exercise = Exercise.get({exerciseId: attempt.exercise},
                    function () {
                        // when data loaded
                        console.log('loading list of items...');
                        $scope.items = Item.query({attemptId: $stateParams.attemptId},
                            function () {
                                // when data loaded
                                console.log('items loaded.');

                                // inclure le premier item
                                $state.go('attempt.item',
                                    {
                                        attemptId: $stateParams.attemptId,
                                        itemId: $scope.items[0].item_id
                                    });
                            });

                    });
            });
    }]);

var modelTryControllers = angular.module('modelTryControllers', ['ui.router']);

modelTryControllers.controller('modelTryController', ['$scope', 'ModelTry', 'ExerciseByAttempt', '$routeParams', '$location', '$stateParams',
    function ($scope, ModelTry, ExerciseByAttempt, $routeParams, $location, $stateParams) {
        // create exercise from model
        console.log('create exercise...');
        exercise = ModelTry.try({modelId: $stateParams.modelId},
            function (exercise) {
                // créer une tentative pour l'exercice
                console.log('create attempt...');
                attempt = ExerciseByAttempt.create({exerciseId: exercise.id},
                    function (attempt) {
                        // rediriger vers la page pour y répondre
                        console.log('redirection');
                        $location.path("/learner/attempt/" + attempt.id);
                    });
            });

    }]);

