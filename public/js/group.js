function renderTableGroup(groups) {
    const html = groups.map(group => {
        return (`<tr>
                    <th scope="row">${group.id}</th>
                    <td>${group.name}</td>
                    <td>${group.color}</td>
                    <td class="text-center"><a href="/seatmap/group/update&id=${group.id}"  type="button" class="btn btn-primary">Edit</a> </td>
                    <td class="text-center"><button  type="button" class="btn btn-danger delete_group" data-toggle="modal" data-id="${group.id}" data-target="#deleteConfirm">Delete</button></td>          
                </tr>`);
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
            $('#table_group').html(html);
            $('.delete_group').on('click', function () {
                let id = $(this).data('id');
                $('#deleteBtn').data('id_d', id);
            })
        }
    }
    xhr.send();
}

function deleteGroup(id) {
    let url = '/seatmap/group/delete';
    let successMessageHtml = `
        <div class="alert alert-success alert-dismissible fade show" role="alert"> Group has been deleted.
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
              </button>
        </div>`;
    let failureMessageHtml = `
        <div class="alert alert-danger alert-dismissible fade show" role="alert"> Group hasn't been deleted.
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
              </button>
        </div>`;
    let params = `id=${id}`;
    var xhr = new XMLHttpRequest();
    xhr.open('POST', url, true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        const response = JSON.parse(this.response);
        if (response.message) {
            $('#message').html(successMessageHtml);
            $('#deleteConfirm').modal('hide');
            fetchGroupData();
            return;
        }
        $('#message').html(failureMessageHtml);
        $('#deleteConfirm').modal('hide');
        fetchGroupData();
    };
    xhr.send(params);
}

$(document).ready(function () {
    fetchGroupData();
    $('#deleteBtn').on('click', function () {
        let seat_id = $(this).data('id_d');
        deleteGroup(seat_id);
    });
});