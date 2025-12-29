<?php

use Carbon\Carbon;
use App\Models\User;

if (!function_exists('extract_mentions')) {
    /**
     * Extrai todas as mentions (@username) de um texto
     * @param string $text
     * @return array Array de usernames mencionados
     */
    function extract_mentions(string $text): array
    {
        preg_match_all('/@(\w+)/', $text, $matches);
        return $matches[1] ?? [];
    }
}

if (!function_exists('get_mentioned_users')) {
    /**
     * Retorna os usuários mencionados em um texto
     * @param string $text
     * @return \Illuminate\Support\Collection
     */
    function get_mentioned_users(string $text)
    {
        $usernames = extract_mentions($text);

        if (empty($usernames)) {
            return collect([]);
        }

        // Buscar todos os usuários e filtrar manualmente para lidar com acentos e case-insensitive
        $users = User::all();
        $mentionedUsers = collect([]);

        foreach ($usernames as $username) {
            $normalizedUsername = remove_accents(strtolower($username));

            foreach ($users as $user) {
                // Comparar com nome completo
                $normalizedFullName = remove_accents(strtolower($user->name));

                // Comparar com primeiro nome
                $firstName = explode(' ', $user->name)[0];
                $normalizedFirstName = remove_accents(strtolower($firstName));

                if ($normalizedFullName === $normalizedUsername || $normalizedFirstName === $normalizedUsername) {
                    if (!$mentionedUsers->contains('id', $user->id)) {
                        $mentionedUsers->push($user);
                    }
                    break;
                }
            }
        }

        return $mentionedUsers;
    }
}

if (!function_exists('remove_accents')) {
    /**
     * Remove acentos de uma string
     * @param string $string
     * @return string
     */
    function remove_accents(string $string): string
    {
        $accents = [
            'á' => 'a', 'à' => 'a', 'ã' => 'a', 'â' => 'a', 'ä' => 'a',
            'é' => 'e', 'è' => 'e', 'ê' => 'e', 'ë' => 'e',
            'í' => 'i', 'ì' => 'i', 'î' => 'i', 'ï' => 'i',
            'ó' => 'o', 'ò' => 'o', 'õ' => 'o', 'ô' => 'o', 'ö' => 'o',
            'ú' => 'u', 'ù' => 'u', 'û' => 'u', 'ü' => 'u',
            'ç' => 'c', 'ñ' => 'n',
            'Á' => 'A', 'À' => 'A', 'Ã' => 'A', 'Â' => 'A', 'Ä' => 'A',
            'É' => 'E', 'È' => 'E', 'Ê' => 'E', 'Ë' => 'E',
            'Í' => 'I', 'Ì' => 'I', 'Î' => 'I', 'Ï' => 'I',
            'Ó' => 'O', 'Ò' => 'O', 'Õ' => 'O', 'Ô' => 'O', 'Ö' => 'O',
            'Ú' => 'U', 'Ù' => 'U', 'Û' => 'U', 'Ü' => 'U',
            'Ç' => 'C', 'Ñ' => 'N',
        ];

        return strtr($string, $accents);
    }
}

if (!function_exists('highlight_mentions')) {
    /**
     * Destaca as mentions em um texto com HTML
     * @param string $text
     * @return string
     */
    function highlight_mentions(string $text): string
    {
        return preg_replace(
            '/@(\w+)/',
            '<span class="font-bold">@$1</span>',
            $text
        );
    }
}

if (!function_exists('contextual_timestamp')) {
    /**
     * Retorna um timestamp contextual mais rico
     * Exemplo: "há 2 min", "há 1 hora", "ontem às 14:30", "15 Jan às 10:00"
     */
    function contextual_timestamp(Carbon $date): string
    {
        $now = Carbon::now();
        $diffInSeconds = $now->diffInSeconds($date);
        $diffInMinutes = $now->diffInMinutes($date);
        $diffInHours = $now->diffInHours($date);
        $diffInDays = $now->diffInDays($date);

        // Menos de 1 minuto: "agora"
        if ($diffInSeconds < 60) {
            return 'agora';
        }

        // Menos de 1 hora: "há X min"
        if ($diffInMinutes < 60) {
            return 'há ' . $diffInMinutes . ' min';
        }

        // Menos de 24 horas: "há X horas"
        if ($diffInHours < 24) {
            return 'há ' . $diffInHours . ($diffInHours === 1 ? ' hora' : ' horas');
        }

        // Ontem: "ontem às HH:mm"
        if ($diffInDays === 1) {
            return 'ontem às ' . $date->format('H:i');
        }

        // Últimos 7 dias: "segunda às HH:mm"
        if ($diffInDays < 7) {
            $diasSemana = [
                'Sunday' => 'domingo',
                'Monday' => 'segunda',
                'Tuesday' => 'terça',
                'Wednesday' => 'quarta',
                'Thursday' => 'quinta',
                'Friday' => 'sexta',
                'Saturday' => 'sábado',
            ];
            return $diasSemana[$date->englishDayOfWeek] . ' às ' . $date->format('H:i');
        }

        // Este ano: "15 Jan às HH:mm"
        if ($date->year === $now->year) {
            $meses = [
                1 => 'Jan', 2 => 'Fev', 3 => 'Mar', 4 => 'Abr',
                5 => 'Mai', 6 => 'Jun', 7 => 'Jul', 8 => 'Ago',
                9 => 'Set', 10 => 'Out', 11 => 'Nov', 12 => 'Dez',
            ];
            return $date->day . ' ' . $meses[$date->month] . ' às ' . $date->format('H:i');
        }

        // Outros anos: "15 Jan 2024"
        $meses = [
            1 => 'Jan', 2 => 'Fev', 3 => 'Mar', 4 => 'Abr',
            5 => 'Mai', 6 => 'Jun', 7 => 'Jul', 8 => 'Ago',
            9 => 'Set', 10 => 'Out', 11 => 'Nov', 12 => 'Dez',
        ];
        return $date->day . ' ' . $meses[$date->month] . ' ' . $date->year;
    }
}
