<?php

namespace App\Http;

use App\Dtos\DTO;
use App\Http\Exceptions\ValidationException;
use Valitron\Validator;

class Request
{
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_PATCH = 'PATCH';
    const METHOD_DELETE = 'DELETE';

    const ALLOWED_HTTP_METHODS = [
        'GET', 'POST', 'PUT', 'PATCH', 'DELETE'
    ];

    protected Validator $validator;

    public function __construct() {
        $this->validator = new Validator($this->all());

        $this->validator->rules($this->rules());

        if (!$this->validator->validate()) {
            throw new ValidationException($this);
        }
    }

    public function all(): array
    {
        return json_decode(file_get_contents('php://input'), true);
    }

    public function dto(): DTO
    {
        return new DTO();
    }

    public function rules(): array
    {
        return [
            // Validation rules
        ];
    }

    public function getHttpErrorCode(): HttpCode
    {
        return HttpCode::UNPROCESSABLE_ENTITY;
    }

    public function errors(?string $field = null): array|bool
    {
        return $this->validator->errors($field);
    }

    public static function method(): string
    {
        //Method spoofing
        if (isset($_POST['_method'])) {
            return in_array($_POST['_method'], Request::ALLOWED_HTTP_METHODS)
                ? $_POST['_method']
                : $_SERVER['REQUEST_METHOD'];
        }

        return $_SERVER['REQUEST_METHOD'];
    }

    public static function uri(): string
    {
        $uri = $_SERVER['REQUEST_URI'];

        // Strip query string (?foo=bar) and decode URI
        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }

        return rawurldecode($uri);
    }
}