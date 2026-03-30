<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Events\DiagnosingHealth;
use Illuminate\Support\Facades\Event;

class HealthCheckController extends Controller
{
    public function __invoke()
    {
        $start = hrtime(true);
        $exception = null;

        try {
            Event::dispatch(new DiagnosingHealth);
        } catch (\Throwable $e) {
            if (app()->hasDebugModeEnabled()) {
                throw $e;
            }

            report($e);

            $exception = $e->getMessage();
        }

        return response()->view('up', [
            'exception' => $exception,
        ], $exception ? 500 : 200);
    }
}
