<?php
/**
 * Contract for classes concerning message creation
 *
 * @author Dusan Mitrovic <dusan@dusanmitrovic.xyz>
 * @licence https://opensource.org/licenses/GPL-3.0 GNU General Public License, version 3
 */
namespace App\Interfaces;

use Swift_Message;

interface MessageFactoryInterface
{
    public const VERIFICATION_MESSAGE = 'verification';
    public const RESET_MESSAGE = 'reset';

    public function produce(string $subject, string $from, string $to, string $contentType = null, array $options): Swift_Message;
}
