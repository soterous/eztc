'use strict';

/**
 * @ngdoc filter
 * @name eztcApp.filter:month
 * @function
 * @description
 * # month
 * Converts a number into the related month. So 07 == July, but so does 7 == July
 */
angular.module('eztcApp')
  .filter('month', function () {
    return function (input) {

      var months = [
        'January',
        'February',
        'March',
        'April',
        'May',
        'June',
        'July',
        'August',
        'September',
        'October',
        'November',
        'December'
      ];

      var number = +input - 1;

      return months[number] || '??';
    };
  });
