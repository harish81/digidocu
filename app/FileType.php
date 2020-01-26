<?php

namespace App;

use App\Rules\ValidationRuleSyntaxChecker;
use Eloquent as Model;

/**
 * Class FileType
 *
 * @package App
 * @version November 12, 2019, 12:21 pm IST
 * @property int $id
 * @property string $name
 * @property int $no_of_files
 * @property string $labels
 * @property string $file_validations
 * @property int $file_maxsize
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FileType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FileType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FileType query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FileType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FileType whereFileMaxsize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FileType whereFileValidations($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FileType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FileType whereLabels($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FileType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FileType whereNoOfFiles($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FileType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FileType extends Model
{

    public $table = 'file_types';




    public $fillable = [
        'name',
        'no_of_files',
        'labels',
        'file_validations',
        'file_maxsize'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'no_of_files' => 'integer',
        'labels' => 'string',
        'file_validations' => 'string',
        'file_maxsize' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        'no_of_files' => 'required|integer',
        'labels' => 'required',
        'file_validations' => 'required',
        'file_maxsize' => 'required|integer'
    ];

    protected static function boot()
    {
        parent::boot();
        self::$rules['file_validations'] = ['required', new ValidationRuleSyntaxChecker()];
    }

}
