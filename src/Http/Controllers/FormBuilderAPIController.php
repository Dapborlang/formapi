<?php

namespace Rdmarwein\FormApi\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Rdmarwein\FormApi\Models\FormMaster;
use Illuminate\Support\Facades\DB;
use Rdmarwein\FormApi\Models\FormInputType;
use Rdmarwein\FormApi\Models\FormDependantField;
use Rdmarwein\FormApi\Models\FormValidation;

class FormBuilderAPIController extends Controller
{
    public function __construct(Request $request)
    {
        $role=FormMaster::findOrFail($request->id);
        $this->middleware('auth');
        $this->middleware('credential:'.$role->role);
    }
    
    public function index($id)
    {
        $formMaster=FormMaster::findOrFail($id);
        $model=$formMaster->model;
        $data=$model::paginate(10);
        $itemData = [];
        $dataArray=[];
        $select=$formMaster->foreignKey->pluck('master_detail','foreign_key')->toArray();
        $excludedColumns = $formMaster->excludedColumn()->pluck('name')->toArray();
        array_push($excludedColumns,'id','created_at','updated_at');
        $columns = array_diff(DB::getSchemaBuilder()->getColumnListing($formMaster->table_name), $excludedColumns);
        foreach($data as $item)
        {
            $itemData = [];
            foreach($columns as $column)
            {
                if(array_key_exists($column,$select))
                {
                    $detail=$select[$column];
                    $camelCase = Ucfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $column))));
                    if (substr($camelCase, -2) === "Id") {
                        $camelCase = substr($camelCase, 0, -2);
                    }
                    if(isset($item->$camelCase->$detail))
                    {
                        $itemData[$column] =$item->$camelCase->$detail;
                    }
                    else{
                        $itemData[$column]="please map the relation";
                    }
                   
                }
                else
                {
                    $itemData[$column] = $item->$column;
                } 
                $itemData['id'] = $item->id;               
            }
            $dataArray[] = $itemData;
        }
        
        return [
            "header"=>$formMaster->header,
            "data"=>$dataArray,
            "pagination" => [
                "current_page" => $data->currentPage(),
                "last_page" => $data->lastPage(),
                "prev_page_url" => $data->previousPageUrl(),
                "next_page_url" => $data->nextPageUrl(),
                "from" => $data->firstItem(),
                "to" => $data->lastItem(),
                "total" => $data->total()
            ],
            "column"=>$columns
        ];
    }

    public function create($id)
    {
        $selectOption=[];
        $inputType=[];
        $dependant=[];
        $breadcrumb=[];

        $formMaster=FormMaster::findOrFail($id);
        $formInputType=FormInputType::where('form_master_id',$id)
                    ->get();
        foreach($formInputType as $item)
        {
            $inputType[$item->column_name]=$item->input_type;
        }

        $getDependant=$formMaster->formDependent;
        foreach($getDependant as $item)
        {
            $dependant[$item->masterKey->foreign_key]=$item->master_key;
        }

        $select=$formMaster->foreignKey->pluck('foreign_key')->toArray();
        foreach($formMaster->foreignKey as $item)
        {
            $model=$item->master_model;
            $data=$model::all();
            $master_id=$item->master_id;
            $master_detail=$item->master_detail;
            foreach($data as $item1)
            {
                if (substr($master_detail, -2) === '()') {
                    $master_detail=rtrim($master_detail, '()');
                    $text=$item1->$master_detail();
                } else {
                    $text=$item1->$master_detail;
                }
                $selectOption[$item->foreign_key][]=[
                    "value"=>$item1->$master_id,
                    "text"=>$item1->$master_id."-".$text    
                ];
            }
        }

        $excludedColumns = $formMaster->excludedColumn()->pluck('name')->toArray();
        array_push($excludedColumns,'id','created_at','updated_at');
        
        if(isset($formMaster->breadCrump->BreadCrumbDetail))
        {
            $breadcrumb=$formMaster->breadCrump->BreadCrumbDetail;
        }
        
        $columns = array_diff(DB::getSchemaBuilder()->getColumnListing($formMaster->table_name), $excludedColumns);
        return [
            "header"=>$formMaster->header,
            "columns"=>$columns,
            "select"=>$selectOption,
            "inputType"=>$inputType,
            "dependant"=>$dependant,
            "breadcrumb"=>$breadcrumb
        ];
    }
    
    public function store(Request $request,$id)
    {
        $formValidations = FormValidation::where('form_master_id', $id)->get();
        $validationData = [];
        $breadcrumb=[];
        foreach ($formValidations as $item) {
            if (!array_key_exists($item->column_name, $validationData)) {
                $validationData[$item->column_name] = $item->validation;
            } else {
                $validationData[$item->column_name] .= '|' . $item->validation;
            }
        }   
        $request->validate($validationData);
        
        $formMaster=FormMaster::findOrFail($id);
        $modelClass = $formMaster->model;
        $modelInstance = new $modelClass;
        $form_data=$modelInstance->create($request->all());

        if(isset($formMaster->breadCrump->BreadCrumbDetail))
        {
            
            $breadcrumb=$formMaster->breadCrump->BreadCrumbDetail;
            return [
                "breadcrumb"=>$breadcrumb,
                "form_master_id"=>5
            ];
        }
    }

    public function show(string $id)
    {
        //
    }

    public function edit($fid,$id)
    {
        $formMaster=FormMaster::findOrFail($fid);
        $model=$formMaster->model;
        $data=$model::findOrFail($id);
        return $data;
    }

    public function update($fid,$id,Request $request )
    {
        $formValidations = FormValidation::where('form_master_id', $fid)->get();
        $validationData = [];
        foreach ($formValidations as $item) {
            if (!array_key_exists($item->column_name, $validationData)) {
                $validationData[$item->column_name] = $item->validation;
            } else {
                $validationData[$item->column_name] .= '|' . $item->validation;
            }
        }   
        $request->validate($validationData);
        
        $formMaster=FormMaster::findOrFail($fid);
        $modelClass = $formMaster->model;
        $modelInstance = $modelClass::findOrFail($id);
        $modelInstance->fill($request->all());
        $modelInstance->save();
    }

    public function destroy($fid, $id)
    {
        $formMaster=FormMaster::findOrFail($fid);
        $model=$formMaster->model;
        $item = $model::find($id);
        if (!$item) {
            return response()->json(['message' => 'Item not found'], 404);
        }
        $item->delete();
        return response()->json(['message' => 'Item deleted successfully'], 200);
    }

    public function getDependant($id,Request $request)
    {
        $keys = $request->keys();
        $DependantField=FormDependantField::where('form_master_id',$id)
                    ->where('master_key',$keys[0])
                    ->get();
        foreach($DependantField as $formDependantField)
        {
            $formForeignKey=$formDependantField->childKey;
            $model=$formForeignKey->master_model;
            $key=$formDependantField->masterKey->foreign_key;
            $data=$model::where($key,$request->{$keys[0]})->get();
            
            $master_id=$formForeignKey->master_id;
            $master_detail=$formForeignKey->master_detail;

            foreach($data as $item1)
            {
                if (substr($master_detail, -2) === '()') {
                    $master_detail=rtrim($master_detail, '()');
                    $text=$item1->$master_detail();
                } else {
                    $text=$item1->$master_detail;
                }
                $selectOption[$formDependantField->childKey->foreign_key][]=[
                    "value"=>$item1->$master_id,
                    "text"=>$item1->$master_id."-".$text    
                ];
            }
        }
        return $selectOption;
    }

    public function checkBreadcrumb($id,$cur_id=null)
    {
        return $id;
    }
}
