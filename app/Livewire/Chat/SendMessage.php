<?php

namespace App\Livewire\Chat;

use App\Models\Mensagem;
use App\Models\Notification;
use App\Events\MensagemEnviada;
use App\Events\UserTyping;
use App\Events\NotificationSent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;

class SendMessage extends Component
{
    use WithFileUploads;

    public $salaId = null;
    public $conteudo = '';
    public $attachment;

    #[On('salaSelected')]

    public function atualizarSala($salaId)
    {
        $this->salaId = $salaId;
    }

    public function notifyTyping()
    {
        if (!$this->salaId || !Auth::check()) {
            return;
        }

        event(new UserTyping(
            Auth::id(),
            Auth::user()->name,
            $this->salaId
        ));
    }

    public function removeAttachment()
    {
        $this->attachment = null;
    }

    public function enviarMensagem()
    {
        $this->validate([
            'conteudo' => 'nullable|string|max:1000',
            'attachment' => 'nullable|file|max:10240', // 10MB max
        ]);

        // Precisa de conteúdo ou anexo
        if (empty($this->conteudo) && !$this->attachment) {
            return;
        }

        if (!$this->salaId) {
            return;
        }

        $mensagemData = [
            'user_id' => Auth::id(),
            'sala_id' => $this->salaId,
            'conteudo' => $this->conteudo ?? '',
        ];

        // Processar anexo se existir
        if ($this->attachment) {
            $fileName = time() . '_' . $this->attachment->getClientOriginalName();
            $filePath = $this->attachment->storeAs('attachments', $fileName, 'public');

            $mensagemData['file_path'] = $filePath;
            $mensagemData['file_name'] = $this->attachment->getClientOriginalName();
            $mensagemData['file_type'] = $this->attachment->getMimeType();
            $mensagemData['file_size'] = $this->attachment->getSize();
        }

        $mensagem = Mensagem::create($mensagemData);

        // Detectar mentions e criar notificações
        if (!empty($this->conteudo)) {
            $mentionedUsers = get_mentioned_users($this->conteudo);

            foreach ($mentionedUsers as $user) {
                // Não notificar o próprio usuário
                if ($user->id !== Auth::id()) {
                    $notification = Notification::create([
                        'user_id' => $user->id,
                        'mensagem_id' => $mensagem->id,
                        'sala_id' => $this->salaId,
                        'type' => 'mention',
                    ]);

                    event(new NotificationSent($notification));
                }
            }
        }

        event(new MensagemEnviada($mensagem));
        $this->dispatch('mensagemEnviada');
        $this->conteudo = '';
        $this->attachment = null;
    }

    public function render()
    {
        return view('livewire.chat.send-message');
    }
}
