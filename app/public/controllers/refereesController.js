app.controller('refereesController', function($scope, $timeout, $http){
    $scope.referees = [];
    $scope.headers = [];

    $scope.selected1 = "Average - Per Game";
    $scope.selected2 = "All Teams";
    $scope.selected3 = "Full Season";

    $scope.propertyName = '';
    $scope.reverse = true;

    $scope.sortBy = function(propertyName) {
        $scope.reverse = ($scope.propertyName === propertyName) ? !$scope.reverse : false;
        $scope.propertyName = propertyName;
    };


    $scope.prikazi = function(){
        $http({
            url: "../../source/primercic.php",
            method: "GET",
            params: {selected1: $scope.selected1, selected2: $scope.selected2, selected3: $scope.selected3}
        }).then(function(response){
            $scope.referees = response.data.referees;
            $scope.headers = response.data.header;
        });
    };

    init();

    function init() {
        $http({
            url: "../../source/primercic.php",
            method: "GET",
            params: {selected1: $scope.selected1, selected2: $scope.selected2, selected3: $scope.selected3}
        }).then(function(response){
            $scope.referees = response.data.referees;
            $scope.headers = response.data.header;
            $scope.propertyName = $scope.headers[0].propertyName;
        });
    };

    $scope.isNameProp = function (propName) {
        if(propName.contains("Name") || propName.contains("name"))
            return false;
        else
            return true;
    };
});