<?php
/**
 * パスワードハッシュの機能を提供するクラス
 * include('libs/cl_password.php');
$p = new Password();
$hash = $p->hash('password');
// ハッシュの比較
//$p->verify('password', $hash);
 *
 */
class Password
{
    const MODE_INTERNAL = 'internal';
    const MODE_HIGH = '$2y$';
    const MODE_NORMAL = '$2a$';
    const MODE_LOW = '$1$';

    private $mode;
    private $cost = 10;

    public function __construct()
    {
        if (function_exists('password_hash')) {
            $this->mode = self::MODE_INTERNAL;
        } else if (defined('CRYPT_BLOWFISH') && CRYPT_BLOWFISH === 1) {
            if (version_compare(PHP_VERSION, '5.3.7', '>=')) {
                $this->mode = self::MODE_HIGH;
            } else {
                $this->mode = self::MODE_NORMAL;
            }
        } else {
            $this->mode = self::MODE_LOW;
        }
    }

    public function setCost($cost)
    {
        if (is_int($cost)) {
            $this->cost = $cost;
        } else {
            throw new \InvalidArgumentException(__METHOD__ . 'only accepts integers. Input was: ' . $cost);
        }
    }

    public function setMode($mode)
    {
        switch (strtolower($mode)) {
            case self::MODE_INTERNAL:
                if (function_exists('password_hash')) {
                    $this->mode = $mode;
                } else {
                    throw new \RuntimeException('Not declared function "password_hash"');
                }
                break;
            case self::MODE_HIGH:
            case self::MODE_NORMAL:
            case self::MODE_LOW:
                $this->mode = $mode;
                break;
            default:
                throw new \InvalidArgumentException('Not found crypt mode "' . $mode . '"');
        }
    }

    private function random($length)
    {
        return substr(strtr(base64_encode(hash('sha256', mt_rand())), '+', '.'), 0, $length);
    }

    public function hash($password)
    {
        switch ($this->mode) {
            case self::MODE_INTERNAL:
                return password_hash($password, PASSWORD_DEFAULT, array('cost' => $this->cost));
                break;
            case self::MODE_HIGH:
            case self::MODE_NORMAL:
                $cost = substr(str_pad($this->cost, 2, '0', STR_PAD_LEFT), -2);
                $salt = $this->mode . $cost . '$' . $this->random(21);
                return crypt($password, $salt);
                break;
            case self::MODE_LOW:
                $salt = $this->mode . $this->random(8);
                return crypt($password, $salt);
                break;
        }
    }

    public function verify($password, $hash)
    {
        switch ($this->mode) {
            case self::MODE_INTERNAL:
                return password_verify($password, $hash);
                break;
            case self::MODE_HIGH:
            case self::MODE_NORMAL:
                if (preg_match('/^(\$2[ay]\$)(\d+)\$(.{21})/', $hash, $matches)) {
                    $mode = $matches[1];
                    $cost = substr(str_pad(intval($matches[2]), 2, '0', STR_PAD_LEFT), -2);
                    $salt = $matches[3];
                    $checkSalt = $mode . $cost . '$' . $salt;
                    $checkHash = crypt($password, $checkSalt);

                    return ($hash === $checkHash);
                }
                return false;
                break;
            case self::MODE_LOW:
                if (preg_match('/^(\$1\$)(.{8})/', $hash, $matches)) {
                    $mode = $matches[1];
                    $salt = $matches[2];
                    $checkSalt = $mode . $salt;
                    $checkHash = crypt($password, $checkSalt);

                    return ($hash === $checkHash);
                }
                return false;
                break;
        }
    }

    public function isNeedRehash($hash)
    {
        // チェック（internalの場合)
        if ($this->mode === self::MODE_INTERNAL) {
            return password_needs_rehash($hash, PASSWORD_DEFAULT, array('cost' => $this->cost));
        }

        // アルゴリズムのチェック
        if ($this->mode === self::MODE_LOW && substr($hash,0, 3) !== self::MODE_LOW) {
            // MD5ハッシュだが、他のハッシュ方式になっている。
            return true;
        }
        if ($this->mode === self::MODE_NORMAL && substr($hash,0, 4) !== self::MODE_NORMAL) {
            // Blowfishハッシュだが、他のハッシュ方式になっている。
            return true;
        }
        if ($this->mode === self::MODE_HIGH && substr($hash,0, 4) !== self::MODE_HIGH) {
            // Blowfishハッシュだが、他のハッシュ方式になっている。
            return true;
        }

        // コストのチェック
        switch ($this->mode) {
            case self::MODE_NORMAL:
            case self::MODE_HIGH:
                $cost = intval(substr($hash, 4, 2));
                return ($this->cost !== $cost);
                break;
        }

        return false;
    }
}