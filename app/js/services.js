'use strict';

/* Services */

var siclabServices = angular.module('siclabServices', ['ngResource']);

siclabServices.factory('Menu', ['$resource', function($resource){
	return $resource('models/menu.json', {}, {
		query: {method:'GET', params:{}, isArray:true}
	});
}]);

siclabServices.factory('ClientDetail', ['$resource', function($resource){
	return $resource('models/clients/:clientId.json', {}, {
		query: {method:'GET', params:{clientId:'id_cliente'}, isArray:true}
	});
}]);

siclabServices.factory('Client', ['$resource', function($resource){
	return $resource('models/clients.json', {}, {
		query: {method:'GET', params:{}, isArray:true}
	});
}]);

siclabServices.factory('Department', ['$resource', function($resource){
	return $resource('models/areas.json', {}, {
		query: {method:'GET', params:{}, isArray:true}
	});
}]);

siclabServices.factory('Employee', ['$resource', function($resource){
	return $resource('models/empleados.json', {}, {
		query: {method:'GET', params:{}, isArray:true}
	});
}]);

siclabServices.factory('User', ['$resource', function($resource){
	return $resource('models/usuarios.json', {}, {
		query: {method:'GET', params:{}, isArray:true}
	});
}]);

siclabServices.factory('Department', ['$resource', function($resource){
	return $resource('models/areas.json', {}, {
		query: {method:'GET', params:{}, isArray:true}
	});
}]);

siclabServices.factory('Parameter', ['$resource', function($resource){
	return $resource('models/parametros.json', {}, {
		query: {method:'GET', params:{}, isArray:true}
	});
}]);

siclabServices.factory('Norm', ['$resource', function($resource){
	return $resource('models/normas.json', {}, {
		query: {method:'GET', params:{}, isArray:true}
	});
}]);

siclabServices.factory('SamplingType', ['$resource', function($resource){
	return $resource('models/tipos_muestreo.json', {}, {
		query: {method:'GET', params:{}, isArray:true}
	});
}]);
/*
siclabServices.factory('LabService', ['$resource', function($resource){
	return $resource('models/labServices/:labServiceId.json', {}, {
		query: {method:'GET', params:{labServiceId:'labServices'}, isArray:true}
	});
}]);
*/