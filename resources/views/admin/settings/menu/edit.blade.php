<div class="modal-header">
    <h2 class="fw-bolder">Edit Menu</h2>
    <div class="btn btn-icon btn-sm btn-active-icon-primary" onclick="closeMainModal()">
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
<div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
    <form id="formEdit" class="form">
        @csrf
        <input type="hidden" name="id" value="{{ $onMenu->id }}">
        <div class="scroll h-450px px-2">
            <div class="fv-row mb-7">
                <label class="fs-6 fw-bold form-label mb-2">
                    <span class="required">Parent</span>
                    <i class="fas fa-exclamation-circle ms-2 fs-7"
                        title="Permission parent is required to be unique."></i>
                </label>
                <select class="form-select form-select-solid select-2" aria-label="Select Parent" name="parent">
                    <option selected disabled>Select Parent</option>
                    <option value="0" {{ $onMenu->parent == 0 ? 'selected' : '' }}>Main Parent</option>
                    @foreach ($menu as $m)
                        <option value="{{ $m->id }}" {{ $onMenu->parent == $m->id ? 'selected' : '' }}>
                            {{ $m->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="fv-row mb-7">
                <label class="fs-6 fw-bold form-label mb-2">
                    <span class="required">Name</span>
                    <i class="fas fa-exclamation-circle ms-2 fs-7"
                        title="Permission names is required to be unique."></i>
                </label>
                <input class="form-control form-control-solid" value="{{ $onMenu->name }}" name="name" />
            </div>
            <div class="fv-row mb-7">
                <label class="fs-6 fw-bold form-label mb-2">
                    <span class="required">Url</span>
                    <i class="fas fa-exclamation-circle ms-2 fs-7" title="Permission url is required to be unique."></i>
                </label>
                <input class="form-control form-control-solid" value="{{ $onMenu->url }}" name="url" />
            </div>
            <div class="fv-row mb-7">
                <label class="fs-6 fw-bold form-label mb-2">
                    <span class="required">Icon</span>
                    <i class="fas fa-exclamation-circle ms-2 fs-7"
                        title="Permission Icon is required to be unique."></i>
                </label>
                <div class="position-relative">
                    <input class="form-control form-control-solid" value="{{ $onMenu->icon }}" name="icon" />
                    <div class="position-absolute translate-middle-y top-50 end-0 me-3">
                        <i class='bi bi-{{ $onMenu->icon }}'></i>
                    </div>
                </div>
            </div>
            <div class="fv-row mb-7">
                <label class="fs-6 fw-bold form-label mb-2">
                    <span class="required">Status</span>
                    <i class="fas fa-exclamation-circle ms-2 fs-7"
                        title="Permission parent is required to be unique."></i>
                </label>
                <select class="form-select form-select-solid select-2" aria-label="Select Parent" name="status">
                    <option value="0" {{ $onMenu->status == 0 ? 'selected' : '' }}>Not Active</option>
                    <option value="1" {{ $onMenu->status == 1 ? 'selected' : '' }}>Active</option>
                   
                </select>
            </div>
            <div class="text-gray-600">Attention,
                <strong class="me-1">URL and Icon</strong> can have null value, you can fill with
                <strong class="me-1"> "-" </strong>in form input
            </div>
           
        </div>
        <div class="text-center pt-15">
            <button type="button" class="btn btn-light me-3" onclick="closeMainModal()">Discard</button>
            <button type="submit" class="btn btn-primary" id="submitForm">
                <span class="indicator-label">Update</span>
                <span class="indicator-progress">Please wait...
                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
            </button>
        </div>
    </form>

</div>

<script src="/assets/metronic/js/custom/apps/user-management/menu/edit.js"></script>
