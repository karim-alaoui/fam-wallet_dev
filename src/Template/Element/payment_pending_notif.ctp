<?php if (isset($appAffiliaterVar)): ?>
    <?php if(count($appAffiliaterVar)>0): ?>
        <p style="  font-weight: 800;
                    font-size: 12px;
                    line-height: 15px;
                    text-align: center;
                    line-height: 50px;
                    background-color: #E1A444;
                    color: #FFFFFF;
">＜＜ご確認依頼＞＞未支払いの支払申請が <?= count($appAffiliaterVar) ?> 件残っています。</p>
    <?php endif; ?>
<?php endif; ?>