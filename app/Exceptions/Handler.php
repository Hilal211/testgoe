<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if ($e instanceof \Illuminate\Session\TokenMismatchException) {
            if ($request->wantsJson()){
                return response()->json(['message' =>'Oops! It looks like your session expired, Please reload Page  and try again'],500);
            }else{
                \Auth::logout();
                $arr = array();
                $arr['token-mismatch'] = 'Oops! It looks like your session expired, Please reload Page and try again';
                return redirect()->route('login')->withErrors(array($arr))->withInput();
            }
        }

        if ($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException){
            $arr = array();
            $arr['error'] = '';
            return redirect()->route('frontend.404')->withErrors(array($arr));
        }
        return parent::render($request, $e);
    }
}
