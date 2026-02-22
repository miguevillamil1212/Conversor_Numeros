<?php

use App\Core\Router;
use App\Controllers\ConversorController;

Router::get('/', [ConversorController::class, 'index']);
Router::post('/', [ConversorController::class, 'convertir']);