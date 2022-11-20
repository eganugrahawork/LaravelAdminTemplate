<div class="modal-content">
    <div class="modal-header" id="kt_modal_add_customer_header">
        <h2 class="fw-bolder">Role </h2>
        <div class="btn btn-icon btn-sm btn-active-icon-primary" onclick="tutupModal()">
            <span class="svg-icon svg-icon-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                        transform="rotate(-45 6 17.3137)" fill="black" />
                    <rect x="7.41422" y="6" width="16" height="2" rx="1"
                        transform="rotate(45 7.41422 6)" fill="black" />
                </svg>
            </span>
        </div>
    </div>
    <div class="p-6">
        <div class="d-flex align-items-center position-relative my-1">
            <span class="svg-icon svg-icon-1 position-absolute ms-6">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none">
                    <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1"
                        transform="rotate(45 17.0365 15.1223)" fill="black" />
                    <path
                        d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                        fill="black" />
                </svg>
            </span>
            <input type="text" id="searchMenuAccessTable" class="form-control form-control-solid w-250px ps-15"
                placeholder="Search" />
        </div>
        <table class="table align-middle table-row-dashed fs-6 gy-5" id="menuAccessTable">
            <thead>
                <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">

                    <th class="min-w-125px">No</th>
                    <th class="min-w-125px">Menu</th>
                    <th class="min-w-70px">Access</th>
                </tr>
            </thead>
            <tbody class="fw-bold text-gray-600">

            </tbody>
        </table>
    </div>

    <div class="modal-footer flex-center">
        <a onclick="tutupModal()" class="btn btn-info me-3">Done</a>

    </div>
</div>

<script>
    var role_id = {{ $id }}
    var menuAccessTable = $('#menuAccessTable').DataTable({
        serverside: true,
        processing: true,
        ajax: {
            url: "{{ url('/admin/menuaccess/listaccess') }}/" + role_id
        },
        columns: [{
                data: 'DT_RowIndex',
                searchable: false
            },
            {
                data: 'name',
                name: 'name'
            },
            {
                data: 'access',
                name: 'access'
            }
        ],
        "pageLength": 5,
        "bLengthChange": false,
        "bFilter": true,
        "bInfo": false
    });

    $('#searchMenuAccessTable').keyup(function() {
        menuAccessTable.search($(this).val()).draw()
    });

    function tutupModal() {
        $('#mainModal').modal('toggle')
    }

    function changeAccess(menuId, roleId) {
        var data = {
            'menu_id': menuId,
            'role_id': roleId
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        Swal.fire({
            title: 'Are you sure?',
            text: "Want to edit this Access!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Change!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('/admin/menuaccess/changeaccess') }}",
                    type: "post",
                    data: data,
                    success: function(response) {
                        Swal.fire(
                            'Changed!',
                            response.success,
                            'success'
                        )
                        $('#mainModal').modal('toggle');
                        $('#contentModal').html(response);
                    }
                })
            }
        })




    }
</script>
