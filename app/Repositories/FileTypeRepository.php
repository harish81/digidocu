<?php

namespace App\Repositories;

use App\FileType;
use App\Repositories\BaseRepository;

/**
 * Class FileTypeRepository
 * @package App\Repositories
 * @version November 12, 2019, 12:21 pm IST
*/

class FileTypeRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'no_of_files',
        'labels',
        'file_validations',
        'file_maxsize'
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
        return FileType::class;
    }
}
