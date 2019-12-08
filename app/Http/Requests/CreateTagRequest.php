<?php

namespace App\Http\Requests;

use App\CustomField;
use Illuminate\Foundation\Http\FormRequest;
use App\Tag;

class CreateTagRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = Tag::$rules;
        $customFields = CustomField::where('model_type','tags')->get();
        foreach ($customFields as $customField) {
            $rules["custom_fields.$customField->name"] = $customField->validation ?? 'nullable';
        }

        return $rules;
    }
}
