'use strict';

/**
 * @ngdoc overview
 * @name eztcApp
 * @description
 * # eztcApp
 *
 * Main module of the application.
 */
angular
  .module('eztcApp', [
    'ngResource',
    'ui.router'
  ])
  .config(function($stateProvider, $urlRouterProvider) {
    var timeoutTime = 5000;

    // For any unmatched url, send to home
    $urlRouterProvider.otherwise('/view/home');

    var baseApiUrl = '/api';

    $stateProvider
      .state('view', {
        templateUrl: '/views/view/jumbotron.html',
        resolve: {
          employeeList: ['$http',
            function($http) {
              return $http({
                method: 'GET',
                url: baseApiUrl + '/list/employees',
                timeout: timeoutTime
              });
            }
          ],
          projectList: ['$http',
            function($http) {
              return $http({
                method: 'GET',
                url: baseApiUrl + '/list/projects',
                timeout: timeoutTime
              });
            }
          ]
        }
      })
      .state('view.home', {
        url: '/view/home',
        templateUrl: '/views/view/home.html',
        resolve: {
          recentProjects: ['$http',
            function($http) {
              return $http({
                method: 'GET',
                url: baseApiUrl + '/list/recentprojects',
                timeout: timeoutTime
              });
            }
          ]
        }
      });


    /*
          .state('stones', {
            templateUrl: '/views/stones.html',
            resolve: {
              stones: ['$http',
                function($http) {
                  return $http({
                    method: 'GET',
                    url: baseApiUrl + '/stones.php',
                    timeout: timeoutTime
                  });
                }
              ]
            },
            controller: 'StonesCtrl'
          })
          .state('stones.items', {
            url: '/stones/{page:[1-9]+[0-9]*}',
            templateUrl: '/views/stones.items.html',
            controller: 'StonesItemsCtrl'
          })
          .state('stones.itemdetails', {
            url: '/details/:stoneId',
            templateUrl: '/views/stones.itemdetails.html',
            controller: 'ItemDetailsCtrl',
            resolve: {
              stoneDetails: ['$http', '$stateParams',
                function($http, $stateParams) {
                  return $http({
                    method: 'GET',
                    url: baseApiUrl + '/details.php',
                    params: {
                      code: $stateParams.stoneId
                    },
                    timeout: timeoutTime
                  });
                }
              ]
            }
          });
          */

  });