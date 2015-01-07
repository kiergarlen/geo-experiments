'use strict';

/* App Module */

var siclabApp = angular.module('siclabApp', [
  'ngRoute',
  //'ui.bootstrap',
  'siclabDirectives',
  'siclabControllers',
  'siclabServices'
]);

siclabApp.config(['$routeProvider',
  function($routeProvider) {
    $routeProvider.
      when('/main', {
          templateUrl: 'partials/sistema/main.html',
          controller: 'TasksController'//,
          //controllerAs: 'tasks'
      }).
      when('/analisis/captura', {
          templateUrl: 'partials/analisis/captura.html',
          controller: 'AnalysisController'//,
          //controllerAs: 'analysis'
      }).
      when('/analisis/consulta', {
          templateUrl: 'partials/analisis/consulta.html',
          controller: 'AnalysisListController'//,
          //controllerAs: 'analysisList'
      }).
      when('/catalogo/metodos', {
          templateUrl: 'partials/catalogo/metodos.html',
          controller: 'MethodsListController'//,
          //controllerAs: 'methodsList'
      }).
      when('/catalogo/areas', {
          templateUrl: 'partials/catalogo/areas.html',
          controller: 'DepartmentsListController'//,
          //controllerAs: 'departmentsList'
      }).
      when('/catalogo/clientes', {
          templateUrl: 'partials/catalogo/clientes.html',
          controller: 'ClientsListController'//,
          //controllerAs: 'clientsList'
      }).
      when('/catalogo/clientes/:clientId', {
        templateUrl: 'partials/catalogo/cliente.html',
        controller: 'ClientDetailController',
        controllerAs: 'clientDetail'
      }).
      when('/catalogo/empleados', {
          templateUrl: 'partials/catalogo/empleados.html',
          controller: 'EmployeesListController'//,
          //controllerAs: 'employeesList'
      }).
      when('/catalogo/normas', {
          templateUrl: 'partials/catalogo/normas.html',
          controller: 'NormsListController'//,
          //controllerAs: 'normsList'
      }).
      when('/catalogo/precios', {
          templateUrl: 'partials/catalogo/precios.html',
          controller: 'PricesListController'//,
          //controllerAs: 'pricesList'
      }).
      when('/catalogo/puntos', {
          templateUrl: 'partials/catalogo/puntos.html',
          controller: 'PointsListController'//,
          //controllerAs: 'pointsList'
      }).
      when('/catalogo/referencia', {
          templateUrl: 'partials/catalogo/referencia.html',
          controller: 'ReferencesListController'//,
          //controllerAs: 'referencesList'
      }).
      when('/inventario/equipos', {
          templateUrl: 'partials/inventario/equipos.html',
          controller: 'InstrumentsListController'//,
          //controllerAs: 'instrumentsList'
      }).
      when('/inventario/muestras', {
          templateUrl: 'partials/inventario/muestras.html',
          controller: 'SamplesListController'//,
          //controllerAs: 'samplesList'
      }).
      when('/inventario/reactivos', {
          templateUrl: 'partials/inventario/reactivos.html',
          controller: 'ReactivesListController'//,
          //controllerAs: 'reactivesList'
      }).
      when('/inventario/recipientes', {
          templateUrl: 'partials/inventario/recipientes.html',
          controller: 'RecipientsListController'//,
          //controllerAs: 'recipientsList'
      }).
      when('/muestreo/orden', {
          templateUrl: 'partials/muestreo/orden.html',
          controller: 'SamplingOrderController'//,
          //controllerAs: 'samplingOrder'
      }).
      when('/muestreo/plan', {
          templateUrl: 'partials/muestreo/plan.html',
          controller: 'SamplingPlanController'//,
          //controllerAs: 'samplingPlan'
      }).
      when('/muestreo/plan/hieleras', {
          templateUrl: 'partials/muestreo/plan/hieleras.html',
          controller: 'SamplingPlanPreservatorController'//,
          //controllerAs: 'samplingPlanPreservator'
      }).
      when('/muestreo/plan/materiales', {
          templateUrl: 'partials/muestreo/plan/materiales.html',
          controller: 'SamplingPlanMaterialistController'//,
          //controllerAs: 'samplingPlanMaterialist'
      }).
      when('/muestreo/plan/reactivos', {
          templateUrl: 'partials/muestreo/plan/reactivos.html',
          controller: 'SamplingPlanChemistController'//,
          //controllerAs: 'samplingPlanChemist'
      }).
      when('/muestreo/plan/recipientes', {
          templateUrl: 'partials/muestreo/plan/recipientes.html',
          controller: 'SamplingPlanPreparatorController'//,
          //controllerAs: 'samplingPlanPreparator'
      }).
      when('/muestreo/plan/verificacion', {
          templateUrl: 'partials/muestreo/plan/verificacion.html',
          controller: 'SamplingPlanCalibratorController'//,
          //controllerAs: 'samplingPlanCalibrator'
      }).
      when('/muestreo/solicitud', {
          templateUrl: 'partials/muestreo/solicitud.html',
          controller: 'QuoteController'//,
          //controllerAs: 'quote'
      }).
      when('/recepcion/custodia', {
          templateUrl: 'partials/recepcion/custodia.html',
          controller: 'CustodyController'//,
          //controllerAs: 'custody'
      }).
      when('/recepcion/campo', {
          templateUrl: 'partials/recepcion/campo.html',
          controller: 'FieldDataSheetController'//,
          //controllerAs: 'fieldDataSheet'
      }).
      when('/recepcion/muestra', {
          templateUrl: 'partials/recepcion/muestra.html',
          controller: 'SampleReceptionController'//,
          //controllerAs: 'sampleReception'
      }).
      when('/recepcion/trabajo', {
          templateUrl: 'partials/recepcion/trabajo.html',
          controller: 'TaskAssignmentController'//,
          //controllerAs: 'taskAssignment'
      }).
      when('/reporte/consulta', {
          templateUrl: 'partials/reporte/consulta.html',
          controller: 'ReportsListController'//,
          //controllerAs: 'reportsList'
      }).
      when('/reporte/validar', {
          templateUrl: 'partials/reporte/validar.html',
          controller: 'ReportApprovalController'//,
          //controllerAs: 'reportApproval'
      }).
      when('/sistema/login', {
          templateUrl: 'partials/sistema/login.html',
          controller: 'LoginController'//,
          //controllerAs: 'login'
      }).
      when('/sistema/logout', {
          templateUrl: 'partials/sistema/logout.html',
          controller: 'LogoutController'//,
          //controllerAs: 'logout'
      }).
      when('/sistema/perfil', {
          templateUrl: 'par tials/sistema/perfil.html',
          controller: 'ProfileController'//,
          //controllerAs: 'profile'
      }).
      when('/sistema/usuarios', {
          templateUrl: 'partials/sistema/usuarios.html',
          controller: 'UsersListController'//,
          //controllerAs: 'usersList'
      })
      .otherwise({
       redirectTo: '/sistema/login'
      })
    ;
  }
]);