<?php
namespace App\Controllers;

class ConversorController {
    public function index() {
        $entrada = '';
        $resultado = null;
        $error = null;
        $explicacion = null;
        $tipoConversion = 'A→R';
        $this->renderView($entrada, $resultado, $error, $explicacion, $tipoConversion);
    }

    public function convertir() {
        $entrada = $_POST['texto_origen'] ?? '';
        $modo = $_POST['modo'] ?? 'A→R';
        $resultado = null;
        $error = null;
        $explicacion = null;
        $tipoConversion = $modo;

        if (trim($entrada) === '') {
            $error = "No ingresó ningún valor";
        } else {
            if ($modo === 'A→R') {
                if (!ctype_digit($entrada)) {
                    $error = "Solo dígitos para arábigo → romano";
                } else {
                    $numero = (int)$entrada;
                    if ($numero < 1 || $numero > 3999) {
                        $error = "Rango: 1-3999";
                    } else {
                        [$resultado, $explicacion] = $this->arabigoARomanoConExplicacion($numero);
                    }
                }
            } else {
                $romano = strtoupper(trim($entrada));
                $validacion = $this->validarRomano($romano);
                if (!$validacion['valido']) {
                    $error = $validacion['mensaje'];
                } else {
                    [$resultado, $explicacion] = $this->romanoAArabigoConExplicacion($romano);
                    if ($resultado < 1 || $resultado > 3999) {
                        $error = "Rango: I-MMMCMXCIX";
                        $resultado = null;
                        $explicacion = null;
                    }
                }
            }
        }

        $this->renderView($entrada, $resultado, $error, $explicacion, $tipoConversion);
    }

    private function renderView($entrada, $resultado, $error, $explicacion, $tipoConversion) {
        require __DIR__ . '/../../public/conversor-view.php';
    }

    private function arabigoARomanoConExplicacion($numero) {
        $mapa = [
            1000 => "M", 900 => "CM", 500 => "D", 400 => "CD",
            100 => "C", 90 => "XC", 50 => "L", 40 => "XL",
            10 => "X", 9 => "IX", 5 => "V", 4 => "IV", 1 => "I"
        ];

        $resultado = "";
        $descomposicion = [];
        $original = $numero;

        foreach ($mapa as $valor => $romano) {
            $conteo = 0;
            while ($numero >= $valor) {
                $resultado .= $romano;
                $numero -= $valor;
                $conteo++;
            }
            if ($conteo > 0) {
                $descomposicion[] = "{$valor} × {$conteo} → " . str_repeat($romano, $conteo);
            }
        }

        $exp = "Entrada: {$original}\n";
        $exp .= implode("\n", $descomposicion);
        $exp .= "\nResultado: {$resultado}";

        return [$resultado, $exp];
    }

    private function romanoAArabigoConExplicacion($romano) {
        $valores = ['I'=>1, 'V'=>5, 'X'=>10, 'L'=>50, 'C'=>100, 'D'=>500, 'M'=>1000];
        $resultado = 0;
        $previo = 0;
        $pasos = [];

        for ($i = strlen($romano) - 1; $i >= 0; $i--) {
            $simbolo = $romano[$i];
            $actual = $valores[$simbolo];
            $signo = ($actual < $previo) ? '-' : '+';
            $pasos[] = "{$simbolo} ({$actual}) {$signo}";
            if ($actual < $previo) {
                $resultado -= $actual;
            } else {
                $resultado += $actual;
            }
            $previo = $actual;
        }

        $exp = "Romano: {$romano}\n";
        $exp .= "De derecha a izquierda:\n";
        $exp .= implode(" → ", $pasos);
        $exp .= "\nTotal: {$resultado}";

        return [$resultado, $exp];
    }

    private function validarRomano($romano) {
        if (!preg_match('/^[IVXLCDM]+$/', $romano)) {
            return ['valido' => false, 'mensaje' => 'Caracteres inválidos'];
        }
        if (preg_match('/(IIII|XXXX|CCCC|MMMM|VV|LL|DD)/', $romano)) {
            return ['valido' => false, 'mensaje' => 'Más de 3 iguales o V/L/D repetidos'];
        }
        if (preg_match('/(IL|IC|ID|IM|XD|XM|VX|LC|LD|LM|DM)/', $romano)) {
            return ['valido' => false, 'mensaje' => 'Sustracciones inválidas'];
        }
        $valores = ['I'=>1,'V'=>5,'X'=>10,'L'=>50,'C'=>100,'D'=>500,'M'=>1000];
        for ($i = 0; $i < strlen($romano) - 1; $i++) {
            $actual = $valores[$romano[$i]];
            $siguiente = $valores[$romano[$i+1]];
            if ($actual < $siguiente) {
                $par = $romano[$i].$romano[$i+1];
                if (!in_array($par, ['IV','IX','XL','XC','CD','CM'])) {
                    return ['valido' => false, 'mensaje' => "Par inválido: {$par}"];
                }
            }
        }
        return ['valido' => true, 'mensaje' => 'OK'];
    }
}
?>
