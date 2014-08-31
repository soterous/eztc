'use strict';

/**
 * @ngdoc function
 * @name eztcApp.controller:JumbotronctrlCtrl
 * @description
 * # JumbotronctrlCtrl
 * Controls the jumbotron dropdowns
 */
angular.module('eztcApp')
  .controller('JumbotronCtrl', function($scope, employeeList, projectList, $state) {
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

      // Go
      $state.go('view.details', {view: 'employee', id: $scope.person.selected.name});
    });

    $scope.$watch('project.selected', function() {
      if(typeof $scope.project.selected === 'undefined') {
        return;
      }

      // Reset the other dropdown
      $scope.person.selected = undefined;

      // Go
      $state.go('view.details', {view: 'project', id: $scope.project.selected.code});
    });

  });