app.controller('teamsCtrl', function($scope, $timeout){
    $scope.teams = [];

    init();

    function loadJSON(callback) {

        var xobj = new XMLHttpRequest();
        xobj.overrideMimeType("application/json");
        xobj.open('GET', 'data/teams.json', true); // Replace 'my_data' with the path to your file
        xobj.onreadystatechange = function () {
            if (xobj.status == "200") {
                // Required use of an anonymous callback as .open will NOT return a value but simply returns undefined in asynchronous mode
                $timeout(
                    callback(xobj.responseText), 2000
                );
            }
        };
        xobj.send(null);
    };

    function init() {
        loadJSON(function(response) {
            // Parse JSON string into object
            var actual_JSON = JSON.parse(response);
            $scope.teams = actual_JSON.teams;
        });
    };
});