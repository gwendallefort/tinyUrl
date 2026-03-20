<?php

namespace App\Http\Requests;

class UpdateShortUrlRequest extends ShortUrlRequest
{
    protected $errorBag = 'editUrl';

    public function authorize(): bool
    {
        return $this->route('shortUrl')->user_id === auth()->id();
    }

    public function rules(): array
    {
        return $this->commonRules($this->route('shortUrl')->id);
    }
}
