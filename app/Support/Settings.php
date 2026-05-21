<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;

class Settings
{
    protected static string $path = 'settings.json';

    public static function get(string $key, mixed $default = null): mixed
    {
        return Cache::rememberForever("setting_{$key}", function () use ($key, $default) {
            return static::all()[$key] ?? $default;
        });
    }

    public static function set(string $key, mixed $value): void
    {
        $all = static::all();
        $all[$key] = $value;
        storage_path('app/' . static::$path);
        file_put_contents(storage_path('app/' . static::$path), json_encode($all, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        Cache::forget("setting_{$key}");
    }

    protected static function all(): array
    {
        $path = storage_path('app/' . static::$path);
        if (!file_exists($path)) {
            return [];
        }
        return json_decode(file_get_contents($path), true) ?? [];
    }
}
