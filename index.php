<?php

session_start();

if (!isset($_SESSION['puestos'])) {
    $_SESSION['puestos'] = [
        ["L", "L", "L", "L", "L"],
        ["L", "L", "L", "L", "L"],
        ["L", "L", "L", "L", "L"],
        ["L", "L", "L", "L", "L"],
        ["L", "L", "L", "L", "L"],
    ];
}


$puestos = &$_SESSION['puestos']; // Referencia a los puestos en la sesión

$errores = "";
$vendido = "";
$reservado = "";
$disponible = "";



if ($_SERVER["REQUEST_METHOD"] == "POST") {


    $fila = intval($_POST["fila"]);
    $puesto = intval($_POST["puesto"]);
    $cupos = $_POST["cupos"];

    if (isset($_POST['action']) && $_POST['action'] == 'borrar') {
        // Restablecer el array 'puestos' a su estado inicial
        $_SESSION['puestos'] = [
            ["L", "L", "L", "L", "L"],
            ["L", "L", "L", "L", "L"],
            ["L", "L", "L", "L", "L"],
            ["L", "L", "L", "L", "L"],
            ["L", "L", "L", "L", "L"],
        ];
        // Redirigir para evitar el reenvío del formulario
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }


    if ($fila >= 0 && $fila < count($puestos) && $puesto >= 0 && $puesto < count($puestos[$fila])) {
        switch ($cupos) {
            case "reservar":
                if ($puestos[$fila][$puesto] == "L") {
                    $puestos[$fila][$puesto] = "R";
                }
                break;
            case "comprar":
                if ($puestos[$fila][$puesto] !== "V") {
                    $puestos[$fila][$puesto] = "V";
                }
                break;
            case "liberar":
                if ($puestos[$fila][$puesto] == "R") {
                    $puestos[$fila][$puesto] = "L";
                }
                break;
            default:
                $errores = "Accion desconocida";
                break;
        }
    } else {
        $errores = "Pasaste el limite de los campos el valor minimo es 0 y el maximo es " . count($puestos) + 1;
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Escenario</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <main class="contenedor">
        <section class="contenedor_sesion">

            <div class="contenedor_sesion__contenido">
                <div class="contenido_escenario">
                    <p>escenario</p>
                </div>
                <div class="contenido_lugares">
                    <?php
                    echo '<table class="contenido_lugares__tabla">'; // Crear una tabla con borde

                    // Encabezado de la tabla
                    echo '<tr><th></th>'; // Celda vacía en la esquina superior izquierda
                    for ($fila = 0; $fila < count($puestos[0]); $fila++) {
                        echo "<th>$fila</th>"; // Encabezados de las columnas
                    }
                    echo '</tr>';

                    // Cuerpo de la tabla
                    foreach ($puestos as $indiceFila => $fila) {
                        echo "<tr>";
                        echo "<th>" . ($indiceFila) . "</th>"; // Encabezado de la fila

                        foreach ($fila as $celda) {
                            echo "<td class='puestos'>$celda</td>"; // Celdas de la fila
                        }
                        echo "</tr>";
                    }
                    echo '</table>';
                    ?>
                </div>
            </div>

            <div class="contenedor_sesion__controles">

                <form action="index.php" method="post" class="controles_formulario" id="formulario">
                    <label for="fila">
                        <p class="controles_formulario-text">
                            Fila:
                        </p>
                    </label>
                    <input name="fila" min="0" type="number" id="fila" max=<?php echo count($puestos) - 1;?>>
                    <label for="puesto">
                        <p class="controles_formulario-text">
                            Puesto:
                        </p>
                    </label>
                    <input type="number" min="0" name="puesto" id="puesto" max=<?php echo count($puestos) - 1;?>>
                    <label for="reservar">
                        <p class="controles_formulario-text" id="reservar">
                            Reservar:
                        </p>
                    </label>
                    <input type="radio" name="cupos" id="reservar" value="reservar">
                    <label for="comprar">
                        <p class="controles_formulario-text" id="comprar">
                            Comprar:
                        </p>
                    </label>
                    <input type="radio" name="cupos" id="comprar" value="comprar">
                    <label for="liberar">
                        <p class="controles_formulario-text" id="libre">
                            Libre:
                        </p>
                    </label>
                    <input type="radio" name="cupos" id="liberar" value="liberar" checked>
                    <div class="botones">
                        <input type="submit" value="Enviar" class="boton">
                    </div>
                    <?php
                    echo isset($errores) ? "<p class='errores'>$errores</p>" : "";
                    ?>
                    <?php
                    echo isset($reservado) ? "<p class='errores'>$reservado</p>" : "";
                    echo isset($vendido) ? "<p class='errores'>$vendido</p>" : "";
                    echo isset($disponible) ? "<p class='errores'>$disponible</p>" : "";
                    ?>
                </form>
                <form action="index.php" method="post" class="controles_formulario" id="formulario_borrar">
                    <div class="botones">
                        <input type="submit" class="boton" name="action" value="borrar">
                    </div>
                </form>
            </div>

        </section>
    </main>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const tds = document.querySelectorAll(".puestos");
            tds.forEach((item) => {
                const textContent = item.textContent.trim();
                if (textContent === "L") {
                    item.style.color = "green";
                } else if (textContent === "R") {
                    item.style.color = "blue";
                } else if (textContent === "V") {
                    item.style.color = "red";
                }
            })
        });
    </script>

</body>

</html>