/**
 * Created by bryan on 25/06/14.
 */

var resourceControllers = angular.module('resourceControllers', ['ui.router']);

resourceControllers.controller('resourceController', ['$scope', '$routeParams', '$location',
    function($scope, $routeParams, $location) {

        $scope.section = 'resource';

        $scope.filters = {
            search: '',
            archived: false,
            type: {
                multiple_choice_question: ''
                ,text: 'text'
                ,picture: 'picture'
                ,open_ended_question: ''
                ,sequence: ''
            },
            keywords: [],
            metadata: []
        };

        if($scope.$parent.section === undefined){$scope.parentSection = '';}else{$scope.parentSection = $scope.$parent.section;}

        // new resource text
        $scope.newTextResource = {
            "type": "text",
            "title": "nouvelle ressource",
            "public": true,
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
            "public": true,
            "archived": false,
            "draft": false,
            "complete": null,
            "metadata": [],
            "keywords": [],
            "content": {
                "source": "img1.jpeg",
                "object_type": "picture"
            },
            "required_exercise_resources": null,
            "required_knowledges": null
        };

        $scope.resourceAddKeywordsField = function (collection){
            var keyword = $("#resourceAddKeyword");
            collection.push(keyword[0].value);
            keyword[0].value = '';
        }

        $scope.resourceAddMetadataField = function (collection){
            var key = $("#resourceAddMetadataKey"), val = $("#resourceAddMetadataValue");
            var newElement = {key: key[0].value, value: val[0].value};
            collection.splice(collection.length, 0, newElement);
            key[0].value = ''; val[0].value = '';
        }

        $scope.resourceRemoveField = function (collection, index){
            collection.splice(index, 1);
        }

    }]);

resourceControllers.controller('resourceListController', ['$scope', 'Resource', '$location', '$http', function($scope, Resource, $location, $http) {

    // retrieve resources
    $scope.resources = Resource.query();

    // delete resource method
    $scope.deleteResource = function (resource) {
        resource.$delete({id:resource.id}, function () {
            $scope.resources = Resource.query();
        });
    };

    // create resource method
    $scope.createResource = function (type) {
        if(type == 'text'){
            Resource.save($scope.newTextResource, function(data){
                $location.path('/teacher/resource/'+data.id)
            });
        }else if(type == 'picture'){
            Resource.save($scope.newPictureResource, function(data){
                $location.path('/teacher/resource/'+data.id)
            });
        }
    };

}]);

resourceControllers.filter('myFilters', function () {
    return function (collection, filters) {
        var items = [];
        var ids = [];
        angular.forEach(collection, function (value) {
            angular.forEach(filters.type, function(type){
                if(value.type == type && ((filters.archived && value.archived) || !value.archived)){
                    if(filters.keywords.length){
                        if(value.keywords.length){
                            angular.forEach(filters.keywords, function(keyword){
                                if($.inArray(keyword, value.keywords) != -1){
                                    if(filters.metadata.length){
                                        if(value.metadata.length){
                                            angular.forEach(filters.metadata, function(meta1){
                                                angular.forEach(value.metadata, function(meta2){
                                                    if(meta1.key.toLowerCase() == meta2.key.toLowerCase() && meta1.value.toLowerCase() == meta2.value.toLowerCase()){if($.inArray(value.id, ids) == -1){items.push(value); ids.push(value.id)}}
                                                    if(meta1.key.toLowerCase() == meta2.key.toLowerCase() && meta1.value == ''){if($.inArray(value.id, ids) == -1){items.push(value); ids.push(value.id)}}
                                                    if(meta1.value.toLowerCase() == meta2.value.toLowerCase() && meta1.key == ''){if($.inArray(value.id, ids) == -1){items.push(value); ids.push(value.id)}}
                                                });
                                            });
                                        }
                                    }else{
                                        if($.inArray(value.id, ids) == -1){items.push(value); ids.push(value.id)}
                                    }
                                }
                            });
                        }
                    }else{
                        if(filters.metadata.length){
                            if(value.metadata.length){
                                angular.forEach(filters.metadata, function(meta1){
                                    angular.forEach(value.metadata, function(meta2){
                                        if(meta1.key.toLowerCase() == meta2.key.toLowerCase() && meta1.value.toLowerCase() == meta2.value.toLowerCase()){if($.inArray(value.id, ids) == -1){items.push(value); ids.push(value.id)}}
                                        if(meta1.key.toLowerCase() == meta2.key.toLowerCase() && meta1.value == ''){if($.inArray(value.id, ids) == -1){items.push(value); ids.push(value.id)}}
                                        if(meta1.value.toLowerCase() == meta2.value.toLowerCase() && meta1.key == ''){if($.inArray(value.id, ids) == -1){items.push(value); ids.push(value.id)}}
                                    });
                                });
                            }
                        }else{
                            if($.inArray(value.id, ids) == -1){items.push(value); ids.push(value.id)}
                        }
                    }
                }
            });
        });
        return items;
    };
});

