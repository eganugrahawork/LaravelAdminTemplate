<div class="modal-content">
    <form class="form">
        @csrf
        <div class="modal-header" id="kt_modal_add_customer_header">
            <h2 class="fw-bolder">Add a Customer</h2>
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
                <label class="required form-label">Parent</label>
                <select class="form-select form-select-solid select-2" aria-label="Select example" name="parent">
                    <option value="0">Main Parent</option>
                    @foreach ($menu as $m)
                        <option value="{{ $m->id }}">{{ $m->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-10">
                <label class="required form-label">Name</label>
                <input type="text" name="name" class="form-control form-control-solid"
                    placeholder="Example input" />
            </div>
            <div class="mb-10">
                <label class="required form-label">Url</label>
                <input type="text" name="url" class="form-control form-control-solid"
                    placeholder="Example input" />
            </div>
            <div class="mb-10">
                <label class="required form-label">Icon</label>
                <input type="text" name="icon" class="form-control form-control-solid"
                    placeholder="Example input" />
            </div>
            <div class="mb-10">
                <label class="required form-label">Status</label>
                <select class="form-select form-select-solid " aria-label="Select example" name="status">
                    <option value="1">Active</option>
                    <option value="0">Not Active</option>
                </select>
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
    $('.select-2').select2({
        dropdownParent: $('#mainModal')
    });

    $('form').submit(function(event) {
        event.preventDefault();
        $.ajax({
            url: "{{ url('/admin/menu/store') }}",
            type: 'post',
            data: $('form').serialize(), // Remember that you need to have your csrf token included
            dataType: 'json',
            success: function(response) {
                Swal.fire(
                    'Success',
                    response.success,
                    'success'
                )

                contentSetting('menu');
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
