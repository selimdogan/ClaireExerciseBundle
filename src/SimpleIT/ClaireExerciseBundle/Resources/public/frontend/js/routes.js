mainApp.config(
    ['$routeProvider', '$locationProvider', '$stateProvider', '$urlRouterProvider', '$resourceProvider',
        function ($routeProvider, $locationProvider, $stateProvider, $urlRouterProvider, $resourceProvider) {

            $resourceProvider.defaults.stripTrailingSlashes = false;

            $urlRouterProvider.otherwise('/learner/models/');

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
            $stateProvider.state('all-attempt-list', {
                url: '/learner/models/',
                templateUrl: BASE_CONFIG.urls.partials.learner + '/partial-attempt-list.html'
            });

            $stateProvider.state('attempt-list', {
                url: '/learner/model/:modelId',
                templateUrl: BASE_CONFIG.urls.partials.learner + '/partial-attempt-list.html'
            });

            $stateProvider.state('attempt', {
                url: '/learner/attempt/:attemptId',
                templateUrl: BASE_CONFIG.urls.partials.learner + '/partial-attempt.html'
            });

            $stateProvider.state('attempt.pair-items', {
                url: '/pair-items/:itemId',
                templateUrl: BASE_CONFIG.urls.partials.learner + '/partial-pair-items.html'
            });

            $stateProvider.state('attempt.group-items', {
                url: '/group-items/:itemId',
                templateUrl: BASE_CONFIG.urls.partials.learner + '/partial-group-items.html'
            });

            $stateProvider.state('attempt.multiple-choice', {
                url: '/multiple-choice/:itemId',
                templateUrl: BASE_CONFIG.urls.partials.learner + '/partial-multiple-choice.html'
            });
        }
    ]
);
