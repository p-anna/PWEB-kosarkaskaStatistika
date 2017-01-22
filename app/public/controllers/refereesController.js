app.controller('refereesController', function($scope, $timeout, $http){
    $scope.referees = [];
    $scope.headers = [];
    $scope.teams = [];

    $scope.team = "All Teams";
    $scope.seasonPart = "Full Season";

    $scope.propertyName = '';
    $scope.reverse = true;

    $scope.sortBy = function(propertyName) {
        $scope.reverse = ($scope.propertyName === propertyName) ? !$scope.reverse : false;
        $scope.propertyName = propertyName;
    };



    $scope.prikazi = function(){
        /* priprema parametara */
        var teamID = null;
        for(t in $scope.teams){
            if($scope.team === t.teamName)
                teamID = t.idTeam;
        }
        var season = $scope.seasonPart === "Full Season" ? null : $scope.seasonPart;

        $http({
            url: "../../source/primercic.php",
            method: "GET",
            params: {team: teamID, seasonPart: season}
        }).then(function(response){
            $scope.referees = response.data.referees;
            $scope.headers = response.data.header;
            $scope.propertyName = $scope.headers[0].propertyName; //ovo ne znam sta je
        });
    }

    init();

    function init() {
        $scope.prikazi();
        $http.get("../../source/player_listOfTeamsInit.php")
            .then(function(response) {
                $scope.teams = response.data;
            });
    }

    $scope.isNameProp = function (propName) {
        if(propName.contains("Name") || propName.contains("name"))
            return false;
        else
            return true;
    };
});