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
autoDiffControllers.controller("AddTaskController",['$scope','$http','$state',
    function ($scope,$http,$state){
        $scope.formData = {};
        $scope.products = [
            { name: "Any" ,        type: "any"},
            { name: "IE"  ,        type: "IE"},
            { name: "FlashPlayer", type: "flash"},
            { name: "Java",        type: "java"}
        ];
        $scope.formData.product = $scope.products[0].type;

        $scope.modes = [
            {name : "Automatically", type: "auto"},
            {name : "Manually", type: "manual"}
        ];
        $scope.formData.mode = $scope.modes[1].type;

        $http.get('Agent/getAgents').success(
            function(data)
            {
                data.push({id:"DEBUG_AGENT"});
                console.log(data);
                $scope.agents = data;
            }
        )

        $scope.processForm = function()
        {
            console.log("processForm");
            $http.post("Task/addTask",$scope.formData);
            //we should also add command for specified agent
            //MOVED TO BACKEND
//            $http.post("Command/addCommand",
//                {
//                    agent: $scope.formData["agent"],
//                    command: "getTask",
//                    params: ""
//                }
//            );

            $state.go("task.info",{task_name : $scope.formData.name});
        };

    }
]);
autoDiffControllers.controller("TabsCtrl",['$scope','$http','$state','$stateParams',
    function($scope, $http,$state,$stateParams) {
        console.log("TabsCtrl");
        if(! $stateParams.hasOwnProperty("task_name") || $stateParams.task_name == null)
        {
            console.log("There is no task_name redirect to tasks ...");
            $state.go("tasks");
            return;
        }
        else
        {
            console.log($stateParams.task_name);
        }
        /*
            Get agent name related with this task
         */
        $http.post("Task/getTaskByName",{task_name: $stateParams.task_name}).success(
            function(data)
            {
                sessionStorage.agent = data["agent_id"];
            });


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
        $http.post('Task/getTaskByName',{"task_name": $stateParams.task_name}).success(
            function(data)
            {
                $scope.task = data;
            }
        )
    }
]);

autoDiffControllers.controller("FilesController",['$scope',"$state","$stateParams","$http",
    function($scope,$state,$stateParams,$http)
    {
        /*
            At the beginning just get the files !
         */
        $http.post("Storage/getFiles",{"task_name": $stateParams.task_name}).success(
            function(data)
            {
                $scope.files = data;
            }
        )
        $scope.formDiff = {}

        /*
            Unpacking things
        */
        $scope.types = [
                        {name: "Auto", type:"auto"},
                        {name: "CAB",  type:".cab"},
                        {name: "ZIP",  type:".zip"}
                       ];

        $scope.formDiff.type = "auto";

        $scope.formUnpackProcess = function()
        {
            console.log("formUnpackProcess");
            $scope.formDiff.task_name = $stateParams.task_name;
            $http.post("Command/addCommand",
                        {agent : sessionStorage.agent,
                         command : "unpack",
                         params  : JSON.stringify($scope.formDiff)
                        }
            );
        }

        /*
            Files things
        */
        $scope.formAddDiffProcess = function()
        {
            console.log("Adding diff");
            $scope.formDiff.task_name = $stateParams.task_name;
            $http.post("Diff/addDiff",$scope.formDiff);
        }
    }
]);

autoDiffControllers.controller("DiffedController",['$scope',"$state","$stateParams","$http",
    function($scope,$state,$stateParams,$http)
    {
        $http.post('Task/getDiffs',{"task_name": $stateParams.task_name}).success(
            function(data)
            {
                $scope.diffed = data;
            }
        )

    }
]);
autoDiffControllers.controller("LogController",['$scope',"$state","$stateParams","$http","$interval",
    function($scope,$state,$stateParams,$http,$interval)
    {
        var logHandler = undefined;
        console.log("LogController");
        $scope.log = "";
        if(!angular.isDefined(logHandler))
        {
            logHandler = $interval(function(){
                $http.post("Logger/getLog",{task_name : $stateParams.task_name}).success(
                    function(data)
                    {
                        if($scope.log !=  data)
                        {
                            $scope.log = data
                        }
                    }
                );
            },1000);
        }


        $scope.$on("$destroy",function(){
            if(angular.isDefined(logHandler))
            {
                $interval.cancel(logHandler);
                logHandler = undefined;
            }
        });

    }
]);
