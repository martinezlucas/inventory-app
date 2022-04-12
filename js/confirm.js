function deleteCount(id, code, page) {

    if(confirm('Eliminar este conteo es irreversible, ¿desea continuar?')) {
        window.location.href = "../server/delete_count.php?id_count=" + id + "&code=" + code + "&page=" + page;
    }
}

function deleteAdd(id, page) {

    if(confirm('Eliminar este conteo es irreversible, ¿desea continuar?')) {
        window.location.href = "../server/delete_add.php?id_add=" + id + "&page=" + page;
    }
}