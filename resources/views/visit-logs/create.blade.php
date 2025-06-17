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
                    <h1 class="h3 mb-0 text-gray-800">Create Visitor Logs</h1>
                </div>
                <!-- Approach -->
                <form method="post" action="{{ route('visit-logs.store') }}" enctype="multipart/form-data">
                 <div class="row">
                    <div class="col-lg-12">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Form Create Visitor Logs</h6>
                            </div>
                            <div class="card-body">
                                    @csrf
                                    <input class="form-control" type="hidden" id="visit_log_id" name="visit_log_id">
                                    <div id="visitorInput">
                                        <label>Visitor Name :</label>
                                        <div class="row">
                                            <div class="col-xl-12">
                                                    <select class="form-control visitor_name_id" id="visitor_name_id" name="visitor_name_id" >
                                                        <option></option>
                                                        @foreach ($visitors as $visitor )
                                                            <option value="{{ $visitor->id }}">{{ $visitor->name }}</option>
                                                        @endforeach
                                                    </select>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div>
                                        <label for="text">Visit Date</label>
                                        <input class="form-control" type="date" id="visit_date" name="visit_date" value="{{ date('Y-m-d') }}" readonly>
                                    </div>
                                    <br>
                                    <div id="appointerInput">
                                        <label>Appointer Name :</label>
                                        <div class="row">
                                            <div class="col-xl-12">
                                                    <select class="form-control appointer_name_id" id="appointer_name_id" name="appointer_name_id" >
                                                        <option></option>
                                                        @foreach ($users as $user )
                                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                        @endforeach
                                                    </select>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div>
                                        <label for="dept">Dept :</label>
                                        <input class="form-control" type="text" id="dept" name="dept" readonly>
                                    </div>
                                    <br>
                                    <div>
                                        <label for="text">Purpose :</label>
                                        <textarea class="form-control" id="purpose" name="purpose"></textarea>
                                    </div>
                                    <br>
                                    <div id="securityInput">
                                        <label>Security Name :</label>
                                        <div class="row">
                                            <div class="col-xl-12">
                                                    <select class="form-control security_name_id" id="security_name_id" name="security_name_id">
                                                        <option value="{{ Auth::user()->id }}">{{ Auth::user()->name }}</option>
                                                    </select>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div>
                                        <label for="text">Visitor Card ID :</label>
                                        <input class="form-control" type="text" id="visitor_card_id" name="visitor_card_id" value="{{$visitor_card_id->id}}" readonly>
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
    $('.security_name_id').select2({
          allowClear: true,
          placeholder: 'Choose Security',
    });
    $('.visitor_name_id').select2({
          allowClear: true,
          placeholder: 'Choose Visitor Name',
    });
    $('.appointer_name_id').select2({
          allowClear: true,
          placeholder: 'Choose Appointer Name',
    });

    $(document).on("change", "#appointer_name_id", function(e){
        e.preventDefault();
        var appointerName_id = $(this).val();
        if (appointerName_id) {
            $.ajax({
                url: '/visit-logs/fetchDept/'+appointerName_id,
                type: "GET",
                dataType: "json",
                success:function(data) {
                    $('#dept').val(data.dept);
                }
            });
        } else{
            $('#dept').empty();
            $('#dept').attr('disabled','disabled');
        }
    });
</script>
</html>