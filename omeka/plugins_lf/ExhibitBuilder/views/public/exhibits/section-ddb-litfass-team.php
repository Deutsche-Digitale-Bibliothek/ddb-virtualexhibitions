<?php // $attachment = exhibit_builder_page_attachment(1); ?>
<section
    data-color-palette="base"
    data-color-section="white"
    class="section tile"
    id="se<?php echo $sectionCounter; ?>">
    <div class="section-container container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="scroll-container">
                    <div class="text-content">
                        <div class="fader"></div>
                        <div class="scroll-frame">
                            <div class="scroll-element">
                            <?php if ($exhibitType === 'litfass_ddb'): ?>
                                <div class="text-center">
                                    <img src="<?php echo img('ddb-studio-logo-large.png') ?>" alt="DDB studio">
                                    <h1><?php echo __('Eine virtuelle Ausstellung der Deutschen Digitalen Bibliothek'); ?></h1>
                                </div>
                                <h3><?php echo __('in Zusammenarbeit mit'); ?></h3>
                            <?php else: ?>
                                <h1><?php echo __('Eine virtuelle Ausstellung von'); ?></h1>
                            <?php endif; ?>
                                <div class="row align-items-stretch justify-content-start mb-4">
                                <?php
                                    $institutionCount = 0;
                                    foreach ($institutions as $institution):
                                    echo ($institutionCount > 0 && ($institutionCount % 3) == 0)? '<div class="w-100 my-2"></div>' : '';
                                ?>
                                    <div class="col-4">
                                        <div class="card" style="height:100%;">
                                            <div class="card-body text-center" style="justify-content: center;align-items: center;display: flex;flex-direction: column;">
                                                <?php if (isset($institution['logo']) && !empty($institution['logo'])): ?>
                                                <img src="<?php echo WEB_FILES . '/layout/institutionlogo/' . $institution['logo']; ?>"
                                                    alt="<?php echo $institution['name'] ?>"
                                                    class="img-fluid">
                                                <?php endif; ?>
                                                <div class="card-text mt-4">
                                                    <small class="text-muted">
                                                        <a href="<?php echo $institution['url'] ?>" target="_blank" rel="noopener">
                                                            <?php echo $institution['name'] ?>
                                                        </a>
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                    $institutionCount++;
                                    endforeach;
                                ?>
                                </div>
                                <?php $team = unserialize($exhibit->team); ?>
                                <?php
                                if (isset($team['description'])):
                                    echo $team['description'];
                                endif;
                                ?>
                                <?php if (isset($team['team_list'])): ?>
                                <h3>Team</h3>
                                <?php echo $team['team_list']; ?>
                                <?php endif; ?>
                                <div class="created-width mb-5">
                                    <strong>
                                    <?php
                                    switch ($exhibitType) {
                                        case 'litfass_featured':
                                            echo __('Unterstützt von');
                                            break;

                                        default:
                                            echo __('Erstellt mit');
                                            break;
                                    }
                                    ?>
                                    :</strong><br>
                                    <img src="<?php echo img('ddb-studio-logo-small.png') ?>" alt="DDB Studio">
                                </div>
                                <p><small>Diese Ausstellung wurde am <?php echo $publishDate; ?> veröffentlicht.</small></p>
                            </div>
                        </div>
                    </div>
                    <div class="scroll-controls">
                        <svg version="1.1" class="scroll-arrow-up" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                            x="0px" y="0px" width="22px" height="13px" viewBox="0 0 22 13" enable-background="new 0 0 22 13"
                            xml:space="preserve">
                            <path d="M20.61,12.04l-9.65-9.91l-9.91,9.91" />
                        </svg>
                        <br>
                        <svg version="1.1" class="scroll-mouse" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                            x="0px" y="0px" width="23px" height="34px" viewBox="0 0 23 34" enable-background="new 0 0 23 34"
                            xml:space="preserve">
                            <path d="M1.52,11.17c0,0-1.55-9.71,9.93-9.71
                            c11.48,0,9.93,9.71,9.93,9.71v11.58c0,0-0.64,9.71-9.93,9.71s-9.93-9.71-9.93-9.71V11.17z" />
                            <line stroke-linecap="round" x1="11.51" y1="10.28"
                                x2="11.51" y2="15.67" />
                        </svg>
                        <br>
                        <svg version="1.1" class="scroll-arrow-down" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                            x="0px" y="0px" width="22px" height="13px" viewBox="-0 1 22 13" enable-background="new 0 1 22 13"
                            xml:space="preserve">
                            <path d="M1.05,2.13l9.65,9.91l9.91-9.91" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>