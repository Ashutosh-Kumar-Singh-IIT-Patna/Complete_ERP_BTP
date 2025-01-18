CREATE TABLE log_queries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    log_time DATETIME NOT NULL,
    message TEXT NOT NULL,
    query TEXT,
    is_error BOOLEAN NOT NULL DEFAULT 0
);
