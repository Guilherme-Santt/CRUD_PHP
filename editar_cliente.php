<?php 
include('conexao.php');
$id = intval($_GET['id']);

function limpar_texto($str){ 
  return preg_replace("/[^0-9]/", "", $str); 
}

$erro = false;
if(count($_POST) > 0){

    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $nascimento = $_POST['nascimento'];

// verificação nome
    if(empty($nome) || Strlen($nome) < 3 || Strlen($nome) > 100){
        $erro = "Por favor, Prencha o campo nome corretamente. Capacidade mínima 3 dígitos! ";
    }

// verificação email
    if(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)){
        $erro = "Por favor, Prencha o campo e-mail corretamente.";
    }   

// verificação nascimento
    if(!empty($nascimento)){
         $pedacos = explode('/', $nascimento);

         if(count($pedacos) == 3){
         $nascimento = implode ('-', array_reverse($pedacos)); 
         }
         else{
             $erro = "A data de nascimento deve ser preenchido no padrão dia/mes/ano";
        }
    }    

// verificação telefone
    if(!empty($telefone)){
        $telefone = limpar_texto($telefone);
        if(strlen($telefone) != 11){
            $erro = "O telefone deve ser preenchido no padrão (11) 98888-8888";
        }
    }

    if($erro){
        // echo "<p><b>$erro</b></p>";
    }
    else{
        $sqlcode = "UPDATE clientes 
        SET nome = $nome,
        email = $email,
        telefone = $telefone,
        nascimento = $nascimento,
        WHERE id = $id";

        $deu_certo = $mysqli->query($sqlcode) or die($mysqli->error);

        if($deu_certo){
            echo "<p><b>Cliente atualizado com sucesso!</b></p>";
            unset($_POST);
        }
        
    }

}

$sql_cliente = "SELECT * FROM clientes WHERE id = '$id'";
$query_cliente = $mysqli->query($sql_cliente) or die($mysqli->error);
$cliente = $query_cliente->fetch_assoc();

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
            <input 
                value= "<?php  echo $cliente['nome']; ?>" type="text" name="nome">
        </p>
        <p>
            <label>E-mail:</label>
            <input
                value ="<?php echo $cliente['email']; ?>" type="email" name="email">
        </p>
        <p>
            <label>Telefone:</label>
            <input 
                value =" <?php if(!empty($cliente['telefone'])){ echo formatar_telefone($cliente['telefone']);} ?>"
                type="text" name="telefone">
        </p>
        <p>
            <label>Data de nascimento:</label>
            <input 
                value ="<?php if(!empty($cliente['telefone'])){ echo formatar_data($cliente['nascimento']);} ?>"
                type="text" name="nascimento">
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

