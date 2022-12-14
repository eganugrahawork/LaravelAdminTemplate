<div class="modal-content">
    <form class="form">
        @csrf
        <div class="modal-header" id="kt_modal_add_customer_header">
            <h2 class="fw-bolder">Add a Role</h2>
            <div class="btn btn-icon btn-sm btn-active-icon-primary" onclick="tutupModal()">
                <span class="svg-icon svg-icon-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none">
                        <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                            transform="rotate(-45 6 17.3137)" fill="black" />
                        <rect x="7.41422" y="6" width="16" height="2" rx="1"
                            transform="rotate(45 7.41422 6)" fill="black" />
                    </svg>
                </span>
            </div>
        </div>
        <div class="modal-body py-10 px-lg-17">

            <div class="mb-10">
                <label class="required form-label">Role</label>
                <input type="text" name="role" class="form-control form-control-solid"
                    placeholder="Ex: Admin" />
            </div>

        </div>
        <div class="modal-footer flex-center">
            <a onclick="tutupModal()" class="btn btn-light me-3">Discard</a>
            <button type="submit" id="kt_modal_add_customer_submit" class="btn btn-primary">
                <span class="indicator-label">Submit</span>
                <span class="indicator-progress">Please wait...
                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
            </button>
        </div>
    </form>
</div>

<script>

    $('form').submit(function(event) {
        event.preventDefault();
        $.ajax({
            url: "{{ url('/admin/role/store') }}",
            type: 'post',
            data: $('form').serialize(), // Remember that you need to have your csrf token included
            dataType: 'json',
            success: function(response) {
                Swal.fire(
                    'Success',
                    response.success,
                    'success'
                )

                contentSetting('role');
                $('#mainModal').modal('toggle')
            },
            error: function(response) {
                // Handle error
            }
        });
    });

    function tutupModal() {
        $('#mainModal').modal('toggle')
    }
</script>
