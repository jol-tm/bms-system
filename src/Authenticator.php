<?php

class Authenticator
{
    private ?object $connection = null;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function authenticate(string $table, array $email, array $password): bool
    {
        try
        {
            $emailKey = array_keys($email)[0];
            $passwordKey = array_keys($password)[0];

            $sql = "SELECT $passwordKey FROM $table WHERE $emailKey = :$emailKey";

            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(":email", $email[$emailKey], PDO::PARAM_STR);

            $stmt->execute();

            $hash = $stmt->fetchColumn();

            if (password_verify($password[$passwordKey], $hash))
            {
                session_regenerate_id(true);
                $_SESSION['loggedUser'] = $email[$emailKey];

                return true;
            }

            return false;
        }
        catch (PDOException $e)
        {
            error_log("\n\n" . date("Y-m-d H:i:s") . " | " . $e, 3, "./errors.log");
            return false;
        }
    }

    public function disconnect(): bool
    {
        $_SESSION = [];
        return session_destroy();
    }
}