<?php
defined('BASE_PATH') OR exit('No direct script access allowed');

/**
 * Database configuration.
 */
return array(
    // Define database connections.
    //
    // Allowed parameters in array: [mysql, sqlite, pgsql, sqlsrv]
    //
    // This parameters are NOT --case sensitive--
    //
    // Support multiple database.
    // For multiple database, append followed codes after default array.
    //    'read' => array(
    //        'slave1' => array(
    //            'type' => 'mysql',
    //            'dsn' => 'mysql:host=localhost;dbname=database;charset=utf8',
    //            'username' => 'root',
    //            'password' => '',
    //            'options' => [
    //
    //            ]
    //        ) // ,
    //        /*
    //         * Your other databases...
    //         */
    //    ),
    //    'write' => array(
    //        'master' => array(
    //            'type' => 'mysql',
    //            'dsn' => 'mysql:host=localhost;dbname=database;charset=utf8',
    //            'username' => 'root',
    //            'password' => '',
    //            'options' => [
    //
    //            ]
    //        ) // ,
    //        /*
    //         * Your other databases...
    //         */
    //    )
    'databases' => array(
        'default' => array(
            'type' => 'mysql',
             'dsn' => 'mysql:host=localhost;dbname=omega;charset=utf8',
             'username' => 'root',
             'password' => '',
//            'dsn' => 'mysql:host=localhost;dbname=omegair_omega;charset=utf8',
//            'username' => 'omegair_omega',
//            'password' => 'omega1398omega',
            'options' => []
        )
    )
);

