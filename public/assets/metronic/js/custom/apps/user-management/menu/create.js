$('.select-2').select2({
    dropdownParent: $('#contentMainModal')
});



var form = document.getElementById('formCreate');
var validator = FormValidation.formValidation(form, {
    fields: {
        parent: {
            validators: {
                notEmpty: {
                    message: "Parent is required"
                },
                integer: {
                    message: "Please Select Your Choice !"
                }
            }
        },
        name: {
            validators: {
                notEmpty: {
                    message: "Menu name is required"
                }
            }
        },
        url: {
            validators: {
                notEmpty: {
                    message: "Menu Url is required"
                }
            }
        },
        icon: {
            validators: {
                notEmpty: {
                    message: "Menu Icon is required"
                }
            }
        },
    },
    plugins: {
        trigger: new FormValidation.plugins.Trigger,
        bootstrap: new FormValidation.plugins.Bootstrap5({
            rowSelector: ".fv-row",
            eleInvalidClass: "is-invalid",
            eleValidClass: "is-valid"
        })
    }
});


var submitButton = document.getElementById('submitForm');
submitButton.addEventListener('click', function(e) {
    // Prevent default button action
    e.preventDefault();

    // Validate form before submit
    if (validator) {
        validator.validate().then(function(status) {
            console.log('status');
            if (status == 'Valid') {

                submitButton.setAttribute("data-kt-indicator", "on");
                submitButton.setAttribute('disabled', true);

                Swal.fire({
                    text: "Submitted, Please Wait For Response !",
                    icon: "success",
                    buttonsStyling: !1,
                    confirmButtonText: "Ok !",
                    customClass: {
                        confirmButton: "btn btn-primary"
                    }
                }).then((function(t) {
                    $.ajax({
                        url: "/admin/menu/store",
                        type: 'post',
                        data: $('#formCreate')
                            .serialize(), // Remember that you need to have your csrf token included
                        dataType: 'json',
                        success: function(response) {
                            Swal.fire({
                                text: response.success,
                                icon: "success",
                                buttonsStyling: !1,
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                }
                            })
                            tableMenu.ajax.reload();
                            $('#mainModal').modal('toggle')
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            if (jqXHR.status === 422) {
                                var errors = jqXHR.responseJSON.errors;
                                Swal.fire({
                                    text: errors[0],
                                    icon: "warning",
                                    buttonsStyling: !1,
                                    confirmButtonText: "Ok, got it!",
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    }
                                })
                            } else {
                                Swal.fire({
                                    text: "Sorry, looks like there are some errors detected, please try again.",
                                    icon: "error",
                                    buttonsStyling: !1,
                                    confirmButtonText: "Ok, got it!",
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    }
                                })
                            }
                            submitButton.setAttribute("data-kt-indicator",
                                "off");
                            submitButton.removeAttribute("disabled");
                        }
                    });

                }))
            } else {
                Swal.fire({
                    text: "Sorry, looks like there are some errors detected, please try again.",
                    icon: "error",
                    buttonsStyling: !1,
                    confirmButtonText: "Ok, got it!",
                    customClass: {
                        confirmButton: "btn btn-primary"
                    }
                })

                submitButton.setAttribute("data-kt-indicator", "off");
                submitButton.removeAttribute("disabled");
            }
        });
    }
});