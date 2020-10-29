{extends file="../common/layout.tpl"}
{block name=title}Update seatmap{/block}

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
                               value="{if isset($name ) }{$name}{/if}">
                    </div>
                </div>
                <div class="form-group row required">
                    <label for="description" class="col-sm-3 col-form-label">Description</label>
                    <div class="col-sm-9">
                        <textarea type="text" class="form-control" id="description" placeholder="Description" name="description"  rows="4"> {if isset($description ) }{$description} {/if} </textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputPassword" class="col-sm-3 col-form-label">Image</label>
                    <div class="col-sm-9">
                        <input type="file" id="file" name="file" class=" form-control">
                    </div>
                </div>
                <div class="text-center">
                    <button type="submit" name="btn_submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
            <div class="pull-right mr-5">
                <img src="/seatmap/{$file}"
                     class="" alt="Cinque Terre" width="304" height="236">
            </div>
        </form>
    </div>
{/block}

