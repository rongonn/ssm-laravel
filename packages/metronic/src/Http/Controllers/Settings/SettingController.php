<?php

namespace Isotope\Metronic\Http\Controllers\Settings;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Isotope\Metronic\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    // permission mappings for this controller
    public static $permissions = [
        'index' => ['access_settings', 'Settings List'],
        'store' => ['store_settings', 'Settings Update'],
    ];

    public function index(Request $request)
    {
        $settings = (object) Setting::where('group', 'system')->pluck('text', 'option')->toArray();
        return view('isotope::settings', compact('settings'));
    }

    public function store(Request $request)
    {
        try {
            $req = $request->all();
            foreach ($req['text'] as $option => $value) {
                Setting::updateOrCreate(
                    ['option' => $option],
                    ['text' => $value, 'group' => 'system'],
                );
            }

            if ($request->hasFile('company_logo')) {
                Storage::delete('settings/company_logo.png');
                $company_logo  = $this->imageUpload($request->company_logo, 'company_logo');
                Setting::updateOrCreate(
                    ['option' => 'company_logo'],
                    ['text' => $company_logo, 'group' => 'system'],
                );
            }
            if ($request->hasFile('login_logo')) {
                Storage::delete('settings/login_logo.png');
                $login_logo  = $this->imageUpload($request->login_logo, 'login_logo');
                Setting::updateOrCreate(
                    ['option' => 'login_logo'],
                    ['text' => $login_logo, 'group' => 'system'],
                );
            }
            if ($request->hasFile('favicon')) {
                Storage::delete('settings/favicon.png');
                $favicon  = $this->imageUpload($request->favicon, 'favicon');
                Setting::updateOrCreate(
                    ['option' => 'favicon'],
                    ['text' => $favicon, 'group' => 'system'],
                );
            }

            $banners = ['landing_banner', 'services_banner', 'products_banner', 'gallery_banner', 'about_banner', 'contact_banner'];
            foreach ($banners as $banner) {
                if ($request->hasFile($banner)) {
                    $bannerPath = $this->imageUpload($request->$banner, $banner);
                    Setting::updateOrCreate(
                        ['option' => $banner],
                        ['text' => $bannerPath, 'group' => 'system'],
                    );
                }
            }

            if ($request->hasFile('popup_image')) {
                $popup_image  = $this->imageUpload($request->popup_image, 'popup_image');
                Setting::updateOrCreate(
                    ['option' => 'popup_image'],
                    ['text' => $popup_image, 'group' => 'system'],
                );
            }

            Cache::forget('settings');
            Cache::rememberForever('settings', function () {
                return (object) Setting::pluck('text', 'option')->toArray();;
            });

            return redirect(tenant() ? '/settings' : 'owner/settings')->withSuccess("Settings sets successfully");
        } catch (Exception $e) {
            return redirect(tenant() ? '/settings' : 'owner/settings')->withErrors($e->getMessage());
        }
    }

    private function imageUpload($file, $name)
    {
        $path = $file->store("settings", 'public');
        return $path;
    }

    private function refreshSettingsCache()
    {
        Cache::forget('settings');
        Cache::rememberForever('settings', function () {
            return (object) Setting::pluck('text', 'option')->toArray();
        });
    }

    private function getSliderImages(): array
    {
        $setting = Setting::where('option', 'slider_images')->first();
        if (!$setting || empty($setting->text)) return [];
        $decoded = json_decode($setting->text, true);
        return is_array($decoded) ? $decoded : [];
    }

    private function saveSliderImages(array $images): void
    {
        Setting::updateOrCreate(
            ['option' => 'slider_images'],
            ['text' => json_encode(array_values($images)), 'group' => 'system']
        );
        $this->refreshSettingsCache();
    }

    public function sliderUpload(Request $request)
    {
        try {
            // Check if post_max_size or upload_max_filesize was exceeded
            if ($request->has('images') && empty($request->file('images')) && $request->server('CONTENT_LENGTH') > 0) {
                return response()->json([
                    'success' => false, 
                    'message' => 'The uploaded images exceed the server maximum limit. Please upload smaller images (max 2MB each).'
                ], 422);
            }

            $request->validate([
                'images' => 'required|array',
                'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120'
            ]);

            $images = $this->getSliderImages();
            $uploadedFiles = $request->file('images');

            foreach ($uploadedFiles as $file) {
                if ($file->isValid()) {
                    $path = $file->store('settings/slider', 'public');
                    $images[] = $path;
                } else {
                    throw new Exception("File " . $file->getClientOriginalName() . " failed to upload correctly.");
                }
            }

            $this->saveSliderImages($images);
            return response()->json(['success' => true, 'images' => $images]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $firstError = collect($e->errors())->flatten()->first();
            return response()->json(['success' => false, 'message' => $firstError], 422);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function sliderDelete(Request $request)
    {
        try {
            $path = $request->input('path');
            $images = $this->getSliderImages();
            $images = array_filter($images, fn($img) => $img !== $path);
            Storage::disk('public')->delete($path);
            $this->saveSliderImages(array_values($images));
            return response()->json(['success' => true]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function sliderReorder(Request $request)
    {
        try {
            $images = $request->input('order', []);
            $this->saveSliderImages($images);
            return response()->json(['success' => true]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
