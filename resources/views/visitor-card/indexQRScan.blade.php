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
                    <h1 class="h3 mb-0 text-gray-800">Visitor Card List</h1>
                    <div>
                        <a href="{{ route('visitor-card.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                            class="fas fa-plus fa-sm text-white-50"></i> Create Visitor Card</a>
                    </div>
                </div>
                
                <!-- DataTales Example -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-sm-flex align-items-center justify-content-between mb-4">
                        <h6 class="m-0 font-weight-bold text-primary">Visitor Card Data</h6>
                        {{-- <form method="GET" id="form-void">
                                <select name="void" id="void" class="form-control" onchange="document.getElementById('form-void').submit()" style="width: 300px;">
                                    <option disabled selected hidden>Select Status</option>
                                    <option value="false" {{ app('request')->input('void') == 'false'  ? 'selected' : ''}}>Active</option>
                                    <option value="true" {{ app('request')->input('void') == 'true'  ? 'selected' : ''}}>Void</option>
                                </select>
                        </form> --}}

                        <div class="d-flex justify-content-end" style="gap: 10px">
                            <form method="GET" id="form-available">
                                <select name="available" id="available" class="form-control" onchange="document.getElementById('form-available').submit()" style="width: 300px;">
                                    <option disabled selected hidden>Select Status Card</option>
                                    <option value="available" {{ app('request')->input('status') == 'available'  ? 'selected' : ''}}>Available</option>
                                    <option value="in-use" {{ app('request')->input('status') == 'in-use'  ? 'selected' : ''}}>In-Use</option>
                                </select>
                            </form>
    
                            {{-- input search and form request--}}
                            <form id="form-search">
                                <div class="input-group" style="width: 300px;">
                                    <input type="text" class="form-control" id="search" name="search" placeholder="Search Visitor Name">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" id="btn-search"><i class="fas fa-search"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach ($visitorCard as $visitorC)
                                <div class="col-md-3 mb-4">
                                    <div class="card bg-white border-radius-10 box-shadow-10 p-20 w-300">
                                        <div class="card-header border-bottom-1 pb-10">
                                            <center>
                                                <img src="{{ asset('img/chutex_logo.png') }}" style="width: 60px;">
                                            </center>
                                            <h2 class="card-title font-size-14 font-weight-bold text-center mt-3">Visitor {{ $visitorC->visitor_number }}</h2>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6 visitor-info">
                                                    <p class="visitor-name font-size-16 font-weight-bold">{{ $visitorC->visitor_name ?? '-'}}</p>
                                                    <p class="visitor-email font-size-14 font-weight-bold">{{$visitorC->visitor_code ?? '-'}}</p>
                                                </div>
                                                <div class="col-md-6 visitor-info">
                                                    <p class="visitor-visit-time font-size-14"><i class="fas fa-clock"></i> {{ $visitorC->visit_time ?? 'Not Scanned'}}</p>
                                                    <p class="visitor-leave-time font-size-14"><i class="fas fa-clock"></i> {{$visitorC->leave_time ?? 'Not Scanned'}}</p>
                                                </div>
                                            </div>
                                            <div class="visitor-stats mb-10">
                                                <p class="stat-label font-size-14 color-666">Status : <a class="stat-value font-size-16 font-weight-bold {{$visitorC->status_card == 'available' ? 'text-success' : 'text-danger'}}">{{$visitorC->status_card}}</a></p>
                                            </div>
                                            <br>
                                            <div class="row">
                                                @if($visitorC->status_card == 'in-use' && $visitorC->visit_time == null)
                                                <p>Please scan visit time</p>
                                                @elseif($visitorC->status_card == 'in-use' && $visitorC->visit_time != null && $visitorC->leave_time == null)
                                                <p>Please scan leave time</p>
                                                @else
                                                <div class="col-sm-8"><a href="{{ route('visit-logs.create', $visitorC->visitor_number) }}" class="btn btn-primary btn-block btn-sm">Use It</a></div>
                                                @endif
                                                <div class="col-sm-4 justify-content-between">
                                                    {{-- button circle visit hijau  --}}
                                                    @if($visitorC->visit_time == null && $visitorC->status_card == 'in-use')
                                                    <a href="#" class="btn btn-success btn-circle btn-sm visitTime" data-toggle="modal" data-target="#visitModal"><i class="fas fa-clock"></i></a>
                                                    @else
                                                    <a href="javascript:void(0)" class="btn btn-secondary btn-circle btn-sm"><i class="fas fa-clock"></i></a>
                                                    @endif

                                                    {{-- button circle visit merah  --}}
                                                    @if($visitorC->leave_time == null && $visitorC->status_card == 'in-use' && $visitorC->visit_time != null)
                                                    <a href="#" class="btn btn-danger btn-circle btn-sm leaveTime" data-toggle="modal" data-target="#leaveModal"><i class="fas fa-clock"></i></a>
                                                    @else
                                                    <a href="javascript:void(0)" class="btn btn-secondary btn-circle btn-sm"><i class="fas fa-clock"></i></a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Pagination --}}
                        <div class="d-flex justify-content-center mt-4">
                            {{ $visitorCard->links() }}
                        </div>
                    </div>
                </div>
                <!-- Content Row -->

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- End of Main Content -->

        <div class="modal fade" id="visitModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document" >
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="comment-title" class="modal-title" id="exampleModalLabel">Visit Time</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">x</span>
                        </button>
                    </div>
                    
                    <div class="modal-body">
                        <input type="hidden" name="date" id="date" value="{{ date('Y-m-d') }}">
                        <div id="reader"></div>
                        <br>
                        @csrf
                        <label>Visitor ID : </label>
                        <input class="form-control" type="text" id="modal_visit_time" name="visitor_id_visit">
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="leaveModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document" >
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="comment-title" class="modal-title" id="exampleModalLabel">Leave Time</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">x</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" name="date" id="date" value="{{ date('Y-m-d') }}">
                        <div id="readerLeave"></div>
                        <br>
                        @csrf
                        <label>Visitor ID : </label>
                        <input class="form-control" type="text" id="modal_leave_time" name="visitor_id_leave">
                    </div>
                </div>
            </div>
        </div>


