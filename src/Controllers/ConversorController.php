<?php

namespace App\Controllers;

class ConversorController {

    public function index() {
        $entrada = '';
        $resultado = null;
        $error = null;

        $this->renderView($entrada, $resultado, $error);
    }

    public function convertir() {
        $entrada = $_POST['numero'] ?? '';
        $resultado = null;
        $error = null;

        if ($entrada === '') {
            $error = "No ingresó ningún valor";
        } else {
            if (is_numeric($entrada)) {
                $numero = (int)$entrada;
                if ($numero < 1 || $numero > 3999) {
                    $error = "Número fuera del rango permitido (1–3999)";
                } else {
                    $resultado = $this->arabigoARomano($numero);
                }
            } else {
                $arabigo = $this->romanoAArabigo(strtoupper($entrada));
                if ($arabigo < 1 || $arabigo > 3999) {
                    $error = "Número romano fuera del rango permitido (I–MMMCMXCIX)";
                } else {
                    $resultado = $arabigo;
                }
            }
        }

        $this->renderView($entrada, $resultado, $error);
    }

    private function renderView($entrada, $resultado, $error) {
        require __DIR__ . '/../../public/conversor-view.php';
    }

    private function arabigoARomano($numero) {
        $mapa = [
            1000 => "M", 900 => "CM", 500 => "D", 400 => "CD",
            100 => "C", 90 => "XC", 50 => "L", 40 => "XL",
            10 => "X", 9 => "IX", 5 => "V", 4 => "IV", 1 => "I"
        ];

        $resultado = "";
        foreach ($mapa as $valor => $romano) {
            while ($numero >= $valor) {
                $resultado .= $romano;
                $numero -= $valor;
            }
        }
        return $resultado;
    }

    private function romanoAArabigo($romano) {
        $valores = [
            'I' => 1, 'V' => 5, 'X' => 10,
            'L' => 50, 'C' => 100, 'D' => 500, 'M' => 1000
        ];

        $resultado = 0;
        $previo = 0;

        for ($i = strlen($romano) - 1; $i >= 0; $i--) {
            $actual = $valores[$romano[$i]] ?? 0;

            if ($actual === 0) {
                // Caracter romano inválido
                return -1; // Esto será detectado como fuera de rango
            }

            if ($actual < $previo) {
                $resultado -= $actual;
            } else {
                $resultado += $actual;
            }
            $previo = $actual;
        }

        return $resultado;
    }
}