<?php
require_once($GLOBALS['g_campsiteDir'].'/classes/Input.php');
require_once($GLOBALS['g_campsiteDir'].'/classes/Attachment.php');
require_once($GLOBALS['g_campsiteDir'].'/classes/Log.php');

use Newscoop\Entity\Translation;

$translator = \Zend_Registry::get('container')->getService('translator');

if (!SecurityToken::isValid()) {
    camp_html_display_error($translator->trans('Invalid security token!'));
    exit;
}

// check input
$f_attachment_id = Input::Get('f_attachment_id', 'int', 0);
$f_description = Input::Get('f_description', 'string', '');
$f_content_disposition = Input::Get('f_content_disposition', 'string', '');
if (!Input::IsValid() || ($f_attachment_id <= 0)) {
	camp_html_goto_page("/$ADMIN/media-archive/index.php#files");
}

$em = \Zend_Registry::get('container')->getService('em');
$attachment = $em->getRepository('Newscoop\Entity\Attachment')->findOneById($f_attachment_id);
$description = new Translation();
$description->setLanguage($attachment->getLanguage());
$description->setTranslationText($f_description);
$em->persist($description);
$attachment->setDescription($description);
$attachment->setUpdated(new \DateTime());
$attachment->setContentDisposition($f_content_disposition);

$em->flush();

camp_html_add_msg($translator->trans('Attachment updated.', array(), 'media_archive'), 'ok');
camp_html_goto_page("/$ADMIN/media-archive/edit-attachment.php?f_attachment_id=".$attachment->getId());
