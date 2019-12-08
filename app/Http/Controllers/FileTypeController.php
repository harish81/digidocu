<?php

namespace App\Http\Controllers;

use App\DataTables\FileTypeDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateFileTypeRequest;
use App\Http\Requests\UpdateFileTypeRequest;
use App\Repositories\FileTypeRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class FileTypeController extends AppBaseController
{
    /** @var  FileTypeRepository */
    private $fileTypeRepository;

    public function __construct(FileTypeRepository $fileTypeRepo)
    {
        $this->fileTypeRepository = $fileTypeRepo;
    }

    /**
     * Display a listing of the FileType.
     *
     * @param FileTypeDataTable $fileTypeDataTable
     * @return Response
     */
    public function index(FileTypeDataTable $fileTypeDataTable)
    {
        $this->isSuperAdmin();
        return $fileTypeDataTable->render('file_types.index');
    }

    /**
     * Show the form for creating a new FileType.
     *
     * @return Response
     */
    public function create()
    {
        $this->isSuperAdmin();
        return view('file_types.create');
    }

    /**
     * Store a newly created FileType in storage.
     *
     * @param CreateFileTypeRequest $request
     *
     * @return Response
     */
    public function store(CreateFileTypeRequest $request)
    {
        $this->isSuperAdmin();
        $input = $request->all();

        $fileType = $this->fileTypeRepository->create($input);

        Flash::success('File Type saved successfully.');

        return redirect(route('fileTypes.index'));
    }

    /**
     * Display the specified FileType.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $this->isSuperAdmin();
        $fileType = $this->fileTypeRepository->find($id);

        if (empty($fileType)) {
            Flash::error('File Type not found');

            return redirect(route('fileTypes.index'));
        }

        return view('file_types.show')->with('fileType', $fileType);
    }

    /**
     * Show the form for editing the specified FileType.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $this->isSuperAdmin();
        $fileType = $this->fileTypeRepository->find($id);

        if (empty($fileType)) {
            Flash::error('File Type not found');

            return redirect(route('fileTypes.index'));
        }

        return view('file_types.edit')->with('fileType', $fileType);
    }

    /**
     * Update the specified FileType in storage.
     *
     * @param  int              $id
     * @param UpdateFileTypeRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateFileTypeRequest $request)
    {
        $this->isSuperAdmin();
        $fileType = $this->fileTypeRepository->find($id);

        if (empty($fileType)) {
            Flash::error('File Type not found');

            return redirect(route('fileTypes.index'));
        }

        $fileType = $this->fileTypeRepository->update($request->all(), $id);

        Flash::success('File Type updated successfully.');

        return redirect(route('fileTypes.index'));
    }

    /**
     * Remove the specified FileType from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $this->isSuperAdmin();
        $fileType = $this->fileTypeRepository->find($id);

        if (empty($fileType)) {
            Flash::error('File Type not found');

            return redirect(route('fileTypes.index'));
        }

        $this->fileTypeRepository->delete($id);

        Flash::success('File Type deleted successfully.');

        return redirect(route('fileTypes.index'));
    }
}
