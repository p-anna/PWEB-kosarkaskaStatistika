
app.controller('playersController', function($scope, $timeout, $http){
    $scope.players = [];
    $scope.teams = [];
    $scope.headers = [];
    $scope.init = init;

    $scope.statisticType = "Average | Per Game";
    $scope.team = "All Teams";
    $scope.position = "All Positions";
    $scope.seasonPart = "Full Season";
    $scope.week = "All Weeks";

    $scope.propertyName = '';
    $scope.reverse = true;

    $scope.loading = false;

    $scope.sortBy = function(propertyName) {
        $scope.reverse = ($scope.propertyName === propertyName) ? !$scope.reverse : false;
        $scope.propertyName = propertyName;
    };




    init();

    function init() {

        $http.get("../../source/player_listOfTeamsInit.php")
            .then(function(response) {
                $scope.teams = response.data;
                $scope.prikazi();
            });
    }
    $scope.prikazi = function(){
        /* priprema parametara */
        var teamID = "null";
        for(var t in $scope.teams){ /*NE RADI, NE ZNAM STO*/
            console.log($scope.team + " " + t.teamName);
            if($scope.team == t.teamName)
                teamID = t.idTeam;
        }
        //alert(teamID);
        var position = $scope.position === "All Positions" ? "null" : $scope.position;
        var season = $scope.seasonPart === "Full Season" ? "null" : $scope.seasonPart;
        var week = $scope.week === "All Weeks" ? "null" : $scope.week;

        $http({
            url: "../../source/players.php",
            method: "GET",
            params: {statisticType: $scope.statisticType, idTeam: teamID, position: position,
                seasonPart: season, week: week}
        }).then(function(response){
            $scope.players = response.data.players;
            $scope.headers = response.data.header;
            $scope.propertyName = $scope.headers[0].nameOfProperty;
        });
    }

    $scope.isNameProp = function (propName) {

        if(propName === "playerName"){
            return true;
        }
        else{
            return false;
        }
    }


});