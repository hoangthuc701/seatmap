{extends file="../common/layout.tpl"}
{block name=title}User management{/block}
{block name=content }
    <div class="">
        <div class="row">
            <a   type="button" class="btn btn-success m-3" href="/seatmap/user/addUser">Add new user</a>
        </div>
        <div class="row" id="message">

        </div>
        <div class="row">
            {if $message}
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    {$message}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            {/if}
        </div>
        <div class="row">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Username</th>
                    <th scope="col">Email</th>
                    <th scope="col" class="text-center">Edit</th>
                    <th scope="col" class="text-center">Delete</th>
                </tr>
                </thead>
                <tbody id="table_user">

                </tbody>
            </table>
        </div>
        <div class="row justify-content-center">
            <nav aria-label="..." id="pagination">

            </nav>
        </div>
    </div>
{/block}


{block name=modal}
    <!-- Modal delete -->
    <div class="modal fade" id="deleteConfirm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Confirm</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Do you want to delete this user?
                </div>
                <div class="modal-footer">
                    <button   type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button id="deleteBtn" type="button" class="btn btn-danger">Delete</button>
                </div>
            </div>
        </div>
    </div>
{/block}
{block name=custom_script}
<script src="/seatmap/public/js/user.js">
</script>
{/block}