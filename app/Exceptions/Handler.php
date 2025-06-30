<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->is('admin/*')) {
                return response()->view('layouts.admin.errors.404', [], 404);
            }
            return response()->view('layouts.user.errors.404', [], 404);
        });

        $this->renderable(function (AuthorizationException $e, $request) {
            if ($request->is('admin/*')) {
                return response()->view('layouts.admin.errors.403', [], 403);
            }
            return response()->view('layouts.user.errors.403', [], 403);
        });
    }
}
