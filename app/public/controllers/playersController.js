
app.controller('playersController', function($scope, $timeout, $http){
    $scope.players = [];
    $scope.teams = [];
    $scope.selected1 = "Average - Per Game";
    $scope.selected2 = "All Teams";
    $scope.selected3 = "Full Season";


    $scope.prikazi = function(){
        alert("Ovo je izabran: " + $scope.selected1 + $scope.selected2 + $scope.selected3);

        /* jos nije pozevan
        $http.get("http:/ljubica/source/primercic.php/players/" + $scope.selected1 + "/" + $scope.selected2 + "/" + $scope.selected3)
            .then(function(response){
                $scope.players = response.data.players;
            })
            .error(function (msg) {
                console.log("Poruka kod zvanja php-a: " + msg);
            });*/
    };



    init();

    function init() {
        /*$scope.prikazi(); treba povezati */
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