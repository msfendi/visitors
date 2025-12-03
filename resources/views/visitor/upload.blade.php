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

                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Create Visitor - Upload KTP</h1>
                </div>
                

                <!-- Approach -->
                <form method="post" action="{{ route('visitor.store') }}" enctype="multipart/form-data">
                 <div class="row">
                    <div class="col-lg-6">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Form Create Visitor</h6>
                            </div>
                            <div class="card-body">
                                    @csrf
                                    <input class="form-control" type="hidden" id="visitor_id" name="visitor_id">
                                    <div class="form-group">
                                        <label for="text">Visitor Name :</label>
                                        <input class="form-control" type="text" id="visitor_name" name="visitor_name">
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
                                        <label for="text">Identity Number :</label>
                                        <input class="form-control" type="text" id="identity_number" name="identity_number">
                                    </div>
                                    <div class="form-group">
                                        <label for="text">Number Plate :</label>
                                        <input class="form-control" type="text" id="number_plate" name="number_plate">
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <button id="submit" type="submit" class="btn btn-primary btn-block">Create</button>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Upload KTP Image</h6>
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
                                    
                                    <!-- KTP File Upload Section -->
                                    <div id="uploadSection">
                                        <div class="upload-area" id="uploadArea" style="border: 3px dashed #007bff; border-radius: 10px; padding: 40px; text-align: center; cursor: pointer; background: #f8f9fc; transition: all 0.3s;">
                                            <div class="upload-icon" style="font-size: 48px; color: #007bff; margin-bottom: 15px;">
                                                📁
                                            </div>
                                            <h5 class="text-primary">Click to Upload KTP Image</h5>
                                            <p class="text-muted mb-0">or drag and drop your KTP image here</p>
                                            <p class="text-muted small mt-2">Supported formats: JPG, PNG, JPEG</p>
                                            <input type="file" id="ktpFileInput" accept="image/*" style="display: none;">
                                        </div>
                                        
                                        <div id="uploadedPreview" class="mt-3" style="display: none;">
                                            <img id="uploadedImage" src="" alt="Uploaded KTP" style="width: 100%; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                                            <div class="mt-3">
                                                <button type="button" class="btn btn-success btn-block" id="processUploadBtn" onclick="processUploadedKTP()">
                                                    🔍 Process KTP Image
                                                </button>
                                                <button type="button" class="btn btn-secondary btn-block" id="changeImageBtn" onclick="resetUpload()">
                                                    🔄 Choose Another Image
                                                </button>
                                            </div>
                                        </div>
                                        
                                        <div id="uploadProcessingStatus" class="alert alert-info mt-3" style="display: none;">
                                            <div class="spinner-border spinner-border-sm mr-2" role="status">
                                                <span class="sr-only">Processing...</span>
                                            </div>
                                            Processing KTP data with Gemini AI...
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
            </form>

                <!-- Content Row -->

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- End of Main Content -->

@include('layout.footer')
</body>
<script src="{{asset('vendor/jquery/jquery-ui.min.js')}}"></script>

<script type="text/javascript">
    $('.product_id').select2({
          allowClear: true,
          placeholder: 'Choose Product Item',
    });
    $('.handover_name_id').select2({
          allowClear: true,
          placeholder: 'Choose Approval',
    });
    $('.receiver_name_id').select2({
          allowClear: true,
          placeholder: 'Choose Receiver Name',
    });
</script>

