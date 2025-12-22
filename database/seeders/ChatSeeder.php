<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Sala;
use App\Models\Mensagem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ChatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void

    {
        // Criar utilizadores
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@inovchat.com',
            'password' => Hash::make('password'),
            'permissao' => 'Admin',
            'estado' => 'online',
        ]);

        $user1 = User::create([
            'name' => 'João Silva',
            'email' => 'joao@example.com',
            'password' => Hash::make('password'),
            'permissao' => 'User',
            'estado' => 'online',
        ]);

        $user2 = User::create([
            'name' => 'Maria Santos',
            'email' => 'maria@example.com',
            'password' => Hash::make('password'),
            'permissao' => 'User',
            'estado' => 'offline',
        ]);

        $user3 = User::create([
            'name' => 'Pedro Costa',
            'email' => 'pedro@example.com',
            'password' => Hash::make('password'),
            'permissao' => 'User',
            'estado' => 'online',
        ]);

        // Criar sala
        $salaGeral = Sala::create([
            'nome' => 'Sala Geral',
        ]);

        $salaTecnologia = Sala::create([
            'nome' => 'Tecnologia',
        ]);

        $salaDesign = Sala::create([
            'nome' => 'Design',
        ]);

        // Associar utilizadores às salas
        $salaGeral->users()->attach([$admin->id, $user1->id, $user2->id, $user3->id]);
        $salaTecnologia->users()->attach([$admin->id, $user1->id, $user3->id]);
        $salaDesign->users()->attach([$admin->id, $user2->id]);


        // Criar algumas mensagens de exemplo
        Mensagem::create([
            'user_id' => $admin->id,
            'sala_id' => $salaGeral->id,
            'conteudo' => 'Bem-vindos ao InovChat! 👋',
        ]);

        Mensagem::create([
            'user_id' => $user1->id,
            'sala_id' => $salaGeral->id,
            'conteudo' => 'Olá pessoal! Muito fixe este sistema de chat!',
        ]);

        Mensagem::create([
            'user_id' => $user2->id,
            'sala_id' => $salaGeral->id,
            'conteudo' => 'Concordo! Está muito bem feito.',
        ]);

        Mensagem::create([
            'user_id' => $admin->id,
            'sala_id' => $salaTecnologia->id,
            'conteudo' => 'Vamos discutir as novas tecnologias aqui!',
        ]);

        Mensagem::create([
            'user_id' => $user1->id,
            'sala_id' => $salaTecnologia->id,
            'conteudo' => 'Laravel + Livewire é uma combinação fantástica!',
        ]);

        $this->command->info('Dados de teste criados com sucesso!');
        $this->command->info('Email: admin@inovchat.com | Password: password');
    }
}
