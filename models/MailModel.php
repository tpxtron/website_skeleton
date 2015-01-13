<?php

/**
 * Mailer Class
 * -------------
 *
 * Can be used for all mailings, automatically renders the given templates
 *
 */


class MailModel {

	private $_mailer;
	private $_twig;
	private $_db;

	// TODO: Change sender details for emails
	const FROM_EMAIL = "noreply@example.com";
	const FROM_NAME = "example";

	public function __construct()
	{
		$this->_mailer = new PHPMailer;

		$loader = new Twig_Loader_Filesystem(dirname(__FILE__)."/../templates/");
		$this->_twig = new Twig_Environment($loader);
		$this->_db = new db();
	}

	public function sendMail($template, $subject, $email, $variables) {
		$bodyHtml = $this->_twig->render('_mail/'.$template.'.html.twig',$variables);
		$bodyText = $this->_twig->render('_mail/'.$template.'.text.twig',$variables);

		$this->_send($email,$subject,$bodyHtml,$bodyText);
	}

	private function _send($to,$subject,$html,$text)
	{
		$this->_mailer->From = $this::FROM_EMAIL;
		$this->_mailer->FromName = $this::FROM_NAME;
		$this->_mailer->addAddress($to);
		$this->_mailer->Subject = $subject;
		$this->_mailer->isHTML(true);
		$this->_mailer->CharSet = 'utf-8';
		$this->_mailer->Body = $html;
		$this->_mailer->AltBody = $text;

		$this->_mailer->send();
	}

}
