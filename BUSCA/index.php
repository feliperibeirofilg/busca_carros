<?php

$config = require 'db_config.php';
$host = $config['host'];
$user = $config['user'];
$pass = $config['pass'];
$db = $config['db'];

// Agora você pode usar essas variáveis para conectar ao banco de dados
$conn = new mysqli($host, $user, $pass, $db);  

//Assunto da aula 

$sql = "SELECT * FROM veiculo";

$result = $conn->query($sql);

$fabricante_busca = ''; //Variavel para armazenar o valor da busca

//Verificando se o formulario foi enviado
if(isset($_GET['fabricante'] )){
    $fabricante_busca = $_GET['fabricante'];
}

//Consultando os dados, se uma marca foi especificada
if($fabricante_busca != ''){
    //Consulta SQL com WHERE usando prepared statement
    $sql = "SELECT * FROM veiculo WHERE Fabricante LIKE ?";
    $stmt = $conn->prepare($sql);
    $like_marca = "%" . $fabricante_busca . "%";//Adicionando os percentuais para busca parcial
    $stmt->bind_param("s", $like_marca); // 's' indica que é uma string
    $stmt->execute();
    $result = $stmt->get_result();
} else{
    //Consulta SQL sem filtro, retornando todos os veículos
    $sql = "SELECT * FROM veiculo";
    $result = $conn->query($sql);
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
    <!-- Formulario de Busca -->
     <form method="get" action "">
        <input type="text" name="fabricante" value="<?php echo htmlspecialchars($fabricante_busca);?>" placeholder="Digite a marca do carro">
        <button type="submit">Buscar</button>
        </form>

    <table border=1>
        <thead>
            <tr>
                <th>Fabricante</th>
                <th>Modelo</th>
                <th>Veiculo</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if($result->num_rows>0){
                //Loop atraves das linhas e exibe dados da tabela

            while($row = $result->fetch_assoc()){
                echo "<tr>";
                echo "<td>" . $row['Fabricante'] . "</td>";
                echo "<td>" . $row['modelo'] . "</td>";
                echo "<td>" . $row['veiculo'] . "</td>";
                echo "</tr>";
            }
            }else {
                echo "<tr><td colspan='5'> nenhum veiculo encontrado.</td> </tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
<?php
$conn->close();
?>
