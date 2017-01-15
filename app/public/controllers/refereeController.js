app.controller('refereeController', function($scope, $timeout, $http, $routeParams){

    //ovde sad za http get treba da se iskoriti $routeParams.id da bi se dobili podaci za igraca sa tim id-jem
    //za sad se prikazuje jedan zakucan
    $scope.referee = {};

    init();

    function init() {
        $http.get("data/referee.json")
            .then(function(response) {
                $scope.referee = response.data;
            });
    }
});