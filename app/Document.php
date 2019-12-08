<?php

namespace App;

use Eloquent as Model;

/**
 * Class Document
 *
 * @package App
 * @version November 13, 2019, 3:00 pm IST
 * @property int $id
 * @property int|null $verified_by
 * @property string|null $verified_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\File[] $files
 * @property-read int|null $files_count
 * @property-read mixed $is_verified
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Tag[] $tags
 * @property-read int|null $tags_count
 * @property-read \App\User|null $verifiedBy
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Document newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Document newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Document query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Document whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Document whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Document whereCustomFields($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Document whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Document whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Document whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Document whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Document whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Document whereVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Document whereVerifiedBy($value)
 * @mixin \Eloquent
 * @property string $name
 * @property string|null $description
 * @property string $status
 * @property int $created_by
 * @property array|null $custom_fields
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\User $createdBy
 */
class Document extends Model
{

    public $table = 'documents';

    public $fillable = [
        'name',
        'description',
        'status',
        'created_by',
        'custom_fields',
        'verified_at',
        'verified_by'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'description' => 'string',
        'status' => 'string',
        'created_by' => 'integer',
        'verified_by' => 'integer',
        'custom_fields' => 'array'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        'description' => 'nullable',
        'tags' => 'required',
        'custom_fields' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function createdBy()
    {
        return $this->belongsTo(\App\User::class, 'created_by', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function verifiedBy()
    {
        return $this->belongsTo(\App\User::class, 'verified_by', 'id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class,'documents_tags','document_id','tag_id');
    }

    public function getIsVerifiedAttribute()
    {
        return !empty($this->verified_by) && !empty($this->verified_at) && $this->status==config('constants.STATUS.APPROVED');
    }

    public function files()
    {
        return $this->hasMany(File::class,'document_id', 'id');
    }

    public function activities()
    {
        return $this->hasMany(Activity::class,'document_id', 'id')
            ->orderByDesc('id');
    }

    public function newActivity($activity_text,$include_document=true){
        if($include_document){
            $activity_text .= " : ".'<a href="'.route('documents.show',$this->id).'">'.$this->name."</a>";
        }
        Activity::create([
            'activity' => $activity_text,
            'created_by' => \Auth::id(),
            'document_id' => $this->id,
        ]);
    }
}
