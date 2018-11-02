<div style="box-sizing: border-box; display: flex; flex-direction: column; justify-content: center; align-items: center; position: fixed; top: 0; left: 0; right: 0; bottom: 0; z-index:1000; width: 100%; height: 100%; background-color:rgba(237,235,222,0.8); overflow:hidden; padding: 32px 0;">
    <div style="max-width:640px; overflow-y:auto; padding: 8px; background-color:#fff;">
        <?php echo get_view()->form('confirm_use_form', array('method' => 'post')); ?>
            <h1>Nutzungsbedingungen und Datenschutzhinweise</h1>
            <p>Sie müssen sich mit den Nutzungsbedingungen und Datenschutzhinweisen einverstanden erklären.</p>
            <div>
                <h2>Nutzungsbedingungen</h2>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Molestiae est quibusdam earum adipisci. Odit recusandae minima totam, accusantium ea, autem voluptates neque possimus minus eveniet in? Quidem maiores labore animi?</p>
                <p>Natus odit magni quisquam, iusto cupiditate suscipit ex similique consequuntur fugiat voluptates! Amet architecto laborum atque labore. Excepturi alias quos officiis, optio porro in cum, iure maiores ipsa, necessitatibus aliquam!</p>
                <p>Provident distinctio explicabo, impedit corrupti assumenda, odit nostrum iure repellendus facere minus similique! Aliquam, adipisci ea doloribus qui blanditiis dolorem repellendus omnis consequuntur ullam impedit voluptates quasi consequatur illo quidem?</p>
                <p>Maxime quos laborum modi, laboriosam mollitia maiores, nesciunt veniam esse at hic nihil beatae cum, quis reprehenderit ducimus eligendi porro! Aliquam minus fugit ipsam ut vitae necessitatibus laborum quo porro!</p>
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
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Molestiae est quibusdam earum adipisci. Odit recusandae minima totam, accusantium ea, autem voluptates neque possimus minus eveniet in? Quidem maiores labore animi?</p>
                <p>Natus odit magni quisquam, iusto cupiditate suscipit ex similique consequuntur fugiat voluptates! Amet architecto laborum atque labore. Excepturi alias quos officiis, optio porro in cum, iure maiores ipsa, necessitatibus aliquam!</p>
                <p>Provident distinctio explicabo, impedit corrupti assumenda, odit nostrum iure repellendus facere minus similique! Aliquam, adipisci ea doloribus qui blanditiis dolorem repellendus omnis consequuntur ullam impedit voluptates quasi consequatur illo quidem?</p>
                <p>Maxime quos laborum modi, laboriosam mollitia maiores, nesciunt veniam esse at hic nihil beatae cum, quis reprehenderit ducimus eligendi porro! Aliquam minus fugit ipsam ut vitae necessitatibus laborum quo porro!</p>
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
