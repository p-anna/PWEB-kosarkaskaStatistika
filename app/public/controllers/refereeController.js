app.controller('refereeController', function($scope, $timeout, $http, $routeParams){

    $scope.referee = {};
    $scope.headers = [];
    $scope.loading = false;

    init();

    function init() {
        $scope.loading = true;
        $http({
            url: "../../source/primercic.php",
            method: "GET",
            params: {screen: "referees", id: $routeParams.id}
        }).then(function (response) {
                $scope.headers = response.data.header;
                $scope.referee = response.data.referee;
            }).finally(function () {
            $scope.loading = false;
        });
    }
});