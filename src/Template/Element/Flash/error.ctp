<?php
if (!isset($params['escape']) || $params['escape'] !== false) {
    $message = h($message);
}
?>
<div class="c-flashMsg-cont is-error" onclick="this.classList.add('is-hidden')">
  <p class="c-flashMsg-txt"><?= $message ?></p>
</div>
