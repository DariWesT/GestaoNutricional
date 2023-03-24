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
        <link rel="apple-touch-icon-precomposed" sizes="144x144"
              href="/pages/assets/ico/apple-touch-icon-144-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="114x114"
              href="/pages/assets/ico/apple-touch-icon-114-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="72x72"
              href="/pages/assets/ico/apple-touch-icon-72-precomposed.png">
        <link rel="apple-touch-icon-precomposed" href="/pages/assets/ico/apple-touch-icon-57-precomposed.png">

    </head>

    <body>
    <?php include("navbar.php"); ?>

    <!-- Top content -->
    <div class="top-content">

        <div class="inner-bg">
            <div class="container">
                <div class="row">
                    <div class="col-sm-8 col-sm-offset-2 text">
                        <h1><strong>Aluno</strong> Cadastro</h1>
                        <div class="description">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 col-sm-offset-3 form-box">
                        <div class="form-top">
                            <div class="form-top-left">
                                <p>Introduza </p>
                            </div>

                        </div>
                        <div class="form-bottom">



                            <?php

                            //Modificar as queries para que funcionem nas vossas bases de dados. Testar e ver se contêm algum erro.


                            if (isset($_POST["form-contact"]) && isset($_POST["form-value"])) {

                                //VAI VERIFICAR SE O CONTACTO EXISTE
                                $sql = $conn->prepare("SELECT * FROM User WHERE contact = ?;");
                                $sql->bind_param("s", $_POST["form-contact"]);
                                $sql->execute();
                                $result = $sql->get_result();
                                $res = mysqli_fetch_assoc($result);
                                if (isset($res["id"])) {

                                    $sql = $conn->prepare("SELECT * FROM Contact WHERE id_user = ? AND id_friend=?;");
                                    $sql->bind_param("dd", $_SESSION["id"], $res["id"]);
                                    $sql->execute();
                                    $result = $sql->get_result();
                                    $res = mysqli_fetch_assoc($result);


                                    if (isset($res["id"])) {
                                        $sql2 = $conn->prepare("SELECT * FROM Card WHERE id = (SELECT card_id FROM User WHERE id=?);");
                                        $sql2->bind_param("d", $_SESSION["id"]);
                                        $sql2->execute();
                                        $result2 = $sql2->get_result();
                                        $result2 = mysqli_fetch_assoc($result2);

                                        if (floatval($result2["balance"]) >= floatval($_POST["form-value"])) {
                                            $balance = floatval($result2["balance"]) - floatval($_POST["form-value"]);
                                            $sql = $conn->prepare("UPDATE Card SET balance=?  WHERE id = (SELECT card_id FROM User WHERE id=?);");
                                            $sql->bind_param("dd", $balance, $_SESSION["id"]);
                                            $sql->execute();

                                            $sql2 = $conn->prepare("SELECT * FROM Card WHERE id = (SELECT card_id FROM User WHERE id=?);");
                                            $sql2->bind_param("d", $res["id_friend"]);
                                            $sql2->execute();
                                            $result2 = $sql2->get_result();
                                            $result2 = mysqli_fetch_assoc($result2);
                                            $balance = floatval($result2["balance"]) + floatval($_POST["form-value"]);
                                            $sql = $conn->prepare("UPDATE Card SET balance=?  WHERE id = (SELECT card_id FROM User WHERE id=?);");
                                            $sql->bind_param("dd", $balance, $res["id_friend"]);
                                            $sql->execute();

                                            ?>
                                            <div class="alert alert-success" role="alert">
                                                Success money sent
                                            </div>
                                            <?php
                                        } else {
                                            ?>
                                            <div class="alert alert-danger" role="alert">
                                                Insufficient funds
                                            </div>
                                            <?php
                                        }
                                    }else{
                                        ?>
                                        <div class="alert alert-danger" role="alert">
                                           Not friends
                                        </div>
                                        <?php
                                    }


                                } else {
                                    ?>
                                    <div class="alert alert-danger" role="alert">
                                        Error sending money user is not friend
                                    </div>
                                    <?php
                                }


                            }
                            ?>

                            <form role="form" method="post" class="login-form">
                                <?php
                                $sql = $conn->prepare("SELECT * FROM Card WHERE id = (SELECT card_id FROM User WHERE id=?);");
                                $sql->bind_param("d", $_SESSION["id"]);
                                $sql->execute();
                                $result = $sql->get_result();
                                $result = mysqli_fetch_assoc($result);
                                generateCard($result["name"], $result["expirationDate"], $result["card_code"]);
                                ?>
                                <div class="form-group">
                                    <label class="sr-only"> <strong>Available:</strong>1203€ </label>
                                </div>

                                <div id="area">
                                    <form name="new_aluno" id="formulario" autocomplete="off" action="" method="POST">
                                        <fieldset>
                                            <legend>Aluno</legend>
                                            <label>Matrícula:</label><input class="matricula" type="text"><br>
                                            <label>NIF:</label><input class="NIF" type="text"><br>
                                            <label>Nome:</label><input class="nome" type="text"><br>
                                            <input type="radio" id="sexo" name="sexo" value="sexo">
                                            <label for="sexom">masculino</label>
                                            <input type="radio" id="sexo" name="sexo" value="sexo">
                                            <label for="sexof">feminino</label><br>
                                            <label for="datanasc">data de nascimento</label>
                                            <input id="date" type="date">
                                            <label>Morada:</label><input class="morada" type="text"><br>
                                            <label>Telefone Principal :</label><input class="telp" type="text"><br>
                                            <label>Telefone Secundário:</label><input class="tels" type="text"><br>

                                        </fieldset>
                                        <fieldset>
                                            <legend>Encarregado de Educação</legend>
                                            <input type="radio" id="enc" name="enc" value="enc">
                                            <label for="pai">Pai</label>
                                            <input type="radio" id="enc" name="enc" value="enc">
                                            <label for="mae">Mãe</label>
                                            <input type="radio" id="enc" name="enc" value="enc">
                                            <label for="outro">Outro</label><br>
                                            <label>Nome:</label><input class="campo_nome" type="text">
                                            <label>Relação:</label><input class="campo_nome" type="text"><br>
                                            <label>NIF:</label><input class="campo_nome" type="text"><br>
                                            <label>Email:</label><input class="campo_email" type="password"><br>
                                            <label>Telefone:</label><input class="campo_nome" type="text"><br>

                                        </fieldset>
                                        <fieldset>
                                            <legend>Informações alimentares</legend>
                                            <input type="checkbox" id="inf1" name="inf1" value="intolerância ao glúten">
                                            <label for="inf1">intolerância ao glúten</label><br>
                                            <input type="checkbox" id="inf2" name="inf2" value="intolerância à lactose">
                                            <label for="inf2">intolerância à lactose</label><br>
                                            <input type="checkbox" id="inf3" name="inf3" value="doença celíaca">
                                            <label for="inf3">doença celíaca</label><br>
                                            <input type="checkbox" id="inf4" name="inf4" value="alergia ao ovo">
                                            <label for="inf4">alergia ao ovo</label><br>
                                            <input type="checkbox" id="inf5" name="inf5" value="alergia ao amendoim e a frutos de casca rija">
                                            <label for="inf5">alergia ao amendoim e a frutos de casca rija</label><br>
                                            <input type="checkbox" id="inf6" name="inf6" value="alergia a marisco (crustáceos) e a moluscos">
                                            <label for="inf6">alergia a marisco (crustáceos) e a moluscos</label><br>
                                            <input type="checkbox" id="inf7" name="inf7" value="alergia a peixe">
                                            <label for="inf7">alergia a peixe</label><br>
                                            <input type="checkbox" id="inf8" name="inf8" value="alimentação vegetariana">
                                            <label for="inf8">alimentação vegetariana</label><br>
                                            <input type="checkbox" id="inf9" name="inf9" value="alimentação kosher">
                                            <label for="inf9">alimentação kosher</label><br>
                                            <input type="checkbox" id="inf10" name="inf10" value="diabético">
                                            <label for="inf10">diabético</label><br>
                                            <input type="checkbox" id="inf11" name="inf11" value="outros">
                                            <label for="inf11">outros</label><br>
                                            <label>Quais:</label><br><textarea class="msg" cols="100" rows="4"></textarea><br>
                                            <input class="btn_submit" type="submit" value="Enviar" name="enviar">
                                        </fieldset>
                                    </form>
                                </div>
    </body>
</html>

                                <button type="submit" class="btn">Gravar</button>
                            </form>
                            <a class="btn" href="/index.php"
                            >Voltar</a>
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

<?php } ?>