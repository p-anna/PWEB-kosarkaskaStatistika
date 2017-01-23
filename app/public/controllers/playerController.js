app.controller('playerController', function($scope, $timeout, $http, $routeParams){

    //ovde sad za http get treba da se iskoriti $routeParams.id da bi se dobili podaci za igraca sa tim id-jem
    //za sad se prikazuje jedan zakucan
    $scope.player = {};
    $scope.headers = [];
    $scope.pStats = [];
    $scope.loading = false;

    $scope.init = init;


    $scope.init();

    function init() {
        $scope.loading = true;
        $http({
            url: "../../source/player.php",
            method: "GET",
            params: {idPlayer: $routeParams.id}
        }).then(function(response){
            $scope.player = response.data.info;
            $scope.pStats = response.data.stats;
            $scope.headers = response.data.header;
        }).finally(function () {
            $scope.loading = false;
        });
    }
});