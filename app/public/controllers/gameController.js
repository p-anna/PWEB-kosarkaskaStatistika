app.controller('gameController', function($scope, $timeout, $http, $routeParams){

    //ovde sad za http get treba da se iskoriti $routeParams.id da bi se dobili podaci za igraca sa tim id-jem
    //za sad se prikazuje jedan zakucan
    $scope.headers = [];
    $scope.teams = [];
    $scope.team1 = [];
    $scope.team2 = [];
    $scope.team1Percents = [];
    $scope.team2Percents = [];
    $scope.team1Total = [];
    $scope.team2Total = [];

    $scope.propertyName = '';
    $scope.reverse = true;

    $scope.loading = false;

    $scope.sortBy = function(propertyName) {
        $scope.reverse = ($scope.propertyName === propertyName) ? !$scope.reverse : false;
        $scope.propertyName = propertyName;
    };

    console.log($routeParams.id);
    init();

    function init() {

        $scope.loading = true;
        $http({
            url: "../../source/game.php",
            method: "GET",
            params: {season: 2016, gameCode: $routeParams.id}
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

        /*
        $scope.loading = true;
        $http.get("data/game.json")
            .then(function(response){
                $scope.headers = response.data.header;
                $scope.teams = response.data.teams;
                $scope.team1 = response.data.team1;
                $scope.team2 = response.data.team2;
                $scope.team1Percents = response.data.team1Percents;
                $scope.team2Percents = response.data.team2Percents;
                $scope.team1Total = response.data.team1Total;
                $scope.team2Total = response.data.team2Total;
                $scope.propertyName = response.data.header[0].nameOfProperty;
            }).finally(function () {
                $scope.loading = false;
            });

*/
    }
});