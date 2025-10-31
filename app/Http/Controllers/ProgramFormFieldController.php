<?php

namespace App\Http\Controllers;

use App\Enums\PermissionEnum;
use App\Http\Requests\ProgramFormFieldRequest;
use App\Models\Program;
use App\Models\ProgramFormField;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProgramFormFieldController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:' . PermissionEnum::VIEW_PROGRAMS)->only('index');
        $this->middleware('permission:' . PermissionEnum::MANAGE_PROGRAMS)->except('index');
    }

    public function index(Request $request, Program $program)
    {
        if ($request->ajax()) {
            $items = $program->formFields()->select('program_form_fields.*');

            return DataTables::eloquent($items)
                ->addColumn('action', function (ProgramFormField $field) {
                    $actions = '';
                    if (auth()->user()->can(PermissionEnum::MANAGE_PROGRAMS)) {
                        $actions .= '<a class="edit btn btn-xs btn-primary mr-1" style="color:#fff"><i class="mdi mdi-tooltip-edit"></i> Edit</a>';
                        $actions .= '<a class="delete btn btn-xs btn-dark" style="color:#fff"><i class="mdi mdi-delete"></i> Delete</a>';
                    }

                    return $actions;
                })
                ->make(true);
        }

        return view('dashboard.programs.form-fields.index', [
            'program' => $program,
        ]);
    }

    public function store(ProgramFormFieldRequest $request, Program $program): JsonResponse
    {
        $validated = $request->validated();
        $validated['field_key'] = strtolower($validated['field_key']);

        $field = $program->formFields()->create($validated);

        return response()->json([
            'success' => true,
            'message' => __('Custom field created successfully.'),
            'field' => $field,
        ]);
    }

    public function update(ProgramFormFieldRequest $request, Program $program, ProgramFormField $form_field): JsonResponse
    {
        $validated = $request->validated();
        $validated['field_key'] = strtolower($validated['field_key']);

        $form_field->update($validated);

        return response()->json([
            'success' => true,
            'message' => __('Custom field updated successfully.'),
        ]);
    }

    public function destroy(Program $program, ProgramFormField $form_field): JsonResponse
    {
        $form_field->delete();

        return response()->json(['message' => __('Field removed successfully.')]);
    }
}
