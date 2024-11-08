<?php

namespace App\Infrastructure\Providers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Response::macro('success', function ($data, $code = SymfonyResponse::HTTP_OK) {
            if ($data instanceof \JsonSerializable) {
                $data = $data->jsonSerialize();
            }

            return Response::json([
                'data' => $data,
            ], $code);
        });

        Response::macro('error', function ($message, $code = SymfonyResponse::HTTP_BAD_REQUEST) {
            return Response::json([
                'message' => $message,
            ], $code);
        });
    }
}
