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
    'ngSanitize',
    'ngResource',
    'ui.router',
    'ui.select'
  ])
  .config(function($stateProvider, $urlRouterProvider, uiSelectConfig) {

    // Select2
    uiSelectConfig.theme = 'bootstrap';

    // Requet timeout
    var timeoutTime = 5000;

    // For any unmatched url, send to home
    $urlRouterProvider.otherwise('/view/home');

    // No pre-slash only trailing plz
    var baseApiUrl = 'api/';

    $stateProvider
      .state('view', {
        templateUrl: 'views/view/jumbotron.html',
        resolve: {
          employeeList: ['$http',
            function($http) {
              return $http({
                method: 'GET',
                url: baseApiUrl + 'list/employees',
                timeout: timeoutTime
              });
            }
          ],
          projectList: ['$http',
            function($http) {
              return $http({
                method: 'GET',
                url: baseApiUrl + 'list/projects',
                timeout: timeoutTime
              });
            }
          ]
        },
        controller: 'JumbotronCtrl'
      })
      .state('view.home', {
        url: '/view/home',
        templateUrl: 'views/view/home.html',
        resolve: {
          recentProjects: ['$http',
            function($http) {
              return $http({
                method: 'GET',
                url: baseApiUrl + 'list/recentprojects',
                timeout: timeoutTime
              });
            }
          ]
        },
        controller: ['$scope', 'recentProjects',
          function($scope, recentProjects) {
            $scope.recentProjects = recentProjects.data;
          }
        ]
      })
      .state('view.project', {
        url: '/view/project/:projectCode/:groupBy',
        templateUrl: 'views/view/project.html',
        resolve: {
          projectData: ['$http', '$stateParams',
            function($http, $stateParams) {
              return $http({
                method: 'GET',
                // Ghetto Bandaid warning. Flight can't accept routes with periods (.) in them so we swap them to dashes (-) here and decode in php (prob a limitation of the dev environment)
                url: baseApiUrl + 'data/project/' + $stateParams.projectCode.replace(/\./g, '-')
              });
            }
          ]
        },
        controller: 'ProjectCtrl'
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