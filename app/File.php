<?php

namespace App;

use Eloquent as Model;

/**
 * Class File
 *
 * @package App
 * @version November 15, 2019, 12:21 pm IST
 * @property \App\Document document
 * @property \App\FileType fileType
 * @property \App\User createdBy
 * @property string name
 * @property string file
 * @property integer document_id
 * @property integer file_type_id
 * @property integer created_by
 * @property string custom_fields
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\File newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\File newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\File query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\File whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\File whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\File whereCustomFields($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\File whereDocumentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\File whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\File whereFileTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\File whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\File whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\File whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $name
 * @property string $file
 * @property int $document_id
 * @property int $file_type_id
 * @property int $created_by
 * @property array|null $custom_fields
 * @property-read \App\User $createdBy
 * @property-read \App\Document $document
 * @property-read \App\FileType $fileType
 */
class File extends Model
{

    public $table = 'files';


    public $fillable = [
        'name',
        'file',
        'document_id',
        'file_type_id',
        'created_by',
        'custom_fields'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'file' => 'string',
        'document_id' => 'integer',
        'file_type_id' => 'integer',
        'created_by' => 'integer',
        'custom_fields' => 'array'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function document()
    {
        return $this->belongsTo(\App\Document::class, 'document_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function fileType()
    {
        return $this->belongsTo(\App\FileType::class, 'file_type_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function createdBy()
    {
        return $this->belongsTo(\App\User::class, 'created_by', 'id');
    }
}
