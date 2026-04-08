<?php

namespace Isotope\Metronic\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BaseApiController extends Controller
{
    protected $model;
    protected $validationRules   = [];
    protected $perPage           = 15;
    protected $filterableColumns = [];
    protected $recordName        = 'Record';
    protected $primaryKey        = 'id'; // Added primary key property
    
    protected $successMessages = [
        'store'   => 'isotope::isotope.record_created',
        'update'  => 'isotope::isotope.record_updated',
        'destroy' => 'isotope::isotope.record_deleted',
    ];

    protected $errorMessages = [
        'notFound' => 'isotope::isotope.record_not_found',
    ];

    public function index(Request $request)
    {
        try {
            $query = $this->model::query();
            foreach ($request->all() as $key => $value) {
                if (in_array($key, $this->filterableColumns) && $value) {
                    $query->where($key, 'like', "%$value%");
                }
            }
            $this->modifyIndexQuery($query);
            $perPage = $this->perPage;
            if ($perPage == -1) {
                $records = $query->get();
                return response()->json($this->getIndexData($records));
            }
            $records = $query->paginate($perPage);
            return response()->json($this->getIndexData($records));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate($this->getValidationRules());
            $data = $this->beforeStore($request->all());
            $record = $this->model::create($data);
            $this->afterStore($record);
            return $this->responseAfterStore($record);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $record = $this->findRecordByKey($id);
            $this->beforeShow($record);
            return response()->json($this->getShowData($record));
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => trans($this->errorMessages['notFound'], ['record' => $this->recordName])], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate($this->getValidationRules($id));
            $record = $this->findRecordByKey($id);
            $data = $this->beforeUpdate($request->all(), $record);
            $record->update($data);
            $this->afterUpdate($record);
            return $this->responseAfterUpdate($record);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => trans($this->errorMessages['notFound'], ['record' => $this->recordName])], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $record = $this->findRecordByKey($id);
            $this->beforeDestroy($record);
            $record->delete();
            $this->afterDestroy($record);
            return response()->json(['message' => trans($this->successMessages['destroy'], ['record' => $this->recordName])]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => trans($this->errorMessages['notFound'], ['record' => $this->recordName])], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    protected function modifyIndexQuery($query) {}
    protected function beforeStore(array $data)
    {
        return $data;
    }
    protected function afterStore($record) {}
    protected function beforeShow($record) {}
    protected function beforeUpdate(array $data, $record)
    {
        return $data;
    }
    protected function afterUpdate($record) {}
    protected function beforeDestroy($record) {}
    protected function afterDestroy($record) {}

    protected function findRecordByKey($value)
    {
        $record = $this->model::where($this->primaryKey, $value)->first();
        if (!$record) {
            throw new Exception(trans($this->errorMessages['notFound'], ['record' => $this->recordName]));
        }
        return $record;
    }

    protected function getValidationRules($id = null)
    {
        return array_map(function ($rule) use ($id) {
            if (is_string($rule) && str_contains($rule, 'unique') && $id) {
                if ($this->primaryKey !== 'id') {
                    return preg_replace('/unique:([a-z_]+),([a-z_]+)/', "unique:$1,$2," . $id . "," . $this->primaryKey, $rule);
                }
                return preg_replace('/unique:([a-z_]+),([a-z_]+)/', "unique:$1,$2,$id", $rule);
            }
            return $rule;
        }, $this->validationRules);
    }
    
    protected function getIndexData($records)
    {
        return $records;
    }
    
    protected function getShowData($record)
    {
        return $record;
    }
    
    protected function responseAfterStore($record)
    {
        return response()->json([
            'message' => trans($this->successMessages['store'], ['record' => $this->recordName]),
            'record' => $record
        ], 201);
    }
    
    protected function responseAfterUpdate($record)
    {
        return response()->json([
            'message' => trans($this->successMessages['update'], ['record' => $this->recordName]),
            'record' => $record
        ]);
    }
}
