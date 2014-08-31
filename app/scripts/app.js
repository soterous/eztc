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
      .state('view.details', {
        url: '/view/details/:view/:id/:groupBy',
        templateUrl: 'views/view/details.html',
        controller: 'DetailsCtrl',
        resolve: {
          viewData: ['$http', '$stateParams',
            function($http, $stateParams) {
              return $http({
                method: 'GET',
                // Ghetto Bandaid warning. Flight can't accept routes with periods (.) in them so we swap them to dashes (-) here and decode in php (prob a limitation of the dev environment)
                url: baseApiUrl + 'data/' + $stateParams.view + '/' + $stateParams.id.replace(/\./g, '-')
              });
            }
          ]
        }
      });

  });