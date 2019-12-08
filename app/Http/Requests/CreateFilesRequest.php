<?php

namespace App\Http\Requests;

use App\FileType;
use App\Repositories\CustomFieldRepository;
use Illuminate\Foundation\Http\FormRequest;

class CreateFilesRequest extends FormRequest
{
    /** @var CustomFieldRepository $customFieldRepository */
    private $customFieldRepository;

    /**
     * CreateFilesRequest constructor.
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
        $customFields = $this->customFieldRepository->getForModel('files');
        $filesTypes = FileType::all();
        $filesData = $this->all('files')['files'] ?? [];
        $rules = [
            'files' => 'required|array|min:1',
            'files.*.name' => 'required',
            'files.*.file' => 'required',
            'files.*.file_type_id' => 'required|numeric',
        ];
        //custom fields validation.
        foreach ($customFields as $customField) {
            $rules["files.*.custom_fields.$customField->name"] = $customField->validation ?? 'nullable';
        }
        //file validation based on type
        foreach ($filesData as $i => $fileData) {
            $fileType = $filesTypes->where('id',$fileData['file_type_id'])->first();
            if($fileType)
                $rules["files.$i.file"] = "required|" . $fileType->file_validations . "|max:" . ($fileType->file_maxsize * 1024);
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'required' => 'This field is required.',
            'numeric' => 'This field must be a number.',
            'max' => [
                'file' => 'Large File, This file should be less than :max kilobytes.',
            ],
            'size' => [
                'file' => 'Large File, This file should be less than :size kilobytes.',
            ],
            'mimes' => 'This file must be of type: :values.',
            'uploaded' => 'Failed to upload this file.',
        ];
    }
}
