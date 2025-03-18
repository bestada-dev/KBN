<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Media;

class CheckMediaAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // // Extract the file name from the URL
        $folder = $request->route('folder');
        $fileName = $request->route('fileName');

        // Define the path to the private storage
        $filePath = $folder .'/'. $fileName;

        // Check if the file exists
        if (!Storage::disk('private')->exists($filePath)) {
            return abort(404, 'File not found');
        }

        // Find the media record by file name
        $media = Media::where('file', $filePath)->first();

        if (!$media) {
            return abort(404, 'File not found');
        }

        // Check if the user is logged in
        $user = Auth::user();
        if (!$user) {
            return abort(403, 'Unauthorized access');
        }

        // User dengan role id 3 aja & Check if the category requires a request
        if($user->role->id === 3) {
            if ($media->categoryMedia->requires_request) {
                $canAccessForView = $request->keyAccessForViewOnly ===  _['SUCCESS_CODE'];
                if(!$canAccessForView) {
                    // Check if the user has access
                    if ($media->requestByUser($user) && $media->requestByUser($user)->status !== 'approved') {
                        return abort(403, 'You do not have access to this media');
                    }
                }
            }
        }

        return $next($request);
    }
}
