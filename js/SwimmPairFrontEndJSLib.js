//article json stuff
function PushLastID() {
    var lastIDval = document.querySelector("#posts .post:last-child").id;
    //var nextIDval = lastIDval - 1;
    return lastIDval;
}
function GetPostAppendPost(id) {
    var returnedGetJSON = null;
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    }
    else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            returnedGetJSON = this.responseText;
            var post = JSON.parse(returnedGetJSON);
            console.log(returnedGetJSON);
            //if failed {"XHRCallResult":"null"}
            if (post.XHRCallResult != "null") {
                ConstructNextPost(post.id, post.timestamp, post.title, post.content, post.author_user_id, post.signature_flag);
            }
            else {
                document.getElementById("err").innerHTML = "Další aktualita nenalezena 😠!";
                document.getElementById("btn").remove();
                return;
            }
        }
    }
    xmlhttp.open("GET", "XMLHttpRequest/following_post.php?id=" + id, true);
    xmlhttp.send();
}
//displayed, author, signed
function ConstructNextPost(id, timestamp, title, content, author, signed) {
    var thisPostId = id;
    var thisTimestamp = timestamp;
    var thisPostTitle = title;
    var thisPostContent = content;
    var thisAuthor = author;
    var thisSigned = signed;

    //New Post HTML-ARTICLE created
    var constructingPost = document.createElement("ARTICLE");
    constructingPost.setAttribute("class", "post");
    constructingPost.setAttribute("id", thisPostId);

    document.getElementById("posts").appendChild(constructingPost);

    var h1 = document.createElement("H1");
    var span_in_h1_element = document.createElement("SPAN");
    var span_in_h1_content = document.createTextNode(thisPostTitle); //title
    span_in_h1_element.appendChild(span_in_h1_content); //pripojim h1, jdu dal na podepsani
    h1.appendChild(span_in_h1_element);

    console.log(thisSigned);
    //If signed=0, article without signiture element
    if (thisSigned == "0") {
        console.log("not signed");
    }
    else {
        //Else signed=1 attaching signature this post

        //Signature frame
        var spanSIGN = document.createElement("SPAN");
        spanSIGN.setAttribute("class", "author");

        //If author=null, author not present, it means PSA
        if (thisAuthor == "null") {
            //Fill w/ info icon and text
            console.log("PSA");
            var txtPSA = document.createTextNode(" OZNÁMENÍ");
            //Info icon here
            var info_img = new Image();
            info_img.src = "img/info_24x24.png";
            info_img.alt = "info";
            info_img.height = 12;
            info_img.width = 12;
            spanSIGN.appendChild(info_img);
            spanSIGN.appendChild(txtPSA);
        }
        else {
            //Else name present

            //Now we're gonna this fill w/ 3 spans of author related stuff
            console.log("signed as " + thisAuthor);

            //Signature frame filling 1/3 - author snippet
            var spanAUTH = document.createElement("SPAN");
            spanAUTH.setAttribute("class", "frame");
            var txtAUTH = document.createTextNode("aut. ");
            spanAUTH.appendChild(txtAUTH);
            spanSIGN.appendChild(spanAUTH);

            //Signature frame filling 2/3 - author name
            var spanNAME = document.createElement("SPAN");
            var txtNAME = document.createTextNode(thisAuthor + ", ");
            spanNAME.appendChild(txtNAME);
            spanSIGN.appendChild(spanNAME);

            //Signature frame filling 3/3 - written date
            var spanDATE = document.createElement("SPAN");
            spanDATE.setAttribute("class", "frame");
            var txtDATE = document.createTextNode("naps. " + thisTimestamp);
            spanDATE.appendChild(txtDATE);
            spanSIGN.appendChild(spanDATE);
        }
        //Append signature to header
        h1.appendChild(spanSIGN);
    }
    //Append HEADER to article
    document.getElementById(thisPostId).appendChild(h1);

    //Content of the article
    var paragraph = document.createElement("P");
    var paragraph_content = document.createTextNode(thisPostContent);
    paragraph.appendChild(paragraph_content);
    //Append CONTENT paragraph to article
    document.getElementById(thisPostId).appendChild(paragraph);
}
function ProcessPersonForTheSeason(userID, callerYearElement) {
    var year = callerYearElement.innerHTML;
    //console.log(callerYearElement);
    //var year = callerYearElement;
    var list = document.getElementById("roky").getElementsByClassName("season-button");

    for (var i = 0; i < list.length; i++) {
        var rok = list[i];
        rok.className = "season-button";
    }

    callerYearElement.className = "season-button selected";
    document.getElementById("curr-rok").innerText = year;
    document.getElementById("rok-ucasti").innerText = year;
    console.log(year + " " + userID);
    //XHRCall
    var stats = CommunicateUserStatsXHRAndUpdateTable(userID, year);
}
function CommunicateUserStatsXHRAndUpdateTable(userID, callerYear) {
    var returnedGetJSON = null;
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            returnedGetJSON = this.responseText;
            var separate = returnedGetJSON.split(';');
            console.log(separate[0]);
            var _arr = JSON.parse(separate[1]);
            console.log(_arr);
            UpdateUserStatsTable(separate[0], separate[1]);
            //return _arr;
        }
    }
    xmlhttp.open("GET", "XMLHttpRequest/seasonal_stats_user.php?id=" + userID + "&year=" + callerYear, true);
    xmlhttp.send();
}
function UpdateUserStatsTable(cnt, arr_str) {
    console.log("calling append f");
    document.getElementById("pocet-ucasti").innerText = String(cnt);
    console.log(cnt);
    console.log(arr_str);
    //console.log(cnt);
    //console.log(arr);
    //console.log(typeof(arr));
    var arr = JSON.parse(arr_str);

    for (var p in arr) {
        console.log(arr[p].position_id + "->" + arr[p].cnt);
        //console.log(arr[p].cnt);

        //pokud pozice s timto id existuje
        if (document.getElementById(arr[p].position_id) !== null) {
            //console.log(arr[p].idpoz);
            document.getElementById(arr[p].position_id).innerHTML = arr[p].cnt;
            arr[p].cnt = 0;
        }
        else {

        }
    }

    var counter = Number(0);
    for (var p in arr) {
        counter += Number(arr[p].cnt);
    }
    console.log("zbyva " + counter + " " + typeof (counter));
    document.getElementById("zbyvajici").innerText = String(counter);
    //loop secti zbtek

    //do the job
}
function ProcessClubForTheSeason(clubID, callerYearElement) {
    var year = callerYearElement.innerHTML;
    var list = document.getElementById("roky").getElementsByClassName("season-button");
    console.log(list);
    console.log(callerYearElement);
    for (var i = 0; i < list.length; i++) {
        var rok = list[i];
        rok.className = "season-button";
    }

    callerYearElement.className = "season-button selected";
    //document.getElementById("curr-rok").innerText = year;
    //console.log(year+" "+clubId);
    document.getElementById("rok-ucasti").innerText = year;
    console.log("tento rok je " + year);
    //XHRCall
    var stats = CommunicateClubStatsXHRAndUpdateTable(clubID, year);
    //Delete rows from table
    //Populate table
}
function PopulateClubStatsGivenYear(clubID, year) {
    var stats = CommunicateClubStatsXHRAndUpdateTable(clubID, year);
}
function CommunicateClubStatsXHRAndUpdateTable(clubId, callerYear) {
    var returnedGetJSON = null;
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            returnedGetJSON = this.responseText;
            //var separate = returnedGetJSON.split(';');
            //console.log(separate[0]);
            //var _arr = JSON.parse(separate[1]);
            //console.log(_arr);
            //UpdateUserStatsTable(separate[0], separate[1]);
            //return _arr;
            //var _arr = JSON.parse(returnedGetJSON);
            UpdateClubStatsTable(returnedGetJSON)
        }
    }
    xmlhttp.open("GET", "XMLHttpRequest/seasonal_stats_club.php?id=" + clubId + "&year=" + callerYear, true);
    xmlhttp.send();
}
function UpdateClubStatsTable(arr_str) {
    console.log("calling refresh f");
    var arr = JSON.parse(arr_str);
    console.log(arr);

    for (var p in arr) {
        console.log(arr[p].user_id + "->" + arr[p].cnt);
        document.getElementById(arr[p].user_id).innerHTML = arr[p].cnt;
        //update table here
    }
    //blabla foreach arr_str as pair element.p.id->fill w/ p.value
}
//deprecate
function refreshPPL() {
    var query = document.getElementById("inputText").value;
    var list = document.getElementById("lide").getElementsByClassName("rozhodci");

    for (var i = 0; i < list.length; i++) {
        var articlePerson = list[i];

        var name = articlePerson.childNodes[1].textContent + " " + articlePerson.childNodes[2].textContent;
        var swapped_name = articlePerson.childNodes[2].textContent + " " + articlePerson.childNodes[1].textContent;

        if ((name.toUpperCase()).includes(query.toUpperCase()) || (swapped_name.toUpperCase()).includes(query.toUpperCase())) {
            articlePerson.setAttribute("style", "");
        }
        else {
            articlePerson.setAttribute("style", "display:none;");
        }
    }
}
//kraj picked trigger
function RegionPickerChanged(callerElementKraj) {
    /*console.log(callerElementKraj);
    var _seznamRef = callerElementKraj.parentElement;
    console.log(_seznamRef);
    var _seznamChildrenRef = _seznamRef.childNodes;
    console.log(_seznamChildrenRef);
    var _allRef = _seznamChildrenRef.item(1);
    console.log(_allRef);*/

    //console.log("_ _ _");

    var list = document.getElementById("kraje").getElementsByClassName("lide-filter-button");
    console.log(list);
    /*for(var i = 0; i<list.length; i++)
    {
        var rok = list[i];
        console.log(rok);
        //console.log(rok.innerHTML);
        //rok.className = "lide-filtered-button";
    }*/

    if (callerElementKraj.innerHTML == "VŠE") {
        //odznac all a konec
        for (var i = 0; i < list.length; i++) {
            var _krajBtn = list[i];
            _krajBtn.className = "lide-filter-button";
        }
        list[0].className = "lide-filter-button selected";
        //return;
    }
    else {
        //odoznacit prvni ALL if still selected
        if (list[0].className == "lide-filter-button selected") {
            list[0].className = "lide-filter-button";
        }
        //zvolit/odzvolit pri kliknuti
        if (callerElementKraj.className == "lide-filter-button selected") {
            callerElementKraj.className = "lide-filter-button";
        }
        else {
            callerElementKraj.className = "lide-filter-button selected";
        }
    }

    //var kraje = queriedKraje();
    //var tridy = ObtainPickerSubquery("tridy");
    FilterQueriedReferees("kraje", "tridy", "inputText", "nopplfound");
}
//trida rozhodcich picked trigger
function RefereeRankPickerChanged(callerElementTrida) {
    var list = document.getElementById("tridy").getElementsByClassName("lide-filter-button");
    console.log(list);

    if (callerElementTrida.innerHTML == "VŠE") {
        //odznac all a konec
        for (var i = 0; i < list.length; i++) {
            var _tridaBtn = list[i];
            _tridaBtn.className = "lide-filter-button";
        }
        list[0].className = "lide-filter-button selected";
        //return;
    }
    else {
        //odoznacit prvni ALL if still selected
        if (list[0].className == "lide-filter-button selected") {
            list[0].className = "lide-filter-button";
        }
        //zvolit/odzvolit pri kliknuti
        if (callerElementTrida.className == "lide-filter-button selected") {
            callerElementTrida.className = "lide-filter-button";
        }
        else {
            callerElementTrida.className = "lide-filter-button selected";
        }
    }

    //var tridy = ObtainPickerSubquery("tridy");
    FilterQueriedReferees("kraje", "tridy", "inputText", "nopplfound");
}
//search performed trigger
function SearchBarChanged() {
    //no GUI modifications
    FilterQueriedReferees("kraje", "tridy", "inputText", "nopplfound");
}
//get list of available kraj[]
//deprecate
function queriedKraje() {
    //list of relevant ids
    var _ids = [];
    var list = document.getElementById("kraje").getElementsByClassName("lide-filter-button");
    console.log("queryKraje() running");
    //console.log(list);

    //if list[0]selected, ret vsechny, jsou acceptable
    if (list[0].classList.contains("selected")) {
        //napushuj vsechny ids 1->length
        for (var i = 1; i < list.length; i++) {
            //raid-1 to _splitted={raid, 1}
            var id = list[i].id;

            var _splited = id.split("-");
            _ids.push(_splited[1]);
        }
    }
    else {
        //else 1->length ocekuj aktivni a podle toho pushuj
        for (var i = 1; i < list.length; i++) {
            //console.log(list[i]);
            if (list[i].classList.contains("selected")) {
                //console.log("this one");
                var id = list[i].id;

                var _splited = id.split("-");
                _ids.push(_splited[1]);
            }
            //contains
        }
    }
    //console.log("endloop");
    console.log(_ids);
    return _ids;
}
//get list of available picker, trida[] or kraj[]
function ObtainPickerSubquery(pickerID) {
    console.log(pickerID);
    var _ids = [];
    var list = document.getElementById(pickerID).getElementsByClassName("lide-filter-button");
    console.log("ObtainPickerSubquery()" + pickerID + " running");

    if (list[0].classList.contains("selected")) {
        for (var i = 1; i < list.length; i++) {
            //r(r|a)id-1 to _splitted={r(r|a)id, 1}
            var id = list[i].id;

            var _splited = id.split("-");
            _ids.push(_splited[1]);
        }
    }
    else {
        for (var i = 1; i < list.length; i++) {
            if (list[i].classList.contains("selected")) {
                var id = list[i].id;

                var _splited = id.split("-");
                _ids.push(_splited[1]);
            }
        }
    }
    console.log("konec");
    console.log(_ids);
    return _ids;
}
//get searchbar text
function ObtainSearchBarSubquery(barID) {
    return document.getElementById(barID).value;
}
//is one of the options from list permissible? ret T/F
function IsOptionPermissible(_queriedID, _listOfPermissibleIDs) {
    for (var i = 0; i < _listOfPermissibleIDs.length; i++) {
        console.log("srovnavam:" + _listOfPermissibleIDs[i] + "s" + _queriedID);
        if (_listOfPermissibleIDs[i] == _queriedID) {
            console.log("ret true");
            return true;
        }
    }
    console.log("ret false");
    return false;
}
//is this name permissible? ret T/F
function IsNamePermissible(_queried, _first_name, _last_name) {
    var name = _first_name + " " + _last_name;
    var swapped_name = _last_name + " " + _first_name;
    if ((name.toUpperCase()).includes(_queried.toUpperCase()) || (swapped_name.toUpperCase()).includes(_queried.toUpperCase())) {
        return true;
    }
    else {
        return false;
    }
}
//rearrange after a change was triggered
function FilterQueriedReferees(_krajID, _tridyID, _strID, _npfID) {
    //id[] of above kraj
    var krajeIDs = ObtainPickerSubquery(_krajID);
    //id[] of trida rozhodcich
    var tridyIDs = ObtainPickerSubquery(_tridyID);
    //jmeno from searchbar
    var jmeno = ObtainSearchBarSubquery(_strID);

    //console.log(jmeno);

    //loop ppl
    var list = document.getElementById("lide").getElementsByClassName("rozhodci");
    //check if everything is empty
    var empty = true;
    for (var i = 0; i < list.length; i++) {
        var articlePerson = list[i];
        console.log(articlePerson);

        var first_name = "";
        var last_name = "";
        var rrid;
        var raid;
        //extract children
        for (var j = 0; j < articlePerson.children.length; j++) {
            var _iteratedNode = articlePerson.children[j];
            console.log(_iteratedNode);

            if (_iteratedNode.className == "first_name") {
                first_name = _iteratedNode.textContent;
            }
            if (_iteratedNode.className == "last_name") {
                last_name = _iteratedNode.textContent;
            }
            if (_iteratedNode.className == "rrid") {
                rrid = _iteratedNode.textContent;
            }
            if (_iteratedNode.className == "raid") {
                raid = _iteratedNode.textContent;
            }
        }

        //Querying
        if (IsOptionPermissible(raid, krajeIDs)) {
            //console.log("IsOptionPermissible"+rrid+"of"+tridyIDs);
            if (IsOptionPermissible(rrid, tridyIDs)) {
                //console.log("IsNamePermissible"+jmeno+"w/"+first_name+"&"+last_name);
                if (IsNamePermissible(jmeno, first_name, last_name)) {
                    //PROJDE VSE, muzu zobrazit
                    //console.log("projde vse");
                    articlePerson.setAttribute("style", "");
                    //empty query? FALSE! THIS ONE FOUND!
                    empty = false;
                    continue;
                }
            }
        }
        //failne nektera podminka
        articlePerson.setAttribute("style", "display:none;");
    }
    //console.log("EMPTY RESULT VAR");
    //console.log(empty);
    if (empty == true) {
        console.log("NO PEOPLE FOUND");
        document.getElementById(_npfID).setAttribute("style", "");
    }
    else {
        console.log("PEOPLE DISPLAYED");
        document.getElementById(_npfID).setAttribute("style", "display:none;");
    }
    //kdyz vsichni lide neviditelni
    return null;
}