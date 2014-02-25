angular.module('myApp')
  .controller('CompCtrl', function($rootScope, $scope, $q, $resource, $http, $timeout, ngTableParams, $routeParams, Initial) {
    Initial();

    $scope.jobsArr = {};
    $scope.progressbar = 0;

    var ApiAddDevice = $resource('/api/adddevice');
    var ApiDevice = $resource('/api/devices')


    ApiDevice.get({type:1}, function(data) {
      $scope.devices = data.result;
      $timeout(function() {
        $scope.progressbar = 90;
      }, 300);
      $timeout(function() {
        $scope.progressbar = 100;
      }, 800);
    });

    $scope.jobs = [];

    $scope.loadJobs = function() {
      data = {"result": [
        {"id": 1, "device_id": 7, "name": "", "comment": "", "price": "0.00", 'kolvo': 1},
        {"id": 2, "device_id": 9, "name": "", "comment": "", "price": "0.00", 'kolvo': 1},
        {"id": 3, "device_id": 2, "name": "", "comment": "", "price": "0.00", 'kolvo': 1},
        {"id": 4, "device_id": 1, "name": "", "comment": "", "price": "0.00", 'kolvo': 1},
        {"id": 5, "device_id": 11, "name": "", "comment": "", "price": "0.00", 'kolvo': 1},
        {"id": 6, "device_id": 1, "name": "", "comment": "", "price": "0.00", 'kolvo': 1},
        {"id": 7, "device_id": 1, "name": "", "comment": "", "price": "0.00", 'kolvo': 1}
      ], "total": 7};
//      ApiJobs.get({'orderId': $scope.orderId}, function(data) {
//        $scope.jobs = data.result;
//        $scope.progressbar += 10;
//        $timeout(function() {
//          $scope.recalculateSumm();
//        }, 2000);
//
//      });
      $scope.jobs = data.result;
    }
    $scope.loadJobs();

    $scope.addJob = function() {
      //var number = $scope.jobs.length + 1;
      // console.log($scope.jobs);
      var newJob = {
        id : $scope.jobs.length + 1,
        order_id : $scope.orderId ,
        device_id : 0,
        name: '',
        comment: '',
        state : 1,
        price : 0.0,
        kolvo : 1,
        created : null,
        updated: null

      };
        $scope.jobs.push(newJob);
//      ApiJob.save({ job: newJob}, function(data) {
//        var job = data.result;
//        $scope.progressbar = 50;
//        console.log(data);
////        $scope.order = data.result;
////        $scope.orderId = $scope.order.id;
//        newJob.id = job.id;
//        newJob.order_id = job.order.id;
//        newJob.device_id = job.device.id;
//        $scope.jobs.push(newJob);
//        $timeout(function() {
//          $scope.progressbar = 90;
//        }, 300);
//        $timeout(function() {
//          $scope.progressbar = 100;
//        }, 800);
//      });


    };

//    $scope.saveJob = function(id) {
//      console.log(id);
//      console.log($scope.jobsArr[id]);
//      $scope.progressbar = 50;
//      ApiJob.save({ jobId: id, job: $scope.jobsArr[id]}, function(data) {
//        // $scope.loadJobs();
//        $scope.recalculateSumm();
//        $timeout(function() {
//          $scope.progressbar = 90;
//        }, 300);
//        $timeout(function() {
//          $scope.progressbar = 100;
//        }, 800);
//      });
//
//    };

    $scope.recalculateSumm = function() {
      var sumin = 0.0;
      var sumout = 0.0;
      for(var i in $scope.jobsArr){
        var el = $scope.jobsArr[i];
        //sumin = sumin + parseFloat(el.price);
        $scope.jobsArr[i].summin =  $scope.jobsArr[i].kolvo *  $scope.jobsArr[i].pricein;
        sumin = sumin +  parseFloat($scope.jobsArr[i].summin);
        sumout = sumout +  parseFloat($scope.jobsArr[i].summout);

        var sm = parseFloat($scope.kurs) * parseFloat($scope.jobsArr[i].pricein);
        $scope.jobsArr[i].priceout = sm +  sm * ($scope.procent / 100.0);
        $scope.jobsArr[i].summout = $scope.jobsArr[i].kolvo * $scope.jobsArr[i].priceout;

      }
      $scope.totalsummin = sumin;
      $scope.totalsummout = sumout;

      $timeout($scope.recalculateSumm, 500);

    }
    $scope.recalculateSumm();


    $scope.saveSumma = function(summa) {
      $scope.order.amount = summa;
      $scope.invoice.amount = summa;

    }
    $scope.checkJob = function() {
      console.log($scope.jobs);
      console.log($scope.jobsArray);
      console.log($scope.jobsArr);
      $scope.loadJobs();

    }


    $scope.delJob = function(id) {
      if (confirm("Are you want to delete?")) {
        $scope.progressbar = 50;
//        ApiJob.save({ jobId: id, 'jobdelete': 1}, function(data) {
//
//          //TODO: refresh list of job
//          $scope.loadJobs();
//          //$scope.jobs.push(newJob);
//          $timeout(function() {
//            $scope.progressbar = 90;
//          }, 300);
//          $timeout(function() {
//            $scope.progressbar = 100;
//          }, 800);
//        });
      }
    };

    $scope.update = function () {
      console.log($scope.order);
      $scope.progressbar = 10;
//      ApiOrder.save({'orderId': $scope.orderId, order: $scope.order}, function(data) {
//        $scope.progressbar = 50;
//        //console.log(data);
//        $scope.order = data.result;
//        $scope.orderId = $scope.order.id;
//        $timeout(function() {
//          $scope.progressbar = 90;
//        }, 300);
//        $timeout(function() {
//          $scope.progressbar = 100;
//        }, 800);
//      });
    }



    $scope.printComp = function() {
      console.log($scope.jobsArr);

    }
    $scope.printGarant = function() {
      console.log($scope.jobsArr);

    }

    // -------add device------------------------
    $scope.newDevice = function(jobId) {
      $scope.curJob = jobId;
      $scope.showModal('addDevice');
    }

    $scope.addDevice = function() {

      if ($scope.adevice.name == undefined || $scope.adevice.name == "") {
        alert('Вы не ввели имя');
        return false;
      }
      console.log($scope.curJob);


      ApiAddDevice.save({device: $scope.adevice}, function(data) {
        if (data.result.id > 0) {
          $scope.adevice = data.result;
          // Reload devices
          ApiDevice.get({type:1}, function(data) {
            $scope.devices = data.result;

            // Set new client in select
            $scope.jobsArr[$scope.curJob].device_id = $scope.adevice.id
            $rootScope.hideModal('addDevice');
          });
        }
        if (data.error != "" && data.error != null) {
          alert(data.error);
        }
      });
    }

  });