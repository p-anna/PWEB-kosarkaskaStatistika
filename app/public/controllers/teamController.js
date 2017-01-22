app.controller('teamController', function($scope, $timeout, $http, $routeParams){

    //ovde sad za http get treba da se iskoriti $routeParams.id da bi se dobili podaci za igraca sa tim id-jem
    //za sad se prikazuje jedan zakucan
    $scope.headers = [];
    $scope.games = [];

    console.log($routeParams.id);
    init();

    function init() {
        $http({
            url: "../../source/primercic.php",
            method: "GET",
            params: {screen: "teams", id: $routeParams.id}
        }).then(function(response){
            $scope.headers = response.data.header;
            $scope.games = response.data.games;
        });
    }
});