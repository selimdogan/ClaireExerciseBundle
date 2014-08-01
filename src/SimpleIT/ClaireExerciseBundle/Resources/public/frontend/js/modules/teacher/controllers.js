/**
 * Created by bryan on 25/06/14.
 */

var resourceControllers = angular.module('resourceControllers', ['ui.router']);

resourceControllers.controller('resourceController', ['$scope', '$routeParams', '$location', 'User',
    function ($scope, $routeParams, $location, User) {

        $scope.section = 'resource';

        $scope.filters = {
            search: '',
            archived: false,
            type: {
                multiple_choice_question: 'multiple-choice-question', text: 'text', picture: 'picture', open_ended_question: 'open-ended-question', sequence: ''
            },
            keywords: [],
            metadata: []
        };

        $scope.$parent.$watch("subSection", function (newValue, oldValue) {
            if (newValue == 'pair-items') {
                $scope.filters.type.multiple_choice_question = '';
                $scope.filters.type.text = 'text';
                $scope.filters.type.picture = 'picture';
                $scope.filters.type.open_ended_question = '';
                $scope.filters.type.sequence = '';
            } else if (newValue == 'multiple-choice') {
                $scope.filters.type.multiple_choice_question = 'multiple-choice-question';
                $scope.filters.type.text = '';
                $scope.filters.type.picture = '';
                $scope.filters.type.open_ended_question = '';
                $scope.filters.type.sequence = '';
            } else if (newValue == 'group-items') {
                $scope.filters.type.multiple_choice_question = '';
                $scope.filters.type.text = 'text';
                $scope.filters.type.picture = 'picture';
                $scope.filters.type.open_ended_question = '';
                $scope.filters.type.sequence = '';
            } else if (newValue == 'sequence') {
                $scope.filters.type.multiple_choice_question = '';
                $scope.filters.type.text = '';
                $scope.filters.type.picture = '';
                $scope.filters.type.open_ended_question = '';
                $scope.filters.type.sequence = '';
            } else if (newValue == 'open-ended-question') {
                $scope.filters.type.multiple_choice_question = '';
                $scope.filters.type.text = '';
                $scope.filters.type.picture = '';
                $scope.filters.type.open_ended_question = 'open-ended-question';
                $scope.filters.type.sequence = '';
            }
        });

        if ($scope.$parent.section === undefined) {
            $scope.parentSection = '';
        } else {
            $scope.parentSection = $scope.$parent.section;
        }

        $scope.resourceContext = {
            "newResources": {
                "text": {
                    "type": "text",
                    "title": "Nouvelle ressource",
                    "public": false,
                    "archived": false,
                    "draft": false,
                    "complete": null,
                    "metadata": [],
                    "keywords": [],
                    "content": {
                        "text": "Texte de la resource",
                        "object_type": "text"
                    },
                    "required_exercise_resources": null,
                    "required_knowledges": null
                },
                "picture": {
                    "type": "picture",
                    "title": "Nouvelle ressource",
                    "public": false,
                    "archived": false,
                    "draft": false,
                    "complete": null,
                    "metadata": [],
                    "keywords": [],
                    "content": {
                        "source": null,
                        "object_type": "picture"
                    },
                    "required_exercise_resources": null,
                    "required_knowledges": null
                },
                "multiple_choice_question": {
                    "type": "multiple-choice-question",
                    "title": "Nouvelle ressource",
                    "public": false,
                    "archived": false,
                    "draft": false,
                    "complete": null,
                    "metadata": [],
                    "keywords": [],
                    "content": {
                        "do_not_shuffle": true,
                        "question": "Question ressource QCM",
                        "propositions": [
                            {
                                "text": "Bonne réponse",
                                "right": true
                            }
                        ],
                        "comment": "Commentaire",
                        "max_number_of_propositions": 0,
                        "max_number_of_right_propositions": 0,
                        "object_type": "multiple_choice_question"
                    },
                    "required_exercise_resources": null,
                    "required_knowledges": null
                },
                "open_ended_question": {
                    "type": "open-ended-question",
                    "title": "Nouvelle ressource",
                    "public": false,
                    "archived": false,
                    "draft": false,
                    "complete": null,
                    "metadata": [],
                    "keywords": [],
                    "content": {
                        "question": "Question à réponse courte",
                        "solutions": [],
                        "comment": "Commentaire",
                        "object_type": "open_ended_question"
                    },
                    "required_exercise_resources": null,
                    "required_knowledges": null
                }
            }
        };

        $scope.resourceAddKeywordsField = function (collection) {
            var keyword = $("#resourceAddKeyword");
            collection.push(keyword[0].value);
            keyword[0].value = '';
        };

        $scope.resourceAddMetadataField = function (collection) {
            var key = $("#resourceAddMetadataKey"), val = $("#resourceAddMetadataValue");
            var newElement = {key: key[0].value, value: val[0].value};
            collection.splice(collection.length, 0, newElement);
            key[0].value = '';
            val[0].value = '';
        };

        $scope.resourceRemoveField = function (collection, index) {
            collection.splice(index, 1);
        };

        // load only once every necessary user
        $scope.loadUsers = function (targetScope, resourcesData) {
            targetScope.users = [];
            var userIds = [];
            for (var i = 0; i < resourcesData.length; ++i) {
                if (userIds.indexOf(resourcesData[i].author) == -1) {
                    userIds.push(resourcesData[i].author);
                }
                if (userIds.indexOf(resourcesData[i].owner) == -1) {
                    userIds.push(resourcesData[i].owner);
                }
            }

            for (i = 0; i < userIds.length; ++i) {
                targetScope.users[userIds[i]] = User.get({userId: userIds[i]});
            }
        };
    }]);

