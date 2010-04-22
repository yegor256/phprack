<?php
require PHPRACK_PATH . '/Mail/Transport/Smtp.php';

require PHPRACK_PATH . '/Mail/Transport/Sendmail.php';

class phpRack_Mail
{
    private function __construct()
    {
        /* closing */
    }

    public static function factory(array $params)
    {
        if (is_array($params['smtp']) && count($params['smtp'])) {
            return new phpRack_Mail_Transport_Smtp($params);
        }
        return new phpRack_Mail_Transport_Sendmail($params);
    }
}
