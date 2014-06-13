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

                $urlRouterProvider.otherwise('/model');

                $stateProvider.state('modelList', { // /contacts?myParam1&myParam2
                    url: '/model',
                    templateUrl: '/bundles/simpleitclaireexercise/webAuthor/partials/partial-model-list.html',
                    controller: 'modelListController'
                });

                $stateProvider.state('modelDisplay', {
                    url: '/model/:id',
                    templateUrl: '/bundles/simpleitclaireexercise/webAuthor/partials/partial-model-display.html',
                    controller: 'modelDisplayController'
                });

                $stateProvider.state('modelEdit', {
                    url: '/model/:id/edit',
                    views: {

                        // the main template will be placed here (relatively named)
                        '': { templateUrl: '/bundles/simpleitclaireexercise/webAuthor/partials/partial-model.html' },

                        // the child views will be defined here (absolutely named)
                        'modelEditPanel@modelEdit': {
                            templateUrl: '/bundles/simpleitclaireexercise/webAuthor/partials/partial-model-edit.html',
                            controller: 'modelEditController'
                        },

                        // for column two, we'll define a separate controller
                        'resourceListPanel@modelEdit': {
                            templateUrl: '/bundles/simpleitclaireexercise/webAuthor/partials/partial-resource-list.html',
                            controller: 'resourceListController'
                        }

                    }
                });


                $stateProvider.state('resourceList', {
                    url: '/resource',
                    templateUrl: '/bundles/simpleitclaireexercise/webAuthor/partials/partial-resource-list.html',
                    controller: 'resourceListController'
                });

                $stateProvider.state('resourceDisplay', {
                    url: '/resource/:id',
                    templateUrl: '/bundles/simpleitclaireexercise/webAuthor/partials/partial-resource-display.html',
                    controller: 'resourceDisplayController'
                });

                $stateProvider.state('resourceEdit', {
                    url: '/resource/:id/edit',
                    templateUrl: '/bundles/simpleitclaireexercise/webAuthor/partials/partial-resource-edit.html',
                    controller: 'resourceEditController'
                });

            }
        ]
    );