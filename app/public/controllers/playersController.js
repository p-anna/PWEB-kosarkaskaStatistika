
app.controller('playersController', function($scope, $timeout, $http){
    $scope.players = [];
    $scope.teams = [];

    $scope.statisticType = "Average | Per Game";
    $scope.team = "All Teams";
    $scope.position = "All Positions";
    $scope.seasonPart = "Full Season";
    $scope.week = "All Weeks";

    $scope.propertyName = 'playerName';
    $scope.reverse = true;



    $scope.sortBy = function(propertyName) {
        $scope.reverse = ($scope.propertyName === propertyName) ? !$scope.reverse : false;
        $scope.propertyName = propertyName;
    };


    $scope.prikazi = function(){
        //alert("Ovo je izabran: " + $scope.selected1 + $scope.selected2 + $scope.selected3);


        $http({
            url: "../../source/primercic.php",
            method: "GET",
            params: {statisticType: $scope.statisticType, team: $scope.team, position: $scope.position,
                seasonPart: $scope.seasonPart, week: $scope.week}
        }).then(function(response){
            $scope.players = response.data;
        });

        // $http.get("../../source/primercic.php/:" + $scope.selected1 + "/:" + $scope.selected2 + "/:" + $scope.selected3)
        //     .then(function(response){
        //         $scope.players = response.data;
        //
        //     });
    };

    init();

    function init() {
        $scope.prikazi(); /*treba povezati */
        /*$http.get("data/players.json")
         .then(function(response) {
         $scope.players = response.data.players;
         });*/
        $http.get("../../source/player_listOfTeamsInit.php")
            .then(function(response) {
                $scope.teams = response.data;
            });
    }
});