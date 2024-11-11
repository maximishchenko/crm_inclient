<?php $this->pageTitle = 'Разное | Настройки'; ?>

<? if (Yii::app()->user->getFlash('settings_success') || Yii::app()->user->getFlash('appearance_success')) { ?>
    <script type="module">
        import {NotificationBar} from '/js/notificationBar.js';

        const notificationBar = new NotificationBar({
            type: 'success',
            title: 'Сохранено',
            description: 'Настройки успешно изменены'
        });
        notificationBar.show();
    </script>
<? } ?>

<div class="clients-hat">
    <div class="settings-name">
        <?php echo CHtml::link('Настройки', array('page/settings_additional_field')); ?>
        <img src="/img/right-arrow-button.svg">
        Разное
    </div>
    <div class="goback-link pull-right" style="margin-bottom: 25px;">

    </div>
</div>
<main class="content full2" role="main">
    <div class="box_edituser_left">
        <div class="edit_user_0anketa">
            <div class="content-01">
                <?php $this->renderPartial('settings_main_nav', array('common' => true)); ?>
                <?php
                $form = $this->beginWidget('CActiveForm', array(
                    'enableAjaxValidation' => false,
                    'method' => 'post',
                    'id' => 'edit-common',
                    'htmlOptions' => [
                        'enctype' => 'multipart/form-data', // для загрузки файлов
                        'class' => 'page-form'
                    ]
                ));
                ?>

                <div class="user-table-block_pola fixWidth">
                    <ul id="ul-listTabs">
                        <?php
                        $listTabs = [
                            'appearance' => 'Внешний вид',
                            'filesAndTime' => 'Файлы и время'
                        ];
                        foreach ($listTabs as $key => $tab) {
                            echo
                                '<li id="' . $key . '" class="button-change-table sectorsBlock '
                                . ($typeTab != $key ?: 'active') . '" onclick="changeTabs(' . "'" . $key . "'" . ');">' . $tab .
                                '</li>';
                        }
                        ?>
                    </ul>
                </div>

                <!-- внешний вид-->
                <div id="appearanceBlock" class="hide">
                    <div class="settings-main-block">

                        <div style="margin: 20px 0px;">
                            <? foreach ($appearanceErrors as $key => $errorsParam) {
                                foreach ($errorsParam as $error) {
                                    echo "<div class='form-error'>$error</div>";
                                }
                            } ?>
                        </div>

                        <div class="profile_info_block clear_fix">
                            <div class="profile_info_header_wrap">
                                <span class="profile_info_header">Наименование и ссылки</span>
                            </div>
                        </div>

                        <div class="settings_appearance_block">
                            <div class="settings_appearance_record">
                                <label> Наименование в меню:<span class="star">*</span></label>
                                <?
                                echo $form->textField($appearance, 'menu_name', array('name' => 'menu_name', 'class' => 'form-control'));
                                ?>
                            </div>

                            <div class="settings_appearance_record">
                                <label> Ссылка:<span class="star"></span></label>
                                <?
                                echo $form->textField($appearance, 'menu_link', array('name' => 'menu_link', 'class' => 'form-control'));
                                ?>
                            </div>
                        </div>

                        <div class="settings_appearance_block">
                            <div class="settings_appearance_record">
                                <label> Наименование в футере:<span class="star"></span></label>
                                <?
                                echo $form->textField($appearance, 'footer_name', array('name' => 'footer_name', 'class' => 'form-control'));
                                ?>
                            </div>

                            <div class="settings_appearance_record">
                                <label> Ссылка:<span class="star"></span></label>
                                <?
                                echo $form->textField($appearance, 'footer_link', array('name' => 'footer_link', 'class' => 'form-control'));
                                ?>
                            </div>
                        </div>

                        <div id="appearanceLinksBlock" class="appearance_links_block">
                            <label> Полезные ссылки</label>

                            <? foreach ($appearanceLinks as $value) { ?>
                                <div class="appearance_links_row">
                                    <div class='settings_appearance_record'>
                                        <?
                                        echo CHtml::textField('AppearanceLinks[current][' . $value->id . '][link_name]', $value->link_name, ['name' => 'link_name', 'class' => 'form-control']);
                                        ?>
                                    </div>

                                    <div class='settings_appearance_record'>
                                        <?
                                        echo CHtml::textField('AppearanceLinks[current][' . $value->id . '][link_value]', $value->link_value, ['name' => 'link_value', 'class' => 'form-control']);
                                        ?>
                                    </div>

                                    <div class="row-input align_center">
                                        <img class="delDocument_set" onclick="deleteLink(event)"
                                             src="/img/cancel_newdoc.svg" alt="">
                                    </div>
                                </div>
                            <? } ?>

                            <? foreach ($newLinks as $key => $value) { ?>
                                <div class="appearance_links_row">
                                    <div class='settings_appearance_record'>
                                        <?
                                        echo CHtml::textField('AppearanceLinks[new][' . $key . '][link_name]', $value['link_name'], ['name' => 'link_name', 'class' => 'form-control']);
                                        ?>
                                    </div>

                                    <div class='settings_appearance_record'>
                                        <?
                                        echo CHtml::textField('AppearanceLinks[new][' . $key . '][link_value]', $value['link_value'], ['name' => 'link_value', 'class' => 'form-control']);
                                        ?>
                                    </div>

                                    <div class="row-input align_center">
                                        <img class="delDocument_set" onclick="deleteLink(event)"
                                             src="/img/cancel_newdoc.svg" alt="">
                                    </div>
                                </div>
                            <? } ?>
                        </div>

                        <div class="div_link appearance_add_link" onclick="addLink()"> Добавить ссылку</div>

                        <div class="profile_info_block clear_fix profile_info_block_margin_top" style="margin-top: 20px;">
                            <div class="profile_info_header_wrap">
                                <span class="profile_info_header">Фон на входе</span>
                            </div>
                        </div>

                        <div class="appearance_background_image_block">
                            <div class="appearance_label">
                                <label> Изображение:</label>
                            </div>

                            <div class="settings_appearance_record flex_center">
                                <? echo CHtml::radioButton('backgroundImageType[rotate]', $backgroundImageType['rotate'],
                                    [
                                        'id' => 'rotate_radio',
                                        'name' => 'rotate',
                                        'class' => 'appearance_background_image',
                                        'checked' => $backgroundImageType['rotate'],
                                        'onClick' => 'changeRadioActive("rotate_radio")'
                                    ]);
                                ?>
                                <label class="pointer" for="rotate_radio">Ротатор</label>
                            </div>

                            <div class="settings_appearance_record flex_center">
                                <? echo CHtml::radioButton('backgroundImageType[link]', $backgroundImageType['link'],
                                    [
                                        'id' => 'link_radio',
                                        'name' => 'link',
                                        'class' => 'appearance_background_image',
                                        'checked' => $backgroundImageType['link'],
                                        'onClick' => 'changeRadioActive("link_radio")'
                                    ]);
                                ?>
                                <label class="pointer" for="link_radio">Ссылка</label>
                            </div>

                            <?
                            echo CHtml::textField('backgroundImageTypeValues[link]', $backgroundImageType['link'] == 1 ? $appearance->background_image_type_value : '',
                                [
                                    'class' => 'form-control',
                                    'style' => 'margin-bottom: 10px; ' . ($backgroundImageType['link'] == 1 ? 'display: block;' : 'display: none'),
                                    'id' => 'backgroundTypeLink',
                                ]
                            );
                            ?>

                            <div class="settings_appearance_record flex_center">
                                <? echo CHtml::radioButton('backgroundImageType[image]', $backgroundImageType['image'],
                                    [
                                        'id' => 'image_radio',
                                        'name' => 'image',
                                        'class' => 'appearance_background_image',
                                        'checked' => $backgroundImageType['image'],
                                        'onClick' => 'changeRadioActive("image_radio")'
                                    ]);
                                ?>
                                <label class="pointer" for="image_radio">Своё изображение</label>
                            </div>

                            <div id="backgroundTypeImage" class="appearance_logo_block"
                                 style="<? echo $backgroundImageType['image'] == 1 ? 'display: flex' : 'display: none' ?>">
                                <div>
                                    <div id="customBlockHeader" class="logo_block_header">
                                        <div id="downloadCustomImage" class="upload_button_2"> Зарузить изображение</div>
                                        <div id="customImageDeleteId" class="fakeButtonAvatarDel <?echo $backgroundImageType['image'] != 1 || $appearance->background_image_type_value == $backgroundImagePathDefault ? 'hide' : '';?>" onclick="customImageDelete()"> Удалить</div>
                                        <?
                                        echo CHtml::activeFileField($appearance, 'customImage', ['id' => 'customImage', 'style' => 'display:none']);
                                        echo $form->hiddenField($appearance, 'customImageValue', ['value' => '']);
                                        ?>
                                    </div>

                                    <div class="logo_block_content">
                                        <label>Разрешение: 1920*1200px</label>
                                        <label>Формат: png, jpg, jpeg, svg</label>
                                    </div>
                                </div>

                                <div class="custom-image">
                                    <img id="customImageVisual"
                                         src="<? echo $backgroundImageType['image'] == 1 ? $appearance->background_image_type_value : $backgroundImagePathDefault ?>"/>
                                </div>
                            </div>

                            <div class="appearance_label">
                                <label> Градиент:</label>
                            </div>

                            <div class="settings_appearance_record flex-flow">
                                <?
                                foreach ($gradients as $key => $value) {
                                    echo "<div class='gradient'>";
                                        echo CHtml::radioButton('backgroundImageType['. $key . ']', $backgroundImageType[$key],
                                            [
                                                'id' => $key,
                                                'class' => 'appearance_background_image',
                                                'checked' => $backgroundImageType[$key],
                                                'onClick' => 'changeRadioActive("' . $key . '")'
                                            ]);
                                        echo "<div id='" . $key . "_block' class='gradient_block' style='background: $value' onClick=\"changeRadioActive('$key')\"></div>";
                                    echo "</div>";

                                    $gradientColor = '';
                                    if ($backgroundImageType[$key]) {
                                        $gradientColor = $value;
                                    }
                                }

                                // отвечает за цвет
                                echo CHtml::textField('gradientColor',  $gradientColor, ['id' => 'gradientColorId', 'style' => 'display:none']);

                                ?>
                            </div>
                        </div>

                        <div class="profile_info_block clear_fix profile_info_block_margin_top">
                            <div class="profile_info_header_wrap">
                                <span class="profile_info_header">Логотип</span>
                            </div>
                        </div>

                        <div class="appearance_logo_block">
                            <div>
                                <div class="appearance_label">
                                    <label> Логотип на входе:</label>
                                </div>

                                <div class="logo_block_header">
                                    <div id="fakeButton" class="upload_button_2"> Зарузить логотип</div>
                                    <div id="logoDeleteId" class="fakeButtonAvatarDel <?echo $appearance->logo == $logoPathDefault ? 'hide' : '';?>" onclick="logoDelete()"
                                         style="<? isset($appearance->logo) ?: 'display:none' ?>"> Удалить
                                    </div>
                                    <?
                                    echo CHtml::activeFileField($appearance, 'image', ['id' => 'loadImage', 'style' => 'display:none']);
                                    echo $form->hiddenField($appearance, 'logo', ['value' => '']);
                                    ?>
                                </div>

                                <div class="logo_block_content">
                                    <label>Разрешение: 260*100px</label>
                                    <label>Формат: png, jpg, jpeg, svg</label>
                                </div>
                            </div>

                            <div class="logo_image">
                                <img id="logoVisual" src="<? echo $appearance->logo ?>"/>
                            </div>
                        </div>

                        <div class="operation-button" style="margin-bottom: 10px">
                            <?php echo CHtml::submitButton('Сохранить', array('class' => 'btn', 'name' => 'appearanceBtn', 'id' => 'appearanceBtn')); ?>
                            <div id="preloader"></div>
                        </div>

                    </div>
                </div>

                <!--ФАЙЛЫ И ВРЕМЯ -->

                <div id="filesAndTimeBlock" class="hide">
                    <div class="clear-message hide" style="margin-bottom: -35px;">
                        <div class="flex">
                            <img src="/img/trash.svg" alt="">
                            <div class="line_height_1_5"><strong>Отчистка завершена</strong><br>Неиспользуемые файлы удалены из хранилища файлов
                            </div>
                        </div>
                    </div>
                    <div class="centre_settings">
                        <div class="profile_info_block clear_fix" style="margin-top: 20px;">
                            <div class="profile_info_header_wrap">
                                <span class="profile_info_header">Файлы</span>
                            </div>
                        </div>
                        <?php
                            foreach ($settingsSearch->data as $setting) {
                                ?>
                                <div class="settings_common_record">
                                    <?php
                                    if ($setting->param == 'timeZone') {
                                        $settingTimeZone = $setting;
                                    } else {
                                        ?>
                                        <label> <? echo Settings::model()->getNameParam($setting->param) ?>:<span class="star">*</span></label>
                                        <?
                                        echo $form->textField($setting, 'value', array('name' => 'Settings[' . $setting->param . '][value]', 'class' => 'form-control', 'placeholder' => 'Наименование'));
                                        echo $form->error($setting, 'value', array('class' => 'form-error'));
                                    }
                                    ?>
                                </div>
                                <?
                            }
                            ?>
                        <div class="profile_info_block clear_fix">
                            <div class="profile_info_header_wrap">
                                <span class="profile_info_header">Часовой пояс</span>
                            </div>
                        </div>

                        <div class="flex">
                            <div class="settings_common_record">
                                <label> <? echo Settings::model()->getNameParam($settingTimeZone->param) ?>:</label>
                                <?php echo $form->dropDownList($settingTimeZone, 'value', $timeZoneList, array('name' => 'Settings[' . $settingTimeZone->param . '][value]', 'class' => 'styled timeZone-selector', 'data-placeholder' => '')); ?>
                            </div>
                        </div>
                        <div class="operation-button">
                            <?php echo CHtml::submitButton('Сохранить', array('class' => 'btn', 'name' => 'settingsBtn', 'id' => 'settingsBtn')); ?>
                            <div id="preloader2"></div>

                        </div>
                        <div style="margin-top: 15px;">
                            <? foreach ($errors as $key => $errorsParam) {
                                foreach ($errorsParam as $error) {
                                    echo "<div class='form-error'>$error</div>";
                                }
                            } ?>
                        </div>
                    </div>

                    <?php $this->endWidget(); ?>

                    <div class="settings_foot">

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="appearanceReference" class="right-sidebar">
            <div class="title_name_2">Информация
                <div class="more"><img src="/img/external-link-symbol.svg"><a href="https://inclient.ru/category/help-crm/" target="_blank" style="color: #707070;">Подробнее</a>
                </div>
            </div>
            <div class="popup__form_actions">
                <div>
                    <p><strong>Хостинг для срм</strong></p>
                    <br>
                    <p>Надежный и быстрый хостинг для срм системы от Хостланд. Проверено, ошибок нет. </p>

                </div>
            </div>
            <div class="banner">
                <div class="banner-content">
                    <h1>Хостинг Hostland</h1>
                    <p>30 дней бесплатно, сайты без ограничений</p>
                    <a class="banner-button" href="https://www.hostland.ru/?r=7123f00e" target="_blank">Подробнее</a>
                </div>
                <a href="https://www.hostland.ru/?r=7123f00e" target="_blank"><img src="/img/mod.png"></a>
            </div>
        </div>

        <div id="settingsReference" class="right-sidebar">
            <div class="title_name_2">Справка
                <div class="more"><img src="/img/external-link-symbol.svg"><a href="https://inclient.ru/category/help-crm/" target="_blank" style="color: #707070;">Подробнее</a>
                </div>
            </div>
            <div class="popup__form_actions">
                <div>
                    <p><strong>О файлах</strong></p><br>
                    <div class="solid_an_client">
                        <p><strong>Размер файла</strong> - вес файла в мегабайтах (MB). Размер файла не должен превышать допустимый размер файла, установленный на хостинге (сервере).</p>
                    </div>
                    <div class="solid_an_client">
                        <p><strong>Расширение файла</strong> - тип файла. Например: png, jpg, svg, rar, zip, pdf, docx, xlsx, html и другие. Расширение указывается через запятую.</p>
                    </div>
                    <p><strong>Чистка хранилища</strong></p><br>
                    <p>Автоматическая очистка срм от неиспользуемых файлов.</p><br>
                    <button class="btn" id='clearFiles' onclick="clearFiles()">Очистить хранилище</button>
                </div>
            </div>
        </div>