resourceControllers.controller('resourceListController', ['$scope', '$state', 'Resource', 'ResourceDuplication',
    function ($scope, $state, Resource, ResourceDuplication) {

        // retrieve resources
        if ($scope.parentSection !== 'model') {
            Resource.query(function (data) {
                // load an id indexed array of the resources
                $scope.resources = [];
                for (var i = 0; i < data.length; ++i) {
                    $scope.resources[data[i].id] = data[i];
                }

                $scope.loadUsers($scope, data);
            });
        }

        // delete resource method
        $scope.deleteResource = function (resource) {
            resource.$delete({id: resource.id}, function () {
                delete $scope.resources[resource.id];
            });
        };

        $scope.archiveResource = function (resource) {
            console.log('archiving...');
            var archived = new Resource;
            archived.archived = true;
            archived.$update({id: model.id}, function () {
                resource.archived = true;
            });
        };

        $scope.restoreResource = function (resource) {
            console.log('restoring...');
            var archived = new Resource;
            archived.archived = false;
            archived.$update({id: model.id}, function () {
                resource.archived = false;
            });
        };

        $scope.duplicateResource = function (resource) {
            ResourceDuplication.save({resourceId: resource.id}, function (data) {
                $scope.resources[data.id] = data;
                if ($scope.parentSection === 'model') {
                    $state.go('modelEdit.resourceEdit', {resourceid: data.id});
                } else {
                    $state.go('resourceEdit', {resourceid: data.id});
                }
            });
        };

        // create resource method
        $scope.createResource = function (type) {
            if (type == 'text') {
                Resource.save($scope.resourceContext.newResources.text, function (data) {
                    $scope.resources[data.id] = data;
                    if ($scope.parentSection === 'model') {
                        $state.go('modelEdit.resourceEdit', {resourceid: data.id});
                    } else {
                        $state.go('resourceEdit', {resourceid: data.id});
                    }
                });
            } else if (type == 'picture') {
                Resource.save($scope.resourceContext.newResources.picture, function (data) {
                    $scope.resources[data.id] = data;
                    if ($scope.parentSection === 'model') {
                        $state.go('modelEdit.resourceEdit', {resourceid: data.id});
                    } else {
                        $state.go('resourceEdit', {resourceid: data.id});
                    }
                });
            } else if (type == 'multiple-choice-question') {
                Resource.save($scope.resourceContext.newResources.multiple_choice_question, function (data) {
                    $scope.resources[data.id] = data;
                    if ($scope.parentSection === 'model') {
                        $state.go('modelEdit.resourceEdit', {resourceid: data.id});
                    } else {
                        $state.go('resourceEdit', {resourceid: data.id});
                    }
                });
            } else if (type == 'open-ended-question') {
                Resource.save($scope.resourceContext.newResources.open_ended_question, function (data) {
                    $scope.resources[data.id] = data;
                    if ($scope.parentSection === 'model') {
                        $state.go('modelEdit.resourceEdit', {resourceid: data.id});
                    } else {
                        $state.go('resourceEdit', {resourceid: data.id});
                    }
                });
            }
        };
    }]);

