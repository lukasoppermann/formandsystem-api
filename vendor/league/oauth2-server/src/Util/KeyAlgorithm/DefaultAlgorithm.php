<?php
/**
 * OAuth 2.0 Secure key interface
 *
 * @package     league/oauth2-server
 * @author      Alex Bilbie <hello@alexbilbie.com>
 * @copyright   Copyright (c) Alex Bilbie
 * @license     http://mit-license.org/
 * @link        https://github.com/thephpleague/oauth2-server
 */

namespace League\OAuth2\Server\Util\KeyAlgorithm;

class DefaultAlgorithm implements KeyAlgorithmInterface
{
    /**
     * {@inheritdoc}
     */
    public function generate($len = 40)
    {
        // We generate twice as many bytes here because we want to ensure we have
        // enough after we base64 encode it to get the length we need because we
        // take out the "/", "+", and "=" characters.
        $bytes = openssl_random_pseudo_bytes($len * 2, $strong);

        // We want to stop execution if the key fails because, well, that is bad.
        if ($bytes === false || $strong === false) {
            // @codeCoverageIgnoreStart
            throw new \Exception('Error Generating Key');
            // @codeCoverageIgnoreEnd
        }

        return substr(str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, $len);
    }
}
