app.controller('gamesController', function($scope, $timeout, $http){

    $scope.games = [];
    $scope.headers = [];

    $scope.team = "All Teams";
    $scope.seasonPart = "Full Season";
    $scope.week = "All Weeks";

    $scope.propertyName = '';
    $scope.reverse = true;

    $scope.sortBy = function(propertyName) {
        $scope.reverse = ($scope.propertyName === propertyName) ? !$scope.reverse : false;
        $scope.propertyName = propertyName;
    };


    $scope.prikazi = function(){
        $scope.loading = true;

        /* priprema parametara */
        var teamID = null;
        for(t in $scope.teams){
            if($scope.team === t.teamName)
                teamID = t.idTeam;
        }
        var season = $scope.seasonPart === "Full Season" ? null : $scope.seasonPart;
        var week = $scope.week === "All Weeks" ? null : $scope.week;
        $http({
            url: "../../source/primercic.php",
            method: "GET",
            params: {team: teamID, seasonPart: season, week: week}
        }).then(function(response){
            $scope.games = response.data.games;
            $scope.headers = response.data.header;
        }).finally( function () {
            $scope.loading = false;
        });
    };

    init();

    function init() {

        $scope.prikazi();
        /* mozda ce trebati jos nesto u init, pa neka ostane za sad*/
    };

    $scope.isNameProp = function (propName) {
        if(propName === "gameName")
            return true;
        else
            return false;
    };
});