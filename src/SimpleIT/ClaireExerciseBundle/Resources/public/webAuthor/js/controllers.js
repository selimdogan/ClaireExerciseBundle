var resourceControllers = angular.module('resourceControllers', ['ui.router']);

resourceControllers.controller('mainCtrl', ['$scope', '$routeParams', '$location',
    function($scope, $routeParams, $location) {
        /*
        $scope.title = 'Resource';
         */

        $scope.go = function (path) {
            $location.path(path);
        };

    }]);

resourceControllers.controller('resourceListController', ['$scope', 'Resource', '$location', function($scope, Resource, $location) {

    $scope.resources = Resource.query();

    $scope.deleteResource = function (resource) {
        resource.$delete(function () {
            $location.path("/resource/list");
        });
    };

    $scope.createResource = function () {// todo : not working

        var newResource = {
            "type": "text",
            "title": "Titre de la ressource new!!!!",
            "public": true,
            "archived": false,
            "draft": false,
            "complete": null,
            "metadata": {
                "_misc": "de",
                "author": "Toto",
                "language": "en",
                "prog-language": "C",
                "return-value": "200",
                "size": "35"
            },
            "content": {
                "text": "Programme 12 en C return value 200",
                "object_type": "text"
            },
            "required_exercise_resources": [],
            "required_knowledges": []
        };

        $scope.newResource = new Resource(newResource);

        //console.log(Resource);

        Resource.save(newResource);
    };

}]);

resourceControllers.controller('resourceDisplayController', ['$scope', 'Resource', '$location', '$stateParams', function($scope, Resource, $location, $stateParams) {

    $scope.resource = Resource.get({id:$stateParams.id});

}]);

resourceControllers.controller('resourceAddController', ['$scope', 'Resource', '$location', '$stateParams', function($scope, Resource, $location, $stateParams) {

}]);

resourceControllers.controller('resourceEditController', ['$scope', 'Resource', '$location', '$stateParams', function($scope, Resource, $location, $stateParams) {

    $scope.context = {
        "common": { "collapse": false },
        "metadata": { "collapse": true },
        "content": { "collapse": false }
    };

    $scope.resource = Resource.get({id:$stateParams.id});

    $scope.updateResource = function () {
        delete $scope.resource.id;
        delete $scope.resource.type;
        delete $scope.resource.author;
        delete $scope.resource.owner;
        $scope.resource.$update({id:$stateParams.id},function (resource) {});
    };

}]);


var modelControllers = angular.module('modelControllers', ['ui.router']);

modelControllers.controller('modelListController', ['$scope', 'Model', '$location', function($scope, Model, $location) {

    $scope.models = Model.query();

    $scope.deleteModel = function (model) {
        model.$delete(function () {
            $location.path("/model/list");
        });
    };

    $scope.createResource = function () {// todo : not working

        var newModel = {

        };

        $scope.newModel = new Model(newModel);

        $scope.newModel.$save(function (data) {
            console.log(data);
            //$location.path("/resource/"+data.id+"/edit");
        });
    };

}]);

modelControllers.controller('modelDisplayController', ['$scope', 'Model', '$location', '$stateParams', function($scope, Model, $location, $stateParams) {

    $scope.model = Model.get({id:$stateParams.id});

}]);

modelControllers.controller('modelAddController', ['$scope', 'Model', '$location', '$stateParams', function($scope, Model, $location, $stateParams) {

}]);

modelControllers.controller('modelEditController', ['$scope', 'Model', '$location', '$stateParams', function($scope, Model, $location, $stateParams) {

    $scope.context = {
        "common": { "collapse": false },
        "metadata": { "collapse": true },
        "pair_items": {
            "collapse": false,
            "wording": { "collapse": false },
            "documents": { "collapse": false },
            "pair_blocks": {
                "collapse": false,
                "resources": { "collapse": false },
            }
        }
    };

    $scope.model = Model.get({id:$stateParams.id});

    console.log($scope.model);

    $scope.updateModel = function () {
        delete $scope.model.id;
        delete $scope.model.type;
        delete $scope.model.author;
        delete $scope.model.owner;
        $scope.model.$update({id:$stateParams.id},function (model) {});
    };

}]);