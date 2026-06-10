<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name'  => ['required', 'string', 'max:255'],
            'email'      => ['required', 'email', 'unique:contacts,email'],
            'phone'      => ['nullable', 'string', 'max:50'],
            'stage'      => ['required', 'in:lead,contacted,qualified,proposal_sent,won,lost'],
            'notes'      => ['nullable', 'string'],
            'tags'       => ['nullable', 'array'],
            'tags.*'     => ['exists:tags,id'],
        ];
    }
}
