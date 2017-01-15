
app.controller('playersController', function($scope, $timeout, $http){
    $scope.players = [];
    $scope.teams = [];

    $scope.selected1 = "Average - Per Game";
    $scope.selected2 = "All Teams";
    $scope.selected3 = "Full Season";

    $scope.propertyName = 'playerName';
    $scope.reverse = true;

    $scope.sortBy = function(propertyName) {
        $scope.reverse = ($scope.propertyName === propertyName) ? !$scope.reverse : false;
        $scope.propertyName = propertyName;
    };


    $scope.prikazi = function(){
        alert("Ovo je izabran: " + $scope.selected1 + $scope.selected2 + $scope.selected3);


        $http.get("http:/BasketStatistic/PWEB-kosarkaskaStatistika/source/primercic.php")///players/" + $scope.selected1 + "/" + $scope.selected2 + "/" + $scope.selected3)
            .then(function(response){
                $scope.players = response.data;

            });
    };

    init();

    function init() {
        $scope.prikazi(); /*treba povezati */
        /*$http.get("data/players.json")
         .then(function(response) {
         $scope.players = response.data.players;
         });*/
        $http.get("data/teams.json")
            .then(function(response) {
                $scope.teams = response.data.teams;
            });
    }
});