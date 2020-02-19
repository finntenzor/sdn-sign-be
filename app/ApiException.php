<?php
declare (strict_types = 1);

namespace app;

use RuntimeException;

class ApiException extends RuntimeException
{
    protected $code;
    // protected $message;

    public function __construct(int $code, string $message)
    {
        parent::__construct($message, $code);
        $this->code = $code;
    }

    public function toArray()
    {
        return [
            'code' => $this->code,
            'message' => $this->getMessage(),
        ];
    }

    public function toJson()
    {
        return json($this->toArray());
    }
}
