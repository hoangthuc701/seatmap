<!DOCTYPE html>
<html lang="" style="height: auto; min-height: 100%;">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>{block name=title}Default Title{/block}</title>
    <!-- Bootstrap CSS -->
    <link
            rel="stylesheet"
            href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
            integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm"
            crossorigin="anonymous"
    />
    <link
            rel="stylesheet"
            href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"
    />
    <link
            rel="stylesheet"
            type="text/css"
            href="/seatmap/public/css/style.css"
    >
    <script src="https://cdnjs.cloudflare.com/ajax/libs/konva/7.1.3/konva.min.js"
            integrity="sha512-qPGyVELaUu6GrullzsSubRGoDYEHFxzZMfCkvOtWXHNkjavlnu2jIknaYuF9ZWY+28Wn+1kh3TCqUTYr8XiOfQ=="
            crossorigin="anonymous"></script>
</head>
<body style="height: auto; min-height: 100vh">
<div class="wrapper" style="height: auto; min-height: 100vh">
    {include file='./header.tpl'}
    {include file='./sidebar.tpl'}
    <div class="content-wrapper">
        <!-- content header -->
        <section class="content-header">
            <h1> {block name=title}Default Title{/block} </h1>
            {include file='./breadcrumb.tpl'}
        </section>
        <!-- main content !-->
        <section class="content">
            {block name=content} {/block}
        </section>
    </div>
    {include file='./footer.tpl'}
</div>
{block name=modal} {/block}
<script
        src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
        crossorigin="anonymous"
></script>
<script
        src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"
></script>
<script
        src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"
></script>
{block name=custom_script} {/block}
</body>
</html>