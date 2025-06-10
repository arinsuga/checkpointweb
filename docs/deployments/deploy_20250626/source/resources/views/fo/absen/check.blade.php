@extends('layouts.fo.app')

@section('content_title', \Arins\Facades\Formater::dateMonth(now()))

@section('toolbar')

@endsection

@section('control_sidebar')
@endsection

@section('js')

<script>

//Check error
window.onerror = function (message, url, lineNo){
    alert('Error: ' + message + '\n' + 'Line Number: ' + lineNo);
    return true;
}

//get HTML Element for Input Forms
var frmSubmit = document.getElementById("frmSubmit");
var btnSubmit = document.getElementById("btnSubmit");

//get HTML Element for Location
var latitude = document.getElementById("latitude");
var longitude = document.getElementById("longitude");

//get HTML Element for utc
var utc_tz = document.getElementById("utc_tz");
var utc_millis = document.getElementById("utc_millis");
var utc_offset = document.getElementById("utc_offset");


if ('geolocation' in navigator) {
    btnSubmit.disabled = false;
} else {
    btnSubmit.disabled = true;
    alert('Device anda tidak support Geolocation');
}


let open_camera = document.querySelector("#open-camera");
let close_camera = document.querySelector("#close-camera");
let rotate_camera = document.querySelector("#rotate-camera");

let capture_photo = document.querySelector("#capture-photo");
let cancel_photo = document.querySelector("#cancel-photo");

let video = document.querySelector("#video");
let image_viewer = document.getElementById("imageViewer");
let canvas = document.querySelector("#canvas");
let base64file = document.querySelector("#base64file");
let upload = document.querySelector("#upload");

video.srcObject = null;
let stream = null;
let cameraMode = 'environment'; //default rear camera

async function setCameraOn() {

    stream = await navigator.mediaDevices.getUserMedia({
                        video: { facingMode: cameraMode },
                        audio: false,
                });

    video.srcObject = stream;

}

function refreshCamera() {

    if (stream) {
        stream.getTracks().forEach(function(track) {
            track.stop();
        });    
    } //end if

    video.srcObject = null;
    stream = null;

}

function rotateCamera() {

   if (cameraMode == 'environment') {

        cameraMode = 'user';

   } else {

        cameraMode = 'environment';

   } //end if

    refreshCamera();
    setCameraOn();
}

function capturePhoto() {

    canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
    
    //base64FileString
    let image_data_url = canvas.toDataURL('image/jpeg');
    base64file.value = image_data_url;

    // JPEG file
    let file = null;
    let blob = canvas.toBlob(function(blob) {
                    file = new File([blob], 'temp.jpg', { type: 'image/jpeg' });
                    let container = new DataTransfer();    
                    container.items.add(file);
                    upload.files = container.files;    
                }, 'image/jpeg');

}

function cancelPhoto() {

    canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height);
    base64file.value = null;

}

//#open-camera
open_camera.addEventListener('click', async function() {

    this.classList.add("hide");
    cancel_photo.classList.add("hide");
    close_camera.classList.remove("hide");
    capture_photo.classList.remove("hide");
    rotate_camera.classList.remove("hide");

    image_viewer.classList.add("hide");
    cameraMode = 'environment';
    setCameraOn();
});

//#close-camera
close_camera.addEventListener('click', function() {

    refreshCamera();
    video.classList.remove("hide");
    canvas.classList.add("hide");
    this.classList.add('hide');
    capture_photo.classList.add('hide');
    rotate_camera.classList.add('hide');
    open_camera.classList.remove('hide');
    
});

//#rotate-camera
rotate_camera.addEventListener('click', async function() {

   rotateCamera();

});

//#capture-photo
capture_photo.addEventListener('click', function() {

    capturePhoto();
    this.classList.add("hide");
    video.classList.add("hide");
    canvas.classList.remove("hide");
    cancel_photo.classList.remove("hide");

});

//#cancel-photo
cancel_photo.addEventListener('click', function() {

    cancelPhoto();
    refreshCamera();
    setCameraOn();

    this.classList.add("hide");
    video.classList.remove("hide");
    canvas.classList.add("hide");
    capture_photo.classList.remove("hide");

});

//Event listener
btnSubmit.addEventListener("click", function() {

    //Run getTZ
    getTZ()
    //Run getLocation
    getLocation();

}); //end event

//Methods
function getLocation() {
  
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(showPosition, failLocation);
  } else {
    x.innerHTML = "Geolocation is not supported by this browser.";
    location.reload();
  } //end if

} //end method

