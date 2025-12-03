<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Auto-Detect ID Card Capture</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://docs.opencv.org/4.8.0/opencv.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f5f5f5;
        }
        .video-container {
            position: relative;
            width: 100%;
            max-width: 640px;
            margin: 0 auto;
        }
        #videoElement {
            width: 100%;
            height: auto;
            border: 3px solid #007bff;
            border-radius: 10px;
            background: #000;
        }
        #canvasElement, #detectionCanvas {
            display: none;
        }
        .detection-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
        }
        .detection-box {
            position: absolute;
            border: 3px solid #00ff00;
            box-shadow: 0 0 15px rgba(0, 255, 0, 0.5);
            border-radius: 5px;
        }
        .status-badge {
            position: absolute;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 10;
            padding: 10px 20px;
            border-radius: 20px;
            font-weight: bold;
            box-shadow: 0 2px 10px rgba(0,0,0,0.3);
        }
        .scanning {
            background: #ffc107;
            color: #000;
        }
        .detected {
            background: #28a745;
            color: #fff;
            animation: pulse 1s infinite;
        }
        .capturing {
            background: #007bff;
            color: #fff;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        #results {
            min-height: 400px;
            border: 2px dashed #28a745;
            border-radius: 8px;
            background: #fff;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }
        #results img {
            max-width: 100%;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .instructions {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .detection-count {
            position: absolute;
            top: 50px;
            right: 10px;
            background: rgba(0,0,0,0.7);
            color: #fff;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h1 class="text-center mb-4">🎯 Auto-Detect ID Card Capture</h1>
        
        <div class="instructions">
            <h5>📱 How It Works:</h5>
            <ul class="mb-0">
                <li>Hold your ID card in front of the camera</li>
                <li>The system will automatically detect and highlight the card</li>
                <li>Keep the card steady for 3 seconds</li>
                <li>The image will be captured automatically when stable</li>
            </ul>
        </div>

        <form id="captureForm" method="POST" action="">
            @csrf
            
            <div class="row">
                <!-- Camera Feed -->
                <div class="col-md-6">
                    <div class="card shadow">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">📹 Live Camera Feed</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="video-container">
                                <video id="videoElement" autoplay playsinline></video>
                                <canvas id="canvasElement"></canvas>
                                <canvas id="detectionCanvas" class="detection-overlay"></canvas>
                                
                                <div id="statusBadge" class="status-badge scanning">
                                    🔍 Scanning for ID Card...
                                </div>
                                
                                <div id="detectionCount" class="detection-count" style="display: none;">
                                    Stability: <span id="countText">0</span>/5
                                </div>
                            </div>
                            
                            <div class="p-3">
                                <button type="button" class="btn btn-primary w-100" id="startBtn" onclick="startCamera()">
                                    📷 Start Camera
                                </button>
                                <button type="button" class="btn btn-danger w-100 mt-2" id="stopBtn" onclick="stopCamera()" style="display: none;">
                                    ⏹️ Stop Camera
                                </button>
                                <button type="button" class="btn btn-warning w-100 mt-2" id="manualCaptureBtn" onclick="manualCapture()" style="display: none;">
                                    📸 Manual Capture
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Preview/Results -->
                <div class="col-md-6">
                    <div class="card shadow">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">✅ Captured Image</h5>
                        </div>
                        <div class="card-body">
                            <div id="results">
                                <i class="bi bi-card-image" style="font-size: 64px; color: #ccc;"></i>
                                <p class="text-muted mt-3">Your captured ID card will appear here...</p>
                            </div>
                            
                            <input type="hidden" name="image" class="image-tag">
                            
                            <div class="mt-3">
                                <input type="text" name="name" class="form-control mb-2" placeholder="Cardholder Name (Optional)">
                                <select name="card_type" class="form-control mb-2">
                                    <option value="">Select Card Type</option>
                                    <option value="national_id">National ID</option>
                                    <option value="drivers_license">Driver's License</option>
                                    <option value="passport">Passport</option>
                                    <option value="student_id">Student ID</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-success w-100 mt-2" id="saveBtn" style="display: none;">
                                💾 Save ID Card
                            </button>
                            <button type="button" class="btn btn-info w-100 mt-2" id="retakeBtn" onclick="retake()" style="display: none;">
                                🔄 Capture Another
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <div id="successMessage" class="alert alert-success mt-3" style="display: none;"></div>
    </div>

    <script>
        // Global variables
        let video = document.getElementById('videoElement');
        let canvas = document.getElementById('canvasElement');
        let detectionCanvas = document.getElementById('detectionCanvas');
        let ctx = canvas.getContext('2d');
        let detectionCtx = detectionCanvas.getContext('2d');
        let stream = null;
        let detectionInterval = null;
        let detectionCount = 0;
        let lastDetectedRect = null;
        let isCapturing = false;

        // CSRF token setup
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Start camera
        async function startCamera() {
            try {
                stream = await navigator.mediaDevices.getUserMedia({
                    video: { 
                        width: { ideal: 1280 },
                        height: { ideal: 720 },
                        facingMode: 'environment'
                    }
                });
                
                video.srcObject = stream;
                
                // Wait for video to be ready
                video.onloadedmetadata = () => {
                    // Set canvas sizes to match video
                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;
                    detectionCanvas.width = video.videoWidth;
                    detectionCanvas.height = video.videoHeight;
                    
                    // Show detection canvas
                    detectionCanvas.style.display = 'block';
                    
                    // Start detection loop
                    startDetection();
                };
                
                document.getElementById('startBtn').style.display = 'none';
                document.getElementById('stopBtn').style.display = 'block';
                document.getElementById('manualCaptureBtn').style.display = 'block';
                
            } catch (err) {
                console.error("Error accessing camera:", err);
                alert('Unable to access camera. Please check permissions.');
            }
        }

        // Stop camera
        function stopCamera() {
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
                video.srcObject = null;
            }
            if (detectionInterval) {
                clearInterval(detectionInterval);
            }
            document.getElementById('startBtn').style.display = 'block';
            document.getElementById('stopBtn').style.display = 'none';
            document.getElementById('manualCaptureBtn').style.display = 'none';
            detectionCanvas.style.display = 'none';
            updateStatus('scanning');
        }

        // Start detection loop
        function startDetection() {
            detectionInterval = setInterval(() => {
                if (!isCapturing) {
                    detectCard();
                }
            }, 200); // Check every 200ms
        }

        // Detect card using edge detection
        function detectCard() {
            // Draw current frame to canvas
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            
            // Get image data
            let src = cv.imread(canvas);
            let dst = new cv.Mat();
            let gray = new cv.Mat();
            let blur = new cv.Mat();
            let edges = new cv.Mat();
            let hierarchy = new cv.Mat();
            
            try {
                // Convert to grayscale
                cv.cvtColor(src, gray, cv.COLOR_RGBA2GRAY);
                
                // Apply Gaussian blur
                cv.GaussianBlur(gray, blur, new cv.Size(5, 5), 0);
                
                // Edge detection
                cv.Canny(blur, edges, 50, 150);
                
                // Find contours
                let contours = new cv.MatVector();
                cv.findContours(edges, contours, hierarchy, cv.RETR_EXTERNAL, cv.CHAIN_APPROX_SIMPLE);
                
                // Find largest rectangular contour
                let maxArea = 0;
                let bestRect = null;
                
                for (let i = 0; i < contours.size(); i++) {
                    let contour = contours.get(i);
                    let area = cv.contourArea(contour);
                    
                    // Filter by area (card should be reasonably large)
                    if (area > 10000) {
                        let peri = cv.arcLength(contour, true);
                        let approx = new cv.Mat();
                        cv.approxPolyDP(contour, approx, 0.02 * peri, true);
                        
                        // Check if it's a rectangle (4 corners)
                        if (approx.rows === 4 && area > maxArea) {
                            maxArea = area;
                            bestRect = cv.boundingRect(contour);
                        }
                        
                        approx.delete();
                    }
                    contour.delete();
                }
                
                // Clear detection canvas
                detectionCtx.clearRect(0, 0, detectionCanvas.width, detectionCanvas.height);
                
                if (bestRect && maxArea > 20000) {
                    // Check aspect ratio (ID cards are typically 1.5:1 to 1.7:1)
                    let aspectRatio = bestRect.width / bestRect.height;
                    
                    if (aspectRatio > 1.3 && aspectRatio < 2.0) {
                        // Draw detection rectangle
                        detectionCtx.strokeStyle = '#00ff00';
                        detectionCtx.lineWidth = 3;
                        detectionCtx.strokeRect(bestRect.x, bestRect.y, bestRect.width, bestRect.height);
                        
                        // Check stability
                        if (isStable(bestRect)) {
                            detectionCount++;
                            document.getElementById('detectionCount').style.display = 'block';
                            document.getElementById('countText').textContent = detectionCount;
                            updateStatus('detected');
                            
                            // Auto-capture after 5 stable detections
                            if (detectionCount >= 5) {
                                autoCapture(bestRect);
                            }
                        } else {
                            detectionCount = 0;
                            document.getElementById('detectionCount').style.display = 'none';
                        }
                        
                        lastDetectedRect = bestRect;
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
                dst.delete();
                gray.delete();
                blur.delete();
                edges.delete();
                hierarchy.delete();
            }
        }

        // Check if detection is stable
        function isStable(rect) {
            if (!lastDetectedRect) return true;
            
            let xDiff = Math.abs(rect.x - lastDetectedRect.x);
            let yDiff = Math.abs(rect.y - lastDetectedRect.y);
            let wDiff = Math.abs(rect.width - lastDetectedRect.width);
            let hDiff = Math.abs(rect.height - lastDetectedRect.height);
            
            // Allow small movement tolerance
            return xDiff < 10 && yDiff < 10 && wDiff < 10 && hDiff < 10;
        }

        // Reset detection
        function resetDetection() {
            detectionCount = 0;
            lastDetectedRect = null;
            document.getElementById('detectionCount').style.display = 'none';
            updateStatus('scanning');
        }

        // Auto capture
        function autoCapture(rect) {
            isCapturing = true;
            updateStatus('capturing');
            
            setTimeout(() => {
                // Draw video frame to canvas
                ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
                
                // Crop to detected card area (with padding)
                let padding = 20;
                let cropX = Math.max(0, rect.x - padding);
                let cropY = Math.max(0, rect.y - padding);
                let cropW = Math.min(canvas.width - cropX, rect.width + padding * 2);
                let cropH = Math.min(canvas.height - cropY, rect.height + padding * 2);
                
                // Create cropped canvas
                let croppedCanvas = document.createElement('canvas');
                croppedCanvas.width = cropW;
                croppedCanvas.height = cropH;
                let croppedCtx = croppedCanvas.getContext('2d');
                croppedCtx.drawImage(canvas, cropX, cropY, cropW, cropH, 0, 0, cropW, cropH);
                
                // Convert to base64
                let imageData = croppedCanvas.toDataURL('image/jpeg', 0.95);
                displayCapturedImage(imageData);
                
                // Stop detection
                clearInterval(detectionInterval);
                detectionCanvas.style.display = 'none';
                resetDetection();
                isCapturing = false;
                
            }, 500); // Small delay for visual feedback
        }

        // Manual capture
        function manualCapture() {
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            let imageData = canvas.toDataURL('image/jpeg', 0.95);
            displayCapturedImage(imageData);
            clearInterval(detectionInterval);
            detectionCanvas.style.display = 'none';
        }

        // Display captured image
        function displayCapturedImage(imageData) {
            document.getElementById('results').innerHTML = 
                '<img src="' + imageData + '" class="img-fluid"/>';
            document.querySelector('.image-tag').value = imageData;
            document.getElementById('saveBtn').style.display = 'block';
            document.getElementById('retakeBtn').style.display = 'block';
            updateStatus('captured');
        }

        // Update status badge
        function updateStatus(status) {
            let badge = document.getElementById('statusBadge');
            badge.className = 'status-badge ' + status;
            
            switch(status) {
                case 'scanning':
                    badge.textContent = '🔍 Scanning for ID Card...';
                    break;
                case 'detected':
                    badge.textContent = '✓ ID Card Detected - Hold Steady';
                    break;
                case 'capturing':
                    badge.textContent = '📸 Capturing...';
                    break;
                case 'captured':
                    badge.textContent = '✅ Capture Complete!';
                    break;
            }
        }

        // Retake photo
        function retake() {
            document.getElementById('results').innerHTML = 
                '<i class="bi bi-card-image" style="font-size: 64px; color: #ccc;"></i><p class="text-muted mt-3">Your captured ID card will appear here...</p>';
            document.querySelector('.image-tag').value = '';
            document.getElementById('saveBtn').style.display = 'none';
            document.getElementById('retakeBtn').style.display = 'none';
            detectionCanvas.style.display = 'block';
            startDetection();
            updateStatus('scanning');
        }

        // Handle form submission
        $('#captureForm').submit(function(e) {
            e.preventDefault();
            
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                beforeSend: function() {
                    $('#saveBtn').prop('disabled', true).html('💾 Saving...');
                },
                success: function(response) {
                    if(response.success) {
                        $('#successMessage')
                            .html('<strong>Success!</strong> ' + response.message)
                            .fadeIn();
                        
                        setTimeout(function() {
                            retake();
                            $('#captureForm')[0].reset();
                            $('#successMessage').fadeOut();
                        }, 2000);
                    }
                },
                error: function(xhr) {
                    alert('Error: ' + (xhr.responseJSON?.message || 'Failed to save ID card'));
                },
                complete: function() {
                    $('#saveBtn').prop('disabled', false).html('💾 Save ID Card');
                }
            });
        });

        // Wait for OpenCV.js to load
        let opencvReady = false;
        document.addEventListener('DOMContentLoaded', function() {
            function checkOpenCV() {
                if (typeof cv !== 'undefined' && cv.getBuildInformation) {
                    opencvReady = true;
                    console.log('OpenCV.js is ready!');
                } else {
                    setTimeout(checkOpenCV, 100);
                }
            }
            checkOpenCV();
        });

        // Cleanup on page unload
        window.addEventListener('beforeunload', function() {
            stopCamera();
        });
    </script>
</body>
</html>