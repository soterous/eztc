'use strict';

describe('Controller: JumbotronctrlCtrl', function () {

  // load the controller's module
  beforeEach(module('eztcApp'));

  var JumbotronctrlCtrl,
    scope;

  // Initialize the controller and a mock scope
  beforeEach(inject(function ($controller, $rootScope) {
    scope = $rootScope.$new();
    JumbotronctrlCtrl = $controller('JumbotronctrlCtrl', {
      $scope: scope
    });
  }));

  it('should attach a list of awesomeThings to the scope', function () {
    expect(scope.awesomeThings.length).toBe(3);
  });
});
