<?php

namespace Arins\Fo\Http\Controllers\Absen;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Arins\Services\Locater\LocaterInterface;
use Illuminate\Support\Facades\Auth;

use Arins\Facades\Response;
use Arins\Facades\Filex;
use Arins\Facades\Formater;
use Arins\Facades\ConvertDate;
use Arins\Facades\Role;
use Arins\Fo\Repositories\Attend\AttendRepositoryInterface;
use Arins\Bo\Repositories\User\UserRepositoryInterface;
use Carbon\Carbon;

//TODO: Sementara saja, nanti pakai repository
use Arins\Fo\Models\Attend;
use App\User;

class AbsenController extends Controller
{
    protected $sViewRoot;
    protected $data, $dataUsers;
    protected $oLocater;
    protected $ip;
    protected $baseURL, $latlng, $key;
    protected $viewModel;
    protected $uploadDirectory;


    /**
     * Create a new controller instance.
     *
     * Method Name: Constructor
     * 
     * @return void
     */
    public function __construct($psViewRoot = 'fo.absen',
    AttendRepositoryInterface $parData,
    UserRepositoryInterface $parDataUsers,
    LocaterInterface $poLocater)
    {
        $this->middleware('auth');
        $roleList = [env('SA_ROLE_CODE'), env('ADM_ROLE_CODE'), env('USR_ROLE_CODE')];
        $this->middleware("check.role:{$roleList[0]},{$roleList[1]},{$roleList[2]}")
        ->only('checkHistoryAdmin');
		$this->uploadDirectory = 'checkpoint';
        $this->sViewRoot = $psViewRoot;
        $this->data = $parData;
        $this->dataUsers = $parDataUsers;
        $this->oLocater = $poLocater;
        
        $this->middleware('auth');

        $this->baseURL = 'https://maps.googleapis.com/maps/api/geocode/json?';
        $this->latlng = null;
        $this->key = '&key=' . env('GEOCODING_APIKEY');

    }

    protected function getFullURL($latitude, $longitude)
    {
        $this->latlng = 'latlng=' . $latitude . ',' . $longitude;

        return $fullURL = $this->baseURL . $this->latlng . $this->key;
    } //end method

    protected function setCity($parData)
    {
        $data = $parData;
        $cityLevel1 = null;
        $cityLevel2 = null;
        $nCount = 0;

        foreach ($data->results[0]->address_components as $key => $component) {

            if ($nCount >= 2)
            {
                break;
            } //end if

            foreach ($component->types as $item) {

                if ($nCount >= 2)
                {
                    break;
                } //end if

                //City Level 1
                if ($item == 'administrative_area_level_1')
                {
                    $cityLevel1 = $component->short_name;
                    $nCount++;
                } //end if

                //City Level 2
                if ($item == 'administrative_area_level_2')
                {
                    $cityLevel2 = $component->short_name;
                    $nCount++;
                } //end if

            } //end loop

        } //end loop

        return $cityLevel2;
    }

    protected function setAddress($parData)
    {
        $data = $parData;
        return $data->results[0]->formatted_address;
    }


    public function show($id)
    {

        $attend = $this->data->find($id);

        $data = [

            'attend' => $attend,
            'user' => $attend->user,

        ];

        $viewModel = Response::viewModel($data);

        return view($this->sViewRoot.'.show',
        ['viewModel' => $viewModel]);

    }
    
