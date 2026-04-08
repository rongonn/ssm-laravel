<?php

namespace Isotope\Metronic\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{

    public static $permissions = [
        'index'                  => ['notification_index', 'Notification Index'],
        'markReadAllNotification'=> ['mark_read_all_notification', 'Mark Read All Notifications'],
        'clearAllNotification'   => ['clear_all_notification', 'Clear All Notifications'],
        'markAsRead'             => ['mark_as_read', 'Mark Single Notification as Read'],
    ];
    public function index(Request $request)
    {
        return view('isotope::notification', [
            'notifications' => Auth::user()->notifications()->paginate(10)
        ]);
    }

    public function markReadAllNotification()
    {
        try {
            $user = Auth::user();
            $user->notifications->markAsRead();
            return redirect()->back()->with('success', 'All notifications marked as read.');
        } catch (Exception $e) {
            return redirect('/settings')->withErrors($e->getMessage());
        }
    }

    public function clearAllNotification()
    {
        try {
            $user = Auth::user();
            $user->notifications()->delete();
            return redirect()->back()->with('success', 'All notifications cleared.');
        } catch (Exception $e) {
            return redirect('/settings')->withErrors($e->getMessage());
        }
    }

    public function markAsRead($id)
    {
        try {
            $notification = Auth::user()->notifications()->findOrFail($id);
            $notification->markAsRead();
            return redirect()->back()->with('success', 'Notification marked as read.');
        } catch (Exception $e) {
            return redirect('/settings')->withErrors($e->getMessage());
        }
    }

}
