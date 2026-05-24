/**
 * ✅ Reusable Real-Time Unique Field Validator
 * @param {string} model - Model name (e.g. 'Department', 'Designation')
 * @param {string} column - Column name (e.g. 'name', 'code')
 * @param {string} inputSelector - Input selector (e.g. '#department_name')
 * @param {string} [recordId] - Optional encrypted ID for edit forms
 */
function setupRealtimeValidation(model, column, inputSelector, recordId = null) {
    let debounceTimer;
    const $input = $(inputSelector);

    $input.on('input', function () {
        clearTimeout(debounceTimer);
        const value = $(this).val().trim();

        if (value !== "") {
            debounceTimer = setTimeout(function () {
                let url = `/check-unique/${model}/${column}/${value}`;
                if (recordId) url += `/${recordId}`;

                $.get(url, function (response) {
                    $input.removeClass('is-valid is-invalid');
                    $('#error-' + column).remove();

                    if (response.exists) {
                        $input.addClass('is-invalid');
                        $input.after(`<div id="error-${column}" class="invalid-feedback">This ${column} already exists.</div>`);
                    } else {
                        $input.addClass('is-valid');
                    }
                });
            }, 200);
        } else {
            $input.removeClass('is-valid is-invalid');
            $('#error-' + column).remove();
        }
    });
}
