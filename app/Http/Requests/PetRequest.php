<?php

namespace App\Http\Requests;

use App\PetStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category' => ['nullable', 'array'],
            'category.id' => ['required_with:category.name', 'integer'],
            'category.name' => ['required_with:category.id', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'photoUrls' => ['required', 'array', 'min:1'],
            'photoUrls.*' => ['string', 'url'],
            'tags' => ['nullable', 'array'],
            'tags.*.id' => ['required_with:tags.name', 'integer'],
            'tags.*.name' => ['required_with:tags.id', 'string', 'max:255'],
            'status' => ['nullable', Rule::enum(PetStatus::class)],
        ];
    }
}
