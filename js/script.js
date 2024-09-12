// js/script.js
$('#uploadForm').on('submit', function (e) {
    e.preventDefault(); // Prevent the form from submitting the default way
    var formData = new FormData(this); // Create a FormData object with the form

    $.ajax({
        type: 'POST',
        url: 'compress.php', // The PHP script to handle compression
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {
            var data = JSON.parse(response);
            $('#result').html(
                '<p>Original Size: ' + (data.originalSize / 1024).toFixed(2) + ' KB</p>' +
                '<p>Compressed Size: ' + (data.compressedSize / 1024).toFixed(2) + ' KB</p>' +
                '<a href="' + data.filePath + '" download>Download Compressed Image</a>'
            );
        }
    });
});
