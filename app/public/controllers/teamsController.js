app.controller('teamsController', function($scope, $timeout, $http){
    $scope.teams = [];
    $scope.selected1 = "Average - Per Game";
    $scope.selected2 = "All Teams";
    $scope.selected3 = "Full Season";


    $scope.sortiranje = function(){
        alert("Ovo je izabran: " + $scope.selected1 + $scope.selected2 + $scope.selected3);
        /* ovde neko sortiranje */
    };

    init();

    function init() {
        $http.get("data/teams.json")
            .then(function(response) {
                $scope.teams = response.data.teams;
            });
    }
});