resourceControllers.filter('myFilters', function () {
    return function (collection, filters) {
        var items = [];
        var ids = [];
        angular.forEach(collection, function (value) {
            angular.forEach(filters.type, function (type) {
                if (value.type == type && ((filters.archived && value.archived) || !value.archived)) {
                    if (filters.keywords.length) {
                        if (value.keywords.length) {
                            angular.forEach(filters.keywords, function (keyword) {
                                if ($.inArray(keyword, value.keywords) != -1) {
                                    if (filters.metadata.length) {
                                        if (value.metadata.length) {
                                            angular.forEach(filters.metadata, function (meta1) {
                                                angular.forEach(value.metadata, function (meta2) {
                                                    if (meta1.key.toLowerCase() == meta2.key.toLowerCase() && meta1.value.toLowerCase() == meta2.value.toLowerCase()) {
                                                        if ($.inArray(value.id, ids) == -1) {
                                                            items.push(value);
                                                            ids.push(value.id)
                                                        }
                                                    }
                                                    if (meta1.key.toLowerCase() == meta2.key.toLowerCase() && meta1.value == '') {
                                                        if ($.inArray(value.id, ids) == -1) {
                                                            items.push(value);
                                                            ids.push(value.id)
                                                        }
                                                    }
                                                    if (meta1.value.toLowerCase() == meta2.value.toLowerCase() && meta1.key == '') {
                                                        if ($.inArray(value.id, ids) == -1) {
                                                            items.push(value);
                                                            ids.push(value.id)
                                                        }
                                                    }
                                                });
                                            });
                                        }
                                    } else {
                                        if ($.inArray(value.id, ids) == -1) {
                                            items.push(value);
                                            ids.push(value.id)
                                        }
                                    }
                                }
                            });
                        }
                    } else {
                        if (filters.metadata.length) {
                            if (value.metadata.length) {
                                angular.forEach(filters.metadata, function (meta1) {
                                    angular.forEach(value.metadata, function (meta2) {
                                        if (meta1.key.toLowerCase() == meta2.key.toLowerCase() && meta1.value.toLowerCase() == meta2.value.toLowerCase()) {
                                            if ($.inArray(value.id, ids) == -1) {
                                                items.push(value);
                                                ids.push(value.id)
                                            }
                                        }
                                        if (meta1.key.toLowerCase() == meta2.key.toLowerCase() && meta1.value == '') {
                                            if ($.inArray(value.id, ids) == -1) {
                                                items.push(value);
                                                ids.push(value.id)
                                            }
                                        }
                                        if (meta1.value.toLowerCase() == meta2.value.toLowerCase() && meta1.key == '') {
                                            if ($.inArray(value.id, ids) == -1) {
                                                items.push(value);
                                                ids.push(value.id)
                                            }
                                        }
                                    });
                                });
                            }
                        } else {
                            if ($.inArray(value.id, ids) == -1) {
                                items.push(value);
                                ids.push(value.id)
                            }
                        }
                    }
                }
            });
        });
        return items;
    };
});

