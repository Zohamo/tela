<script type="text/javascript">
    const appUrl = "<?= url() ?>";
    <?php foreach ($json as $name => $value) : ?>
        const <?= $name ?> = JSON.parse('<?= json_encode($value) ?>');
    <?php endforeach ?>
</script>