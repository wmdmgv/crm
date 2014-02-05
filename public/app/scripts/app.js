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
        templateUrl: 'views/new.html',
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
  .run(['$rootScope', '$location', '$resource', function($rootScope, $location, $resource){
    var path = function() { return $location.path();};
    $rootScope.$watch(path, function(newVal, oldVal){
      $rootScope.activetab = newVal;
    });
    // Check login
    var Api = $resource('/api');
    $rootScope.roleChecked = false;
    $rootScope.checkRole = function (role) {
      if (!$rootScope.roleChecked) {
        Api.get({}, function(data) {
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

  }])
;
