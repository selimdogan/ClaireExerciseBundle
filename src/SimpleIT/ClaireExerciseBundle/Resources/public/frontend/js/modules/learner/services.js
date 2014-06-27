var itemServices = angular.module('itemServices', ['ngResource']);

itemServices.factory('Item', ['$resource',
    function ($resource) {

        return $resource(
            BASE_CONFIG.urls.api.attempts + ':attemptId/items/:itemId',
            {'attemptId': '@attemptId', 'itemId': '@itemId'}
        );

    }]);

var exerciseServices = angular.module('exerciseServices', ['ngResource']);

exerciseServices.factory('Exercise', ['$resource',
    function ($resource) {

        return $resource(
            BASE_CONFIG.urls.api.exercises + ':exerciseId',
            {'exerciseId': '@exerciseId'}
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

var attemptServices = angular.module('attemptServices', ['ngResource']);

attemptServices.factory('Attempt', ['$resource',
    function ($resource) {

        return $resource(
            BASE_CONFIG.urls.api.attempts + ':attemptId',
            {'attemptId': '@attemptId'}
        );

    }]);


var modelTryServices = angular.module('modelTryServices', ['ngResource']);

modelTryServices.factory('ModelTry', ['$resource',
    function ($resource) {

        return $resource(
            BASE_CONFIG.urls.api.models + ':modelId/try',
            {'modelId': '@modelId'},
            {
                try: {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json', 'Accept': 'application/json'}
                }
            }
        );

    }]);