function failLocation(error) {
    alert('GPS / Location belum di aktifkan');
}

function showPosition(position) {

    latitude.value = position.coords.latitude;
    longitude.value = position.coords.longitude;
    if ((latitude.value) && (longitude.value)) {
        //Runs Submit Form
        frmSubmit.submit();
    } else {
        alert('GPS / Location belum di aktifkan');
    } //end if


    console.log(latitude.value + ',' + longitude.value);
} //end method

function setTZ() {

    // Get current timezone info
    var now = new Date();
    var utc = new Date(
        now.getUTCFullYear(),
        now.getUTCMonth(),
        now.getUTCDate(),
        now.getUTCHours(),
        now.getUTCMinutes(),
        now.getUTCSeconds(),
        now.getUTCMilliseconds()
    );

    var utc_tz_value = Intl.DateTimeFormat().resolvedOptions().timeZone;
    var utc_millis_value = utc.getTime();
    var utc_offset_value = -now.getTimezoneOffset(); // getTimezoneOffset returns negative values for positive offsets

    utc_tz.value = utc_tz_value;
    utc_millis.value = utc_millis_value;
    utc_offset.value = utc_offset_value / 60;

    return true;
}

function getTZ() {

    // Get current timezone info
    var now = new Date();
    var now_offset_minutes_value = now.getTimezoneOffset(); // getTimezoneOffset returns negative values for positive offsets
    var now_offset_hours_value = now_offset_minutes_value / 60;

    var utc_tz_value = Intl.DateTimeFormat().resolvedOptions().timeZone;
    var utc_offset_value = -now_offset_hours_value;
    var utc_millis_value = now.getTime();

    utc_tz.value = utc_tz_value;
    utc_offset.value = utc_offset_value;
    utc_millis.value = utc_millis_value;

}

function getTZ_OLD_UNUSED_3() {

    // Get current timezone info
    var now = new Date();
    var now_offset_minutes_value = -now.getTimezoneOffset(); // getTimezoneOffset returns negative values for positive offsets
    var now_offset_hours_value = now_offset_minutes_value / 60;

    var utc_tz_value = Intl.DateTimeFormat().resolvedOptions().timeZone;
    var utc_offset_value = now_offset_hours_value;
    var utc_millis_value = now.setHours(now.getHours() - now_offset_hours_value);

    utc_tz.value = utc_tz_value;
    utc_offset.value = utc_offset_value;
    utc_millis.value = utc_millis_value;

}

function getTZ_OLD_UNUSED_2() {

    // Get current timezone info
    var now = new Date();
    var now_offset_minutes_value = -now.getTimezoneOffset(); // getTimezoneOffset returns negative values for positive offsets
    var now_offset_hours_value = now_offset_minutes_value / 60;
    var now_millis_value = now.getTime();

    var utc_tz_value = Intl.DateTimeFormat().resolvedOptions().timeZone;
    var utc_millis_value = now_millis_value - (now_offset_hours_value * 3600000);
    var utc_offset_value = now_offset_hours_value;

    utc_tz.value = utc_tz_value;
    utc_millis.value = utc_millis_value;
    utc_offset.value = utc_offset_value;

}

function getTZ_OLD_UNUSED_1() {

    // Get current timezone info
    var now = new Date();
    var utc = new Date(
        now.getUTCFullYear(),
        now.getUTCMonth(),
        now.getUTCDate(),
        now.getUTCHours(),
        now.getUTCMinutes(),
        now.getUTCSeconds(),
        now.getUTCMilliseconds()
    );

    var utc_tz_value = Intl.DateTimeFormat().resolvedOptions().timeZone;
    var utc_millis_value = utc.getTime();
    var utc_offset_value = -now.getTimezoneOffset(); // getTimezoneOffset returns negative values for positive offsets

    utc_tz.value = utc_tz_value;
    utc_millis.value = utc_millis_value;
    utc_offset.value = utc_offset_value / 60;

}

getTZ();

</script>

@endsection

@section('content')

<nav class="navbar navbar-expand ">
    <ul class="navbar-nav">

        <li class="nav-item" style="border-bottom: 5px solid red;">
            <a class="nav-link" href="{{ route('absen') }}" style="font-weight: bold;">
                Checkpoint
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ route('absen.history') }}" style="font-weight: bold;">
                History
            </a>
        </li>

    </ul>
</nav>


<div class="row">
    <div class="col-sm-12">

        @if ($viewModel->data !=null)
            @include('fo.absen.input')
        @endif

    </div>
</div>

@endsection
