app.controller('refereeController', function($scope, $timeout, $http, $routeParams){

    //ovde sad za http get treba da se iskoriti $routeParams.id da bi se dobili podaci za igraca sa tim id-jem
    //za sad se prikazuje jedan zakucan
    $scope.referee = {};
    $scope.headers = [];

    init();

    function init() {
        $http.get("data/referees.json")
            .then(function (response) {
                $scope.headers = response.data.header;
                $scope.referee = response.data.referee;
            });
    }
});