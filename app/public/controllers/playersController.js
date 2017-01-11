
app.controller('playersController', function($scope, $timeout, $http){
    $scope.players = [];
    $scope.teams = [];
    $scope.selected1 = "Average - Per Game";
    $scope.selected2 = "All Teams";
    $scope.selected3 = "Full Season";


    $scope.sortiranje = function(){
        /* ovde ubacis lopiticu koji bounce*/
        alert("Ovo je izabran: " + $scope.selected1 + $scope.selected2 + $scope.selected3);
        /* ovde neko $http.get(nesto.php/$scope.selected1/$scope.selected2/$scop.selected3) */

    };



    init();

    function init() {
        $http.get("data/players.json")
            .then(function(response) {
                $scope.players = response.data.players;
            })
            .error;
        $http.get("data/teams.json")
            .then(function(response) {
                $scope.teams = response.data.teams;
            });
    }
});