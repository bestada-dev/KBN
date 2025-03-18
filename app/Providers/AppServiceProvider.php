<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Child;

use App\Services\IncidentService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(IncidentService::class, function ($app) {
            return new IncidentService();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Request $request)
    {
        // Attach a view composer to the _header.blade.php view
        View::composer('layouts.partials._search', function ($view) use ($request) {
            $headerController = new Controller();
            $data = null;
            if(Auth::user()->is_admin) {
               $data =  $headerController->getForms($request);
            } else {
                $data =  $headerController->getFormsUser($request);
            }
            $view->with('data', $data);
        });

        // View::composer('pages/admin/halaman-arah/index', function ($view) use ($request) {
        //     $home = new Controller();
        //     $home =  $home->getDataOfLandingPage('home');
        //     $idOfSection5 =  $home[4]['id'];
        //     $idOfSection6 =  $home[5]['id'];

        //     $childBagian5 = Child::where('landing_page_setting_id',$idOfSection5)->get();
        //     $childBagian6 = Child::where('landing_page_setting_id',$idOfSection6)->get();
            
        //     // kalo mau tau detail sectionnya ,, liat aja web.php URL : insert-data-dummy
        //     $section1 = $home[0];
        //     $section2 = $home[1];
        //     $section3 = $home[2];   
        //     $section4 = $home[3];
        //     $section5 = $home[4];
        //     $section6 = $home[5];
        //     $section7 = $home[6];
        //     $section8 = $home[7];
            
        //     // dd($home[8]['whatsapp']);
        //     $view->with([
        //         'section1' => $section1,
        //         'section2' => $section2,
        //         'section3' => $section3,
        //         'section4' => $section4,
        //         'section5' => $section5,
        //         'section6' => $section6,
        //         'section7' => $section7,
        //         'section8' => $section8,
        //     ]);
        // });
        View::composer('pages/admin/halaman-arah/component/home', function ($view) use ($request) {
            $home = new Controller();
            $home =  $home->getDataOfLandingPage('home');
            $idOfSection5 =  $home[4]['id'];
            $idOfSection6 =  $home[5]['id'];

            $childBagian5 = Child::where('landing_page_setting_id',$idOfSection5)->get();
            $childBagian6 = Child::where('landing_page_setting_id',$idOfSection6)->get();
            
            // kalo mau tau detail sectionnya ,, liat aja web.php URL : insert-data-dummy
            $section1 = $home[0];
            $section2 = $home[1];
            $section3 = $home[2];   
            $section4 = $home[3];
            $section5 = $home[4];
            $section6 = $home[5];
            $section7 = $home[6];
            $section8 = $home[7];
            
            // dd($home);
            // dd($home[8]['whatsapp']);
            $view->with([
                'section1' => $section1,
                'section2' => $section2,
                'section3' => $section3,
                'section4' => $section4,
                'section5' => $section5,
                'section6' => $section6,
                'section7' => $section7,
                'section8' => $section8,
                'childBagian5' => $childBagian5,
                'childBagian6' => $childBagian6
            ]);
        });

        View::composer('layouts-landing-page/partials/navbar-white', function ($view) use ($request) {
            $home = new Controller();
            $home =  $home->getDataOfLandingPage('home');
            
            $section7 = $home[6];

            $view->with(['section7' => $section7]);
        });



        // FOOTER

        View::composer('layouts-landing-page/partials/footer_', function ($view) use ($request) {
            $home = new Controller();
            $home =  $home->getDataOfLandingPage('home');

            $idOfSection5 =  $home[4]['id'];
            $idOfSection6 =  $home[5]['id'];

            $childBagian5 = Child::where('landing_page_setting_id',$idOfSection5)->get();
            $childBagian6 = Child::where('landing_page_setting_id',$idOfSection6)->get();
            

            $view->with(['home' => $home,  'childBagian6' => $childBagian6]);
        });

        View::composer('layouts-landing-page/partials/footer', function ($view) use ($request) {
            $home = new Controller();
            $home =  $home->getDataOfLandingPage('home');

            // $section6 = $home[5];
            // $section7 = $home[6];
            // $section8 = $home[7];

            $idOfSection6 =  $home[5]['id'];
            $childBagian6 = Child::where('landing_page_setting_id',$idOfSection6)->get();
            
            $view->with(['dataHome' => $home, 'dataPartner' => $childBagian6]);
        });
       
    }
}
