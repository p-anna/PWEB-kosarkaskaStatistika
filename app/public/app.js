var app = angular.module('app', ["ngRoute"]);

app.config(['$routeProvider', function ($routeProvider) {
    $routeProvider
        .when('/', {
            templateUrl: "templates/home.html",
            controller: 'homeController'
        })
        .when('/players', {
            templateUrl: "templates/players.html",
            controller: 'playersController'
        })
        .when('/teams', {
            templateUrl: "templates/teams.html",
            controller: 'teamsController'
        })
        .when('/referees', {
            templateUrl: "templates/referees.html",
            controller: 'refereesController'
        });
}]);
