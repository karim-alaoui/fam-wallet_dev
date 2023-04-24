<?php
$class = 'message';
if (!empty($params['class'])) {
    $class .= ' ' . $params['class'];
}
if (!isset($params['escape']) || $params['escape'] !== false) {
    $message = h($message);
}
?>
<div class="<?= h($class) ?> c-flashMsg-cont" onclick="this.classList.add('is-hidden')">
  <p class="c-flashMsg-txt"><?= $message ?></p>
</div>
