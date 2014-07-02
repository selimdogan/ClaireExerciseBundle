mainApp.config(
    ['$routeProvider', '$locationProvider', '$stateProvider', '$urlRouterProvider', '$resourceProvider',
        function ($routeProvider, $locationProvider, $stateProvider, $urlRouterProvider, $resourceProvider) {

            $resourceProvider.defaults.stripTrailingSlashes = false;

            $urlRouterProvider.otherwise('/model');

            // Teacher's routes

            $stateProvider.state('model', { // /contacts?myParam1&myParam2
                url: '/teacher/model',
                templateUrl: BASE_CONFIG.urls.partials.teacher + '/partial-model-list.html'
                //,controller: 'modelListController'
            });

            $stateProvider.state('modelEdit', {
                url: '/teacher/model/:modelid',
                templateUrl: BASE_CONFIG.urls.partials.teacher + '/partial-model-edit.html'
                //,controller: 'modelEditController'
            });

            $stateProvider.state('modelEdit.resource', {
                url: '/resource',
                templateUrl: BASE_CONFIG.urls.partials.teacher + '/partial-resource-list.html'
                //,controller: 'resourceController'
                //,controller: 'resourceListController'
            });

            $stateProvider.state('modelEdit.resourceEdit', {
                url: '/resource/:resourceid',
                templateUrl: BASE_CONFIG.urls.partials.teacher + '/partial-resource-edit.html'
                //,controller: 'resourceController'
                //,controller: 'resourceEditController'
            });

            $stateProvider.state('resource', {
                url: '/teacher/resource',
                templateUrl: BASE_CONFIG.urls.partials.teacher + '/partial-resource-list.html'
                //,controller: 'resourceListController'
            });

            $stateProvider.state('resourceEdit', {
                url: '/teacher/resource/:resourceid',
                templateUrl: BASE_CONFIG.urls.partials.teacher + '/partial-resource-edit.html'
                //,controller: 'resourceEditController'
            });


            // learner's routes

            $stateProvider.state('learner', {
                url: '/learner/',
                templateUrl: BASE_CONFIG.urls.partials.learner + '/partial-index.html'
            });

            $stateProvider.state('attempt.item', {
                url: '/item/:itemId',
                templateUrl: BASE_CONFIG.urls.partials.learner + '/partial-item.html'
            });

            $stateProvider.state('attempt', {
                url: '/learner/attempt/:attemptId',
                templateUrl: BASE_CONFIG.urls.partials.learner + '/partial-exercise.html'
            });

            $stateProvider.state('modelTry', {
                url: '/learner/model/:modelId/try',
                controller: 'modelTryController'
            });
        }
    ]
);
