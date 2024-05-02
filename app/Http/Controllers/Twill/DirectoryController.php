<?php

namespace App\Http\Controllers\Twill;

use App\Models\Theme;
use Illuminate\Routing\Controller;

class DirectoryController extends Controller
{
    public function __invoke()
    {
        return view('admin.directory', [
            'themes' => Theme::with('prompts.artworks.artwork')->get(),
        ]);
    }
}
