# Logic Mail – MU Plugin

Logic Mail is a WordPress must-use (MU) plugin that overrides the default email sender name and address used by WordPress.

## Installation

Install the plugin directly into the MU plugins directory:

```bash
mkdir -p wp-content/mu-plugins \
&& cd wp-content/mu-plugins \
&& wget https://raw.githubusercontent.com/logic-design/logic-mail/refs/heads/main/logic-mail.php
```

Once the file is in `wp-content/mu-plugins/`, it loads automatically and requires no activation.

## Included File

**logic-mail.php**
Contains the `Logic_Mail` class, which sets the default "from" name, "from" email address, and applies PHPMailer configuration before emails are sent.

## Usage

No configuration is required.
To customise behaviour, edit the values inside `logic-mail.php` as needed.

## License

Maintained by Logic Design & Consultancy Ltd.
