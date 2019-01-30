<?php if (has_alert_success()): ?>
    <div class="alert alert-success">
        <p>
            <strong>Felicitations : </strong>
            <ul>
                <?php foreach ($_SESSION['alert_success'] as $i => $v): ?>
                    <li><?= $v ?></li>
                <?php endforeach ?>
            </ul>
        </p>
    </div>
<?php endif ?>
<?php if (has_alert_error()): ?>
    <div class="alert alert-error">
        <p>
            <strong>Erreur : </strong>
        <ul>
            <?php foreach ($_SESSION['alert_error'] as $i => $v): ?>
                <li><?= $v ?></li>
            <?php endforeach ?>
        </ul>
        </p>
    </div>
<?php endif ?>
<?php
$_SESSION['alert_success'] = [];
$_SESSION['alert_error'] = [];
