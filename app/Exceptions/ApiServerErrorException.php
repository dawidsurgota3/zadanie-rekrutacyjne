<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\RedirectResponse;
use Throwable;

class ApiServerErrorException extends Exception
{
    /**
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message, int $code = 400, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return RedirectResponse
     */
    public function render(): RedirectResponse
    {
        return back()->withErrors([
            'server' => $this->getMessage(),
        ]);
    }
}
