angular.module('myApp')
  .controller('ListCtrl', function($rootScope, $scope, $resource, $q, $http, $timeout, ngTableParams, Initial) {
    Initial();
    var ApiOrders = $resource('/api/orders');
    var ApiStatus = $resource('/api/statuses');
    var ApiFirm = $resource('/api/firms');
    var ApiUser = $resource('/api/users');
    var ApiClient = $resource('/api/clients');



    $scope.recheck = function () {
      $timeout(function() {
        console.log("AAAAA");
        $rootScope.roleChecked = false;
        $rootScope.checkRole(1);
        $scope.recheck();
      }, 10000);
    }
    $scope.recheck();

    ApiStatus.get({}, function(data) {
      $scope.statuses = data.result;
    });
    ApiFirm.get({}, function(data) {
      $scope.firms = data.result;
    });
    ApiUser.get({}, function(data) {
      $scope.users = data.result;
    });
    ApiClient.get({}, function(data) {
      $scope.clients = data.result;
    });

    $scope.getWidth = function (jobs_cnt) {
      //console.log(Math.round(70/jobs_cnt));
      return Math.round(70/jobs_cnt);

    }

//    var inArray = Array.prototype.indexOf ?
//      function (val, arr) {
//        return arr.indexOf(val)
//      } :
//      function (val, arr) {
//        var i = arr.length;
//        while (i--) {
//          if (arr[i] === val) return i;
//        }
//        return -1;
//      }
//    var data = $scope.statuses;
//
//    $scope.names = function(column) {
//      var def = $q.defer(),
//        arr = [],
//        names = [];
//      console.log(data);
//      angular.forEach(data, function(item){
//        console.log(item);
//        if (inArray(item.name, arr) === -1) {
//          arr.push(item.name);
//          names.push({
//            'id': item.id,
//            'title': item.name
//          });
//        }
//      });
//      def.resolve(names);
//      return def;
//    };



//    var Api = $resource('/api');
//    $scope.roleChecked = false;
//    $scope.checkRole = function (role) {
//      if (!$scope.roleChecked) {
//        Api.get({}, function(data) {
//         console.log(data.role);
//         console.log(role);
//          if ((role == data.role) || (role == 1 && data.role != null)) {
//              // Message thats Ok
//          } else {
//            // Goto login
//            location.href = "/user/login";
//          }
//        });
//        $scope.roleChecked = true;
//      }
//
//    }

    $scope.tableOrders = new ngTableParams({
      page: 1,            // show first page
      count: 25,          // count per page
      sorting: {
        created: 'desc'     // initial sorting
      }
    }, {
      total: 0,           // length of data
      getData: function($defer, params) {
       // $scope.loading = true;
        // ajax request to api
        ApiOrders.get(params.url(), function(data) {
        //$http.post('/api/orders', params.url()).success(function(data) {
          $timeout(function() {
           // $scope.loading = false;
            params.total(data.total);
            // set new data
            $defer.resolve(data.result);
          }, 100);
        });
      }
    });



//    $scope.orders = [];
//
//    $scope.tableOrders = new ngTableParams({
//      $liveFiltering: false,
//      page: 1,
//      total: 0,
//      count: 10,
//      sorting: {
//        id: 'desc'
//      }
//    });
//
//    $scope.$watch('tableOrders', function(params) {
//      $scope.loading = true;
//      $http.post('/api/orders/table', params.url()).success(function(d) {
//        $scope.loading = false;
//        $scope.orders = d.data;
//        $scope.tableOrders.total = d.total;
//      });
//    });

  });