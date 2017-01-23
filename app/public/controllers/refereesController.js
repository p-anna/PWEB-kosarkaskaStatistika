app.controller('refereesController', function($scope, $timeout, $http){
    $scope.referees = [];
    $scope.headers = [];
    $scope.teams = [];

    $scope.team = "All Teams";
    $scope.referee = "All Referees";

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
        var teamID = null;
        for(t in $scope.teams){
            if($scope.team === t.teamName)
                teamID = t.idTeam;
        }
        var season = $scope.seasonPart === "Full Season" ? null : $scope.seasonPart;

        $http({
            url: "../../source/referee.php",
            method: "GET",
            params: {teamId: $scope.team, refId: $scope.referee}
        }).then(function(response){
            $scope.referees = response.data.referees;
            $scope.headers = response.data.header;
            $scope.propertyName = $scope.headers[0].propertyName; //ovo ne znam sta je
        }).finally(function () {
            $scope.loading = false;
        });
    }

    init();

    function init() {
        $scope.loading = true;
        $http.get("../../source/initRefereesAndTeams.php")
            .then(function(response) {
                $scope.teams = response.data.teams;
                $scope.referees = response.data.referees;
            });
        $scope.prikazi();
    }




    $scope.isNameProp = function (propName) {

        if(propName === "gameName"){
            return true;
        }
        else{
            return false;
        }
    };
});