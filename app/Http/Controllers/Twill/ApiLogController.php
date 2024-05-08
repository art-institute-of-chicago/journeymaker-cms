<?php

namespace App\Http\Controllers\Twill;

use App\Models\ApiLog;
use Illuminate\Routing\Controller;

class ApiLogController extends Controller
{
    public function __invoke()
    {
        return view('admin.api-log', [
            'logs' => ApiLog::getRecentChanges(),
        ]);
    }
}
