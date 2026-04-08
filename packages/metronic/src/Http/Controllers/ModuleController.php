<?php

namespace Isotope\Metronic\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Isotope\Metronic\Models\Setting;

class ModuleController extends Controller
{

    public function getModules()
    {
        $modules = [];
        $blockedModules = Setting::where('option', 'blocked_modules')->first();
        $blockedModules = $blockedModules ? json_decode($blockedModules->text, true) : [];
        $routes  = Route::getRoutes();

        foreach ($routes as $route) {
            if (
                (preg_match("/^App(.*)/i", trim($route->getActionName())) == 0)
                &&
                (preg_match("/^Isotope(.*)/", trim($route->getActionName())) == 0)
            ) continue;
            $arr = explode('\\', $route->getActionName());
            $moduleName = $arr[0] . '\\' . ($arr[1] ?? '');
            $modules[$moduleName]  = $moduleName;
        }

        sort($modules);

        return view('isotope::modules', compact('modules', 'blockedModules'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'modules'   => 'required|array',
            'modules.*' => 'string'
        ]);

        DB::beginTransaction();
        try {
            Setting::updateOrCreate(
                ['option' => 'blocked_modules'],
                ['text' => json_encode($request->modules), 'group' => 'system']
            );

            DB::commit();
            return redirect()->back()->with('success', 'Modules updated successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'An error occurred while updating modules: ' . $e->getMessage());
        }
    }

    public function getRoutes()
    {
        $actions = [];
        $routes  = Route::getRoutes();

        foreach ($routes as $route) {
            if (
                (preg_match("/^App(.*)/i", trim($route->getActionName())) == 0)
                &&
                (preg_match("/^Isotope(.*)/", trim($route->getActionName())) == 0)
            ) continue;

            $actionName     = $route->getActionName();
            $method         = $route->methods()[0];
            $action         = substr($actionName, strpos($actionName, '@') + 1);
            $namespace      = substr($actionName, 0, strrpos($actionName, '\\'));
            $controller     = substr($actionName, strrpos($actionName, '\\') + 1, - (strlen($action) + 1));
            $controllerPath = $namespace . '\\' . $controller;

            if (strlen($controller) > 0) {
                $controllerObj  = new $controllerPath;
                if (isset($controllerObj::$permissions[$action])) {
                    $actionIsset = $controllerObj::$permissions[$action];
                    $actions[str_replace('Controller', '', $controller)][] = [
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

        ksort($actions);

        return $actions;
    }
}
