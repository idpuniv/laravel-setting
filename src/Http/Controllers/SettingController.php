<?php

namespace Idpuniv\Http\Controllers;

use App\Services\SettingsService;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Affiche la page des paramètres.
     */
    public function index(SettingsService $settings)
    {
        return view('settings.index', [
            'groups' => $settings->visibleGroups(),
            'fields' => config('settings.fields'),
            'settings' => $settings->all(),
        ]);
    }

    /**
     * Met à jour un paramètre (AJAX).
     */
    public function update(Request $request, SettingsService $settings)
    {
        $request->validate([
            'key' => 'required|string',
            'value' => 'required',
        ]);

        $settings->set($request->input('key'), $request->input('value'));

        // Si la requête est AJAX, retourner JSON
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Paramètre sauvegardé',
                'key' => $request->input('key'),
                'value' => $request->input('value')
            ]);
        }

        // Fallback pour requête normale
        return back()->with('success', 'Paramètre mis à jour.');
    }
}