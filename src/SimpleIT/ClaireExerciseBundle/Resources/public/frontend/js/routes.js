mainApp.config(
        ['$routeProvider', '$locationProvider', '$stateProvider', '$urlRouterProvider','$resourceProvider',
            function ($routeProvider, $locationProvider, $stateProvider, $urlRouterProvider, $resourceProvider) {

                /*
                $routeProvider.when('/resource/list',     {templateUrl: '/bundles/simpleitclaireexercise/webAuthor/views/resource/list.html',    controller: 'resourceListController'});
                $routeProvider.when('/resource/add',      {templateUrl: '/bundles/simpleitclaireexercise/webAuthor/views/resource/edit.html',     controller: 'resourceAddController'});
                $routeProvider.when('/resource/:id/edit', {templateUrl: '/bundles/simpleitclaireexercise/webAuthor/views/resource/edit.html',     controller: 'resourceEditController'});
                $routeProvider.when('/resource/:id',      {templateUrl: '/bundles/simpleitclaireexercise/webAuthor/views/resource/display.html', controller: 'resourceDisplayController'});

                $routeProvider.when('/model/list',     {templateUrl: '/bundles/simpleitclaireexercise/webAuthor/views/model/list.html',    controller: 'modelListController'});
                $routeProvider.when('/model/add',      {templateUrl: '/bundles/simpleitclaireexercise/webAuthor/views/model/edit.html',     controller: 'modelAddController'});
                $routeProvider.when('/model/:id/edit', {templateUrl: '/bundles/simpleitclaireexercise/webAuthor/views/model/edit.html',     controller: 'modelEditController'});
                $routeProvider.when('/model/:id',      {templateUrl: '/bundles/simpleitclaireexercise/webAuthor/views/model/display.html', controller: 'modelDisplayController'});

                $routeProvider.otherwise({redirectTo: '/model/list'});
                $locationProvider.hashPrefix('!'); // Enable ajax crawling
                */

                $resourceProvider.defaults.stripTrailingSlashes = false;


                // Teacher's routes
                $urlRouterProvider.otherwise('/model');

                $stateProvider.state('model', {
                    url: '/model',
                    templateUrl: BASE_CONFIG.urls.partials.teacher+'/partial-model.html'
                });

                $stateProvider.state('model.list', { // /contacts?myParam1&myParam2
                    url: '/',
                    templateUrl: BASE_CONFIG.urls.partials.teacher+'/partial-model-list.html'
                    //,controller: 'modelListController'
                });

                $stateProvider.state('model.display', {
                    url: '/:modelid',
                    templateUrl: BASE_CONFIG.urls.partials.teacher+'/partial-model-display.html'
                    //,controller: 'modelDisplayController'
                });

                $stateProvider.state('model.edit', {
                    url: '/:modelid/edit',
                    templateUrl: BASE_CONFIG.urls.partials.teacher+'/partial-model-edit.html'
                    //,controller: 'modelEditController'
                });


                $stateProvider.state('model.edit.resource', {
                    url: '/resource',
                    templateUrl: BASE_CONFIG.urls.partials.teacher+'/partial-resource.html'
                    //,controller: 'resourceController'
                });

                $stateProvider.state('model.edit.resource.list', {
                    url: '/list',
                    templateUrl: BASE_CONFIG.urls.partials.teacher+'/partial-resource-list.html'
                    //,controller: 'resourceController'
                    //,controller: 'resourceListController'
                });

                $stateProvider.state('model.edit.resource.display', {
                    url: '/:resourceid',
                    templateUrl: BASE_CONFIG.urls.partials.teacher+'/partial-resource-display.html'
                    //,controller: 'resourceController'
                    //,controller: 'resourceDisplayController'
                });

                $stateProvider.state('model.edit.resource.edit', {
                    url: '/:resourceid/edit',
                    templateUrl: BASE_CONFIG.urls.partials.teacher+'/partial-resource-edit.html'
                    //,controller: 'resourceController'
                    //,controller: 'resourceEditController'
                });

                $stateProvider.state('resource', {
                    url: '/resource',
                    templateUrl: BASE_CONFIG.urls.partials.teacher+'/partial-resource.html'
                    //,controller: 'resourceController'
                });

                $stateProvider.state('resource.list', {
                    url: '/list',
                    templateUrl: BASE_CONFIG.urls.partials.teacher+'/partial-resource-list.html'
                    //,controller: 'resourceListController'
                });

                $stateProvider.state('resource.display', {
                    url: '/:resourceid',
                    templateUrl: BASE_CONFIG.urls.partials.teacher+'/partial-resource-display.html'
                    //,controller: 'resourceDisplayController'
                });

                $stateProvider.state('resource.edit', {
                    url: '/:resourceid/edit',
                    templateUrl: BASE_CONFIG.urls.partials.teacher+'/partial-resource-edit.html'
                    //,controller: 'resourceEditController'
                });

                // learner's routes
                // ...
            }
        ]
    );