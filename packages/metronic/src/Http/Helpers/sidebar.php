<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Isotope\Metronic\Models\Permission;

if(!function_exists('sidebar')) {
    function sidebar() {
        $GLOBALS['permittedUris'] = Permission::where('role_id', Auth::user()->role_id ?? 1)->pluck('uri');
        $GLOBALS['isSuperAdmin']  = Auth::user()?->isSuperAdmin() ?? false;
        $actionName   = request()->route()->getActionName();
        $actionArray  = array_map('strtolower', explode('\\', $actionName));
        $sidebarArray = collect(config('sidebar'));
        $html         = '';
        foreach ($sidebarArray as $key => $sidebar) {
            if(in_array(strtolower($key), $actionArray)) {
                $html .= collect($sidebar)->sortBy('sl')->map(function($nav){
                    if(array_key_exists('children', $nav)){
                        return ul($nav);
                    } 
                    elseif(array_key_exists('header', $nav)){
                        return liHeader($nav);
                    } 
                    elseif(array_key_exists('model', $nav)){
                        return liModel($nav['model']);
                    } 
                    else {
                        return li($nav);
                    }
                })->join('');
            }
        }

        return $html;
    }
}

if(!function_exists('ul')) {
    function ul($li) {
        if (array_key_exists('role', $li)) {
            if ($li['role'] == 'admin' && !$GLOBALS['isSuperAdmin']) return '';
            if ($li['role'] == 'owner' && $GLOBALS['isSuperAdmin']) return '';
        }
        if (array_key_exists('permission', $li)) {
             if(!$GLOBALS['isSuperAdmin'] && !auth()->user()->can($li['permission'])) return '';
        }

        $show = '';
        $children = collect($li['children'])->map(function($nav) use(&$show){
                        if(array_key_exists('children', $nav)){
                            return ul($nav);
                        } else {
                            $route = array_key_exists('route', $nav) ? $nav['route'] : null;
                            $url_key = array_key_exists('url', $nav) ? $nav['url'] : '#';
                            $url = $route ? route($route) : url($url_key);
                            if(url()->current() == $url && strlen($show) < 1) {
                                $show = ' here show';
                            }
                            return li($nav);
                        }
                    })->join('');
        if(strlen($children) < 1) return '';
        $title = trans($li['title'] ?? 'Menu');
        $icon = $li['icon'] ?? 'bi bi-grid';
        return  <<<TEXT
            <div data-kt-menu-trigger="click" class="menu-item$show menu-accordion">
                <span class="menu-link">
                    <span class="menu-icon">
                        <i class="{$icon} fs-3"></i>
                    </span>
                    <span class="menu-title">{$title}</span>
                    <span class="menu-arrow"></span>
                </span>
                <div class="menu-sub menu-sub-accordion">
                    $children
                </div>
            </div>
        TEXT;
    }
}

if(!function_exists('li')) {
    function li($li) {
        if (array_key_exists('role', $li)) {
            if ($li['role'] == 'admin' && !$GLOBALS['isSuperAdmin']) return '';
            if ($li['role'] == 'owner' && $GLOBALS['isSuperAdmin']) return '';
        }
        if (array_key_exists('permission', $li)) {
             if(!$GLOBALS['isSuperAdmin'] && !auth()->user()->can($li['permission'])) return '';
        }

        $url = '#';
        if (array_key_exists('route', $li)) {
            $routeName = tenant() ? $li['route'] : 'owner.'.$li['route'];
            if (Route::has($routeName)) {
                $url = route($routeName);
            } else {
                $url = url($li['url'] ?? '#');
            }
        } elseif (array_key_exists('url', $li)) {
            $url = url($li['url']);
        }
        
        $icon   = array_key_exists('icon', $li) ? $li['icon'] . ' fs-3' : 'bullet bullet-dot';
        $active = url()->current() == $url ? ' active' : '';
        $path = isset(parse_url($url)['path']) ? substr(parse_url($url)['path'], 1) : '/';
        if(!$GLOBALS['isSuperAdmin'] && !in_array($path, $GLOBALS['permittedUris']->toArray())) return '';
        $title = trans($li['title'] ?? 'Item');
        return  <<<TEXT
                    <div class="menu-item">
                        <a class="menu-link$active" href="$url">
                            <span class="menu-icon">
                                <i class="$icon"></i>
                            </span>
                            <span class="menu-title">{$title}</span>
                        </a>
                    </div>
                TEXT;
    }
}

if(!function_exists('liHeader')) {
    function liHeader($li) {
        if (array_key_exists('role', $li)) {
            if ($li['role'] == 'admin' && !$GLOBALS['isSuperAdmin']) return '';
            if ($li['role'] == 'owner' && $GLOBALS['isSuperAdmin']) return '';
        }
        if (array_key_exists('permission', $li)) {
            if(!$GLOBALS['isSuperAdmin'] && !auth()->user()->can($li['permission'])) return '';
        }
        $header = trans($li['header']);
        return  <<<TEXT
            <div class="menu-item">
                <div class="menu-content">
                    <span class="menu-heading fw-bold text-uppercase fs-7">{$header}</span>
                </div>
            </div>
        TEXT;
    }
}

if(!function_exists('liModel')) {
    function liModel($model) {
        $className    = new $model[0]();
        $functionName = $model[1];
        return collect($className::$functionName())->map(function($nav){
            if(array_key_exists('children', $nav)){
                return ul($nav);
            } 
            elseif(array_key_exists('header', $nav)){
                return liHeader($nav);
            }
            else {
                return li($nav);
            }
        })->join('');
    }
}
