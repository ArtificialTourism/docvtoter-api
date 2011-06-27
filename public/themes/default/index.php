<?php
$renderer = $app->response()->renderer();
$sysevents = $app->session()->getSysevents();
?>

<a name="up" id="up"></a>

<h1 id="sitename">
    <a href="index.php">My Site</a>
</h1>

<div style="clear:both;"></div>

<!-- Content -->
<div id="wrapper_outer">

<div id="topmenu">
<?php echo $renderer->renderPartial("menu")."\n"; ?>
</div>

<div id="wrapper">
    <?php echo $renderer->renderPartial("sysevents", array("events"=>$sysevents))."\n"; ?>
    <div id="content">
    <?php echo $this->body(); ?>
    </div><!-- close #main_col_inner -->
</div><!-- close #wrapper -->

</div><!-- close #wrapper_outer -->
<!-- End Content -->

<div id="footer">
Powered by <?php echo nl2br(PHPFrame::version())."\n"; ?>
</div>