resourceControllers.controller('resourceEditController', ['$scope', 'Resource', 'Upload', '$location', '$stateParams', 'User', '$upload',
    function ($scope, Resource, Upload, $location, $stateParams, User, $upload) {
        // retrieve resource
        $scope.resource = Resource.get({id: $stateParams.resourceid}, function (res) {
            if (typeof $scope.users === "undefined") {
                $scope.loadUsers($scope, [res]);
            }
        });

        // update resource method
        $scope.updateResource = function () {

            var keyword = $("#resourceAddKeyword");
            if (keyword[0].value != '') {
                $scope.resource.keywords.push(keyword[0].value);
                keyword[0].value = '';
            }

            var key = $("#resourceAddMetadataKey"), val = $("#resourceAddMetadataValue");
            if (key[0].value != '' && val[0].value != '') {
                var newElement = {key: key[0].value, value: val[0].value};
                $scope.resource.metadata.splice($scope.resource.metadata.length, 0, newElement);
                key[0].value = '';
                val[0].value = '';
            }

            delete $scope.resource.id;
            delete $scope.resource.type;
            delete $scope.resource.author;
            delete $scope.resource.owner;
            delete $scope.resource.required_exercise_resources;
            delete $scope.resource.required_knowledges;
            $scope.resource.$update({id: $stateParams.resourceid}, function (resource) {
                if (typeof $scope.resources === "object") {
                    $scope.resources[resource.id] = resource;
                }
            });
        };

        $scope.onFileSelect = function ($files) {
            var file = $files[0];
            $scope.upload = $upload.upload({
                url: BASE_CONFIG.urls.api.uploads,
                method: 'POST',
                // withCredentials: true,
                data: {myObj: $scope.myModelObj},
                file: file
            }).progress(function (evt) {
                    console.log('percent: ' + parseInt(100.0 * evt.loaded / evt.total));
                }).success(function (data, status, headers, config) {
                    // file is uploaded successfully
                    $scope.resource.content.source = data.fileName;
                });
        };

        // delete resource method
        $scope.deleteResource = function (resource) {
            resource.$delete({id: resource.id}, function () {
                if (typeof $scope.resources === "object") {
                    delete $scope.resources[resource.id];
                }
            });
        };

        $scope.removeFromCollection = function (collection, index) {
            collection.splice(index, 1);
        }

        $scope.addProposition = function (collection) {
            var newProposition = {"text": "Nouvelle proposition", "right": false};
            collection.splice(collection.length, 0, newProposition);
        }

        $scope.addSolution = function (collection) {
            var newSolution = $('#resourceAddSolution');
            collection.push(newSolution[0].value);
            newSolution[0].value = '';
        }

    }]);


var modelControllers = angular.module('modelControllers', ['ui.router']);

