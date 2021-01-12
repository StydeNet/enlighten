<?php

namespace Styde\Enlighten;

use Illuminate\Validation\ValidationException;
use Throwable;

class ExceptionInfo
{
    /**
     * @var Throwable
     */
    private $exception;

    public static function make(Throwable $exception): ExceptionInfo
    {
        return new self($exception);
    }

    public function __construct(Throwable $exception)
    {
        $this->exception = $exception;
    }

    public function getClassName(): string
    {
        return get_class($this->exception);
    }

    public function getCode(): int
    {
        return $this->exception->getCode();
    }

    public function getMessage(): string
    {
        return $this->exception->getMessage();
    }

    public function getFile(): string
    {
        return $this->exception->getFile();
    }

    public function getLine(): int
    {
        return $this->exception->getLine();
    }

    public function getTrace(): array
    {
        return $this->exception->getTrace();
    }

    public function getData(): array
    {
        if ($this->exception instanceof ValidationException) {
            return [
                'errors' => $this->exception->errors(),
            ];
        }

        return [];
    }
}
