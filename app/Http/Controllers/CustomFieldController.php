<?php

namespace App\Http\Controllers;

use App\DataTables\CustomFieldDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateCustomFieldRequest;
use App\Http\Requests\UpdateCustomFieldRequest;
use App\Repositories\CustomFieldRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class CustomFieldController extends AppBaseController
{
    /** @var  CustomFieldRepository */
    private $customFieldRepository;

    public function __construct(CustomFieldRepository $customFieldRepo)
    {
        $this->customFieldRepository = $customFieldRepo;
    }

    /**
     * Display a listing of the CustomField.
     *
     * @param CustomFieldDataTable $customFieldDataTable
     * @return Response
     */
    public function index(CustomFieldDataTable $customFieldDataTable)
    {
        $this->isSuperAdmin();
        return $customFieldDataTable->render('custom_fields.index');
    }

    /**
     * Show the form for creating a new CustomField.
     *
     * @return Response
     */
    public function create()
    {
        $this->isSuperAdmin();
        return view('custom_fields.create');
    }

    /**
     * Store a newly created CustomField in storage.
     *
     * @param CreateCustomFieldRequest $request
     *
     * @return Response
     */
    public function store(CreateCustomFieldRequest $request)
    {
        $this->isSuperAdmin();
        $input = $request->all();
        $input['suggestions'] = explode(",", $input['suggestions']);

        $customField = $this->customFieldRepository->create($input);

        Flash::success('Custom Field saved successfully.');

        return redirect(route('customFields.index'));
    }

    /**
     * Display the specified CustomField.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $this->isSuperAdmin();
        $customField = $this->customFieldRepository->find($id);

        if (empty($customField)) {
            Flash::error('Custom Field not found');

            return redirect(route('customFields.index'));
        }

        return view('custom_fields.show')->with('customField', $customField);
    }

    /**
     * Show the form for editing the specified CustomField.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $this->isSuperAdmin();
        $customField = $this->customFieldRepository->find($id);

        if (empty($customField)) {
            Flash::error('Custom Field not found');

            return redirect(route('customFields.index'));
        }

        return view('custom_fields.edit')->with('customField', $customField);
    }

    /**
     * Update the specified CustomField in storage.
     *
     * @param  int              $id
     * @param UpdateCustomFieldRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCustomFieldRequest $request)
    {
        $this->isSuperAdmin();
        $customField = $this->customFieldRepository->find($id);

        if (empty($customField)) {
            Flash::error('Custom Field not found');

            return redirect(route('customFields.index'));
        }

        $input = $request->all();
        $input['suggestions'] = explode(",", $input['suggestions']);
        $customField = $this->customFieldRepository->update($input, $id);

        Flash::success('Custom Field updated successfully.');

        return redirect(route('customFields.index'));
    }

    /**
     * Remove the specified CustomField from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $this->isSuperAdmin();
        $customField = $this->customFieldRepository->find($id);

        if (empty($customField)) {
            Flash::error('Custom Field not found');

            return redirect(route('customFields.index'));
        }

        $this->customFieldRepository->delete($id);

        Flash::success('Custom Field deleted successfully.');

        return redirect(route('customFields.index'));
    }
}
