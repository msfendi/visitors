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
                    <h1 class="h3 mb-0 text-gray-800">Create Visitor Card</h1>
                </div>
                

                <!-- Approach -->
                <form method="post" action="{{ route('visitor-card.store') }}" enctype="multipart/form-data">
                 <div class="row">
                    <div class="col-lg-12">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Form Create Visitor Card</h6>
                            </div>
                            <div class="card-body">
                                    @csrf
                                    <input class="form-control" type="hidden" id="visitor_card_id" name="visitor_card_id">
                                    {{-- <div>
                                        <label for="text">RFID :</label>
                                        <input class="form-control" type="text" id="rfid" name="rfid">
                                    </div> --}}
                                    <div>
                                        <label for="text">Visitor Code :</label>
                                        <input class="form-control" type="text" id="visitor_code" name="visitor_code" value="{{ $newVisitorCode }}" readonly>
                                    </div>
                                    <br>
                                    <div>
                                        <label for="text">Visitor Number :</label>
                                        <input class="form-control" type="text" id="visitor_number" name="visitor_number" value="{{ $visitCardId }}" readonly>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-12">
                                            <button id="submit" type="submit" class="btn btn-primary btn-block">Create</button>
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

<script type="module" src="{{asset('vendor/module/pdf.min.mjs')}}"></script>
<script type="module" src="{{asset('vendor/module/pdf.worker.min.mjs')}}"></script>
<script src="{{asset('vendor/jquery/interact.min.js')}}"></script>

<script type="text/javascript">
    $('.status_card').select2({
          allowClear: true,
          placeholder: 'Choose Status Card',
    });
</script>
</html>