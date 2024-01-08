<?php 

$id = intval($_GET['id']);
include('conexao.php');

    // FUNÇÃO PARA FORMATAR DATA PADRÃO AMERICANO PARA O BRASILEIRO 
    function formatar_data($data){
        return implode('/', array_reverse(explode('-', $data)));
    };

    // FUNÇÃO FORMATAR TELEFONE 1199999999 -> (11) 99999-9999
    function formatar_telefone($telefone){
        $ddd = substr ($telefone, 0, 2);
        $parte1 = substr ($telefone, 2, 5);
        $parte2 = substr ($telefone, 7);
            return "($ddd) $parte1-$parte2";
    }
// VARIAVEL PADRÃO CASO HAVER ERRO
    $erro = false;

// VERIFICAÇÃO SE EXISTE INFORMAÇÃO NOS CAMPOS POST
    if(count($_POST) > 0){
        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $telefone = $_POST['telefone'];
        $nascimento = $_POST['nascimento'];
    
// VERIFICANDO SE CAMPO NOME ESTÁ VAZIO OU TEM MENOS QUE 3 LETRAS E MAIS DE 100, MENSAGEM ERRO 
    if(empty($nome) || Strlen($nome) < 3 || Strlen($nome) > 100){
        $erro = "Por favor, Prencha o campo nome corretamente. Capacidade mínima 3 dígitos! ";
    }

// VERIFICANDO SE O CAMPO EMAIL ESTÁ VAZIO OU NÃO ESTÁ NO PADRÃO DE VALIDAÇÃO
    if(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)){
        $erro = "Por favor, Prencha o campo e-mail corretamente.";
    }   

// VERIFICANDO QUE SE CASO O CAMPO NASCIMENTO NÃO ESTÁ VAZIO, VAI PEGAR O CAMPO DIGITADO(PADRÃO BR) & CONVERTER PARA PADRÃO AMERICANO
    if(!empty($nascimento)){
         $pedacos = explode('/', $nascimento);

         if(count($pedacos) == 3){
         $nascimento = implode ('-', array_reverse($pedacos)); 
         }
         else{
             $erro = "A data de nascimento deve ser preenchido no padrão dia/mes/ano";
        }
    }    

    // FUNÇÃO LIMPAR CARACTERES NA INSERÇÃO DO CAMPO NUMERO, (INPUT NUMERO)
    function limpar_texto($str){ 
        return preg_replace("/[^0-9]/", "", $str); 
        }

    // VERIFICAÇÃO: CASO CAMPO NUMERO TIVER PREENCHIDO, ESTEJA NO PADRÃO CORRETO PARA INSERÇÃO
    if(!empty($telefone)){
        $telefone = limpar_texto($telefone);
        if(strlen($telefone) != 11){
            $erro = "O telefone deve ser preenchido no padrão (11) 98888-8888";
        }
    }

    //VERIFICAÇÃO SE EXISTE ERRO
    if($erro){
        // echo "<p><b>$erro</b></p>";
    }

    // CASO NÃO HOUVER ERRO, FAZER EDIÇÃO NO BANCO DE DADOS
    else{
        $verify = "SELECT email FROM clientes WHERE email = '$email' ";
        $query_verify = $mysqli->query($verify);
        $query_verify = $query_verify->num_rows;

        if($query_verify){
            echo  "<script>alert('Email ja cadastrado!');</script>";
        }else{
            $sql_code = "UPDATE clientes
            SET nome   = '$nome', 
            email      = '$email',
            telefone   = '$telefone',
            nascimento = '$nascimento' WHERE id   = '$id'";
            $deu_certo = $mysqli->query($sql_code) or die($mysqli->error);
            }
                if($deu_certo){
                    echo  "<script>alert('Atualizado com Sucesso!');</script>";
                    unset($_POST);
                };     
    }
}

// PUXANDO CAMPO ID PELO GET ENVIADO NA TELA CLIENTE, IDENTIFICANDO ESSE ID NO BANCO DE DADOS PELO F.ASSOC PARA A $CLIENTE 
    include('conexao.php');
    $sql_cliente = "SELECT * FROM clientes WHERE id = '$id'";
    $query_cliente = $mysqli->query($sql_cliente) or die ($mysqli->error);
    $cliente = $query_cliente->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
    /* body{
        background-color: black;
        color: white;
        border-color: white;
    } */
</style>
<body>
    <a href="http://127.0.0.1/projetoCadastro/cliente.php">Voltar para a lista</a>
    <form action="" method="POST">
        <p>
            <label>Nome:</label>
            <input value= "<?php echo $cliente['nome']; ?>"
            type="text" name="nome">
        </p>
        <p>
            <label>E-mail:</label>
            <input value ="<?php echo $cliente['email']; ?>"
             type="email" name="email">
        </p>
        <p>
            <label>Telefone:</label>
            <input value ="<?php if(!empty($cliente['telefone'])){ echo formatar_telefone($cliente['telefone']);} ?>" placeholder="(11) 98888-8888" type="text" name="telefone">
        </p>
        <p>
            <label>Data de nascimento:</label>
            <input value ="<?php if(!empty($cliente['nascimento'])){ echo formatar_data($cliente['nascimento']);} ?>" placeholder="dia/mês/ano" type="text" name="nascimento">
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

