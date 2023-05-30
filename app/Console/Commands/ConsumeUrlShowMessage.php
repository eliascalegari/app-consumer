<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\RabbitMQService;
use App\Models\Url;

class ConsumeUrlShowMessage extends Command
{
    private $rabbitMQService;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    protected $signature = 'app:consume-url-show-message';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Consumer URL Show Messagem from RabbitMQ';

    /**
     * Execute the console command.
     */
    public function handle(RabbitMQService $rabbitMQService)
    {
        $this->rabbitMQService = $rabbitMQService;
        $this->rabbitMQService->consumerUrlShowMessage();
        // $queueName = 'url_show'; // Replace 'your_queue_name' with the actual name of your queue

        // $messages = $this->rabbitMQService->consumerMessage($queueName);

        // // Process the received messages as needed
        // foreach ($messages as $message) {
        //     $hash = str_replace('"', '', $message); // Valor retornado da mensagem
        //     $existingModel = Url::where('hash', $hash)->first();
        //     if ($existingModel) {
        //         // O valor já existe no banco de dados, incrementa o valor associado
        //         $existingModel->increment('quantity');
        //     } else {
        //         // O valor ainda não existe no banco de dados, cria um novo registro
        //         Url::create([
        //             'hash' => $hash,
        //             'quantity' => 1 // Valor inicial do valor associado
        //         ]);

        //     }
        // }

        // Optionally, you can return the messages or perform other actions
    }
}
