<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sections = Cache::remember('sections', now()->addDay(), fn () => Section::get('name'));

        return view('products.index', [
            'sections' => $sections,
        ]);
    }
}