modelControllers.controller('modelController', ['$scope', 'ExerciseByModel', 'AttemptByExercise', '$routeParams', '$location', 'User',
    function ($scope, ExerciseByModel, AttemptByExercise, $routeParams, $location, User) {

        $scope.section = 'model';

        $scope.subSection;

        $scope.filters = {
            search: '',
            archived: false,
            type: {
                multiple_choice: 'multiple-choice', pair_items: 'pair-items', order_items: '', open_ended_question: 'open-ended-question', group_items: 'group-items'
            },
            keywords: [],
            metadata: []
        };

        $scope.modelContext = {
            "newModel": {
                "pair_items": {
                    "type": "pair-items",
                    "title": "nouvel appariement",
                    "public": false,
                    "archived": false,
                    "draft": false,
                    "complete": null,
                    "metadata": [],
                    "keywords": [],
                    "content": {
                        "wording": "Consigne de l'exercice",
                        "documents": [],
                        "pair_blocks": [
                            {
                                "is_list": true,
                                "number_of_occurrences": 0,
                                "resources": [],
                                "pair_meta_key": ""
                            }
                        ],
                        "exercise_model_type": "pair-items"
                    }
                },
                "sub_pair_items": {
                    "block_field": {
                        "is_list": true,
                        "number_of_occurrences": 0,
                        "resources": [],
                        "pair_meta_key": ""
                    }
                },
                "block_constraint": {
                    "exists": {"key": '', "values": [], "comparator": 'exists'},
                    "in": {"key": '', "values": [], "comparator": 'in'},
                    "between": {"key": '', "values": ['', ''], "comparator": 'between'},
                    "other": {"key": '', "values": [''], "comparator": ''}
                },
                "multiple_choice": {
                    "type": "multiple-choice",
                    "title": "Nouveau QCM",
                    "public": false,
                    "archived": false,
                    "draft": false,
                    "complete": null,
                    "metadata": [],
                    "keywords": [],
                    "content": {
                        "wording": "consigne",
                        "documents": [],
                        "question_blocks": [
                            {
                                "number_of_occurrences": 0,
                                "resources": [],
                                "is_list": true,
                                "max_number_of_propositions": 0,
                                "max_number_of_right_propositions": 0
                            }
                        ],
                        "shuffle_questions_order": true,
                        "exercise_model_type": "multiple-choice"
                    },
                    "required_exercise_resources": null,
                    "required_knowledges": null
                },
                "sub_multiple_choice": {
                    "block_field": {
                        "number_of_occurrences": 0,
                        "resources": [],
                        "is_list": true,
                        "max_number_of_propositions": 0,
                        "max_number_of_right_propositions": 0
                    }
                },
                "group_items": {
                    "type": "group-items",
                    "title": "Nouvelle ressource",
                    "public": true,
                    "archived": false,
                    "draft": false,
                    "complete": null,
                    "metadata": [],
                    "keywords": [],
                    "content": {
                        "wording": "Consigne",
                        "documents": [],
                        "object_blocks": [
                            {
                                "number_of_occurrences": 0,
                                "resources": [],
                                "is_list": true
                            }
                        ],
                        "display_group_names": "ask",
                        "classif_constr": {
                            "other": "own",
                            "meta_keys": [],
                            "groups": []
                        },
                        "exercise_model_type": "group-items"
                    },
                    "required_exercise_resources": null,
                    "required_knowledges": null
                },
                "sub_group_items": {
                    "block_field": {
                        "number_of_occurrences": 0,
                        "resources": [],
                        "is_list": true
                    }
                },
                "open_ended_question": {
                    "type": "open-ended-question",
                    "title": "Nouvelle ressource",
                    "public": false,
                    "archived": false,
                    "draft": false,
                    "complete": null,
                    "metadata": [],
                    "keywords": [],
                    "content": {
                        "wording": "consigne",
                        "documents": [],
                        "question_blocks": [
                            {
                                "number_of_occurrences": 0,
                                "resources": [],
                                "is_list": true
                            }
                        ],
                        "shuffle_questions_order": true,
                        "exercise_model_type": "open-ended-question"
                    },
                    "required_exercise_resources": null,
                    "required_knowledges": null
                }
            }
        };

        $scope.modelAddKeywordsField = function (collection) {
            var keyword = $('#modelAddKeyword');
            collection.push(keyword[0].value);
            keyword[0].value = '';
        }

        $scope.modelAddMetadataField = function (collection) {
            var key = $("#modelAddMetadataKey"), val = $("#modelAddMetadataValue");
            var newElement = {key: key[0].value, value: val[0].value};
            collection.splice(collection.length, 0, newElement);
            key[0].value = '';
            val[0].value = '';
        }

//        $scope.modelAddBlockField = function (collection) {
//            var newElement = {
//                "number_of_occurrences": 0,
//                "resources": [],
//                "resource_constraint": {
//                    "metadata_constraints": [],
//                    "excluded": []
//                },
//                "pair_meta_key": ""
//            };
//            collection.splice(collection.length, 0, newElement);
//        }

        $scope.modelAddBlockResourceField = function (collection, id) {
            var isAlreadyAdded = false;
            angular.forEach(collection, function (res) {
                if (res.id == id) {
                    isAlreadyAdded = true;
                }
            });
            if (!isAlreadyAdded) {
                collection.splice(collection.length, 0, {"id": id});
            }
        };

        $scope.modelAddBlockResourceConstraint = function (metadata_constraints, type) {
            var newElement;
            if (type == 'exists') {
                newElement = $scope.modelContext.newModel.block_constraint.exists;
            } else if (type == 'in') {
                newElement = $scope.modelContext.newModel.block_constraint.in;
            } else if (type == 'between') {
                newElement = $scope.modelContext.newModel.block_constraint.between;
            } else {
                newElement = $scope.modelContext.newModel.block_constraint.other;
                newElement.comparator = type;
            }
            metadata_constraints.splice(metadata_constraints.length, 0, newElement);
        };

        $scope.modelAddBlockResourceConstraintValue = function (collection) {
            var constrainsInValue = $('#constrainsInValue');
            collection.push(constrainsInValue[0].value);
            constrainsInValue[0].value = '';
        };

        $scope.modelRemoveField = function (collection, index) {
            collection.splice(index, 1);
        };

        $scope.viewAttempt = function (attempt) {
            $location.path("/learner/attempt/" + attempt.id);
        };

        $scope.tryExercise = function (exercise) {
            // create attempt from exercise
            attempt = AttemptByExercise.create({exerciseId: exercise.id},
                function (attempt) {
                    $scope.viewAttempt(attempt);
                });
        };

        $scope.tryModel = function (model) {
            // create exercise from model
            exercise = ExerciseByModel.try({modelId: model.id},
                function (exercise) {
                    $scope.tryExercise(exercise);
                });
        };

        // load only once every necessary user
        $scope.loadUsers = function (targetScope, resourcesData) {
            targetScope.users = [];
            var userIds = [];
            for (var i = 0; i < resourcesData.length; ++i) {
                if (userIds.indexOf(resourcesData[i].author) == -1) {
                    userIds.push(resourcesData[i].author);
                }
                if (userIds.indexOf(resourcesData[i].owner) == -1) {
                    userIds.push(resourcesData[i].owner);
                }
            }

            for (i = 0; i < userIds.length; ++i) {
                targetScope.users[userIds[i]] = User.get({userId: userIds[i]});
            }
        };

    }]);

