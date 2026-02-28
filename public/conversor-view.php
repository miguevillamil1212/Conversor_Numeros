<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Conversor Arábigo ↔ Romano</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            background: #f4f1ee;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .app {
            background: white;
            width: 900px;
            max-width: 95%;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            padding: 20px 30px 30px;
        }
        h1 { margin-top: 0; text-align: center; color: #6b4f3b; }
        .subtitulo { text-align: center; color: #777; margin-bottom: 20px; }
        .paneles { display: flex; gap: 10px; align-items: stretch; }
        .panel { flex: 1; display: flex; flex-direction: column; }
        .panel label { font-size: 13px; color: #555; margin-bottom: 4px; }
        textarea {
            flex: 1; resize: vertical; min-height: 130px;
            padding: 10px; border-radius: 8px; border: 1px solid #ccc; font-size: 16px;
        }
        .controles-centrales {
            display: flex; flex-direction: column; align-items: center;
            justify-content: center; gap: 10px; padding: 0 5px;
        }
        .modo-select { padding: 6px 10px; border-radius: 8px; border: 1px solid #ccc; font-size: 13px; }
        .btn-intercambiar {
            border: none; border-radius: 50%; width: 36px; height: 36px;
            background: #6b4f3b; color: #fff; cursor: pointer; font-size: 18px;
        }
        .acciones {
            margin-top: 15px; display: flex; justify-content: flex-end; gap: 10px;
        }
        button[type="submit"], .btn-limpiar {
            padding: 10px 20px; border-radius: 8px; border: none;
            cursor: pointer; font-size: 14px;
        }
        button[type="submit"] { background: #6b4f3b; color: #fff; }
        .btn-limpiar { background: #eee; }
        .resultado, .error, .explicacion {
            margin-top: 15px; padding: 10px 12px; border-radius: 8px;
            font-size: 14px; white-space: pre-line;
        }
        .resultado { background: #e6f5ea; color: #1c6b2c; font-weight: bold; }
        .error { background: #fde7e7; color: #c0392b; font-weight: bold; }
        .explicacion { background: #f5f2ec; color: #555; }
        @media (max-width: 700px) {
            .paneles { flex-direction: column; }
            .controles-centrales { flex-direction: row; justify-content: center; }
        }
    </style>
</head>
<body>
<div class="app">
    <h1>Conversor Arábigo ↔ Romano</h1>
    <p class="subtitulo">1-3999 ↔ I-MMMCMXCIX</p>

    <?php
    $entrada = $entrada ?? '';
    $resultado = $resultado ?? null;
    $error = $error ?? null;
    $explicacion = $explicacion ?? null;
    $tipoConversion = $tipoConversion ?? 'A→R';
    ?>

    <form method="POST" action="" id="form-conversor">
        <div class="paneles">
            <div class="panel">
                <label>
                    Origen <?php echo ($tipoConversion === 'A→R') ? '(Arábigo)' : '(Romano)'; ?>
                </label>
                <textarea name="texto_origen" id="texto_origen"
                          placeholder="<?php echo ($tipoConversion === 'A→R') ? '1987' : 'MCMLXXXVII'; ?>">
                    <?= htmlspecialchars($entrada) ?>
                </textarea>
            </div>

            <div class="controles-centrales">
                <select name="modo" id="modo" class="modo-select" onchange="this.form.submit()">
                    <option value="A→R" <?= $tipoConversion === 'A→R' ? 'selected' : '' ?>>Arábigo → Romano</option>
                    <option value="R→A" <?= $tipoConversion === 'R→A' ? 'selected' : '' ?>>Romano → Arábigo</option>
                </select>
                <button type="button" class="btn-intercambiar" onclick="intercambiar()" title="Intercambiar">⇄</button>
            </div>

            <div class="panel">
                <label>Resultado <?php echo ($tipoConversion === 'A→R') ? '(Romano)' : '(Arábigo)'; ?></label>
                <textarea id="texto_destino" readonly><?= $resultado !== null ? htmlspecialchars($resultado) : '' ?></textarea>
            </div>
        </div>

        <div class="acciones">
            <button type="button" class="btn-limpiar" onclick="limpiar()">Limpiar</button>
            <button type="submit">Convertir</button>
        </div>
    </form>

    <?php if ($error !== null): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($resultado !== null && $error === null): ?>
        <div class="resultado"><?= htmlspecialchars($resultado) ?></div>
    <?php endif; ?>

    <?php if ($explicacion !== null): ?>
        <div class="explicacion"><?= nl2br(htmlspecialchars($explicacion)) ?></div>
    <?php endif; ?>
</div>

<script>
function intercambiar() {
    const modo = document.getElementById('modo');
    modo.value = (modo.value === 'A→R') ? 'R→A' : 'A→R';
    document.getElementById('form-conversor').submit();
}
function limpiar() {
    document.getElementById('texto_origen').value = '';
    document.getElementById('texto_destino').value = '';
}
</script>
</body>
</html>
