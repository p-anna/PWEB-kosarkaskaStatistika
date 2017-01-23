app.controller('teamController', function($scope, $timeout, $http, $routeParams){

    //ovde sad za http get treba da se iskoriti $routeParams.id da bi se dobili podaci za igraca sa tim id-jem
    //za sad se prikazuje jedan zakucan
    $scope.headers = [];
    $scope.games = [];

    $scope.seasonPart = "Full Season";

    init();

    function init() {
        var season = $scope.seasonPart == "Full Season" ? "null" : $scope.seasonPart;

        $http({
            url: "../../source/team.php",
            method: "GET",
            params: {season: 2016, seasonMonth: season, teamId: $routeParams.id}
        }).then(function(response){
            $scope.headers = response.data.header;
            $scope.games = response.data.players;
            $scope.teamName = response.data.teamName;
        });
    }

    $scope.isNameProp = function (propName) {

        if(propName === "ATeam"){
            return true;
        }
        else{
            return false;
        }
    };
});