'use strict';

/* Controllers */

var siclabControllers = angular.module('siclabControllers', []);

siclabApp.controller('LoginCtrl', ['$scope', 'Login', function($scope, Login) {
    //$scope.user = Login.query();
    $scope.user = {
        username: '',
        password: ''
    }
    $scope.login = function() {
        if ($scope.loginForm.$valid)
        {
            if ($scope.user.username == 'rgarcia' &&
                $scope.user.password == '123'
            ) {
                console.log('Enviando...');
            }
            else
            {
                console.log('Usuario o contraseña incorrectos');
            }

        }
        else
        {
            console.log('Debe ingresar usuario y/o contraseña');
        }
    };
}]);

siclabApp.controller('NavCtrl', ['$scope', 'Menu', function($scope, Menu) {
    $scope.menu = Menu.query();
}]);

siclabApp.controller('TasksCtrl', ['$scope', function($scope) {
    $scope.welcome = {title:"Bienvenido", subtitle:"Sistema de Control y Administración de Laboratorio"};
}]);

siclabControllers.controller('ClientsListCtrl', ['$scope', 'Client', function($scope, Client) {
    $scope.clients = Client.query();
}]);

siclabControllers.controller('ClientDetailCtrl', ['$scope', 'ClientDetail', function($scope, ClientDetail) {
    $scope.clientDetail = ClientDetail.query();
}]);

siclabControllers.controller('DepartmentsListCtrl', ['$scope', 'Department', function($scope, Department) {
    $scope.departments = Department.query();
}]);

siclabControllers.controller('EmployeesListCtrl', ['$scope', 'Employee', function($scope, Employee) {
    $scope.employees = Employee.query();
}]);

siclabControllers.controller('UsersListCtrl', ['$scope', 'User', function($scope, User) {
    $scope.users = User.query();
}]);

siclabControllers.controller('NormsListCtrl', ['$scope', 'Norm', function($scope, Norm) {
    $scope.norms = Norm.query();
}]);

