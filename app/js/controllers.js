'use strict';

/* Controllers */

var siclabControllers = angular.module('siclabControllers', []);

siclabControllers.controller('PhoneListCtrl', ['$scope', 'Phone',
  function($scope, Phone) {
    $scope.phones = Phone.query();
    $scope.orderProp = 'age';
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
        $scope.currentLabServiceRequest =
            {
                "id_solicitud_cotizacion":3458,
                "folio_solicitud":432,
                "ejercicio":2014,
                "fecha_solicitud":"2014-07-01",
                "fecha_captura":"2014-07-01",
                "fecha_valida":null,
                "fecha_acepta":null,
                "fecha_actualizacion":"2014-07-01",
                "esta_validado":0,
                "precio_total":2580.00,
                "id_cliente":1,
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
                "id_usuario_captura":1,
                "captura":{
                    "id_usuario":1,
                    "nombre":"Usuario captura",
                    "puesto":"puesto Usuario captura"
                },
                "id_usuario_valida":3,
                "valida":{
                    "id_empleado":3,
                    "nombre":"Gerente que valida",
                    "puesto":"puesto Usuario valida"
                },
                "id_norma":1,
                "norma":
                {
                    "id_norma":1,
                    "norma":"NOM-001-SEMARNAT-1996",
                    "desc":"Norma Oficial Mexicana",
                },
                "parametros":[
                    {"id_parametro":25,"parametro":"Arsénico"},
                    {"id_parametro":27,"parametro":"Cadmio"},
                    {"id_parametro":28,"parametro":"Cobre"},
                    {"id_parametro":38,"parametro":"Coliformes fecales"},
                    {"id_parametro":29,"parametro":"Cromo"},
                    {"id_parametro":16,"parametro":"Demada bioquímica de oxígeno*"},
                    {"id_parametro":19,"parametro":"Fósforo total"},
                    {"id_parametro":18,"parametro":"Grasas y aceites"},
                    {"id_parametro":6,"parametro":"Alcalinidad total"},
                    {"id_parametro":39,"parametro":"Materia flotante"},
                    {"id_parametro":32,"parametro":"Mercurio"},
                    {"id_parametro":7,"parametro":"Cloruros totales"},
                    {"id_parametro":33,"parametro":"Níquel"},
                    {"id_parametro":2,"parametro":"Potencial de hidrógeno"},
                    {"id_parametro":34,"parametro":"Plomo"},
                    {"id_parametro":22,"parametro":"Sólidos sedimentables"},
                    {"id_parametro":20,"parametro":"Sólidos suspendidos totales"},
                    {"id_parametro":1,"parametro":"Temperatura"},
                    {"id_parametro":36,"parametro":"Zinc"}
                ],
                "actividades_adicionales":[
                    {
                        "id_actividad":1,
                        "actividad":"Muestreo instantáneo",
                        "id_metodo":87,
                        "metodo":{
                            "id_metodo":87,
                            "metodo":"metodo para muestreo instantáneo"
                        },
                        "id_precio":80,
                        "precio": {
                            "id_precio":80,
                            "precio":0,
                            "fecha_actualizacion":"2014-01-01",
                            "activo":1
                        }
                    }
                ],
                "id_tipo_muestreo":1,
                "tipo_muestreo":
                {
                    "id_tipo_muestreo":1,
                    "tipo_muestreo":"Simple"
                }
            }
        ;
    }
]);

siclabControllers.controller('PhoneDetailCtrl', ['$scope', '$routeParams', 'Phone',
  function($scope, $routeParams, Phone) {
    $scope.phone = Phone.get({phoneId: $routeParams.phoneId}, function(phone) {
      $scope.mainImageUrl = phone.images[0];
    });

    $scope.setImage = function(imageUrl) {
      $scope.mainImageUrl = imageUrl;
    }
  }]);
