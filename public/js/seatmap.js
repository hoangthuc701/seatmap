var width = 800;
var height = 600;
var block_size = 20;
var seatmap_id = null;

var stage = new Konva.Stage({
    container: 'seatmap',
    width: width,
    height: height,
});

var layer = new Konva.Layer();
stage.add(layer);


var tr = new Konva.Transformer();
layer.add(tr);


var selectionRectangle = new Konva.Rect({
    fill: 'rgba(0,0,255,0.5)',
});
layer.add(selectionRectangle);

var x1, y1, x2, y2;
stage.on('mousedown touchstart', (e) => {
    if (e.target !== stage) {
        return;
    }
    x1 = stage.getPointerPosition().x;
    y1 = stage.getPointerPosition().y;
    x2 = stage.getPointerPosition().x;
    y2 = stage.getPointerPosition().y;

    selectionRectangle.visible(true);
    selectionRectangle.width(0);
    selectionRectangle.height(0);
    layer.draw();
});

stage.on('mousemove touchmove', () => {
    if (!selectionRectangle.visible()) {
        return;
    }
    x2 = stage.getPointerPosition().x;
    y2 = stage.getPointerPosition().y;

    selectionRectangle.setAttrs({
        x: Math.min(x1, x2),
        y: Math.min(y1, y2),
        width: Math.abs(x2 - x1),
        height: Math.abs(y2 - y1),
    });
    layer.batchDraw();
});

stage.on('mouseup touchend', () => {
    if (!selectionRectangle.visible()) {
        return;
    }
    setTimeout(() => {
        selectionRectangle.visible(false);
        layer.batchDraw();
    });

    var shapes = stage.find('.group').toArray();
    var box = selectionRectangle.getClientRect();
    var selected = shapes.filter((shape) =>
        Konva.Util.haveIntersection(box, shape.getClientRect())
    );
    tr.nodes(selected);
    layer.batchDraw();
});

stage.on('click tap', function (e) {
    if (selectionRectangle.visible()) {
        return;
    }

    if (e.target === stage) {
        tr.nodes([]);
        layer.draw();
        return;
    }

    if (!e.target.hasName('rect')) {
        return;
    }

    const metaPressed = e.evt.shiftKey || e.evt.ctrlKey || e.evt.metaKey;
    const isSelected = tr.nodes().indexOf(e.target) >= 0;

    if (!metaPressed && !isSelected) {
        tr.nodes([e.target]);
    } else if (metaPressed && isSelected) {
        const nodes = tr.nodes().slice();
        nodes.splice(nodes.indexOf(e.target), 1);
        tr.nodes(nodes);
    } else if (metaPressed && !isSelected) {
        const nodes = tr.nodes().concat([e.target]);
        tr.nodes(nodes);
    }
    layer.draw();
});


function addNewPeople({x, y, scale_x, scale_y, rotation, people}) {
    var originalAttrs = {
        x,
        y,
        scaleX: scale_x || 1,
        scaleY: scale_y || 1,
        draggable: true,
        rotation: rotation || 0,
        width: block_size * 5,
        height: block_size * 5,
        name:'group',
        dragBoundFunc: function (pos) {
            let new_x = pos.x;
            let new_y = pos.y;
            if (new_x < 0) new_x = 0;
            if (new_x > width - 6 * block_size) new_x = width - 6 * block_size;
            if (new_y < 0) new_y = 0;
            if (new_y > height - 3 * block_size) new_y = height - 3 * block_size;
            return {
                x: new_x,
                y: new_y
            }
        },
    };

    var group = new Konva.Group(originalAttrs);
    group.user_id = people.id;
    layer.add(group);

    var imageObj = new Image();
    imageObj.onload = function () {
        var image = new Konva.Image({
            x: block_size / 2,
            y: block_size / 2,
            image: imageObj,
            width: 40,
            height: 40
        });
        group.add(image);
    };
    imageObj.src = `/seatmap/${people.avatar}`;
    let rect = new Konva.Rect({
        width: block_size * 6,
        height: block_size * 3,
        fill: people.color ||'#fff' ,
        stroke: '#ddd',
        strokeWidth: 1,
        shadowColor: 'black',
        name: 'table'
    });
    group.add(rect);
    var defaultText = people.name
    var text = new Konva.Text({
        text: defaultText,
        x: block_size * 2.5,
        y: block_size / 2,
        width: block_size * 3.5,
        align: 'center',
        verticalAlign: 'bottom',
        fontStyle: 'bold'
    });

    group.on('dragstart', function () {
        this.moveToTop();
        group.draw();
    });
    group.on('dragend', (e) => {
        saveMap(group, e);
    });
    group.on('transformend', function (e) {
        //console.log('transformend',e.target._id);
        saveMap(group, e);
    });
    group.on('transform', function (e) {
        //console.log('transform',e.target._id);
    });
    group.on('transformstart', function (e) {
        //console.log('transform start',e.target._id);
    });
    group.add(text);
    layer.draw();
}

