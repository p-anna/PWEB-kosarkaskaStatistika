app.controller('refereeController', function($scope, $timeout, $http, $routeParams){

    $scope.referee = {};
    $scope.headers = [];

    init();

    function init() {
        $http({
            url: "../../source/primercic.php",
            method: "GET",
            params: {screen: "referees", id: $routeParams.id}
        }).then(function(response){
            $scope.headers = response.data.header;
            $scope.referee = response.data.referee;
        });
        // $http.get("data/referees.json")
        //     .then(function (response) {
        //         $scope.headers = response.data.header;
        //         $scope.referee = response.data.referee;
        //     });
    }
});