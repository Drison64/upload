document.getElementById("dropzone-file").addEventListener("change", function() {
    document.getElementById("upload-form").submit();
});

function copyToClipboard(copyText) {
    console.log(copyText);
    navigator.clipboard.writeText(copyText);
}
