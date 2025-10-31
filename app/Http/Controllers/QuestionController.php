<?php

namespace App\Http\Controllers;

use App\Enums\PermissionEnum;
use App\Http\Requests\DistributorRequest;
use App\Http\Requests\QuestionRequest;
use App\Http\Requests\ServiceProviderTypeRequest;
use App\Http\Requests\SubProgramRequest;
use App\Http\Requests\UserRequest;
use App\Models\Choice;
use App\Models\Distributor;
use App\Models\Drug;
use App\Models\Patient;
use App\Models\Program;
use App\Models\Question;
use App\Models\QuestionCategory;
use App\Models\QuestionType;
use App\Models\ServiceProvider;
use App\Models\ServiceProviderType;
use App\Models\SubProgram;
use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Calculation\Category;
use Yajra\DataTables\Facades\DataTables;

class QuestionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:'.PermissionEnum::MANAGE_QUESTIONS)->except('index');
        $this->middleware('permission:'.PermissionEnum::VIEW_QUESTIONS)->only('index');
    }


    public function index(Request $request,$sub_program_id)
    {
        $items = Question::where('sub_program_id', $sub_program_id)->with(['sub_program', 'category', 'type','choices'])->select('questions.*');
        $question_types = QuestionType::all();
        $categories = QuestionCategory::all();

        if($request->ajax()){
            return  DataTables::eloquent($items)
                ->addColumn('action', function () {
                    $actions = '';
                    if (auth()->user()->can(PermissionEnum::MANAGE_QUESTIONS)) {
                        $actions .= '<a class="edit btn btn-xs btn-primary" style="color:#fff"><i class="mdi mdi-tooltip-edit"></i> Edit</a>  ';
                    }
                    return $actions;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('dashboard.programs.sub_programs.questions.index', ['sub_program_id'=>$sub_program_id,'question_types'=>$question_types,'categories'=>$categories]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserRequest $request
     * @return JsonResponse
     */
    public function store(QuestionRequest $request,$sub_program_id)
    {
        $question = Question::create([
            'question'=> $request->question,
            'question_type_id'=> $request->question_type_id,
            'category_id'=> $request->category_id,
            'sub_program_id'=> $sub_program_id,
        ]);

        if(!empty($request->choices)) {
            foreach ($request->choices as $item) {
                $question->choices()->create([
                    'choice' => $item['choice']
                ]);
            }
        }
        return response()->json(['success' => true,'message'=>"Added Successfully"],200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(QuestionRequest $request,$sub_program_id, $id)
    {
        $question = Question::findOrFail($id);
        $question->update([
            'question'=> $request->question,
            'question_type_id'=> $request->question_type_id,
            'category_id'=> $request->category_id,
            'sub_program_id'=> $sub_program_id,
        ]);


        if(!empty($request->choices)) {
            Choice::where('question_id', $question->id)
                ->whereNotIn('id', array_column($request->choices, 'id'))
                ->delete();
            foreach ($request->choices as $item) {
                if (isset($item['id'])) {
                Choice::where('id',$item['id'])->update([
                      'choice' => $item['choice'],
                  ]);
                } elseif(isset($item['choice']) and $item['choice'] != null) {
                    $question->choices()->create([
                        'choice' => $item['choice']
                    ]);
                }
            }
        }
        return response()->json(['success' => true,'message'=>"Updated Successfully"],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($sub_program_id, $id)
    {
        $model = Question::findOrFail($id);
        $model->delete();
        return response()->json(['message' => 'Successfully Deleted!']);
    }

}
