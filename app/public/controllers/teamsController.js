app.controller('teamsController', function($scope, $timeout, $http){
    $scope.teams = [];
    $scope.headers = [];

    $scope.statisticType = "Average | Per Game";
    $scope.seasonPart = "Full Season";
    $scope.week = "All Weeks";

    $scope.propertyName = '';
    $scope.reverse = true;

    $scope.loading = false;

    $scope.sortBy = function(propertyName) {
        $scope.reverse = ($scope.propertyName === propertyName) ? !$scope.reverse : false;
        $scope.propertyName = propertyName;
    };



    $scope.prikazi = function(){
        $scope.loading = true;
        /* priprema parametara */
        var season = $scope.seasonPart === "Full Season" ? null : $scope.seasonPart;
        var week = $scope.week === "All Weeks" ? null : $scope.week;

        $http({
            url: "../../source/primercic.php",
            method: "GET",
            params: {statisticType: $scope.statisticType, seasonPart: season, week: week}
        }).then(function(response){
            $scope.referees = response.data.referees;
            $scope.headers = response.data.header;
        }).finally(function () {
            $scope.loading = false;
        });
    };

    init();

    function init() {
        $scope.loading = true;
        $http({
            url: "../../source/primercic.php",
            method: "GET",
            params: {selected1: $scope.selected1, selected2: $scope.selected2, selected3: $scope.selected3}
        }).then(function(response){
            $scope.referees = response.data.referees;
            $scope.headers = response.data.header;
            $scope.propertyName = $scope.headers[0].propertyName;
        }).finally(function () {
            $scope.loading = false;
        });
    };

    $scope.isNameProp = function (propName) {

        if(propName === "teamName"){
            return true;
        }
        else{
            return false;
        }
    };
});