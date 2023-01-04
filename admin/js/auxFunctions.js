/*DnD functions w/ jQuery*/
function goBack() {
    window.history.back();
}
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
function dropAvailability(ev, id) {
    ev.preventDefault();
    var data = ev.dataTransfer.getData("text");

    var nodeCopy = document.getElementById(data).cloneNode(true);
    var flag = getAvFlag(id, ev.dataTransfer.getData("text"));
    flag = flag.replace(/\s/g, '');
    console.log("flag:" + flag);
    nodeCopy.id = id + "," + ev.dataTransfer.getData("text") + "," + flag;
    /* We cannot use the same ID */
    /*ev.dataTransfer.getData("text")*/
    nodeCopy.draggable = false;
    nodeCopy.setAttribute("ondblclick", "destroyElement(this.id);");
    ev.target.appendChild(nodeCopy);

    /*ev.target.appendChild(document.getElementById(data));*/
    removeEventualDuplicities();
}
function getAvFlag(cupid, userid) {
    var _returnedFlag = null;
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            _returnedFlag = this.responseText;
            console.log(_returnedFlag);
            //callback(_returnedFlag);
            return _returnedFlag;
            //return _returnedFlag;
            //var clanek = JSON.parse(returnedGetJSON);
            //ConstructNextPost(clanek.id, clanek.title, clanek.content);
            //ret flag
        }
    }
    xmlhttp.open("GET", "XMLHttpRequest/call_get_availability_flag.php?cupid=" + cupid + "&userid=" + userid, false);
    xmlhttp.send();
    return _returnedFlag;
}
function destroyElement(obj) {
    var el = document.getElementById(obj);
    el.remove();
}
function availChangeHandler(obj, delay) {
    setTimeout(function () { availChange(obj); }, delay);
}
function availChange(obj) {
    //console.log(obj);
    //console.log(obj.id);
    var split_id = obj.id.split(",");
    //console.log(split_id[2]);
    if (split_id[2] == 1) {
        console.log(split_id[2]);
        console.log("je 1");
        obj.id = split_id[0] + "," + split_id[1] + "," + "0";
        obj.className = "clovekNG";
    } else {
        console.log(split_id[2]);
        console.log("je 0");
        obj.id = split_id[0] + "," + split_id[1] + "," + "1";
        obj.className = "clovek";
    }
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

//Text input handling 
function validateForm() {
    var title = document.getElementById("title").value;
    //var mytextarea = document.getElementById("mytextarea").value;
    var mytextarea = tinyMCE.get('mytextarea').getContent();
    console.log(title);
    console.log(mytextarea);
    if (title == "") {
        alert("Titulek missing");
        return false;
    }
    if (mytextarea == "") {
        alert("Content missing");
        return false;
    }
}

//CMS stuff
function UpdatePost() {
    var id = document.getElementById("postID").value;
    var title = document.getElementById("title").value;
    var article = tinyMCE.get('mytextarea').getContent();
    var result;
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            location.reload();
        }
    }
    xmlhttp.open("GET", "XMLHttpRequest/call_update_article.php?id=" + encodeURIComponent(id) + "&title=" + encodeURIComponent(title) + "&article=" + encodeURIComponent(article), true);
    xmlhttp.send();
}
function ApproveUser(i) {
    //window.alert(i);
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            //change fajfku&swap onclick
            location.reload();
        }
    }
    xmlhttp.open("GET", "XMLHttpRequest/call_approve_user.php?id=" + encodeURIComponent(i), true);
    xmlhttp.send();
}
function ParseSerializePairingDOM() {
    //var list = document.getElementById("pairing").getElementsByClassName("clovek");
    var list = document.getElementById("pairing").querySelectorAll('[class^="clovek"]');
    var str = "[";
    //var node = document.createElement("span");
    for (i = 0; i < list.length; i++) {
        var result;
        result = list[i].id.split(",");
        str += '{"idpoz":"' + result[0] + '","iduser":"' + result[1] + '"}';
        if (i != list.length - 1) {
            str += ',';
        }
    }
    str += ']';
    //var textnode = document.createTextNode(str);
    //node.appendChild(textnode);
    //console.log(str);
    return (str);
}
function ParseSerializeStatsDOM() {
    var list = document.getElementById("pairing").getElementsByClassName("pozice");
    var str = "[";
    for (i = 0; i < list.length; i++) {
        var result;
        result = list[i].id.split(",");
        str += '{"idpoz":"' + result[1] + '"}';
        if (i != list.length - 1) {
            str += ',';
        }
    }
    str += ']';
    console.log("ParseSerializeStatsDOM" + str);
    return str;
}
function UpdatePairing(JSON) {
    var zavodID = encodeURIComponent(document.getElementById("zavodID").innerHTML);
    var json = encodeURIComponent(JSON);
    var currPairingLoadHash = encodeURIComponent(document.getElementById("currPairingLoadHash").innerHTML);
    var encoded = 'id=' + zavodID + '&json=' + json + '&hash=' + currPairingLoadHash;
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            location.reload();
        }
    }
    xmlhttp.open("POST", "XMLHttpRequest/call_update_pairing.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send(encoded);
    //console.log(JSON);
}
function UpdatePreferedStats(JSON) {
    var json = encodeURIComponent(JSON);
    var encoded = 'json=' + json;
    console.log(JSON);
    console.log(encoded);
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            location.reload();
        }
    }
    xmlhttp.open("POST", "XMLHttpRequest/call_update_stats_positions_config.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send(encoded);
}

//Availability
//create JSON for availability
function AvailableToJSON() {
    //var list = document.getElementById("pairing").getElementsByClassName("clovek");
    var list = document.getElementById("pairing").querySelectorAll('[class^="clovek"]');
    var str = "[";
    for (i = 0; i < list.length; i++) {
        var result;
        result = list[i].id.split(",");
        str += '{"idcup":"' + result[0] + '","iduser":"' + result[1] + '","coming":"' + result[2] + '"}';
        if (i != list.length - 1) {
            str += ',';
        }
    }
    str += ']';
    return (str);
}
function UpdateAvailability(JSON) {
    var zavodID = encodeURIComponent(document.getElementById("zavodID").innerHTML);
    var json = encodeURIComponent(JSON);
    var encoded = 'id=' + zavodID + '&json=' + json;
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            location.reload();
        }
    }
    xmlhttp.open("POST", "XMLHttpRequest/call_update_availability.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send(encoded);
}
function registerMeForTheCup(cupId, userId) {
    var encoded = 'cupid=' + cupId + '&userid=' + userId;
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            location.reload();
        }
    }
    xmlhttp.open("POST", "XMLHttpRequest/call_im_available.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send(encoded);
}
function makeMeGoing(cupId, userId) {
    var encoded = 'cupid=' + cupId + '&userid=' + userId;
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            location.reload();
        }
    }
    xmlhttp.open("POST", "XMLHttpRequest/call_i_can_go.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send(encoded);
}
function makeMeNotGoing(cupId, userId) {
    var encoded = 'cupid=' + cupId + '&userid=' + userId;
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            location.reload();
        }
    }
    xmlhttp.open("POST", "XMLHttpRequest/call_i_cant_go.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send(encoded);
}
function DummyPasswd(iters) {
    var heslo = "heslo";
    var i;
    for (i = 0; i < iters; i++) {
        var num = Math.floor(Math.random() * 10);
        heslo += num;
    }
    console.log(heslo);
    return heslo;
}
function FillDummyPasswdIN(passElemName, passwd) {
    var field = document.getElementById(passElemName).value = passwd;
}