    //Check
    protected function check()
    {
        if (Role::deny(Auth::user()->roles, env('ADM_ROLE_CODE'))) {

            return redirect()->route('absen.history.admin');

        } //end if
        
        $user = Auth::user();
        $date = Formater::date(now());
        $dateIso = ConvertDate::strDateToDate($date);

        $attend = $this->data->getOutstandingCheckoutByUserId($user->id);

        if (!$attend) {
            $attend = $this->data->getAttendancesByUserIdAndDate($user->id, $dateIso);
        } //end if
        
        $action = 'absen.checkin.post';
        $actionButton = 'Checkin';
        
        $data = null;
        $data = [
            'action' => $action,
            'action_button' => $actionButton,
            'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'dept' => $user->dept,
                        'attend_id' => null,

                        'checkin_time' => null,
                        'checkin_city' => null,
                        'checkin_address' => null,
                        'checkin_image' => null,
                        'checkin_title' => null,
                        'checkin_subtitle' => null,
                        'checkin_description' => null,

                        'checkout_time' => null,
                        'checkout_city' => null,
                        'checkout_address' => null,
                        'checkout_image' => null,
                        'checkout_title' => null,
                        'checkout_subtitle' => null,
                        'checkout_description' => null,
                    ],
            'date' => null
        ];
        // if ($attend != null)
        if (count($attend) >= 1) {
            $attends = $attend;
            $attend = null;
            foreach ($attends as $item) {
                if ( ($item->checkin_time) && (!$item->checkout_time) ) {
                    
                    $action = 'absen.checkout.post';
                    $actionButton = 'Checkout';
                    $attend = $item;
                    break;

                } //end if
            } //end loop

            if ($attend)
            {
                $data = [
                    'action' => $action,
                    'action_button' => $actionButton,
                    'user' => [
                                'id' => $user->id,
                                'name' => $user->name,
                                'email' => $user->email,
                                'dept' => $user->dept,
                                'attend_id' => $attend->id,
                                
                                'checkin_time' => Formater::time($attend->checkin_time),
                                'checkin_city' => $attend->checkin_city,
                                'checkin_address' => $attend->checkin_address,
                                'checkin_image' => $attend->checkin_image,
                                'checkin_title' => $attend->checkin_title,
                                'checkin_subtitle' => $attend->checkin_subtitle,
                                'checkin_description' => $attend->checkin_description,

                                'checkout_time' => Formater::time($attend->checkout_time),
                                'checkout_city' => $attend->checkout_city,
                                'checkout_address' => $attend->checkout_address,
                                'checkout_image' => $attend->checkout_image,
                                'checkout_title' => $attend->checkout_title,
                                'checkout_subtitle' => $attend->checkout_subtitle,
                                'checkout_description' => $attend->checkout_description,
                            ],
                    'date' => $date
                ];
            } //end if

        } //end if

        $viewModel = Response::viewModel($data);

