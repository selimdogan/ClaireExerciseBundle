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
                                if ($state['current'].name == 'attempt') {
                                    $state.go('attempt.item',
                                        {
                                            attemptId: $stateParams.attemptId,
                                            itemId: $scope.items[0].item_id
                                        });
                                }
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
                // rediriger vers la tentative d'exercice
                console.log('redirection');
                $location.path("/learner/exercise/" + exercise.id + '/try');
            });

    }]);

exerciseControllers.controller('exerciseTryController', ['$scope', 'ModelTry', 'ExerciseByAttempt', '$routeParams', '$location', '$stateParams',
    function ($scope, ModelTry, ExerciseByAttempt, $routeParams, $location, $stateParams) {
        // créer une tentative pour l'exercice
        console.log('create attempt...');
        attempt = ExerciseByAttempt.create({exerciseId: $stateParams.exerciseId},
            function (attempt) {
                // rediriger vers la page pour y répondre
                console.log('redirection');
                $location.path("/learner/attempt/" + attempt.id);
            });
    }]);

var attemptListControllers = angular.module('attemptListControllers', ['ui.router']);

attemptListControllers.controller('attemptListController', ['$scope', 'AttemptList', '$routeParams', '$location', '$stateParams',
    function ($scope, AttemptList, $routeParams, $location, $stateParams) {
        $scope.section = 'attempts';
        $scope.imageUrl = BASE_CONFIG.urls.images.uploads;
        $scope.imageExoUrl = BASE_CONFIG.urls.images.exercise;

        console.log('attempts loading...');

        // retrieve attempts
        $scope.models = AttemptList.query(
            function () {
                // when data loaded
                console.log('attempts loaded');
            });

        $scope.tryAgainExercise = function (exercise) {
            $location.path("/learner/exercise/" + exercise.id + '/try');
        };

        $scope.tryModel = function (model) {
            $location.path("/learner/model/" + model.id + '/try');
        };

        $scope.viewAttempt = function (attempt) {
            $location.path("/learner/attempt/" + attempt.id);
        };
    }]);

