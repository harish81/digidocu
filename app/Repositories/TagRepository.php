<?php

namespace App\Repositories;

use App\Tag;
use App\Repositories\BaseRepository;
use App\User;
use Spatie\Permission\Models\Permission;

/**
 * Class TagRepository
 * @package App\Repositories
 * @version November 12, 2019, 3:59 pm IST
*/

class TagRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'color'
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
        return Tag::class;
    }

    public function deleteWithPermissions($tag)
    {
        $this->delete($tag->id);
        //delete tag permissions
        $permissions = [];
        foreach (config('constants.TAG_LEVEL_PERMISSIONS') as $perm_key => $perm) {
            $permissions[] = $perm_key . $tag->id;
        }

        /** @var User $user */
        $users=User::permission($permissions)->get();
        foreach ($users as $user){
            $user->revokePermissionTo($permissions);
        }
        Permission::whereIn('name', $permissions)->delete();
    }
}
