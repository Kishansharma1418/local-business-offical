
function handleModalFormSubmit(formSelector, modalSelector, tableSelector, successMessage) {
    $(document).off('submit', formSelector).on('submit', formSelector, function (e) {
        e.preventDefault();
        let form = $(this);
        let url = form.find('input[type=hidden][id$="_route"]').val();
        let submitBtn = form.find('button[type=submit]');
        let formData = form.serialize();

        form.find('.is-invalid').removeClass('is-invalid');
        form.find('.is-valid').removeClass('is-valid');
        form.find('.invalid-feedback').remove();

        let isValid = true;
        form.find('[data-rule-required="true"]').each(function () {
            let input = $(this);
            if ($.trim(input.val()) === '') {
                isValid = false;
                input.addClass('is-invalid');
                input.removeClass('is-valid');
                if (input.next('.invalid-feedback').length === 0) {
                    input.after('<div class="invalid-feedback d-block">This field is required.</div>');
                }
            } else {
                input.removeClass('is-invalid').addClass('is-valid');
                input.next('.invalid-feedback').remove();
            }
        });

        if (!isValid) {
            toastr.error('Please fill in all required fields.');
            return;
        }

        submitBtn.prop('disabled', true).html('<i class="spinner-border spinner-border-sm"></i> Saving...');

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            success: function (response) {
                submitBtn.prop('disabled', false).html('Save');
                if (response.status == true) {
                    toastr.success(successMessage || response.message);
                    $(modalSelector).modal('hide');
                    form[0].reset();
                    form.find('.is-valid').removeClass('is-valid');
                    if ($.fn.DataTable.isDataTable(tableSelector)) {
                        $(tableSelector).DataTable().ajax.reload(null, false);
                    }
                } else {
                    toastr.error(response.message || 'Something went wrong');
                }
            },
            error: function (xhr) {
                submitBtn.prop('disabled', false).html('Save');
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    $.each(errors, function (key, value) {
                        let input = form.find('[name="' + key + '"]');
                        input.addClass('is-invalid');
                        input.removeClass('is-valid');
                        if (input.next('.invalid-feedback').length === 0) {
                            input.after('<div class="invalid-feedback d-block">' + value[0] + '</div>');
                        }
                    });
                } else {
                    toastr.error('Server error: ' + xhr.status);
                }
            }
        });
    });

    $(document).off('input change', formSelector + ' [data-rule-required="true"]').on('input change', formSelector + ' [data-rule-required="true"]', function () {
        let input = $(this);
        if ($.trim(input.val()) === '') {
            input.addClass('is-invalid').removeClass('is-valid');
            if (input.next('.invalid-feedback').length === 0) {
                input.after('<div class="invalid-feedback d-block">This field is required.</div>');
            }
        } else {
            input.removeClass('is-invalid').addClass('is-valid');
            input.next('.invalid-feedback').remove();
        }
    });
}


function handleModalUpdateSubmit(formSelector, modalSelector, tableSelector, successMessage) {
    $(document).off('submit', formSelector).on('submit', formSelector, function (e) {
        e.preventDefault();
        let form = $(this);
        let url = form.find('input[type=hidden][id$="_route"]').val();
        let submitBtn = form.find('button[type=submit]');
        let formData = form.serialize();

        form.find('.is-invalid').removeClass('is-invalid');
        form.find('.is-valid').removeClass('is-valid');
        form.find('.invalid-feedback').remove();

        let isValid = true;
        form.find('[data-rule-required="true"]').each(function () {
            let input = $(this);
            if ($.trim(input.val()) === '') {
                isValid = false;
                input.addClass('is-invalid').removeClass('is-valid');
                if (input.next('.invalid-feedback').length === 0) {
                    input.after('<div class="invalid-feedback d-block">This field is required.</div>');
                }
            } else {
                input.removeClass('is-invalid').addClass('is-valid');
                input.next('.invalid-feedback').remove();
            }
        });

        if (!isValid) {
            toastr.error('Please fill in all required fields.');
            return;
        }

        submitBtn.prop('disabled', true).html('<i class="spinner-border spinner-border-sm"></i> Updating...');

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            success: function (response) {
                submitBtn.prop('disabled', false).html('Update');
                if (response.status) {
                    toastr.success(successMessage || response.message || 'Updated successfully');
                    $(modalSelector).modal('hide');
                    form[0].reset();
                    form.find('.is-valid').removeClass('is-valid');
                    if ($.fn.DataTable.isDataTable(tableSelector)) {
                        $(tableSelector).DataTable().ajax.reload(null, false);
                    }
                } else {
                    toastr.error(response.message || 'Something went wrong');
                }
            },
            error: function (xhr) {
                submitBtn.prop('disabled', false).html('Update');
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    $.each(errors, function (key, value) {
                        let input = form.find('[name="' + key + '"]');
                        input.addClass('is-invalid').removeClass('is-valid');
                        if (input.next('.invalid-feedback').length === 0) {
                            input.after('<div class="invalid-feedback d-block">' + value[0] + '</div>');
                        }
                    });
                } else {
                    toastr.error('Server error: ' + xhr.status);
                }
            }
        });
    });

    $(document).off('input change', formSelector + ' [data-rule-required="true"]').on('input change', formSelector + ' [data-rule-required="true"]', function () {
        let input = $(this);
        if ($.trim(input.val()) === '') {
            input.addClass('is-invalid').removeClass('is-valid');
            if (input.next('.invalid-feedback').length === 0) {
                input.after('<div class="invalid-feedback d-block">This field is required.</div>');
            }
        } else {
            input.removeClass('is-invalid').addClass('is-valid');
            input.next('.invalid-feedback').remove();
        }
    });
}

$(document).on('hidden.bs.modal', function () {
    if ($('.modal.show').length === 0) {
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open');
        $('body').css({
            'padding-right': '',
            'overflow': 'auto'
        });
    }
});

$(document).on('click', "[id='edit_value']", function (event) {
    var route = $(this).attr('data-route');
    axios.get(route).then(function (response) {
        $('#edit_model').html(response.data);


        $('#edit_model').modal('toggle').addClass('show');
    })
});

$(document).ready(function () {
    if (typeof window.laravelSuccess !== 'undefined' && window.laravelSuccess) {
        toastr.success(window.laravelSuccess);
    }

    if (typeof window.laravelError !== 'undefined' && window.laravelError) {
        toastr.error(window.laravelError);
    }
});


$('form').on('submit', function (e) {
    let form = $(this)[0];

    if (!form.checkValidity()) {
        e.preventDefault();
        e.stopPropagation();
        $(this).addClass('was-validated');
        return false;
    }

    $(this).find('button[type="submit"]')
        .prop('disabled', true)
        .text('Processing...');
});
