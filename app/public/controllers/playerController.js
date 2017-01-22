app.controller('playerController', function($scope, $timeout, $http, $routeParams){

    //ovde sad za http get treba da se iskoriti $routeParams.id da bi se dobili podaci za igraca sa tim id-jem
    //za sad se prikazuje jedan zakucan
    $scope.player = {};
    $scope.headers = [];

    console.log($routeParams.id);
    init();

    function init() {
        $http({
            url: "../../source/primercic.php",
            method: "GET",
            params: {screen: "players", id: $routeParams.id}
        }).then(function(response){
            $scope.player = response.data[0];
            //$scope.headers = response.data.header;
        });
    }
});