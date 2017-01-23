app.controller('teamController', function($scope, $timeout, $http, $routeParams){

    //ovde sad za http get treba da se iskoriti $routeParams.id da bi se dobili podaci za igraca sa tim id-jem
    //za sad se prikazuje jedan zakucan
    $scope.headers = [];
    $scope.games = [];
    $scope.init = init;

    $scope.seasonPart = "Full Season";
    $scope.seasons = "2016-2017";

    $scope.prikazi = function(){
        var season = $scope.seasonPart == "Full Season" ? "null" : $scope.seasonPart;
        var siz = $scope.seasons == "2016-2017" ? 2016 : 2015;
        $http({
            url: "../../source/team.php",
            method: "GET",
            params: {season: siz, seasonMonth: season, teamId: $routeParams.id}
        }).then(function(response){
            $scope.headers = response.data.header;
            $scope.games = response.data.players;
            $scope.teamName = response.data.teamName;
        });
    }

    init();

    function init() {
        $scope.prikazi();
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