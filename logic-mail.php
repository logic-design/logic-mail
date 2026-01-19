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
    private $name  = 'Logic_ Mail Notifications';
    private $email = 'noreply@logic-mail.co.uk';

    public function __construct()
    {
        add_filter('wp_mail_from', [$this, 'filter_mail_from']);
        add_filter('wp_mail_from_name', [$this, 'filter_mail_from_name']);
        add_action('phpmailer_init', [$this, 'action_phpmailer_init']);
        add_filter('wpcf7_mail_components', [$this, 'filter_wpcf7_mail_components'], 10, 2);

        // WPForms
        add_filter('wpforms_email_sender_address', [$this, 'filter_wpforms_email_sender_address']);
        add_filter('wpforms_email_sender_name', [$this, 'filter_wpforms_email_sender_name']);
        add_filter('wpforms_email_headers', [$this, 'filter_wpforms_email_headers']);

        // Gravity Forms
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
        $existing = isset($components['additional_headers']) ? $components['additional_headers'] : '';

        // Remove any existing From: header
        $existing = preg_replace('/^From:.*$/m', '', $existing);

        // Trim whitespace and ensure proper line ending
        $existing = trim($existing);
        if (!empty($existing)) {
            $existing .= "\r\n";
        }

        // Add our From header to the existing headers
        $components['additional_headers'] = $existing . "From: {$this->name} <{$this->email}>\r\n";

        return $components;
    }

    // -----------------------------
    // WPForms
    // -----------------------------

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
        // Remove existing From header
        $headers = preg_replace('/^From:.*$/m', '', $headers);
        $headers .= "From: {$this->name} <{$this->email}>\r\n";
        return $headers;
    }

    // -----------------------------
    // Gravity Forms (FIXED)
    // -----------------------------

    public function filter_gform_pre_send_email($email)
    {
        $email['from']     = $this->email;
        $email['fromName'] = $this->name;

        $fromHeader = "From: {$email['fromName']} <{$email['from']}>\r\n";

        // Handle headers as string or array
        if (isset($email['headers']) && is_array($email['headers'])) {

            // Remove existing From:
            $email['headers'] = array_filter($email['headers'], function ($h) {
                return stripos($h, 'From:') !== 0;
            });

            $email['headers'][] = $fromHeader;
        } else {

            // Treat as string
            $existing = $email['headers'] ?? '';

            // Remove any previous From:
            $existing = preg_replace('/^From:.*$/m', '', $existing);

            $email['headers'] = $existing . $fromHeader;
        }

        return $email;
    }
}

new Logic_Mail();
