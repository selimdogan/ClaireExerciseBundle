var userServices = angular.module('userServices', ['ngResource']);

userServices.factory('Resource', ['$resource',
    function ($resource) {

        return $resource(
            BASE_CONFIG.urls.api.users + ':userId',
            { 'userId': '@userId'}
        );

    }]);
