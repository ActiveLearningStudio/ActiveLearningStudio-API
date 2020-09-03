<?php

/*
 *
 * @Project        Expression project.displayName is undefined on line 5, column 35 in Templates/Licenses/license-default.txt.
 * @Copyright      Djoudi
 * @Created        2017-02-21
 * @Filename       H5PExceptionHandler.php
 * @Description
 *
 */

namespace Djoudi\LaravelH5p\Exceptions;

use Djoudi\LaravelH5p\Exceptions\H5PException;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class H5PExceptionHandler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        HttpException::class,
    ];

    /**
     * Report or log an exception.
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param Throwable $e
     * @return void
     * @throws Exception
     */
    public function report(Throwable $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param Request $request
     * @param Throwable $e
     * @return Response
     * @throws Throwable
     */
    public function render($request, Throwable $e)
    {
        switch ($e) {
            case ($e instanceof ModelNotFoundException):
            case ($e instanceof H5PException):
                return $this->renderException($e);

            default:
                return parent::render($request, $e);
        }
    }

    protected function renderException($e)
    {
        switch ($e) {
            case ($e instanceof ModelNotFoundException):
                return response()->view('errors.404', [], 404);

            case ($e instanceof H5PException):
                return response()->view('errors.friendly');

            default:
                return (new SymfonyDisplayer(config('app.debug')))->createResponse($e);
        }
    }
}
