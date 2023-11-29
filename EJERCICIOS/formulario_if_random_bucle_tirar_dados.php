
<html>
    <h1>Tirar los dados</h1>
    <p>¿Cuantas veces quiere tirar los dados?</p>
    <!-- Formulario -->
    <form method="GET" action="ej.php">

        <label for="intentos"><b></b></label>
        <input type="number" name="intentos" id="intentos" min="1" max="100000" required>

        <input type="submit" value="Tirar">
    </form>
</form>
</html>


<?php
    //<!-- Inizializacion -->
    $intentos = $_GET['intentos'] ?? '';
    $contador = 0;
    $a=0;
    $b=0;
    $c=0;
    $d=0;
    $e=0;
    $f=0;

    //<!-- Randomizacion y suma de los numeros -->

    do {
        $num=rand(1,6);
        if ($num==1) {
            $a=$a+1;
        } elseif ($num==2) {
            $b=$b+1;
        } elseif ($num==3) {
            $c=$c+1;
        } elseif ($num==4) {
            $d=$d+1;
        } elseif ($num==5) {
            $e=$e+1;
        } elseif ($num==6) {
            $f=$f+1;
        } else {
            echo "Algo salio mal";
        }

        $contador++;
        
    } while ($contador<$intentos);

    //<!-- Porcentualizacion -->

    $ra= ($a/$intentos)*100;
    $rb= ($b/$intentos)*100;
    $rc= ($c/$intentos)*100;
    $rd= ($d/$intentos)*100;
    $re= ($e/$intentos)*100;
    $rf= ($f/$intentos)*100;
    //<!-- Resultado -->
    echo "Los resultados son:<br>1:$ra%<br>2:$rb%<br>3:$rc%<br>4:$rd%<br>5:$re%<br>6:$rf%";
?>
