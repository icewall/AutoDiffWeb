'use strict'

var autoDiffApp = angular.module('autoDiffApp',[
    "ui.router",
    "autoDiffControllers",
    'ui.bootstrap'
]);

autoDiffApp.run(
    [ '$rootScope', '$state', '$stateParams','$location',
        function ($rootScope, $state, $stateParams,$location) {

            $rootScope.$state = $state;
            $rootScope.$stateParams = $stateParams;
        }
    ]
)

//autoDiffApp.config(
//['$routeProvider',
//    function($routeProvider){
//        $routeProvider.
//            when('/Tasks',{
//                templateUrl:"templates/diff/tasks.html",
//                controller: "TasksController"
//            }).
//            when('/Add',{
//                templateUrl:"templates/diff/add.html"
//            }).
//            when('/Task/:name/:tabName',{
//                templateUrl: "templates/task/tabs.html",
//                controller:  "TabsCtrl"
//            }).
//            when("/Statistics",{
//                templateUrl: "templates/task/tabs.html",
//                controller: "testController"
//            }).
//            otherwise({
//                templateUrl:"templates/welcome.html"
//            });
//    }
//]
//);
autoDiffApp.config(function($stateProvider, $urlRouterProvider,$httpProvider){

    $urlRouterProvider
        .when("/index.html","/index.html#/")
        ;

    $stateProvider
        .state("home",{
            url: "/",
            templateUrl: "templates/welcome.html"
        }
    )
        .state("add",{
            url:"/Add",
            templateUrl:"templates/add.html"
        })
        .state("diff",{
            url:"/Tasks",
            templateUrl: "templates/tasks.html",
            controller: "TasksController"
        })
        .state("task",{
            abstract: true,
            url: "/Task/:patchName",
            templateUrl: "templates/task/tabs.html",
            controller: "TabsCtrl"
        })
            .state("task.info",{
                url: "/info",
                templateUrl: "templates/task/info.html",
                controller:   "InfoController"
            })
            .state("task.files",{
                url: "/files",
                templateUrl: "templates/task/files.html",
                controller:   "FilesController"
            })
            .state("task.diffed",{
                url: "/diffed",
                templateUrl: "templates/task/diffed.html",
                controller:   "DiffedController"
            })
            .state("task.log",{
                url: "/log",
                templateUrl: "templates/task/log.html",
                controller:   "LogController"
            })

    // Use x-www-form-urlencoded Content-Type
    $httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';

    /**
     * The workhorse; converts an object to x-www-form-urlencoded serialization.
     * @param {Object} obj
     * @return {String}
     */
    var param = function(obj) {
        var query = '', name, value, fullSubName, subName, subValue, innerObj, i;

        for(name in obj) {
            value = obj[name];

            if(value instanceof Array) {
                for(i=0; i<value.length; ++i) {
                    subValue = value[i];
                    fullSubName = name + '[' + i + ']';
                    innerObj = {};
                    innerObj[fullSubName] = subValue;
                    query += param(innerObj) + '&';
                }
            }
            else if(value instanceof Object) {
                for(subName in value) {
                    subValue = value[subName];
                    fullSubName = name + '[' + subName + ']';
                    innerObj = {};
                    innerObj[fullSubName] = subValue;
                    query += param(innerObj) + '&';
                }
            }
            else if(value !== undefined && value !== null)
                query += encodeURIComponent(name) + '=' + encodeURIComponent(value) + '&';
        }

        return query.length ? query.substr(0, query.length - 1) : query;
    };

// Override $http service's default transformRequest
    $httpProvider.defaults.transformRequest = [function(data) {
        return angular.isObject(data) && String(data) !== '[object File]' ? param(data) : data;
    }];

});