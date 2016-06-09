<?php
/*
	Plugin Name: Seasonal questions
	Plugin URI:
	Plugin Description: seasonal questions
	Plugin Version: 0.3
	Plugin Date: 2015-06-21
	Plugin Author:
	Plugin Author URI:
	Plugin License: GPLv2
	Plugin Minimum Question2Answer Version: 1.7
	Plugin Update Check URI:
*/
if (!defined('QA_VERSION')) {
	header('Location: ../../');
	exit;
}

qa_register_plugin_module('widget', 'qa-seasonal-question-widget.php', 'qa_seasonal_question_widget', 'seasonal questions');

require_once QA_INCLUDE_DIR.'db/selects.php';

function getHotQuestions() {

	$voteuserid = 0;
	$sort='hotness';
	$start=0;
	$categoryslugs=null;
	$createip=null;
	$specialtype='Q';
	$full=false;
	$count=6;
	$selectspec=qa_db_qs_selectspec($voteuserid, $sort, $start, $categoryslugs, $createip, $specialtype, $full, $count);
	$questions=qa_db_single_select($selectspec);
	return $questions;
}

function getSeasonalQuestions() {
	// seasonal
	$month = date("m");
	$day= date("j");
	$day = floor($day/10);
	if($day == 3) {
		$day  = 2;
	}
	$date = '%-' . $month . '-' . $day . '%';

	$userid = '1';
	$selectspec=qa_db_posts_basic_selectspec($userid);
	$selectspec['source'] .=" WHERE type='Q'";
	$selectspec['source'] .= " AND ^posts.created like '" . $date . "' ORDER BY RAND() LIMIT 6";
	$questions=qa_db_single_select($selectspec);
	return $questions;
}

function getWeekQuestions() {
	$month = date("m");

	$day= date("j");
	$day = floor($day/10);
	if($day == 3) {
		$day  = 2;
	}
	$date = '%-' . $month . '-' . $day . '%';


	$sql = "select * ";
	$sql .= "from qa_posts where type='Q' AND ";
	$sql .= " created like '" . $date . "' order by views desc";

	$result = qa_db_query_sub($sql);
	return qa_db_read_all_assoc($result);
}
