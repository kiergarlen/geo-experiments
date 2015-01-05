'use strict';

/* Directives */

var siclabDirectives = angular.module('siclabDirectives', []);

function mainNav() {
  return {
    restrict: 'EA',
    require: '^ngModel',
    templateUrl: 'partials/sistema/nav.html'
  };
}

siclabDirectives.directive('mainNav', mainNav);