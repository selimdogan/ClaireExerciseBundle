var mainAppControllers = angular.module('mainAppControllers', ['ui.router']);

mainAppControllers.controller('mainManagerController', ['$scope', '$sce', '$routeParams', '$location', 'BASE_CONFIG', 'User', 'Resource',
    function ($scope, $sce, $routeParams, $location, BASE_CONFIG, User, Resource) {
        // load only once every necessary user
        $scope.loadUsers = function (resourcesData) {
            if (typeof $scope.users === 'undefined') {
                $scope.users = [];
            }

            var userIds = [];

            for (var i in resourcesData) {
                if (resourcesData.hasOwnProperty(i) && i != "$promise" && i != "$resolved") {
                    if (userIds.indexOf(resourcesData[i].author) == -1) {
                        userIds.push(resourcesData[i].author);
                    }
                    if (userIds.indexOf(resourcesData[i].owner) == -1) {
                        userIds.push(resourcesData[i].owner);
                    }
                }
            }

            for (i in userIds) {
                if (typeof $scope.users[userIds[i]] === 'undefined') {
                    $scope.users[userIds[i]] = User.get({userId: userIds[i]});
                }
            }
        };

        $scope.loadResourcesAndUsers = function () {
            Resource.query({owner: BASE_CONFIG.currentUserId}, function (data) {
                // load an id indexed array of the resources
                var privateResources = [];
                for (var i = 0; i < data.length; ++i) {
                    privateResources[data[i].id] = data[i];
                }
                $scope.resources = privateResources;

                Resource.query({'public-except-user': BASE_CONFIG.currentUserId}, function (data) {
                    // load an id indexed array of the resources
                    var publicResources = [];
                    for (var i = 0; i < data.length; ++i) {
                        publicResources[data[i].id] = data[i];
                    }

                    $scope.resources = jQuery.extend(publicResources, privateResources);

                    $scope.loadUsers($scope.resources);
                });
            });
        };

        $scope.to_trusted = function(html_code) {
            return $sce.trustAsHtml(html_code);
        }

        // initial loading
        $scope.loadResourcesAndUsers();
        $scope.BASE_CONFIG = BASE_CONFIG;
    }]);

mainAppControllers.controller('mainUserController', ['$scope', '$sce', '$routeParams', '$location', 'BASE_CONFIG', 'User',
    function ($scope, $sce, $routeParams, $location, BASE_CONFIG, User) {
        // load only once every necessary user
        $scope.loadUsers = function (resourcesData) {
            if (typeof $scope.users === 'undefined') {
                $scope.users = [];
            }

            var userIds = [];

            for (var i in resourcesData) {
                if (resourcesData.hasOwnProperty(i) && i != "$promise" && i != "$resolved") {
                    if (userIds.indexOf(resourcesData[i].author) == -1) {
                        userIds.push(resourcesData[i].author);
                    }
                    if (userIds.indexOf(resourcesData[i].owner) == -1) {
                        userIds.push(resourcesData[i].owner);
                    }
                }
            }

            for (i in userIds) {
                if (typeof $scope.users[userIds[i]] === 'undefined') {
                    $scope.users[userIds[i]] = User.get({userId: userIds[i]});
                }
            }
        };

        $scope.to_trusted = function(html_code) {
            return $sce.trustAsHtml(html_code);
        }

        $scope.BASE_CONFIG = BASE_CONFIG;
    }]);
