<?php
/**
 * BaseModel Class
 *
 * Provides a shared PDO database connection for all models in the MVC architecture.
 * Ensures a single database connection instance (Singleton pattern) is reused across models.
 */

class BaseModel
{
    /**
     * @var PDO|null Shared PDO instance across all inheriting models.
     */
    protected static ?PDO $pdo = null;

    /**
     * @var PDO Instance reference to the shared PDO connection for convenience.
     */
    protected PDO $db;

    /**
     * Constructor initializes the single PDO connection if not already created.
     */
    public function __construct()
    {
        if (self::$pdo === null) {
            $config = require __DIR__ . '/../config/database.php';

            $dsn = sprintf(
                'mysql:host=%s;dbname=%s;charset=%s',
                $config['host'],
                $config['dbname'],
                $config['charset']
            );

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            self::$pdo = new PDO($dsn, $config['user'], $config['password'], $options);
        }

        $this->db = self::$pdo;
    }

    /**
     * Returns the active PDO database connection instance.
     *
     * @return PDO
     */
    public function getConnection(): PDO
    {
        return $this->db;
    }
}
