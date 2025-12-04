<!DOCTYPE html>
<html lang="en">
@include('layout.header')
<body id="page-top">
<!-- Page Wrapper -->
@include('sweetalert::alert')
<div id="wrapper">
@include('layout.sidebar')
    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">
            @include('layout.navbar')
            <!-- Begin Page Content -->
            <div class="container-fluid">
                <div id="stepper2" class="bs-stepper">
                    <div class="bs-stepper-header">
                        <div class="step" data-target="#test-l-1">
                            <button type="button" class="btn step-trigger">
                            <span class="bs-stepper-circle">1</span>
                            <span class="bs-stepper-label">Identity Information</span>
                            </button>
                            <br>
                        </div>
                        <div class="line"></div>
                        <div class="step" data-target="#test-l-2">
                            <button type="button" class="btn step-trigger">
                            <span class="bs-stepper-circle">2</span>
                            <span class="bs-stepper-label">Visitor Information</span>
                            </button>
                            <br>
                        </div>
                        <div class="line"></div>
                        <div class="step" data-target="#test-l-3">
                            <button type="button" class="btn step-trigger">
                            <span class="bs-stepper-circle">3</span>
                            <span class="bs-stepper-label">Scan In</span>
                            </button>
                            <br>
                        </div>
                    </div>
                    <div class="bs-stepper-content">
                        <form method="post" action="{{ route('visitor.check-in') }}" enctype="multipart/form-data">
                        <div id="test-l-1" class="content">
                            <!-- Approach -->
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="card shadow mb-4">
                                            <div class="card-header py-3">
                                                <h6 class="m-0 font-weight-bold text-primary">Form Create Visitor</h6>
                                            </div>
                                            <div class="card-body">
                                                    <input class="form-control" type="hidden" id="visitor_id" name="visitor_id">
                                                    <div class="form-group">
                                                        <label for="text">NIK :</label>
                                                        <input class="form-control" type="text" id="nik" name="nik">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="text">Visitor Name :</label>
                                                        <input class="form-control" type="text" id="visitor_name" name="visitor_name">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="text">Alamat :</label>
                                                        <input class="form-control" type="text" id="alamat" name="alamat">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="text">Kelurahan :</label>
                                                        <input class="form-control" type="text" id="kelurahan" name="kelurahan">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="text">Kecamatan :</label>
                                                        <input class="form-control" type="text" id="kecamatan" name="kecamatan">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="text">Kota :</label>
                                                        <input class="form-control" type="text" id="kota" name="kota">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="text">Phone :</label>
                                                        <input class="form-control" type="text" id="phone" name="phone">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="text">Instansi :</label>
                                                        <input class="form-control" type="text" id="instansi" name="instansi">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="text">Number Plate :</label>
                                                        <input class="form-control" type="text" id="number_plate" name="number_plate">
                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                            <div class="card shadow mb-4">
                                                <div class="card-header py-3">
                                                    <h6 class="m-0 font-weight-bold text-primary">Use Webcam</h6>
                                                </div>
                                                <div class="card-body">
                                                    @if ($message = Session::get('success'))
                                                    <div class="alert alert-success alert-block">
                                                        <button type="button" class="close" data-dismiss="alert">×</button>	
                                                        <strong>{{ $message }}</strong>
                                                    </div>
                                                    @endif
                            
                                                    @if ($message = Session::get('error'))
                                                    <div class="alert alert-danger alert-block">
                                                        <button type="button" class="close" data-dismiss="alert">×</button>	
                                                        <strong>{{ $message }}</strong>
                                                    </div>
                                                    @endif
                            
                                                    @if ($message = Session::get('warning'))
                                                    <div class="alert alert-warning alert-block">
                                                        <button type="button" class="close" data-dismiss="alert">×</button>	
                                                        <strong>{{ $message }}</strong>
                                                    </div>
                                                    @endif
                            
                                                    @if ($message = Session::get('info'))
                                                    <div class="alert alert-info alert-block">
                                                        <button type="button" class="close" data-dismiss="alert">×</button>	
                                                        <strong>{{ $message }}</strong>
                                                    </div>
                                                    @endif
                                                    
                                                    <!-- KTP Camera Scanner -->
                                                    <div id="readerKtp" style="width: 100%; max-width: 500px; margin: 0 auto;">
                                                        <div class="video-container-ktp" style="position: relative;">
                                                            <video id="cameraVideo" autoplay playsinline style="width: 100%; border: 3px solid #007bff; border-radius: 10px; background: #000; display: none;"></video>
                                                            <canvas id="cameraCanvas" style="display: none;"></canvas>
                                                            <canvas id="detectionOverlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; display: none;"></canvas>
                                                            
                                                            <div id="scanStatus" style="position: absolute; top: 10px; left: 50%; transform: translateX(-50%); z-index: 10; padding: 8px 16px; border-radius: 15px; font-weight: bold; box-shadow: 0 2px 10px rgba(0,0,0,0.3); background: #ffc107; color: #000; display: none;">
                                                                🔍 Scanning...
                                                            </div>
                                                            
                                                            <div id="stabilityCounter" style="position: absolute; top: 50px; right: 10px; background: rgba(0,0,0,0.7); color: #fff; padding: 5px 10px; border-radius: 5px; font-size: 12px; display: none;">
                                                                <span id="stabilityCount">0</span>/5
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="mt-3">
                                                            <button type="button" class="btn btn-primary btn-block" id="startCameraBtn" onclick="startKTPCamera()">
                                                                📷 Scan KTP with Camera
                                                            </button>
                                                            <button type="button" class="btn btn-danger btn-block" id="stopCameraBtn" onclick="stopKTPCamera()" style="display: none;">
                                                                ⏹️ Stop Camera
                                                            </button>
                                                            <button type="button" class="btn btn-warning btn-block mt-2" id="manualCaptureBtn" onclick="manualCaptureKTP()" style="display: none;">
                                                                📸 Capture Now
                                                            </button>
                                                        </div>
                                                        
                                                        <div id="processingStatus" class="alert alert-info mt-3" style="display: none;">
                                                            <div class="spinner-border spinner-border-sm mr-2" role="status">
                                                                <span class="sr-only">Processing...</span>
                                                            </div>
                                                            Processing KTP data...
                                                        </div>
                                                        
                                                        <div id="capturedPreview" class="mt-3" style="display: none;">
                                                            <img id="previewImage" src="" alt="Captured KTP" style="width: 100%; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                </div>
                            <a class="btn btn-primary" onclick="stepper2.next()">Next</a>
                        </div>
                        <div id="test-l-2" class="content">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card shadow mb-4">
                                        <div class="card-header py-3">
                                            <h6 class="m-0 font-weight-bold text-primary">Form Create Visitor Logs</h6>
                                        </div>
                                        <div class="card-body">
                                            @csrf
                                            <input class="form-control" type="hidden" id="visit_log_id" name="visit_log_id">
                                            <div class="form-group">
                                                <label for="text">Visit Date</label>
                                                <input class="form-control" type="date" id="visit_date" name="visit_date" value="{{ date('Y-m-d') }}" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label>Appointer Name :</label>
                                                <select class="form-control appointer_id" id="appointer_id" name="appointer_id" >
                                                    <option></option>
                                                    @foreach ($employees as $employee )
                                                        <option value="{{ $employee->NPK }}">{{ $employee->NAMA_KARYAWAN }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="dept">Dept :</label>
                                                <input class="form-control" type="text" id="dept" name="dept" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label for="text">Purpose :</label>
                                                <textarea class="form-control" id="purpose" name="purpose"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label>Security Name :</label>
                                                <select class="form-control security_id" id="security_id" name="security_id">
                                                    @foreach ($securities as $security )
                                                        <option value="{{ $security->NPK }}">{{ $security->NAMA_KARYAWAN }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <a class="btn btn-primary" onclick="stepper2.next()">Next</a>
                            <a class="btn btn-primary" onclick="stepper2.previous()">Previous</a>
                        </div>
                        <div id="test-l-3" class="content">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card shadow mb-4">
                                        <div class="card-header py-3">
                                            <h6 class="m-0 font-weight-bold text-primary">Use Webcam</h6>
                                        </div>
                                        <div class="card-body">
                                            @if ($message = Session::get('success'))
                                            <div class="alert alert-success alert-block">
                                                <button type="button" class="close" data-dismiss="alert">×</button>	
                                                <strong>{{ $message }}</strong>
                                            </div>
                                            @endif
                    
                                            @if ($message = Session::get('error'))
                                            <div class="alert alert-danger alert-block">
                                                <button type="button" class="close" data-dismiss="alert">×</button>	
                                                <strong>{{ $message }}</strong>
                                            </div>
                                            @endif
                    
                                            @if ($message = Session::get('warning'))
                                            <div class="alert alert-warning alert-block">
                                                <button type="button" class="close" data-dismiss="alert">×</button>	
                                                <strong>{{ $message }}</strong>
                                            </div>
                                            @endif
                    
                                            @if ($message = Session::get('info'))
                                            <div class="alert alert-info alert-block">
                                                <button type="button" class="close" data-dismiss="alert">×</button>	
                                                <strong>{{ $message }}</strong>
                                            </div>
                                            @endif
                                            <center>
                                                <div id="reader" style="width: 500px;"></div>
                                                <div class="form-group my-3" style="max-width: 500px;">
                                                    <input class="form-control" type="text" id="barcode" name="barcode" autocomplete="off">
                                                </div>
                                            </center>
                                        </div>
                                    </div>
                                </div>
                                <a class="btn btn-primary mx-2" onclick="stepper2.previous()">Previous</a>
                                <a class="btn btn-primary" onclick="stepper2.submit()">Submit</a>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- End of Main Content -->

@include('layout.footer')
</body>
<script src="{{asset('vendor/jquery/jquery-ui.min.js')}}"></script>

<script type="module" src="{{asset('vendor/module/pdf.min.mjs')}}"></script>
<script type="module" src="{{asset('vendor/module/pdf.worker.min.mjs')}}"></script>
<script src="{{asset('vendor/jquery/interact.min.js')}}"></script>

<script>
    $('.security_id').select2({
        allowClear: true,
        placeholder: 'Choose Security Person',
    });

    $('.appointer_id').select2({
        allowClear: true,
        placeholder: 'Choose Appointer',
    });
</script>

 <script>
    $('#appointer_id').change(function() {
        var appointer_id = $(this).val();
        $.ajax({
            url: '/visitor/fetch-employee/' + appointer_id,
            type: 'GET',
            success: function(data) {
                $('#dept').val(data.dept);
            }
        });
    });
 </script>

<script>
    var stepper2Node = document.querySelector('#stepper2')  
    var stepper2 = new Stepper(document.querySelector('#stepper2'), {
          linear: false,
          animation: true
        })
    stepper2Node.addEventListener('show.bs-stepper', function (event) {
        console.warn('show.bs-stepper', event)
    })
    stepper2Node.addEventListener('shown.bs-stepper', function (event) {
        console.warn('shown.bs-stepper', event)
    })
    var stepper3 = new Stepper(document.querySelector('#stepper3'), {
    animation: true
    })
    var stepper4 = new Stepper(document.querySelector('#stepper4'))
</script>

<!-- OpenCV.js for Card Detection -->
<script src="https://docs.opencv.org/4.8.0/opencv.js"></script>

<script>
    // KTP Camera Scanner Implementation
    let ktpStream = null;
    let ktpVideo = document.getElementById('cameraVideo');
    let ktpCanvas = document.getElementById('cameraCanvas');
    let detectionCanvas = document.getElementById('detectionOverlay');
    let ktpCtx = null;
    let detectionCtx = null;
    let detectionInterval = null;
    let stabilityCount = 0;
    let lastRect = null;
    let isProcessing = false;
    
    // Wait for OpenCV to load
    let opencvLoaded = false;
    function waitForOpenCV() {
        if (typeof cv !== 'undefined' && cv.getBuildInformation) {
            opencvLoaded = true;
            console.log('✅ OpenCV.js loaded successfully');
        } else {
            setTimeout(waitForOpenCV, 100);
        }
    }
    waitForOpenCV();
    
    // Start KTP Camera
    async function startKTPCamera() {
        if (!opencvLoaded) {
            alert('Please wait, loading camera detection library...');
            return;
        }
        
        // Reset all scanner states before starting new scan
        resetKTPScanner();
        
        try {
            ktpStream = await navigator.mediaDevices.getUserMedia({
                video: {
                    width: { ideal: 1280 },
                    height: { ideal: 720 },
                    facingMode: 'environment'
                }
            });
            
            ktpVideo.srcObject = ktpStream;
            ktpVideo.style.display = 'block';
            
            ktpVideo.onloadedmetadata = () => {
                ktpCanvas.width = ktpVideo.videoWidth;
                ktpCanvas.height = ktpVideo.videoHeight;
                detectionCanvas.width = ktpVideo.videoWidth;
                detectionCanvas.height = ktpVideo.videoHeight;
                
                ktpCtx = ktpCanvas.getContext('2d');
                detectionCtx = detectionCanvas.getContext('2d');
                
                detectionCanvas.style.display = 'block';
                document.getElementById('scanStatus').style.display = 'block';
                
                startDetectionLoop();
            };
            
            document.getElementById('startCameraBtn').style.display = 'none';
            document.getElementById('stopCameraBtn').style.display = 'block';
            document.getElementById('manualCaptureBtn').style.display = 'block';
            
        } catch (err) {
            console.error('Camera error:', err);
            alert('Unable to access camera. Please check permissions.');
        }
    }
    
    // Stop KTP Camera
    function stopKTPCamera() {
        if (ktpStream) {
            ktpStream.getTracks().forEach(track => track.stop());
            ktpVideo.srcObject = null;
            ktpVideo.style.display = 'none';
        }
        if (detectionInterval) {
            clearInterval(detectionInterval);
            detectionInterval = null;
        }
        detectionCanvas.style.display = 'none';
        document.getElementById('scanStatus').style.display = 'none';
        document.getElementById('stabilityCounter').style.display = 'none';
        document.getElementById('startCameraBtn').style.display = 'block';
        document.getElementById('stopCameraBtn').style.display = 'none';
        document.getElementById('manualCaptureBtn').style.display = 'none';
        resetDetection();
        
        // Ensure complete reset for next scan
        resetKTPScanner();
    }
    
    // Start Detection Loop
    function startDetectionLoop() {
        detectionInterval = setInterval(() => {
            if (!isProcessing) {
                detectKTPCard();
            }
        }, 200);
    }
    
    // Detect KTP Card
    function detectKTPCard() {
        ktpCtx.drawImage(ktpVideo, 0, 0, ktpCanvas.width, ktpCanvas.height);
        
        let src = cv.imread(ktpCanvas);
        let gray = new cv.Mat();
        let blur = new cv.Mat();
        let edges = new cv.Mat();
        let hierarchy = new cv.Mat();
        
        try {
            cv.cvtColor(src, gray, cv.COLOR_RGBA2GRAY);
            cv.GaussianBlur(gray, blur, new cv.Size(5, 5), 0);
            cv.Canny(blur, edges, 50, 150);
            
            let contours = new cv.MatVector();
            cv.findContours(edges, contours, hierarchy, cv.RETR_EXTERNAL, cv.CHAIN_APPROX_SIMPLE);
            
            let maxArea = 0;
            let bestRect = null;
            
            for (let i = 0; i < contours.size(); i++) {
                let contour = contours.get(i);
                let area = cv.contourArea(contour);
                
                if (area > 10000) {
                    let peri = cv.arcLength(contour, true);
                    let approx = new cv.Mat();
                    cv.approxPolyDP(contour, approx, 0.02 * peri, true);
                    
                    if (approx.rows === 4 && area > maxArea) {
                        maxArea = area;
                        bestRect = cv.boundingRect(contour);
                    }
                    approx.delete();
                }
                contour.delete();
            }
            
            detectionCtx.clearRect(0, 0, detectionCanvas.width, detectionCanvas.height);
            
            if (bestRect && maxArea > 20000) {
                let aspectRatio = bestRect.width / bestRect.height;
                
                // KTP aspect ratio is approximately 1.586 (85.6mm x 53.98mm)
                if (aspectRatio > 1.3 && aspectRatio < 2.0) {
                    detectionCtx.strokeStyle = '#00ff00';
                    detectionCtx.lineWidth = 3;
                    detectionCtx.strokeRect(bestRect.x, bestRect.y, bestRect.width, bestRect.height);
                    
                    if (isCardStable(bestRect)) {
                        stabilityCount++;
                        document.getElementById('stabilityCounter').style.display = 'block';
                        document.getElementById('stabilityCount').textContent = stabilityCount;
                        updateScanStatus('detected');
                        
                        if (stabilityCount >= 5) {
                            autoCaptureKTP(bestRect);
                        }
                    } else {
                        stabilityCount = 0;
                        document.getElementById('stabilityCounter').style.display = 'none';
                    }
                    
                    lastRect = bestRect;
                } else {
                    resetDetection();
                }
            } else {
                resetDetection();
            }
            
            contours.delete();
            
        } catch (err) {
            console.error('Detection error:', err);
        } finally {
            src.delete();
            gray.delete();
            blur.delete();
            edges.delete();
            hierarchy.delete();
        }
    }
    
    // Check if card is stable
    function isCardStable(rect) {
        if (!lastRect) return true;
        
        let xDiff = Math.abs(rect.x - lastRect.x);
        let yDiff = Math.abs(rect.y - lastRect.y);
        let wDiff = Math.abs(rect.width - lastRect.width);
        let hDiff = Math.abs(rect.height - lastRect.height);
        
        return xDiff < 10 && yDiff < 10 && wDiff < 10 && hDiff < 10;
    }
    
    // Reset detection
    function resetDetection() {
        stabilityCount = 0;
        lastRect = null;
        document.getElementById('stabilityCounter').style.display = 'none';
        updateScanStatus('scanning');
    }
    
    // Reset entire KTP scanner state (for re-scanning)
    function resetKTPScanner() {
        // Reset processing flag
        isProcessing = false;
        
        // Reset detection variables
        stabilityCount = 0;
        lastRect = null;
        
        // Hide all status elements
        document.getElementById('stabilityCounter').style.display = 'none';
        document.getElementById('processingStatus').style.display = 'none';
        document.getElementById('capturedPreview').style.display = 'none';
        
        // Reset processing status to default
        document.getElementById('processingStatus').className = 'alert alert-info mt-3';
        document.getElementById('processingStatus').innerHTML = '<div class="spinner-border spinner-border-sm mr-2" role="status"><span class="sr-only">Processing...</span></div>Processing KTP data...';
        
        // Clear form fields (optional - uncomment if you want to clear fields on new scan)
        // $('#nik').val('');
        // $('#visitor_name').val('');
        // $('#alamat').val('');
        // $('#kelurahan').val('');
        // $('#kecamatan').val('');
        // $('#kota').val('');
        
        console.log('✅ KTP Scanner state reset');
    }
    
    // Update scan status
    function updateScanStatus(status) {
        let badge = document.getElementById('scanStatus');
        
        switch(status) {
            case 'scanning':
                badge.style.background = '#ffc107';
                badge.style.color = '#000';
                badge.textContent = '🔍 Scanning...';
                break;
            case 'detected':
                badge.style.background = '#28a745';
                badge.style.color = '#fff';
                badge.textContent = '✓ KTP Detected - Hold Steady';
                break;
            case 'capturing':
                badge.style.background = '#007bff';
                badge.style.color = '#fff';
                badge.textContent = '📸 Capturing...';
                break;
        }
    }
    
    // Auto Capture KTP
    function autoCaptureKTP(rect) {
        isProcessing = true;
        updateScanStatus('capturing');
        
        setTimeout(() => {
            ktpCtx.drawImage(ktpVideo, 0, 0, ktpCanvas.width, ktpCanvas.height);
            
            // Crop to detected area with padding
            let padding = 20;
            let cropX = Math.max(0, rect.x - padding);
            let cropY = Math.max(0, rect.y - padding);
            let cropW = Math.min(ktpCanvas.width - cropX, rect.width + padding * 2);
            let cropH = Math.min(ktpCanvas.height - cropY, rect.height + padding * 2);
            
            let croppedCanvas = document.createElement('canvas');
            croppedCanvas.width = cropW;
            croppedCanvas.height = cropH;
            let croppedCtx = croppedCanvas.getContext('2d');
            croppedCtx.drawImage(ktpCanvas, cropX, cropY, cropW, cropH, 0, 0, cropW, cropH);
            
            let base64Image = croppedCanvas.toDataURL('image/jpeg', 0.95);
            
            processKTPImage(base64Image);
            
            clearInterval(detectionInterval);
            detectionCanvas.style.display = 'none';
            resetDetection();
            
        }, 300);
    }
    
    // Manual Capture KTP
    function manualCaptureKTP() {
        ktpCtx.drawImage(ktpVideo, 0, 0, ktpCanvas.width, ktpCanvas.height);
        let base64Image = ktpCanvas.toDataURL('image/jpeg', 0.95);
        processKTPImage(base64Image);
        clearInterval(detectionInterval);
        detectionCanvas.style.display = 'none';
    }
    
    // Process KTP Image via API
    function processKTPImage(base64Image) {
        // Show preview
        document.getElementById('capturedPreview').style.display = 'block';
        document.getElementById('previewImage').src = base64Image;
        
        // Show processing status
        document.getElementById('processingStatus').style.display = 'block';
        
        // Extract base64 data (remove data:image/jpeg;base64, prefix)
        const base64Data = base64Image.split(',')[1];
        
        // Send to Google Apps Script for Gemini processing (no Tesseract OCR)
        fetch('https://script.google.com/macros/s/AKfycbwBmYQQ_EMTJJ_5Ru11iL49vAQ9ydAVBMzPFrEwaLbkyzUGtaaI1u1uPXt5oN3PLglM/exec', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                action: 'process-ktp',
                fileData: base64Data,
                fileName: 'ktp_scan.jpg',
                mimeType: 'image/jpeg'
            })
        })
        .then(response => response.json())
        .then(data => {
            console.log('API Response:', data);
            
            // Auto-fill form fields
            if (data.code === 200) {
                if (data.data.analysis.parsed.nik) {
                    $('#nik').val(data.data.analysis.parsed.nik);
                }
                if (data.data.analysis.parsed.nama) {
                    $('#visitor_name').val(data.data.analysis.parsed.nama);
                }
                if (data.data.analysis.parsed.alamat) {
                    $('#alamat').val(data.data.analysis.parsed.alamat);
                }
                if (data.data.analysis.parsed.kel_desa) {
                    $('#kelurahan').val(data.data.analysis.parsed.kel_desa);
                }
                if (data.data.analysis.parsed.kecamatan) {
                    $('#kecamatan').val(data.data.analysis.parsed.kecamatan);
                }
                if (data.data.analysis.parsed.tempat_tanggal_lahir) {
                    $('#kota').val(data.data.analysis.parsed.tempat_tanggal_lahir);
                }
                
                document.getElementById('processingStatus').className = 'alert alert-success mt-3';
                document.getElementById('processingStatus').innerHTML = '✅ KTP data extracted successfully with Gemini AI!';
                
                setTimeout(() => {
                    document.getElementById('processingStatus').style.display = 'none';
                }, 3000);
            } else {
                showError(data.message || 'Failed to extract KTP data. Please try again.');
            }
        })
        .catch(error => {
            console.error('API Error:', error);
            showError('Error processing KTP. Please try manual entry or scan again.');
        })
        .finally(() => {
            // Reset processing flag BEFORE stopping camera
            isProcessing = false;
            
            // Stop camera and cleanup
            stopKTPCamera();
        });
    }
    
    // Show error message
    function showError(message) {
        document.getElementById('processingStatus').className = 'alert alert-danger mt-3';
        document.getElementById('processingStatus').innerHTML = '❌ ' + message;
        
        setTimeout(() => {
            document.getElementById('processingStatus').style.display = 'none';
            document.getElementById('processingStatus').className = 'alert alert-info mt-3';
        }, 5000);
    }
    
    // Cleanup on page unload
    window.addEventListener('beforeunload', function() {
        stopKTPCamera();
    });
