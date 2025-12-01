<?php
/*
Plugin Name: Logic Mail
Description: Custom logic for handling mail in WordPress.
Version: 1.0.0
Author: Logic Design & Consultancy Ltd
Author URI: https://www.logicdesign.co.uk/
*/

class Logic_Mail
{
	private $name = 'Logic_ Mail Notifications';
	private $email = 'noreply@logic-mail.co.uk';

	public function __construct()
	{
		add_filter('wp_mail_from', [$this, 'filter_mail_from']);
		add_filter('wp_mail_from_name', [$this, 'filter_mail_from_name']);
		add_action('phpmailer_init', [$this, 'action_phpmailer_init']);
		add_filter('wpcf7_mail_components', [$this, 'filter_wpcf7_mail_components'], 10, 2);
		add_filter('wpforms_email_sender_address', [$this, 'filter_wpforms_email_sender_address']);
		add_filter('wpforms_email_sender_name', [$this, 'filter_wpforms_email_sender_name']);
		add_filter('wpforms_email_headers', [$this, 'filter_wpforms_email_headers']);
		add_filter('gform_pre_send_email', [$this, 'filter_gform_pre_send_email']);
	}

	public function filter_mail_from($original_email_address)
	{
		return $this->email;
	}

	public function filter_mail_from_name($original_email_from)
	{
		return $this->name;
	}

	public function action_phpmailer_init($phpmailer)
	{
		$phpmailer->setFrom($this->email, $this->name, false);
	}

	public function filter_wpcf7_mail_components($components, $contactForm)
	{
		$components['additional_headers'] = "From: {$this->name} <{$this->email}>\r\n";
		return $components;
	}

	public function filter_wpforms_email_sender_address($sender)
	{
		return $this->email;
	}

	public function filter_wpforms_email_sender_name($name)
	{
		return $this->name;
	}

	public function filter_wpforms_email_headers($headers)
	{
		$headers = preg_replace('/^From:.*$/m', '', $headers);
		$headers .= "From: {$this->name} <{$this->email}>\r\n";
		return $headers;
	}

	public function filter_gform_pre_send_email($email)
	{
		$email['from']     = $this->email;
		$email['fromName'] = $this->name;
		$email['headers']  = "From: {$email['fromName']} <{$email['from']}>\r\n";
		return $email;
	}
}

new Logic_Mail();
