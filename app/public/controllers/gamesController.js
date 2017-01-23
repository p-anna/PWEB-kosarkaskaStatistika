app.controller('gamesController', function($scope, $timeout, $http){

    $scope.games = [];
    $scope.headers = [];
    $scope.teams = [];

    $scope.team = "All Teams";
    $scope.seasonPart = "Full Season";
    $scope.week = "All Weeks";

    $scope.propertyName = '';
    $scope.reverse = true;

    $scope.loading = false;

    $scope.sortBy = function(propertyName) {
        $scope.reverse = ($scope.propertyName === propertyName) ? !$scope.reverse : false;
        $scope.propertyName = propertyName;
    };


    $scope.prikazi = function(){
        $scope.loading = true;

        /* priprema parametara */
        var teamID = "null";
        for(var t = 0; t < $scope.teams.length; t++){
            if($scope.team == $scope.teams[t].teamName)
                teamID = $scope.teams[t].idTeam;
        }

        var season = $scope.seasonPart === "Full Season" ? "null" : $scope.seasonPart;
        var week = $scope.week === "All Weeks" ? "null" : $scope.week;
        $http({
            url: "../../source/games.php",
            method: "GET",
            params: {seasonMonth: season, season: 2016}
        }).then(function(response){
            $scope.games = response.data.teams;
            $scope.headers = response.data.header;
        }).finally( function () {
            $scope.loading = false;
        });
    };

    init();

    function init() {

        $scope.prikazi();
        $http.get("../../source/player_listOfTeamsInit.php")
            .then(function(response) {
                $scope.teams = response.data;
            });
    };

    $scope.isNameProp = function (propName) {
        if(propName === "team1" || propName === "team2")
            return true;
        else
            return false;
    };
});