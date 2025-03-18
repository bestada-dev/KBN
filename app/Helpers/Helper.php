<?php

use App\LogError;
use App\LogEmail;
use App\Project;
use App\Certificate;
use Illuminate\Support\Facades\Storage;
use Stevebauman\Location\Facades\Location;

define('_', [
    'SUCCESS_CODE' => '91252d3be902dda0994dcd806a0d8d388bb26edf1891feb911b214bb6441aef15b859af95eae741b27994d3dfcc82ce0e67efece9c904530c7566aa30275161f814a9ebefd0d4bcc9653fd2d0384fdf368d3d124ee21092dbf7aba0b064b78e03dff3ea0'
]);

define('TYPE_UPDATE_PASSWORD', [
    'CREATE_NEW_PASSWORD_AFTER_LOGIN' => 'Create New Password after Login',
    'RESET_PASSWORD'                  => 'Reset Password',
]);

function ____error($data)
{

    $_ = $data;

	$data = [
		'type'     => 'controller',
		'path'     => explode("\\", get_class(\Request::route()->getController()))[3],
		'function' => $data[0],
		'error'	   => $data[1]
	];

	LogError::insert($data);

    return response()->json([
        'message' => 'Oops.. Something went wrong! '. $_[1],
        'status'  => false
    ], 500);
}

function __generateToken($length = 200)
{
    // Generate random bytes and convert to hex
    $bytes = random_bytes($length);
    $token = bin2hex($bytes);

    // Truncate or pad the token to ensure it matches the required length
    return substr($token, 0, $length);
}

function _uploadFile($file, $path)
{
	$dir  = 'assets/' . $path;

	if(!File::exists($dir)){
		File::makeDirectory($dir);
	}

	$name = sha1($file.time()) . '.' . $file->getClientOriginalExtension();
	$file->move($dir, $name);

    chmod($dir . '/' . $name, 775);
	$data = $dir . '/' . $name;

	return $data;
}

function _uploadFileWithStorage($file, $path, $disk = 'public')
{
    // Generate a unique file name
    $fileName = sha1($file->getClientOriginalName() . time()) . '.' . $file->getClientOriginalExtension();
    // Store the file and get the relative path
    $filePath = $file->storeAs($path, $fileName, $disk);

    // Return the relative path
    return $filePath;
}

function _settingSidebar()
{
	/*
        --- R  O  L  E ---
        1 : Super
        2 : Admin
        3 : User
        ------------------
    */

	$setting = [
        'dashboard'                         => [1, 2, 3],
        'user-management/pic-list'          => [1, 2],
		'user-management/user-list'         => [1,2],
        'project-setup'                     => [1, 2, 3],
        'report/report-type'                => [1, 2, 3],
        'report/report-list'                => [1, 2, 3],
        'report/kpi-recap'                  => [1, 2, 3],
        'information'                       => [1, 2, 3],
        'media'                             => [1, 2, 3],
	];

	return $setting;
}

function _checkSidebar($URL)
{
	$ROLE    = Session::get('data')['user']->role_id;
	$SETTING = _settingSidebar();

	if(array_key_exists($URL, $SETTING)){

		if(!in_array($ROLE, $SETTING[$URL])){
			return false;
		}

	}else{
		return false;
	}

	return true;
}
function ___set($param)
{
    // Define roles for different routes
    //  1 = Superadmin
    //  2 = Vendor
    //  3 = Karyawan
    //  4 = Perusahaan

    $setting = [
        'superadmin*'                            => [1],
        'vendor*'                                => [2],
        'employe*'                               => [3],
        'company*'                               => [4],
        'landing-page*'                          => [1],

        // 'admin*'                                 => [1],
        // 'pengguna*'                              => [1],
        // 'register*'                              => [1],
        // 'pelatihan_saya*'                        => [1,2,3],
        // 'pengembangan_diri*'                     => [1,2,3],
        // 'test*'                                  => [1,2,3],
        // 'evaluasi_level_3*'                      => [1,2,3],
        // 'akses_pelatihan*'                       => [1,2,3,4],
    ];

    // Check exact match or specific dynamic route
    if (isset($setting[$param])) {
        return checkAccess($setting[$param]);
    }

    // Pattern-based matching for dynamic routes
    foreach ($setting as $pattern => $roles) {
        if (matchesPattern($param, $pattern)) {
            return checkAccess($roles);
        }
    }

    return false;
}

// Helper to check access
function checkAccess($roles)
{

    // dd(Session::get('data'));
    if (Session::has('data')) {
        $role = (int)Session::get('data')['user']['role_id'];
        return in_array($role, $roles);
    }

    return false;
}

// Helper to match route pattern with parameters
function matchesPattern($param, $pattern)
{
    // Escape any special regex characters in the pattern
    $escapedPattern = preg_quote($pattern, '/');
    // Replace {id} with \d+ for numeric IDs and {any} with .* for any characters
    $regex = str_replace(['\*'], ['.*'], $escapedPattern);
    // Match the parameter against the regex pattern
    return preg_match('/^' . $regex . '$/', $param);
}



