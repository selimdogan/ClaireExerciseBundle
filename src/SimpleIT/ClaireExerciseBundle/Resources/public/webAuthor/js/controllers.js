var resourceControllers = angular.module('resourceControllers', ['ui.router']);

resourceControllers.controller('mainController', ['$scope', '$routeParams', '$location',
    function($scope, $routeParams, $location) {

        $scope.toggleResourceList = false;
        $scope.togglePanelResourceList = function (){
            $scope.toggleResourceList = !$scope.toggleResourceList;
        }

    }]);

resourceControllers.controller('resourceController', ['$scope', '$routeParams', '$location',
    function($scope, $routeParams, $location) {

        $scope.section = 'resource';


        if($scope.$parent.section === undefined){$scope.parentSection = '';}else{$scope.parentSection = $scope.$parent.section;}

        // new resource text
        $scope.newTextResource = {
            "type": "text",
            "title": "Titre de la ressource",
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

resourceControllers.controller('resourceListController', ['$scope', 'Resource', '$location', function($scope, Resource, $location) {

    // retrieve resources
    $scope.resources = Resource.query();

    // delete resource method
    $scope.deleteResource = function (resource) {
        resource.$delete(function () {
            $location.path("/resource");
        });
    };

    // create resource method
    $scope.createTextResource = function () {
        Resource.save($scope.newTextResource);
    };

}]);

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

        $scope.newPairItemsModel = {
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
                    }
                ],
                "exercise_model_type": "pair-items"
            }
        };

        $scope.modelAddKeywordsField = function (collection){
            var keyword = $("#modelAddKeyword");
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
            collection.splice(collection.length, 0, {"id": id});
        }

        $scope.modelRemoveField = function (collection, index){
            collection.splice(index, 1);
        }

    }]);

modelControllers.controller('modelListController', ['$scope', 'Model', '$location', function($scope, Model, $location) {

    $scope.models = Model.query();

    $scope.deleteModel = function (model) {
        model.$delete(function () {
            $location.path("/model");
        });
    };

    $scope.createModel = function () {
        Model.save($scope.newPairItemsModel);
    };

}]);

modelControllers.controller('modelEditController', ['$scope', 'Model', 'Resource', '$location', '$stateParams', function($scope, Model, Resource, $location, $stateParams) {

    $scope.model = Model.get({id:$stateParams.modelid});

    $scope.usedDocuments = [];
    $scope.usedResources = [];

    $scope.updateModel = function () {
        delete $scope.model.id;
        delete $scope.model.author;
        delete $scope.model.owner;
        delete $scope.model.required_exercise_resources;
        delete $scope.model.required_knowledges;
        $scope.model.$update({id:$stateParams.modelid},function (model) {});
    };

    $scope.onDropResourceToBlock = function(event,resource,collection){
        $scope.modelAddBlockResourceField(collection, resource.id);
    }

    $scope.onDropMetadataKey = function(event,metakey,field){
        field.pair_meta_key = metakey;
    }

    $scope.onDropDocument = function(event,resource,documents){
        $scope.modelAddBlockResourceField(documents, resource.id);
    }

    $scope.getResourceInfo = function(blockid, resourceid){
        $scope.usedResources[blockid][resourceid] = Resource.get({id:resourceid});
    }

    $scope.getDocumentInfo = function(documentId){
        $scope.usedDocuments[documentId] = Resource.get({id:documentId});
    }
}]);