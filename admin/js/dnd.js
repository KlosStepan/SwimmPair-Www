function allowDrop(ev) {
    ev.preventDefault();
}

function drag(ev) {
    ev.dataTransfer.setData("text", ev.target.id);
}

function drop(ev, id) {
    ev.preventDefault();
    var data = ev.dataTransfer.getData("text");

    var nodeCopy = document.getElementById(data).cloneNode(true);
    nodeCopy.id = id + "," + ev.dataTransfer.getData("text");
    /* We cannot use the same ID */
    /*ev.dataTransfer.getData("text")*/
    nodeCopy.draggable = false;
    nodeCopy.setAttribute("ondblclick", "destroyElement(this.id);");
    ev.target.appendChild(nodeCopy);

    /*ev.target.appendChild(document.getElementById(data));*/
    removeEventualDuplicities();
}

function destroyElement(obj) {
    var el = document.getElementById(obj);
    el.remove();
}

function removeEventualDuplicities() {
    var ids = [];
    $('*').each(function () {
        if (this.id && this.id !== '') {
            if (ids[this.id]) {
                $(this).remove();
            } else {
                ids[this.id] = this
            }
        }
    });
}