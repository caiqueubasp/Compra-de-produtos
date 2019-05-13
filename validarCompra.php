<?php 
//----------variáveis criadas para armazenar o conteúdo dos input's do formulário
$nome = $_POST["nomeCliente"];
$cpf = $_POST["cpf"];
$validade = $_POST["validade"];
$nCartao = $_POST["nCartao"];
$cvv = $_POST["cvv"];
$nomeCurso = $_POST["nomeCurso"];
$preco = $_POST["preco"];
//----------array onde vou armazenar os erros de validação digitados pelo Usuário 
$erros = [];

//----------Validação Campos do Formulário
function validarNome($nome){
    return strlen($nome)>0;
}

function validarCpf($cpf){
    return strlen($cpf) == 11;
}

function validarNCArtao($nCartao){
    $validacao = strlen($nCartao) == 16;
    $cartaoCP = password_hash($nCartao, PASSWORD_BCRYPT);
    $_POST['nCartao'] = $cartaoCP;
    return $validacao;
}

function validarData($data){
    $dataAtual = date("Y-m");
    return $data >= $dataAtual;
}

function salvarCompras($novaCompra){
    // criando lista de compras
    if(!file_exists('compras.json')){
        $compras["listaCompras"] = [$novaCompra];
        $jsoncompras = json_encode($compras);
        file_put_contents('compras.json',json_encode($compras));
        echo "<script>alert('Compra salva com sucesso!')</script>";
    }else {
        // 
        $jsoncompras = file_get_contents('compras.json');
        $listaCompras = json_decode($jsoncompras, TRUE);
        $listaCompras["listaCompras"][] = $novaCompra;
        
        $jsonListaCompras = json_encode($listaCompras);
        file_put_contents('compras.json', $jsonListaCompras);
    }
}

function validarCvv($cvv){
    return strlen($cvv) == 3;
}

function validarCompra($nome, $cpf, $nCartao, $data, $cvv){
    global $erros;
    if(!validarNome($nome)){
        array_push($erros, "Preencha o nome corretamente");
    }
    if (!validarCpf($cpf)){
        array_push($erros, "Preencha o cpf com 11 digitos");
    }
    if(!validarNCartao($nCartao)){
        array_push($erros, "Preencha o numero do cartão com 16 digitos");
    }
    if(!validarData($data)){
        array_push($erros, "O catao não pode estar vencido");
    }
    if(!validarCvv($cvv)){
        array_push($erros, "O cvv precisa ter 3 numeros");
    }
    if(count($erros) ==0){
        salvarCompras($_POST);
    }
}
//----------rodando a função
validarCompra($nome, $cpf, $nCartao, $validade, $cvv);

//**caso seja dificil visualizar o que está acontecendo com o array ERROS -> dar var_dump 
//var_dump($erros);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
        integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css"
        integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <div class="container">
        <div class="col-md-6 col-md-offset-3">
            <!-- Este bloco é o que deve aparecer quando forem passados dados incorretos/incompletos -->


            <?php if(count($erros) > 0): ?>
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <span>Preencha seus dados corretamente!</span>
                </div>
                <div class="panel-body">
                    <ul class="list-group">
                        <?php foreach($erros as $key=>$value){
                                ?>
                        <li class="list-group-item">
                            <!-- aqui deve mostrar ao usuário o que deve ser arrumado no form -->
                            <?=$value;?>
                        </li>
                        <?php } ?>
                    </ul>
                    <div class="center">
                        <a href="index.php">Voltar para home</a>
                    </div>
                </div>
            </div>
                            <?php else:?>
            <!-- Aqui termina o bloco de erros -->
            <!-- Este bloco deve aparecer quando todos os dados estiverem corretos -->
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <span>Compra realizada com sucesso!</span>
                </div>
                <div class="panel-body">
                    <ul class="list-group">
                        <li class="list-group-item"><strong>Nome Curso: </strong> NOME CURSO </li>
                        <li class="list-group-item"><strong>Preço: R$ </strong> PREÇO CURSO </li>
                        <li class="list-group-item"><strong>Nome Completo: </strong> NOME COMPLETO</li>
                    </ul>
                    <div class="center">
                        <a href="index.php">Voltar para home</a>
                    </div>
                </div>
            </div>
            <!-- Aqui termina o bloco com os dados corretos -->
                            <?php endif;?>
        </div>
    </div>
</body>

</html>