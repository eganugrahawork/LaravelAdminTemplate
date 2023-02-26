"use strict";


$('#title-header').html('Menu');

var pathArray = window.location.pathname.split('/');
// Contoh nilai dari variabel pathArray: ["", "admin", "dashboard"]

var path1 = pathArray[2].charAt(0).toUpperCase() + pathArray[2].slice(1);
var path2 = pathArray[3].charAt(0).toUpperCase() + pathArray[3].slice(1) || "Default";
$('#breadCrumbTitle').html('<li class="breadcrumb-item text-muted"><a href="' + window.location.pathname + '" class="text-muted text-hover-primary">' + path1 + '</a></li><li class="breadcrumb-item"><span class="bullet bg-gray-200 w-5px h-2px"></span></li><li class="breadcrumb-item text-dark">' + path2 + '</li>')


function showAddModal() {
    $.ajax({
        url: "/admin/menu/create",
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

var tableMenu = $('#tableMenu').DataTable({
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
        name: 'icon'
    },
    {
        data: 'status',
        name: 'status'
    },
    {
        data: 'action',
        name: 'action'
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


$('#searchtableMenu').keyup(function() {
    tableMenu.search($(this).val()).draw()
});




