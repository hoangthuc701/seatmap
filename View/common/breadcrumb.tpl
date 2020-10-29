<nav aria-label="breadcrumb">
    <ol class="breadcrumb">

        {$count =1}
        {$num_item = count($breadcrumbs)}
        {foreach $breadcrumbs as $title => $link}
            {if $count == $num_item}
                <li class="breadcrumb-item active"><a href="{$link}">{$title}</a></li>
            {else}
                <li class="breadcrumb-item"><a href="{$link}">{$title}</a></li>
            {/if}
            {$count = $count +1}
        {/foreach}
    </ol>
</nav>