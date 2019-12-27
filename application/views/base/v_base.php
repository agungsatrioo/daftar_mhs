<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        
        <title><?= @$title ?></title>
        <?= link_tag(base_url('media/sbadmin2/css/sbadmin.css')) ?>
        <?= link_tag(base_url('media/vendor/fontawesome-free/css/all.min.css')) ?>
        <?= link_tag(base_url('media/custom.css')) ?>

        <?= @$additional_css ?>
    </head>
    <?= @$contents ?>
</html>
