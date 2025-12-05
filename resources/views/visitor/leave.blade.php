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
            <div class="container-fluid">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Leave Scan</h1>
                </div>
                
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Use Webcam</h6>
                            </div>
                            <div class="card-body">
                                <!-- @if ($message = Session::get('success'))
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
                                @endif -->
                                <center>
                                    <div id="reader" style="width: 500px;"></div>
                                    <div class="form-group my-3" style="max-width: 500px;">
                                        <input class="form-control" type="text" id="barcode" name="barcode" autocomplete="off">
                                    </div>
                                </center>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- End of Main Content -->

@include('layout.footer')
</body>
<script src="{{asset('vendor/jquery/jquery-ui.min.js')}}"></script>

<script type="module" src="{{asset('vendor/module/pdf.min.mjs')}}"></script>
<script type="module" src="{{asset('vendor/module/pdf.worker.min.mjs')}}"></script>
<script src="{{asset('vendor/jquery/interact.min.js')}}"></script>

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
        html5QRCodeScanner.render(onScanSuccess);
    });

    function onScanSuccess(decodedText, decodedResult) {
        document.getElementById("barcode").value = decodedText;
        var visitor_code = decodedText;
        html5QRCodeScanner.clear();
        $.ajax({
                url: "{{ route('visitor.check-out') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    visitor_code: visitor_code,
                },
                dataType: "json",
                success: function (data) {
                    if (data.status == "success") {
                        Swal.fire(data.message);
                        window.location.href = "{{ route('visitor.index') }}";
                    } else {
                        Swal.fire('scan failed1');
                        window.location.href = "{{ route('visitor.index') }}";
                    }
                },
                error: function (data) {
                    Swal.fire('scan failed2');
                    window.location.href = "{{ route('visitor.index') }}";
                },
            });
    }
</script>

<script>
    // request use ajax
    // $(document).ready(function () {
    //     $('#barcode').on('change', function () {
    //         var visitor_code = $(this).val();
            
    //     });
    // })
</script>
</html>