app.controller('gamesController', function($scope, $timeout, $http){

    $scope.games = [];

    $scope.selected1 = "Average - Per Game";
    $scope.selected2 = "All Teams";
    $scope.selected3 = "Full Season";

    $scope.propertyName = 'game';
    $scope.reverse = true;

    $scope.sortBy = function(propertyName) {
        $scope.reverse = ($scope.propertyName === propertyName) ? !$scope.reverse : false;
        $scope.propertyName = propertyName;
    };


    $scope.prikazi = function(){
        alert("Ovo je izabran: " + $scope.selected1 + $scope.selected2 + $scope.selected3);

    };

    init();

    function init() {
        $http.get("data/games.json")
            .then(function(response) {
                $scope.games = response.data.games;
            });
    }
});