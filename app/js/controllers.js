'use strict';

/* Controllers */

var siclabControllers = angular.module('siclabControllers', []);

siclabControllers.controller('LoginController',
  ['$scope', 'Login', function($scope, Login) {
  //$scope.user = Login.query();
  $scope.login = {};
  $scope.login.message = '';
  $scope.login.user = {
    username: '',
    password: ''
  };
  $scope.login.login = function() {
    $scope.login.message = 'Working...';
    if ($scope.loginForm.$valid)
    {
      if ($scope.login.user.username == 'rgarcia' &&
        $scope.login.user.password == '123'
      )
      {
        $scope.login.message = 'Enviando...';

      }
      else
      {
        $scope.login.message = 'Usuario o contraseña incorrectos';
      }
    }
    else
    {
      $scope.login.message = 'Debe ingresar usuario y/o contraseña';
    }
  };
}]);

siclabControllers.controller('NavController',
  ['$scope', 'Menu', function($scope, Menu) {
  $scope.menu = Menu.query();
}]);

siclabControllers.controller('TasksController',
  ['$scope', function() {
  $scope.tasks.title = 'Bienvenido';
  $scope.tasks.subtitle = 'Sistema de Control y Administración de Laboratorio';
}]);

siclabControllers.controller('ClientsListController',
  ['$scope', 'Client', function($scope, Client) {
  $scope.clients = Client.query();
}]);

siclabControllers.controller('ClientDetailController',
  ['$scope', 'ClientDetail', function($scope, ClientDetail) {
  $scope.clientDetail = ClientDetail.query();
}]);

siclabControllers.controller('DepartmentsListController',
  ['$scope', 'Department', function($scope, Department) {
  $scope.departments = Department.query();
}]);

siclabControllers.controller('EmployeesListController',
  ['$scope', 'Employee', function($scope, Employee) {
  $scope.employees = Employee.query();
}]);

siclabControllers.controller('UsersListController',
  ['$scope', 'User', function($scope, User) {
  $scope.users = User.query();
}]);

siclabControllers.controller('NormsListController',
  ['$scope', 'Norm', function($scope, Norm) {
  $scope.norms = Norm.query();
}]);

siclabControllers.controller('QuoteController',
 ['$scope', 'Client', 'Parameter', 'Norm', 'SamplingType', 'Quote',
  function($scope, Client, Parameter, Norm, SamplingType, Quote) {
    $scope.quote = {};
    $scope.quote.clients = Client.query();
    $scope.quote.parameters = Parameter.query();
    $scope.quote.norms = Norm.query();
    $scope.quote.samplingTypes = SamplingType.query();
    $scope.quote.quote = Quote.query();
    $scope.quote.clientDetailsIsShown = false;
    $scope.quote.totalCost = 0;

    $scope.quote.toggleClientInfo = function($event) {
      var id = $scope.quote.quote.id_cliente;
      $event.stopPropagation();
      $scope.quote.clientDetailsIsShown = (
        $scope.quote.quote.id_cliente > 0 &&
        $scope.quote.selectClient(id).cliente &&
        !$scope.quote.clientDetailsIsShown
      );
    };

    $scope.quote.selectClient = function(idClient) {
      var i = 0,l = $scope.quote.clients.length;
      $scope.quote.quote.cliente = {};
      for (i; i < l; i += 1) {
        if ($scope.quote.clients[i].id_cliente == idClient) {
          $scope.quote.quote.cliente = $scope.quote.clients[i];
          break;
        }
      }
      return $scope.quote.quote.cliente;
    };

    $scope.quote.totalParameter = function(){
      var t = 0;
      angular.forEach($scope.quote.parameters, function(s){
        if(s.selected) {
          t += parseFloat(s.precio);
        }
      });
      t = t * $scope.quote.quote.cliente.tasa;
      $scope.quote.totalCost = (Math.round(t * 100) / 100);
      return $scope.quote.totalCost;
    };

    $scope.quote.toggleParamSel = function(s){
      s.selected = !s.selected;
    };

    $scope.quote.selectNorm = function(idNorm) {
      var i, l, j, m, params;
      l = $scope.quote.norms.length;
      $scope.quote.quote.norma = {};
      $scope.quote.quote.parametros_seleccionados = [];
      for (i = 0; i < l; i += 1) {
        if ($scope.quote.norms[i].id_norma == idNorm) {
          $scope.quote.quote.norma = $scope.quote.norms[i];
          break;
        }
      }
      l = $scope.quote.parameters.length;
      params = $scope.quote.quote.norma.parametros;
      for(i = 0; i < l; i += 1) {
        $scope.quote.parameters[i].selected = false;
        if (params !== undefined) {
          m = params.length;
          for (j = 0; j < m; j += 1) {
            if ($scope.quote.parameters[i].id_parametro == params[j].id_parametro) {
              $scope.quote.parameters[i].selected = true;
            }
          }
        }
      }
      return '';
    };

    $scope.quote.submitQuoteForm = function () {

    };
  }
]);

siclabControllers.controller('SamplingOrderController',
  ['$scope', 'Quote', 'OrderSource', 'Matrix', 'SamplingSupervisor', 'SamplingOrder',
  function($scope, Quote, OrderSource, Matrix, SamplingSupervisor, SamplingOrder) {
    $scope.order.order = SamplingOrder.query();
    $scope.order.quote = Quote.query();
    $scope.order.orderSources = OrderSource.query();
    $scope.order.matrices = Matrix.query();
    $scope.order.supervisors = SamplingSupervisor.query();
    $scope.order.selectOrderSource = function() {

    };
    $scope.order.selectMatrix = function() {

    };
    $scope.order.selectSupervisor = function() {

    };
    $scope.order.validateOrderForm = function() {

    };
    $scope.order.submitOrderForm = function() {

    };
  }
]);

siclabControllers.controller('SamplingPlanController',
  ['$scope',
  function() {

  }
]);