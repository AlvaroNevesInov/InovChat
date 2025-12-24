<?php

namespace App\Events;

use App\Models\Mensagem;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MensagemEnviada implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $mensagem;

    /**
     * Create a new event instance.
     */
    public function __construct(Mensagem $mensagem)
    {
        $this->mensagem = $mensagem->load('user');
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('sala.' . $this->mensagem->sala_id),
        ];
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->mensagem->id,
            'sala_id' => $this->mensagem->sala_id,
            'user_id' => $this->mensagem->user_id,
            'conteudo' => $this->mensagem->conteudo,
            'created_at' => $this->mensagem->created_at->toIso8601String(),
            'user' => [
                'id' => $this->mensagem->user->id,
                'name' => $this->mensagem->user->name,
                'avatar' => $this->mensagem->user->avatar,
            ],
        ];
    }
}
