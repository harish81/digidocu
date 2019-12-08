<?php


namespace App\Repositories;


use App\Document;
use App\User;
use Spatie\Permission\Models\Permission;

class PermissionRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'name'
    ];

    /**
     * Get TagWise Users Permission for given document
     * @param Document $document
     * @return array
     */
    public function getTagWiseUsersPermissionsForDoc($document)
    {
        $tagWisePermList = [];
        foreach ($document->tags as $tag) {
            foreach (config('constants.TAG_LEVEL_PERMISSIONS') as $perm_key => $perm) {
                $usersTagWise = User::permission([$perm_key . $tag->id])->get();
                foreach ($usersTagWise as $item) {
                    if (isset($tagWisePermList[$tag->id . '_' . $item->id])) {
                        $tagWisePermList[$tag->id . '_' . $item->id]['permissions'][] = $perm;
                    } else {
                        $tagWisePermList[$tag->id . '_' . $item->id] = [
                            'tag' => $tag,
                            'user' => $item,
                            'permissions' => [$perm]
                        ];
                    }
                }
            }
        }
        return $tagWisePermList;
    }

    /**
     * Get Users wise document level permissions for given document
     * @param Document $document
     */
    public function getUsersWiseDocumentLevelPermissionsForDoc($document)
    {
        $thisDocsPermList = [];
        foreach (config('constants.DOCUMENT_LEVEL_PERMISSIONS') as $key => $item) {
            $thisDocsPermList[] = $key . $document->id;
        }
        $retPermissions = [];
        $users = User::permission($thisDocsPermList)->get();
        foreach ($users as $user) {
            $retPermissions[] = [
                'user'=>$user,
                'permissions'=>[]
            ];
            foreach (config('constants.DOCUMENT_LEVEL_PERMISSIONS') as $perm_key => $value) {
                if ($user->can($perm_key.$document->id)) {
                    $retPermissions[count($retPermissions)-1]['permissions'][] = $value;
                }
            }
        }
        return $retPermissions;
    }

    /**
     * Get global permission user wise for given document.
     * @param Document $document
     * @return array
     */
    public function getGlobalPermissionsForDoc($document)
    {
        $users = User::permission(array_keys(config('constants.GLOBAL_PERMISSIONS.DOCUMENTS')))->get();
        $retPermissions = [];
        foreach ($users as $user) {
            $retPermissions[] = [
                'user'=>$user,
                'permissions'=>[]
            ];
            foreach (config('constants.GLOBAL_PERMISSIONS.DOCUMENTS') as $key=>$item) {
                if($user->can($key)){
                    $retPermissions[count($retPermissions)-1]['permissions'][] = $item;
                }
            }
        }
        return $retPermissions;
    }

    public function getUserWisePermissionsByTag($tag)
    {
        $tagWisePermList = [];
        foreach (config('constants.TAG_LEVEL_PERMISSIONS') as $perm_key => $perm) {
            $usersTagWise = User::permission([$perm_key . $tag->id])->get();
            foreach ($usersTagWise as $item) {
                if (isset($tagWisePermList[$tag->id . '_' . $item->id])) {
                    $tagWisePermList[$tag->id . '_' . $item->id]['permissions'][] = $perm;
                } else {
                    $tagWisePermList[$tag->id . '_' . $item->id] = [
                        'tag' => $tag,
                        'user' => $item,
                        'permissions' => [$perm]
                    ];
                }
            }
        }
        return $tagWisePermList;
    }

    public function setDocumentLevelPermissionForUser($user, $document, $doc_permissions)
    {
        $permissions = [];
        $tags = $document->tags->pluck('id');
        foreach (config('constants.DOCUMENT_LEVEL_PERMISSIONS') as $perm_key => $perm) {
            if (isset($doc_permissions[$perm])) {
                $flag = false;
                foreach ($tags as $tag) {
                    if ($user->hasAnyPermission([$perm . ' documents in tag ' . $tag, $perm . " documents"])) {
                        $flag = true;
                    }
                }
                if (!$flag) {
                    $permissions[] = $perm_key . $document->id;
                }
            }
        }

        if ($permissions) {
            $user->givePermissionTo($permissions);
        }
    }

    public function getGlobalPermissionsModelWiseForUser($user)
    {
        $permissions = [];
        $permissions['Users']=[];
        foreach (config('constants.GLOBAL_PERMISSIONS.USERS') as $perm_key => $perm) {
            if($user->can($perm_key)){
                $permissions['Users']['permissions'][] = $perm;
            }
        }

        $permissions[ucfirst(config('settings.tags_label_plural'))]=[];
        foreach (config('constants.GLOBAL_PERMISSIONS.TAGS') as $perm_key => $perm) {
            if($user->can($perm_key)){
                $permissions[ucfirst(config('settings.tags_label_plural'))]['permissions'][]
                    = $perm;
            }
        }

        $permissions[ucfirst(config('settings.document_label_plural'))]=[];
        foreach (config('constants.GLOBAL_PERMISSIONS.DOCUMENTS') as $perm_key => $perm) {
            if($user->can($perm_key)){
                $permissions[ucfirst(config('settings.document_label_plural'))]['permissions'][]
                    = $perm;
            }
        }

        return $permissions;
    }

    public function setPermissionsForUser($user, $permissions)
    {
        if (!empty($permissions))
            $user->givePermissionTo($permissions);
    }

    public function deleteDocumentLevelPermissionForUser($document, $user)
    {
        $permissions = [];
        foreach (config('constants.DOCUMENT_LEVEL_PERMISSIONS') as $perm_key => $perm) {
            if ($user->can($perm_key . $document->id)) {
                $permissions[] = $perm_key . $document->id;
            }
        }
        $user->revokePermissionTo($permissions);
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
        return Permission::class;
    }
}
