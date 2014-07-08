var attemptControllers = angular.module('attemptControllers', ['ui.router']);

attemptControllers.controller('attemptController', ['$scope', '$state', 'AttemptByExercise', 'ExerciseByModel', 'Exercise', 'Attempt', 'Item', '$routeParams', '$location', '$stateParams',
    function ($scope, $state, AttemptByExercise, ExerciseByModel, Exercise, Attempt, Item, $routeParams, $location, $stateParams) {

        $scope.imageUrl = BASE_CONFIG.urls.images.uploads;
        $scope.imageExoUrl = BASE_CONFIG.urls.images.exercise;
        $scope.navBarUrl = BASE_CONFIG.urls.partials.learner + '/fragment-nav-bar.html';

        $scope.validable = false;

        console.log('loading attempt...');
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
                                $scope.gotoItem(0);
                            });
                    });
            });

        $scope.gotoItem = function (index) {
            // switch item
            $scope.item = $scope.items[index];
            // when data loaded
            if ($scope.item.type == 'pair-items') {
                $state.go('attempt.pair-items', {itemId: index}, {location: false});
            } else if ($scope.item.type == 'group-items') {
                $state.go('attempt.group-items', {itemId: index}, {location: false});
            } else if ($scope.item.type == 'multiple-choice') {
                $state.go('attempt.multiple-choice', {itemId: index}, {location: false});
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

        $scope.tryModel = function (modelId) {
            // create exercise from model
            console.log('create exercise...');
            exercise = ExerciseByModel.try({modelId: modelId},
                function (exercise) {
                    $scope.tryExercise(exercise);
                });
        };

    }]);

var itemControllers = angular.module('itemControllers', ['ui.router']);

