<?php

class Router {
    
    // Como el router no tiene estados, los métodos pueden ser estáticos.
    public static function dispatch() {
        // Determinar controlador (por defecto EmpleadoController)
        $controllerName = $_POST['controller'] ?? $_GET['controller'] ?? 'PreguntasController';
        
        // Determinar acción (por defecto index)
        $action = $_POST['accion'] ?? $_GET['accion'] ?? 'index';
        
        // Cargar y ejecutar controlador
        self::loadController($controllerName, $action);
    }
    
    private static function loadController($controllerName, $action) {
        // Construir ruta del archivo del controlador
        $controllerFile = __DIR__ . "/controllers/{$controllerName}.php";
        require_once $controllerFile;
        
        // Instanciar controlador
        $controller = new $controllerName();

        // Ejecutar método
        $controller->$action();
    }
    

}