function saveMap(group, e) {
    const user_id = group.user_id;
    const x = Math.round(e.target.attrs.x);
    const y = Math.round(e.target.attrs.y);
    const rotation = Math.round(e.target.attrs.rotation);
    const scale_x = e.target.attrs.scaleX;
    const scale_y = e.target.attrs.scaleY;
    changePosition(user_id, seatmap_id, x, y, scale_x, scale_y, rotation);
}

// setup menu
let currentShape;
var menuNode = document.getElementById('menu');

document.getElementById('delete-button').addEventListener('click', () => {
    deleteSeat(currentShape.user_id);
    currentShape.destroy();
    layer.draw();
});

window.addEventListener('click', () => {
    menuNode.style.display = 'none';
});

stage.on('contextmenu', function (e) {
    e.evt.preventDefault();
    if (e.target === stage) {
        return;
    }
    currentShape = e.target;
    if (currentShape.parent.getClassName() == 'Group') currentShape = currentShape.parent;

    menuNode.style.display = 'initial';
    var containerRect = stage.container().getBoundingClientRect();
    menuNode.style.top =
        containerRect.top + stage.getPointerPosition().y + 4 + 'px';
    menuNode.style.left =
        containerRect.left + stage.getPointerPosition().x + 4 + 'px';
});

var dragSrcEl = null;

function handleDragStart(e) {
    dragSrcEl = this;

    e.dataTransfer.effectAllowed = 'move';
    e.dataTransfer.setData('text/html', this.outerHTML);

    this.classList.add('dragElem');
    dragSrcEl = $(this).data('id');
}

function addDnDHandlers(elem) {
    elem.addEventListener('dragstart', handleDragStart, false);
}

var con = stage.container();
con.addEventListener('dragover', function (e) {
    e.preventDefault();
});

con.addEventListener('drop', async function (e) {
    e.preventDefault();
    e.preventDefault();
    var data = dragSrcEl;
    let user = await getUser(data);
    stage.setPointersPositions(e);
    var position = stage.getPointerPosition();
    addNewPeople({x: position.x, y: position.y, people: {id: user.id, name: user.username, avatar: user.avatar}});
    removeList(data);
    changePosition(user.id, seatmap_id, position.x, position.y, 1, 1, 0);
});

async function getUser(id) {
    const response = await fetch(`/seatmap/user/getUserInfo&id=${id}`);
    const data = await response.json();
    return data;
}

function fetchUserList(keyword) {

    let xhr = new XMLHttpRequest;
    xhr.open('GET', `/seatmap/UserSeatmap/getUserAvailable&keyword=${keyword}`, true)
    xhr.onload = function () {
        if (this.status === 200) {
            let users = JSON.parse(this.response);
            renderList(users);
        }
    }
    xhr.send();
}

function removeList(id) {
    var cols = document.querySelectorAll('#users .column');
    cols.forEach((user => {
        if ($(user).data('id') == id) {
            $(user).remove();
        }
    }))
}

function renderList(users) {
    var html = users.map((user) => (
        `<li data-id="${user.id}" class="column" draggable="true"><header>${user.username} </header></li>`
    ))
    $('#users').html(html);

    var cols = document.querySelectorAll('#users .column');
    [].forEach.call(cols, addDnDHandlers);
}


