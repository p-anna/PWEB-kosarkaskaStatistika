app.controller('refereesController', function($scope, $timeout, $http){
    $scope.referees = [];
    $scope.teams = [];
    $scope.selected1 = "Average - Per Game";
    $scope.selected2 = "All Teams";
    $scope.selected3 = "Full Season";

    $scope.propertyName = 'Name';
    $scope.reverse = true;

    $scope.sortBy = function(propertyName) {
        $scope.reverse = ($scope.propertyName === propertyName) ? !$scope.reverse : false;
        $scope.propertyName = propertyName;
    };


    $scope.prikazi = function(){
        alert("Ovo je izabran: " + $scope.selected1 + $scope.selected2 + $scope.selected3);
        /* ovde neko sortiranje */
    };

    init();

    function init() {
        $http.get("data/referees.json")
            .then(function(response) {
                $scope.referees = response.data.referees;
            });
        $http.get("data/teams.json")
            .then(function(response) {
                $scope.teams = response.data.teams;
            });
    }
});