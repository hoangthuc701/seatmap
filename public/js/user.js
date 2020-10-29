function renderTableUser(users) {
    const html = users.map(user => {
        return (`<tr>
                    <th scope="row">${user.id}</th>
                    <td>${user.username}</td>
                    <td>${user.email}</td>
                    <td class="text-center"><a href="/seatmap/User/updateUser&id=${user.id}"  type="button" class="btn btn-primary">Edit</a> </td>
                    <td class="text-center"><button  type="button" class="btn btn-danger delete_user" data-toggle="modal" data-id="${user.id}" data-target="#deleteConfirm">Delete</button></td>
                </tr>`);
    });
    return html;
}

function renderPagination(currentPage, totalPage) {
    let selectOptionsHtml = '';
    for (let page = 1; page <= totalPage; page += 1) {
        if (page == currentPage) {
            selectOptionsHtml += `<option selected>${page}</option>`
        } else {
            selectOptionsHtml += `<option>${page}</option>`
        }
    }
    return `
        <ul class="pagination justify-content-center">
                    <li class="page-item ${currentPage == 1 ? 'disabled' : ''}">
                        <a class="page-link" id="prev_btn">Previous</a>
                    </li>
                    <div class="form-group">
                        <select class="form-control" id="paginationItem">
                           ${selectOptionsHtml}
                        </select>
                    </div>
                    <li class="page-item ${currentPage == totalPage ? 'disabled' : ''}">
                        <a class="page-link" id="next_btn" >Next</a>
                    </li>
                </ul>
    `
}

function fetchUserData(page) {
    let xhr = new XMLHttpRequest;
    xhr.open('GET', `/seatmap/User/getUsers&p=${page}`, true)
    xhr.onload = function () {
        if (this.status === 200) {
            let json_data = JSON.parse(this.response);
            let {users, totalPage} = json_data;
            const html = renderTableUser(users);
            $('#table_user').html(html);
            $('#pagination').html(renderPagination(page, totalPage));
            $('#paginationItem').on('change', function (e) {
                let page = e.target.value;
                fetchUserData(page);
            })
            $('.delete_user').on('click', function () {
                let id = $(this).data('id');
                $('#deleteBtn').data('id_d', id);
            })
            $('#next_btn').on('click', function (e) {

                let page = parseInt($('#paginationItem').val()) + 1;
                fetchUserData(page);
            })
            $('#prev_btn').on('click', function (e) {

                let page = parseInt($('#paginationItem').val()) - 1;
                fetchUserData(page);
            })
        }
    }
    xhr.send();
}

function deleteUser(user_id) {
    let url = '/seatmap/User/deleteUser';
    let successMessageHtml = `
        <div class="alert alert-success alert-dismissible fade show" role="alert"> User has been deleted.
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
              </button>
        </div>`;
    let failureMessageHtml = `
        <div class="alert alert-danger alert-dismissible fade show" role="alert"> User hasn't been deleted.
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
              </button>
        </div>`;
    let params = `id=${user_id}`;
    var xhr = new XMLHttpRequest();
    xhr.open('POST', url, true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        const response = JSON.parse(this.response);
        if (response.message) {
            let current_page = $('#paginationItem').val();
            fetchUserData(current_page);
            $('#deleteConfirm').modal('hide');
            $('#message').html(successMessageHtml);
            return;
        }
        $('#message').html(failureMessageHtml);
        $('#deleteConfirm').modal('hide');
    };
    xhr.send(params);
}

$(document).ready(function () {
    fetchUserData(1);
    $('#deleteBtn').on('click', function () {
        let user_id = $(this).data('id_d');
        deleteUser(user_id);
    });
});