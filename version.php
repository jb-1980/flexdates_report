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
 * Version details
 *
 * @package    block_flexdates_report
 * @copyright  1999 onwards Martin Dougiamas (http://dougiamas.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$plugin->version   = 2015040900;        // The current plugin version (Date: YYYYMMDDXX)
$plugin->requires  = 2012112900;        // Requires this Moodle version
$plugin->component = 'block_flexdates_report'; // Full name of the plugin (used for diagnostics)
$plugin->cron = 0;
$plugin->maturity = MATURITY_ALPHA;
$plugin->release = 'v1.0';
$plugin->dependencies = array(
    'local_flexdates'=>ANY_VERSION
);
