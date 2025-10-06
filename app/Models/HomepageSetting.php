<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class HomepageSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'label',
        'description',
        'sort_order',
    ];

    /**
     * Get a setting value by key with caching
     */
    public static function getValue(string $key, $default = null)
    {
        return Cache::remember("homepage_setting_{$key}", 3600, function() use ($key, $default) {
            $setting = static::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    /**
     * Set a setting value and clear cache
     */
    public static function setValue(string $key, $value): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
        
        Cache::forget("homepage_setting_{$key}");
    }

    /**
     * Get all settings grouped by group
     */
    public static function getGroupedSettings(): array
    {
        return static::orderBy('group')->orderBy('sort_order')->get()->groupBy('group')->toArray();
    }

    /**
     * Clear all homepage settings cache
     */
    public static function clearCache(): void
    {
        $keys = static::pluck('key');
        foreach ($keys as $key) {
            Cache::forget("homepage_setting_{$key}");
        }
    }
}