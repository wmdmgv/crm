angular.module('myApp')
  .controller('JobCtrl', function($rootScope, $scope, $q, $resource, $http, $timeout, ngTableParams, $routeParams, Initial) {
    Initial();
    $scope.order = {firm:{},state:0};
    $scope.jobsArray = [];
    $scope.orderId = $routeParams.orderId;
    $scope.progressbar = 0;
   // var ApiOrders = $resource('/api/orders');
    var ApiOrder = $resource('/api/order/:orderId');
    var ApiFirm = $resource('/api/firms');
    var ApiUser = $resource('/api/users');
    var ApiStatus = $resource('/api/statuses');
    var ApiClient = $resource('/api/clients');
    var ApiDevice = $resource('/api/devices')
    var ApiJob = $resource('/api/job/:jobId');
    var ApiJobs = $resource('/api/jobs/:orderId');
    //if edit, then load order object and jobs
    if ( $scope.orderId > 0) {
      ApiOrder.get({'orderId': $scope.orderId}, function(data) {
        $scope.order = data.result;
        $scope.progressbar += 10;
        console.log(data);
      });
    } else {
      //Wait for data
      function updateUserId() {
        if ($rootScope.userId == undefined) {
          $timeout(updateUserId, 100);
        }
        //Setup default user
        $scope.order.user_id = $rootScope.userId;
        //Setup default Firm
        $scope.order.firm.id = $rootScope.firmUser;
      }
      $timeout(updateUserId, 100);
      $scope.progressbar += 10;
    }

    $scope.form = {};

    ApiFirm.get({}, function(data) {
        $scope.firms = data.result;
        $scope.progressbar += 10;
    });
    ApiUser.get({}, function(data) {
      $scope.users = data.result;
      $scope.progressbar += 10;
    });
    ApiStatus.get({}, function(data) {
      $scope.statuses = data.result;
      $scope.progressbar += 10;
    });
    ApiClient.get({}, function(data) {
      $scope.clients = data.result;
      $scope.progressbar += 60;
    });




//    $scope.master = {};
//
//    $scope.update = function(user) {
//      $scope.master = angular.copy(user);
//    };
//
//    $scope.reset = function() {
//      $scope.user = angular.copy($scope.master);
//    };
//
//    $scope.reset();

    // Initial
  //  $scope.status = 2;

    $scope.update = function () {
      console.log($scope.order);
      $scope.progressbar = 10;
      ApiOrder.save({'orderId': $scope.orderId, order: $scope.order}, function(data) {
        $scope.progressbar = 50;
        console.log(data);
        $scope.order = data.result;
        $scope.orderId = $scope.order.id;
        $timeout(function() {
            $scope.progressbar = 90;
          }, 300);
        $timeout(function() {
          $scope.progressbar = 100;
        }, 800);
      });
    }


    $scope.jobs = [];

    $scope.loadJobs = function() {
      ApiJobs.get({'orderId': $scope.orderId}, function(data) {
        console.log(data.result);

      });
    }

    $scope.addJob = function() {
      var number = $scope.jobs.length + 1;
     // console.log($scope.jobs);
      var newJob = {
        id : 0,
        order_id : $scope.orderId ,
        device_id : null,
        name: 'Test Message',
        comment: 'Comment',
        state : 1,
        price : 0,
        created : null,
        updated: null,
        number: number
      };

      ApiJob.save({ job: newJob}, function(data) {
        var job = data.result;
        $scope.progressbar = 50;
        console.log(data);
//        $scope.order = data.result;
//        $scope.orderId = $scope.order.id;
        newJob.id = job.id;
        newJob.order_id = job.order.id;
        newJob.device_id = job.device.id;
        $scope.jobs.push(newJob);
        $timeout(function() {
          $scope.progressbar = 90;
        }, 300);
        $timeout(function() {
          $scope.progressbar = 100;
        }, 800);
      });


    };

    $scope.saveJob = function(id) {
      console.log(id);
      console.log($scope.jobsArray[id]);

//      ApiJob.save({ jobId: id, job: }, function(data) {
//
//        //TODO: refresh list of job
//
//        //$scope.jobs.push(newJob);
//        $timeout(function() {
//          $scope.progressbar = 90;
//        }, 300);
//        $timeout(function() {
//          $scope.progressbar = 100;
//        }, 800);
//      });

    };

    $scope.delJob = function(id) {

      ApiJob.save({ jobId: id, 'jobdelete': 1}, function(data) {

        //TODO: refresh list of job

        //$scope.jobs.push(newJob);
        $timeout(function() {
          $scope.progressbar = 90;
        }, 300);
        $timeout(function() {
          $scope.progressbar = 100;
        }, 800);
      });

//      for(var i in $scope.jobs){
//        var el = $scope.jobs[i];
//        if (el.number == number) {
//          //delete $scope.jobs[i];
//          $scope.jobs.splice(i, 1);
//        }
//        console.log($scope.jobs);
//        //If you want 'i' to be INT just put parseInt(i)
//        //Do something with el
//      }

      //$scope.jobs.push(newJob);
    };

//    $scope.tableOrders = new ngTableParams({
//      page: 1,            // show first page
//      count: 10,          // count per page
//      sorting: {
//        name: 'asc'     // initial sorting
//      }
//    }, {
//      total: 0,           // length of data
//      getData: function($defer, params) {
//        // $scope.loading = true;
//        // ajax request to api
//        ApiOrders.get(params.url(), function(data) {
//          //$http.post('/api/orders', params.url()).success(function(data) {
//          $timeout(function() {
//            // $scope.loading = false;
//            params.total(data.total);
//            // set new data
//            $defer.resolve(data.result);
//          }, 100);
//        });
//      }
//    });



  });