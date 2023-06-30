

let opts = {
  // Whether to scan continuously for QR codes. If false, use scanner.scan() to manually scan.
  // If true, the scanner emits the "scan" event when a QR code is scanned. Default true.
  continuous: true,
  
  // The HTML element to use for the camera's video preview. Must be a <video> element.
  // When the camera is active, this element will have the "active" CSS class, otherwise,
  // it will have the "inactive" class. By default, an invisible element will be created to
  // host the video.
  video: document.getElementById('preview'),
  
  // Whether to horizontally mirror the video preview. This is helpful when trying to
  // scan a QR code with a user-facing camera. Default true.
  mirror: true,
  
  // Whether to include the scanned image data as part of the scan result. See the "scan" event
  // for image format details. Default false.
  captureImage: false,
  
  // Only applies to continuous mode. Whether to actively scan when the tab is not active.
  // When false, this reduces CPU usage when the tab is not active. Default true.
  backgroundScan: true,
  
  // Only applies to continuous mode. The period, in milliseconds, before the same QR code
  // will be recognized in succession. Default 5000 (5 seconds).
  refractoryPeriod: 5000,
  
  // Only applies to continuous mode. The period, in rendered frames, between scans. A lower scan period
  // increases CPU usage but makes scan response faster. Default 1 (i.e. analyze every frame).
  scanPeriod: 1
};

let scanner = new Instascan.Scanner( opts );
scanner.addListener('scan', function (content) {
    // split the data to an array
    let studentArr = content.split(' ');

    // change the value of the ids to the corres indeces of student array
    document.getElementById('firstName').value = studentArr[0];
    document.getElementById('midName').value = studentArr[1];
    document.getElementById('lastName').value = studentArr[2];
    // document.getElementById('section').value = studentArr[3];

    //submit the form for php
    document.forms[0].submit();


});

function onStop() {

    let hideName = document.getElementById("myForm");
    hideName.classList.add("display-hide");

    scanner.stop();
}
// function onStart() {
    Instascan.Camera.getCameras().then(function (cameras) {
    if (cameras.length > 0) {
        scanner.start(cameras[1]);
    } else {
        console.error('No cameras found.');
    }
    }).catch(function (e) {
    console.error(e);
    });
// }