resourceControllers.controller('resourceDisplayController', ['$scope', 'Resource', '$location', '$stateParams', function($scope, Resource, $location, $stateParams) {

    $scope.resource = Resource.get({id:$stateParams.resourceid});

}]);

resourceControllers.controller('resourceEditController', ['$scope', 'Resource', '$location', '$stateParams', function($scope, Resource, $location, $stateParams) {

    // retrieve resource
    $scope.resource = Resource.get({id:$stateParams.resourceid});

    // update resource method
    $scope.updateResource = function () {
        delete $scope.resource.id;
        delete $scope.resource.type;
        delete $scope.resource.author;
        delete $scope.resource.owner;
        delete $scope.resource.required_exercise_resources;
        delete $scope.resource.required_knowledges;
        $scope.resource.$update({id:$stateParams.resourceid},function (resource) {});
    };

}]);


var modelControllers = angular.module('modelControllers', ['ui.router']);

modelControllers.controller('modelController', ['$scope', '$routeParams', '$location',
    function($scope, $routeParams, $location) {

        $scope.section = 'model';

        $scope.filters = {
            search: '',
            archived: false,
            type: {
                multiple_choice: ''
                ,pair_items: 'pair-items'
                ,order_items: ''
                ,open_ended_question: ''
                ,group_items: ''
            },
            keywords: [],
            metadata: []
        };

        $scope.newPairItemsModel = {
            "type": "pair-items",
            "title": "nouvel appariement",
            "public": true,
            "archived": false,
            "draft": false,
            "complete": null,
            "metadata": [],
            "keywords": [],
            "content": {
                "wording": "1",
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

        $scope.modelAddKeywordsField = function (collection){
            var keyword = $('#modelAddKeyword');
            collection.push(keyword[0].value);
            keyword[0].value = '';
        }

        $scope.modelAddMetadataField = function (collection){
            var key = $("#modelAddMetadataKey"), val = $("#modelAddMetadataValue");
            var newElement = {key: key[0].value, value: val[0].value};
            collection.splice(collection.length, 0, newElement);
            key[0].value = ''; val[0].value = '';
        }

        $scope.modelAddBlockField = function (collection){
            var newElement = {
                "number_of_occurrences": 0,
                "resources": [],
                "pair_meta_key": ""
            };
            collection.splice(collection.length, 0, newElement);
        }

        $scope.modelAddBlockResourceField = function (collection, id){
            var isAlreadyAdded = false;
            angular.forEach(collection, function(res){
                if(res.id == id){isAlreadyAdded = true;}
            });
            if(!isAlreadyAdded){
                collection.splice(collection.length, 0, {"id": id});
            }
        }

        $scope.modelAddBlockResourceConstraint = function (collection, type){
            if(collection === undefined){collection = [];}
            var newElement;
            if(type == 'exists'){
                newElement = {"key": '',"values": [],"comparator": 'exists'};
            }else if(type == 'in'){
                newElement = {"key": '',"values": [],"comparator": 'in'};
            }else if(type == 'between'){
                newElement = {"key": '',"values": ['', ''],"comparator": 'between'};
            }else{
                newElement = {"key": '',"values": [''],"comparator": type};
            }
            collection.splice(collection.length, 0, newElement);
        }

        $scope.modelAddBlockResourceConstraintValue = function (collection){
            var constrainsInValue = $('#constrainsInValue');
            collection.push(constrainsInValue[0].value);
            constrainsInValue[0].value = '';
        }

        $scope.modelRemoveField = function (collection, index){
            collection.splice(index, 1);
        }

        $scope.initResourceConstraints = function(pair_blocks){
            if(!pair_blocks.hasOwnProperty('resourceConstraint')){
                pair_blocks.resourceConstraint = {type: 'text'};
                pair_blocks.resourceConstraint.metadataConstraints = [];
                pair_blocks.resourceConstraint.excluded = [];
            }
            if(!pair_blocks.resourceConstraint.hasOwnProperty('metadataConstraints')){
                pair_blocks.resourceConstraint.metadataConstraints = [];
            }
            if(!pair_blocks.resourceConstraint.hasOwnProperty('excluded')){
                pair_blocks.resourceConstraint.excluded = [];
            }
        }

    }]);

modelControllers.controller('modelListController', ['$scope', 'Model', '$location', function($scope, Model, $location) {

    $scope.models = Model.query();

    $scope.deleteModel = function (model) {
        model.$delete({id:model.id}, function () {
            $scope.models = Model.query();
        });
    };

    $scope.createModel = function (type) {
        if(type == 'pair-items'){
            Model.save($scope.newPairItemsModel, function(data){
                $location.path('/teacher/model/'+data.id)
            });
        }
    };

}]);

modelControllers.controller('modelEditController', ['$scope', 'Model', 'Resource', '$location', '$stateParams', function($scope, Model, Resource, $location, $stateParams) {

    $scope.model = Model.get({id:$stateParams.modelid});

    $scope.usedDocuments = [];
    $scope.usedResources = [];
    $scope.excludedResources = [];

    $scope.updateModel = function () {
        delete $scope.model.id;
        delete $scope.model.author;
        delete $scope.model.owner;
        delete $scope.model.required_exercise_resources;
        delete $scope.model.required_knowledges;
        $scope.model.$update({id:$stateParams.modelid},function (model) {});
    };

    $scope.onDropResourceToBlock = function(event,resource,collection){
        if($scope.model.type == 'pair-items'){
            if(resource.type == 'text' || resource.type == 'picture'){
                $scope.modelAddBlockResourceField(collection, resource.id);
            }
        }
    }

    $scope.onDropMetadataKey = function(event,metakey,collection,field){
        collection[field] = metakey;
    }

    $scope.onDropDocument = function(event,resource,documents){
        if($scope.model.type == 'pair-items'){
            if(resource.type == 'text' || resource.type == 'picture'){
                $scope.modelAddBlockResourceField(documents, resource.id);
            }
        }
    }

    $scope.getResourceInfo = function(blockid, resourceid){
        $scope.usedResources[blockid][resourceid] = Resource.get({id:resourceid});
    }

    $scope.getExcludedResourceInfo = function(blockid, resourceid){
        $scope.excludedResources[blockid][resourceid] = Resource.get({id:resourceid});
    }

    $scope.getDocumentInfo = function(documentId){
        $scope.usedDocuments[documentId] = Resource.get({id:documentId});
    }

    $scope.getMobilPart = function(collection, key){
        var returnValue = '';
        angular.forEach(collection.metadata, function (meta) {
            if(meta.key == key){returnValue = meta.value;}
        });
        if(returnValue != ''){return collection.title+' ('+returnValue+')'}else{return collection.title;}
    }
}]);