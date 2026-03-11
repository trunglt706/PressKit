<?php

namespace App\Http\Requests\Guest;

use Illuminate\Foundation\Http\FormRequest;

class StoreContactSubmissionRequest extends FormRequest
{
    /**
     * Determine whether the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare input data before validation.
     */
    protected function prepareForValidation(): void
    {
        $normalizedPhone = preg_replace('/\D+/', '', (string) $this->input('phone', ''));

        $this->merge([
            'name' => trim((string) $this->input('name', '')),
            'email' => mb_strtolower(trim((string) $this->input('email', ''))),
            'phone' => $normalizedPhone,
            'subject' => trim((string) $this->input('subject', '')),
            'message' => trim((string) $this->input('message', '')),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:120'],
            'email' => ['required', 'email:rfc,dns', 'max:190'],
            'phone' => ['required', 'string', 'min:9', 'max:20'],
            'subject' => ['required', 'string', 'min:3', 'max:180'],
            'message' => ['required', 'string', 'min:10', 'max:3000'],
        ];
    }

    /**
     * Custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Vui lòng nhập họ và tên.',
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không đúng định dạng.',
            'phone.required' => 'Vui lòng nhập số điện thoại.',
            'subject.required' => 'Vui lòng nhập tiêu đề.',
            'message.required' => 'Vui lòng nhập nội dung tin nhắn.',
            'message.min' => 'Nội dung tin nhắn cần ít nhất 10 ký tự.',
        ];
    }
}
