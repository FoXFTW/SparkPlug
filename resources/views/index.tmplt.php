@use('app')
@set('title', 'Start')
@set('content')
<div>
    <?php
    if (login_check()) {
        ?>
        <h1>Welcome, <?php echo app()->make(\App\SparkPlug\Auth\Auth::class)->getUser()->username; ?>!</h1>
    <?php } else { ?>
        <h1>You are not logged in</h1>
        <a href="@route('login_view')">Login</a>
    <?php } ?>
    <p>The Variable <code>$varFromView</code> is set via PageController. Its content is: <code><?php echo $varFromView;?></code></p>
</div>
@endset