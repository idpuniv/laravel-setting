<?php

namespace Idpuniv\Setting\Services;

use Idpuniv\Setting\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Permissions\SystemPermissions;

class SettingsService
{
    private array $loadedSettings = [];

    /**
     * Récupère un paramètre.
     */
    public function get(string $key, $default = null)
    {
        return $this->all()[$key] ?? $default;
    }

    /**
     * Sauvegarde un paramètre.
     */
    public function set(string $key, $value): void
    {
        $user = Auth::user();
        
        // Protection: system.* = permission Spatie requise
        if (str_starts_with($key, 'system.')) {
            abort_unless($user && $user->can(SystemPermissions::EDIT_SETTINGS), 403, 'Paramètre système interdit.');
        }

        Setting::updateOrCreate(
            [
                'key' => $key,
                'user_id' => str_starts_with($key, 'system.') ? null : $user?->id
            ],
            [
                'value' => $value,
                'is_system' => str_starts_with($key, 'system.')
            ]
        );

        $this->clearCache();
    }

    /**
     * Tous les paramètres fusionnés.
     * Ordre: defaults < globaux < utilisateur
     */
    public function all(): array
    {
        if ($this->loadedSettings) {
            return $this->loadedSettings;
        }

        $user = Auth::user();
        $userId = $user?->id;

        $global = Cache::remember('settings_global', 3600, function () {
            return Setting::whereNull('user_id')->pluck('value', 'key')->toArray();
        });

        $userSettings = $userId ? Cache::remember("settings_user_{$userId}", 3600, function () use ($userId) {
            return Setting::where('user_id', $userId)->pluck('value', 'key')->toArray();
        }) : [];

        $this->loadedSettings = array_replace_recursive(
            $this->defaults(),
            $global,
            $userSettings
        );

        return $this->loadedSettings;
    }

    /**
     * Groupes visibles dans l'interface.
     */
    public function visibleGroups(): array
    {
        $groups = config('settings.groups', []);
        $user = Auth::user();

        if (!$user || !$user->can(SystemPermissions::VIEW_SETTINGS)) {
            unset($groups['system']);
        }

        return $groups;
    }

    /**
     * Vide le cache.
     */
    public function clearCache(): void
    {
        Cache::forget('settings_global');
        if ($user = Auth::user()) {
            Cache::forget("settings_user_{$user->id}");
        }
        $this->loadedSettings = [];
    }

    /**
     * Extrait les valeurs par défaut depuis la configuration fields.
     */
    private function defaults(): array
    {
        $defaults = [];
        
        foreach (config('settings.fields', []) as $key => $config) {
            if (isset($config['default'])) {
                $defaults[$key] = $config['default'];
            }
        }
        return $defaults;
    }
}