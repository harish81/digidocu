<?php

namespace App\Http\Controllers;

use App\CustomField;
use App\DataTables\TagDataTable;
use App\Http\Requests\CreateTagRequest;
use App\Http\Requests\UpdateTagRequest;
use App\Repositories\PermissionRepository;
use App\Repositories\TagRepository;
use App\Tag;
use App\User;
use Flash;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Response;
use Spatie\Permission\Models\Permission;

class TagController extends AppBaseController
{
    /** @var  TagRepository */
    private $tagRepository;
    /** @var PermissionRepository */
    private $permissionRepository;

    public function __construct(TagRepository $tagRepo,
                                PermissionRepository $permissionRepository)
    {
        $this->tagRepository = $tagRepo;
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * Display a listing of the Tag.
     *
     * @param TagDataTable $tagDataTable
     * @return Response
     */
    public function index(TagDataTable $tagDataTable)
    {
        $this->authorize('viewAny', Tag::class);
        return $tagDataTable->render('tags.index');
    }

    /**
     * Show the form for creating a new Tag.
     *
     * @return Response
     */
    public function create()
    {
        $this->authorize('create', Tag::class);
        $customFields = CustomField::where('model_type', 'tags')->get();
        return view('tags.create', compact('customFields'));
    }

    /**
     * Store a newly created Tag in storage.
     *
     * @param CreateTagRequest $request
     *
     * @return Response
     */
    public function store(CreateTagRequest $request)
    {
        $this->authorize('create', Tag::class);
        $input = $request->all();
        $input['created_by'] = Auth::id();

        $tag = $this->tagRepository->create($input);

        //create permissions for new tag
        foreach (config('constants.TAG_LEVEL_PERMISSIONS') as $perm_key => $perm) {
            Permission::create(['name' => $perm_key . $tag->id]);
        }

        Flash::success(ucfirst(config('settings.tags_label_singular')) . " saved successfully.");

        return redirect(route('tags.index'));
    }

    /**
     * Display the specified Tag.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $tag = $this->tagRepository->find($id);
        $this->authorize('view', $tag);

        if (empty($tag)) {
            Flash::error(ucfirst(config('settings.tags_label_singular')) . ' not found');

            return redirect(route('tags.index'));
        }
        if (auth()->user()->can('user manage permission')) {
            //Tag wise permission
            $tagWisePermList = $this->permissionRepository->getUserWisePermissionsByTag($tag);

            //Global Permission
            $globalPermissionUsers = User::permission(array_keys(config('constants.GLOBAL_PERMISSIONS.DOCUMENTS')))->get();
        }

        return view('tags.show', compact('tag', 'tagWisePermList', 'globalPermissionUsers'));
    }

    /**
     * Show the form for editing the specified Tag.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $tag = $this->tagRepository->find($id);
        $this->authorize('update', $tag);
        $customFields = CustomField::where('model_type', 'tags')->get();

        if (empty($tag)) {
            Flash::error(ucfirst(config('settings.tags_label_singular')) . ' not found');

            return redirect(route('tags.index'));
        }

        return view('tags.edit')->with('tag', $tag)->with('customFields', $customFields);
    }

    /**
     * Update the specified Tag in storage.
     *
     * @param int $id
     * @param UpdateTagRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTagRequest $request)
    {
        $tag = $this->tagRepository->find($id);
        $this->authorize('update', $tag);

        if (empty($tag)) {
            Flash::error(ucfirst(config('settings.tags_label_singular')) . ' not found');

            return redirect(route('tags.index'));
        }

        $tag = $this->tagRepository->update($request->all(), $id);

        Flash::success(ucfirst(config('settings.tags_label_singular')) . ' updated successfully.');

        return redirect(route('tags.index'));
    }

    /**
     * Remove the specified Tag from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $tag = $this->tagRepository->find($id);
        $this->authorize('delete', $tag);

        if (empty($tag)) {
            Flash::error(ucfirst(config('settings.tags_label_singular')) . ' not found');
            return redirect(route('tags.index'));
        }
        try {
            $this->tagRepository->deleteWithPermissions($tag);
        } catch (QueryException $e) {
            Flash::error('This ' . config('settings.tags_label_singular') . ' has ' . config('settings.document_label_plural') . '. Please delete ' . config('settings.document_label_plural') . ' and try again later.');
            return redirect(route('tags.index'));
        }

        Flash::success(ucfirst(config('settings.tags_label_singular')) . ' deleted successfully.');
        return redirect(route('tags.index'));
    }
}
