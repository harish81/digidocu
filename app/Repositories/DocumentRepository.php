<?php


namespace App\Repositories;


use App\Document;
use App\FileType;
use App\User;
use Illuminate\Container\Container as Application;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;

class DocumentRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'status',
        'description',
        'custom_fields'
    ];

    /** @var PermissionRepository $permissionRepository */
    private $permissionRepository;

    public function __construct(Application $app,PermissionRepository $permissionRepository)
    {
        parent::__construct($app);
        $this->permissionRepository = $permissionRepository;
    }


    /**
     * Get searchable fields array
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     *
     * @return string
     */
    public function model()
    {
        return Document::class;
    }


    /**
     * Search or get all documents with pagination
     * @param null $search
     * @param array $tag
     * @param null $status
     * @param bool $paginate
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|Collection|array
     */
    public function searchDocuments($search=null,$tag=[],$status=null,$paginate=true)
    {
        $query = $this->allQuery($search);
        if(!empty($tag)){
            $query->whereHas('tags', function ($q) use ($tag) {
                $q->whereIn('tag_id', $tag);
            });
        }
        if(!empty($status)){
            $query->where('status',$status);
        }
        $query = $query->with('tags');
        debug($query->toSql());
        if($paginate)
            return $query->paginate(25);
        else
            return $query->get();
    }

    public function createWithTags($data)
    {
        $document = $this->create($data);
        $document->tags()->attach($data['tags']);
        return $document;
    }

    public function updateWithTags($data,$document)
    {
        $document->update($data);
        $document->tags()->sync($data['tags']);
    }

    public function deleteWithFiles($document,$withPermissions=false)
    {
        //delete file from storage
        $varients = explode(",",config('settings.image_files_resize'));
        $varients[] = 'original';
        $varients[] = 'thumb';
        foreach ($document->files as $file) {
            foreach ($varients as $varient) {
                Storage::delete("files/$varient/$file->file");
            }
        }
        $document->delete();
        if($withPermissions){
            $permissions = [];
            foreach (config('constants.DOCUMENT_LEVEL_PERMISSIONS') as $perm_key => $perm) {
                $permissions[] = $perm_key . $document->id;
            }
            /** @var User $user */
            $users=User::permission($permissions)->get();
            foreach ($users as $user){
                $this->permissionRepository->deleteDocumentLevelPermissionForUser($document,$user);
            }
            Permission::whereIn('name', $permissions)->delete();
        }
    }

    public function getOneEagerLoaded($id, $relations = [])
    {
        $query = $this->allQuery();
        if(!empty($relations)){
            $query = $query->with($relations);
        }
        return $query->where('id',$id)->first();
    }

    public function buildMissingDocErrors($document)
    {
        $missigDocMsgs = [];
        if (config('settings.show_missing_files_errors') == 'true' && $document->status != config('constants.STATUS.APPROVED')) {
            $fileTypes = FileType::all();
            /** @var FileType $fileType */
            foreach ($fileTypes as $fileType) {
                $count = $fileType->no_of_files;
                $allFiles = $document->files->where('file_type_id', $fileType->id);
                if ($allFiles->count() <= $count) {
                    for ($i = $allFiles->count(); $i < $count; $i++) {
                        $labels = explode(",", $fileType->labels)[$i];
                        $missigDocMsgs[] = $fileType->name . " " . $labels;
                    }
                }
            }
        }
        return $missigDocMsgs;
    }

    public function approveDoc($document)
    {
        $document->verified_by = Auth::id();
        $document->verified_at = now();
        $document->status = config('constants.STATUS.APPROVED');
        $document->save();
    }

    public function rejectDoc($document)
    {
        $document->status = config('constants.STATUS.REJECT');
        $document->save();
    }

    public function saveFilesWithDoc($filesData,$document)
    {
        $document->updated_at = now();
        $document->status = config('constants.STATUS.PENDING');
        $document->save();
        foreach ($filesData as $key=>$filesDatum) {
            $filesData[$key]['document_id'] = $document->id;
        }
        $document->files()->insert($filesData);
    }

    public function deleteFile($file)
    {
        //delete file from storage
        $varients = explode(",",config('settings.image_files_resize'));
        $varients[] = 'original';
        $varients[] = 'thumb';
        foreach ($varients as $varient) {
            Storage::delete("files/$varient/$file->file");
        }
        $file->delete();
    }
}
