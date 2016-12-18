var app = angular.module('app', []);

app.controller('navCtrl', function($scope){
    $scope.showPlayers = false;
    $scope.showTeams = false;
    $scope.showSeasons = false;

    $scope.clickPlayers = function(){
        $scope.showPlayers = !$scope.showPlayers;
    };

    $scope.clickTeams = function(){
        $scope.showTeams = !$scope.showTeams;
    };

    $scope.clickSeasons = function(){
        $scope.showSeasons = !$scope.showSeasons;
    };
});