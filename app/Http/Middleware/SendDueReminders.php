<?php

namespace App\Http\Middleware;

use App\Services\ReminderNotificationService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SendDueReminders
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()) {
            return $next($request);
        }

        try {
            app(ReminderNotificationService::class)->sendDueReminders();
        } catch (\Throwable $e) {
            report($e);
        }

        return $next($request);
    }
}
