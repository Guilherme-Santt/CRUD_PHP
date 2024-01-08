<?php 
    function limpar_texto($str){ 
      return preg_replace("/[^0-9]/", "", $str); 
    }

    $erro = false;
    if(count($_POST) > 0){
        include('conexao.php');
        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $telefone = $_POST['telefone'];
        $nascimento = $_POST['nascimento'];

    // VERIFICAÇÃO SE ESTÁ VAZIO OU 3 Á 100 DÍGITOS
        if(empty($nome) || Strlen($nome) < 3 || Strlen($nome) > 100){
            $erro = "Por favor, Prencha o campo nome corretamente. Capacidade mínima 3 dígitos! ";
        }

    // VERIFICAÇÃO SE ESTÁ VAZIO OU SE NÃO ESTÁ NO PADRÃO DE EMAIL
        if(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)){
            $erro = "Por favor, Prencha o campo e-mail corretamente.";
        }   

    // SE NÃO ESTIVER VAZIO, FAZER CONVERSÃO PARA PADRÃO AMERICANO
        if(!empty($nascimento)){
            $pedacos = explode('/', $nascimento);

            if(count($pedacos) == 3){
                $nascimento = implode ('-', array_reverse($pedacos)); 
            }
            else{
                $erro = "A data de nascimento deve ser preenchido no padrão dia/mes/ano";
            }
        }    

    // CASO NÃO TIVER VÁZIO, FAZER CONVERSÃO PARA PADRÃO BR
        if(!empty($telefone)){
            $telefone = limpar_texto($telefone);
            if(strlen($telefone) != 11){
                $erro = "O telefone deve ser preenchido no padrão (11) 98888-8888";
            }
        }

        if($erro){

        }
        else{
            $verify = "SELECT email FROM clientes WHERE email = '$email' ";
            $query_verify = $mysqli->query($verify);
            $query_verify = $query_verify->num_rows;

            if($query_verify){
                echo  "<script>alert('Email ja cadastrado!');</script>";
            }else{
                $sqlcode = "INSERT INTO clientes (nome, email, telefone, nascimento, data)
                values ('$nome', '$email', '$telefone', '$nascimento', NOW())";
                $deu_certo = $mysqli->query($sqlcode) or die($mysqli->error);
                    if($deu_certo){
                        echo  "<script>alert('Cadastrado com Sucesso!');</script>";
                        unset($_POST);
                    }
            }
        }    
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <a href="http://127.0.0.1/projetoCadastro/cliente.php">Voltar para a lista</a>
    <form action="" method="POST">
        <p>
            <label>Nome:</label>
            <input required value= "<?php if(isset($_POST['nome'])) echo $_POST['nome']; ?>" type="text" name="nome">
        </p>
        <p>
            <label>E-mail:</label>
            <input value ="<?php if(isset($_POST['email'])) echo $_POST['email']; ?>" type="email" name="email">
        </p>
        <p>
            <label>Telefone:</label>
            <input value ="<?php if(isset($_POST['telefone'])) echo $_POST['telefone']; ?>" placeholder="(11) 98888-8888" type="text" name="telefone">
        </p>
        <p>
            <label>Data de nascimento:</label>
            <input value ="<?php if(isset($_POST['nascimento'])) echo $_POST['nascimento']; ?>" placeholder="dia/mês/ano" type="text" name="nascimento">
        </p>
        <p>
            <button type="submit">Enviar</button>
        </p>
        <?php 
            if(isset($erro))
            echo $erro;
        ?>
    </form>
</body>
</html>

