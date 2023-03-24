<?php


require $_SERVER['DOCUMENT_ROOT'] . '/database/connectDatabase.php';

session_start();
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>IEFPWay</title>

        <!-- CSS -->
        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:400,100,300,500">

        <link rel="stylesheet" href="/pages/assets/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="/pages/assets/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="/pages/assets/css/form-elements.css">
        <link rel="stylesheet" href="/pages/assets/css/style.css">
        <link rel="stylesheet" href="/pages/assets/css/sendmoney.css">
        <link href='https://fonts.googleapis.com/css?family=Share+Tech+Mono' rel='stylesheet' type='text/css'>
        <link href='https://fonts.googleapis.com/css?family=Signika:400' rel='stylesheet' type='text/css'>

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->

        <!-- Favicon and touch icons -->
        <link rel="shortcut icon" href="/pages/assets/ico/favicon.png">
        <link rel="apple-touch-icon-precomposed" sizes="244x144"
              href="/pages/assets/ico/apple-touch-icon-144-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="214x114"
              href="/pages/assets/ico/apple-touch-icon-114-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="172x72"
              href="/pages/assets/ico/apple-touch-icon-72-precomposed.png">
        <link rel="apple-touch-icon-precomposed" href="/pages/assets/ico/apple-touch-icon-57-precomposed.png">

    </head>

    <body>
    <!-- Top content -->
    <div class="top-content">

        <div class="inner-bg">
            <div class="container">
                <div class="row">
                    <div class="col-sm-8 col-sm-offset-2 text">
                        <h1><strong>Bem Vindo!<br> </strong></h1>
                        <div class="description">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 col-sm-offset-3 form-box">
                        <div class="form-top">
                            <?php include("navbar.php"); ?>



                                <?php

                                //ID do cartao
                                $query_id_cartao = $conn->prepare("SELECT * FROM User WHERE  id = ? ");
                                $query_id_cartao->bind_param("d", $_SESSION["id"]);
                                $query_id_cartao->execute();
                                $r = $query_id_cartao->get_result();
                                $_SESSION["id_cartao"] = mysqli_fetch_assoc($r)["card_id"];

                                //Criar uma query para ir buscar o balance ao cartão do utilizador logado $_SESSION["id"]
                                $sql = $conn->prepare("SELECT * FROM Card WHERE  id = ? ");
                                $sql->bind_param("d", $_SESSION["id"]);
                                $sql->execute();
                                $query= $sql->get_result();


                                //Guardar o mysqli_fetch_assoc na variável seguinte:

                                $result = mysqli_fetch_assoc($query);

                                $balance=$result["balance"];//guardar na variavel balanço atual SEM! o valor inserido


                                if(isset($_POST["form-value"])){

                                $valorInserido = $_POST["form-value"]; //GUARDAR NUM VARIAVEL O VALOR INSERIDO NO FORM
                                $valorTotal = $balance+$valorInserido; // GUARDAR NUMA VARIAVEL O BALANÇO ATUAL MAIS O VALOR INSERIDO NO FORM


                                /* Fazer update no balance do cartão obtido.*/

                                $sql = $conn->prepare("Update card set balance = ? where id = ? ");
                                $sql->bind_param("dd", $valorTotal, $_SESSION["id"]);
                                $sql->execute();

                                if (!mysqli_error($conn)) {?>
                                     <div class="alert alert-success" role="alert">
                                            Success - Current balance: <?php echo $valorTotal;?>
                                     </div>

                                 <?php } else { ?>


                                 <!-- Em caso de falhar mostrar: -->


                                <?php }


}



                                generateCard($result["name"], $result["expirationDate"], $result["card_code"]);
                                ?>





                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>



    <!-- Javascript -->
    <script src="/pages/assets/js/jquery-1.11.1.min.js"></script>
    <script src="/pages/assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="/pages/assets/js/jquery.backstretch.min.js"></script>
    <script src="/pages/assets/js/scripts.js"></script>

    <!--[if lt IE 10]>
    <script src="/pages/assets/js/placeholder.js"></script>
    <![endif]-->

    </body>

</html>


<?php function generateCard($name, $expirationDate, $number)
{ ?>

            </div>
        </div>
    </div>

<?php } ?>