function __randomNumber($length)
{
    $c    = '0123456789';
    $cl   = strlen($c);
    $data = '';

    for($i = 0; $i < $length; $i++){
        $data .= $c[rand(0, $cl - 1)];
    }

    return $data;
}

function __uploadFileURL($path, $url, $extension = 'jpg')
{
    $dir = 'file/' . $path . '/';

    if(!File::exists($dir)){
        File::makeDirectory($dir);
    }

    $file = file_get_contents($url);
    $name = $dir . __randomNumber(15) . '.' . $extension;

    file_put_contents($name, $file);

    return $name;
}

function __logEmail($status, $to, $type, $message = null, $data = null)
{
    $param = [
        'status' => $status,
        'to'     => $to,
        'type'	 => $type
    ];

    $message !== null ? $param['message'] = $message : null;
    $data    !== null ? $param['data']    = $data    : null;

    LogEmail::create($param);
}


function _numberFormat($val)
{
    $val = number_format($val);
    $val = str_replace(',', '.', $val);

    return $val;
}

function generateCurrentId()
{
    // This method generates a unique ID.
    // For simplicity, we'll use a simple incrementing ID from the database.

    $lastId = Project::latest('created_at')->value('proj_id');

    if ($lastId) {
        // Extract the last numeric part and increment it
        $lastNumber = intval(substr($lastId, -2)) + 1;
        $currentId = str_pad($lastNumber, 2, '0', STR_PAD_LEFT);
    } else {
        $currentId = '01'; // Start with '01' if there are no previous IDs
    }

    $date = now()->format('dmy'); // Get current date in DDMMYYYY format

    return "PRO-{$date}-{$currentId}";
}

function generateCertificateNumber()
{
    // Get the last certificate number from the database
    $lastCertificate = Certificate::latest('created_at')->value('certificate_number');

    if ($lastCertificate) {
        // Extract the last numeric part and increment it
        $lastNumber = intval(substr($lastCertificate, -2)) + 1;
        $incrementedId = str_pad($lastNumber, 2, '0', STR_PAD_LEFT);
    } else {
        $incrementedId = '01'; // Start with '01' if there are no previous IDs
    }

    // Format the date as DDMMYY
    $date = now()->format('dmy');

    // Return the formatted certificate number
    return "CERT-{$date}-{$incrementedId}";
}


if (!function_exists('formattingTitle')) {
    /**
     * Format the title by wrapping random words in a span.
     *
     * @param string $title
     * @return string
     */
    function formattingTitle($title, $color = 'white', $fontWeight= 'bold')
    {
        // // OLDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDD
        // Split the title into words
        $words = explode(' ', $title);

        // Check the number of words
        if (count($words) === 3) {
            // Wrap only the second word in a span
            $wrapped = '<span class="text-'.$color.' underline underline-offset-8 font-'.$fontWeight.'">' . $words[1] . '</span>';
            $result = $words[0] . ' ' . $wrapped . ' ' . $words[2];
        } elseif (count($words) === 2) {
            // Wrap only the second word in a span
            $wrapped = '<span class="text-'.$color.' underline underline-offset-8 font-'.$fontWeight.'">' . $words[1] . '</span>';
            $result = $words[0] . ' ' . $wrapped;
        }elseif (count($words) > 3) {
            // Wrap the second and third words in a span
            $wrappedWords = array_slice($words, 1, 2);
            $wrapped = '<span class="text-'.$color.' underline underline-offset-8 font-'.$fontWeight.'">' . implode(' ', $wrappedWords) . '</span>';
            $result = $words[0] . ' ' . $wrapped . ' ' . implode(' ', array_slice($words, 3));
        } else {
            // If there are fewer than 3 words, just display them
            $result = $title;
        }

        //    NEWWWWWWWWWWWWWWWWWWWWWWWWWWWWWW
            // Split the title into words
            // $words = explode(' ', $title);
            // $wordCount = count($words);

            // // Initialize the result variable
            // $result = '';

            // // Check the number of words
            // if ($wordCount > 2) {
            //     // Create an array of indices to wrap (not including the first and last)
            //     $indices = range(1, $wordCount - 2);

            //     // Select a random number of indices to wrap (at least 1, at most count of indices)
            //     $randomCount = rand(1, count($indices));

            //     // Ensure we have enough indices to randomly select
            //     if (count($indices) > 0) {
            //         $randomKeys = array_rand($indices, min($randomCount, count($indices)));

            //         // Wrap the selected words in a span
            //         foreach ((array)$randomKeys as $key) {
            //             $words[$indices[$key]] = '<span class="text-white covered-by-your-grace-regular font-bold">' . $words[$indices[$key]] . '</span>';
            //         }
            //     }
            // }

            // // Join the words back into a string
            // $result = implode(' ', $words);

            return $result;
    }
}