@include('layout.footer')
</body>
<!-- Page level plugins -->
<script src="{{asset('vendor/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> --}}


<!-- Page level custom scripts -->
<script src="{{asset('js/demo/datatables-demo.js')}}"></script>
<script type="text/javascript">
    $('.btn-delete-record').on('click', function () {
            $('#btn-confirm').attr('href', $(this).data('delete-link'));
            $("#modal-text-record").text('Apakah anda yakin ingin menghapus Approval ' + $(this).data('delete-name') + '?');
    });
    $('.btn-void-record').on('click', function () {
            $("#modal-text-record-void").text('Apakah anda yakin ingin menghapus Visitor ' + $(this).data('void-name') + '?');
    });
    $('.btn-restore-record').on('click', function () {
            $("#modal-text-record-restore").text('Apakah anda yakin ingin mengembalikan Visitor ' + $(this).data('restore-name') + '?');
    });

    $(function () {
        $('body').on('click', '#show-void', function() {
        var jsonVoid = $(this).data('void-url'); 
        $.get(jsonVoid, function (data) {
            if (data.length > 0) {
                $('#modal_visitor_id_void').val(data[0].id);
                } else {

                }
            });
        });
    });
    $(function () {
        $('body').on('click', '#show-restore', function() {
        var jsonRestore = $(this).data('restore-url'); 
        $.get(jsonRestore, function (data) {
            if (data.length > 0) {
                $('#modal_visitor_id_restore').val(data[0].id);
                } else {

                }
            });
        });
    });
</script>

<script type="text/javascript">
    $('#visitModal').on('shown.bs.modal', function () {
        $('#modal_visit_time').focus();
        html5QRCodeScanner.render(onScanSuccess);
    }) 

    $('#leaveModal').on('shown.bs.modal', function () {
        $('#modal_leave_time').focus();
        html5QRCodeScannerLeave.render(onScanSuccessLeave);
    }) 
</script>


<script>
// post untuk update visit time

