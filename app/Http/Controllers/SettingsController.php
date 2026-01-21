<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function index()
    {
        return view('settings.index');
    }

    public function updateLanguage(Request $request)
    {
        $request->validate([
            'language' => 'required|in:en,ru,lv',
        ]);

        $language = $request->language;

        if (Auth::check()) {
            Auth::user()->update(['language' => $language]);
        }

        session(['locale' => $language]);
        App::setLocale($language);

        return back()->with('success', __('settings.language_updated'));
    }
}
