<?php
/* For licensing terms, see /license.txt */

/**
 * Script
 * @package chamilo.gradebook
 */
require_once __DIR__.'/../inc/global.inc.php';

api_block_anonymous_users();

$eval= Evaluation :: load($_GET['selecteval']);
if ($eval[0]->get_category_id() < 0) {
    // if category id is negative, then the evaluation's origin is a link
    $link= LinkFactory :: get_evaluation_link($eval[0]->get_id());
    $currentcat = Category :: load($link->get_category_id());
} else {
    $currentcat = Category :: load($eval[0]->get_category_id());
}

$interbreadcrumb[]= array (
    'url' => $_SESSION['gradebook_dest'].'?selectcat=' . $currentcat[0]->get_id(), 'name' => get_lang('ToolGradebook'));

if (api_is_allowed_to_edit()) {
    $interbreadcrumb[]= array (
        'url' => 'gradebook_view_result.php?selecteval=' . Security::remove_XSS($_GET['selecteval']).'&'.api_get_cidreq(),
        'name' => get_lang('ViewResult')
    );
}
$displayscore = ScoreDisplay :: instance();

Display::display_header(get_lang('EvaluationStatistics'));
DisplayGradebook::display_header_result(
    $eval[0],
    $currentcat[0]->get_id(),
    0,
    'statistics'
);

//Bad, Regular, Good  - User definitions
$displays = $displayscore->get_custom_score_display_settings();

if (!$displayscore->is_custom() || empty($displays)) {
    if (api_is_platform_admin() || api_is_course_admin()) {
        Display :: display_error_message(get_lang('PleaseEnableScoringSystem'), false);
    }
} else {
    $allresults = Result::load(null,null,$eval[0]->get_id());
    $nr_items = array();
    foreach ($displays as $itemsdisplay) {
        $nr_items[$itemsdisplay['display']] = 0;
    }

    $resultcount = 0;
    foreach ($allresults as $result) {
        $score = $result->get_score();
        if (isset($score)) {
            $display = $displayscore->display_score(
                array($score, $eval[0]->get_max()),
                SCORE_CUSTOM,
                SCORE_ONLY_CUSTOM,
                true
            );
            $nr_items[$display]++;
            $resultcount++;
        }
    }

    $keys = array_keys($nr_items);

    // find the region with the most scores, this is 100% of the bar

    $highest_ratio = 0;
    foreach ($keys as $key) {
        if ($nr_items[$key] > $highest_ratio) {
            $highest_ratio = $nr_items[$key];
        }
    }

    // Generate table
    $stattable= '<table class="data_table" cellspacing="0" cellpadding="3">';
    $stattable .= '<tr><th>' . get_lang('ScoringSystem') . '</th>';
    $stattable .= '<th>' . get_lang('Percentage') . '</th>';
    $stattable .= '<th>' . get_lang('CountUsers') . '</th>';
    //$stattable .= '<th>' . get_lang('Statistics') . '</th></tr>';
    $counter=0;
    foreach ($keys as $key) {
        $bar = ($highest_ratio > 0?($nr_items[$key] / $highest_ratio) * 100:0);
        $stattable .= '<tr class="row_' . ($counter % 2 == 0 ? 'odd' : 'even') . '">';
        $stattable .= '<td width="150">' . $key . '</td>';

        $stattable .= '<td width="550">'.Display::bar_progress($bar).'</td>';
        $stattable .= '<td align="right">' . $nr_items[$key] . '</td>';
        $counter++;
    }
    $stattable .= '</tr></table>';
    echo $stattable;
}
Display :: display_footer();
