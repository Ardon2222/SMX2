<?php 
$num1 = $_GET['num1'];
$num2 = $_GET['num2'];
$op = $_GET['op'];

if (!is_numeric($num1) || !is_numeric($num2)) {
      echo "Los numeros... no son NUMEROS.. pon NUMEROS en los numeros... porfavor<br> <br> Y A CONTINUACION LE MOSTRAMOS... Si un error, asi es, es culpa tuya (Por no poner NUMEROS en los numeros ._. ) <br> <br>";
  } else {
  }

if ($op == "suma") {
    $res=$num1+$num2; echo "$res"; 
} elseif ($op == "resta") {
    $res=$num1-$num2;
    echo "$res";
} elseif ($op == "multiplicacion") {
    $res=$num1*$num2;
    echo "$res";
} elseif ($op == "division") {
    $res=$num1/$num2;
    echo "$res";
} else {
    echo "El valor de la operacion no es ninguno de los siguientes:<br>Suma; Resra; Multiplicacion; Division";
}
?>
