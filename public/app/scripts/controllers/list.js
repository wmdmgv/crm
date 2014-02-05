angular.module('myApp')
  .controller('ListCtrl', function($scope, $resource, $http, $timeout, ngTableParams) {
    var ApiOrders = $resource('/api/orders');
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
      count: 10,          // count per page
      sorting: {
        name: 'asc'     // initial sorting
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