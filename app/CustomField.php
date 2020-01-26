<?php

namespace App;

use App\Rules\ValidationRuleSyntaxChecker;
use Eloquent as Model;

/**
 * Class CustomField
 *
 * @package App
 * @version November 11, 2019, 5:04 pm IST
 * @property int $id
 * @property string $model_type
 * @property string $name
 * @property string|null $validation
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomField newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomField newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomField query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomField whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomField whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomField whereModelType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomField whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomField whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomField whereValidation($value)
 * @mixin \Eloquent
 */
class CustomField extends Model
{

    public $table = 'custom_fields';




    public $fillable = [
        'model_type',
        'name',
        'validation',
        'suggestions'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'model_type' => 'string',
        'name' => 'string',
        'validation' => 'string',
        'suggestions' => 'array'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'model_type' => 'required',
        'name' => 'required',
        'validation' => 'nullable',
        'suggestions' => 'nullable'
    ];

    protected static function boot()
    {
        parent::boot();
        self::$rules['validation'] = ['nullable', new ValidationRuleSyntaxChecker()];
    }


}
