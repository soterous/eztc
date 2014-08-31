'use strict';
angular.module('eztcApp')
  .controller('DetailsCtrl', ['$scope', 'viewData', '$stateParams', '$filter', '$state',
    function($scope, viewData, $stateParams, $filter, $state) {


      // Data comes in like this with each entry in the array being similar
      //"projectCode":"1233.0114.OPT2.28FJ.00KK.ABCD","employeeName":"SMITH, JOHN","year":"2014","month":"07","day":"01","hours":"8"

      // Pull required data from query
      if(viewData.data === ''){
        return;
      }
      var projects = viewData.data;

      $scope.view = $stateParams.view;
      $scope.id = $stateParams.id;

      //Init all the sorting and grouping variables
      var viewType = $stateParams.view.toLowerCase();

      if(viewType === 'employee') {
        $scope.pageTitle = projects[0].employeeName;

        // Ensure groupBy is valid (employee does project & month)
        $scope.groupBy = $stateParams.groupBy;
        if ($scope.groupBy !== 'project' && $scope.groupBy !== 'month') {
          $stateParams.groupBy = $scope.groupBy = 'project';
          $state.transitionTo('view.details', $stateParams);
        }

      } else if (viewType === 'project') {

        $scope.pageTitle = projects[0].projectCode;

        // Ensure groupBy is valid (project does employee & month)
        $scope.groupBy = $stateParams.groupBy;
        if ($scope.groupBy !== 'month' && $scope.groupBy !== 'employee') {
          $stateParams.groupBy = $scope.groupBy = 'month';
          $state.transitionTo('view.details', $stateParams);
        }

      } else {
        // error
        return;
      }

      // Assign local equivalents to be used in sorting. These values will be the same as the objects' properties
      var groupBy = null, subGroup = null;
      if(viewType === 'employee') {
        // What do we group by?
        groupBy = ($scope.groupBy === 'month') ? 'yearMonth' : 'projectCode'; // Either month or project
        subGroup = ($scope.groupBy !== 'month') ? 'yearMonth' : 'projectCode';
      } else {
        groupBy = ($scope.groupBy === 'month') ? 'yearMonth' : 'employeeName'; // Either month or employee
        subGroup = ($scope.groupBy !== 'month') ? 'yearMonth' : 'employeeName';
      }

      var totalHours = 0;
      var groupedData = {};


      // Do the grouping by looping through each project line
      // Grouping should end up looking something like this
      /*
      2014-07: {
        JohnSmith: [{"projectCode":"123","employeeName":"John Smith","year":"2014","month":"07","day":"02","yearMonth":"2014-07","hours":"8"}, ...],
        BobJoe: [{"projectCode":"123","employeeName":"John Smith","year":"2014","month":"07","day":"02","yearMonth":"2014-07","hours":"8"}, ...],
        ...
      }
      or
      JohnSmith: {
        "2014-07": [{"projectCode":"123","employeeName":"John Smith","year":"2014","month":"07","day":"02","yearMonth":"2014-07","hours":"8"}, ...],
        ...
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
            case 'employee':
              return firstRow.employeeName;
            case 'project':
              return firstRow.projectCode;
          }
        }
      };

      // Sub title will get handed to us in the format of (2014-07)
      $scope.formatSubTitle = function(title) {
        switch ($scope.groupBy) {
          case 'employee':
          case 'project':
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
        if (angular.isDefined(month) && angular.isDefined(year)) {
          var daysInMonth = getDaysInMonth(month, year);

          return new Array(daysInMonth);
        }

        return new Array(0);
      };

      // given the day, return the number of hours, or 0 if none
      $scope.getHours = function(day, rows) {

        if (!angular.isDefined(day) || !angular.isDefined(rows)) {
          return;
        }

        for (var i = 0; i < rows.length; i++) {
          var row = rows[i];

          if (+row.day === +day) {
            return +row.hours;
          }
        }

        return 0;
      };

      // Gets the total hours for the passed sub. This is what displays the month totals in each month.
      $scope.getSubTotalHours = function(sub) {
        var total = 0;

        angular.forEach(sub, function(key) {
          total += +key.hours;
        });

        return total;
      };
    }
  ]);