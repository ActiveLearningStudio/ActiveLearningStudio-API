<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Throwable;

/**
 * Class GeneralException.
 */
class GeneralException extends Exception
{
    /**
     * @var
     */
    public $message;

    /**
     * GeneralException constructor.
     *
     * @param string         $message
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Report the exception.
     */
    public function report()
    {
        //
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param Request
     * @return Application|ResponseFactory|RedirectResponse|Response
     */
    public function render($request)
    {
        if ($request->ajax() || $request->is('api/*') || $request->wantsJson()) {
            return response(['errors' => [$this->message],], 500);
        }
        // All instances of GeneralException redirect back with a flash message to show a bootstrap alert-error
        return redirect()
            ->back()
            ->withInput()
            ->withErrors($this->message);
    }
}
