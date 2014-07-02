
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
        'exerciseServices',
        'exerciseByModelServices',
        'attemptByExerciseServices',
        'answerServices',
        'attemptServices',
        'attemptListServices',
        'itemServices',
        'itemControllers',
        'attemptControllers',
        'learnerControllers'
    ]
);
