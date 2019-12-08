<?php

namespace App\Http\Controllers;

use App\DataTables\UserDataTable;
use App\Document;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Repositories\PermissionRepository;
use App\Repositories\UserRepository;
use App\Tag;
use App\User;
use Flash;
use Response;

class UserController extends AppBaseController
{
    /** @var  UserRepository */
    private $userRepository;

    /** @var PermissionRepository */
    private $permissionRepository;

    public function __construct(UserRepository $userRepo,
                                PermissionRepository $permissionRepository)
    {
        $this->userRepository = $userRepo;
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * Display a listing of the User.
     *
     * @param UserDataTable $userDataTable
     * @return Response
     */
    public function index(UserDataTable $userDataTable)
    {
        $this->authorize('viewAny', User::class);
        return $userDataTable->render('users.index');
    }

    /**
     * Show the form for creating a new User.
     *
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $tags = Tag::pluck('name', 'id');
        $this->authorize('create', User::class);
        return view('users.create', compact('tags'));
    }

    /**
     * Store a newly created User in storage.
     *
     * @param CreateUserRequest $request
     *
     * @return Response
     */
    public function store(CreateUserRequest $request)
    {
        $this->authorize('create', User::class);
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);

        /** @var User $user */
        $user = $this->userRepository->create($input);

        //give selected permission to user
        if (\Auth::user()->can('user manage permission')) {
            $this->permissionRepository->setPermissionsForUser($user, $this->mapInputToPermissions($input));
        }
        //end permission
        Flash::success('User saved successfully.');

        return redirect(route('users.index'));
    }

    private function mapInputToPermissions($input)
    {
        $permissions = $input['global_permissions'] ?? [];
        foreach ($input['tag_permissions'] ?? [] as $tag_permission) {
            foreach (config('constants.TAG_LEVEL_PERMISSIONS') as $perm_key => $perm) {
                if (isset($tag_permission[$perm]))
                    $permissions[] = $perm_key . $tag_permission['tag_id'];
            }
        }
        return $permissions;
    }

    /**
     * Display the specified User.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        abort_if($id == 1, 404);
        $user = $this->userRepository->find($id);
        $this->authorize('view', $user);

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('users.index'));
        }

        if (auth()->user()->can('user manage permission')) {
            $tmpTagIds = groupTagsPermissions($user->getAllPermissions());
            $tags = Tag::whereIn('id', array_column($tmpTagIds, 'tag_id'))->get();

            $tmpDocIds = groupDocumentsPermissions($user->getAllPermissions());

            $documents = Document::whereIn('id', array_column($tmpDocIds, 'doc_id'))->get();

            $globalPermissions = $this->permissionRepository->getGlobalPermissionsModelWiseForUser($user);
        }

        return view('users.show', compact('user', 'tags', 'documents','globalPermissions'));
    }

    /**
     * Show the form for editing the specified User.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        abort_if($id == 1, 404);
        $user = $this->userRepository->find($id);
        $this->authorize('update', $user);
        $tags = Tag::pluck('name', 'id');
        $user->password = "";

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('users.index'));
        }

        return view('users.edit')->with('user', $user)->with('tags', $tags);
    }

    /**
     * Update the specified User in storage.
     *
     * @param int $id
     * @param UpdateUserRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateUserRequest $request)
    {
        abort_if($id == 1, 404);
        $user = $this->userRepository->find($id);
        $this->authorize('update', $user);
        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('users.index'));
        }

        $data = $request->all();
        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = bcrypt($data['password']);
        }

        /** @var User $user */
        $user = $this->userRepository->update($data, $id);
        //give selected permission to users
        if (\Auth::user()->can('user manage permission')) {
            $permissions = $this->mapInputToPermissions($data);
            $docsPermissions = [];//also allocate doc level permissions.
            foreach (groupDocumentsPermissions($user->getAllPermissions()) as $perm) {
                foreach ($perm['permissions'] as $perm_val) {
                    $docsPermissions[] = $perm_val." document ".$perm['doc_id'];
                }
            }
            $user->syncPermissions(array_merge($permissions,$docsPermissions));
        }
        //end permission
        Flash::success('User updated successfully.');
        return redirect(route('users.index'));
    }

    /**
     * Remove the specified User from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        abort_if($id == 1, 404);
        /** @var User $user */
        $user = $this->userRepository->find($id);
        $this->authorize('delete', $user);
        //revoke all permission
        $user->syncPermissions();
        if (empty($user)) {
            Flash::error('User not found');
            return redirect(route('users.index'));
        }
        $this->userRepository->delete($id);
        Flash::success('User deleted successfully.');
        return redirect(route('users.index'));
    }

    public function blockUnblock(User $user)
    {
        $this->authorize('update', User::class);
        $user->status = $user->status == config('constants.STATUS.BLOCK') ?
            config('constants.STATUS.ACTIVE') : config('constants.STATUS.BLOCK');

        $user->save();

        Flash::success('User ' . strtolower($user->status) . " successfully.");

        return redirect()->route('users.index');
    }
}
