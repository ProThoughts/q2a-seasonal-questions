<?php
if (!defined('QA_VERSION')) { 
	require_once dirname(empty($_SERVER['SCRIPT_FILENAME']) ? __FILE__ : $_SERVER['SCRIPT_FILENAME']).'/../../qa-include/qa-base.php';
   require_once QA_INCLUDE_DIR.'app/emails.php';
}

require_once QA_INCLUDE_DIR.'db/selects.php';

$questions = getWeekQuestions();
$body =  crateBody($questions);

$params['fromemail'] = qa_opt('from_email');
$params['fromname'] = qa_opt('site_title');
$params['subject'] = '【' . qa_opt('site_title') . '】この時期の過去の質問';
$params['body'] = $body;
$params['toname'] = '管理人';
$params['toemail'] = 'yuichi.shiga@gmail.com';
$params['html'] = false;


qa_send_email($params);

function crateBody($questions){
	foreach ($questions as $q) {
		$body .= '・[' . date("Y/n/j", strtotime($q['created'])) . '] ' . $q['title'] . "\n";
		$body .= qa_opt('site_url') . $q['postid'] . "\n";
		$body .= "\n";
	}
	return $body;
}

