<?php

namespace App\Exceptions;

use App;
use Exception;
use Request;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        if ($this->shouldReport($exception)) {
            if (App::environment('staging') || App::environment('production')) {
                $fields = Request::all();
                
                if (key_exists('password', $fields)) {
                    $fields['password'] = '********';
                }
                
                if (key_exists('password_confirmation', $fields)) {
                    $fields['password_confirmation'] = '********';
                }
    
                Log::emergency($exception->getMessage(), [
                    'url' => Request::url(),
                    'input' => $fields,
                ]);
            }
        }

        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if (App::environment('local') == false && App::environment('testing') == false) {
            if ($exception instanceof AuthorizationException) {
                return redirect(route('index'))
                    ->withErrors($exception->getMessage());
            }

            if ($exception instanceof MethodNotAllowedHttpException) {
                return redirect(route('index'))
                    ->withErrors('Ooops, something went wrong...');
            }
        }

        return parent::render($request, $exception);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        $redirect_route = 'login';
        $guard = $exception->guards();

        if (count($guard) < 1) {
            return redirect()->guest(route($redirect_route));
        }

        if ($guard[0] == 'admin') {
            $redirect_route = 'admin.login';
        }

        return redirect()->guest(route($redirect_route));
    }
}
