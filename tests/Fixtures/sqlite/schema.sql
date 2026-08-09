DROP TABLE IF EXISTS users;

CREATE TABLE users
(
    id         INTEGER PRIMARY KEY,
    name       VARCHAR(255) NOT NULL,
    email      VARCHAR(255) NOT NULL,
    active     BOOLEAN      NOT NULL,
    nickname   VARCHAR(255) NULL,
    created_at DATETIME     NOT NULL
);

INSERT INTO users (id,
                   name,
                   email,
                   active,
                   nickname,
                   created_at)
VALUES (1,
        'John Doe',
        'john@example.com',
        1,
        NULL,
        '2026-08-08 12:00:00'),
       (2,
        'Jane Doe',
        'jane@example.com',
        0,
        'Jane',
        '2026-08-08 13:00:00');
