<div class="p-affiliaterAccount" id="payment">

    <section class="p-affiliaterAccount__title__wrap">
        <h1 class="p-affiliaterAccount__title">支払方法</h1>
    </section>
    <div class="p-affiliaterAccount__contents">
        <div id="card_errors" class="p-paymentEdit__alert">
        </div>
<!--        <div id="card_errors" class="c-flashMsg-txt"></div>-->

        <div class="p-affiliaterAccount__item__wrap">

            <?= $this->Form->create(null, ['id' => 'form',
                'url' => array('controller' => 'Myusers', 'action' => 'paymentEdit'),
                ]); ?>
                <div>
                    <label for="card_number" class="p-affiliaterAccount__item__label">カード番号</label>
                    <div id="card_number" class="p-affiliaterAccount__form__input c-input__form__input jsc-input-text"></div>
                </div>

                <div>
                    <label for="card_expiry" class="p-affiliaterAccount__item__label">有効期限</label>
                    <div id="card_expiry" class="p-affiliaterAccount__form__input c-input__form__input jsc-input-text"></div>
                </div>

                <div style="width: 30%">
                    <label for="card_security" class="p-affiliaterAccount__item__label">セキュリティコード</label>
                    <div id="card_security" class="p-affiliaterAccount__form__input c-input__form__input jsc-input-text"></div>
                </div>

                <div>
                    <label for="card_name" class="p-affiliaterAccount__item__label">カード名義</label>
                    <input required="false" type="text" id="card_name" name="card_name" class="p-affiliaterAccount__form__input c-input__form__input jsc-input-text" />
                </div>

                <input type="hidden" id="card_id" name="card_id"/>
                <input type="hidden" id="card_brand" name="card_brand"/>
                <input type="hidden" id="card_last4" name="card_last4"/>
                <input type="hidden" id="card_exp_month" name="card_exp_month"/>
                <input type="hidden" id="card_exp_year" name="card_exp_year"/>

                <button class="p-stampEdit__form__btn c-btn--orange" type="submit">確認</button>
                <div class="p-stampComfirm__form__btn c-btn--white"><?= $this->Html->link('戻る',['action' => 'edit', 'id' => $User['id']]) ?></div>

            <?= $this->Form->end() ?>

        </div>

    </div>
</div>

<script src="https://js.stripe.com/v3/"></script>

<script>
    let stripe = Stripe(<?php echo json_encode($api_key) ?>);
    let elements = stripe.elements();
    let cardNumber = elements.create('cardNumber');
    let cardExpiry = elements.create('cardExpiry');
    let cardCvc = elements.create('cardCvc');

    cardNumber.mount('#card_number');

    cardNumber.addEventListener('change', function(event) {
        validate(event)
    });

    cardExpiry.mount('#card_expiry');

    cardExpiry.addEventListener('change', function(event) {
        validate(event)
    });

    cardCvc.mount('#card_security');

    cardCvc.addEventListener('change', function(event) {
        validate(event)
    });

    let form = document.getElementById('form');
    form.addEventListener('submit', function(event) {
        event.preventDefault();
        validate(event)

        stripe.createToken(cardNumber, {name: document.getElementById("card_name").value}).then(function(result) {
            if (result.error) {
                let errorElement = document.getElementById('card_errors');
                errorElement.innerHTML = '<p class="c-flashMsg-cont is-error">' + result.error.message + '</p>';
            } else {
                console.log(result.token)
                stripeSubmit(result.token);
            }
        });
    });

    function validate(event) {
        let displayError = document.getElementById('card_errors');
        if (event.error) {
            displayError.innerHTML = '<p class="c-flashMsg-cont is-error">' + event.error.message + '</p>';
        } else {
            displayError.innerHTML = '';
        }
    }

    function stripeSubmit(token) {
        let form = document.getElementById('form');
        let hiddenInput = document.createElement('input');
        hiddenInput.setAttribute('type', 'hidden');
        hiddenInput.setAttribute('name', 'stripeToken');
        hiddenInput.setAttribute('value', token.id);

        document.getElementById("card_id").setAttribute('value', token.card.id);
        document.getElementById("card_brand").setAttribute('value', token.card.brand);
        document.getElementById("card_last4").setAttribute('value', token.card.last4);
        document.getElementById("card_exp_month").setAttribute('value', token.card.exp_month);
        document.getElementById("card_exp_year").setAttribute('value', token.card.exp_year);

        form.appendChild(hiddenInput);
        form.submit();
    }
</script>

<?php
echo $this->element('footer_login');


