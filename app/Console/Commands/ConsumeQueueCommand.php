<!-- <?php

// use PhpAmqpLib\Connection\AMQPStreamConnection;
// use PhpAmqpLib\Message\AMQPMessage;
// use App\Models\Url; // Substitua "YourModel" pelo nome do seu modelo do Eloquent

// // Configurações do RabbitMQ
// $host = 'rabbitmq';
// $port = 5672;
// $user = 'guest';
// $pass = 'guest';
// $queue = 'url_show';

// // Configurações do banco de dados
// $dbHost = env('DB_HOST');
// $dbPort = 3306;
// $dbName = 'app_consumer';
// $dbUser = env('DB_USERNAME');
// $dbPass = env('DB_PASSWORD');

// // Criação da conexão com o RabbitMQ
// $connection = new AMQPStreamConnection($host, $port, $user, $pass);

// // Criação do canal
// $channel = $connection->channel();

// // Declaração da fila
// $channel->queue_declare($queue, false, true, false, false);

// // Função de callback para processar as mensagens recebidas
// $callback = function (AMQPMessage $message) use ($dbHost, $dbPort, $dbName, $dbUser, $dbPass) {
//     $body = $message->getBody();
    
//     // Lógica de processamento da mensagem aqui
//     $hash = str_replace('"', '', $body); // Valor retornado da mensagem
    
//     // Consulta o banco de dados para verificar se o valor já existe
//     $existingModel = Url::where('hash', $hash)->first();
    
//     if ($existingModel) {
//         // O valor já existe no banco de dados, incrementa o valor associado
//         $existingModel->increment('quantity');
//     } else {
//         // O valor ainda não existe no banco de dados, cria um novo registro
//         Url::create([
//             'hash' => str_replace('"', '', $hash),
//             'quantity' => 1 // Valor inicial do valor associado
//         ]);
//     }
    
//     // Confirmação de conclusão do processamento da mensagem
//     $message->ack();
// };

// // Inicia o consumo das mensagens
// $channel->basic_consume($queue, '', false, false, false, false, $callback);

// // Loop de consumo de mensagens
// while ($channel->is_consuming()) {
//     $channel->wait();
// }

// // Fecha a conexão
// $channel->close();
// $connection->close(); 
