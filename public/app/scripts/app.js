'use strict';

angular.module('myApp', [
    'ngCookies',
    'ngResource',
    'ngSanitize',
    'ngRoute',
    'ngTable'
  ])
  .config(function ($routeProvider) {
    $routeProvider
      .when('/', {
        templateUrl: 'views/main.html',
        controller: 'MainCtrl'
      })
      .when('/list', {
        templateUrl: 'views/list.html',
        controller: 'ListCtrl'
      })
      .when('/new', {
        templateUrl: 'views/order.html',
        controller: 'JobCtrl'
        //resolve:{auth:"Initial"}
      })
      .when('/edit/:orderId', {
        templateUrl: 'views/order.html',
        controller: 'JobCtrl'
      })
      .otherwise({
        redirectTo: '/'
      });

  })
  .directive('loadingContainer', function () {
    return {
      restrict: 'A',
      scope: false,
      link: function (scope, element, attrs) {
        var loadingLayer = angular.element('<div class="loading"></div>');
        element.append(loadingLayer);
        element.addClass('loading-container');
        scope.$watch(attrs.loadingContainer, function (value) {
          loadingLayer.toggleClass('ng-hide', !value);
        });
      }
    };
  })
  .factory('Initial', function($rootScope, $resource){

    return function(scope) {
      //    // Check login
      var Api = $resource('/api');
      $rootScope.roleChecked = false;
      $rootScope.checkRole = function (role) {
        if (!$rootScope.roleChecked) {
          Api.get({}, function(data) {
            $rootScope.userName = data.name;
            $rootScope.userId = data.id;
            $rootScope.firmUser = data.firmuser;
            console.log(data.role);
            console.log(role);
            if ((role == data.role) || (role == 1 && data.role != null)) {
              // Message thats Ok
            } else {
              // Goto login
              location.href = "/user/login";
            }

          });
          $rootScope.roleChecked = true;
        }
      }
    }
  })
  .run(['$rootScope', '$location', '$resource', function($rootScope, $location, $resource){
    var path = function() { return $location.path();};
    $rootScope.$watch(path, function(newVal, oldVal){
      $rootScope.activetab = newVal;
    });
    $rootScope.translate = function(lng,text) {
      var dict = {'view' : {'ru' : 'Просмотр', 'en' : 'View'}};
      return dict[text][lng];
    }
//    // Check login
//    var Api = $resource('/api');
//    $rootScope.roleChecked = false;
//    $rootScope.checkRole = function (role) {
//      if (!$rootScope.roleChecked) {
//        Api.get({}, function(data) {
//          $rootScope.userName = data.name;
//          $rootScope.userId = data.id;
//          console.log(data.role);
//          console.log(role);
//          if ((role == data.role) || (role == 1 && data.role != null)) {
//            // Message thats Ok
//          } else {
//            // Goto login
//            location.href = "/user/login";
//          }
//        });
//        $rootScope.roleChecked = true;
//      }
//    }

  }])
;
