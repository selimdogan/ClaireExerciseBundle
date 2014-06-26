var itemServices = angular.module('itemServices', ['ngResource']);

itemServices.factory('Item', ['$resource',
    function ($resource) {

        return $resource(
            BASE_CONFIG.urls.api.attempts + ':attemptId/items/:itemId',
            {},
            {
                save: {method: 'POST', headers: {'Content-Type': 'application/json', 'Accept': 'application/json'}}
            }
        );

    }]);
