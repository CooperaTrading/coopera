<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreProformaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'payment_terms' => ['required', 'string', 'max:2000'],
            'delivery_time' => ['required', 'string', 'max:2000'],
            'bank_details' => ['required', 'string', 'max:5000'],
            'notes' => ['nullable', 'string', 'max:5000'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.quote_item_id' => ['required', 'integer'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
            'send_email' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'items.*.unit_price.required' => 'Unit price is required for each item.',
            'items.*.unit_price.min' => 'Unit price cannot be negative.',
        ];
    }
}
