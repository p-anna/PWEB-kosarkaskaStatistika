app.controller('gamesController', function($scope, $timeout, $http){

    $scope.games = [];

    init();

    function init() {
        $http.get("data/games.json")
            .then(function(response) {
                $scope.games = response.data.games;
            });
    }
});