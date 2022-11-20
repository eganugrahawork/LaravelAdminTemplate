@extends('admin.layouts.main')

@section('content')
    <div class="success-message" data-successmessage="{{ session('success') }}"></div>
    <div class="fail-message" data-failmessage="{{ session('fail') }}"></div>
    <div class="toolbar py-5 py-lg-5" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-xxl d-flex flex-stack flex-wrap">
            <div class="page-title d-flex flex-column me-3">
                <h1 class="d-flex text-dark fw-bolder my-1 fs-3">Settings</h1>
                <ul class="breadcrumb breadcrumb-dot fw-bold text-gray-600 fs-7 my-1">
                    <li class="breadcrumb-item text-gray-600">
                        <a href="/admin/dashboard" class="text-gray-600 text-hover-primary">Dashboard</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
        <div class="content flex-row-fluid" id="kt_content">
            <div class="row gy-0 gx-10">
                <div class="mb-10">
                    <ul class="nav row mb-10">
                        <li class="nav-item col-12 col-lg mb-5 mb-lg-0">
                            <a class="nav-link btn btn-flex btn-color-gray-800 btn-outline btn-outline-primary btn-active-primary d-flex flex-grow-1  flex-center py-5"
                                onclick="contentSetting('menu')">
                                <!--begin::Svg Icon | path: icons/duotune/abstract/abs027.svg-->
                                <i class="bi bi-three-dots"></i>
                                <!--end::Svg Icon-->
                                <span class="fs-6 fw-bold">Menu</span>
                            </a>
                        </li>
                        <li class="nav-item col-12 col-lg mb-5 mb-lg-0">
                            <a class="nav-link btn btn-flex btn-color-gray-800 btn-outline btn-outline-primary btn-active-primary d-flex flex-grow-1  flex-center py-5"
                                onclick="contentSetting('role')">
                                <!--begin::Svg Icon | path: icons/duotune/general/gen008.svg-->
                                <i class="bi bi-person-rolodex"></i>
                                <!--end::Svg Icon-->
                                <span class="fs-6 fw-bold">Roles</span>
                            </a>
                        </li>
                        <li class="nav-item col-12 col-lg mb-5 mb-lg-0">
                            <a class="nav-link btn btn-flex btn-color-gray-800 btn-outline btn-outline-primary btn-active-primary d-flex flex-grow-1  flex-center py-5"
                                onclick="contentSetting('menuaccess')">
                                <!--begin::Svg Icon | path: icons/duotune/finance/fin006.svg-->
                                <i class="bi bi-door-open"></i>
                                <!--end::Svg Icon-->
                                <span class="fs-6 fw-bold">Access</span>
                            </a>
                        </li>
                    </ul>
                    <!--begin::Tab content-->
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="contentSetting">

                        </div>
                    </div>
                    <!--end::Tab content-->
                </div>


            </div>
            <!--end::Row-->
        </div>
        <!--end::Post-->
    </div>
@endsection


@section('js')
    <script>
        contentSetting('menu');

        function contentSetting(data) {
            $.ajax({
                url: "{{ url('/admin') }}/" + data + "/index",
                type: "get",
                success: function(response) {
                    $('#contentSetting').html(response);
                }
            })
        }

        const successMessage = $('.success-message').data('successmessage')
        const failMessage = $('.fail-message').data('failmessage')

        if (successMessage) {
            Swal.fire(
                'Success',
                successMessage,
                'success'
            )
        }

        if (failMessage) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: failMessage
            })
        }
    </script>
@endsection
