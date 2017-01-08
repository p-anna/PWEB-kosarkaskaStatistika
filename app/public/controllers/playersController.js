app.controller('playersController', function($scope, $timeout, $http){
    $scope.players = [];
    $scope.teams = [];
    $scope.sortiranje = function(){
        alert("Ovo je izabran: " + $scope.selectedGroup1);
        /* ovde neko sortiranje */
    };


    init();

    function init() {
        $http.get("data/players.json")
            .then(function(response) {
                $scope.players = response.data.players;
            });
        $http.get("data/teams.json")
            .then(function(response) {
                $scope.teams = response.data.teams;
            });
    }
});