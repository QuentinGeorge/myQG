<?php
session_start();

$aRoutes = require( 'configs/routes.php' );
$sDefaultRoute = $aRoutes[ 'get_login' ];
$aRoutesplited = explode( '/', $sDefaultRoute );
$method = $_SERVER[ 'REQUEST_METHOD' ];

$a = $_REQUEST[ 'a' ]??$aRoutesplited[ 1 ];
$r = $_REQUEST[ 'r' ]??$aRoutesplited[ 2 ];

if ( !in_array( $method . '/' . $a . '/' . $r, $aRoutes ) ) {
    die( 'Ce que vous cherchez n’est pas ici' );
}
$sControllerName = 'Controllers\\' . ucfirst( $r );
$controller = new $sControllerName();

$data = call_user_func( [ $controller, $a ] );
