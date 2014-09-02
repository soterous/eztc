'use strict';

/**
 * router-state-eq Directive
 *
 * What does this directive do?
 * Analyses the current $state and allows us to apply a css class depending on true/false
 *
 * How to use it?
 * Apply the router-state-eq="" attribute to the DOM element you want to style
 * The directive takes an object as a parameter with these properties:
 *
 * - state : (string) the state you want to match against
 *
 * - params: (object)[optional] the stateParams you want to match against,
 *                              properties will depend on the specific state.
 *
 * - css: (string|object) A string representation of a class will be toggled on and off.
 *                        Pass an object to have different classes depending if the state is matched.
 *                        Use this format: {true: 'active', false: 'hidden'}
 *
 * - options: (object) Options currently only supports one property, 'strict'.
 *                     'strict' (default: false) if true, comparison will be done via $state.is()
 *                              otherwise if false, comparison will be done via $state.includes()
 *                              View the ui-router docs for $state for more info
 *
 * EG:
 * <li router-state-eq="{state:'view.details', params:{view:'project'}, css:{true: 'active', false: 'hidden'}}"><a>project</a></li>
 */
angular.module('eztcApp')
  .directive('routerStateEq', ['$state', '$rootScope', function ($state, $rootScope) {
    return {
      restrict: 'A',
      scope: {
        data: '=routerStateEq'
      },
      link: function postLink(scope, element) {

        // Default options
        var options = {
          strict: false
        };

        var data = scope.data;

        // Merge options
        if(angular.isDefined(data.options)){
          angular.extend(options, data.options);
        }

        // fix data
        if(!angular.isDefined(data.params)){
          data.params = {};
        }

        // when a state has changed, do what this directive does
        $rootScope.$on('$stateChangeSuccess', function() {

          var validState = false;

          if(options.strict === true){
            if($state.is(data.state, data.params)){
              validState = true;
            }
          } else {
            if($state.includes(data.state, data.params)){
              validState = true;
            }
          }

          // figure out our CSS
          var css = data.css;
          var hideCss = data.css;
          if(typeof data.css === 'object'){
            css = data.css[validState];
            hideCss = data.css[!validState];

            // if CSS is a lookup object, we always have a 'valid' state because we have to flop
            validState = true;
          }

          // remove the old css class
          element.removeClass(hideCss);

          if(validState){
            element.addClass(css);
          }

        });

      }
    };
  }]);
