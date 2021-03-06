<?php declare(strict_types = 1);
/**
 * User: Hannes
 * Date: 27.11.2017
 * Time: 20:02
 */

namespace App\SparkPlug\Auth;

use App\Models\User;
use App\SparkPlug\Database\DBAccessInterface;
use App\SparkPlug\Session;

/**
 * Class Auth
 */
class Auth
{
    /** @var \PDO */
    private $db;
    /** @var Session */
    private $session;
    /** @var \App\Models\User */
    private $user;

    /**
     * Auth constructor.
     *
     * @param \App\SparkPlug\Database\DBAccessInterface $DBAccess
     */
    public function __construct(DBAccessInterface $DBAccess)
    {
        $this->db = $DBAccess->getDB();
        $this->session = app()->make(Session::class);
        $this->reloadUser();
    }

    /**
     * @param array $credentials
     *
     * @return bool
     */
    public function attempt(array $credentials): bool
    {
        if (!is_array($credentials) || !isset($credentials['username']) || !isset($credentials['password'])) {
            throw new \InvalidArgumentException('username or password not set');
        }

        $statement = $this->db->prepare('SELECT * FROM users WHERE username LIKE ? LIMIT 1');
        if (!$statement->execute([$credentials['username']])) {
            return false;
        }

        $user = $statement->fetch();

        if (false === $user) {
            return false;
        }

        if (!password_verify($credentials['password'], $user['password'])) {
            return false;
        }

        $this->user = new User($user);
        $this->session->user = $user;

        return true;
    }

    /**
     * @return \App\Models\User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * Check if User is logged in
     *
     * @return bool
     */
    public function check(): bool
    {
        $this->reloadUser();

        return $this->user !== null;
    }

    /**
     * Lädt User aus Session in Klasse zurück
     */
    private function reloadUser()
    {
        if (!is_null($this->session->user) && is_null($this->user)) {
            $this->user = new User($this->session->user);
        }
    }
}
