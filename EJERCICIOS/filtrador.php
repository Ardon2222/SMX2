<!DOCTYPE html>
<html>
<head>
    <!-- Nombre -->
    <title>Buscador de Rentacars en Mallorca</title>
</head>
<body>
    <hr>
    <h1>Buscador de Rentacars en Mallorca</h1>

    <!-- Filtros -->
    <form method="GET" action="filtrador.php">
        <label for="municipio"><b>Municipio:</b></label>
        <select name="municipio" id="municipio">
            <option value="">No filtrar por municipio</option> <!-- Opción para no aplicar filtro -->
            <?php
            $filtrarMunicipio = $_GET['filtrar_municipio'] ?? '';

            if ($filtrarMunicipio === '') {
                $url = 'https://catalegdades.caib.cat/resource/rjfm-vxun.xml';
                $xmlContent = file_get_contents($url);

                if ($xmlContent !== false) {
                    $xml = simplexml_load_string($xmlContent);
                    $municipios = [];

                    foreach ($xml->rows->row as $row) {
                        $municipio = (string)$row->municipi;
                        if (!in_array($municipio, $municipios)) {
                            $municipios[] = $municipio;
                            echo "<option value=\"$municipio\">$municipio</option>";
                        }
                    }       
                }
            }
            ?>
        </select>

        <br><br>

        <label for="codigo_postal"><b>Código Postal:</b><hr></label>
        
        <?php
        $codigos_postales = [];

        if ($xmlContent !== false) {
            foreach ($xml->rows->row as $row) {
                $direccion = (string) $row->adre_a_de_l_establiment;
                preg_match('/\b\d{5}\b/', $direccion, $matches);

                if (!empty($matches)) {
                    $codigo_postal = $matches[0];
                    if (!in_array($codigo_postal, $codigos_postales)) {
                        $codigos_postales[] = $codigo_postal;
                    }
                }
            }

            sort($codigos_postales);

            $contador = 0;
            foreach ($codigos_postales as $codigo_postal) {
                echo "<input type=\"radio\" name=\"codigo_postal\" value=\"$codigo_postal\"> $codigo_postal ";
                $contador++;

                if ($contador % 8 == 0) {
                    echo "<br>";
                }
            }
        }
        ?>
        <hr>
        <br>

        <!-- Busqueda por nombre -->
        <label for="nombre"><b>Nombre:</b></label>
        <input type="text" name="nombre" id="nombre">

        <br><br>

        <!-- Orden -->
        <label for="orden"><b>Orden:</b></label>
        <select name="orden" id="orden">
            <option value="orden1">Alfabetico</option>
            <option value="orden2">Cantidad de coches, mayor a menor</option>
            <option value="orden3">Cantidad de coches, menor a mayor</option>
        </select>

        <br><br>

        <input type="submit" value="Buscar">
    </form>

    <?php
    // ... Código de obtención del XML y manipulación de datos ...

    if ($xmlContent === false) {
        //<!-- Verificacion del XML -->
        echo '<hr><h3>Hubo un problema al descargar el archivo.</h3><hr>';
    } else {
        $xml = simplexml_load_string($xmlContent);

        if (isset($xml->rows->row)) {
            $rentacars = [];
            $total_veiculos = 0;
            $municipioSeleccionado = $_GET['municipio'] ?? '';
            $codigoPostalSeleccionado = $_GET['codigo_postal'] ?? '';
            $orden = $_GET['orden'] ?? '';
            $nombreFiltro = isset($_GET['nombre']) ? $_GET['nombre'] : '';

            foreach ($xml->rows->row as $row) {
                $municipio = (string)$row->municipi;

                if (($municipioSeleccionado === '' || $municipio === $municipioSeleccionado)
                    && ($codigoPostalSeleccionado === '' || strpos((string)$row->adre_a_de_l_establiment, $codigoPostalSeleccionado) !== false)) {

                    $denominacion = (string)$row->denominaci_comercial;
                    $direccion = (string)$row->adre_a_de_l_establiment;
                    $numVehiculos = (int)$row->nombre_de_vehicles;
                    $explotador = (string)$row->nom_explotador_s;

                    // Filtrar por nombre
                    $nombreRentacar = (string)$row->denominaci_comercial;
                    if ($nombreFiltro === '' || stripos($nombreRentacar, $nombreFiltro) !== false) {
                        $rentacars[] = [
                            'denominacion' => $denominacion,
                            'direccion' => $direccion,
                            'numVehiculos' => $numVehiculos,
                            'explotador' => $explotador,
                        ];

                        // Suma al total de vehículos
                        $total_veiculos += $numVehiculos;
                    }
                }
            }

            if ($municipioSeleccionado === '') {
                $municipioSeleccionado = "NO FILTRADO";
            }

            if ($codigoPostalSeleccionado === '') {
                $codigoPostalSeleccionado = "NO FILTRADO";
            }

            if ($nombreFiltro === '') {
                $nombreFiltro = "NO FILTRADO";
            }

            if ($orden == 'orden1') {
                $ordenres = "Alfabetico";
            } elseif ($orden == 'orden2') {
                $ordenres = "Mayor a menor";
                usort($rentacars, function ($a, $b) {
                    return $b['numVehiculos'] - $a['numVehiculos'];
                });
            } else {
                $ordenres = "Menor a mayor";
                usort($rentacars, function ($a, $b) {
                    return $a['numVehiculos'] - $b['numVehiculos'];
                });
            }

            // Mostrar el total de vehículos y la tabla de resultados
            echo "<hr><h3>Filtrando por: </h3>";
            echo "<p>Municipio: $municipioSeleccionado </p>";
            echo "<p>Codigo Postal: $codigoPostalSeleccionado </p>";
            echo "<p>Nombre: $nombreFiltro </p>"; 
            echo "Orden: $ordenres "; 
            echo "<hr><h3>Total de Vehículos: $total_veiculos </h3><hr>";

            //<!-- Mostrar los resultados en una tabla -->
            echo "<h2>Resultados:</h2><hr>";

            echo "<table border='1'>";
            echo "<tr><th>Denominación</th><th>Número de vehículos</th><th>Dirección</th><th>Explotador</th></tr>";

            foreach ($rentacars as $rentacar) {
                echo "<tr>";
                echo "<td>{$rentacar['denominacion']}</td>";
                echo "<td>{$rentacar['numVehiculos']}</td>";
                echo "<td>{$rentacar['direccion']}</td>";
                echo "<td>{$rentacar['explotador']}</td>";
                echo "</tr>";
            }

            echo "</table>";
        }
    }
    ?>
</body>
</html>
