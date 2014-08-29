'use strict';

/**
 * @ngdoc filter
 * @name eztcApp.filter:capitalize
 * @function
 * @description
 * # capitalize
 * Capitalizes the first letter of the input string
 */
angular.module('eztcApp')
  .filter('capitalize', function () {
    return function(input, scope) {
      if (!input){
        return;
      }
      input = input.toLowerCase();
      return input.substring(0,1).toUpperCase()+input.substring(1);
    }
  });
