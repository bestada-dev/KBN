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
        $this->updateVideoPath = 'videos/';

        $this->uploadLayoutsPath = 'layouts';
        $this->updateLayoutsPath = 'layouts/';

        $this->uploadDetailPhotoPath = 'detail_photos';
        $this->updateDetailPhotoPath = 'detail_photos/';

        $this->uploadIconPath = 'public/icons';
        $this->updateIconPath = 'icons/';

        // production
        // $this->uploadPath = '../../../../../home/hannache/public_html/images';
        // $this->updatePath = '../../../../../home/hannache/public_html/images/';
    }
}
