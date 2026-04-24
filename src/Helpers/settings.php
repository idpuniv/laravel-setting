<?php

use App\Services\SettingsService;

if (!function_exists('setting')) {
    /**
     * Récupère un paramètre ou tous les paramètres.
     *
     * @param string|null $key     Clé du paramètre (ex: 'user.theme') ou null pour tout
     * @param mixed       $default Valeur par défaut si la clé n'existe pas
     * @return mixed
     */
    function setting(?string $key = null, $default = null)
    {
        $service = app(SettingsService::class);
        
        // Sans paramètre : retourne tous les settings
        if ($key === null) {
            return $service->all();
        }
        
        // Avec paramètre : retourne la valeur spécifique
        return $service->get($key, $default);
    }
}