<?php

namespace App\Http\Controllers;

use App\Models\Mensagem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class AttachmentController extends Controller
{
    public function download($mensagemId)
    {
        $mensagem = Mensagem::with('sala.users')->findOrFail($mensagemId);

        // Verificar se o utilizador pertence à sala
        $userBelongsToSala = $mensagem->sala->users->contains(Auth::id());

        if (!$userBelongsToSala) {
            abort(403, 'Não tem permissão para aceder a este ficheiro.');
        }

        // Verificar se a mensagem tem anexo
        if (!$mensagem->hasAttachment()) {
            abort(404, 'Anexo não encontrado.');
        }

        // Retornar o ficheiro para download
        $filePath = storage_path('app/public/' . $mensagem->file_path);

        if (!file_exists($filePath)) {
            abort(404, 'Ficheiro não encontrado no servidor.');
        }

        return response()->download($filePath, $mensagem->file_name, [
            'Content-Type' => $mensagem->file_type,
        ]);
    }
}
