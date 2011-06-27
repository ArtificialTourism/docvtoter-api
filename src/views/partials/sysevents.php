<?php if (count($events) > 0) : ?>
<div id="sysevents">
<ul>
<?php foreach ($events as $event) : ?>
<li class="sysevents_<?php echo $event[1]; ?>">
    <?php echo $event[0]."\n"; ?>
</li>
<?php endforeach; ?>
</ul>
</div>
<?php endif; ?>

<?php $events->clear(); ?>
