<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Mensagem extends Model
{
    protected $table = 'mensagens';

    protected $fillable = [
        'user_id',
        'sala_id',
        'conteudo',
        'lida',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
    ];

    protected $casts = [
        'lida' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sala()
    {
        return $this->belongsTo(Sala::class);
    }

    public function hasAttachment()
    {
        return !empty($this->file_path);
    }

    public function getFileSizeFormattedAttribute()
    {
        if (!$this->file_size) {
            return '';
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = $this->file_size;
        $i = 0;

        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getFileIconAttribute()
    {
        if (!$this->file_type) {
            return '📄';
        }

        return match(true) {
            str_contains($this->file_type, 'image') => '🖼️',
            str_contains($this->file_type, 'pdf') => '📕',
            str_contains($this->file_type, 'word') || str_contains($this->file_type, 'document') => '📘',
            str_contains($this->file_type, 'excel') || str_contains($this->file_type, 'spreadsheet') => '📗',
            str_contains($this->file_type, 'video') => '🎥',
            str_contains($this->file_type, 'audio') => '🎵',
            str_contains($this->file_type, 'zip') || str_contains($this->file_type, 'rar') || str_contains($this->file_type, 'compressed') => '🗜️',
            default => '📄',
        };
    }

    public function getFileUrlAttribute()
    {
        if (!$this->file_path) {
            return null;
        }

        return Storage::url($this->file_path);
    }
}
