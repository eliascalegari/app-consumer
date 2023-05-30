<?php

namespace App\Services;

use App\Models\Url;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQService
{
    private $connection;
    private $receivedMessages = [];

    public function __construct()
    {
        $this->connection = new AMQPStreamConnection('rabbitmq', 5672, 'guest', 'guest');
    }

    public function publishMessage($queueName, $messageBody)
    {
        $channel = $this->connection->channel();
        $channel->exchange_declare('url_events', 'direct');
        $channel->queue_declare($queueName, false, true, false, false);
        $channel->queue_bind($queueName, 'url_events', $queueName);

        $msg = new AMQPMessage($messageBody);
        $channel->basic_publish($msg, '', $queueName);

        $channel->close();
    }

    public function consumerMessage($queueName)
    {
        $channel = $this->connection->channel();
        $channel->queue_declare($queueName, false, true, false, false);

        $callback = function (AMQPMessage $message) {
            $body = $message->getBody();
            $body = str_replace('"', '', $body);
            
            $this->receivedMessages[] = $body;


            $message->ack();
        };

        $channel->basic_consume($queueName, '', false, false, false, false, $callback);

        while ($channel->is_consuming()) {
            $channel->wait();
        }

        $channel->close();
        return $this->receivedMessages;

    }


    public function consumerUrlShowMessage()
    {
        $queueName = 'url_show';
        $channel = $this->connection->channel();
        $channel->queue_declare($queueName, false, true, false, false);

        $callback = function (AMQPMessage $message) {
            $body = $message->getBody();
            $hash = str_replace('"', '', $body);
            $existingModel = Url::where('hash', $hash)->first();

            if ($existingModel) {
                // O valor já existe no banco de dados, incrementa o valor associado
                $existingModel->increment('quantity');
            } else {
                // O valor ainda não existe no banco de dados, cria um novo registro
                Url::create([
                    'hash' => str_replace('"', '', $hash),
                    'quantity' => 1 // Valor inicial do valor associado
                ]);
            }
            
            // Exemplo: Imprimir o corpo da mensagem
            // echo "Mensagem recebida: $body\n";

            // Confirmação de conclusão do processamento da mensagem
            $message->ack();
        };

        $channel->basic_consume($queueName, '', false, false, false, false, $callback);

        while ($channel->is_consuming()) {
            $channel->wait();
        }

        $channel->close();
    }


    public function __destruct()
    {
        $this->connection->close();
    }
}
