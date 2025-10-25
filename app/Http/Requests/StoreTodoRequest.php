<?php

namespace App\Http\Requests;

use App\Enums\TodoPriority;
use App\Enums\TodoStatus;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\Enum;

class StoreTodoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Untuk sekarang, always authorize.
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
            'title' => 'required|string',
            'assignee' => 'nullable|string',
            'due_date' => [
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    if (Carbon::parse($value)->isPast()) {
                        $fail('Due date tidak bisa di waktu yang sudah berlalu.');
                    }
                }
            ],
            'time_tracked' => 'numeric|min:0',
            'status' => ['nullable', new Enum(TodoStatus::class)],
            'priority' => ['required', new Enum(TodoPriority::class)],
        ];
    }

    protected function failedValidation(Validator $validator) : void
    {
        throw new HttpResponseException(
            response()->json([
                'status' => 422,
                'errors' => $validator->errors(),
            ])
        );
    }
}
