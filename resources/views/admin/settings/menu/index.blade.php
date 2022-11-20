<div class="content flex-row-fluid" id="kt_content">

    <div class="card">
        <h1 class="text-center mt-4 text-gray-600">Menu</h1>
        <hr>
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <div class="d-flex align-items-center position-relative my-1">
                    <span class="svg-icon svg-icon-1 position-absolute ms-6">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none">
                            <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2"
                                rx="1" transform="rotate(45 17.0365 15.1223)" fill="black" />
                            <path
                                d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                fill="black" />
                        </svg>
                    </span>
                    <input type="text" id="searchMenuTable"
                        class="form-control form-control-solid w-250px ps-15" placeholder="Search" />
                </div>
            </div>
            <div class="card-toolbar">
                <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                    <button type="button" class="btn btn-primary" onclick="addModal()">Add Menues</button>
                </div>
            </div>
        </div>
        <div class="card-body pt-0">
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="menuTable">
                <thead>
                    <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">

                        <th class="min-w-125px">No</th>
                        <th class="min-w-125px">Parent</th>
                        <th class="min-w-125px">Name</th>
                        <th class="min-w-125px">Url</th>
                        <th class="min-w-125px">Icon</th>
                        <th class="text-end min-w-70px">Actions</th>
                    </tr>
                    <!--end::Table row-->
                </thead>
                <!--end::Table head-->
                <!--begin::Table body-->
                <tbody class="fw-bold text-gray-600">


                </tbody>
            </table>
        </div>
    </div>
    <div class="modal fade" id="mainModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px" id="contentModal">

        </div>
    </div>

</div>

<script>
    function addModal() {
        $.ajax({
            url: "{{ url('/admin/menu/create') }}",
            type: "get",
            success: function(response) {
                $('#mainModal').modal('toggle');
                $('#contentModal').html(response);
            }
        })
    }

    function editModal(id) {
        $.ajax({
            url: "{{ url('/admin/menu/edit') }}/" + id,
            type: "get",
            success: function(response) {
                $('#mainModal').modal('toggle');
                $('#contentModal').html(response);
            }
        })
    }

    function deleteModal(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('/admin/menu/delete') }}/" + id,
                    type: "get",
                    success: function(response) {
                        Swal.fire(
                            'Deleted!',
                            response.success,
                            'success'
                        )
                        contentSetting('menu');
                    }
                })
            }
        })
    }



    var menuTable = $('#menuTable').DataTable({
        serverside: true,
        processing: true,
        ajax: {
            url: "{{ url('/admin/menu/list') }}"
        },
        columns: [{
                data: 'DT_RowIndex',
                searchable: false
            },
            {
                data: 'parent',
                name: 'parent'
            },
            {
                data: 'name',
                name: 'name'
            },
            {
                data: 'url',
                name: 'url'
            },
            {
                data: 'icon',
                name: 'icon'
            },
            {
                data: 'action',
                name: 'action'
            }
        ],
        "bLengthChange": false,
        "bFilter": true,
        "bInfo": false
    });


    $('#searchMenuTable').keyup(function () {
                menuTable.search($(this).val()).draw()
        });
</script>
