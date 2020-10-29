function renderTableSeatmap(seatmaps) {
    const html = seatmaps.map(seatmap => {
        return (`<tr>
                    <th scope="row">${seatmap.id}</th>
                    <td>${seatmap.name}</td>
                    <td>${seatmap.description}</td>
                    <td class="text-center"><a href="/seatmap/seatmap/update&id=${seatmap.id}"  type="button" class="btn btn-primary">Edit</a> </td>
                    <td class="text-center"><button  type="button" class="btn btn-danger delete_user" data-toggle="modal" data-id="${seatmap.id}" data-target="#deleteConfirm">Delete</button></td>
                    <td class="text-center"> <a href="/seatmap/seatmap/arrangeSeatmap&map=${seatmap.id}"  type="button" class="btn btn-primary">Arrange</a>  </td>
                </tr>`);
    });
    return html;
}

function fetchUserData() {
    let xhr = new XMLHttpRequest;
    xhr.open('GET', `/seatmap/seatmap/getSeatmaps`, true)
    xhr.onload = function () {
        if (this.status === 200) {
            let seatmaps = JSON.parse(this.response);
            const html = renderTableSeatmap(seatmaps);
            $('#table_user').html(html);
            $('.delete_user').on('click', function () {
                let id = $(this).data('id');
                $('#deleteBtn').data('id_d', id);
            })
        }
    }
    xhr.send();
}

function deleteSeat(seat_id) {
    let url = '/seatmap/seatmap/deleteSeatmap';
    let successMessageHtml = `
        <div class="alert alert-success alert-dismissible fade show" role="alert"> Seat map has been deleted.
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
              </button>
        </div>`;
    let failureMessageHtml = `
        <div class="alert alert-danger alert-dismissible fade show" role="alert"> Seat map hasn't been deleted.
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
              </button>
        </div>`;
    let params = `id=${seat_id}`;
    var xhr = new XMLHttpRequest();
    xhr.open('POST', url, true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        const response = JSON.parse(this.response);
        if (response.message)
        {
            $('#message').html(successMessageHtml);
            $('#deleteConfirm').modal('hide');
            fetchUserData(1);
            return;
        }
        $('#message').html(failureMessageHtml);
        $('#deleteConfirm').modal('hide');
        fetchUserData(1);
    };
    xhr.send(params);
}

$(document).ready(function () {
    fetchUserData();
    $('#deleteBtn').on('click', function () {
        let seat_id = $(this).data('id_d');
        deleteSeat(seat_id);
    });
});