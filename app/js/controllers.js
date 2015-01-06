'use strict';

/* Controllers */

var siclabControllers = angular.module('siclabControllers', []);

siclabControllers.controller('LoginController',
  ['$scope', 'Login', function($scope, Login) {
  //$scope.user = Login.query();
  $scope.user = {
    username: '',
    password: ''
  };
  $scope.login = function() {
    if ($scope.loginForm.$valid)
    {
      if ($scope.user.username == 'rgarcia' &&
        $scope.user.password == '123'
      )
      {
        //console.log('Enviando...');

      }
      else
      {
        //console.log('Usuario o contraseña incorrectos');
      }

    }
    else
    {
      //console.log('Debe ingresar usuario y/o contraseña');
    }
  };
}]);

siclabControllers.controller('NavController',
  ['$scope', 'Menu', function($scope, Menu) {
  $scope.menu = Menu.query();
}]);

siclabControllers.controller('TasksController',
  ['$scope', function() {
  $scope.welcome = {
    title:"Bienvenido",
    subtitle:"Sistema de Control y Administración de Laboratorio"
  };
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
    /*
      <form name="myForm">
        <input type="text" ng-model="field" name="myField" required minlength="5" />
        <div ng-messages="myForm.myField.$error">
        <div ng-message="required">You did not enter a field</div>
        <div ng-message="minlength">The value entered is too short</div>
        </div>
      </form>
      ng-class="{ 'has-error': registerForm.email.$invalid && (registerForm.email.$touched || registerForm.$submitted), 'has-success': !registerForm.email.$invalid && (registerForm.email.$touched || registerForm.$submitted)}"

      and

      this.reset = function() {
      registerController.email = "";
      registerController.password = "";
      registerController.repeatPassword = "";
      $scope.quote.registerForm.$setUntouched(true);
      $scope.quote.registerForm.$setPristine(true);
      };
    */
  }
]);

siclabControllers.controller('SamplingOrderController',
  ['$scope', 'Quote', 'OrderSource', 'Matrix', 'SamplingSupervisor', 'SamplingOrder',
  function($scope, Quote, OrderSource, Matrix, SamplingSupervisor, SamplingOrder) {
    $scope.order = SamplingOrder.query();
    $scope.quote = Quote.query();
    $scope.orderSources = OrderSource.query();
    $scope.matrices = Matrix.query();
    $scope.supervisors = SamplingSupervisor.query();
    $scope.selectOrderSource = function() {

    };
    $scope.selectMatrix = function() {

    };
    $scope.selectSupervisor = function() {

    };
    $scope.validateOrderForm = function() {

    };
    $scope.submitOrderForm = function() {

    };
  }
]);

siclabControllers.controller('SamplingPlanController',
  ['$scope',
  function() {}
]);


