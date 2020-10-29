{extends file="../common/layout.tpl"}
{block name=title}Update  user{/block}

{block name=content }
    <div class="row add-user">
        <div class="pull-left">
            <form action="#" method="post" enctype="multipart/form-data" >
                <div class="row">
                    {if $message }
                        <div class="alert alert-info" role="alert">
                            {$message}
                        </div>
                    {/if}
                </div>
                <div class="form-group row required">
                    <label for="staticEmail" class="col-sm-3 col-form-label">Email</label>
                    <div class="col-sm-9">
                        <input type="email" class="form-control" id="staticEmail" placeholder="Email" name="email"
                               value="{if isset($email ) }{$email} {/if}">
                    </div>
                </div>
                <div class="form-group row required">
                    <label for="input_username" class="col-sm-3 col-form-label">Username</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="input_username" placeholder="Username" name="username"
                               value="{if isset($username ) }{$username} {/if}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputPassword" class="col-sm-3 col-form-label">Avatar</label>
                    <div class="col-sm-9">
                        <input type="file" id="avatar" name="avatar" class=" form-control">
                    </div>
                </div>
                <div class="text-center">
                    <button type="submit" name="btn_submit" class="btn btn-primary">Submit</button>
                </div>

            </form>
        </div>
        <div class="pull-right">
            <img src="/seatmap/{$avatar}"
                 class="rounded-circle" alt="Cinque Terre" width="304" height="236">
        </div>

    </div>
{/block}