{extends file="../common/layout.tpl"}
{block name=title}Arrange seatmap{/block}

{block name=content }

    <div class="row">
        <div class="md-4 mr-5">
            <div class="text-center">
                <input type="text" class="form-control" id="username" aria-describedby="emailHelp" placeholder="">
                <button id="search_btn" type="button" class="btn btn-primary m-3">Search</button>
                <ul id="users">
                </ul>
            </div>
        </div>
        <div class="md-8" >
            <div id="seatmap" style="border: 1px solid black; position: relative; top: 0px; left: 0px; z-index: 50;  background-color: white;">
            </div>
            <div id="menu">
                <div>
                    <li id="pulse-button">
                        <a>Change group</a>
                        <ul class="third-level-menu" id="groups">

                        </ul>

                    </li>
                    <li id="delete-button">Delete</li>
                </div>
            </div>
        </div>
    </div>
{/block}
{block name=custom_script}
    <script src="/seatmap/public/js/seatmap.js">
    </script>
{/block}
