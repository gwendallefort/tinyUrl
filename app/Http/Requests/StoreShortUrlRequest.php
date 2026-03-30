<?php

namespace App\Http\Requests;

class StoreShortUrlRequest extends ShortUrlRequest
{
    public $errorBag = 'createUrl';

    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return $this->commonRules();
    }
}
