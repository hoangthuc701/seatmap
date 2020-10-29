{extends file="../common/layout.tpl"}
{block name=title}Add new group{/block}

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
                    <label for="input_name" class="col-sm-3 col-form-label">Name</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="input_name" placeholder="Name" name="name"
                               value="{if isset($smarty.post.btn_submit ) }{$smarty.post.name} {/if}">
                    </div>
                </div>
                <div class="form-group row required" >
                    <label for="input_color" class="col-sm-3 col-form-label">Color</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="input_color" placeholder="Color" name="color"
                               value="{if isset($smarty.post.btn_submit ) }{$smarty.post.color} {/if}">
                    </div>
                </div>
                <div class="text-center">
                    <button type="submit" name="btn_submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
{/block}
