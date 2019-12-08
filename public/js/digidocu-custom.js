$(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();
    $(".select2").select2();
    $('.b-wysihtml5-editor').wysihtml5();
    registerIcheck();
    registerTypeahead();
});

function registerIcheck() {
    $('input.iCheck-helper').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
        increaseArea: '20%' // optional
    });
}

function registerTypeahead() {
    $(".typeahead").each(function () {
        var inputTypeahead = $(this);
        var sourcedt = inputTypeahead.data('source');
        if(typeof sourcedt !== undefined && sourcedt!==null)
            inputTypeahead.typeahead({
                source: sourcedt,
                autoSelect: true
            });
    });
}

function conformDel(aa, event) {
    event.preventDefault();
    swal({
        title: "Are you sure?",
        text: "Once you click, Action can't be undo!",
        icon: "error",
        buttons: true,
        dangerMode: true,
    }).then((willDelete) => {
        if (willDelete) {
            aa.form.submit();
        }
    });
    return false;
}

function readURL(input, preview_selector) {
    var image_types = ["jpg", "jpeg", "gif", "png", "bmp"];
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            preview_selector.attr('src', e.target.result);
        };
        if (image_types.includes(getExtension(input.files[0].name))) {
            reader.readAsDataURL(input.files[0]);
        } else {
            preview_selector.attr('src', "http://dummyimage.com/400x200/3c8dbc/FFFFFF&text=" + input.files[0].name);
        }
    }
}

function getExtension(filename) {
    var parts = filename.split('.');
    return parts[parts.length - 1].toLowerCase();
}



