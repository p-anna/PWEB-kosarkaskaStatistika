app.controller('homeController', function ($scope) {
    $scope.klasaLopte = '';
    $scope.animacija = function (imeStrane, broj){
        var text = "<img id='ball" + broj + "'" + " src='img/ball.png' width='50'/>";
        document.getElementById('teren').innerHTML+=text;
        setTimeout(function(){
            window.location = "#/" + imeStrane;
        }, 3800);

    }
});

/* rootscope proveri */