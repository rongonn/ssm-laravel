$(document).ready(() => {
    initalRequiredSign();
    $('a[data-act="ajax-model"]').click(function (e) {
        e.preventDefault();
        var url = $(this).data('action-url');
        $('#ajax-model .modal-content').load(url, function () {
            $('#ajax-model').modal('toggle');
        });
    });
});

const initalRequiredSign = () => {
    const elements = $('input[required],select[required]');
    for (const element of elements) {
        const parent = $(element.closest('[class^="mb-"]'));
        if (parent.find('label>.text-danger').length < 1) {
            parent.find('label').append(` <span class="text-danger">*</span>`)
        }
    }
}