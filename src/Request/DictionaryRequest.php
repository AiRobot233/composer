<?php

declare(strict_types=1);

namespace Airobot\Hyperf\Request;

use Hyperf\Validation\Request\FormRequest;

class DictionaryRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'pid' => 'required',
            'name' => 'required',
            'value' => 'required',
        ];
    }

    /**
     * 获取已定义验证规则的错误消息
     */
    public function messages(): array
    {
        return [
            'pid.required' => 'pid必须',
            'name.required'  => 'name必须',
            'value.required'  => 'value必须',
        ];
    }
}
