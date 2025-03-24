<?php

namespace App\Http\Controllers\BaseController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UploadImageController extends Controller
{
    protected $uploadVideoPath;
    protected $updateVideoPath;
    protected $uploadLayoutsPath;
    protected $updateLayoutsPath;
    protected $uploadDetailPhotoPath;
    protected $updateDetailPhotoPath;
    protected $uploadIconPath;
    protected $updateIconPath;

    public function __construct()
    {
        // Development
        $this->uploadVideoPath = 'videos';
        $this->updateVideoPath = 'public/videos/';

        $this->uploadLayoutsPath = 'layouts';
        $this->updateLayoutsPath = 'public/layouts/';

        $this->uploadDetailPhotoPath = 'detail_photos';
        $this->updateDetailPhotoPath = 'public/detail_photos/';

        $this->uploadIconPath = 'public/icons';
        $this->updateIconPath = 'icons/';

        // production
        // $this->uploadVideoPath = '../../../../var/www/html/videos';
        // $this->updateVideoPath = '../../../../var/www/html/videos/';

        // $this->uploadLayoutsPath = '../../../../var/www/html/layouts';
        // $this->updateLayoutsPath = '../../../../var/www/html/layouts/';

        // $this->uploadDetailPhotoPath = '../../../../var/www/html/detail_photos';
        // $this->updateDetailPhotoPath = '../../../../var/www/html/detail_photos/';

        // $this->uploadIconPath = '../../../../var/www/html/public/icons';
        // $this->updateIconPath = '../../../../var/www/html/icons/';

    }
}
