<?php
/* For licensing terms, see /license.txt */

/**
 *  @package chamilo.admin
 */
$cidReset = true;

require_once __DIR__.'/../inc/global.inc.php';

$this_section = SECTION_PLATFORM_ADMIN;

api_protect_admin_script(false, true);

if (api_get_setting('allow_skills_tool') != 'true') {
    api_not_allowed();
}

//Adds the JS needed to use the jqgrid
$htmlHeadXtra[] = api_get_asset('d3/d3.js');
$htmlHeadXtra[] = api_get_asset('colorbrewer/colorbrewer.js');
$htmlHeadXtra[] = api_get_asset('xcolor/jquery.xcolor.js');

$tpl = new Template(null, false, false);

$load_user = 0;
if (isset($_GET['load_user'])) {
    $load_user = 1;
}

$skill_condition = '';
if (isset($_GET['skill_id'])) {
    $skill_condition = '&skill_id='.intval($_GET['skill_id']);
    $tpl->assign('skill_id_to_load', $_GET['skill_id']);
}

$url = api_get_path(WEB_AJAX_PATH)."skill.ajax.php?a=get_skills_tree_json&load_user=$load_user";
$tpl->assign('wheel_url', $url);

$url  = api_get_path(WEB_AJAX_PATH).'skill.ajax.php?1=1';
$tpl->assign('url', $url);
$tpl->assign('isAdministration', true);

$dialogForm = new FormValidator('form', 'post', null, null, ['id' => 'add_item']);
$dialogForm->addLabel(
    get_lang('Name'),
    Display::tag('p', null, ['id' => 'name', 'class' => 'form-control-static'])
);
$dialogForm->addLabel(
    get_lang('ShortCode'),
    Display::tag('p', null, ['id' => 'short_code', 'class' => 'form-control-static'])
);
$dialogForm->addLabel(
    get_lang('Parent'),
    Display::tag('p', null, ['id' => 'parent', 'class' => 'form-control-static'])
);
$dialogForm->addLabel(
    [get_lang('Gradebook'), get_lang('WithCertificate')],
    Display::tag('ul', null, ['id' => 'gradebook', 'class' => 'form-control-static list-unstyled'])
);
$dialogForm->addLabel(
    get_lang('Description'),
    Display::tag('p', null, ['id' => 'description', 'class' => 'form-control-static'])
);

$tpl->assign('dialogForm', $dialogForm->returnForm());

$saveProfileForm = new FormValidator('form', 'post', null, null, ['id' => 'dialog-form-profile']);
$saveProfileForm->addHidden('profile_id', null);
$saveProfileForm->addText('name', get_lang('Name'), true, ['id' => 'name_profile']);
$saveProfileForm->addTextarea('description', get_lang('Description'), ['id' => 'description_profile', 'rows' => 6]);
$tpl->assign('saveProfileForm', $saveProfileForm->returnForm());
$tpl->assign('skill_id_to_load', 0);
$templateName = $tpl->get_template('skill/skill_wheel.tpl');
$content = $tpl->fetch($templateName);
$tpl->assign('content', $content);
$tpl->display_no_layout_template();
