'use strict';

/* App Module */

var siclabApp = angular.module('siclabApp', [
  'ngRoute',
  //'ui.bootstrap',
  'siclabAnimations',
  'siclabDirectives',
  'siclabControllers',
  'siclabFilters',
  'siclabServices'
]);

siclabApp.config(['$routeProvider',
  function($routeProvider) {
    $routeProvider.
      when('/main', {
          templateUrl: 'partials/main.html',
          controller: 'TasksCtrl'
      }).
      when('/analisis/captura', {
          templateUrl: 'partials/analisis/captura.html',
          controller: 'AnalysisCtrl'
      }).
      when('/analisis/consulta', {
          templateUrl: 'partials/analisis/consulta.html',
          controller: 'AnalysisListCtrl'
      }).
      when('/catalogo/metodos', {
          templateUrl: 'partials/catalogo/metodos.html',
          controller: 'MethodsListCtrl'
      }).
      when('/catalogo/areas', {
          templateUrl: 'partials/catalogo/areas.html',
          controller: 'DepartmentsListCtrl'
      }).
      when('/catalogo/clientes', {
          templateUrl: 'partials/catalogo/clientes.html',
          controller: 'ClientsListCtrl'
      }).
      when('/catalogo/clientes/:clientId', {
        templateUrl: 'partials/catalogo/cliente.html',
        controller: 'ClientDetailCtrl'
      }).
      when('/catalogo/empleados', {
          templateUrl: 'partials/catalogo/empleados.html',
          controller: 'EmployeesListCtrl'
      }).
      when('/catalogo/normas', {
          templateUrl: 'partials/catalogo/normas.html',
          controller: 'NormsListCtrl'
      }).
      when('/catalogo/precios', {
          templateUrl: 'partials/catalogo/precios.html',
          controller: 'PricesListCtrl'
      }).
      when('/catalogo/puntos', {
          templateUrl: 'partials/catalogo/puntos.html',
          controller: 'PointsListCtrl'
      }).
      when('/catalogo/referencia', {
          templateUrl: 'partials/catalogo/referencia.html',
          controller: 'ReferencesListCtrl'
      }).
      when('/inventario/equipos', {
          templateUrl: 'partials/inventario/equipos.html',
          controller: 'InstrumentsListCtrl'
      }).
      when('/inventario/muestras', {
          templateUrl: 'partials/inventario/muestras.html',
          controller: 'SamplesListCtrl'
      }).
      when('/inventario/reactivos', {
          templateUrl: 'partials/inventario/reactivos.html',
          controller: 'ReactivesListCtrl'
      }).
      when('/inventario/recipientes', {
          templateUrl: 'partials/inventario/recipientes.html',
          controller: 'RecipientsListCtrl'
      }).
      when('/muestreo/cotizacion', {
          templateUrl: 'partials/muestreo/cotizacion.html',
          controller: 'QuotationCtrl'
      }).
      when('/muestreo/orden', {
          templateUrl: 'partials/muestreo/orden.html',
          controller: 'SamplingOrderCtrl'
      }).
      when('/muestreo/plan', {
          templateUrl: 'partials/muestreo/plan.html',
          controller: 'SamplingPlanCtrl'
      }).
      when('/muestreo/solicitud', {
          templateUrl: 'partials/muestreo/solicitud.html',
          controller: 'QuoteCtrl'
      }).
      when('/recepcion/custodia', {
          templateUrl: 'partials/recepcion/custodia.html',
          controller: 'CustodyCtrl'
      }).
      when('/recepcion/campo', {
          templateUrl: 'partials/recepcion/campo.html',
          controller: 'FieldDataSheetCtrl'
      }).
      when('/recepcion/muestra', {
          templateUrl: 'partials/recepcion/muestra.html',
          controller: 'SampleReceptionCtrl'
      }).
      when('/recepcion/trabajo', {
          templateUrl: 'partials/recepcion/trabajo.html',
          controller: 'TaskAssignmentCtrl'
      }).
      when('/reporte/consulta', {
          templateUrl: 'partials/reporte/consulta.html',
          controller: 'ReportsListCtrl'
      }).
      when('/reporte/validar', {
          templateUrl: 'partials/reporte/validar.html',
          controller: 'ReportApprovalCtrl'
      }).
      when('/sistema/login', {
          templateUrl: 'partials/sistema/login.html',
          controller: 'LoginCtrl'
      }).
      when('/sistema/logout', {
          templateUrl: 'partials/sistema/logout.html',
          controller: 'LogoutCtrl'
      }).
      when('/sistema/perfil', {
          templateUrl: 'partials/sistema/perfil.html',
          controller: 'ProfileCtrl'
      }).
      when('/sistema/usuarios', {
          templateUrl: 'partials/sistema/usuarios.html',
          controller: 'UsersListCtrl'
      })
      .otherwise({
       redirectTo: '/sistema/login'
      })
    ;
  }]);
