'use strict'

var autoDiffControllers = angular.module("autoDiffControllers",[]);

autoDiffControllers.controller('TasksController',['$scope','$http',
    function($scope,$http){
        console.log("TasksController");
        $http.get('Task/getTasks').success(
            function(data)
            {
                $scope.tasks = data;
            }
        )
    }
]);

autoDiffControllers.controller("TabsCtrl",['$scope','$http','$state','$stateParams',
    function($scope, $http,$state,$stateParams) {
        console.log("TabsCtrl");

        $scope.tabs =
            [
                {title: "info" , route: "task.info",  active: false},
                {title: "files", route: "task.files", active: false},
                {title: "diffed", route: "task.diffed", active: false},
                {title: "log", route: "task.log", active: false}
            ];
        $scope.go = function(route)
        {
            $state.go(route);
        }
        $scope.active = function(route){
            return $state.is(route);
        };

        $scope.$on("$stateChangeSuccess", function() {
            $scope.tabs.forEach(function(tab) {
                tab.active = $scope.active(tab.route);
            });
        });

}]);

autoDiffControllers.controller("InfoController",['$scope',"$state","$stateParams","$http",
    function($scope,$state,$stateParams,$http)
    {
        $http.post('Task/getTaskByName',{"patchName": $stateParams.patchName}).success(
            function(data)
            {
                $scope.task = data;
            }
        )

    }
]);

autoDiffControllers.controller("FilesController",['$scope',"$state","$stateParams",
    function($scope,$state,$stateParams)
    {

    }
]);

autoDiffControllers.controller("DiffedController",['$scope',"$state","$stateParams","$http",
    function($scope,$state,$stateParams,$http)
    {
        $http.post('Task/getDiffs',{"patchName": $stateParams.patchName}).success(
            function(data)
            {
                $scope.diffed = data;
            }
        )

    }
]);
autoDiffControllers.controller("LogController",['$scope',"$state","$stateParams","$http",
    function($scope,$state,$stateParams,$http)
    {
        console.log("LogController");
        $scope.log = "[+]Downloading patches...\n" +
            "Downloding file  	http://download.microsoft.com/download/8/4/B/84B4EA25-1933-484E-A8B8-AE07492AFC31/Windows6.0-KB2957689-x86.msu\n" +
            "Unpacker detected .msu package\n" +
            "Unpacking files...." +
            "";
    }
]);
