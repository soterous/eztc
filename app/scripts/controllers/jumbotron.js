'use strict';

/**
 * @ngdoc function
 * @name eztcApp.controller:JumbotronctrlCtrl
 * @description
 * # JumbotronctrlCtrl
 * Controller of the eztcApp
 */
angular.module('eztcApp')
  .controller('JumbotronCtrl', function($scope, employeeList, projectList) {
    $scope.employeeList = employeeList.data;
    $scope.projectList = projectList.data;

    $scope.person = {};
    $scope.project = {};

    $scope.$watch('person.selected', function() {
      if(typeof $scope.person.selected === 'undefined') {
        return;
      }

      // Reset the other dropdown
      $scope.project.selected = undefined;

      console.log('person selected ' + typeof $scope.person.selected);
    });

    $scope.$watch('project.selected', function() {
      if(typeof $scope.project.selected === 'undefined') {
        return;
      }

      // Reset the other dropdown
      $scope.person.selected = undefined;
      console.log('project selected ' + typeof $scope.person.selected);
    });

  });