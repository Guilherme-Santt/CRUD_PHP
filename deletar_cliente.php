<?php
// VERIFICAÇÃO DO POST SUBMIT BUTTON "SIM"
    if(isset($_POST['confirmar'])){ 
        include('conexao.php');
        // PUXANDO ID DO GET
        $id = intval($_GET['id']);
        // CÓDIGO DE DELETAR SQL
        $sql_code = "DELETE FROM clientes WHERE id = '$id'";
        // FAZENDO A QUERY DO CÓDIGO DE DELETAR 
        $query_code = $mysqli->query($sql_code) or die($mysqli->error);
        
            if($query_code) {?> 
                <h1>Cliente removido com sucesso!</h1>
                <p><a href="/projetoCadastro/cliente.php">Clique aqui </a>para retornar a lista de clientes</p>
                <?php
                die();
            }
    } 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deletar Cliente</title>
</head>
<body>
    <form action="" method="POST">
        <h1>Tem certeza que deseja deletar este cliente?</h1>
        <a href="cliente.php">Não!</a>
        <Button name="confirmar" type="submit">Sim!</Button>
    </form>
</body>
</html>