$(document).on("change", "#modal_visit_time", function(e){
    e.preventDefault();
    var visit_time = $(this).val();
    var visitor_code = $('#modal_visit_time').val();
    
    $.ajax({
        url: '/visit-logs/visit',
        type: "POST",
        data: {
            "_token": "{{ csrf_token() }}",
            "visitor_code": visitor_code
        },
        dataType: "json",
        success: function (data) {
            // tampilkan modal ketika success
            $('#modal_visit_time').val('')
            swal.fire({
                icon: 'success',
                title: 'Visit Time Successed Input',
                text: 'Visit Time Successfully Input',
                showConfirmButton: false,
                timer: 1500
            })
            setTimeout(function() {
                window.location.reload();
            }, 1500);
        },
        error: function (data) {
            $('#modal_visit_time').val('')
            
            swal.fire({
                icon: 'error',
                title: 'Visit Time Failed Input',
                text: 'Visit Time Failed Input',
                showConfirmButton: false,
                timer: 1500
            })
            setTimeout(function() {
                window.location.reload();
            }, 1500);
    }
    });
})

$(document).on("change", "#modal_leave_time", function(e){
    e.preventDefault();
    var visit_time = $(this).val();
    var visitor_code = $('#modal_leave_time').val();
    $.ajax({
        url: '/visit-logs/leave',
        type: "POST",
        data: {
            "_token": "{{ csrf_token() }}",
            "visitor_code": visitor_code
        },
        dataType: "json",
        success: function (data) {
            // tampilkan modal ketika success
            $('#modal_leave_time').val('')
            swal.fire({
                icon: 'success',
                title: 'Leave Time Successed Input',
                text: 'Leave Time Successfully Input',
                showConfirmButton: false,
                timer: 1500
            })
            setTimeout(function() {
                window.location.reload();
            }, 1500);
        },
        error: function (data) {
            $('#modal_leave_time').val('')
            swal.fire({
                icon: 'error',
                title: 'Leave Time Failed Input',
                text: 'Leave Time Failed Input',
                showConfirmButton: false,
                timer: 1500
            })
            setTimeout(function() {
                window.location.reload();
            }, 1500);
    }
    });
})
</script>

{{-- <script type="text/javascript">
    $(document).ready(function() {
        $('#search').on('keyup', debounce(1000,function() {
            var searchValue = $(this).val().toLowerCase();
            $.ajax({
                url: '/visit-logs/search',
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "search": searchValue
                },
                dataType: "json",
                success: function (data) {
                    console.log('success');
                    
                }
            })
        }))
    });
</script> --}}

<script src="{{ asset('vendor/jquery/html5-qrcode.min.js') }}"></script>
<script>
    var date = document.getElementById('date').value;
    let html5QRCodeScanner = new Html5QrcodeScanner(
        "reader", {
            fps: 30,
            qrbox: 300,
            supportedScanTypes: [
                // Html5QrcodeScanType.SCAN_TYPE_FILE, 
                Html5QrcodeScanType.SCAN_TYPE_CAMERA
            ],
        }
    );

    function onScanSuccess(decodedText, decodedResult) {
        // redirect ke link hasil scan
        // var decoder = "canteen?npk=" + decodedResult.decodedText + "&canteen_no=1";
        var decoder = decodedResult.decodedText;
        document.getElementById('modal_visit_time').value = decoder;
        $('#modal_visit_time').trigger('change');
        
        // alert(decoder);
        // window.location.href = decoder;
        html5QRCodeScanner.clear();
    }

    let html5QRCodeScannerLeave = new Html5QrcodeScanner(
        "readerLeave", {
            fps: 30,
            qrbox: 300,
            supportedScanTypes: [
                // Html5QrcodeScanType.SCAN_TYPE_FILE, 
                Html5QrcodeScanType.SCAN_TYPE_CAMERA
            ],
        }
    );

    function onScanSuccessLeave(decodedText, decodedResult) {
        // redirect ke link hasil scan
        // var decoder = "canteen?npk=" + decodedResult.decodedText + "&canteen_no=1";
        var decoder = decodedResult.decodedText;
        document.getElementById('modal_leave_time').value = decoder;
        $('#modal_leave_time').trigger('change');
        
        // alert(decoder);
        // window.location.href = decoder;
        html5QRCodeScanner.clear();
    }
</script>
</html>