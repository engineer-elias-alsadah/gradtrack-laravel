<?php

namespace App\Http\Requests;

use App\Models\Milestone;
use Illuminate\Foundation\Http\FormRequest;

class StoreSubmissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $milestone = $this->route('milestone');
        $user = $this->user();

        if (! $user || ! $milestone) {
            return false;
        }

        // Edge Case: Only enrolled project members with student role can submit
        return $milestone->project->isMember($user->id);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'notes' => ['required', 'string', 'min:10', 'max:5000'],
            'github_url' => [
                'required',
                'url',
                'regex:/^https:\/\/(www\.)?github\.com\/[a-zA-Z0-9_\-]+\/[a-zA-Z0-9_\-\.]+.*$/i',
            ],
            'attachment' => ['nullable', 'file', 'mimes:pdf,zip,rar,doc,docx', 'max:10240'], // 10MB max
        ];
    }

    /**
     * Custom validation messages in Arabic
     */
    public function messages(): array
    {
        return [
            'notes.required' => 'حقل ملاحظات وتفاصيل التسليم مطلوب ولا يمكن تركه فارغاً.',
            'notes.min' => 'يجب ألا تقل ملاحظات التسليم عن 10 أحرف لشرح المخرجات بدقة.',
            'notes.max' => 'تجاوزت الملاحظات الحد الأقصى المسموح به (5000 حرف).',
            'github_url.required' => 'رابط مستودع GitHub مطلوب لربط الكود المصدري.',
            'github_url.url' => 'يجب إدخال رابط إنترنت صحيح.',
            'github_url.regex' => 'يجب أن يكون الرابط مستودعاً صالحاً على موقع GitHub (مثال: https://github.com/username/repo).',
            'attachment.mimes' => 'الملف المرفق يجب أن يكون بصيغة (PDF, ZIP, RAR, DOCX).',
            'attachment.max' => 'حجم الملف المرفق يجب ألا يتجاوز 10 ميغابايت.',
        ];
    }
}