siclabControllers.controller('LabServiceRequestCtrl',
    ['$scope', 'Client', 'Parameter', 'Norm', 'SamplingType',
    function($scope, Client, Parameter, Norm, SamplingType) {
        $scope.clients = Client.query();
        $scope.parameters = Parameter.query();
        $scope.norms = Norm.query();
        $scope.samplingTypes = SamplingType.query();
        $scope.labServiceRequest =
            {
                "id_solicitud_cotizacion":3458,
                "folio_solicitud":432,
                "ejercicio":2014,
                "fecha_solicitud":"2014-07-01",
                "fecha_captura":"2014-07-01",
                "fecha_valida":null,
                "fecha_acepta":null,
                "fecha_actualizacion":"2014-07-01",
                "validado":0,
                "precio_total":2580.00,
                "id_cliente":1,
                "id_usuario_captura":1,
                "id_usuario_valida":3,
                "id_norma":1,
                "id_tipo_muestreo":1,
                "cliente":
                {
                    "id_cliente":1,
                    "id_organismo":1,
                    "cliente":"CEA Jalisco",
                    "area":"Dirección de Operación de PTAR'S",
                    "rfc":"Registro Federal de Contribuyentes",
                    "calle":"Av. Brasilia",
                    "numero":"2970",
                    "colonia":"Col. Colomos Providencia",
                    "cp":"44680",
                    "id_estado":14,
                    "id_municipio":14039,
                    "municipio":"Guadalajara",
                    "id_localidad":140390001,
                    "localidad":"Guadalajara",
                    "tel":"3030-9350 ext. 8370",
                    "fax":"",
                    "contacto":"Biol. Luis Aceves Martínez",
                    "puesto_contacto":"puesto contacto",
                    "email":"laceves@ceajalisco.gob.mx",
                    "fecha_act":"2014-11-23",
                    "interno":1,
                    "cea":1,
                    "tasa":0,
                    "activo":1
                },
                "descripcion_servicio":"Servicio de muestreo y análisis, para verificar el cumplimiento de la norma NOM-001-SEMARNAT-1996, que establece los límites máximos permisibles de contaminantes en las descargas de aguas residuales a los sistemas de alcantarillado urbano o municipal. -auto",
                "notas":"La presente cotización se realiza sin visita previa y se contempla un fácil y seguro acceso para la toma de muestras. Se requiere regresar esta cotización con la firma y sello de Aceptación del Servicio. -auto",
                "condiciones":"El informe de resultados se entregará a los 10 días hábiles de haber ingresado las muestras al laboratorio. El pago de resultados se hará en las instalaciones del Laboratorio de Calidad del Agua de la CEA, así también mediante depósito bancario a la cuenta: 884371445 de la Institución Bancaria BANORTE a nombre de la Comisión Estatal del Agua de Jalisco o por transferencia electrónica, cuenta interbancaria: 072320008843714454. -auto",
                "captura":{
                    "id_usuario":1,
                    "nombre":"Usuario captura",
                    "puesto":"puesto Usuario captura"
                },
                "valida":{
                    "id_empleado":3,
                    "nombre":"Gerente que valida",
                    "puesto":"puesto Usuario valida"
                },
                "norma":{
                    "id_norma":1,
                    "norma":"NOM-001-SEMARNAT-1996",
                    "desc":"Norma Oficial Mexicana",
                    "parametros":[
                        {
                            "id_parametro":25,
                            "parametro":"Arsénico",
                            "cert":1,
                            "id_metodo":1,
                            "metodo": {
                                "id_metodo":1,
                                "metodo":"NMX-AA-000-0000"
                            },
                            "cantidad":1,
                            "precio":164.65
                        },
                        {
                            "id_parametro":27,
                            "parametro":"Cadmio",
                            "cert":1,
                            "id_metodo":1,
                            "metodo": {
                                "id_metodo":1,
                                "metodo":"NMX-AA-000-0000"
                            },
                            "cantidad":1,
                            "precio":164.65
                        },
                        {
                            "id_parametro":28,
                            "parametro":"Cobre",
                            "cert":1,
                            "id_metodo":1,
                            "metodo": {
                                "id_metodo":1,
                                "metodo":"NMX-AA-000-0000"
                            },
                            "cantidad":1,
                            "precio":164.65
                        },
                        {
                            "id_parametro":38,
                            "parametro":"Coliformes fecales",
                            "cert":1,
                            "id_metodo":1,
                            "metodo": {
                                "id_metodo":1,
                                "metodo":"NMX-AA-000-0000"
                            },
                            "cantidad":1,
                            "precio":164.65
                        },
                        {
                            "id_parametro":29,
                            "parametro":"Cromo",
                            "cert":1,
                            "id_metodo":1,
                            "metodo": {
                                "id_metodo":1,
                                "metodo":"NMX-AA-000-0000"
                            },
                            "cantidad":1,
                            "precio":164.65
                        },
                        {
                            "id_parametro":16,
                            "parametro":"Demada bioquímica de oxígeno",
                            "cert":0,
                            "id_metodo":1,
                            "metodo": {
                                "id_metodo":1,
                                "metodo":"NMX-AA-000-0000"
                            },
                            "cantidad":1,
                            "precio":164.65
                        },
                        {
                            "id_parametro":19,
                            "parametro":"Fósforo total",
                            "cert":1,
                            "id_metodo":1,
                            "metodo": {
                                "id_metodo":1,
                                "metodo":"NMX-AA-000-0000"
                            },
                            "cantidad":1,
                            "precio":164.65
                        },
                        {
                            "id_parametro":18,
                            "parametro":"Grasas y aceites",
                            "cert":1,
                            "id_metodo":1,
                            "metodo": {
                                "id_metodo":1,
                                "metodo":"NMX-AA-000-0000"
                            },
                            "cantidad":1,
                            "precio":164.65
                        },
                        {
                            "id_parametro":6,
                            "parametro":"Alcalinidad total",
                            "cert":1,
                            "id_metodo":1,
                            "metodo": {
                                "id_metodo":1,
                                "metodo":"NMX-AA-000-0000"
                            },
                            "cantidad":1,
                            "precio":164.65
                        },
                        {
                            "id_parametro":39,
                            "parametro":"Materia flotante",
                            "cert":1,
                            "id_metodo":1,
                            "metodo": {
                                "id_metodo":1,
                                "metodo":"NMX-AA-000-0000"
                            },
                            "cantidad":1,
                            "precio":164.65
                        },
                        {
                            "id_parametro":32,
                            "parametro":"Mercurio",
                            "cert":1,
                            "id_metodo":1,
                            "metodo": {
                                "id_metodo":1,
                                "metodo":"NMX-AA-000-0000"
                            },
                            "cantidad":1,
                            "precio":164.65
                        },
                        {
                            "id_parametro":7,
                            "parametro":"Cloruros totales",
                            "cert":1,
                            "id_metodo":1,
                            "metodo": {
                                "id_metodo":1,
                                "metodo":"NMX-AA-000-0000"
                            },
                            "cantidad":1,
                            "precio":164.65
                        },
                        {
                            "id_parametro":33,
                            "parametro":"Níquel",
                            "cert":1,
                            "id_metodo":1,
                            "metodo": {
                                "id_metodo":1,
                                "metodo":"NMX-AA-000-0000"
                            },
                            "cantidad":1,
                            "precio":164.65
                        },
                        {
                            "id_parametro":2,
                            "parametro":"Potencial de hidrógeno",
                            "cert":1,
                            "id_metodo":1,
                            "metodo": {
                                "id_metodo":1,
                                "metodo":"NMX-AA-000-0000"
                            },
                            "cantidad":1,
                            "precio":164.65
                        },
                        {
                            "id_parametro":34,
                            "parametro":"Plomo",
                            "cert":1,
                            "id_metodo":1,
                            "metodo": {
                                "id_metodo":1,
                                "metodo":"NMX-AA-000-0000"
                            },
                            "cantidad":1,
                            "precio":164.65
                        },
                        {
                            "id_parametro":22,
                            "parametro":"Sólidos sedimentables",
                            "cert":1,
                            "id_metodo":1,
                            "metodo": {
                                "id_metodo":1,
                                "metodo":"NMX-AA-000-0000"
                            },
                            "cantidad":1,
                            "precio":164.65
                        },
                        {
                            "id_parametro":20,
                            "parametro":"Sólidos suspendidos totales",
                            "cert":1,
                            "id_metodo":1,
                            "metodo": {
                                "id_metodo":1,
                                "metodo":"NMX-AA-000-0000"
                            },
                            "cantidad":1,
                            "precio":164.65
                        },
                        {
                            "id_parametro":1,
                            "parametro":"Temperatura",
                            "cert":1,
                            "id_metodo":1,
                            "metodo": {
                                "id_metodo":1,
                                "metodo":"NMX-AA-000-0000"
                            },
                            "cantidad":1,
                            "precio":164.65
                        },
                        {
                            "id_parametro":36,
                            "parametro":"Zinc",
                            "cert":1,
                            "id_metodo":1,
                            "metodo": {
                                "id_metodo":1,
                                "metodo":"NMX-AA-000-0000"
                            },
                            "cantidad":1,
                            "precio":164.65
                        },
                    ]
                },
                "actividades":[
                    {
                        "id_actividad":1,
                        "actividad":"Muestreo instantáneo",
                        "id_metodo":87,
                        "metodo":{
                            "id_metodo":87,
                            "metodo":"metodo para muestreo instantáneo",
                        },
                        "cantidad":1,
                        "precio":0
                    }
                ],
                "tipo_muestreo":
                {
                    "id_tipo_muestreo":1,
                    "tipo_muestreo":"Simple"
                },
                "parametros_seleccionados":[]
            }
        ;
        $scope.currentClient = {};
        $scope.clientDetailsIsShown = false;
        $scope.toggleClientInfo = function($event) {
            var id = $scope.labServiceRequest.id_cliente;
            $event.stopPropagation();
            $scope.clientDetailsIsShown = (
                $scope.labServiceRequest.id_cliente > 0 &&
                $scope.selectClient(id).cliente &&
                !$scope.clientDetailsIsShown
            );
        };

        $scope.selectClient = function(idClient) {
            var i = 0,l = $scope.clients.length;
            $scope.currentClient = {};
            for (i; i < l; i += 1) {
                if ($scope.clients[i].id_cliente == idClient) {
                    $scope.currentClient = $scope.clients[i];
                    break;
                }
            }
            $scope.labServiceRequest.cliente = $scope.currentClient;
            return $scope.currentClient;
        };

        $scope.totalParameter=function(){
            var t = 0;
            angular.forEach($scope.parameters, function(s){
                if(s.selected) {
                    t += parseFloat(s.precio);
                }
            });
            t = t * $scope.labServiceRequest.cliente.tasa;
            return (Math.round(t * 100) / 100);
        };

        $scope.toggleParamSel = function(s){
            s.selected = !s.selected;
        };

        $scope.selectNorm = function(idNorm) {
            var i, l, j, m, params;
            l = $scope.norms.length;
            $scope.labServiceRequest.norma = {};
            $scope.labServiceRequest.parametros_seleccionados = [];
            for (i = 0; i < l; i += 1) {
                if ($scope.norms[i].id_norma == idNorm) {
                    $scope.labServiceRequest.norma = $scope.norms[i];
                    break;
                }
            }
            l = $scope.parameters.length;
            params = $scope.labServiceRequest.norma.parametros;
            for(i = 0; i < l; i += 1) {
                $scope.parameters[i].selected = false;
                if (params !== undefined) {
                    m = params.length;
                    for (j = 0; j < m; j += 1) {
                        if ($scope.parameters[i].id_parametro == params[j].id_parametro) {
                            $scope.parameters[i].selected = true;
                        }
                    }
                }
            }
            return '';
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
            $scope.registerForm.$setUntouched(true);
            $scope.registerForm.$setPristine(true);
            };
        */
    }
]);