// Javascript API
function uploadFile(file, title) {
    var formData = new FormData();
    formData.append('file', file);
    formData.append('title', title);

    fetch('http://social.faysi.de/upload.php', { 
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        // Verarbeite die Antwort
        console.log(data.message);
    })
    .catch(error => console.error('Error:', error));
}

// Event-Listener für das Dateiupload-Formular
document.getElementById('uploadForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Formular wird nicht standardmäßig gesendet

    var fileInput = document.getElementById('fileInput');
    var titleInput = document.getElementById('titleInput');

    if (fileInput.files.length > 0) {
        var file = fileInput.files[0];
        var title = titleInput.value;
        uploadFile(file, title);
    } else {
        console.error('No file selected.');
    }
});