modelControllers.controller('modelListController', ['$scope', 'Model', 'ModelDuplication', '$location',
    function ($scope, Model, ModelDuplication, $location) {

        $scope.models = Model.query(function () {
            $scope.loadUsers($scope, $scope.models);
        });

        $scope.deleteModel = function (model) {
            model.$delete({id: model.id}, function () {
                $scope.models = Model.query();
            });
        };

        $scope.archiveModel = function (model) {
            console.log('archiving...');
            var archived = new Model;
            archived.archived = true;
            archived.$update({id: model.id}, function () {
                model.archived = true;
            });
        };

        $scope.restoreModel = function (model) {
            console.log('restoring...');
            var archived = new Model;
            archived.archived = false;
            archived.$update({id: model.id}, function () {
                model.archived = false;
            });
        };

        $scope.createModel = function (type) {
            if (type == 'pair-items') {
                Model.save($scope.modelContext.newModel.pair_items, function (data) {
                    $location.path('/teacher/model/' + data.id)
                });
            } else if (type == 'multiple-choice') {
                Model.save($scope.modelContext.newModel.multiple_choice, function (data) {
                    $location.path('/teacher/model/' + data.id)
                });
            } else if (type == 'group-items') {
                Model.save($scope.modelContext.newModel.group_items, function (data) {
                    $location.path('/teacher/model/' + data.id)
                });
            } else if (type == 'open-ended-question') {
                Model.save($scope.modelContext.newModel.open_ended_question, function (data) {
                    $location.path('/teacher/model/' + data.id)
                });
            }
        };

        $scope.duplicateModel = function (model) {
            ModelDuplication.save({modelId: model.id}, function (data) {
                $location.path('/teacher/model/' + data.id)
            });
        };
    }]);

