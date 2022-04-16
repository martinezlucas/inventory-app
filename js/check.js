const loadButton = document.getElementById('csv_file');
const info = document.getElementById('info');
const submit = document.getElementById('upload-file');

submit.disabled = true;

loadButton.onchange = function() {
    info.innerText = "Archivo seleccionado: " + loadButton.files[0].name;
    submit.disabled = false;
}