angular.module('myApp')
  .controller('JobCtrl', function($scope, $resource, $http, $timeout, ngTableParams) {
    var ApiOrders = $resource('/api/orders');
    var ApiFirm = $resource('/api/firms');
    var ApiClient = $resource('/api/clients');
    var ApiDevice = $resource('/api/devices')

    $scope.form = {};
    ApiFirm.get({}, function(data) {
        $scope.firms = data.result;
    });

    $scope.master = {};

    $scope.update = function(user) {
      $scope.master = angular.copy(user);
    };

    $scope.reset = function() {
      $scope.user = angular.copy($scope.master);
    };

    $scope.reset();







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



  });