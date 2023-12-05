<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * @package   local_onlineeduru
 * @copyright 2023, Yuriy Yurinskiy <yuriyyurinskiy@yandex.ru>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use local_onlineeduru\helper;

global $CFG, $DB, $OUTPUT, $PAGE, $SITE;

require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/adminlib.php');

$page = optional_param('page', 0,  PARAM_INT);

$systemcontext = $context = context_system::instance();

$PAGE->set_context($context);
$PAGE->set_url('/local/onlineeduru/index.php'); // Defined here to avoid notices on errors etc.
$PAGE->set_pagelayout('admin');
$PAGE->set_title(get_string('pluginname', 'local_onlineeduru'));
$PAGE->set_heading(format_string($SITE->fullname, true, ['context' => $systemcontext]));

/** Проверяем авторизован ли пользователь */
require_login();

/** Проверяем права пользователя */
if (!is_siteadmin() && !has_capability('local/onlineeduru:view', $context)) {
    header('Location: ' . $CFG->wwwroot);
    die();
}

echo $OUTPUT->header();

$params = array();
$params['page'] = $page;

$baseurl = new moodle_url('/local/onlineeduru/index.php', $params);

$renderer = $PAGE->get_renderer('local_onlineeduru');

echo $renderer->courses_table([]);

if (has_capability('local/onlineeduru:manager', $context)) {
    echo $renderer->single_button(helper::get_create_passport_url(), get_string('createnewcourse', 'local_onlineeduru'));
}

echo $OUTPUT->footer();
