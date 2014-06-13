var resourceServices = angular.module('resourceServices', ['ngResource']);

resourceServices.factory('Resource', ['$resource',
    function($resource){

        return $resource(

            'http://claire/app_dev.php/api/resources/:id',
            { 'id': '@id'},
            {
                 update: {method: 'PUT', headers: {'Content-Type': 'application/json', 'Accept': 'application/json'}}
                ,save: {method: 'POST', isArray: false, headers: {'Content-Type': 'application/json', 'Accept': 'application/json'}}
            }
        );

    }]);

var modelServices = angular.module('modelServices', ['ngResource']);

modelServices.factory('Model', ['$resource',
    function($resource){

        return $resource(
            'http://claire/app_dev.php/api/exercise-models/:id',
            {},
            {
                'update': {method: 'PUT', params: {'id': '@id'}, headers: {'Content-Type': 'application/json', 'Accept': 'application/json'}}
                ,save: {method: 'POST', isArray: false, headers: {'Content-Type': 'application/json', 'Accept': 'application/json'}}
            }
        );

    }]);