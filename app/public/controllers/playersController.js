
app.controller('playersController', function($scope, $timeout, $http){
    $scope.players = [];
    $scope.teams = [];
    $scope.headers = [];

    $scope.statisticType = "Average | Per Game";
    $scope.team = "All Teams";
    $scope.position = "All Positions";
    $scope.seasonPart = "Full Season";
    $scope.week = "All Weeks";

    $scope.propertyName = '';
    $scope.reverse = true;



    $scope.sortBy = function(propertyName) {
        $scope.reverse = ($scope.propertyName === propertyName) ? !$scope.reverse : false;
        $scope.propertyName = propertyName;
    };


    // $scope.prikazi = function(){
            /* priprema parametara */
            // var teamID = null;
            // for(t in $scope.teams){
            //     if($scope.team === t.teamName)
            //         teamID = t.idTeam;
            // }
            // var position = $scope.position === "All Positions" ? null : $scope.position;
            // var season = $scope.seasonPart === "Full Season" ? null : $scope.seasonPart;
            // var week = $scope.week === "All Weeks" ? null : $scope.week;

    //     $http({
    //         url: "../../source/primercic.php",
    //         method: "GET",
    //         params: {statisticType: $scope.statisticType, team: teamID, position: position,
    //             seasonPart: season, week: week}
    //     }).then(function(response){
    //         $scope.players = response.data.players;
    //         $scope.headers = response.data.header;
    //         $scope.propertyName = $scope.headers[0].nameOfProperty;
    //     });
    // };

    $scope.isNameProp = function (propName) {

        // if(propName.contains("Name") || propName.contains("name"))
        //     return false;
        // else
        //     return true;

        if(propName === "playerName"){
            return true;
        }
        else{
            return false;
        }
    };

    init();

    function init() {
        //$scope.prikazi();
        $http.get("data/players.json")
            .then(function (response) {
            $scope.players = response.data.players;
            $scope.headers = response.data.header;
            $scope.propertyName = $scope.headers[0].nameOfProperty;
        });

        // $http.get("data/teams.json")
        //     .then(function (response) {
        //         $scope.teams = response.data.teams;
        //     });



        $http.get("../../source/player_listOfTeamsInit.php")
            .then(function(response) {
                $scope.teams = response.data;
            });

    }


});