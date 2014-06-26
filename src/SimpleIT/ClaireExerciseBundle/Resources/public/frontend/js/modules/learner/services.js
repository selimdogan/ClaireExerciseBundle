var itemServices = angular.module('itemServices', ['ngResource']);

itemServices.factory('Item', ['$resource',
    function ($resource) {

        return $resource(
            BASE_CONFIG.urls.api.attempts + ':attemptId/items/:itemId',
            {'attemptId': '@attemptId', 'itemId': '@itemId'}
        );

    }]);

var answerServices = angular.module('answerServices', ['ngResource']);

answerServices.factory('Answer', ['$resource',
    function ($resource) {

        return $resource(
            BASE_CONFIG.urls.api.attempts + ':attemptId/items/:itemId/answers/',
            {'attemptId': '@attemptId', 'itemId': '@itemId'},
            {
                save: {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json', 'Accept': 'application/json'}
                }
            }
        );

    }]);
