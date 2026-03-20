<?php

namespace App\Http\Requests;

class StoreShortUrlRequest extends ShortUrlRequest
{
    protected $errorBag = 'createUrl';

    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return $this->commonRules();
    }
}
