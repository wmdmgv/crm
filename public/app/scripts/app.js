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
        templateUrl: 'views/list.html',
        controller: 'ListCtrl'
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
      .when('/invoicelist/:clientId', {
        templateUrl: 'views/invoices.html',
        controller: 'InvoiceCtrl'
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
    // TODO: refactore with lang
    $rootScope.curlng = 'ru';
    $rootScope.translate = function(lng,text) {
      /*
       array('id' => 0, 'name' => $translate('none')),
       array('id' => 1, 'name' => $translate('received')),
       array('id' => 2, 'name' => $translate('in job')),
       array('id' => 3, 'name' => $translate('done')),
       array('id' => 4, 'name' => $translate('given')),
       array('id' => 5, 'name' => $translate('WTF?'))
       */
      var dict = {
        'view' : {'ru' : 'Просмотр', 'en' : 'View'},
        'Order' : {'ru' : 'Заказ', 'en' : 'Order'},
        'firm' : {'ru' : 'Фирма', 'en' : 'Firm'},
        'client' : {'ru' : 'Клиент', 'en' : 'Client'},
        'name' : {'ru' : 'Название', 'en' : 'Name'},
        'comment' : {'ru' : 'Комментарий', 'en' : 'Comment'},
        'user' : {'ru' : 'Сотрудник', 'en' : 'User'},
        'Jobs' : {'ru' : 'Работы', 'en' : 'Jobs'},

        'amount' : {'ru' : 'Сумма заказа', 'en' : 'Amount'},
        'status' : {'ru' : 'Статус', 'en' : 'Status'},
        'none' :  {'ru' : 'нет', 'en' : 'none'},
        'received' :  {'ru' : 'принят', 'en' : 'received'},
        'in job' :  {'ru' : 'в работе', 'en' : 'in job'},
        'done' :  {'ru' : 'завершен', 'en' : 'done'},
        'given' :  {'ru' : 'отдан', 'en' : 'given'},
        'WTF?' :  {'ru' : 'что за...???', 'en' : 'WTF??'},
        'invoiceadd' :  {'ru' : 'Списать сумму', 'en' : 'Add Invoice'},
        'invoiceinfo' :  {'ru' : 'Списана сумма', 'en' : 'Invoice'},




        'copytoamount' : {'ru': 'Заполнить' , 'en' : 'Fill amount'},
        'clientbalance' : {'ru' : 'Баланс клиента', 'en' : 'Client Balance'},
        'summajobs' : {'ru' : 'Сумма работ', 'en' : 'Summ of jobs'}
      };
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