        return view($this->sViewRoot.'.check',
        ['viewModel' => $viewModel]);

    }

    protected function history($userId=null, $startdt=null, $enddt=null)
    {
        $startDateIso = ConvertDate::strDateToDate($startdt);
        $endDateIso = ConvertDate::strDateToDate($enddt);
        if (isset($endDateIso)) {

            $endDateIso = Carbon::create($endDateIso->year, $endDateIso->month, $endDateIso->day, 25, 0, 0);

        } //end if
        
        $checkpointDate1 = $startDateIso;
        $checkpointDate2 = $endDateIso;

        $data = null;
        $users = $this->dataUsers->withAttends($userId, $checkpointDate1, $checkpointDate2);

        $data['users'] = [];
        foreach ($users as $userKey => $user) {

            array_push($data['users'], [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'dept' => $user->dept,
                    'startdt' => $startdt,
                    'enddt' => $enddt,
                    'attend_list' => [],
                ]
            ]);

            foreach ($user->attends->sortBy('attend_dt') as $attendKey => $value) {
                
                $time_elapse1 = $value->checkin_time->diffInHours($value->checkout_time);
                $time_elapse2 = $value->checkin_time->diff($value->checkout_time)->format('%I:%S');
                array_push($data['users'][$userKey]['user']['attend_list'], [

                    'id' => $value->id,
                    'user_id' => $value->user->id,
                    'name' => $value->user->name,
                    'attend_dt' => Formater::dateMonth($value->attend_dt),
                    
                    'checkin_time' => (!isset($value->checkin_utcmillis)) ? '' :
                    "Tanggal: " . Formater::date($value->checkin_time) .
                    " Jam: " . Formater::time(ConvertDate::DatetimeByTimezone($value->checkin_time, $value->checkin_utctz)) .
                    " " . config('a1.date.timezoneinfo.' . ($value->checkin_utcoffset) .'.short') .
                    " ( " . ConvertDate::millisOffsetDesc($value->checkin_utcoffset) . " ) ",

                    'checkin_address' => $value->checkin_address,
                    'checkin_description' => $value->checkin_description,
                    
                    'checkout_time' => (!isset($value->checkout_utcmillis)) ? '' :
                    "Tanggal ". Formater::date($value->checkout_time) .
                    " Jam: " . Formater::time(ConvertDate::DatetimeByTimezone($value->checkout_time, $value->checkout_utctz)) .
                    " " . config('a1.date.timezoneinfo.' . ($value->checkout_utcoffset) .'.short') .
                    " ( " . ConvertDate::millisOffsetDesc($value->checkout_utcoffset) . " ) ",

                    'checkout_address' => $value->checkout_address,
                    'checkout_description' => $value->checkout_description,
                    'time_elapse' => $time_elapse1 . ':' . $time_elapse2,
                    
                ]);

            } //end foreach attends
            
        } //end foreach users

        $this->viewModel = Response::viewModel($data);
    }

    //checkHistory
    public function checkHistory()
    {
        return view($this->sViewRoot.'.check-history');
    }

    //checkHistory
    public function checkHistoryAdmin()
    {

        // return dd(date_default_timezone_get());

        return view($this->sViewRoot.'.check-history', [
            'admin' => true,
            'users' => $this->dataUsers->all()
        ]);
    }

    //checkHistoryPost
    public function checkHistoryPost(Request $request)
    {
        $user = Auth::user();
        $selectedUserId = $request->input('userid');
        $startdt = $request->input('startdt');
        $enddt = $request->input('enddt');
        $historyMedia = $request->input('history_media');

        $userId = $user->id;
        if (isset($selectedUserId)) {
            $userId = $selectedUserId;
        } //end if

        // return dd([
        //     'user' => $user,
        //     'userId' => $userId,
        //     'selectedUserId' => $selectedUserId,
        //     'startdt' => $startdt,
        //     'enddt' => $enddt,
        //     'historyMedia' => $historyMedia,
        // ]);

        $resultView = null;
        if ($historyMedia == 'view') {
            $resultView = 'check-history-view';
        } //end if

        if ($historyMedia == 'pdf') {
            $resultView = 'check-history-pdf';
        } //end if

        $this->history($userId, $startdt, $enddt);

        if (isset($selectedUserId)) {
            return view($this->sViewRoot.'.'.$resultView,
                ['admin' => true,
                'viewModel' => $this->viewModel
            ]);
        } //end if

        return view($this->sViewRoot.'.'.$resultView,
        ['viewModel' => $this->viewModel]);
    }

    /**
     * Method Name: checkHistory
     * 
     * http method: GET
     * 
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkHistoryPdf()
    {
        $user = Auth::user();

        $date = Formater::date(now());
        $dateIso = ConvertDate::strDateToDate($date);

        $this->history($user->id, $dateIso);

        return view($this->sViewRoot.'.check-history-pdf',
        ['viewModel' => $this->viewModel]);
    }

    /**
     * Method Name: store
     * 
     * http method: POST
     * 
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function checkin(Request $request)
    {
        $authUser = Auth::user();
        $attend = new Attend();
        
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $upload = $request->file('upload'); //upload file (image/document) ==> if included
        $imageTemp = $request->input('imageTemp'); //temporary file uploaded

        //Get UTC
        $utc_tz = $request->input('utc_tz');
        $utc_millis = $request->input('utc_millis');
        $utc_offset = $request->input('utc_offset');

        //validasi upload foto mandatory
        // if (!isset($upload)) {

        //     return redirect('/')->with('status-failed', 'CHECKIN GAGAL - Foto harus dilampirkan')
        //                         ->with('checkin_description', $request->input('checkin_description'));
            
        // } //end if

        //validasi Timezone
        if (!isset($utc_tz) || !isset($utc_millis) || !isset($utc_offset)) {

            return redirect('/')->with('status-failed', 'CHECKIN GAGAL - Aplikasi update versi 3.1.0, Silahkan checkin ulang')
                                ->with('checkin_description', $request->input('checkin_description'));
            
        } //end if

        $host = $this->getFullURL($latitude, $longitude);
        $data = $this->oLocater->locate($host);

        // $data = $parData;
        // $city1 = $data->results[0]->address_components[2]->short_name;
        // $city2 = $data->results[0]->address_components[3]->short_name;
        //return dd($data->results[0]->address_components);

        //convert to JSON
        if ($data) {


            //create temporary uploaded image
            $uploadTemp = Filex::uploadTemp($upload, $imageTemp, null, 'checkin');
            $request->session()->flash('imageTemp', $uploadTemp);

            //copy temporary uploaded image to real path
            $checkin_image = Filex::uploadOrCopyAndRemove('', $uploadTemp, $this->uploadDirectory, $upload, 'public', false, 'checkin');

            $attend->user_id = $authUser->id;
            $attend->attend_dt = now();
            $attend->checkin_time = now();
            $attend->checkin_city = $this->setCity($data);
            $attend->checkin_address = $this->setAddress($data);
            $attend->checkin_latitude = $latitude;
            $attend->checkin_longitude = $longitude;
            $attend->checkin_ip = null;
            $attend->checkin_metadata = json_encode($data);

            if (isset($checkin_image)) {
                $attend->checkin_image = $checkin_image;
            }

            $attend->checkin_title = $request->input('checkin_title');
            $attend->checkin_subtitle = $request->input('checkin_subtitle');
            $attend->checkin_description = $request->input('checkin_description');

            $attend->attend_utctz = $utc_tz;
            $attend->attend_utcmillis = $utc_millis;
            $attend->attend_utcoffset = $utc_offset;

            $attend->checkin_utctz = $utc_tz;
            $attend->checkin_utcmillis = $utc_millis;
            $attend->checkin_utcoffset = $utc_offset;

            $attend->save();

            $response = [
                'message' => 'data absensi tersimpan',
                'result' => $data,
                'metadata' => json_encode($data)
            ];
    
            return redirect('/')->with('status', 'data absensi tersimpan');
    
        } //end if

        $response = [
            'message' => 'data absensi tersimpan',
            'result' => $data,
            'metadata' => json_encode($data)
        ];

        return redirect('/')->with('status-failed', 'data absensi gagal tersimpan');
    }

    /**
     * Method Name: store
     * 
     * http method: POST
     * 
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function checkout(Request $request)
    {

        $attend = Attend::find($request->input('attend_id'));


        if ($attend)
        {

            $latitude = $request->input('latitude');
            $longitude = $request->input('longitude');
            $upload = $request->file('upload'); //upload file (image/document) ==> if included
            $imageTemp = $request->input('imageTemp'); //temporary file uploaded

            //Get UTC
            $utc_tz = $request->input('utc_tz');
            $utc_millis = $request->input('utc_millis');
            $utc_offset = $request->input('utc_offset');

            //validasi upload foto mandatory
            // if (!isset($upload)) {

            //     return redirect('/')->with('status-failed', 'CHECKOUT GAGAL - Foto harus dilampirkan')
            //                          ->with('checkout_description', $request->input('checkout_description'));
                
            // } //end if

            //validasi Timezone
            if (!isset($utc_tz) || !isset($utc_millis) || !isset($utc_offset)) {

                return redirect('/')->with('status-failed', 'CHECKOUT GAGAL - Aplikasi update versi 3.1.0, Silahkan checkout ulang')
                                    ->with('checkout_description', $request->input('checkout_description'));
                
            } //end if
            
            $host = $this->getFullURL($latitude, $longitude);
            $data = $this->oLocater->locate($host);
            if ($data)
            {

                //create temporary uploaded image
                $uploadTemp = Filex::uploadTemp($upload, $imageTemp, null, 'checkin');
                $request->session()->flash('imageTemp', $uploadTemp);

                //copy temporary uploaded image to real path
                $checkout_image = Filex::uploadOrCopyAndRemove('', $uploadTemp, $this->uploadDirectory, $upload, 'public', false, 'checkout');
                
                $attend->checkout_time = now();
                $attend->checkout_city = $this->setCity($data);
                $attend->checkout_address = $this->setAddress($data);
                $attend->checkout_latitude = $latitude;
                $attend->checkout_longitude = $longitude;
                $attend->checkout_ip = null;
                $attend->checkout_metadata = json_encode($data);

                if (!isset($checkout_image)) {

                    $attend->checkout_image = $checkout_image;
                    
                }

                $attend->checkout_title = $request->input('checkout_title');
                $attend->checkout_subtitle = $request->input('checkout_subtitle');
                $attend->checkout_description = $request->input('checkout_description');

                $attend->checkout_utctz = $utc_tz;
                $attend->checkout_utcmillis = $utc_millis;
                $attend->checkout_utcoffset = $utc_offset;


                $attend->save();
                return redirect('/')->with('status', 'data absensi tersimpan');

            } //end if

        } //end if

        return redirect('/')->with('status-failed', 'data absensi gagal tersimpan');
    } //end method

} //end method
