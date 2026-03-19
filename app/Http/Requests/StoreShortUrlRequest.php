<?php

namespace App\Http\Requests;

use App\Models\ShortUrl;
use Illuminate\Foundation\Http\FormRequest;

class StoreShortUrlRequest extends FormRequest
{
    protected $errorBag = 'createUrl';

    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'title'        => 'nullable|string|max:255',
            'original_url' => ['required', 'max:2048', 'regex:/^.+\..{2,}$/'],
            'short_code'   => [
                'nullable', 'string', 'max:20', 'alpha_dash',
                function ($attribute, $value, $fail) {
                    if (ShortUrl::whereRaw('BINARY short_code = ?', [$value])->exists()) {
                        $fail('This alias is already taken. Please choose another.');
                    }
                },
            ],
        ];
    }

    public function messages(): array
    {
        return trans('validation_short_url');
    }
}