modelControllers.controller('modelEditController', ['$scope', 'Model', 'Resource', '$location', '$stateParams', 'User',
    function ($scope, Model, Resource, $location, $stateParams, User) {

        // load resources
        Resource.query(function (data) {
            // load an id indexed array of the resources
            $scope.resources = [];
            for (var i = 0; i < data.length; ++i) {
                $scope.resources[data[i].id] = data[i];
            }

            // load users
            $scope.loadUsers($scope, data);

            // load model
            $scope.model = Model.get({id: $stateParams.modelid}, function () {
                // fill each block with empty constraints
                $scope.fillBlockConstraints($scope.model);
                $scope.$parent.subSection = $scope.model.type;
            });
        });

        $scope.fillBlockConstraints = function (model) {
            switch (model.type) {
                case 'pair-items':
                    $scope.fillConstraints(model.content.pair_blocks);
                    break;
                case 'order-items':
                    $scope.fillConstraints(model.content.object_blocks);
                    break;
                case 'group-items':
                    $scope.fillConstraints(model.content.object_blocks);
                    break;
                case 'multiple-choice':
                    $scope.fillConstraints(model.content.question_blocks);
                    break;
                case 'open-ended-question':
                    $scope.fillConstraints(model.content.question_blocks);
                    break;
            }
        };

        $scope.fillConstraints = function (blocks) {
            for (i = 0; i < blocks.length; ++i) {
                if (typeof blocks[i].resource_constraint === "undefined") {
                    blocks[i].resource_constraint = {
                        "metadata_constraints": [],
                        "excluded": []
                    };
                }
                if (typeof blocks[i].resource_constraint.metadata_constraints === "undefined") {
                    blocks[i].resource_constraint.metadata_constraints = [];
                }
                if (typeof blocks[i].resource_constraint.excluded === "undefined") {
                    blocks[i].resource_constraint.excluded = [];
                }
            }
        };

        $scope.saveAndTry = function () {
            $scope.preUpdate();
            $scope.model.$update({id: $stateParams.modelid}, function (model) {
                if (model.complete) {
                    $scope.tryModel(model);
                }
            });
        };

        $scope.preUpdate = function () {
            var keyword = $("#modelAddKeyword");
            if (keyword[0].value != '') {
                $scope.model.keywords.push(keyword[0].value);
                keyword[0].value = '';
            }

            var key = $("#modelAddMetadataKey"), val = $("#modelAddMetadataValue");
            if (key[0].value != '' && val[0].value != '') {
                var newElement = {key: key[0].value, value: val[0].value};
                $scope.model.metadata.splice($scope.model.metadata.length, 0, newElement);
                key[0].value = '';
                val[0].value = '';
            }

            delete $scope.model.id;
            delete $scope.model.author;
            delete $scope.model.owner;
            delete $scope.model.required_exercise_resources;
            delete $scope.model.required_knowledges;
        };

        $scope.updateModel = function () {
            $scope.preUpdate();
            $scope.model.$update({id: $stateParams.modelid}, function (model) {
            });
        };

        $scope.onDropMetadataKey = function (event, metakey, collection, field) {
            collection[field] = metakey;
        };

        $scope.deleteModel = function (model) {
            model.$delete({id: model.id}, function () {
            });
        };

        $scope.usedDocuments = [];

        $scope.onDropDocument = function (event, resource, documents) {
            if (resource.type == 'text' || resource.type == 'picture') {
                $scope.modelAddBlockResourceField(documents, resource.id);
            }
        };

        $scope.getMobilePart = function (collection, key) {
            var returnValue = '';
            angular.forEach(collection.metadata, function (meta) {
                if (meta.key == key) {
                    returnValue = meta.value;
                }
            });
            if (returnValue != '') {
                return collection.title + ' (' + returnValue + ')'
            } else {
                return collection.title;
            }
        };

        $scope.openFirstBlocks = {};

        $scope.openFirst = function (selector, index) {
            if (!$scope.openFirstBlocks.hasOwnProperty(selector)) {
                $scope.openFirstBlocks[selector] = [];
            }
            if (index == 0) {
                $scope.openFirstBlocks[selector].splice(index, 0, true);
            } else {
                $scope.openFirstBlocks[selector].splice(index, 0, false);
            }
        };
    }]);

modelControllers.controller('modelEditPairItemsController', ['$scope', 'Model', 'Resource', '$location', '$stateParams', 'User', function ($scope, Model, Resource, $location, $stateParams, User) {

    $scope.modelAddBlockField = function (collection) {
        collection.splice(collection.length, 0, $scope.modelContext.newModel.sub_pair_items.block_field);
    }

    $scope.onDropResourceToBlock = function (event, resource, collection) {
        if ($scope.model.type == 'pair-items') {
            if (resource.type == 'text' || resource.type == 'picture') {
                $scope.modelAddBlockResourceField(collection, resource.id);
            }
        }
    };

    $scope.initResourceConstraints = function (block) {
        if (!block.hasOwnProperty('resource_constraint')) {
            block.resource_constraint = {};
        }
        if (!block.resource_constraint.hasOwnProperty('metadata_constraints')) {
            block.resource_constraint.metadata_constraints = [];
        }
        if (!block.resource_constraint.hasOwnProperty('excluded')) {
            block.resource_constraint.excluded = [];
        }
    }

}]);

modelControllers.controller('modelEditMultipleChoiceController', ['$scope', 'Model', 'Resource', '$location', '$stateParams', 'User', function ($scope, Model, Resource, $location, $stateParams, User) {

    $scope.modelAddBlockField = function (collection) {
        collection.splice(collection.length, 0, $scope.modelContext.newModel.sub_multiple_choice.block_field);
    }

    $scope.onDropResourceToBlock = function (event, resource, collection) {
        if ($scope.model.type == 'multiple-choice') {
            if (resource.type == 'text' || resource.type == 'picture' || resource.type == 'multiple-choice-question') {
                $scope.modelAddBlockResourceField(collection, resource.id);
            }
        }
    };

    $scope.initResourceConstraints = function (block) {
        if (!block.hasOwnProperty('resource_constraint')) {
            block.resource_constraint = {};
        }
        if (!block.resource_constraint.hasOwnProperty('type')) {
            block.resource_constraint.type = 'multiple-choice-question';
        }
        if (!block.resource_constraint.hasOwnProperty('metadata_constraints')) {
            block.resource_constraint.metadata_constraints = [];
        }
        if (!block.resource_constraint.hasOwnProperty('excluded')) {
            block.resource_constraint.excluded = [];
        }
    }

}]);

