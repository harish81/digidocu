<?php

namespace App\Repositories;

use App\CustomField;
use App\Repositories\BaseRepository;

/**
 * Class CustomFieldRepository
 * @package App\Repositories
 * @version November 11, 2019, 5:04 pm IST
*/

class CustomFieldRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'model_type',
        'name',
        'validation'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return CustomField::class;
    }

    public function getForModel($model)
    {
        return $this->allQuery()
            ->where('model_type',$model)
            ->get();
    }
}