itemControllers.controller('pairItemsController', ['$scope', 'Answer', '$routeParams', '$location', '$stateParams',
    function ($scope, Answer, $routeParams, $location, $stateParams) {

        // post answer
        $scope.saveAnswer = function () {
            $scope.validable = false;
            answer = new Answer;
            answer.content = [];

            for (i = 0; i < $scope.drop.length; ++i) {
                answer.content.push($scope.drop[i].id);
            }

            item = answer.$save({itemId: $scope.item.item_id, attemptId: $stateParams.attemptId},
                function (item) {
                    $scope.items[$stateParams.itemId] = item;
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

        // drag and drop
        $scope.onDropList = function ($event, $data, array) {
            array.push($data);
            $scope.validable = false;
        };

        $scope.onDropField = function ($event, $data, fieldNumber) {
            $scope.drop[fieldNumber] = $data;
        };

        $scope.dropSuccessHandler = function ($event, index, array) {
            array.splice(index, 1);
            if ($scope.item['content'].mobile_parts.length == 0) {
                $scope.validable = true;
            }
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

itemControllers.controller('multipleChoiceController', ['$scope', 'Answer', '$routeParams', '$location', '$stateParams',
    function ($scope, Answer, $routeParams, $location, $stateParams) {

        // post answer
        $scope.saveAnswer = function () {
            $scope.validable = false;

            answer = new Answer;
            answer.content = [];

            for (i = 0; i < $scope.tick.length; ++i) {
                if ($scope.tick[i]) {
                    val = 1;
                } else {
                    val = 0;
                }

                answer.content.push(val);
            }

            console.log($scope.item.item_id);

            item = answer.$save({itemId: $scope.item.item_id, attemptId: $stateParams.attemptId},
                function (item) {
                    $scope.items[$stateParams.itemId] = item;
                    $scope.displayCorrection(item)
                });
        };

        // correction
        $scope.displayCorrection = function (item) {
            for (i = 0; i < $scope.tick.length; ++i) {
                $scope.solution[i] = item['content'].propositions[i]['right'];
            }
            $scope.item.corrected = true;
            $scope.item['content']['comment'] = item['content']['comment'];
            $scope.item['content']['mark'] = item['content']['mark'];
        };

        // display learner answers
        $scope.fillLearnerAnswers = function () {
            for (i = 0; i < $scope.tick.length; ++i) {
                $scope.tick[i] = $scope.item['content'].propositions[i].ticked;
            }
        };

        // init answer array
        $scope.tick = [];
        $scope.solution = [];
        console.log('reinit...');
        for (i = 0; i < $scope.item['content'].propositions.length; ++i) {
            $scope.tick[i] = false;
            $scope.solution[i] = null;
        }

        if ($scope.item['corrected'] == true) {
            $scope.fillLearnerAnswers();
            $scope.displayCorrection($scope.item);
        } else {
            $scope.validable = true;
        }
    }]);

itemControllers.controller('groupItemsController', ['$scope', 'Answer', '$routeParams', '$location', '$stateParams',
    function ($scope, Answer, $routeParams, $location, $stateParams) {

        // post answer
        $scope.saveAnswer = function () {
            $scope.validable = false;
            var answer = new Answer;
            answer.content = {"obj": []};
            if ($scope.dgn === 'ask') {
                answer.content.gr = [];
            }

            for (var i = 0; i < $scope.groups.length; ++i) {
                // objects
                for (var j = 0; j < $scope.groups[i].objects.length; ++j) {
                    answer.content.obj[$scope.groups[i].objects[j].id] = i;
                }

                // group names
                if ($scope.dgn === 'ask') {
                    answer.content.gr[i] = $scope.groups[i].name;
                }
            }


            console.log(answer);

            item = answer.$save({itemId: $scope.item.item_id, attemptId: $stateParams.attemptId},
                function (item) {
                    $scope.items[$stateParams.itemId] = item;
                    $scope.displayCorrection(item)
                });
        };

        // correction
        $scope.displayCorrection = function (item) {
            $scope.item.corrected = true;
            $scope.item['content']['mark'] = item['content']['mark'];

            console.log($scope.solutions);

            for (var i = 0; i < $scope.groups.length; ++i) {
                for (var j = 0; j < $scope.groups[i].objects.length; ++j) {
                    if (item['content'].solutions[$scope.groups[i].objects[j].id] === i) {
                        $scope.groups[i].objects[j].right = true;
                    } else {
                        $scope.solutions[
                            item['content'].solutions[$scope.groups[i].objects[j].id]
                            ].obj.push($scope.groups[i].objects[j]);
                        $scope.groups[i].objects[j].right = false;
                    }
                }

                // group names
                if ($scope.dgn == 'ask') {
                    $scope.groups[i].goodName = item['content'].groups[i];
                }
            }


        };

        // display learner answers
        $scope.fillLearnerAnswers = function () {
            for (i = 0; i < $scope.item['content'].answers.obj.length; ++i) {
                $scope.groups[
                    $scope.item['content'].answers.obj[i]
                    ].objects.push($scope.item['content'].objects[i]);
            }

            // group names
            if ($scope.dgn == 'ask')
            {
                for (i = 0; i < $scope.item['content'].answers.gr.length; ++i) {
                    $scope.groups[i].name = $scope.item['content'].answers.gr[i];
                }
            }
        };

        // drag and drop
        $scope.onDropList = function ($event, $data, array) {
            array.push($data);
        };

        $scope.dropSuccessHandler = function ($event, index, array) {
            array.splice(index, 1);
            $scope.validable = ($scope.item['content'].objects.length == 0);
        };

        // init groups and solution
        $scope.groups = [];
        $scope.solutions = [];
        $scope.dgn = $scope.item['content'].display_group_names;
        for (i = 0; i < $scope.item['content'].groups.length; ++i) {
            $scope.groups[i] = {objects: []};
            if ($scope.dgn === 'show') {
                $scope.groups[i].name = $scope.item['content'].groups[i];
            }
            else {
                $scope.groups[i].name = null;
            }
            $scope.solutions[i] = {"obj": [], "gr": []};
        }

        // init objects
        for (i = 0; i < $scope.item['content'].objects.length; ++i) {
            $scope.item['content'].objects[i].id = i;
        }

        // corrected?
        if ($scope.item['corrected'] == true) {
            $scope.fillLearnerAnswers();
            $scope.displayCorrection($scope.item);
        }
    }]);