modelControllers.controller('modelEditGroupItemsController', ['$scope', 'Model', 'Resource', '$location', '$stateParams', 'User', function ($scope, Model, Resource, $location, $stateParams, User) {

    $scope.modelAddBlockField = function (collection) {
        collection.splice(collection.length, 0, $scope.modelContext.newModel.sub_group_items.block_field);
    }

    $scope.onDropResourceToBlock = function (event, resource, collection) {
        if ($scope.model.type == 'group-items') {
            if (resource.type == 'text' || resource.type == 'picture') {
                $scope.modelAddBlockResourceField(collection, resource.id);
            }
        }
    };

    $scope.initResourceConstraints = function (block) {
        if (!block.hasOwnProperty('resource_constraint')) {
            block.resource_constraint = {};
        }
        if (!block.resource_constraint.hasOwnProperty('type')) {
            block.resource_constraint.type = 'text';
        }
        if (!block.resource_constraint.hasOwnProperty('metadata_constraints')) {
            block.resource_constraint.metadata_constraints = [];
        }
        if (!block.resource_constraint.hasOwnProperty('excluded')) {
            block.resource_constraint.excluded = [];
        }
    }

    $scope.modelAddBlockResourceConstraint = function (metadata_constraints, type) {
        var newElement;
        if (type == 'exists') {
            newElement = $scope.$parent.modelContext.newModel.block_constraint.exists;
        } else if (type == 'in') {
            newElement = $scope.$parent.modelContext.newModel.block_constraint.in;
        } else if (type == 'between') {
            newElement = $scope.$parent.modelContext.newModel.block_constraint.between;
        } else {
            newElement = $scope.$parent.modelContext.newModel.block_constraint.other;
            newElement.comparator = type;
        }
        metadata_constraints.splice(metadata_constraints.length, 0, newElement);
    };

}]);

modelControllers.controller('modelEditOpenEndedQuestionController', ['$scope', 'Model', 'Resource', '$location', '$stateParams', 'User', function ($scope, Model, Resource, $location, $stateParams, User) {

    $scope.modelAddBlockField = function (collection) {
        collection.splice(collection.length, 0, $scope.modelContext.newModel.sub_group_items.block_field);
    }

    $scope.onDropResourceToBlock = function (event, resource, collection) {
        if ($scope.model.type == 'open-ended-question') {
            if (resource.type == 'open-ended-question') {
                $scope.modelAddBlockResourceField(collection, resource.id);
            }
        }
    };

    $scope.initResourceConstraints = function (block) {
        if (!block.hasOwnProperty('resource_constraint')) {
            block.resource_constraint = {};
        }
        if (!block.resource_constraint.hasOwnProperty('type')) {
            block.resource_constraint.type = 'text';
        }
        if (!block.resource_constraint.hasOwnProperty('metadata_constraints')) {
            block.resource_constraint.metadata_constraints = [];
        }
        if (!block.resource_constraint.hasOwnProperty('excluded')) {
            block.resource_constraint.excluded = [];
        }
    }

    $scope.modelAddBlockResourceConstraint = function (metadata_constraints, type) {
        var newElement;
        if (type == 'exists') {
            newElement = $scope.$parent.modelContext.newModel.block_constraint.exists;
        } else if (type == 'in') {
            newElement = $scope.$parent.modelContext.newModel.block_constraint.in;
        } else if (type == 'between') {
            newElement = $scope.$parent.modelContext.newModel.block_constraint.between;
        } else {
            newElement = $scope.$parent.modelContext.newModel.block_constraint.other;
            newElement.comparator = type;
        }
        metadata_constraints.splice(metadata_constraints.length, 0, newElement);
    };

}]);
