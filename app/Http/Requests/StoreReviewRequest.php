<?php

namespace App\Http\Requests;

use App\Models\Submission;
use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $submission = $this->route('submission');
        $user = $this->user();

        if (! $user || ! $submission) {
            return false;
        }

        // Edge Case: Only project supervisor or admin can evaluate submission
        return $user->isSupervisor() || $user->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'score' => ['required', 'integer', 'min:0', 'max:100'],
            'decision' => ['required', 'in:approved,needs_revision,rejected'],
            'feedback' => ['required', 'string', 'min:10', 'max:5000'],
        ];
    }

    /**
     * Custom validation messages in Arabic
     */
    public function messages(): array
    {
        return [
            'score.required' => 'رصد درجة التقييم مطلوب.',
            'score.integer' => 'يجب أن تكون الدرجة رقماً صحيحاً.',
            'score.min' => 'الدرجة لا يمكن أن تكون أقل من 0.',
            'score.max' => 'الدرجة لا يمكن أن تتجاوز 100.',
            'decision.required' => 'يجب تحديد قرار المراجعة (معتمد / يحتاج تعديل / مرفوض).',
            'decision.in' => 'قرار المراجعة المحدد غير صالح.',
            'feedback.required' => 'التغذية الراجعة والملاحظات الأكاديمية مطلوبة لتوجيه الطلاب.',
            'feedback.min' => 'يجب كتابة ملاحظات مفصلة لا تقل عن 10 أحرف.',
        ];
    }
}
