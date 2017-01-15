app.controller('playerController', function($scope, $timeout, $http, $routeParams){

    //ovde sad za http get treba da se iskoriti $routeParams.id da bi se dobili podaci za igraca sa tim id-jem
    //za sad se prikazuje jedan zakucan
    $scope.player = {};

    init();

    function init() {
        $http.get("data/player.json")
            .then(function(response) {
                $scope.player = response.data;
            });
    }
});