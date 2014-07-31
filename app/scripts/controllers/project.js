'use strict';

/**
 * @ngdoc function
 * @name eztcApp.controller:ProjectCtrl
 * @description
 * # ProjectCtrl
 * Controller of the eztcApp
 */
angular.module('eztcApp')
  .controller('ProjectCtrl', function($scope, $filter, $stateParams, projectData) {

    // Data comes in like this with each entry in the array being similar
    //"projectCode":"1233.0114.OPT2.28FJ.00KK.ABCD","employeeName":"SMITH, JOHN","year":"2014","month":"07","day":"01","hours":"8"

    // Pull required data from query
    var projects = projectData.data;

    $scope.projectTitle = projects[0].projectCode;
    $scope.groupBy = $stateParams.groupBy || 'month';
    if ($scope.groupBy !== 'month' && $scope.groupBy !== 'user') {
      $scope.groupBy = 'month';
    }

    // What do we group by?
    var groupBy = ($scope.groupBy === 'month') ? 'yearMonth' : 'employeeName'; // Either month or user
    var subGroup = ($scope.groupBy !== 'month') ? 'yearMonth' : 'employeeName';

    var totalHours = 0;
    var groupedData = {};

    // Do the grouping by looping through each project line
    // Grouping should end up looking something liek this
    /*
      2014-07: {
        JohnSmith: [row, row, row],
        BobJoe: [row, row, row]
      }
      or
      JohnSmith: {
        2014
      }
    */
    angular.forEach(projects, function(row) {

      totalHours += +row.hours;

      // init this grouping (either month or employee)
      if (!groupedData.hasOwnProperty(row[groupBy])) {
        groupedData[row[groupBy]] = {};
      }

      var parentGroup = groupedData[row[groupBy]];

      // Check if the child grouping exists
      if (!parentGroup.hasOwnProperty(row[subGroup])) {
        parentGroup[row[subGroup]] = [];
      }

      parentGroup[row[subGroup]].push(row);

    });

    // Done grouping
    $scope.groupedData = groupedData;
    $scope.totalHours = totalHours;



    // UI Util Functions
    $scope.getGroupingTitle = function(grouping) {
      var firstRow = grouping[Object.keys(grouping)[0]][0] || null;

      if (firstRow !== null) {
        switch ($scope.groupBy) {
          case 'month':
            return $filter('month')(firstRow.month) + ' ' + firstRow.year;
          case 'user':
            return firstRow.employeeName;
        }
      }
    };

    $scope.formatSubTitle = function(title) {

      switch ($scope.groupBy) {
        case 'user':
          var split = title.split('-');
          return $filter('month')(split[1]) + ' ' + split[0];
        case 'month':
          return title;
      }

    };

    $scope.getGroupingTotalHours = function(grouping) {
      var hours = 0;

      // Each Group
      angular.forEach(grouping, function(val) {
        // Each sub-group
        angular.forEach(val, function(v) {
          hours += +v.hours;
        });
      });

      return hours;
    };

    function getDaysInMonth(month, year) {
      return new Date(year, month, 0).getDate();
    }

    // This is so we can use an ng-repeat
    $scope.daysInMonthRepeater = function(month, year) {
      var daysInMonth = getDaysInMonth(month, year);

      return new Array(daysInMonth);
    };

    // given the day, return the number of hours, or 0 if none
    $scope.getHours = function(day, rows) {

      for (var i = 0; i < rows.length; i++) {
        var row = rows[i];

        if (+row.day === +day) {
          return +row.hours;
        }
      }

      return 0;
    };






  });