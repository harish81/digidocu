<?php

namespace App\Policies;

use App\Document;
use App\Tag;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DocumentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any documents.
     * @param User $user
     * @return bool
     * @throws \Exception
     */
    public function viewAny(User $user)
    {
        if ($user->can('read documents')) {
            return true;
        }

        //check read permission in any tags
        $tagsPermissions = Tag::selectRaw('CONCAT("read documents in tag ",id) as permissions')->pluck('permissions')->toArray();
        if ($user->hasAnyPermission($tagsPermissions)) {
            return true;
        }

        //check read permission in any documents
        $documentsPermission = Document::selectRaw('CONCAT("read document ",id) as permissions')->pluck('permissions')->toArray();
        if ($user->hasAnyPermission($documentsPermission)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the document.
     * @param User $user
     * @param Document $document
     * @return bool
     * @throws \Exception
     */
    public function view(User $user, Document $document)
    {
        if ($user->can('read documents')) {
            return true;
        }

        //check read permission in document
        if ($user->can("read document " . $document->id)) {
            return true;
        }

        //check read permission in any tag
        $tagPermissions = [];
        foreach ($document->tags as $tag) {
            $tagPermissions[] = 'read documents in tag ' . $tag->id;
        }
        if ($user->hasAnyPermission($tagPermissions)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create documents.
     * @param User $user
     * @return bool
     * @throws \Exception
     */
    public function create(User $user)
    {
        if ($user->can('create documents')) {
            return true;
        }

        //check create permission in any tag
        $tagsPermissions = Tag::selectRaw('CONCAT("create documents in tag ",id) as permissions')->pluck('permissions')->toArray();
        if ($user->hasAnyPermission($tagsPermissions)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can store documents.
     * @param User $user
     * @param $tags
     * @return bool
     * @throws \Exception
     */
    public function store(User $user, $tags)
    {
        if ($user->can('create documents')) {
            return true;
        }

        //check create permission in all tags
        $tagPermissions = [];
        foreach ($tags as $tag) {
            $tagPermissions[] = 'create documents in tag ' . $tag;
        }
        if ($user->hasAllPermissions($tagPermissions)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can edit the document.
     * @param User $user
     * @param Document $document
     * @return bool
     * @throws \Exception
     */
    public function edit(User $user, Document $document)
    {
        if ($user->can('update documents')) {
            return true;
        }

        //check update permission in document
        if ($user->can("update document " . $document->id)) {
            return true;
        }

        //check update permission in all tag
        $tagPermissions = [];
        foreach ($document->tags as $tag) {
            $tagPermissions[] = 'update documents in tag ' . $tag->id;
        }
        if ($user->hasAllPermissions($tagPermissions)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the document.
     * @param User $user
     * @param Document $document
     * @param $tags
     * @return bool
     * @throws \Exception
     */
    public function update(User $user, Document $document, $tags)
    {
        if ($user->can('update documents')) {
            return true;
        }

        //check update permission in document
        if ($user->can("update document " . $document->id)) {
            return true;
        }

        //check update permission in all tag
        $tagPermissions = [];
        foreach ($tags as $tag) {
            $tagPermissions[] = 'update documents in tag ' . $tag;
        }
        if ($user->hasAllPermissions($tagPermissions)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the document.
     *
     * @param \App\User $user
     * @param \App\Document $document
     * @return mixed
     * @throws \Exception
     */
    public function delete(User $user, Document $document)
    {

        if ($user->can('delete documents')) {
            return true;
        }

        //check update permission in document
        if ($user->can("delete document " . $document->id)) {
            return true;
        }

        //check update permission in all tag
        $tagPermissions = [];
        foreach ($document->tags as $tag) {
            $tagPermissions[] = 'delete documents in tag ' . $tag->id;
        }
        if ($user->hasAllPermissions($tagPermissions)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the document.
     *
     * @param \App\User $user
     * @param \App\Document $document
     * @return mixed
     * @throws \Exception
     */
    public function verify(User $user, Document $document)
    {
        if ($user->can('verify documents')) {
            return true;
        }

        //check verify permission in document
        if ($user->can("verify document " . $document->id)) {
            return true;
        }

        //check verify permission in all tag
        $tagPermissions = [];
        foreach ($document->tags as $tag) {
            $tagPermissions[] = 'verify documents in tag ' . $tag->id;
        }
        if ($user->hasAllPermissions($tagPermissions)) {
            return true;
        }

        return false;
    }
}
