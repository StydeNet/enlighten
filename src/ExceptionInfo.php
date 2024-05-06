<?php

namespace Styde\Enlighten;

use Illuminate\Validation\ValidationException;
use Throwable;

class ExceptionInfo
{
    public static function make(Throwable $exception): ExceptionInfo
    {
        return new self($exception);
    }

    public function __construct(private readonly Throwable $exception)
    {
    }

    public function getClassName(): string
    {
        return $this->exception::class;
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