</script>

<script src="{{ asset('vendor/jquery/html5-qrcode.min.js') }}"></script>
<script>
    let html5QRCodeScanner = new Html5QrcodeScanner(
        "reader", {
            fps: 10,
            qrbox: {
                width: 300,
                height: 300,
            },
            supportedScanTypes: [
                Html5QrcodeScanType.SCAN_TYPE_FILE, 
                Html5QrcodeScanType.SCAN_TYPE_CAMERA
            ],
        }
    );



    $(document).ready(function () {
        document.getElementById("barcode").focus();
        $('input[name="barcode"]').blur(function(){
            $('input[name="barcode"]').focus();
        });
        
        var typingTimer;
        var doneTypingInterval = 500;
        var $input = $('#barcode');

        $input.on('keyup', function () {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(doneTyping, doneTypingInterval);
        });

        $input.on('keydown', function () {
            clearTimeout(typingTimer);
        });

        function doneTyping () {
            onSuccessScanner();
        }
        // document.getElementById("barcode").addEventListener("input", onScanSuccess);
    });

    function onSuccessScanner() {
        var decoder = document.getElementById("barcode").value;
        Swal.fire(decoder);
        document.getElementById("barcode").value = '';
    }
    function onScanSuccess(decodedText, decodedResult) {
        // redirect ke link hasil scan
        var decoder = decodedResult.decodedText;
        document.getElementById("barcode").value = decoder;
    }

    html5QRCodeScanner.render(onScanSuccess);
</script>
</html>