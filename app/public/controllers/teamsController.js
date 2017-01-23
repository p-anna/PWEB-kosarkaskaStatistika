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
        var season = $scope.seasonPart === "Full Season" ? "null" : $scope.seasonPart;
        var week = $scope.week === "All Weeks" ? "null" : $scope.week;

        $http({
            url: "../../source/teams.php",
            method: "GET",
            params: {statisticType: $scope.statisticType, seasonMonth: season, week: week, season: 2016}
        }).then(function(response){
            $scope.teams = response.data.players;
            $scope.headers = response.data.header;
        }).finally(function () {
            $scope.loading = false;
        });
    };

    init();

    function init() {
        $scope.loading = true;
        $scope.prikazi();
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