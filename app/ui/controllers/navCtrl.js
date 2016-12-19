var app = angular.module('app', []);

app.controller('navCtrl', function($scope){
    $scope.showPlayers = false;
    $scope.showTeams = false;
    $scope.showSeasons = false;

    $scope.clickPlayers = function(){
        $scope.showPlayers = !$scope.showPlayers;
        $scope.showTeams = false;
        $scope.showSeasons = false;
    };

    $scope.clickTeams = function(){
        $scope.showPlayers = false;
        $scope.showTeams = !$scope.showTeams;
        $scope.showSeasons = false;
    };

    $scope.clickSeasons = function(){
        $scope.showPlayers = false;
        $scope.showTeams = false;
        $scope.showSeasons = !$scope.showSeasons;
    };
});