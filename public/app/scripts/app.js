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
        controller: 'MainCtrl'
      })
      .otherwise({
        redirectTo: '/'
      });
  });
//  .directive('loadingContainer', function () {
//    return {
//      restrict: 'A',
//      scope: false,
//      link: function (scope, element, attrs) {
//        var loadingLayer = angular.element('<div class="loading"></div>');
//        element.append(loadingLayer);
//        element.addClass('loading-container');
//        scope.$watch(attrs.loadingContainer, function (value) {
//          loadingLayer.toggleClass('ng-hide', !value);
//        });
//      }
//    };
//  });
