<?php
$attachment = exhibit_builder_page_attachment(1);
?>
<section
    data-color-palette="<?php echo $colorpalette; ?>"
    data-color-section="<?php echo $exhibitSection->backgroundcolor; ?>"
    class="section section-text-media section-text-media-right <?php echo $colors[$exhibitSection->backgroundcolor]['type']; ?>"
    id="se<?php echo $sectionCounter; ?>">
    <div class="section-container container-fluid">
        <div class="row">
            <div class="col-md-6 col-media order-md-last">
                <div class="container-media">
                    <div class="content-controls order-md-last">
                        <div class="control-info control-icon-right">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48" class="icon-info">
                                <g class="icon-info-frame" transform="translate(-98 -31)">
                                    <g transform="translate(57)">
                                        <g>
                                            <line x2="10" transform="translate(52.5 32.5) rotate(180)" stroke-linecap="square" />
                                            <line x2="10" transform="translate(52.5 77.5) rotate(180)" stroke-linecap="square" />
                                            <line y1="45" transform="translate(42.5 77.5) rotate(180)" stroke-linecap="square" />
                                        </g>
                                        <g transform="translate(130 110) rotate(180)">
                                            <line x2="10" transform="translate(52.5 32.5) rotate(180)" stroke-linecap="square" />
                                            <line x2="10" transform="translate(52.5 77.5) rotate(180)" stroke-linecap="square" />
                                            <line y1="45" transform="translate(42.5 77.5) rotate(180)" stroke-linecap="square" />
                                        </g>
                                    </g>
                                    <g transform="translate(-66.5 -365.5)" class="icon-info-i">
                                        <line y2="18" transform="translate(188.5 415.5)" />
                                        <line y2="4" transform="translate(188.5 407.5)" />
                                    </g>
                                </g>
                                <g transform="translate(0 0)" class="icon-info-x">
                                    <line class="icon-info-x-line" x2="18" y2="18" transform="translate(15 15)" />
                                    <line class="icon-info-x-line" x2="18" y2="18" transform="translate(33 15) rotate(90)" />
                                </g>
                            </svg>
                        </div>
                        <div class="control-zoom control-icon-right">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48" class="icon-zoom">
                                <g transform="translate(-21 -84)">
                                    <g transform="translate(-216.5 -429.5)">
                                        <line class="icon-zoom-1" y2="10" transform="translate(259.5 529.5)" />
                                        <line class="icon-zoom-1" x1="10" transform="translate(254.5 534.5)" />
                                    </g>
                                    <path class="icon-zoom-1" d="M2,2,12,12.286" transform="translate(48 110)" />
                                    <g transform="translate(-20 53)">
                                        <line class="icon-zoom-2" x2="10" transform="translate(52.5 32.5) rotate(180)" />
                                        <line class="icon-zoom-2" x2="10" transform="translate(52.5 77.5) rotate(180)" />
                                        <line class="icon-zoom-2" y1="45" transform="translate(42.5 77.5) rotate(180)" />
                                    </g>
                                    <g transform="translate(110 163) rotate(180)">
                                        <line class="icon-zoom-2" x2="10" transform="translate(52.5 32.5) rotate(180)" />
                                        <line class="icon-zoom-2" x2="10" transform="translate(52.5 77.5) rotate(180)" />
                                        <line class="icon-zoom-2" y1="45" transform="translate(42.5 77.5) rotate(180)" />
                                    </g>
                                    <g class="icon-zoom-1" transform="translate(31 93)">
                                        <circle class="icon-zoom-3" cx="12" cy="12" r="12" />
                                        <circle class="icon-zoom-4" cx="12" cy="12" r="10.5" />
                                    </g>
                                </g>
                            </svg>
                        </div>
                    </div>
                    <div class="content-media order-md-first">
                        <div class="media-meta d-none order-md-last">
                            <div class="media-meta-scroll">
                                <div class="media-meta-scroll-content">
                                    <h3>Unbekannter Herr im roten Rock</h3>
                                    <h4>Max Mustermann, Gemälde, 1740-1750. Musterstadt</h4>
                                    <p>
                                        Aus der Sammlung von<br>
                                        <a href="#">Gleimhaus - Museum der deutschen Aufklärung</a>
                                    </p>
                                    <p>
                                        Wie darf ich das Objekt nutzen?
                                    </p>
                                    <p>
                                        Quelle <br>
                                        © GLEIMHAUS Museum der deutschen Aufklärung
                                    </p>
                                    <p>
                                        <a href="#">Zum Objekt >></a>
                                    </p>
                                    <p>
                                        Kurzbeschreibung<br>
                                        Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula
                                        eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient
                                        montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque
                                        eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo,
                                        fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut,
                                        imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis
                                        pretium.
                                    </p>
                                    <p>
                                        Esse hic, libero harum ex corporis amet voluptatibus sapiente molestiae velit
                                        voluptatem ullam deleniti excepturi fugit minima cum quo, et architecto
                                        voluptate.
                                        Officia accusantium exercitationem iure voluptas nesciunt excepturi vel
                                        repudiandae sint qui? Perferendis accusantium incidunt quod, cum eligendi
                                        magnam nihil excepturi vero in nesciunt. Fugit cum optio harum? Quasi, cumque
                                        voluptatum.
                                        Nam dolore obcaecati eos, facere recusandae doloribus nobis incidunt libero
                                        veritatis non optio eaque tenetur maxime repellat mollitia reiciendis vel
                                        ratione sunt odio harum vitae magnam earum. Ducimus, dolorum molestiae?
                                        Tempore repellendus minus qui itaque voluptatibus. Itaque, nobis reprehenderit
                                        quis veniam perspiciatis laudantium recusandae cupiditate quas! Voluptas
                                        similique molestiae, aliquid velit in ab qui officiis dolores accusamus esse
                                        dicta voluptatem!
                                        Quidem esse quisquam, ducimus vero, quos, eius soluta voluptates unde illo
                                        ullam provident sapiente eos laudantium. In minus eligendi earum alias est
                                        distinctio error, officia rerum, quia, tempore animi quos?
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="media-item-container order-md-first">
                            <?php
                            echo ExhibitDdbHelper::getAttachmentMarkup(
                                $attachment,
                                array('class' => 'media-item'),
                                true
                            );
                            ?>
                            <div class="media-item-caption media-item-caption-right">
                                <?php echo ExhibitDdbHelper::getItemDescription($attachment, null); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-text order-md-first">
                <div class="scroll-container">
                    <div class="scroll-controls scroll-controls-left">
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
                    <div class="text-content">
                        <div class="fader"></div>
                        <div class="scroll-frame">
                            <div class="scroll-element">
                                <h1><?php echo htmlspecialchars(strip_tags($exhibitSection->title), ENT_QUOTES | ENT_HTML5); ?></h1>
                                <?php echo exhibit_builder_page_text(1); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>