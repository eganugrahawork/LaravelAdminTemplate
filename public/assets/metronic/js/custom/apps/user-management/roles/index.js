"use strict";


$('#title-header').html('Roles');

// Contoh nilai dari variabel pathArray: ["", "admin", "dashboard"]


function showAddModal() {
    $.ajax({
        url: "/admin/role/create",
        type: 'get',
        success: function (response) {
            // console.log(response);
            $('#mainModal').modal('toggle')
            $('#contentMainModal').html(response);
        },
        error: function (response) {
            Swal.fire({
                text: "Cannot Open Modal,",
                icon: "error",
                buttonsStyling: !1,
                confirmButtonText: "Ok, got it!",
                customClass: {
                    confirmButton: "btn btn-primary"
                }
            })
        },
    });
}

function showEditModal(id) {
    $.ajax({
        url: "/admin/role/edit/" + id,
        type: 'get',
        success: function (response) {
            // console.log(response);
            $('#mainModal').modal('toggle')
            $('#contentMainModal').html(response);
        },
        error: function (response) {
            Swal.fire({
                text: "Cannot Open Modal,",
                icon: "error",
                buttonsStyling: !1,
                confirmButtonText: "Ok, got it!",
                customClass: {
                    confirmButton: "btn btn-primary"
                }
            })
        },
    });
}

function closeMainModal() {
    Swal.fire({
        text: "Are you sure you would like to close?",
        icon: "warning",
        showCancelButton: !0,
        buttonsStyling: !1,
        confirmButtonText: "Yes, close it!",
        cancelButtonText: "No, return",
        customClass: {
            confirmButton: "btn btn-primary",
            cancelButton: "btn btn-active-light"
        }
    }).then((function (t) {
        $('#mainModal').modal('toggle');
    }))
}

function deleteList(id) {
    Swal.fire({
        text: "Are you sure you want to delete ?",
        icon: "warning",
        showCancelButton: !0,
        buttonsStyling: !1,
        confirmButtonText: "Yes, delete!",
        cancelButtonText: "No, cancel",
        customClass: {
            confirmButton: "btn fw-bold btn-danger",
            cancelButton: "btn fw-bold btn-active-light-primary"
        }
    }).then((function (e) {
        e.value ?
            $.ajax({
                url: "/admin/role/destroy/" + id,
                type: 'get',
                success: function (response) {
                    Swal.fire({
                        text: response.success,
                        icon: "success",
                        buttonsStyling: !1,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    })
                    tableRole.ajax.reload();
                },
                error: function (response) {
                    Swal.fire({
                        text: "Cannot Open Modal,",
                        icon: "error",
                        buttonsStyling: !1,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    })
                },
            })
            : "cancel" === e.dismiss && Swal.fire({
                text: "Data was not deleted.",
                icon: "question",
                buttonsStyling: !1,
                confirmButtonText: "Ok, got it!",
                customClass: {
                    confirmButton: "btn fw-bold btn-primary"
                }
            })
    }))
}

var tableRole = $('#tableRole').DataTable({
    processing: true,
    serverSide: true,
    searching: true,
    ajax: {
        "url": "/admin/menu/list",
        "error": function (response) {
            Swal.fire(
                'Error',
                response.statusText,
                'warning'
            )
        }
    },
    columns: [{
        data: 'DT_RowIndex',
        searchable: false
    },
    {
        data: 'parent_name',
        name: 'parent_name'
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
        name: 'icon',
        className: 'text-center'
    },
    {
        data: 'status',
        name: 'status'
    },
    {
        data: 'action',
        name: 'action',
        className: 'text-end'
    }
    ],
    "language": {
        "processing": '<button class="btn btn-transparent" type="button" disabled><span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>Memuat...</button>'
    },
    "bLengthChange": false,
    "bFilter": true,
    "bInfo": false,
    "orderable": false
});


$('#searchtableRole').keyup(function () {
    tableRole.search($(this).val()).draw()
});




