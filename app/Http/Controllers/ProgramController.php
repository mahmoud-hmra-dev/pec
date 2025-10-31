<?php

namespace App\Http\Controllers;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use App\Http\Requests\ProgramRequest;
use App\Http\Requests\SubProgramRequest;
use App\Models\Choice;
use App\Models\Client;
use App\Models\Drug;
use App\Models\Program;
use App\Models\ProgramCountry;
use App\Models\ProgramDrug;
use App\Models\Question;
use App\Models\QuestionCategory;
use App\Models\QuestionType;
use App\Models\ServiceProviderType;
use App\Models\ServiceType;
use App\Models\SubProgram;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use PhpOffice\PhpSpreadsheet\Calculation\Category;
use Yajra\DataTables\Facades\DataTables;

class ProgramController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:'.PermissionEnum::VIEW_PROGRAMS)->only('index');
        $this->middleware('permission:'.PermissionEnum::MANAGE_PROGRAMS)->except('index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View|JsonResponse
     * @throws Exception
     */
    public function index(Request $request)
    {
        $items = Program::with(['client.user','program_countries','program_countries.country','program_drugs','program_drugs.drug','service_provider_type.service_provider.user'])
            ->when(auth()->user()->hasRole(RoleEnum::CLIENT), function ($query) {
                return $query->where('client_id', auth()->user()->id);
            })
            ->when(auth()->user()->hasRole(RoleEnum::ProjectManager), function ($query) {
                $service_provider_type_id = optional(auth()->user()->service_provider)
                    ->service_provider_types()
                    ->whereHas('service_type', function($query){
                        $query->where('name', RoleEnum::ProjectManager);
                    })
                    ->value('id');
                return $query->where('service_provider_type_id', $service_provider_type_id);
            })
            ->when(auth()->user()->hasRole(RoleEnum::NURSE), function ($query) {
                $nurse_service_provider_type_id = optional(auth()->user()->service_provider)
                    ->service_provider_types()
                    ->whereHas('service_type', function ($query) {
                        $query->where('name', RoleEnum::NURSE);
                    })
                    ->value('id');

                $query->whereHas('sub_programs', function ($subQuery) use ($nurse_service_provider_type_id) {
                    $subQuery->whereHas('country_services_provider', function ($countryQuery) use ($nurse_service_provider_type_id){
                        $countryQuery->where('service_provider_type_id', $nurse_service_provider_type_id);
                        });
                    });
                return $query;
            })
            ->when(auth()->user()->hasRole(RoleEnum::ProgramCoordinator), function ($query) {
                $nurse_service_provider_type_id = optional(auth()->user()->service_provider)
                    ->service_provider_types()
                    ->whereHas('service_type', function ($query) {
                        $query->where('name', RoleEnum::ProgramCoordinator);
                    })
                    ->value('id');

                $query->whereHas('sub_programs', function ($subQuery) use ($nurse_service_provider_type_id) {
                    $subQuery->whereHas('country_services_provider', function ($countryQuery) use ($nurse_service_provider_type_id){
                        $countryQuery->where('service_provider_type_id', $nurse_service_provider_type_id);
                    });
                });
                return $query;
            })
            ->select('programs.*');

        $clients  = Client::all();

        $managers = ServiceProviderType::whereHas('service_type', function($query) {
            $query->where('name', 'Project Manager');
        })->get();

        if($request->ajax()){
            return  DataTables::eloquent($items)
                ->addColumn('action', function ($item) {
                    $actions = '';
                    if (auth()->user()->can(PermissionEnum::MANAGE_PROGRAMS)) {
                        $actions .= '<a class="edit btn btn-xs btn-primary mr-1" style="color:#fff" ><i class="mdi mdi-tooltip-edit"></i> Edit</a>';
                    }
                    if (auth()->user()->can(PermissionEnum::MANAGE_PROGRAMS)) {
                        $actions .= '<a class="btn btn-xs btn-info mr-1" style="color:#fff" href="'.route('programs.contacts.index', $item->id).'"><i class="mdi mdi-account-group"></i> Contacts</a>';
                        $actions .= '<a class="btn btn-xs btn-secondary mr-1" style="color:#fff" href="'.route('programs.form-fields.index', $item->id).'"><i class="mdi mdi-text-box"></i> Form Fields</a>';
                    }
                    if (auth()->user()->can(PermissionEnum::VIEW_SUBPROGRAMS)) {
                    $actions .= '<a class="btn btn-xs btn-success mr-1" style="color:#fff" href="'.route('programs.sub_programs.index', $item->id).'"><i class="mdi mdi-tooltip-edit"></i> Sub programs</a>';
                    }
                    if (auth()->user()->can(PermissionEnum::VIEW_Distributors)) {
                        $actions .= '<a class="btn btn-xs btn-success mr-1" style="color:#fff" href="'.route('programs.distributors.index', $item->id).'"><i class="mdi mdi-tooltip-edit"></i> Distributors</a>';
                    }
                    if (auth()->user()->can(PermissionEnum::VIEW_PROGRAMS)) {
                        $actions .= '<a class="view btn btn-xs btn-success mr-1" style="color:#fff" ><i class="mdi mdi-tooltip-edit"></i> View</a>';
                    }
                    return $actions;
                })
                ->addColumn('countries', function ($row) {
                    $countries = '';
                    foreach ($row->program_countries as $item) {
                        !$countries ? $countries = $item->country->name : $countries = $countries . ' | '. $item->country->name ;
                    }
                    return $countries;
                })
                ->addColumn('drugs', function ($row) {
                    $drugs = '';
                    foreach ($row->program_drugs as $item) {
                        !$drugs ? $drugs = $item->drug->name : $drugs = $drugs . ' | '. $item->drug->name ;
                    }
                    return $drugs;
                })
                ->rawColumns(['action','drugs','countries'])
                ->make(true);
        }

        return view('dashboard.programs.index',['managers'=>$managers,'clients'=>$clients]);
    }

    /**
     *
     * @return Application|Factory|View
     */
    public function create(){
        $clients  = Client::all();

        $managers = ServiceProviderType::whereHas('service_type', function($query) {
            $query->where('name', 'Project Manager');
        })->get();

        return view('dashboard.programs.create',compact(['clients','managers']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ProgramRequest $program_request
     * @param SubProgramRequest $sub_program_request
     * @return  RedirectResponse
     */
    public function store(ProgramRequest $request )
    {
        $program = new Program();
        $program->fill($request->validated());
        $program->save();
        if(!empty($request->drugs)) {
            foreach ($request->drugs as $key => $item) {
                $program->program_drugs()->create(['drug_id'=>$item]);
            }
        }
        if(!empty($request->program_countries)) {
            foreach ($request->program_countries as $key => $item) {
                $program->program_countries()->create(['country_id'=>$item]);
            }
        }


        return response()->json(['success' => true,'message'=>"Added Successfully"],200);
    }

    /**
     *
     * @return Application|Factory|View
     */
    public function edit($id){
        $program  = Program::with(['client','service_provider_type'])->findOrFail($id);
        $clients  = Client::all();
        $managers = ServiceProviderType::whereHas('service_type', function($query) {
            $query->where('name', 'Project Manager');
        })->get();

        return view('dashboard.programs.edit',compact(['program','clients','managers']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ProgramRequest $request
     * @param  int  $id
     * @return RedirectResponse
     */
    public function update(ProgramRequest $request, $id)
    {
        $program = Program::findOrFail($id);
        $program->update( $request->only([
            'name',
            'program_no',
            'client_id',
            'service_provider_type_id',
            'map_id',
            'started_at',
            'ended_at',
        ]));

        if(!empty($request->drugs)) {

            ProgramDrug::where('program_id', $program->id)
                ->whereNotIn('drug_id', $request->drugs)
                ->delete();

            foreach ($request->drugs as $key => $item) {
                $drug = ProgramDrug::where('program_id', $program->id)
                    ->where('drug_id', $item)->first();
                if(!$drug){
                    $program->program_drugs()->create(['drug_id'=>$item]);
                }
            }
        } else {
            ProgramDrug::where('program_id', $id)
                ->delete();
        }

        if(!empty($request->program_countries)) {
            ProgramCountry::where('program_id', $program->id)
                ->whereNotIn('country_id', $request->program_countries)
                ->delete();
            foreach ($request->program_countries as $key => $item) {
                $country = ProgramCountry::where('program_id', $program->id)
                    ->where('country_id', $item)->first();
                if(!$country){
                    $program->program_countries()->create(['country_id'=>$item]);
                }
            }
        } else {
            ProgramCountry::where('program_id', $id)
                ->delete();
        }
        return response()->json(['success' => true,'message'=>"Updated Successfully"],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $program = Program::with(['client'])->findOrFail($id);
        $program->delete();
        return response()->json(['message' => 'Successfully Deleted!']);
    }

    /**
     * return view of adding sub program by index and client id
     *
     * @param null $index
     * @param null $client_id
     * @return JsonResponse
     */
    public function addSubProgram($index = null , $client_id = null)
    {
        $drugs = Drug::where('client_id',$client_id)->get();
        $sub_program_view = view('dashboard.programs.partials.sub_program',compact(['index','drugs']))->render();
        return response()->json(['sub_program' => $sub_program_view]);
    }

    /**
     * return view of adding sub program by index and client id
     *
     * @param null $sub_program_index
     * @param null $question_index
     * @return JsonResponse
     */
    public function addQuestion($sub_program_index = null , $question_index = null)
    {
        $question_types = QuestionType::all();
        $question_categories = QuestionCategory::all();
        $question = view('dashboard.programs.partials.question',compact(['sub_program_index','question_index','question_types','question_categories']))->render();
        return response()->json(['question' => $question]);
    }


    public function drugs($client_id)
    {
        $drugs = Drug::where('client_id',$client_id)->with(['client','client.user'])->get();
        return response()->json(['drugs'=>$drugs],200);
    }
    public function program_drugs($program_id)
    {
        $program_drugs = ProgramDrug::where('program_id',$program_id)->get();
        return response()->json(['program_drugs'=>$program_drugs],200);
    }
}
