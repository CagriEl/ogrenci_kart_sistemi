<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FormController extends Controller
{
    public function kategoriForm($kategori)
{
    $viewName = 'kategori-formlari.' . strtolower($kategori);

    if (view()->exists($viewName)) {
        return view($viewName);
    }

    return response('<div class="alert alert-danger">Form bulunamadı.</div>', 404);
}

    private function normalize(string $k): string
    {
        $k = trim($k);
        $replace = ['Ğ'=>'G','Ü'=>'U','Ş'=>'S','İ'=>'I','Ö'=>'O','Ç'=>'C',
                    'ğ'=>'g','ü'=>'u','ş'=>'s','ı'=>'i','ö'=>'o','ç'=>'c'];
        $k = strtr($k, $replace);
        $k = strtolower($k);
        $k = preg_replace('~[^a-z0-9]+~', '-', $k);
        return trim($k, '-');
    }
}
