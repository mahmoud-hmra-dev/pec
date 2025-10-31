<?php

namespace App\Http\Controllers;

use App\Enums\PermissionEnum;
use App\Http\Requests\ProgramContactRequest;
use App\Models\Program;
use App\Models\ProgramContact;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class ProgramContactController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:' . PermissionEnum::VIEW_PROGRAMS)->only('index');
        $this->middleware('permission:' . PermissionEnum::MANAGE_PROGRAMS)->except('index');
    }

    public function index(Request $request, Program $program)
    {
        if ($request->ajax()) {
            $items = $program->contacts()->select('program_contacts.*');

            return DataTables::eloquent($items)
                ->addColumn('resolved_title', fn (ProgramContact $contact) => $contact->resolved_title)
                ->addColumn('action', function (ProgramContact $contact) {
                    $actions = '';
                    if (auth()->user()->can(PermissionEnum::MANAGE_PROGRAMS)) {
                        $actions .= '<a class="edit btn btn-xs btn-primary mr-1" style="color:#fff"><i class="mdi mdi-tooltip-edit"></i> Edit</a>';
                        $actions .= '<a class="delete btn btn-xs btn-dark" style="color:#fff"><i class="mdi mdi-delete"></i> Delete</a>';
                    }

                    return $actions;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('dashboard.programs.contacts.index', [
            'program' => $program,
        ]);
    }

    public function store(ProgramContactRequest $request, Program $program): JsonResponse
    {
        $contact = $program->contacts()->create($request->validated());

        return response()->json([
            'success' => true,
            'message' => __('Program contact added successfully.'),
            'contact' => $contact,
        ]);
    }

    public function update(ProgramContactRequest $request, Program $program, ProgramContact $contact): JsonResponse
    {
        $contact->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => __('Program contact updated successfully.'),
        ]);
    }

    public function destroy(Program $program, ProgramContact $contact): JsonResponse
    {
        $contact->delete();

        return response()->json(['message' => __('Contact removed successfully.')]);
    }
}
