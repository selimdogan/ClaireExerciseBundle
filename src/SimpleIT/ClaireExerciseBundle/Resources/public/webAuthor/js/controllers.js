var resourceControllers = angular.module('resourceControllers', ['ui.router']);

resourceControllers.controller('mainController', ['$scope', '$routeParams', '$location',
    function($scope, $routeParams, $location) {

        $scope.toggleResourceList = false;
        $scope.togglePanelResourceList = function (){
            $scope.toggleResourceList = !$scope.toggleResourceList;
        }

        /*
        $scope.title = 'Resource';
         */

        $scope.go = function (path) {
            $location.path(path);
        };

    }]);

resourceControllers.controller('resourceController', ['$scope', '$routeParams', '$location',
    function($scope, $routeParams, $location) {

    }]);

resourceControllers.controller('resourceListController', ['$scope', 'Resource', '$location', function($scope, Resource, $location) {

    if($scope.$parent.context === undefined) {
        $scope.includedby = '';
    }else{$scope.includedby = $scope.$parent.context.include;}

    $scope.resources = Resource.query();

    //console.log($scope.resources);

    $scope.deleteResource = function (resource) {
        resource.$delete(function () {
            $location.path("/resource");
        });
    };

    $scope.createResource = function () {// todo : not working

        var newResource = {
            "type": "text",
            "title": "retry",
            "public": true,
            "archived": false,
            "draft": false,
            "complete": null,
            "metadata": [
                {
                    "key": "author",
                    "value": "Toto"
                },
                {
                    "key": "language",
                    "value": "en"
                },
                {
                    "key": "prog-language",
                    "value": "C"
                },
                {
                    "key": "return-value",
                    "value": "200"
                },
                {
                    "key": "size",
                    "value": "35"
                }
            ],
            "keywords": [
                "de"
            ],
            "content": {
                "text": "Programme 12 en C return value 200",
                "object_type": "text"
            },
            "required_exercise_resources": null,
            "required_knowledges": null
        };

        //console.log(newResource);

        Resource.save(newResource);
    };

}]);

resourceControllers.controller('resourceDisplayController', ['$scope', 'Resource', '$location', '$stateParams', function($scope, Resource, $location, $stateParams) {

    if($scope.$parent.context === undefined) {
        $scope.includedby = '';
    }else{$scope.includedby = $scope.$parent.context.include;}

    $scope.resource = Resource.get({id:$stateParams.resourceid});

}]);

resourceControllers.controller('resourceAddController', ['$scope', 'Resource', '$location', '$stateParams', function($scope, Resource, $location, $stateParams) {

}]);

resourceControllers.controller('resourceEditController', ['$scope', 'Resource', '$location', '$stateParams', function($scope, Resource, $location, $stateParams) {

    $scope.context = {
        "include": '',
        "included": '',
        "common": { "collapse": false },
        "keywords": { "collapse": true },
        "metadata": { "collapse": true },
        "content": { "collapse": false }
    };

    $scope.resource = Resource.get({id:$stateParams.resourceid});

    //console.log($scope.resource);

    $scope.updateResource = function () {

        delete $scope.resource.id;
        delete $scope.resource.type;
        delete $scope.resource.author;
        delete $scope.resource.owner;
        //delete $scope.resource.complete;
        delete $scope.resource.required_exercise_resources;
        delete $scope.resource.required_knowledges;

        $scope.resource.$update({id:$stateParams.resourceid},function (resource) {});

    };

    $scope.resourceAddField = function (collection){

        //var newElement = {key: '', value: ''};
        //var newElement = Object(collection[collection.length-1]);
        var newElement = deepCopy(collection[collection.length-1]);
        collection.splice(collection.length, 0, newElement);

    }

    $scope.resourceAddKeywordsField = function (collection){

        collection.push($("#resourceAddKeyword")[0].value);
        $("#resourceAddKeyword")[0].value = '';

    }

    $scope.resourceAddMetadataField = function (collection){

        var newElement = {key: '', value: ''};
        collection.splice(collection.length, 0, newElement);

    }

    $scope.resourceRemoveField = function (collection, index){
        collection.splice(index, 1);
    }

    function deepCopy(p,c) {
        var c = c||{};
        for (var i in p) {
            if (typeof p[i] === 'object') {
                c[i] = (p[i].constructor === Array)?[]:{};
                deepCopy(p[i],c[i]);
            } else c[i] = '';}
        return c;
    }

}]);


var modelControllers = angular.module('modelControllers', ['ui.router']);

modelControllers.controller('modelController', ['$scope', '$routeParams', '$location',
    function($scope, $routeParams, $location) {


    }]);

modelControllers.controller('modelListController', ['$scope', 'Model', '$location', function($scope, Model, $location) {

    $scope.models = Model.query();

    $scope.deleteModel = function (model) {
        model.$delete(function () {
            $location.path("/model");
        });
    };

    $scope.createModel = function () {

        var newModel = {
            "type": "pair-items",
            "title": "new Appariement",
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
                    },
                    {
                        "number_of_occurrences": 0,
                        "resources": [],
                        "pair_meta_key": ""
                    }
                ],
                "exercise_model_type": "pair-items"
            }
        };

        console.log(newModel);

        Model.save(newModel);
    };

}]);

modelControllers.controller('modelDisplayController', ['$scope', 'Model', '$location', '$stateParams', function($scope, Model, $location, $stateParams) {

    $scope.model = Model.get({id:$stateParams.modelid});

}]);

modelControllers.controller('modelAddController', ['$scope', 'Model', '$location', '$stateParams', function($scope, Model, $location, $stateParams) {

}]);

modelControllers.controller('modelEditController', ['$scope', 'Model', 'Resource', '$location', '$stateParams', function($scope, Model, Resource, $location, $stateParams) {

    $scope.tooltipPreviewResource = function(idRes){
        $scope.tooltipResource = Resource.get({id:idRes});
    }

    $scope.modelAddKeywordsField = function (collection){

        collection.push($("#resourceAddKeyword")[0].value);
        $("#resourceAddKeyword")[0].value = '';

    }

    $scope.modelAddMetadataField = function (collection){

        var newElement = {key: '', value: ''};
        collection.splice(collection.length, 0, newElement);

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

        var newElement = {"id": id};
        collection.splice(collection.length, 0, newElement);

    }

    $scope.modelRemoveField = function (collection, index){

        collection.splice(index, 1);

    }

    $scope.context = {
        "include": 'resource',
        "included": '',
        "common": { "collapse": false },
        "keywords": { "collapse": true },
        "metadata": { "collapse": true },
        "pair_items": {
            "collapse": false,
            "wording": { "collapse": false },
            "documents": { "collapse": false },
            "pair_blocks": {
                "collapse": false,
                "resources": { "collapse": false }
            }
        }
    };

    $scope.model = Model.get({id:$stateParams.modelid});

    //console.log($scope.model );

    $scope.updateModel = function () {
        delete $scope.model.id;
        delete $scope.model.author;
        delete $scope.model.owner;
        delete $scope.model.required_exercise_resources;
        delete $scope.model.required_knowledges;
        $scope.model.$update({id:$stateParams.modelid},function (model) {});
    };

    $scope.onDrop = function(event,data,collection){

        $scope.modelAddBlockResourceField(collection, data);

    }

}]);