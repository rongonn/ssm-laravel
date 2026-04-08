<?php

namespace Isotope\Metronic\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class BaseController extends Controller
{
    protected $model;
    protected $validationRules   = [];
    protected $perPage           = 15;
    protected $filterableColumns = [];

    protected $indexView  = 'index';
    protected $createView = 'create';
    protected $editView   = 'edit';
    protected $showView   = 'show';

    protected $routePrefix = '';
    protected $viewPrefix  = '';
    protected $recordName  = 'Record';

    protected $redirects = [
        'index_error'             => null,
        'create_error'            => null,
        'store_success'           => null,
        'store_validation_error'  => null,
        'store_query_error'       => null,
        'store_error'             => null,
        'show_not_found'          => null,
        'show_query_error'        => null,
        'show_error'              => null,
        'edit_not_found'          => null,
        'edit_query_error'        => null,
        'edit_error'              => null,
        'update_success'          => null,
        'update_validation_error' => null,
        'update_not_found'        => null,
        'update_query_error'      => null,
        'update_error'            => null,
        'destroy_success'         => null,
        'destroy_not_found'       => null,
        'destroy_query_error'     => null,
        'destroy_error'           => null
    ];

    protected $successMessages = [
        'store'   => 'isotope::isotope.record_created',
        'update'  => 'isotope::isotope.record_updated',
        'destroy' => 'isotope::isotope.record_deleted',
    ];

    protected $errorMessages = [
        'notFound' => 'isotope::isotope.record_not_found',
    ];

    // Add this helper method for redirects
    protected function getRedirect($key, $default, $params = [])
    {
        if (isset($this->redirects[$key]) && $this->redirects[$key] !== null) {
            if ($this->redirects[$key] === 'back') {
                return redirect()->back();
            } else {
                $routeName = $this->redirects[$key];
                return redirect()->route(tenant() ? $routeName : 'owner.' . $routeName, $params);
            }
        }

        if ($default === 'back') {
            return redirect()->back();
        } else {
            $routeName = $this->routePath($default);
            return redirect()->route(tenant() ? $routeName : 'owner.' . $routeName, $params);
        }
    }

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
            $records = $query->paginate($this->perPage);
            return view($this->viewPath($this->indexView), $this->getIndexViewData($records));
        } catch (\Exception $e) {
            return $this->getRedirect('index_error', 'back')
                ->with('error', $e->getMessage());
        }
    }

    public function create()
    {
        try {
            $viewData = $this->getCreateViewData();
            return view($this->viewPath($this->createView), $viewData);
        } catch (\Exception $e) {
            return $this->getRedirect('create_error', 'index')
                ->with('error', $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate($this->getValidationRules());
            $data = $this->beforeStore($request->all());
            $record = $this->model::create($data);
            $this->afterStore($record);

            return $this->getRedirect('store_success', 'index', ['id' => $record->id])
                ->with('success', trans($this->successMessages['store'], ['record' => $this->recordName]));
        } catch (ValidationException $e) {
            return $this->getRedirect('store_validation_error', 'back')
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->getRedirect('store_query_error', 'back')
                ->with('error', 'Database query error: ' . $e->getMessage())
                ->withInput();
        } catch (\Exception $e) {
            return $this->getRedirect('store_error', 'back')
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }

    public function show($id)
    {
        try {
            $record = $this->model::findOrFail($id);
            $this->beforeShow($record);

            return view($this->viewPath($this->showView), $this->getShowViewData($record));
        } catch (ModelNotFoundException $e) {
            return $this->getRedirect('show_not_found', 'index')
                ->with('error', trans($this->errorMessages['notFound'], ['record' => $this->recordName]));
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->getRedirect('show_query_error', 'back')
                ->with('error', 'Database query error: ' . $e->getMessage())
                ->withInput();
        } catch (\Exception $e) {
            return $this->getRedirect('show_error', 'index')
                ->with('error', $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $record = $this->model::findOrFail($id);
            $viewData = $this->getEditViewData($record);
            return view($this->viewPath($this->editView), $viewData);
        } catch (ModelNotFoundException $e) {
            return $this->getRedirect('edit_not_found', 'index')
                ->with('error', trans($this->errorMessages['notFound'], ['record' => $this->recordName]));
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->getRedirect('edit_query_error', 'back')
                ->with('error', 'Database query error: ' . $e->getMessage())
                ->withInput();
        } catch (\Exception $e) {
            return $this->getRedirect('edit_error', 'index')
                ->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate($this->getValidationRules($id));
            $record = $this->model::findOrFail($id);
            $data = $this->beforeUpdate($request->all(), $record);
            $record->update($data);
            $this->afterUpdate($record);

            return $this->getRedirect('update_success', 'index', ['id' => $record->id])
                ->with('success', trans($this->successMessages['update'], ['record' => $this->recordName]));
        } catch (ValidationException $e) {
            return $this->getRedirect('update_validation_error', 'back')
                ->withErrors($e->validator)
                ->withInput();
        } catch (ModelNotFoundException $e) {
            return $this->getRedirect('update_not_found', 'index')
                ->with('error', trans($this->errorMessages['notFound'], ['record' => $this->recordName]));
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->getRedirect('update_query_error', 'back')
                ->with('error', 'Database query error: ' . $e->getMessage())
                ->withInput();
        } catch (\Exception $e) {
            return $this->getRedirect('update_error', 'back')
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $record = $this->model::findOrFail($id);
            $this->beforeDestroy($record);
            $record->delete();
            $this->afterDestroy($record);

            return $this->getRedirect('destroy_success', 'index')
                ->with('success', trans($this->successMessages['destroy'], ['record' => $this->recordName]));
        } catch (ModelNotFoundException $e) {
            return $this->getRedirect('destroy_not_found', 'index')
                ->with('error', trans($this->errorMessages['notFound'], ['record' => $this->recordName]));
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->getRedirect('destroy_query_error', 'back')
                ->with('error', 'Database query error: ' . $e->getMessage())
                ->withInput();
        } catch (\Exception $e) {
            return $this->getRedirect('destroy_error', 'index')
                ->with('error', $e->getMessage());
        }
    }

    // Helper methods
    protected function viewPath($view)
    {
        return $this->viewPrefix ? $this->viewPrefix . '.' . $view : $view;
    }

    protected function routePath($action)
    {
        return $this->routePrefix ? $this->routePrefix . '.' . $action : $action;
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
    protected function getValidationRules($id = null)
    {
        return array_map(function ($rule) use ($id) {
            if (is_string($rule) && str_contains($rule, 'unique') && $id) {
                return preg_replace('/unique:([a-z_]+),([a-z_]+)/', "unique:$1,$2,$id", $rule);
            }
            return $rule;
        }, $this->validationRules);
    }

    protected function getIndexViewData($records)
    {
        return [
            'records' => $records,
            'filters' => request()->all()
        ];
    }

    protected function getCreateViewData()
    {
        return [];
    }

    protected function getShowViewData($record)
    {
        return ['record' => $record];
    }

    protected function getEditViewData($record)
    {
        return ['record' => $record];
    }
}
