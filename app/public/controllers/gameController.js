app.controller('gameController', function($scope, $timeout, $http, $routeParams){

    //ovde sad za http get treba da se iskoriti $routeParams.id da bi se dobili podaci za igraca sa tim id-jem
    //za sad se prikazuje jedan zakucan
    $scope.headers = [];
    $scope.teams = [];
    $scope.team1 = [];
    $scope.team2 = [];
    $scope.team1Total = [];
    $scope.team2Total = [];
    $scope.init = init;

    $scope.statisticType = "Average | Per Game";
    $scope.seasons = "2016-2017";

    $scope.propertyName = '';
    $scope.reverse = true;

    $scope.loading = false;

    $scope.sortBy = function(propertyName) {
        $scope.reverse = ($scope.propertyName === propertyName) ? !$scope.reverse : false;
        $scope.propertyName = propertyName;
    };

    console.log($routeParams.id);

    $scope.prikazi = function(){
        $scope.loading = true;
        var siz = $scope.seasons == "2016-2017" ? 2016 : 2015;

        $http({
            url: "../../source/game.php",
            method: "GET",
            params: {season: siz, gameCode: $routeParams.id}
        }).then(function(response){
            $scope.headers = response.data.header;
            $scope.teams = response.data.teams;
            $scope.team1 = response.data.team1;
            $scope.team2 = response.data.team2;
            $scope.team1Total = response.data.team1Total;
            $scope.team2Total = response.data.team2Total;
        }).finally(function () {
            $scope.loading = false;
        });
    }

    init();

    function init() {

        $scope.prikazi();
    }
});