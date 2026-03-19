<?php

namespace App\Http\Requests;

use App\Models\ShortUrl;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class UpdateShortUrlRequest extends FormRequest
{
    protected $errorBag = 'editUrl';

    public function authorize(): bool
    {
        return $this->route('shortUrl')->user_id === auth()->id();
    }

    public function rules(): array
    {
        $shortUrl = $this->route('shortUrl');

        return [
            'title'        => 'nullable|string|max:255',
            'original_url' => ['required', 'max:2048', 'regex:/^.+\..{2,}$/'],
            'short_code'   => [
                'required', 'string', 'max:20', 'alpha_dash',
                function ($attribute, $value, $fail) use ($shortUrl) {
                    if (ShortUrl::whereRaw('BINARY short_code = ?', [$value])->where('id', '!=', $shortUrl->id)->exists()) {
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

    protected function failedValidation(Validator $validator): void
    {
        session()->flash('edit_id', $this->route('shortUrl')->id);

        parent::failedValidation($validator);
    }
}
