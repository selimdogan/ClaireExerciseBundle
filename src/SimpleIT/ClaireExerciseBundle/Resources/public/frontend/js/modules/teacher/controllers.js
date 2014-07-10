/**
 * Created by bryan on 25/06/14.
 */

var resourceControllers = angular.module('resourceControllers', ['ui.router']);

resourceControllers.controller('resourceController', ['$scope', '$routeParams', '$location',
    function ($scope, $routeParams, $location) {

        $scope.section = 'resource';

        $scope.filters = {
            search: '',
            archived: false,
            type: {
                multiple_choice_question: '', text: 'text', picture: 'picture', open_ended_question: '', sequence: ''
            },
            keywords: [],
            metadata: []
        };

        if ($scope.$parent.section === undefined) {
            $scope.parentSection = '';
        } else {
            $scope.parentSection = $scope.$parent.section;
        }

        // new resource text
        $scope.newTextResource = {
            "type": "text",
            "title": "nouvelle ressource",
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
        };

        // new resource picture
        $scope.newPictureResource = {
            "type": "picture",
            "title": "nouvelle ressource",
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
        };

        $scope.resourceAddKeywordsField = function (collection) {
            var keyword = $("#resourceAddKeyword");
            collection.push(keyword[0].value);
            keyword[0].value = '';
        }

        $scope.resourceAddMetadataField = function (collection) {
            var key = $("#resourceAddMetadataKey"), val = $("#resourceAddMetadataValue");
            var newElement = {key: key[0].value, value: val[0].value};
            collection.splice(collection.length, 0, newElement);
            key[0].value = '';
            val[0].value = '';
        }

        $scope.resourceRemoveField = function (collection, index) {
            collection.splice(index, 1);
        }

    }]);

