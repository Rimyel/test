<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Analytic;

class TrackPageViews
{
    public function handle(Request $request, Closure $next)
    {
        // Получаем текущий URL
        $url = $request->fullUrl();

        // Записываем событие в базу данных
        Analytic::create([
            'event_type' => 'page_view',
            'url' => $url,
            'user_id' => auth()->id(), // Если нужно отслеживать пользователя
            'user_agent' => $request->userAgent(), // Если нужно отслеживать User-Agent
        ]);

        return $next($request);
    }
}

