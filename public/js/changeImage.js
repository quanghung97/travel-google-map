var addNewLogo = function (input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            //Hiển thị ảnh vừa mới upload lên
            $('#logo-img').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}