async function changePosition(user_id, seatmap_id, x, y, scale_x, scale_y, rotation) {
    const data = {
        user_id,
        seatmap_id,
        x,
        y,
        scale_x,
        scale_y,
        rotation,
    }
    let formBody = [];
    for (let property in data) {
        let encodedKey = encodeURIComponent(property);
        let encodedValue = encodeURIComponent(data[property]);
        formBody.push(encodedKey + "=" + encodedValue);
    }
    formBody = formBody.join("&");

    let url = '/seatmap/UserSeatmap/changeUserPosition';
    var xhr = new XMLHttpRequest();
    xhr.open('POST', url, true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.send(formBody);
}

function loadBackground(url) {
    var imageUrl = url;
    $("#seatmap").css("background-image", "url(" + imageUrl + ")");
}

function fetchSeatMapData(id) {
    let xhr = new XMLHttpRequest;
    let url = `/seatmap/UserSeatmap/getSeatmap&seat_id=${id}`
    xhr.open('GET', url, true)
    xhr.onload = function () {
        if (this.status === 200) {
            let seats = JSON.parse(this.response);
            renderSeatmap(seats);
        }
    }
    xhr.send();
}

function renderSeatmap(seats) {
    seats.forEach(async (seat) => {
        const {id, user_id, coordinates_x, coordinates_y, scale_x, scale_y, rotation, color} = seat;
        let user = await getUser(user_id);
        addNewPeople({
            x: coordinates_x,
            y: coordinates_y,
            scale_x,
            scale_y,
            rotation,
            people: {id: user.id, name: user.username, avatar: user.avatar, color: color}
        });
    })
}

function GetURLParameter(sParam) {
    var sPageURL = window.location.href.substring(1);
    var sURLVariables = sPageURL.split('&');
    for (var i = 0; i < sURLVariables.length; i++) {
        var sParameterName = sURLVariables[i].split('=');
        if (sParameterName[0] == sParam) {
            return sParameterName[1];
        }
    }
}

function deleteSeat(user_id) {
    let url = '/seatmap/UserSeatmap/deleteUserPosition';
    let params = `user_id=${user_id}`;
    var xhr = new XMLHttpRequest();
    xhr.open('POST', url, true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        if (this.response.message) {

        }
    };
    xhr.send(params);
}

function fetchMapInfo(id) {
    let xhr = new XMLHttpRequest;
    let url = `/seatmap/Seatmap/getSeatmapInfo&id=${id}`
    xhr.open('GET', url, true)
    xhr.onload = function () {
        if (this.status === 200) {
            let data = JSON.parse(this.response);
            if (data.message == '0') {
                window.location.href = "/seatmap/notfound";
            }
            loadBackground(`/seatmap/${data.file}`);
        }
    }
    xhr.send();
}

$(document).ready(function () {
    seatmap_id = GetURLParameter('map');
    fetchUserList("");
    fetchSeatMapData(seatmap_id);
    fetchMapInfo(seatmap_id);
    fetchGroupData();


    $('#search_btn').on('click', function () {
        let keyword = $('#username').val();
        fetchUserList(keyword);
    });
});

function changeColor(color, group_id) {
    currentShape.findOne('.table').fill(color);
    layer.draw();
    let user_id = currentShape.user_id;
    changeGroup(user_id, group_id);
}

function changeGroup(user_id, group_id){
    let url = '/seatmap/UserSeatmap/changeGroup';
    let formBody = `user_id=${user_id}&group_id=${group_id}`
    var xhr = new XMLHttpRequest();
    xhr.open('POST', url, true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.send(formBody);
}

function renderTableGroup(groups) {
    const html = groups.map(group => {
        return (`<li onclick="changeColor('${group.color}', ${group.id})"> ${group.name}</li>`);
    });
    return html;
}

function fetchGroupData() {
    let xhr = new XMLHttpRequest;
    xhr.open('GET', `/seatmap/group/getGroups`, true)
    xhr.onload = function () {
        if (this.status === 200) {
            let groups = JSON.parse(this.response);
            const html = renderTableGroup(groups);
            $('#groups').html(html);
        }
    }
    xhr.send();
}
