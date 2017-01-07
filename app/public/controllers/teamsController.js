app.controller('teamsController', function($scope, $timeout, $http){
    $scope.teams = [];

    init();

    function init() {
        $http.get("data/teams.json")
            .then(function(response) {
                $scope.teams = response.data.teams;
            });
    }
});