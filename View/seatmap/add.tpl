{extends file="../common/layout.tpl"}
{block name=title}Add new seatmap{/block}

{block name=content }
    <div class="row add-user">
        <form action="#" method="post" enctype="multipart/form-data" >
            <div class="pull-left">
                <div class="row">
                    {if $message }
                        <div class="alert alert-info" role="alert">
                            {$message}
                        </div>
                    {/if}
                </div>
                <div class="form-group row required">
                    <label for="name" class="col-sm-3 col-form-label">Name</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="name" placeholder="Name" name="name"
                               value="{if isset($smarty.post.btn_submit ) }{$smarty.post.name} {/if}">
                    </div>
                </div>
                <div class="form-group row required">
                    <label for="description" class="col-sm-3 col-form-label">Description</label>
                    <div class="col-sm-9">
                        <textarea type="text" class="form-control" id="description" placeholder="Description" name="description"  rows="4"
                                  >{if isset($smarty.post.btn_submit ) }{$smarty.post.description} {/if}</textarea>
                    </div>
                </div>
                <div class="form-group row required">
                    <label for="inputPassword" class="col-sm-3 col-form-label">Image</label>
                    <div class="col-sm-9">
                        <input type="file" id="file" name="file" class=" form-control">
                    </div>
                </div>
                <div class="text-center">
                    <button type="submit" name="btn_submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </div>
{/block}

