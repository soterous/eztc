'use strict';

/**
 * @ngdoc function
 * @name eztcApp.controller:MainCtrl
 * @description
 * # MainCtrl
 * Controller of the eztcApp
 */
angular.module('eztcApp')
  .controller('MainCtrl', function ($scope) {
    $scope.awesomeThings = [
      'HTML5 Boilerplate',
      'AngularJS',
      'Karma'
    ];
  });
