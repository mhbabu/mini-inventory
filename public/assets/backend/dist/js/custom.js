/*************************
 SWEET ALERT SCRIPT HERE
 *************************/
function warnBeforeAction(URL, redirectURL) {
    swal({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-primary",
            confirmButtonText: "Yes, proceed!",
            cancelButtonText: "No, cancel!",
            cancelButtonClass: "btn-danger",
            closeOnConfirm: false,
            closeOnCancel: false,
            showLoaderOnConfirm: true
        },
        function(isConfirm) {
            if (isConfirm) {
                setTimeout(function () {
                    $.ajax({
                        type: "GET",
                        url: URL,
                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                        success: function(){
                            swal("Deleted!", "Your imaginary file has been deleted.", "success");
                            window.location.href = redirectURL;
                        }
                    })
                }, 1000);

            } else {
                swal("Cancelled", "Action Cancelled :)", "error");
            }
        });
}

/***********************
 VALIDATION SCRIPT HERE
 **********************/
$(document).ready(function () {
    $('#dataForm').validate({
        errorPlacement: function () {
            return false;
        }
    });
});

/**************************
 DYNAMIC MODAL SCRIPT HERE
 **************************/
$(document.body).on('click','.AppModal',function(e){
    e.preventDefault();
    $('#ModalContent').html('<div style="text-align:center;"><h3 class="text-primary">Loading Form...</h3></div>');
    $('#ModalContent').load(
        $(this).attr('href'),
        function (response, status, xhr) {
            if (status === 'error') {
                alert('error');
                $('#ModalContent').html('<p>Sorry, but there was an error:' + xhr.status + ' ' + xhr.statusText + '</p>');
            }
            return this;
        }
    );
});

/**************************
 PHOTO PREVIEW SCRIPT HERE
 **************************/
function changePhoto(input) {
    if (input.files && input.files[0]) {
        $("#photo_err").html('');
        let mime_type = input.files[0].type;
        if (!(mime_type == 'image/jpeg' || mime_type == 'image/jpg' || mime_type == 'image/png')) {
            $("#photo_err").html("Image format is not valid. Only PNG or JPEG or JPG type images are allowed.");
            return false;
        }
        let reader = new FileReader();
        reader.onload = function (e) {
            $('#photoViewer').attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
    }
}

/**************************
 PHOTO PREVIEW SCRIPT HERE
 **************************/
function companyWiseSupplier(e,route){
    $('.loading_data').hide();
    $(e).after('<span class="loading_data">Loading...</span>');
    let self = $(e);
    let parentHtml = $(e).parent().parent().parent();
    let companyId = $(e).val();
    $.ajax({
        type: "GET",
        url: route,
        data: {
            company_id: companyId
        },
        success: function (response) {
            let option = '<option value="">Select supplier</option>';
            if (response.responseCode == 1){
                $.each(response.data, function (id, value) {
                    option += '<option value="' + id + '">' + value + '</option>';
                });
            }
            $(parentHtml).find('.supplier').html(option);
            $(self).next().hide();
        }
    });
}


