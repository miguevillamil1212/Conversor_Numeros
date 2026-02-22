LISTA DE SUPUESTOS QUE YO REALICE:
## a. Cómo realizar las conversiones

Números arábigos → romanos: Se utiliza un mapa descendente de valores (1000 → M, 900 → CM, 500 → D, … 1 → I) y se resta iterativamente del número arábigo agregando las letras romanas correspondientes.

Números romanos → arábigos: Se recorre la cadena romana de derecha a izquierda, sumando o restando según el valor del símbolo respecto al anterior (ejemplo: IV = 5 - 1 = 4).

Ejemplo:

1987 → MCMLXXXVII

XLII → 42

## b. Rango numérico

Se limita la conversión a 1–3999 porque los números romanos clásicos no representan ceros ni valores mayores sin notación especial.

Para entradas fuera de este rango se muestra un mensaje de error.

## c. Cómo manejar errores

Si el usuario deja el campo vacío → mensaje: "Error: no se ingresó ningún número".

Si se ingresa un número fuera del rango 1–3999 → "Error: número fuera del rango permitido".

Si se ingresa un símbolo romano inválido → "Error: número romano inválido".

El sistema no se rompe: los errores se muestran en pantalla junto con un enlace para volver a la página principal.

## d. Diseño de la interfaz

Web simple y didáctica: Formulario con un solo campo de entrada y un botón de conversión.

Resultado mostrado debajo del formulario sin recargar toda la página (se puede mejorar luego con AJAX si se desea).

Mensajes de error claros y visibles, con enlace “Volver” al formulario principal.

HTML limpio y etiquetas semánticas:

<h1> para título del módulo

<form> para entrada de datos

<input type="text"> para el número

<button> para ejecutar la conversión

<p> para mensajes de resultado o error

## e. Tipo de solución

Aplicación web tipo MVC (Modelo-Vista-Controlador) utilizando PHP puro:

Controlador: Lógica de conversión y manejo de errores.

Vista: Formulario HTML + resultados.

Rutas: Archivo routes/web.php que define URLs / y /convertir.
