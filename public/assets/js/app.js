function previewFile() {
    const file = document.querySelector('input[type=file]').files[0];
    const reader = new FileReader();

    reader.addEventListener("load", function () {
        document.getElementsByName("image")[0].setAttribute("value", reader.result);
    }, false);

    if (file) {
        reader.readAsDataURL(file);
    }
}
