angular.module('myApp')
  .controller('ListCtrl', function($scope, $resource, $http, $timeout, ngTableParams) {
    var Api = $resource('/api/orders');
    console.log("a");
    $scope.tableOrders = new ngTableParams({
      page: 1,            // show first page
      count: 10,          // count per page
      sorting: {
        name: 'asc'     // initial sorting
      }
    }, {
      total: 0,           // length of data
      getData: function($defer, params) {
        // ajax request to api
        Api.get(params.url(), function(data) {
       // $http.post('/api/orders/table', params.url()).success(function(data) {
          $timeout(function() {
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