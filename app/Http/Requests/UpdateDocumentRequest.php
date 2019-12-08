<?php

namespace App\Http\Requests;

use App\Document;
use App\Repositories\CustomFieldRepository;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDocumentRequest extends FormRequest
{
    /** @var CustomFieldRepository $customFieldRepository */
    private $customFieldRepository;

    /**
     * UpdateDocumentRequest constructor.
     * @param CustomFieldRepository $customFieldRepository
     */
    public function __construct(CustomFieldRepository $customFieldRepository)
    {
        parent::__construct();
        $this->customFieldRepository = $customFieldRepository;
    }

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
        $rules = Document::$rules;
        $customFields = $this->customFieldRepository->getForModel('documents');
        foreach ($customFields as $customField) {
            $rules["custom_fields.$customField->name"] = $customField->validation ?? 'nullable';
        }
        return $rules;
    }
}
