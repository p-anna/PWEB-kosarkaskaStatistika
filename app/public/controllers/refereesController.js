app.controller('refereesController', function($scope, $timeout, $http){
    $scope.referees = [];
    $scope.teams = [];

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