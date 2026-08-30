<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SuggestShortCodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['nullable', 'string', 'max:255'],
            'original_url' => ['nullable', 'string', 'max:2048', 'regex:/^.+\..{2,}$/'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator): void {
            if (! filled($this->input('title')) && ! filled($this->input('original_url'))) {
                $validator->errors()->add(
                    'original_url',
                    __('validation_short_url.suggest.missing_context')
                );
            }
        });
    }
}