resourceControllers.controller('resourceListController', ['$scope', 'Resource', '$location', '$http', 'User', function ($scope, Resource, $location, $http, User) {

    // retrieve resources
    if ($scope.parentSection !== 'model') {
        $scope.resources = Resource.query(function (data) {
            $scope.users = [];
            $scope.loadUsers();
        });
    }

    // delete resource method
    $scope.deleteResource = function (resource) {
        resource.$delete({id: resource.id}, function () {
            $scope.resources = Resource.query();
        });
    };

    // create resource method
    $scope.createResource = function (type) {
        if (type == 'text') {
            Resource.save($scope.newTextResource, function (data) {
                $location.path('/teacher/resource/' + data.id)
            });
        } else if (type == 'picture') {
            Resource.save($scope.newPictureResource, function (data) {
                $location.path('/teacher/resource/' + data.id)
            });
        }
    };

    // load only once every necessary user
    $scope.loadUsers = function () {
        userIds = [];
        for (i = 0; i < $scope.resources.length; ++i) {
            if (userIds.indexOf($scope.resources[i].author) == -1) {
                userIds.push($scope.resources[i].author);
            }
            if (userIds.indexOf($scope.resources[i].owner) == -1) {
                userIds.push($scope.resources[i].owner);
            }
        }

        $scope.users = [];
        for (i = 0; i < userIds.length; ++i) {
            $scope.users[userIds[i]] = User.get({userId: userIds[i]});
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

resourceControllers.controller('resourceEditController', ['$scope', 'Resource', 'Upload', '$location', '$stateParams', 'User', '$upload', function ($scope, Resource, Upload, $location, $stateParams, User, $upload) {

    $scope.users = [];

    // retrieve resource
    $scope.resource = Resource.get({id: $stateParams.resourceid}, function (res) {
        User.get({userId: res.author}, function (user) {
            $scope.users[user.id] = user.user_name;
        })
        User.get({userId: res.owner}, function (user) {
            $scope.users[user.id] = user.user_name;
        })
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

        });
    };

}]);


var modelControllers = angular.module('modelControllers', ['ui.router']);

modelControllers.controller('modelController', ['$scope', 'ExerciseByModel', 'AttemptByExercise', '$routeParams', '$location',
    function ($scope, ExerciseByModel, AttemptByExercise, $routeParams, $location) {

        $scope.section = 'model';

        $scope.filters = {
            search: '',
            archived: false,
            type: {
                multiple_choice: '', pair_items: 'pair-items', order_items: '', open_ended_question: '', group_items: ''
            },
            keywords: [],
            metadata: []
        };

        $scope.newPairItemsModel = {
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
                        "number_of_occurrences": 0,
                        "resources": [],
                        "pair_meta_key": ""
                    }
                ],
                "exercise_model_type": "pair-items"
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

        $scope.modelAddBlockField = function (collection) {
            var newElement = {
                "number_of_occurrences": 0,
                "resources": [],
                "resource_constraint": {
                    "metadata_constraints": [],
                    "excluded": []
                },
                "pair_meta_key": ""
            };
            collection.splice(collection.length, 0, newElement);
        }

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
        }

        $scope.modelAddBlockResourceConstraint = function (pair_blocks, type) {
            var newElement;
            if (type == 'exists') {
                newElement = {"key": '', "values": [], "comparator": 'exists'};
            } else if (type == 'in') {
                newElement = {"key": '', "values": [], "comparator": 'in'};
            } else if (type == 'between') {
                newElement = {"key": '', "values": ['', ''], "comparator": 'between'};
            } else {
                newElement = {"key": '', "values": [''], "comparator": type};
            }
            pair_blocks.resource_constraint.metadata_constraints.splice(pair_blocks.resource_constraint.metadata_constraints.length, 0, newElement);
        }

        $scope.modelAddBlockResourceConstraintValue = function (collection) {
            var constrainsInValue = $('#constrainsInValue');
            collection.push(constrainsInValue[0].value);
            constrainsInValue[0].value = '';
        }

        $scope.modelRemoveField = function (collection, index) {
            collection.splice(index, 1);
        }

        $scope.initResourceConstraints = function (pair_blocks) {
            if (!pair_blocks.hasOwnProperty('resourceConstraint')) {
                pair_blocks.resourceConstraint = {type: 'text'};
                pair_blocks.resourceConstraint.metadataConstraints = [];
                pair_blocks.resourceConstraint.excluded = [];
            }
            if (!pair_blocks.resourceConstraint.hasOwnProperty('metadataConstraints')) {
                pair_blocks.resourceConstraint.metadataConstraints = [];
            }
            if (!pair_blocks.resourceConstraint.hasOwnProperty('excluded')) {
                pair_blocks.resourceConstraint.excluded = [];
            }
        }

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

modelControllers.controller('modelListController', ['$scope', 'Model', '$location', 'User', function ($scope, Model, $location, User) {

    $scope.users = [];

    $scope.models = Model.query(function (model) {
        $scope.loadUsers();
    });

    $scope.deleteModel = function (model) {
        model.$delete({id: model.id}, function () {
            $scope.models = Model.query();
        });
    };

    $scope.createModel = function (type) {
        if (type == 'pair-items') {
            Model.save($scope.newPairItemsModel, function (data) {
                $location.path('/teacher/model/' + data.id)
            });
        }
    };

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

}]);

modelControllers.controller('modelEditController', ['$scope', 'Model', 'Resource', '$location', '$stateParams', 'User', function ($scope, Model, Resource, $location, $stateParams, User) {

    $scope.users = [];

    // load resources
    Resource.query(function (data) {
        // load an id indexed array of the resources
        $scope.resources = [];
        for (var i = 0; i < data.length; ++i) {
            $scope.resources[data[i].id] = data[i];
        }

        // load users
        $scope.loadUsers(data);

        // load model
        $scope.model = Model.get({id: $stateParams.modelid}, function () {
            // fill each blovk with empty constraints
            for (i = 0; i < $scope.model.content.pair_blocks.length; ++i) {
                if (typeof $scope.model.content.pair_blocks[i].resource_constraint === "undefined") {
                    $scope.model.content.pair_blocks[i].resource_constraint = {
                        "metadata_constraints": [],
                        "excluded": []
                    };
                }
                if (typeof $scope.model.content.pair_blocks[i].resource_constraint.metadata_constraints === "undefined") {
                    $scope.model.content.pair_blocks[i].resource_constraint.metadata_constraints = [];
                }
                if (typeof $scope.model.content.pair_blocks[i].resource_constraint.excluded === "undefined") {
                    $scope.model.content.pair_blocks[i].resource_constraint.excluded = [];
                }
            }


        });
    });

    $scope.usedDocuments = [];
    $scope.usedResources = [];
    $scope.excludedResources = [];

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

    $scope.onDropResourceToBlock = function (event, resource, collection) {
        if ($scope.model.type == 'pair-items') {
            if (resource.type == 'text' || resource.type == 'picture') {
                $scope.modelAddBlockResourceField(collection, resource.id);
            }
        }
    };

    $scope.onDropMetadataKey = function (event, metakey, collection, field) {
        collection[field] = metakey;
        console.log(metakey);
    };

    $scope.onDropDocument = function (event, resource, documents) {
        if ($scope.model.type == 'pair-items') {
            if (resource.type == 'text' || resource.type == 'picture') {
                $scope.modelAddBlockResourceField(documents, resource.id);
            }
        }
    };

    $scope.getResourceInfo = function (blockid, resourceid) {
        $scope.usedResources[blockid][resourceid] = $scope.resources[resourceid];
    };

    $scope.getExcludedResourceInfo = function (blockid, resourceid) {
        $scope.excludedResources[blockid][resourceid] = $scope.resources[resourceid];
    };

    $scope.getDocumentInfo = function (documentId) {
        $scope.usedDocuments[documentId] = $scope.resources[documentId];
    };

    $scope.getMobilPart = function (collection, key) {
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

    $scope.deleteModel = function (model) {
        model.$delete({id: model.id}, function () {
        });
    };

    // load only once every necessary user
    $scope.loadUsers = function (resourcesData) {
        userIds = [];
        for (i = 0; i < resourcesData.length; ++i) {
            if (userIds.indexOf(resourcesData[i].author) == -1) {
                userIds.push(resourcesData[i].author);
            }
            if (userIds.indexOf(resourcesData[i].owner) == -1) {
                userIds.push(resourcesData[i].owner);
            }
        }

        for (i = 0; i < userIds.length; ++i) {
            $scope.users[userIds[i]] = User.get({userId: userIds[i]},
                function () {
                    console.log($scope.users);
                });
        }
    };
}]);
