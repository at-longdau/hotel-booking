<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class LanguageController extends Controller
{
    /**
     * Index of multiple language
     *
     * @param Request $request http request
     *
     * @return redirect
     */
    public function index(Request $request)
    {
        $session = $request->session();
        if ($session->has('locale')) {
            session()->put('locale', Route::input('lang'));
        } else {
            session(['locale' => Route::input('lang')]);
        }

        return redirect()->back();
    }
}
