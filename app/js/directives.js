'use strict';

/* Directives */

var siclabDirectives = angular.module('siclabDirectives', []);

siclabDirectives.directive('ngMainNav', function() {
  return {
    restrict: 'A',
    require: '^ngModel',
    templateUrl: 'partials/navbar.html'
  };
});
