<?php

namespace App\Http\Controllers;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use App\Http\Requests\ClientRequest;
use App\Models\Client;
use App\Models\ClientDocument;
use App\Models\Document;
use App\Models\DocumentType;
use App\Models\User;
use App\Traits\FileHandler;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;


class ClientController extends Controller
{


    public function __construct()
    {
        $this->middleware('permission:'.PermissionEnum::VIEW_CLIENTS)->only('index');
        $this->middleware('permission:'.PermissionEnum::MANAGE_CLIENTS)->except('index');
    }

    use FileHandler;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $items = Client::with(['user','user.country','documents','documents.type'])->select('clients.*');
        if($request->ajax()){
            return  DataTables::eloquent($items)
                ->addColumn('action', function ($item) {
                    $actions = '';
                    if (auth()->user()->can(PermissionEnum::MANAGE_CLIENTS)) {
                        $actions .= ' <a class="edit btn btn-xs btn-primary mr-1" style="color:#fff" ><i class="mdi mdi-tooltip-edit"></i> Edit</a>';
                    }
                    if (auth()->user()->can(PermissionEnum::VIEW_CLIENTS)) {
                        $actions .= '<a class="view btn btn-xs btn-success mr-1" style="color:#fff" ><i class="mdi mdi-tooltip-edit"></i> View</a>  <a class="btn btn-xs btn-success mr-1" style="color:#fff" href="'.route('clients.contacts.index', $item->id).'"><i class="mdi mdi-tooltip-edit"></i>Contacts</a>';
                    }
                    return $actions;
                })
                ->make(true);
        }
        return view('dashboard.clients.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ClientRequest $request)
    {
        $user = new User();

        $user->fill(array_merge($request->only(
            ['phone',
            'email',
            'country_id',
            'city',]
        ), ['first_name'=>$request->client_name,
                'last_name'=>$request->client_name,
                'password'=>'password']
        ));
        $user->save();
        $model = new Client();
        $model->fill(array_merge($request->only([
            'client_name',
            'client_address',
        ]),
            ['id'=>$user->id]));

        if ($request->hasFile('safety_report_document')) {
            $model->safety_report_document =  $this->storeFile($request->file('safety_report_document'), 'documents', false);
        }

        $model->save();

        if(!empty($request->documents)) {
            foreach ($request->documents as $key => $item) {
                if ($request->hasFile('documents.'.$key.'.name')) {
                    $documentPath = $this->storeFile($request->file('documents.'.$key.'.name'), 'documents', false);
                    $model->documents()->create([
                        'document_type_id' => $item['document_type_id'],
                        'name'  => $documentPath,
                        'description' => $item['description'],
                    ]);
                }
            }
        }

        $user->assignRole(RoleEnum::CLIENT);
        return response()->json(['success' => true,'message'=>"Added Successfully"],200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(ClientRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update(array_merge($request->only(
            ['phone',
                'email',
                'country_id',
                'city',]
        ), ['first_name'=>$request->client_name,
                'last_name'=>$request->client_name,
                'password'=> 'password',]
        ));

        $client = Client::find($id);

        $documentPath = null;
        if ($request->hasFile('safety_report_document')) {
            $documentPath = $this->updateFile($request->file('safety_report_document'),$client->name,'documents',false);
        }

        $client->update(array_merge($request->only(
            [ 'client_name',
                'client_address',]
        ), ['safety_report_document'=>$documentPath ?? $client->safety_report_document,]
        ));


        if(!empty($request->documents)) {
            ClientDocument::where('client_id', $client->id)
                ->whereNotIn('id', array_column($request->documents, 'id'))
                ->delete();
            foreach ($request->documents as $key => $item) {
                if (isset($item['id']) and $request->hasFile('documents.'.$key.'.name')) {
                    $model = ClientDocument::where('id',$item['id'])->first();
                    $documentPath = $this->updateFile($request->file('documents.'.$key.'.name'),$model->name,'documents',false);
                    $model->update([
                        'document_type_id' => $item['document_type_id'],
                        'name'  => $documentPath,
                        'description' => $item['description'],
                    ]);
                } elseif( isset($item['document_type_id']) and isset($item['name']) and $item['document_type_id'] != null and  $item['name'] != null and $request->hasFile('documents.'.$key.'.name')) {
                    $documentPath = $this->storeFile($request->file('documents.'.$key.'.name'), 'documents', false);
                    $client->documents()->create([
                        'document_type_id' => $item['document_type_id'],
                        'name'  => $documentPath,
                        'description' => isset($item['description']) ? $item['description'] :null ,
                    ]);
                }
            }
        } else {
            ClientDocument::where('client_id', $client->id)->delete();
        }

        return response()->json(['success' => true,'message'=>"Added Successfully"],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = Client::findOrFail($id);
        $model->delete();
        return response()->json(['message' => 'Successfully Deleted!']);
    }
}
