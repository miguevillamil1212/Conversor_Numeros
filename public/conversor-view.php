<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Módulo Didáctico - Conversor</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f1ee;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .card {
            background: white;
            padding: 40px;
            border-radius: 12px;
            width: 420px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            text-align: center;
        }
        h2 {
            color: #6b4f3b;
        }
        input {
            width: 90%;
            padding: 12px;
            margin-top: 15px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }
        button {
            margin-top: 15px;
            padding: 12px 25px;
            border: none;
            background: #6b4f3b;
            color: white;
            border-radius: 8px;
            cursor: pointer;
        }
        .resultado {
            margin-top: 20px;
            font-weight: bold;
            color: green;
        }
        .error {
            margin-top: 20px;
            font-weight: bold;
            color: red;
        }
    </style>
</head>
<body>
    <div class="card">
        <h2>Módulo Didáctico</h2>
        <p>Conversión de Números Arábigos y Romanos</p>

        <?php
        // Asegurar que las variables existan
        $entrada   = $entrada ?? '';
        $resultado = $resultado ?? null;
        $error     = $error ?? null;
        ?>

        <form method="POST" action="">
    <input type="text" name="numero" placeholder="Ej: 25 o XXV" value="<?= htmlspecialchars($entrada) ?>">
    <br>
    <button type="submit">Convertir</button>
</form>

        <?php if ($resultado !== null): ?>
            <div class="resultado">
                Resultado: <?= htmlspecialchars($resultado) ?>
            </div>
        <?php endif; ?>

        <?php if ($error !== null): ?>
            <div class="error">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>