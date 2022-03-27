const queryString = window.location.search;
const urlParams = new URLSearchParams(queryString);
const page = urlParams.get('page');

function deleteCount(id, code) {

    if(confirm('Eliminar este conteo es irreversible, ¿desea continuar?')) {
        window.location.href = "../server/delete_count.php?id_count=" + id + "&code=" + code + "&page=" + page;
    }
}