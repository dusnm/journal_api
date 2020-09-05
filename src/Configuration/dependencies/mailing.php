<?php
/**
 * Application dependency
 *
 * @author Dusan Mitrovic <dusan@dusanmitrovic.xyz>
 * @licence https://opensource.org/licenses/GPL-3.0 GNU General Public License, version 3
 */
use function App\Helpers\env;
use function DI\create;

$smtpTransport = new Swift_SmtpTransport(
    env('MAILER_HOST'),
    env('MAILER_PORT'),
    env('MAILER_ENCRYPTION')
);

$smtpTransport->setUsername(env('MAILER_USERNAME'));
$smtpTransport->setPassword(env('MAILER_PASSWORD'));

return [
    Swift_SmtpTransport::class => $smtpTransport,
    Swift_Mailer::class => create(Swift_Mailer::class)->constructor($smtpTransport),
];