</main>

<script>
    $("#edit-common").submit(function () {
        $("#preloader").addClass('preloader');
        $("#preloader2").addClass('preloader');
        $("#appearanceBtn").hide();
        $("#settingsBtn").hide();
    });

    var tabActive = $('#ul-listTabs li.active');
    $('#' + tabActive[0].id + 'Block').removeClass('hide');
    console.log(tabActive[0].id);

    switch (tabActive[0].id) {
        case 'appearance': {
            $('#appearanceReference').show();
            $('#settingsReference').hide();
            break;
        }
        case 'filesAndTime': {
            $('#settingsReference').show();
            $('#appearanceReference').hide();
            break;
        }
    }

    $("#fakeButton").click(function () {
        $("#loadImage").click();
    });

    $("#downloadCustomImage").click(function () {
        $("#customImage").click();
    });

    const fileTypes = ['image/jpeg', 'image/svg+xml', 'image/png'];
    const logoDownloadElem = document.getElementById('Appearance_logo');
    const logoVisualElem = document.getElementById('logoVisual');
    const customImageDownloadElem = document.getElementById('Appearance_customImageValue');
    const customImageVisualElem = document.getElementById('customImageVisual');
    const customBlockHeaderElem = document.getElementById('customBlockHeader');
    const url = window.URL || window.webkitURL;
    let countLinks = <? echo count($appearanceLinks) + count($newLinks) ?> || 0;
    let logoCurrentPath = <?echo '"' . $appearance->logo . '"'?>;

    // обработка загрузки лого
    document.getElementById('loadImage').onchange = function () {
        let file = {};

        if (file = this.files[0]) {
            if (fileTypes.includes(file.type)) {
                const img = new Image();
                img.src = url.createObjectURL(file);
                img.onload = function () {
                    if ((this.width > 260 || this.width < 260) && (this.height > 100 || this.height < 100) && (file.type !== 'image/svg+xml')) {
                        logoDownloadElem.value = "";
                        logoVisualElem.src = logoCurrentPath;
                        alert('Ошибка. Загрузите логотип в разрешении: 260*100px');
                    } else {
                        logoDownloadElem.value = img.src;
                        logoVisualElem.src = img.src;
                        document.getElementById('logoDeleteId').style.display = 'none';
                    }
                };
            } else {
                logoDownloadElem.value = "";
                logoVisualElem.src = logoCurrentPath;
                alert('Неккоректное тип файла')
            }
        }
    };

    function logoDelete() {
        logoVisualElem.src = <?echo '"' . $logoPathDefault . '"'?>;
        logoDownloadElem.value = logoVisualElem.src;
        document.getElementById('logoDeleteId').style.display = 'none';
    }

    // обработка загрузки лого
    document.getElementById('customImage').onchange = function () {
        let file = {};
        let customImageDownloadElem = document.getElementById('Appearance_customImageValue');

        if (file = this.files[0]) {
            if (fileTypes.includes(file.type)) {
                const img = new Image();
                img.src = url.createObjectURL(file);
                customImageVisualElem.src = img.src;
                customImageDownloadElem.value = img.src;
                document.getElementById('customImageDeleteId').style.display = 'none';
            } else {
                let myClone = customImageDownloadElem.cloneNode(true);
                customImageDownloadElem.remove();
                customBlockHeaderElem.appendChild(myClone);
                //customImageVisualElem.src = logoCurrentPath;
                alert('Неккоректное тип файла');
            }
        }
    };

    function customImageDelete() {
        customImageVisualElem.src = <?echo '"' . $backgroundImagePathDefault . '"'?>;
        customImageDownloadElem.value = '';
        document.getElementById('customImageDeleteId').style.display = 'none';
    }

    function addLink() {
        countLinks++;

        const appearanceLinksBlockNode = document.getElementById('appearanceLinksBlock');
        const appearanceLinksRowDiv = document.createElement('div');
        appearanceLinksRowDiv.classList.add('appearance_links_row');

        const linkNameDiv = document.createElement('div');
        linkNameDiv.classList.add('settings_appearance_record');

        const linkNameInput = document.createElement('input');
        linkNameInput.classList.add('form-control');
        linkNameInput.name = 'AppearanceLinks[new][' + countLinks + '][link_name]';

        const linkValueDiv = document.createElement('div');
        linkValueDiv.classList.add('settings_appearance_record');

        const linkValueInput = document.createElement('input');
        linkValueInput.classList.add('form-control');
        linkValueInput.name = 'AppearanceLinks[new][' + countLinks + '][link_value]';

        const deleteBtn = document.createElement('div');
        deleteBtn.classList.add('row-input');
        deleteBtn.classList.add('align_center');

        const deleteImg = document.createElement('img');
        deleteImg.classList.add('delDocument_set');
        deleteImg.src = "/img/cancel_newdoc.svg";
        deleteImg.onclick = deleteLink;

        deleteBtn.appendChild(deleteImg);

        linkNameDiv.appendChild(linkNameInput);
        linkValueDiv.appendChild(linkValueInput);

        appearanceLinksRowDiv.appendChild(linkNameDiv);
        appearanceLinksRowDiv.appendChild(linkValueDiv);
        appearanceLinksRowDiv.appendChild(deleteBtn);

        appearanceLinksBlockNode.appendChild(appearanceLinksRowDiv);
    }

    function deleteLink(event) {
        event.target.closest('.appearance_links_row').remove();
    }

    function clearFiles() {
        event.preventDefault();
        $.get('/page/ClearFiles').done(function (result) {
            result = result ? JSON.parse(result) : {};
            if (result.status == 'success') {
                $('.clear-message')[0].style.display = 'flex';
                $('.save-message').hide();
                $('.gud').hide();
            } else {
                $('.clear-message').hide();
                $('.save-message').hide();
                alert(result['error']);
            }
        });
    }

    changeTabs = function (tab) {
        tabActive.removeClass('active');
        $('#' + tabActive[0].id + 'Block').addClass('hide');

        tabActive = $('#' + tab);
        tabActive.addClass('active');
        $('#' + tabActive[0].id + 'Block').removeClass('hide');

        switch (tab) {
            case 'appearance': {
                $('#appearanceReference').show();
                $('#settingsReference').hide();
                break;
            }
            case 'filesAndTime': {
                $('#settingsReference').show();
                $('#appearanceReference').hide();
                break;
            }
        }
    };

    changeRadioActive = function (radioId) {
        $('input:radio').prop('checked', false);
        $('#' + radioId).prop("checked", true);

        let typeLink = $('#backgroundTypeLink')[0];
        let typeImage = $('#backgroundTypeImage')[0];

        if (radioId === 'link_radio') {
            typeLink.style.display = 'block';
            typeImage.style.display = 'none';

        } else if (radioId === 'image_radio') {
            typeImage.style.display = 'flex';
            typeLink.style.display = 'none';
        } else if (radioId === 'rotate_radio') {
            typeImage.style.display = 'none';
            typeLink.style.display = 'none';
        } else {
            typeImage.style.display = 'none';
            typeLink.style.display = 'none';

            let gradientBlockElem = document.getElementById(radioId + '_block');
            let gradientInputElem = document.getElementById('gradientColorId');
            gradientInputElem.value = gradientBlockElem.style.background;
        }
    };
</script>
