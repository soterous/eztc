'use strict';

describe('Directive: routerStateEq', function () {

  // load the directive's module
  beforeEach(module('eztcApp'));

  var element,
    scope;

  beforeEach(inject(function ($rootScope) {
    scope = $rootScope.$new();
  }));

  it('should make hidden element visible', inject(function ($compile) {
    element = angular.element('<router-state-eq></router-state-eq>');
    element = $compile(element)(scope);
    expect(element.text()).toBe('this is the routerStateEq directive');
  }));
});