<script>
    // KTP File Upload Implementation
    let uploadedFile = null;
    let uploadedBase64 = null;
    
    const uploadArea = document.getElementById('uploadArea');
    const fileInput = document.getElementById('ktpFileInput');
    const uploadedPreview = document.getElementById('uploadedPreview');
    const uploadedImage = document.getElementById('uploadedImage');
    
    // Click to upload
    uploadArea.addEventListener('click', () => {
        fileInput.click();
    });
    
    // Drag and drop functionality
    uploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadArea.style.borderColor = '#0056b3';
        uploadArea.style.background = '#e7f1ff';
    });
    
    uploadArea.addEventListener('dragleave', (e) => {
        e.preventDefault();
        uploadArea.style.borderColor = '#007bff';
        uploadArea.style.background = '#f8f9fc';
    });
    
    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.style.borderColor = '#007bff';
        uploadArea.style.background = '#f8f9fc';
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            handleFileSelect(files[0]);
        }
    });
    
    // File input change
    fileInput.addEventListener('change', (e) => {
        if (e.target.files.length > 0) {
            handleFileSelect(e.target.files[0]);
        }
    });
    
    // Handle file selection
    function handleFileSelect(file) {
        // Validate file type
        const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        if (!validTypes.includes(file.type)) {
            alert('Please upload a valid image file (JPG, PNG, or JPEG)');
            return;
        }
        
        // Validate file size (max 5MB)
        const maxSize = 5 * 1024 * 1024; // 5MB
        if (file.size > maxSize) {
            alert('File size must be less than 5MB');
            return;
        }
        
        uploadedFile = file;
        
        // Read and display image
        const reader = new FileReader();
        reader.onload = function(e) {
            uploadedBase64 = e.target.result;
            uploadedImage.src = uploadedBase64;
            uploadArea.style.display = 'none';
            uploadedPreview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
    
    // Reset upload
    function resetUpload() {
        uploadedFile = null;
        uploadedBase64 = null;
        fileInput.value = '';
        uploadArea.style.display = 'block';
        uploadedPreview.style.display = 'none';
        document.getElementById('uploadProcessingStatus').style.display = 'none';
    }
    
    // Process uploaded KTP
    function processUploadedKTP() {
        if (!uploadedBase64) {
            alert('Please upload an image first');
            return;
        }
        
        // Show processing status
        document.getElementById('uploadProcessingStatus').style.display = 'block';
        document.getElementById('processUploadBtn').disabled = true;
        
        // Extract base64 data (remove data:image/jpeg;base64, prefix)
        const base64Data = uploadedBase64.split(',')[1];
        
        // Send to Google Apps Script for Gemini processing
        fetch('https://script.google.com/macros/s/AKfycbwz3TxE3EL92oqDbh_WQupnc8VnFGlAvQ-Wli77sgATr1YFGjy0bmmZNL0qkHSds-8k/exec', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                action: 'process-ktp',
                fileData: base64Data,
                fileName: uploadedFile.name,
                mimeType: uploadedFile.type
            })
        })
        .then(response => response.json())
        .then(data => {
            // Auto-fill form fields
            if (data.code === 200) {
                if (data.data.analysis.parsed.nik) {
                    $('#identity_number').val(data.data.analysis.parsed.nik);
                }
                if (data.data.analysis.parsed.nama) {
                    $('#visitor_name').val(data.data.analysis.parsed.nama);
                }
                if (data.data.analysis.parsed.alamat) {
                    $('#instansi').val(data.data.analysis.parsed.alamat);
                }
                
                // You can add more field mappings as needed
                
                document.getElementById('uploadProcessingStatus').className = 'alert alert-success mt-3';
                document.getElementById('uploadProcessingStatus').innerHTML = '✅ KTP data extracted successfully with Gemini AI!';
                
                setTimeout(() => {
                    document.getElementById('uploadProcessingStatus').style.display = 'none';
                }, 3000);
            } else {
                showUploadError(data.message || 'Failed to extract KTP data. Please try again.');
            }
        })
        .catch(error => {
            console.error('API Error:', error);
            showUploadError('Error processing KTP. Please try manual entry.');
        })
        .finally(() => {
            document.getElementById('processUploadBtn').disabled = false;
        });
    }
    
    // Show upload error message
    function showUploadError(message) {
        document.getElementById('uploadProcessingStatus').className = 'alert alert-danger mt-3';
        document.getElementById('uploadProcessingStatus').innerHTML = '❌ ' + message;
        document.getElementById('uploadProcessingStatus').style.display = 'block';
        
        setTimeout(() => {
            document.getElementById('uploadProcessingStatus').style.display = 'none';
            document.getElementById('uploadProcessingStatus').className = 'alert alert-info mt-3';
        }, 5000);
    }
</script>
</html>
