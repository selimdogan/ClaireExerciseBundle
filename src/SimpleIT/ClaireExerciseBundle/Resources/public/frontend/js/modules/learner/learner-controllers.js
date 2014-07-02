var learnerControllers = angular.module('learnerControllers', ['ui.router']);

learnerControllers.controller('learnerController', ['$scope', 'User', 'AttemptByExercise', 'ExerciseByModel', 'AttemptList', '$routeParams', '$location', '$stateParams',
    function ($scope, User, AttemptByExercise, ExerciseByModel, AttemptList, $routeParams, $location, $stateParams) {
        $scope.section = 'attempts';
        $scope.imageUrl = BASE_CONFIG.urls.images.uploads;
        $scope.imageExoUrl = BASE_CONFIG.urls.images.exercise;

        console.log('attempts loading...');

        // retrieve attempts
        if ($stateParams.modelId == '' || $stateParams.modelId == null) {
            $scope.models = AttemptList.query(
                function () {
                    // when data loaded
                    console.log('attempts loaded');
                    $scope.loadUsers();
                });
        } else {
            $scope.models = [];
            $scope.models[0] = AttemptList.get({modelId: $stateParams.modelId},
                function () {
                    // when data loaded
                    console.log('attempts loaded');
                    $scope.loadUsers();
                });

        }

        $scope.loadUsers = function () {
            userIds = [];
            for (i = 0; i < $scope.models.length; ++i) {
                if (userIds.indexOf($scope.models[i].author) == -1) {
                    userIds.push($scope.models[i].author);
                }
                if (userIds.indexOf($scope.models[i].owner) == -1) {
                    userIds.push($scope.models[i].owner);
                }
            }

            $scope.users = [];
            for (i = 0; i < userIds.length; ++i) {
                $scope.users[userIds[i]] = User.get({userId: userIds[i]});
            }
        };

        $scope.viewAttempt = function (attempt) {
            $location.path("/learner/attempt/" + attempt.id);
        };

        $scope.tryExercise = function (exercise) {
            // create attempt from exercise
            console.log('create attempt...');
            attempt = AttemptByExercise.create({exerciseId: exercise.id},
                function (attempt) {
                    console.log('redirection');
                    $scope.viewAttempt(attempt);
                });
        };

        $scope.tryModel = function (model) {
            // create exercise from model
            console.log('create exercise...');
            exercise = ExerciseByModel.try({modelId: model.id},
                function (exercise) {
                    $scope.tryExercise(exercise);
                });
        };
    }]);

