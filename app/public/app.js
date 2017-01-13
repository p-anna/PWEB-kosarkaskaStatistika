var app = angular.module('app', ["ngRoute"]);

app.config(['$routeProvider', function ($routeProvider) {
    $routeProvider
        .when('/', {
            isHome: 'true',
            templateUrl: "templates/home.html",
            controller: 'homeController'
        })
        .when('/players', {
            isHome: 'false',
            templateUrl: "templates/players.html",
            controller: 'playersController'
        })
        .when('/teams', {
            isHome: 'false',
            templateUrl: "templates/teams.html",
            controller: 'teamsController'
        })
        .when('/referees', {
            isHome: 'false',
            templateUrl: "templates/referees.html",
            controller: 'refereesController'
        })
        .when('/games', {
            isHome: 'false',
            templateUrl: "templates/games.html",
            controller: 'gamesController'
        })
        .when('/player/:id', {
            templateUrl: "templates/player.html",
            controller: 'playerController'
        });
}]);

app.run(['$rootScope', function($rootScope) {
    $rootScope.$on('$routeChangeSuccess', function (event, current, previous) {
        $rootScope.isHome = current.$$route.isHome;
    });
}]);
