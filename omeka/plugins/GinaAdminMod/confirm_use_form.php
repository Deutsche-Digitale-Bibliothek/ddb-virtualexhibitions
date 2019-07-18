<?php
// $masterDoc = file_get_contents(realpath($_SERVER['DOCUMENT_ROOT'] . '/../data') . '/consent_termsofuse.html');
?>
<div style="box-sizing: border-box; display: flex; flex-direction: column; justify-content: center; align-items: center; position: fixed; top: 0; left: 0; right: 0; bottom: 0; z-index:1000; width: 100%; height: 100%; background-color:rgba(237,235,222,0.8); overflow:hidden; padding: 32px 0;">
    <div style="max-width:640px; overflow-y:auto; padding: 8px; background-color:#fff;">
        <?php echo get_view()->form('confirm_use_form', array('method' => 'post')); ?>
            <h1>Nutzungsbedingungen und Datenschutzhinweise</h1>
            <p>Sie müssen sich mit den Nutzungsbedingungen und Datenschutzhinweisen einverstanden erklären.</p>
            <div>
                <h2>Nutzungsbedingungen</h2>
                <?php echo file_get_contents(realpath($_SERVER['DOCUMENT_ROOT'] . '/../data') . '/consent_termsofuse.html'); ?>
                <div class="explanation">
                    <?php
                    echo get_view()->formCheckbox(
                        'confirm_use_Nutzungsbedingungen',
                        null,
                        array(
                            // 'checked' => true,
                            'id' => 'confirm_use_Nutzungsbedingungen'
                        )
                    );
                    ?> Ich habe die Nutzungsbedingungen gelesen und bin mit ihnen einverstanden.
                </div>
            </div>
            <div>
                <h2>Datenschutzhinweise</h2>
                <?php echo file_get_contents(realpath($_SERVER['DOCUMENT_ROOT'] . '/../data') . '/consent_privacypolicy.html'); ?>
                <div class="explanation">
                    <?php
                    echo get_view()->formCheckbox(
                        'confirm_use_Datenschutzhinweise',
                        null,
                        array(
                            // 'checked' => true,
                            'id' => 'confirm_use_Datenschutzhinweise'
                        )
                    );
                    ?> Ich habe die Datenschutzhinweise gelesen und bin mit ihnen einverstanden.
                </div>
            </div>
            <div style="text-align:right; margin-top:32px;">
                <?php
                echo get_view()->formSubmit('sumbit_confirm_use', 'Speichern',
                    array('style' => 'width: auto; font-size: inherit; line-height: initial;height: auto;')
                );
                ?>
            </div>
            <div style="text-align:right;">Ich bin <strong>nicht</strong> einverstanden und möchte mich lieber <a href="<?php echo WEB_DIR ?>/users/logout">abmelden!</a></div>
        </form>
    </div>
</div>
