<?php

namespace Isotope\Metronic\Http\Controllers;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Isotope\Metronic\Models\Role;
use Isotope\Metronic\Models\Permission;
use Isotope\Metronic\Models\Setting;

class RoleController extends Controller
{

    public function index(Request $request)
    {
        $roles = Role::all();
        return view('isotope::role.index', compact('roles'));
    }

    public function create(Request $request)
    {
        $routes = $this->getRoutes();
        $misc = $this->getConfigPermission();
        if (!empty($misc)) {
            $routes['Global Config']['System'] = $misc;
        }
        return view('isotope::role.create', compact('routes'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required',
                'permissions' => 'required',
            ]);

            DB::beginTransaction();

            $role = Role::create([
                "uuid"  => Str::uuid(),
                "title" => $request->input('title'),
                "alias" => Str::of($request->input('title'))->slug('_'),
            ]);

            foreach ($request->input('permissions') ?? [] as $permission) {
                $permission = json_decode($permission);
                Permission::create([
                    "uuid"       => Str::uuid(),
                    "role_id"    => $role->id,
                    "title"      => $permission->title,
                    "permission" => $permission->permission,
                    "namespace"  => $permission->namespace,
                    "controller" => $permission->controller,
                    "method"     => $permission->method,
                    "action"     => $permission->action,
                    "uri"        => $permission->uri,
                ]);
            }
            DB::commit();

            return redirect()->route(tenant() ? 'authorization.roles.index' : 'owner.authorization.roles.index')->withSuccess('Created Successfully');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    public function edit($id)
    {
        $role           = Role::with('permissions')->findOrFail($id);
        $routes         = $this->getRoutes();
        $misc           = $this->getConfigPermission();
        if (!empty($misc)) {
            $routes['Global Config']['System'] = $misc;
        }
        $permissions    = $role->permissions->pluck('permission')->toArray();
        return view('isotope::role.edit', compact('routes', 'role', 'permissions'));
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $role = Role::find($id);

            $role->update([
                "title" => $request->input('title'),
                "alias" => Str::of($request->input('title'))->slug('_'),
            ]);

            Permission::where('role_id', $role->id)->delete();
            foreach ($request->input('permissions') as $permission) {
                $permission = json_decode($permission);
                Permission::create([
                    "uuid"       => Str::uuid(),
                    "role_id"    => $role->id,
                    "title"      => $permission->title,
                    "permission" => $permission->permission,
                    "namespace"  => $permission->namespace,
                    "controller" => $permission->controller,
                    "method"     => $permission->method,
                    "action"     => $permission->action,
                    "uri"        => $permission->uri,
                ]);
            }
            DB::commit();

            return redirect()->route(tenant() ? 'authorization.roles.index' : 'owner.authorization.roles.index')->withSuccess('Updated Successfully');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        Permission::where('role_id', $role->id)->delete();
        $role->delete();
        return redirect()->route(tenant() ? 'authorization.roles.index' : 'owner.authorization.roles.index')->withSuccess('Deleted Successfully');
    }

    private function getConfigPermission()
    {
        $arr = [];
        $permissions = config('ipermissions');
        foreach ($permissions as $permission => $title) {
            array_push($arr, [
                'namespace'  => 'N/A',
                'controller' => 'N/A',
                'method'     => 'N/A',
                'action'     => 'N/A',
                'uri'        => 'N/A',
                'permission' => $permission,
                'title'      => $title,
            ]);
        }
        return $arr;
    }

    public function getRoutes()
    {
        $actions = [];
        $routes  = Route::getRoutes();
        $blockedModules = Setting::where('option', 'blocked_modules')->first();
        $blockedModules = $blockedModules ? json_decode($blockedModules->text, true) : [];
        foreach ($routes as $route) {
            $actionFull = $route->getActionName();
            if (strpos($actionFull, '@') === false) continue;

            $arr = explode('\\', $actionFull);
            $moduleName = $arr[0];
            if (isset($arr[1]) && strpos($arr[1], '@') === false) {
                $moduleName .= '\\' . $arr[1];
            }

            if (in_array($moduleName, $blockedModules)) {
                continue;
            }
            if (
                (preg_match("/^App(.*)/i", trim($actionFull)) == 0)
                &&
                (preg_match("/^Isotope(.*)/", trim($actionFull)) == 0)
            ) continue;

            $actionName     = $actionFull;
            $method         = $route->methods()[0];
            $action         = substr($actionName, strpos($actionName, '@') + 1);
            $namespace      = substr($actionName, 0, strrpos($actionName, '\\'));
            $controller     = substr($actionName, strrpos($actionName, '\\') + 1, - (strlen($action) + 1));
            $controllerPath = $namespace . '\\' . $controller;

            if (strlen($controller) > 0) {
                if (class_exists($controllerPath)) {
                    $controllerObj  = new $controllerPath;
                    if (isset($controllerObj::$permissions[$action])) {
                        $actionIsset = $controllerObj::$permissions[$action];
                        $controllerName = str_replace('Controller', '', $controller);
                        $actions[$moduleName][$controllerName][] = [
                            'namespace'  => $namespace,
                            'controller' => $controller,
                            'method'     => $method,
                            'action'     => $action,
                            'permission' => $actionIsset[0],
                            'title'      => $actionIsset[1],
                            'uri'        => $route->uri()
                        ];
                    }
                }
            }
        }

        ksort($actions);
        foreach ($actions as $module => $ctrls) {
            ksort($actions[$module]);
        }

        return $actions;
    }
}
