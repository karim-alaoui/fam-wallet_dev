<div class="c-list" id="affiliater_points">
  <section class="c-list__title__wrap">
    <h1 class="c-list__title">換金申請完了</h1>
  </section>
  <div class="m-10 u-fs-12">
    <p>ポイントの換金申請をしました。</p>
    <p>振込完了までお待ちください。</p>

      <?= $this->Html->link('トップに戻る',
          ['controller' => 'Affiliaters', 'action' => 'index', $this->request->session()->read('Auth.User.id')],
          [
          'name' => 'back',
          'class' => 'p-couponEdit__form__btn c-btn--orange flex align-items-center justify-content-center'
      ])?>
  </div>

</div>
