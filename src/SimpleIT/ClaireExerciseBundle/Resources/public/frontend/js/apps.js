
var mainApp = angular.module('mainApp',
    [
        'ngRoute',
        'ngResource',
        'ui.bootstrap',
        'ui.router',
        'ngDragDrop',
        'angular-loading-bar',
        'mainAppControllers',
        'userServices',
        'resourceControllers',
        'resourceServices',
        'modelControllers',
        'modelServices',
        'modelTryControllers',
        'modelTryServices',
        'itemControllers',
        'itemServices',
        'exerciseControllers',
        'exerciseServices',
        'answerServices',
        'exerciseByAttemptServices',
        'attemptServices'
    ]
);
