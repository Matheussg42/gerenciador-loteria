<!DOCTYPE html>
<html>

<head>
    <title>Loteria</title>
    <style>
        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        td,
        th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #dddddd;
        }
    </style>
</head>

<body>

    <?php

    use Loteria\Loteria;

    require('Loteria.class.php');

    $loteria = new Loteria(2, 5);
    if ($loteria->_isValid) {
        $loteria->gerandoJogos();
        $loteria->sorteio();
        $tabelaJogos = $loteria->confereSorteio();

        echo $tabelaJogos;
    }

    ?>

</body>

</html>